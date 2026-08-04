<?php

include "../auth/auth.php";
include "../config/database.php";

// ===========================
// Validasi ID
// ===========================

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];


// ===========================
// Ambil Data
// ===========================

$query = mysqli_query($conn, "

SELECT
    wilayah.*,
    users.nama AS author

FROM wilayah

JOIN users
ON users.id = wilayah.author_id

WHERE wilayah.id='$id'

");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['success'] = "Data tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title><?= htmlspecialchars($data['title']); ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card shadow">

            <div class="card-header bg-success text-white">

                <h3 class="mb-0">

                    Detail Data Wilayah

                </h3>

            </div>

            <div class="card-body">

                <!-- Thumbnail -->

                <?php if (!empty($data['image'])) { ?>

                    <div class="text-center mb-4">

                        <img
                            src="uploads/thumbnail/<?= htmlspecialchars($data['image']); ?>"
                            class="img-fluid rounded shadow"
                            style="max-height:450px;">

                    </div>

                <?php } ?>



                <h2 class="fw-bold">

                    <?= htmlspecialchars($data['title']); ?>

                </h2>

                <hr>

                <div class="row">

                    <div class="col-md-6">

                        <table class="table table-borderless">

                            <tr>

                                <th width="160">

                                    Jenis Data

                                </th>

                                <td>

                                    <span class="badge bg-primary">

                                        <?= htmlspecialchars($data['type']); ?>

                                    </span>

                                </td>

                            </tr>

                            <tr>

                                <th>Status</th>

                                <td>

                                    <?php

                                    if ($data['status'] == "Published") {

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

                            </tr>

                            <tr>

                                <th>Penulis</th>

                                <td>

                                    <?= htmlspecialchars($data['author']); ?>

                                </td>

                            </tr>

                        </table>

                    </div>

                    <div class="col-md-6">

                        <table class="table table-borderless">

                            <tr>

                                <th width="160">

                                    Dibuat

                                </th>

                                <td>

                                    <?= date("d F Y H:i", strtotime($data['created_at'])); ?>

                                </td>

                            </tr>

                            <tr>

                                <th>Diupdate</th>

                                <td>

                                    <?= date("d F Y H:i", strtotime($data['updated_at'])); ?>

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

                <hr>

                <h5>

                    Deskripsi

                </h5>

                <div style="line-height:1.8;">

                    <?= nl2br(htmlspecialchars($data['description'])); ?>

                </div>

                <hr>

                <h5>

                    File Lampiran

                </h5>

                <?php

                if (!empty($data['file'])) {

                    $ext = strtolower(pathinfo($data['file'], PATHINFO_EXTENSION));

                    if ($ext == "pdf") {

                ?>

                        <div class="alert alert-danger">

                            PDF tersedia.

                            <br><br>

                            <a
                                href="uploads/files/<?= htmlspecialchars($data['file']); ?>"
                                target="_blank"
                                class="btn btn-danger">

                                Buka PDF

                            </a>

                        </div>

                    <?php

                    } else {

                    ?>

                        <div class="text-center">

                            <img
                                src="uploads/files/<?= htmlspecialchars($data['file']); ?>"
                                class="img-fluid rounded border"
                                style="max-height:500px;">

                        </div>

                    <?php

                    }
                } else {

                    ?>

                    <p class="text-muted">

                        Belum ada file.

                    </p>

                <?php

                }

                ?>

                <hr>

                <div class="d-flex justify-content-between">

                    <a
                        href="index.php"
                        class="btn btn-secondary">

                        Kembali

                    </a>

                    <div>

                        <a
                            href="edit.php?id=<?= $data['id']; ?>"
                            class="btn btn-warning">

                            Edit

                        </a>

                        <a
                            href="delete.php?id=<?= $data['id']; ?>"
                            class="btn btn-danger"
                            onclick="return confirm('Yakin ingin menghapus data ini?')">

                            Hapus

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>