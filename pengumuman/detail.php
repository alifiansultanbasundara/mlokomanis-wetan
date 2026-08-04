<?php

include "../auth/auth.php";
include "../config/database.php";

// ======================================
// Validasi ID
// ======================================

if (!isset($_GET['id'])) {

    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// ======================================
// Ambil Data
// ======================================

$query = mysqli_query($conn, "

SELECT *

FROM pengumuman

WHERE id='$id'

");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['success'] = "Data pengumuman tidak ditemukan.";

    header("Location:index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Detail Pengumuman</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card shadow">

            <div class="card-header bg-success text-white">

                <h3 class="mb-0">

                    Detail Pengumuman

                </h3>

            </div>

            <div class="card-body">

                <?php

                if (!empty($data['gambar'])) {

                ?>

                    <div class="text-center mb-4">

                        <img
                            src="uploads/pengumuman/<?= $data['gambar']; ?>"
                            class="img-fluid rounded shadow"
                            style="max-height:350px;">

                    </div>

                <?php

                }

                ?>

                <h3 class="fw-bold">

                    <?= htmlspecialchars($data['judul']); ?>

                </h3>

                <hr>

                <div class="row">

                    <div class="col-md-6">

                        <table class="table table-borderless">

                            <tr>

                                <th width="180">

                                    Kategori

                                </th>

                                <td>

                                    <span class="badge bg-primary">

                                        <?= htmlspecialchars($data['kategori']); ?>

                                    </span>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Prioritas

                                </th>

                                <td>

                                    <?php

                                    switch ($data['prioritas']) {

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

                            </tr>

                            <tr>

                                <th>

                                    Status

                                </th>

                                <td>

                                    <?php

                                    if ($data['status'] == "Publish") {

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

                            </tr>

                        </table>

                    </div>

                    <div class="col-md-6">

                        <table class="table table-borderless">

                            <tr>

                                <th width="180">

                                    Tanggal Mulai

                                </th>

                                <td>

                                    <?= !empty($data['tanggal_mulai'])
                                        ? date("d F Y", strtotime($data['tanggal_mulai']))
                                        : "-"; ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Tanggal Selesai

                                </th>

                                <td>

                                    <?= !empty($data['tanggal_selesai'])
                                        ? date("d F Y", strtotime($data['tanggal_selesai']))
                                        : "-"; ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Dibuat Oleh

                                </th>

                                <td>

                                    <?= htmlspecialchars($data['created_by']); ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Dibuat

                                </th>

                                <td>

                                    <?= !empty($data['created_at'])
                                        ? date("d F Y H:i", strtotime($data['created_at']))
                                        : "-"; ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Diupdate

                                </th>

                                <td>

                                    <?= !empty($data['updated_at'])
                                        ? date("d F Y H:i", strtotime($data['updated_at']))
                                        : "-"; ?>

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

                <hr>

                <h5>

                    Isi Pengumuman

                </h5>

                <div style="line-height:1.9">

                    <?php

                    echo nl2br(htmlspecialchars($data['isi']));

                    ?>

                </div>

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
                            onclick="return confirm('Yakin ingin menghapus pengumuman ini?')">

                            Hapus

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>