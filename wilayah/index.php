<?php

include "../auth/auth.php";
include "../config/database.php";

// ==============================
// Pencarian
// ==============================

$search = "";

$where = "";

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);

    $where = "WHERE wilayah.title LIKE '%$search%'";
}

$query = mysqli_query($conn, "

SELECT

wilayah.*,

users.nama AS author

FROM wilayah

JOIN users

ON users.id = wilayah.author_id

$where

ORDER BY wilayah.created_at DESC

");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Kelola Wilayah</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">

                    Kelola Wilayah Desa

                </h2>

                <small class="text-muted">

                    Kelola seluruh informasi wilayah desa.

                </small>

            </div>

            <a
                href="create.php"
                class="btn btn-success">

                + Tambah Data

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
                            placeholder="Cari judul wilayah..."
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

                                <th width="120">
                                    Thumbnail
                                </th>

                                <th>
                                    Judul
                                </th>

                                <th width="170">
                                    Jenis
                                </th>

                                <th width="90">
                                    File
                                </th>

                                <th width="110">
                                    Status
                                </th>

                                <th width="150">
                                    Penulis
                                </th>

                                <th width="170">
                                    Dibuat
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

                                            if (!empty($row['image'])) {

                                            ?>

                                                <img
                                                    src="uploads/thumbnail/<?= $row['image']; ?>"
                                                    class="img-fluid rounded border"
                                                    style="width:90px;height:70px;object-fit:cover;">

                                            <?php

                                            } else {

                                                echo "<span class='text-muted'>Tidak ada</span>";
                                            }

                                            ?>

                                        </td>

                                        <td>

                                            <strong>

                                                <?= htmlspecialchars($row['title']); ?>

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                <?= htmlspecialchars(substr($row['description'], 0, 70)); ?>

                                                ...

                                            </small>

                                        </td>

                                        <td>

                                            <?php

                                            $color = "secondary";

                                            switch ($row['type']) {

                                                case "Profil Wilayah":
                                                    $color = "primary";
                                                    break;

                                                case "Luas Wilayah":
                                                    $color = "success";
                                                    break;

                                                case "Peta Desa":
                                                    $color = "warning";
                                                    break;

                                                case "Peta Blok SPPT":
                                                    $color = "danger";
                                                    break;

                                                case "Peta RT":
                                                    $color = "info";
                                                    break;

                                                case "Peta Dusun":
                                                    $color = "dark";
                                                    break;
                                            }

                                            ?>

                                            <span class="badge bg-<?= $color; ?>">

                                                <?= $row['type']; ?>

                                            </span>

                                        </td>

                                        <td>

                                            <?php

                                            if (!empty($row['file'])) {

                                            ?>

                                                <span class="badge bg-success">

                                                    Ada

                                                </span>

                                            <?php

                                            } else {

                                            ?>

                                                <span class="badge bg-secondary">

                                                    -

                                                </span>

                                            <?php

                                            }

                                            ?>

                                        </td>

                                        <td>

                                            <?php

                                            if ($row['status'] == "Published") {

                                            ?>

                                                <span class="badge bg-success">

                                                    Published

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

                                            <?= htmlspecialchars($row['author']); ?>

                                        </td>

                                        <td>

                                            <?= date("d M Y H:i", strtotime($row['created_at'])); ?>

                                        </td>

                                        <td>

                                            <a
                                                href="detail.php?id=<?= $row['id']; ?>"
                                                class="btn btn-sm btn-info text-white">

                                                Detail

                                            </a>

                                            <a
                                                href="edit.php?id=<?= $row['id']; ?>"
                                                class="btn btn-sm btn-warning">

                                                Edit

                                            </a>

                                            <a
                                                href="delete.php?id=<?= $row['id']; ?>"
                                                class="btn btn-sm btn-danger"
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

                                    <td colspan="9" class="text-center text-muted">

                                        Belum ada data wilayah.

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