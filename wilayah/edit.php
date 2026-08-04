<?php

include "../auth/auth.php";
include "../config/database.php";

// ==============================
// Validasi ID
// ==============================

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// ==============================
// Ambil Data
// ==============================

$query = mysqli_query($conn, "
SELECT *
FROM wilayah
WHERE id='$id'
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

    <title>Edit Data Wilayah</title>

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

                            Edit Data Wilayah

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
                                name="old_image"
                                value="<?= $data['image']; ?>">

                            <input
                                type="hidden"
                                name="old_file"
                                value="<?= $data['file']; ?>">

                            <!-- Judul -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Judul

                                </label>

                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['title']); ?>"
                                    required>

                            </div>

                            <!-- Slug -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Slug

                                </label>

                                <input
                                    type="text"
                                    id="slug"
                                    name="slug"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['slug']); ?>"
                                    readonly
                                    required>

                            </div>

                            <!-- Jenis -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Jenis Data

                                </label>

                                <select
                                    name="type"
                                    class="form-select">

                                    <?php

                                    $types = [
                                        "Profil Wilayah",
                                        "Luas Wilayah",
                                        "Peta Desa",
                                        "Peta Blok SPPT",
                                        "Peta RT",
                                        "Peta Dusun",
                                        "Batas Wilayah",
                                        "Lainnya"
                                    ];

                                    foreach ($types as $type) {

                                    ?>

                                        <option
                                            value="<?= $type; ?>"
                                            <?= ($data['type'] == $type) ? "selected" : ""; ?>>

                                            <?= $type; ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <!-- Status -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Status

                                </label>

                                <select
                                    name="status"
                                    class="form-select">

                                    <option
                                        value="Published"
                                        <?= ($data['status'] == "Published") ? "selected" : ""; ?>>

                                        Published

                                    </option>

                                    <option
                                        value="Draft"
                                        <?= ($data['status'] == "Draft") ? "selected" : ""; ?>>

                                        Draft

                                    </option>

                                </select>

                            </div>

                            <!-- Deskripsi -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Deskripsi

                                </label>

                                <textarea
                                    name="description"
                                    rows="8"
                                    class="form-control"><?= htmlspecialchars($data['description']); ?></textarea>

                            </div>

                            <!-- Thumbnail -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Thumbnail Saat Ini

                                </label>

                                <br>

                                <?php if (!empty($data['image'])) { ?>

                                    <img
                                        src="uploads/thumbnail/<?= htmlspecialchars($data['image']); ?>"
                                        class="img-thumbnail"
                                        style="max-width:250px;">

                                <?php } else { ?>

                                    <p class="text-muted">

                                        Belum ada thumbnail.

                                    </p>

                                <?php } ?>

                            </div>

                            <div class="mb-4">

                                <label class="form-label">

                                    Ganti Thumbnail

                                </label>

                                <input
                                    type="file"
                                    name="image"
                                    accept="image/*"
                                    class="form-control">

                                <small class="text-muted">

                                    Kosongkan jika tidak ingin mengganti thumbnail.

                                </small>

                            </div>

                            <!-- File -->

                            <div class="mb-3">

                                <label class="form-label">

                                    File Saat Ini

                                </label>

                                <br>

                                <?php

                                if (!empty($data['file'])) {

                                    $ext = strtolower(pathinfo($data['file'], PATHINFO_EXTENSION));

                                    if ($ext == "pdf") {

                                ?>

                                        <a
                                            href="uploads/files/<?= htmlspecialchars($data['file']); ?>"
                                            target="_blank"
                                            class="btn btn-danger">

                                            Lihat PDF

                                        </a>

                                    <?php

                                    } else {

                                    ?>

                                        <img
                                            src="uploads/files/<?= htmlspecialchars($data['file']); ?>"
                                            class="img-thumbnail"
                                            style="max-width:250px;">

                                    <?php

                                    }
                                } else {

                                    ?>

                                    <p class="text-muted">

                                        Belum ada file.

                                    </p>

                                <?php } ?>

                            </div>

                            <div class="mb-4">

                                <label class="form-label">

                                    Ganti File

                                </label>

                                <input
                                    type="file"
                                    name="file"
                                    class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png,.webp">

                                <small class="text-muted">

                                    Kosongkan jika tidak ingin mengganti file.

                                </small>

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

    <script>
        const title = document.getElementById("title");
        const slug = document.getElementById("slug");

        title.addEventListener("keyup", function() {

            let value = this.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');

            slug.value = value;

        });
    </script>

</body>

</html>