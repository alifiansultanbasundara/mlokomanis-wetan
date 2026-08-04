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

$jenis = trim($_POST['jenis']);
$nomor = trim($_POST['nomor']);
$tahun = (int) $_POST['tahun'];
$judul = trim($_POST['judul']);
$deskripsi = trim($_POST['deskripsi']);

$tanggal_ditetapkan = !empty($_POST['tanggal_ditetapkan'])
    ? $_POST['tanggal_ditetapkan']
    : NULL;

$tanggal_diundangkan = !empty($_POST['tanggal_diundangkan'])
    ? $_POST['tanggal_diundangkan']
    : NULL;

$status = $_POST['status'];


// ======================================
// Validasi
// ======================================

if (

    empty($jenis) ||

    empty($nomor) ||

    empty($tahun) ||

    empty($judul)

) {

    $_SESSION['success'] = "Data belum lengkap.";

    header("Location: create.php");
    exit;
}


// ======================================
// Upload PDF
// ======================================

if (empty($_FILES['file_pdf']['name'])) {

    $_SESSION['success'] = "File PDF wajib diupload.";

    header("Location:create.php");
    exit;
}

$allowed = ["pdf"];

$filename = $_FILES['file_pdf']['name'];

$tmp = $_FILES['file_pdf']['tmp_name'];

$size = $_FILES['file_pdf']['size'];

$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {

    $_SESSION['success'] = "File harus berformat PDF.";

    header("Location:create.php");
    exit;
}

if ($size > (10 * 1024 * 1024)) {

    $_SESSION['success'] = "Ukuran file maksimal 10 MB.";

    header("Location:create.php");
    exit;
}

// Folder Upload

$uploadDir = "uploads/produk_hukum/";

if (!is_dir($uploadDir)) {

    mkdir($uploadDir, 0777, true);
}

$file_pdf = time() . "_" . uniqid() . ".pdf";

move_uploaded_file(

    $tmp,

    $uploadDir . $file_pdf

);


// ======================================
// Simpan Database
// ======================================

$sql = "

INSERT INTO produk_hukum(

jenis,

nomor,

tahun,

judul,

deskripsi,

tanggal_ditetapkan,

tanggal_diundangkan,

file_pdf,

status,

created_at,

updated_at

)

VALUES(

'$jenis',

'$nomor',

'$tahun',

'$judul',

'$deskripsi',

" . ($tanggal_ditetapkan ? "'$tanggal_ditetapkan'" : "NULL") . ",

" . ($tanggal_diundangkan ? "'$tanggal_diundangkan'" : "NULL") . ",

'$file_pdf',

'$status',

NOW(),

NOW()

)

";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Produk hukum berhasil ditambahkan.";
} else {

    $_SESSION['success'] = "Gagal menyimpan produk hukum.";
}

header("Location:index.php");
exit;
