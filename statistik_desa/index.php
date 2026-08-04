<?php

include "../auth/auth.php";
include "../config/database.php";

// ======================================
// Pencarian
// ======================================

$search = "";

$where = "";

if (isset($_GET['search']) && trim($_GET['search']) != "") {

    $search = mysqli_real_escape_string($conn, trim($_GET['search']));

    $where = "WHERE
                s.nama_statistik LIKE '%$search%'
                OR
                k.nama LIKE '%$search%'";
}

// ======================================
// Ambil Data
// ======================================

$query = mysqli_query($conn, "

SELECT

s.*,

k.nama AS kategori

FROM statistik_desa s

INNER JOIN kategori_statistik k

ON s.kategori_id = k.id

$where

ORDER BY

k.urutan ASC,

s.urutan ASC,

s.id DESC

");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Kelola Statistik Desa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">

                    Kelola Statistik Desa

                </h2>

                <small class="text-muted">

                    Kelola seluruh data statistik desa.

                </small>

            </div>

            <a
                href="create.php"
                class="btn btn-success">

                + Tambah Statistik

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
                            placeholder="Cari statistik..."
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

                                    Nama Statistik

                                </th>

                                <th width="180">

                                    Kategori

                                </th>

                                <th width="120">

                                    Nilai

                                </th>

                                <th width="100">

                                    Satuan

                                </th>

                                <th width="90">

                                    Urutan

                                </th>

                                <th width="120">

                                    Status

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

                                                <?= htmlspecialchars($row['nama_statistik']); ?>

                                            </strong>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars($row['kategori']); ?>

                                        </td>

                                        <td>

                                            <?= number_format($row['nilai'], 0, ',', '.'); ?>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars($row['satuan']); ?>

                                        </td>

                                        <td>

                                            <?= $row['urutan']; ?>

                                        </td>

                                        <td>

                                            <?php if ($row['status'] == "Aktif") { ?>

                                                <span class="badge bg-success">

                                                    Aktif

                                                </span>

                                            <?php } else { ?>

                                                <span class="badge bg-secondary">

                                                    Nonaktif

                                                </span>

                                            <?php } ?>

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
                                                onclick="return confirm('Yakin ingin menghapus data ini?')">

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
                                        colspan="8"
                                        class="text-center text-muted">

                                        Belum ada data statistik.

                                    </td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</body>

</html>