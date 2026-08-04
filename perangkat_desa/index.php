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
        name LIKE '%$search%'
        OR position LIKE '%$search%'
    )";
}

// ======================================
// Ambil Data
// ======================================

$query = mysqli_query($conn, "

SELECT *

FROM perangkat_desa

$where

ORDER BY

sort_order ASC,

name ASC

");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Kelola Perangkat Desa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">

                    Kelola Perangkat Desa

                </h2>

                <small class="text-muted">

                    Kelola data perangkat desa dan struktur organisasi.

                </small>

            </div>

            <a
                href="create.php"
                class="btn btn-success">

                + Tambah Perangkat

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
                            placeholder="Cari nama atau jabatan..."
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

                                <th width="110">

                                    Foto

                                </th>

                                <th>

                                    Nama

                                </th>

                                <th width="220">

                                    Jabatan

                                </th>

                                <th width="170">

                                    NIP

                                </th>

                                <th width="150">

                                    No. HP

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

                                            <?php

                                            if (!empty($row['photo'])) {

                                            ?>

                                                <img
                                                    src="uploads/<?= htmlspecialchars($row['photo']); ?>"
                                                    class="img-fluid rounded border"
                                                    style="width:80px;height:80px;object-fit:cover;">

                                            <?php

                                            } else {

                                            ?>

                                                <span class="text-muted">

                                                    Tidak ada

                                                </span>

                                            <?php

                                            }

                                            ?>

                                        </td>

                                        <td>

                                            <strong>

                                                <?= htmlspecialchars($row['name']); ?>

                                            </strong>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars($row['position']); ?>

                                        </td>

                                        <td>

                                            <?= !empty($row['nip'])
                                                ? htmlspecialchars($row['nip'])
                                                : "-"; ?>

                                        </td>

                                        <td>

                                            <?= !empty($row['phone'])
                                                ? htmlspecialchars($row['phone'])
                                                : "-"; ?>

                                        </td>

                                        <td class="text-center">

                                            <?= $row['sort_order']; ?>

                                        </td>

                                        <td>

                                            <?php

                                            if ($row['status'] == "Aktif") {

                                            ?>

                                                <span class="badge bg-success">

                                                    Aktif

                                                </span>

                                            <?php

                                            } else {

                                            ?>

                                                <span class="badge bg-secondary">

                                                    Tidak Aktif

                                                </span>

                                            <?php

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
                                                onclick="return confirm('Yakin ingin menghapus perangkat desa ini?')">

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
                                        colspan="9"
                                        class="text-center text-muted">

                                        Belum ada data perangkat desa.

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