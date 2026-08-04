<?php

require_once '../../../config/app.php';

// =====================================
// Hanya menerima POST
// =====================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// =====================================
// Ambil Data
// =====================================

$id             = (int) $_POST['id'];
$title          = mysqli_real_escape_string($conn, trim($_POST['title']));
$slug           = mysqli_real_escape_string($conn, strtolower(trim($_POST['slug'])));
$excerpt        = mysqli_real_escape_string($conn, trim($_POST['excerpt']));
$content        = mysqli_real_escape_string($conn, trim($_POST['content']));
$category       = mysqli_real_escape_string($conn, $_POST['category']);
$status         = mysqli_real_escape_string($conn, $_POST['status']);
$old_thumbnail  = $_POST['old_thumbnail'] ?? "";

// =====================================
// Validasi
// =====================================

if (
    empty($id) ||
    empty($title) ||
    empty($slug) ||
    empty($content)
) {

    $_SESSION['error'] = "Mohon lengkapi seluruh data yang wajib diisi.";

    header("Location: edit.php?id=$id");
    exit;
}

// =====================================
// Validasi Status
// =====================================

$allowedStatus = ['Draft', 'Published'];

if (!in_array($status, $allowedStatus)) {

    $_SESSION['error'] = "Status tidak valid.";

    header("Location: edit.php?id=$id");
    exit;
}

// =====================================
// Cek Slug (Selain dirinya sendiri)
// =====================================

$check = mysqli_query(
    $conn,
    "SELECT id FROM articles
     WHERE slug='$slug'
     AND id != $id"
);

if (mysqli_num_rows($check) > 0) {

    $_SESSION['error'] = "Slug sudah digunakan.";

    header("Location: edit.php?id=$id");
    exit;
}

// =====================================
// Upload Thumbnail
// =====================================

$thumbnail = $old_thumbnail;

if (!empty($_FILES['thumbnail']['name'])) {

    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

    $tmp      = $_FILES['thumbnail']['tmp_name'];
    $fileName = $_FILES['thumbnail']['name'];
    $size     = $_FILES['thumbnail']['size'];

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt)) {

        $_SESSION['error'] = "Format gambar harus JPG, PNG, atau WEBP.";

        header("Location: edit.php?id=$id");
        exit;
    }

    if ($size > (2 * 1024 * 1024)) {

        $_SESSION['error'] = "Ukuran gambar maksimal 2 MB.";

        header("Location: edit.php?id=$id");
        exit;
    }

    $uploadPath = APP_PATH . "uploads/informasi/berita/";

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $thumbnail = uniqid('berita_', true) . "." . $ext;

    if (!move_uploaded_file($tmp, $uploadPath . $thumbnail)) {

        $_SESSION['error'] = "Upload thumbnail gagal.";

        header("Location: edit.php?id=$id");
        exit;
    }

    // =====================================
    // Hapus Thumbnail Lama
    // =====================================

    if (
        !empty($old_thumbnail) &&
        file_exists($uploadPath . $old_thumbnail)
    ) {
        unlink($uploadPath . $old_thumbnail);
    }
}

// =====================================
// Update Database
// =====================================

$sql = "
UPDATE articles
SET
    title       = '$title',
    slug        = '$slug',
    excerpt     = '$excerpt',
    content     = '$content',
    thumbnail   = '$thumbnail',
    category    = '$category',
    status      = '$status'
WHERE id = $id
";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Berita berhasil diperbarui.";
} else {

    $_SESSION['error'] = "Gagal memperbarui berita.";
}

header("Location: index.php");
exit;
