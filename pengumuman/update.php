<?php

include "../auth/auth.php";
include "../config/database.php";

// ======================================
// Validasi Request
// ======================================

if ($_SERVER['REQUEST_METHOD'] != "POST") {

    header("Location: index.php");
    exit;
}

// ======================================
// Ambil Data
// ======================================

$id                 = (int) $_POST['id'];

$judul              = trim($_POST['judul']);
$isi                = trim($_POST['isi']);
$kategori           = trim($_POST['kategori']);
$tanggal_mulai      = !empty($_POST['tanggal_mulai']) ? $_POST['tanggal_mulai'] : NULL;
$tanggal_selesai    = !empty($_POST['tanggal_selesai']) ? $_POST['tanggal_selesai'] : NULL;
$prioritas          = trim($_POST['prioritas']);
$status             = trim($_POST['status']);

$gambar = $_POST['old_gambar'];

// ======================================
// Validasi
// ======================================

if (empty($judul) || empty($isi)) {

    $_SESSION['success'] = "Judul dan isi pengumuman wajib diisi.";

    header("Location: edit.php?id=" . $id);
    exit;
}

// ======================================
// Upload Gambar
// ======================================

$uploadDir = "uploads/pengumuman/";

if (!is_dir($uploadDir)) {

    mkdir($uploadDir, 0777, true);
}

if (!empty($_FILES['gambar']['name'])) {

    $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {

        $_SESSION['success'] = "Format gambar tidak didukung.";

        header("Location: edit.php?id=" . $id);
        exit;
    }

    if (!empty($gambar) && file_exists($uploadDir . $gambar)) {

        unlink($uploadDir . $gambar);
    }

    $gambar = time() . "_" . uniqid() . "." . $ext;

    move_uploaded_file(

        $_FILES['gambar']['tmp_name'],

        $uploadDir . $gambar

    );
}

// ======================================
// Update Database
// ======================================

$sql = "

UPDATE pengumuman

SET

judul='$judul',

isi='$isi',

gambar='$gambar',

kategori='$kategori',

tanggal_mulai=" . ($tanggal_mulai ? "'$tanggal_mulai'" : "NULL") . ",

tanggal_selesai=" . ($tanggal_selesai ? "'$tanggal_selesai'" : "NULL") . ",

prioritas='$prioritas',

status='$status',

updated_at=NOW()

WHERE id='$id'

";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Pengumuman berhasil diperbarui.";
} else {

    $_SESSION['success'] = "Gagal memperbarui pengumuman.";
}

header("Location: index.php");
exit;
