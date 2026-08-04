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

FROM produk_hukum

WHERE id='$id'

");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['success'] = "Data produk hukum tidak ditemukan.";

    header("Location:index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Edit Produk Hukum</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-9">

                <div class="card shadow">

                    <div class="card-header bg-warning">

                        <h4 class="mb-0">

                            Edit Produk Hukum

                        </h4>

                    </div>

                    <div class="card-body">

                        <form
                            action="update.php"
                            method="POST"
                            enctype="multipart/form-data">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $data['id']; ?>">

                            <input
                                type="hidden"
                                name="old_file"
                                value="<?= htmlspecialchars($data['file_pdf']); ?>">

                            <!-- Jenis -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Jenis Produk Hukum

                                </label>

                                <select
                                    name="jenis"
                                    class="form-select"
                                    required>

                                    <?php

                                    $jenisList = [

                                        "Peraturan Desa",

                                        "Peraturan Kepala Desa",

                                        "Keputusan Kepala Desa",

                                        "Surat Keputusan",

                                        "Instruksi",

                                        "Lainnya"

                                    ];

                                    foreach ($jenisList as $jenis) {

                                    ?>

                                        <option
                                            value="<?= $jenis; ?>"
                                            <?= ($data['jenis'] == $jenis) ? "selected" : ""; ?>>

                                            <?= $jenis; ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <!-- Nomor -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Nomor Dokumen

                                </label>

                                <input
                                    type="text"
                                    name="nomor"
                                    class="form-control"
                                    required
                                    value="<?= htmlspecialchars($data['nomor']); ?>">

                            </div>

                            <!-- Tahun -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Tahun

                                </label>

                                <input
                                    type="number"
                                    name="tahun"
                                    class="form-control"
                                    required
                                    value="<?= $data['tahun']; ?>">

                            </div>

                            <!-- Judul -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Judul

                                </label>

                                <input
                                    type="text"
                                    name="judul"
                                    class="form-control"
                                    required
                                    value="<?= htmlspecialchars($data['judul']); ?>">

                            </div>

                            <!-- Deskripsi -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Deskripsi

                                </label>

                                <textarea
                                    name="deskripsi"
                                    rows="5"
                                    class="form-control"><?= htmlspecialchars($data['deskripsi']); ?></textarea>

                            </div>

                            <div class="row">

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Tanggal Ditetapkan

                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_ditetapkan"
                                        class="form-control"
                                        value="<?= $data['tanggal_ditetapkan']; ?>">

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Tanggal Diundangkan

                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_diundangkan"
                                        class="form-control"
                                        value="<?= $data['tanggal_diundangkan']; ?>">

                                </div>

                            </div>

                            <br>

                            <!-- File Lama -->

                            <div class="mb-3">

                                <label class="form-label">

                                    File PDF Saat Ini

                                </label>

                                <br>

                                <?php if (!empty($data['file_pdf'])) { ?>

                                    <a
                                        href="uploads/produk_hukum/<?= htmlspecialchars($data['file_pdf']); ?>"
                                        target="_blank"
                                        class="btn btn-danger btn-sm">

                                        Lihat PDF

                                    </a>

                                    <p class="mt-2 mb-0 text-muted">

                                        <?= htmlspecialchars($data['file_pdf']); ?>

                                    </p>

                                <?php } else { ?>

                                    <p class="text-muted">

                                        Belum ada file PDF.

                                    </p>

                                <?php } ?>

                            </div>

                            <!-- Upload Baru -->

                            <div class="mb-4">

                                <label class="form-label">

                                    Ganti File PDF

                                </label>

                                <input
                                    type="file"
                                    name="file_pdf"
                                    class="form-control"
                                    accept=".pdf">

                                <small class="text-muted">

                                    Kosongkan jika tidak ingin mengganti file PDF.

                                </small>

                            </div>

                            <!-- Status -->

                            <div class="mb-4">

                                <label class="form-label">

                                    Status

                                </label>

                                <select
                                    name="status"
                                    class="form-select">

                                    <option
                                        value="Publish"
                                        <?= ($data['status'] == "Publish") ? "selected" : ""; ?>>

                                        Publish

                                    </option>

                                    <option
                                        value="Draft"
                                        <?= ($data['status'] == "Draft") ? "selected" : ""; ?>>

                                        Draft

                                    </option>

                                </select>

                            </div>

                            <div class="d-flex justify-content-between">

                                <a
                                    href="index.php"
                                    class="btn btn-secondary">

                                    Kembali

                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-warning">

                                    Update Produk Hukum

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>