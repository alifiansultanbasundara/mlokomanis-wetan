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

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// ======================================
// Hapus Gambar
// ======================================

$uploadDir = "uploads/pengumuman/";

if (

    !empty($data['gambar'])

    &&

    file_exists($uploadDir . $data['gambar'])

) {

    unlink($uploadDir . $data['gambar']);
}

// ======================================
// Hapus Database
// ======================================

$delete = mysqli_query($conn, "

DELETE FROM pengumuman

WHERE id='$id'

");

if ($delete) {

    $_SESSION['success'] = "Pengumuman berhasil dihapus.";
} else {

    $_SESSION['success'] = "Gagal menghapus pengumuman.";
}

header("Location: index.php");
exit;
