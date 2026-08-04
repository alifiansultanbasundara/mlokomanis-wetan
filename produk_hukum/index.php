<?php

include "../auth/auth.php";
include "../config/database.php";

// ======================================
// Filter
// ======================================

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$jenis  = isset($_GET['jenis']) ? trim($_GET['jenis']) : "";
$tahun  = isset($_GET['tahun']) ? trim($_GET['tahun']) : "";

$where = [];

if ($search != "") {
    $where[] = "(judul LIKE '%$search%' OR nomor LIKE '%$search%')";
}

if ($jenis != "") {
    $where[] = "jenis='$jenis'";
}

if ($tahun != "") {
    $where[] = "tahun='$tahun'";
}

$whereSql = "";

if (count($where) > 0) {
    $whereSql = "WHERE " . implode(" AND ", $where);
}

// ======================================
// Ambil Data
// ======================================

$query = mysqli_query($conn, "

SELECT *

FROM produk_hukum

$whereSql

ORDER BY

tahun DESC,

tanggal_ditetapkan DESC,

created_at DESC

");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Kelola Produk Hukum</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">

                    Kelola Produk Hukum

                </h2>

                <small class="text-muted">

                    Kelola Peraturan Desa, SK, Instruksi dan dokumen hukum lainnya.

                </small>

            </div>

            <a
                href="create.php"
                class="btn btn-success">

                + Tambah Produk Hukum

            </a>

        </div>

        <?php

        if (isset($_SESSION['success'])) {

        ?>

            <div class="alert alert-success">

                <?= $_SESSION['success']; ?>

            </div>

        <?php

            unset($_SESSION['success']);
        }

        ?>

        <div class="card shadow-sm">

            <div class="card-body">

                <form method="GET" class="row g-3 mb-4">

                    <div class="col-md-5">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari nomor atau judul..."
                            value="<?= htmlspecialchars($search); ?>">

                    </div>

                    <div class="col-md-3">

                        <select
                            name="jenis"
                            class="form-select">

                            <option value="">

                                Semua Jenis

                            </option>

                            <?php

                            $listJenis = [

                                "Peraturan Desa",

                                "Peraturan Kepala Desa",

                                "Keputusan Kepala Desa",

                                "Surat Keputusan",

                                "Instruksi",

                                "Lainnya"

                            ];

                            foreach ($listJenis as $j) {

                            ?>

                                <option
                                    value="<?= $j ?>"
                                    <?= ($jenis == $j) ? "selected" : "" ?>>

                                    <?= $j ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <input
                            type="number"
                            name="tahun"
                            class="form-control"
                            placeholder="Tahun"
                            value="<?= htmlspecialchars($tahun); ?>">

                    </div>

                    <div class="col-md-2 d-grid">

                        <button class="btn btn-primary">

                            Filter

                        </button>

                    </div>

                </form>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th width="60">

                                    No

                                </th>

                                <th width="170">

                                    Jenis

                                </th>

                                <th width="130">

                                    Nomor

                                </th>

                                <th width="80">

                                    Tahun

                                </th>

                                <th>

                                    Judul

                                </th>

                                <th width="120">

                                    Status

                                </th>

                                <th width="130">

                                    PDF

                                </th>

                                <th width="220">

                                    Aksi

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            $no = 1;

                            if (mysqli_num_rows($query) > 0):

                                while ($row = mysqli_fetch_assoc($query)):

                            ?>

                                    <tr>

                                        <td><?= $no++; ?></td>

                                        <td><?= htmlspecialchars($row['jenis']); ?></td>

                                        <td><?= htmlspecialchars($row['nomor']); ?></td>

                                        <td><?= $row['tahun']; ?></td>

                                        <td>

                                            <strong>

                                                <?= htmlspecialchars($row['judul']); ?>

                                            </strong>

                                        </td>

                                        <td>

                                            <?php

                                            if ($row['status'] == "Publish") {

                                            ?>

                                                <span class="badge bg-success">

                                                    Publish

                                                </span>

                                            <?php

                                            } else {

                                            ?>

                                                <span class="badge bg-secondary">

                                                    Draft

                                                </span>

                                            <?php

                                            }

                                            ?>

                                        </td>

                                        <td class="text-center">

                                            <?php

                                            if (!empty($row['file_pdf'])) {

                                            ?>

                                                <a
                                                    href="uploads/<?= htmlspecialchars($row['file_pdf']); ?>"
                                                    target="_blank"
                                                    class="btn btn-danger btn-sm">

                                                    PDF

                                                </a>

                                            <?php

                                            } else {

                                                echo "-";
                                            }

                                            ?>

                                        </td>

                                        <td>

                                            <a
                                                href="detail.php?id=<?= $row['id']; ?>"
                                                class="btn btn-info btn-sm text-white">

                                                Detail

                                            </a>

                                            <a
                                                href="edit.php?id=<?= $row['id']; ?>"
                                                class="btn btn-warning btn-sm">

                                                Edit

                                            </a>

                                            <a
                                                href="delete.php?id=<?= $row['id']; ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus produk hukum ini?')">

                                                Hapus

                                            </a>

                                        </td>

                                    </tr>

                                <?php

                                endwhile;

                            else:

                                ?>

                                <tr>

                                    <td colspan="8" class="text-center text-muted">

                                        Belum ada produk hukum.

                                    </td>

                                </tr>

                            <?php

                            endif;

                            ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</body>

</html>