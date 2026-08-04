<?php

include "../../auth/auth.php";
include "../../config/database.php";

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

FROM jenis_surat

WHERE id='$id'

");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['success'] = "Data jenis surat tidak ditemukan.";

    header("Location:index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Edit Jenis Surat</title>

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

                            Edit Jenis Surat

                        </h4>

                    </div>

                    <div class="card-body">

                        <form
                            action="update.php"
                            method="POST">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $data['id']; ?>">

                            <!-- Nama -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Nama Surat

                                </label>

                                <input
                                    type="text"
                                    name="nama"
                                    class="form-control"
                                    required
                                    value="<?= htmlspecialchars($data['nama']); ?>">

                            </div>

                            <!-- Slug -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Slug

                                </label>

                                <input
                                    type="text"
                                    name="slug"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['slug']); ?>">

                                <small class="text-muted">

                                    Kosongkan jika ingin dibuat otomatis.

                                </small>

                            </div>

                            <!-- Icon -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Icon (Emoji)

                                </label>

                                <input
                                    type="text"
                                    name="icon"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['icon']); ?>">

                            </div>

                            <!-- Estimasi -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Estimasi Hari

                                </label>

                                <input
                                    type="number"
                                    name="estimasi_hari"
                                    class="form-control"
                                    required
                                    min="1"
                                    value="<?= $data['estimasi_hari']; ?>">

                            </div>

                            <!-- Google Form -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Link Google Form

                                </label>

                                <input
                                    type="url"
                                    name="google_form"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['google_form']); ?>">

                            </div>

                            <!-- Persyaratan -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Persyaratan

                                </label>

                                <textarea
                                    name="persyaratan"
                                    rows="5"
                                    class="form-control"><?= htmlspecialchars($data['persyaratan']); ?></textarea>

                            </div>

                            <!-- Deskripsi -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Deskripsi

                                </label>

                                <textarea
                                    name="deskripsi"
                                    rows="4"
                                    class="form-control"><?= htmlspecialchars($data['deskripsi']); ?></textarea>

                            </div>

                            <div class="row">

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Status

                                    </label>

                                    <select
                                        name="is_active"
                                        class="form-select">

                                        <option
                                            value="1"
                                            <?= ($data['is_active'] == 1) ? "selected" : ""; ?>>

                                            Aktif

                                        </option>

                                        <option
                                            value="0"
                                            <?= ($data['is_active'] == 0) ? "selected" : ""; ?>>

                                            Nonaktif

                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Urutan

                                    </label>

                                    <input
                                        type="number"
                                        name="urutan"
                                        class="form-control"
                                        value="<?= $data['urutan']; ?>">

                                </div>

                            </div>

                            <br>

                            <div class="d-flex justify-content-between">

                                <a
                                    href="index.php"
                                    class="btn btn-secondary">

                                    Kembali

                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-warning">

                                    Update Jenis Surat

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