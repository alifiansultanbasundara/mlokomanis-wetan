<?php

include "../../auth/auth.php";
include "../../config/database.php";

// ======================================
// Pencarian
// ======================================

$search = "";

$where = "";

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);

    $where = "WHERE (

        nama LIKE '%$search%'

        OR deskripsi LIKE '%$search%'

    )";
}

// ======================================
// Ambil Data
// ======================================

$query = mysqli_query($conn, "

SELECT *

FROM jenis_surat

$where

ORDER BY

urutan ASC,

nama ASC

");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Kelola Jenis Surat</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">

                    Kelola Jenis Surat

                </h2>

                <small class="text-muted">

                    Kelola layanan surat yang tersedia untuk masyarakat.

                </small>

            </div>

            <a
                href="create.php"
                class="btn btn-success">

                + Tambah Jenis Surat

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
                            placeholder="Cari jenis surat..."
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

                                    Nama Surat

                                </th>

                                <th width="120">

                                    Estimasi

                                </th>

                                <th>

                                    Google Form

                                </th>

                                <th width="120">

                                    Status

                                </th>

                                <th width="90">

                                    Urutan

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

                                                <?= htmlspecialchars($row['nama']); ?>

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                <?= htmlspecialchars(substr($row['deskripsi'], 0, 80)); ?>

                                            </small>

                                        </td>

                                        <td>

                                            <?= $row['estimasi_hari']; ?>

                                            Hari

                                        </td>

                                        <td>

                                            <?php

                                            if (!empty($row['google_form'])) {

                                            ?>

                                                <a
                                                    href="<?= htmlspecialchars($row['google_form']); ?>"
                                                    target="_blank"
                                                    class="btn btn-outline-primary btn-sm">

                                                    Buka Form

                                                </a>

                                            <?php

                                            } else {

                                            ?>

                                                <span class="text-muted">

                                                    Belum ada

                                                </span>

                                            <?php

                                            }

                                            ?>

                                        </td>

                                        <td>

                                            <?php

                                            if ($row['is_active']) {

                                            ?>

                                                <span class="badge bg-success">

                                                    Aktif

                                                </span>

                                            <?php

                                            } else {

                                            ?>

                                                <span class="badge bg-secondary">

                                                    Nonaktif

                                                </span>

                                            <?php

                                            }

                                            ?>

                                        </td>

                                        <td class="text-center">

                                            <?= $row['urutan']; ?>

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
                                                onclick="return confirm('Yakin ingin menghapus jenis surat ini?')">

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

                                        Belum ada data jenis surat.

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