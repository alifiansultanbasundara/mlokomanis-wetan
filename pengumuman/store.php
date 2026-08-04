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

$judul             = trim($_POST['judul']);
$isi               = trim($_POST['isi']);
$kategori          = $_POST['kategori'];
$tanggal_mulai     = !empty($_POST['tanggal_mulai']) ? $_POST['tanggal_mulai'] : NULL;
$tanggal_selesai   = !empty($_POST['tanggal_selesai']) ? $_POST['tanggal_selesai'] : NULL;
$prioritas         = $_POST['prioritas'];
$status            = $_POST['status'];

$created_by = isset($_SESSION['nama'])
    ? $_SESSION['nama']
    : 'Administrator';

// ======================================
// Validasi
// ======================================

if (empty($judul) || empty($isi)) {

    $_SESSION['success'] = "Judul dan isi pengumuman wajib diisi.";

    header("Location: create.php");
    exit;
}

// ======================================
// Upload Gambar
// ======================================

$gambar = "";

$uploadDir = "uploads/pengumuman/";

if (!is_dir($uploadDir)) {

    mkdir($uploadDir, 0777, true);
}

if (!empty($_FILES['gambar']['name'])) {

    $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {

        $_SESSION['success'] = "Format gambar tidak didukung.";

        header("Location: create.php");
        exit;
    }

    $gambar = time() . "_" . uniqid() . "." . $ext;

    move_uploaded_file(

        $_FILES['gambar']['tmp_name'],

        $uploadDir . $gambar

    );
}

// ======================================
// Simpan Database
// ======================================

$sql = "

INSERT INTO pengumuman(

judul,

isi,

gambar,

kategori,

tanggal_mulai,

tanggal_selesai,

prioritas,

status,

created_by,

created_at,

updated_at

)

VALUES(

'$judul',

'$isi',

'$gambar',

'$kategori',

" . ($tanggal_mulai ? "'$tanggal_mulai'" : "NULL") . ",

" . ($tanggal_selesai ? "'$tanggal_selesai'" : "NULL") . ",

'$prioritas',

'$status',

'$created_by',

NOW(),

NOW()

)

";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Pengumuman berhasil ditambahkan.";
} else {

    $_SESSION['success'] = "Gagal menambahkan pengumuman.";
}

header("Location:index.php");
exit;
