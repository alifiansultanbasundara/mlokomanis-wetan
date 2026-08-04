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

    <title>Edit Perangkat Desa</title>

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

                            Edit Perangkat Desa

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
                                name="old_photo"
                                value="<?= $data['photo']; ?>">

                            <!-- Nama -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Nama Lengkap

                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    required
                                    value="<?= htmlspecialchars($data['name']); ?>">

                            </div>

                            <!-- Jabatan -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Jabatan

                                </label>

                                <input
                                    type="text"
                                    name="position"
                                    class="form-control"
                                    required
                                    value="<?= htmlspecialchars($data['position']); ?>">

                            </div>

                            <!-- NIP -->

                            <div class="mb-3">

                                <label class="form-label">

                                    NIP

                                </label>

                                <input
                                    type="text"
                                    name="nip"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['nip']); ?>">

                            </div>

                            <!-- No HP -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Nomor HP

                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['phone']); ?>">

                            </div>

                            <!-- Email -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Email

                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['email']); ?>">

                            </div>

                            <!-- Deskripsi -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Deskripsi / Tugas

                                </label>

                                <textarea
                                    name="description"
                                    rows="5"
                                    class="form-control"><?= htmlspecialchars($data['description']); ?></textarea>

                            </div>

                            <!-- Urutan -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Urutan Tampil

                                </label>

                                <input
                                    type="number"
                                    name="sort_order"
                                    class="form-control"
                                    value="<?= $data['sort_order']; ?>">

                            </div>

                            <!-- Foto Lama -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Foto Saat Ini

                                </label>

                                <br>

                                <?php

                                if (!empty($data['photo'])) {

                                ?>

                                    <img
                                        src="uploads/<?= htmlspecialchars($data['photo']); ?>"
                                        class="img-thumbnail"
                                        style="width:180px;height:220px;object-fit:cover;">

                                <?php

                                } else {

                                ?>

                                    <div class="text-muted">

                                        Belum ada foto.

                                    </div>

                                <?php

                                }

                                ?>

                            </div>

                            <!-- Upload Baru -->

                            <div class="mb-4">

                                <label class="form-label">

                                    Ganti Foto

                                </label>

                                <input
                                    type="file"
                                    name="photo"
                                    accept="image/*"
                                    class="form-control">

                                <small class="text-muted">

                                    Kosongkan jika tidak ingin mengganti foto.

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
                                        value="Aktif"
                                        <?= ($data['status'] == "Aktif") ? "selected" : ""; ?>>

                                        Aktif

                                    </option>

                                    <option
                                        value="Tidak Aktif"
                                        <?= ($data['status'] == "Tidak Aktif") ? "selected" : ""; ?>>

                                        Tidak Aktif

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

                                    Update Data

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