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

LIMIT 1

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

    <title>Edit Pengumuman</title>

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

                            Edit Pengumuman

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
                                name="old_gambar"
                                value="<?= $data['gambar']; ?>">

                            <!-- Judul -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Judul Pengumuman

                                </label>

                                <input
                                    type="text"
                                    name="judul"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['judul']); ?>"
                                    required>

                            </div>

                            <!-- Isi -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Isi Pengumuman

                                </label>

                                <textarea
                                    name="isi"
                                    rows="6"
                                    class="form-control"
                                    required><?= htmlspecialchars($data['isi']); ?></textarea>

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Kategori

                                    </label>

                                    <select
                                        name="kategori"
                                        class="form-select">

                                        <?php

                                        $kategori = [

                                            'Pelayanan',

                                            'Bansos',

                                            'Kesehatan',

                                            'Kegiatan',

                                            'Keuangan',

                                            'Lainnya'

                                        ];

                                        foreach ($kategori as $item) {

                                        ?>

                                            <option
                                                value="<?= $item; ?>"
                                                <?= $data['kategori'] == $item ? 'selected' : ''; ?>>

                                                <?= $item; ?>

                                            </option>

                                        <?php } ?>

                                    </select>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Prioritas

                                    </label>

                                    <select
                                        name="prioritas"
                                        class="form-select">

                                        <?php

                                        $prioritas = [

                                            'Biasa',

                                            'Penting',

                                            'Sangat Penting'

                                        ];

                                        foreach ($prioritas as $item) {

                                        ?>

                                            <option
                                                value="<?= $item; ?>"
                                                <?= $data['prioritas'] == $item ? 'selected' : ''; ?>>

                                                <?= $item; ?>

                                            </option>

                                        <?php } ?>

                                    </select>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Tanggal Mulai

                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_mulai"
                                        class="form-control"
                                        value="<?= $data['tanggal_mulai']; ?>">

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Tanggal Selesai

                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_selesai"
                                        class="form-control"
                                        value="<?= $data['tanggal_selesai']; ?>">

                                </div>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Status

                                </label>

                                <select
                                    name="status"
                                    class="form-select">

                                    <option
                                        value="Publish"
                                        <?= $data['status'] == "Publish" ? "selected" : ""; ?>>

                                        Publish

                                    </option>

                                    <option
                                        value="Draft"
                                        <?= $data['status'] == "Draft" ? "selected" : ""; ?>>

                                        Draft

                                    </option>

                                </select>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Gambar Baru (Opsional)

                                </label>

                                <input
                                    type="file"
                                    name="gambar"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp">

                                <?php

                                if (!empty($data['gambar'])) {

                                ?>

                                    <div class="mt-3">

                                        <img
                                            src="uploads/pengumuman/<?= $data['gambar']; ?>"
                                            class="img-thumbnail"
                                            width="220">

                                    </div>

                                <?php

                                }

                                ?>

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

                                    Update Pengumuman

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