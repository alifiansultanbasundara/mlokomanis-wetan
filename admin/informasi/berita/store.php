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

$title      = mysqli_real_escape_string($conn, trim($_POST['title']));
$slug       = mysqli_real_escape_string($conn, strtolower(trim($_POST['slug'])));
$excerpt    = mysqli_real_escape_string($conn, trim($_POST['excerpt']));
$content    = mysqli_real_escape_string($conn, trim($_POST['content']));
$category   = mysqli_real_escape_string($conn, $_POST['category']);
$status     = mysqli_real_escape_string($conn, $_POST['status']);
$author_id  = $_SESSION['id'];

// =====================================
// Validasi
// =====================================

if (
    empty($title) ||
    empty($slug) ||
    empty($content)
) {
    $_SESSION['error'] = "Mohon lengkapi seluruh data yang wajib diisi.";
    header("Location: create.php");
    exit;
}

// =====================================
// Validasi Status
// =====================================

$allowedStatus = ['Draft', 'Published'];

if (!in_array($status, $allowedStatus)) {

    $_SESSION['error'] = "Status tidak valid.";

    header("Location: create.php");
    exit;
}

// =====================================
// Cek Slug
// =====================================

$check = mysqli_query(
    $conn,
    "SELECT id FROM articles WHERE slug='$slug'"
);

if (mysqli_num_rows($check) > 0) {

    $_SESSION['error'] = "Slug sudah digunakan.";

    header("Location: create.php");
    exit;
}

// =====================================
// Upload Thumbnail
// =====================================

$thumbnail = null;

if (!empty($_FILES['thumbnail']['name'])) {

    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

    $tmp      = $_FILES['thumbnail']['tmp_name'];
    $fileName = $_FILES['thumbnail']['name'];
    $size     = $_FILES['thumbnail']['size'];

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt)) {

        $_SESSION['error'] = "Format gambar harus JPG, PNG, atau WEBP.";

        header("Location: create.php");
        exit;
    }

    if ($size > (2 * 1024 * 1024)) {

        $_SESSION['error'] = "Ukuran gambar maksimal 2 MB.";

        header("Location: create.php");
        exit;
    }

    $uploadPath = APP_PATH . "uploads/informasi/berita/";

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $thumbnail = uniqid('berita_', true) . "." . $ext;

    if (!move_uploaded_file($tmp, $uploadPath . $thumbnail)) {

        $_SESSION['error'] = "Upload thumbnail gagal.";

        header("Location: create.php");
        exit;
    }
}

// =====================================
// Simpan Database
// =====================================

$sql = "
INSERT INTO articles
(
    title,
    slug,
    excerpt,
    content,
    thumbnail,
    category,
    status,
    author_id
)
VALUES
(
    '$title',
    '$slug',
    '$excerpt',
    '$content',
    '$thumbnail',
    '$category',
    '$status',
    '$author_id'
)";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Berita berhasil ditambahkan.";
} else {

    $_SESSION['error'] = "Gagal menambahkan berita.";
}

header("Location: index.php");
exit;
