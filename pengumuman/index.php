<?php

include "../auth/auth.php";
include "../config/database.php";

// ======================================
// Pencarian
// ======================================

$search = "";

$where = "";

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);

    $where = "WHERE (
        judul LIKE '%$search%'
        OR isi LIKE '%$search%'
    )";
}

// ======================================
// Ambil Data
// ======================================

$query = mysqli_query($conn, "

SELECT *

FROM pengumuman

$where

ORDER BY

FIELD(prioritas,'Sangat Penting','Penting','Biasa'),

tanggal_mulai DESC,

id DESC

");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Kelola Pengumuman</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">

                    Kelola Pengumuman

                </h2>

                <small class="text-muted">

                    Kelola seluruh pengumuman yang akan ditampilkan kepada masyarakat.

                </small>

            </div>

            <a
                href="create.php"
                class="btn btn-success">

                + Tambah Pengumuman

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

                <form
                    method="GET"
                    class="row g-3 mb-4">

                    <div class="col-md-10">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari pengumuman..."
                            value="<?= htmlspecialchars($search); ?>">

                    </div>

                    <div class="col-md-2 d-grid">

                        <button
                            class="btn btn-primary">

                            Cari

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

                                <th>

                                    Pengumuman

                                </th>

                                <th width="140">

                                    Kategori

                                </th>

                                <th width="150">

                                    Prioritas

                                </th>

                                <th width="120">

                                    Status

                                </th>

                                <th width="170">

                                    Periode

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

                                        <td>

                                            <?= $no++; ?>

                                        </td>

                                        <td>

                                            <strong>

                                                <?= htmlspecialchars($row['judul']); ?>

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                <?= htmlspecialchars(substr(strip_tags($row['isi']), 0, 80)); ?>

                                                <?= strlen($row['isi']) > 80 ? "..." : ""; ?>

                                            </small>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars($row['kategori']); ?>

                                        </td>

                                        <td>

                                            <?php

                                            switch ($row['prioritas']) {

                                                case "Sangat Penting":

                                                    echo '<span class="badge bg-danger">Sangat Penting</span>';

                                                    break;

                                                case "Penting":

                                                    echo '<span class="badge bg-warning text-dark">Penting</span>';

                                                    break;

                                                default:

                                                    echo '<span class="badge bg-primary">Biasa</span>';
                                            }

                                            ?>

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

                                        <td>

                                            <?php

                                            if (!empty($row['tanggal_mulai'])) {

                                                echo date("d M Y", strtotime($row['tanggal_mulai']));
                                            } else {

                                                echo "-";
                                            }

                                            ?>

                                            <br>

                                            <small>

                                                s/d

                                            </small>

                                            <br>

                                            <?php

                                            if (!empty($row['tanggal_selesai'])) {

                                                echo date("d M Y", strtotime($row['tanggal_selesai']));
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
                                                onclick="return confirm('Yakin ingin menghapus pengumuman ini?')">

                                                Hapus

                                            </a>

                                        </td>

                                    </tr>

                                <?php

                                endwhile;

                            else:

                                ?>

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center text-muted">

                                        Belum ada data pengumuman.

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