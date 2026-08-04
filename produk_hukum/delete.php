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

FROM produk_hukum

WHERE id='$id'

");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['success'] = "Data produk hukum tidak ditemukan.";

    header("Location:index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);


// ======================================
// Hapus File PDF
// ======================================

$uploadDir = "uploads/produk_hukum/";

if (

    !empty($data['file_pdf'])

    &&

    file_exists($uploadDir . $data['file_pdf'])

) {

    unlink($uploadDir . $data['file_pdf']);
}


// ======================================
// Hapus Database
// ======================================

$delete = mysqli_query($conn, "

DELETE FROM produk_hukum

WHERE id='$id'

");

if ($delete) {

    $_SESSION['success'] = "Produk hukum berhasil dihapus.";
} else {

    $_SESSION['success'] = "Gagal menghapus produk hukum.";
}

header("Location:index.php");
exit;
