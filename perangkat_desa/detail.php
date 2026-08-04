<?php

include "../auth/auth.php";
include "../config/database.php";

// =====================================
// Validasi ID
// =====================================

if (!isset($_GET['id'])) {

    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];


// =====================================
// Ambil Data
// =====================================

$query = mysqli_query($conn, "

SELECT *

FROM perangkat_desa

WHERE id='$id'

");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['success'] = "Data perangkat desa tidak ditemukan.";

    header("Location:index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Detail Perangkat Desa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card shadow">

            <div class="card-header bg-success text-white">

                <h3 class="mb-0">

                    Detail Perangkat Desa

                </h3>

            </div>

            <div class="card-body">

                <!-- Foto -->

                <div class="text-center mb-4">

                    <?php if (!empty($data['photo'])) { ?>

                        <img
                            src="uploads/<?= htmlspecialchars($data['photo']); ?>"
                            class="rounded shadow border"
                            style="width:220px;height:280px;object-fit:cover;">

                    <?php } else { ?>

                        <div
                            class="border rounded d-flex align-items-center justify-content-center mx-auto"
                            style="width:220px;height:280px;background:#f8f9fa;">

                            <span class="text-muted">

                                Tidak Ada Foto

                            </span>

                        </div>

                    <?php } ?>

                </div>

                <h3 class="text-center fw-bold">

                    <?= htmlspecialchars($data['name']); ?>

                </h3>

                <p class="text-center text-muted mb-4">

                    <?= htmlspecialchars($data['position']); ?>

                </p>

                <hr>

                <div class="row">

                    <div class="col-md-6">

                        <table class="table table-borderless">

                            <tr>

                                <th width="180">

                                    Nama

                                </th>

                                <td>

                                    <?= htmlspecialchars($data['name']); ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Jabatan

                                </th>

                                <td>

                                    <?= htmlspecialchars($data['position']); ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    NIP

                                </th>

                                <td>

                                    <?= !empty($data['nip'])
                                        ? htmlspecialchars($data['nip'])
                                        : "-"; ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    No. HP

                                </th>

                                <td>

                                    <?= !empty($data['phone'])
                                        ? htmlspecialchars($data['phone'])
                                        : "-"; ?>

                                </td>

                            </tr>

                        </table>

                    </div>

                    <div class="col-md-6">

                        <table class="table table-borderless">

                            <tr>

                                <th width="180">

                                    Email

                                </th>

                                <td>

                                    <?= !empty($data['email'])
                                        ? htmlspecialchars($data['email'])
                                        : "-"; ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Urutan

                                </th>

                                <td>

                                    <?= $data['sort_order']; ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Status

                                </th>

                                <td>

                                    <?php

                                    if ($data['status'] == "Aktif") {

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

                            </tr>

                            <tr>

                                <th>

                                    Dibuat

                                </th>

                                <td>

                                    <?= date("d F Y H:i", strtotime($data['created_at'])); ?>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Diupdate

                                </th>

                                <td>

                                    <?= date("d F Y H:i", strtotime($data['updated_at'])); ?>

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

                <hr>

                <h5>

                    Deskripsi / Tugas

                </h5>

                <div style="line-height:1.8">

                    <?php

                    if (!empty($data['description'])) {

                        echo nl2br(htmlspecialchars($data['description']));
                    } else {

                        echo "<span class='text-muted'>Tidak ada deskripsi.</span>";
                    }

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
                            onclick="return confirm('Yakin ingin menghapus data perangkat desa ini?')">

                            Hapus

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>