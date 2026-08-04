<?php

require_once '../../../config/app.php';

// =====================================
// Validasi Request
// =====================================

if (!isset($_GET['slug']) || empty($_GET['slug'])) {

    header("Location: index.php");
    exit;
}

$slug = mysqli_real_escape_string(
    $conn,
    trim($_GET['slug'])
);


// =====================================
// Ambil Data Album
// =====================================

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM galleries
     WHERE slug='$slug'
     LIMIT 1"
);

if (mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = "Album galeri tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$gallery = mysqli_fetch_assoc($query);

$galleryId = $gallery['id'];


// =====================================
// Hapus Semua File Foto
// =====================================

$images = mysqli_query(
    $conn,
    "SELECT image
     FROM gallery_images
     WHERE gallery_id='$galleryId'"
);

while ($img = mysqli_fetch_assoc($images)) {

    if (!empty($img['image'])) {

        $file = APP_PATH . "uploads/informasi/galeri/" . $img['image'];

        if (file_exists($file)) {
            unlink($file);
        }
    }
}


// =====================================
// Hapus Detail Foto
// =====================================

mysqli_query(
    $conn,
    "DELETE
     FROM gallery_images
     WHERE gallery_id='$galleryId'"
);


// =====================================
// Hapus Album
// =====================================

$delete = mysqli_query(
    $conn,
    "DELETE
     FROM galleries
     WHERE id='$galleryId'"
);


// =====================================
// Response
// =====================================

if ($delete) {

    $_SESSION['success'] = "Album galeri berhasil dihapus.";
} else {

    $_SESSION['error'] = "Gagal menghapus album galeri.";
}

header("Location: index.php");
exit;
