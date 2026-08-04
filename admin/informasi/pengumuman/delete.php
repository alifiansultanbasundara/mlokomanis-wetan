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
// Ambil Data Pengumuman
// =====================================

$query = mysqli_query(
    $conn,
    "SELECT * FROM announcements WHERE slug = '$slug' LIMIT 1"
);

if (mysqli_num_rows($query) === 0) {

    $_SESSION['error'] = "Pengumuman tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$pengumuman = mysqli_fetch_assoc($query);


// =====================================
// Hapus Pengumuman
// =====================================

$delete = mysqli_query(
    $conn,
    "DELETE FROM announcements WHERE slug = '$slug'"
);


// =====================================
// Response
// =====================================

if ($delete) {

    $_SESSION['success'] = "Pengumuman berhasil dihapus.";
} else {

    $_SESSION['error'] = "Gagal menghapus pengumuman.";
}

header("Location: index.php");
exit;
