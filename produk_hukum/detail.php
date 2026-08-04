<?php

include "../auth/auth.php";
include "../config/database.php";

// ======================================
// Validasi ID
// ======================================

if (!isset($_GET['id'])) {

    header("Location:index.php");
    exit;
}

$id = (int)$_GET['id'];

// ======================================
// Ambil Data
// ======================================

$query = mysqli_query($conn, "

SELECT *

FROM produk_hukum

WHERE id='$id'

");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['success'] = "Data tidak ditemukan.";

    header("Location:index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Detail Produk Hukum</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card shadow">

            <div class="card-header bg-success text-white">

                <h3 class="mb-0">

                    Detail Produk Hukum

                </h3>

            </div>

            <div class="card-body">

                <h4 class="fw-bold">

                    <?= htmlspecialchars($data['judul']); ?>

                </h4>

                <hr>

                <div class="row">

                    <div class="col-md-6">

                        <table class="table table-borderless">

                            <tr>

                                <th width="190">

                                    Jenis

                                </th>

                                <td>

                                    <?= htmlspecialchars($data['jenis']); ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Nomor

                                </th>

                                <td>

                                    <?= htmlspecialchars($data['nomor']); ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Tahun

                                </th>

                                <td>

                                    <?= $data['tahun']; ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Tanggal Ditetapkan

                                </th>

                                <td>

                                    <?= !empty($data['tanggal_ditetapkan'])
                                        ? date("d F Y", strtotime($data['tanggal_ditetapkan']))
                                        : "-"; ?>

                                </td>

                            </tr>

                        </table>

                    </div>

                    <div class="col-md-6">

                        <table class="table table-borderless">

                            <tr>

                                <th width="190">

                                    Tanggal Diundangkan

                                </th>

                                <td>

                                    <?= !empty($data['tanggal_diundangkan'])
                                        ? date("d F Y", strtotime($data['tanggal_diundangkan']))
                                        : "-"; ?>

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

                    Deskripsi

                </h5>

                <div style="line-height:1.8">

                    <?php

                    if (!empty($data['deskripsi'])) {

                        echo nl2br(htmlspecialchars($data['deskripsi']));
                    } else {

                        echo "<span class='text-muted'>Tidak ada deskripsi.</span>";
                    }

                    ?>

                </div>

                <hr>

                <h5>

                    File Dokumen

                </h5>

                <?php

                if (!empty($data['file_pdf'])) {

                ?>

                    <div class="d-flex gap-2">

                        <a
                            href="uploads/produk_hukum/<?= htmlspecialchars($data['file_pdf']); ?>"
                            target="_blank"
                            class="btn btn-danger">

                            Lihat PDF

                        </a>

                        <a
                            href="uploads/produk_hukum/<?= htmlspecialchars($data['file_pdf']); ?>"
                            download
                            class="btn btn-primary">

                            Download PDF

                        </a>

                    </div>

                <?php

                } else {

                ?>

                    <p class="text-muted">

                        Tidak ada file PDF.

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
                            onclick="return confirm('Yakin ingin menghapus produk hukum ini?')">

                            Hapus

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>