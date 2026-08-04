<?php

require_once '../../../config/app.php';

// =====================================
// Validasi Request
// =====================================

if (!isset($_GET['slug']) || empty($_GET['slug'])) {

    header("Location: index.php");
    exit;
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);


// =====================================
// Ambil Data Berita
// =====================================

$query = mysqli_query(
    $conn,
    "SELECT * FROM articles WHERE slug = '$slug' LIMIT 1"
);

if (mysqli_num_rows($query) === 0) {

    $_SESSION['error'] = "Berita tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$berita = mysqli_fetch_assoc($query);


// =====================================
// Hapus Thumbnail
// =====================================

if (!empty($berita['thumbnail'])) {

    $thumbnailPath = APP_PATH . "uploads/informasi/berita/" . $berita['thumbnail'];

    if (file_exists($thumbnailPath)) {
        unlink($thumbnailPath);
    }
}


// =====================================
// Hapus Berita
// =====================================

$delete = mysqli_query(
    $conn,
    "DELETE FROM articles WHERE slug = '$slug'"
);


// =====================================
// Response
// =====================================

if ($delete) {

    $_SESSION['success'] = "Berita berhasil dihapus.";
} else {

    $_SESSION['error'] = "Gagal menghapus berita.";
}

header("Location: index.php");
exit;
