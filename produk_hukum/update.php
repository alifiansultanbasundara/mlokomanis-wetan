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

$id = (int) $_POST['id'];

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

$file_pdf = $_POST['old_file'];


// ======================================
// Validasi
// ======================================

if (

    empty($id) ||

    empty($jenis) ||

    empty($nomor) ||

    empty($tahun) ||

    empty($judul)

) {

    $_SESSION['success'] = "Data belum lengkap.";

    header("Location: edit.php?id=" . $id);
    exit;
}


// ======================================
// Folder Upload
// ======================================

$uploadDir = "uploads/produk_hukum/";

if (!is_dir($uploadDir)) {

    mkdir($uploadDir, 0777, true);
}


// ======================================
// Upload PDF Baru (Opsional)
// ======================================

if (!empty($_FILES['file_pdf']['name'])) {

    $allowed = ["pdf"];

    $filename = $_FILES['file_pdf']['name'];

    $tmp = $_FILES['file_pdf']['tmp_name'];

    $size = $_FILES['file_pdf']['size'];

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {

        $_SESSION['success'] = "File harus berformat PDF.";

        header("Location: edit.php?id=" . $id);
        exit;
    }

    if ($size > (10 * 1024 * 1024)) {

        $_SESSION['success'] = "Ukuran file maksimal 10 MB.";

        header("Location: edit.php?id=" . $id);
        exit;
    }

    $newFile = time() . "_" . uniqid() . ".pdf";

    move_uploaded_file(

        $tmp,

        $uploadDir . $newFile

    );

    // Hapus File Lama

    if (

        !empty($file_pdf)

        &&

        file_exists($uploadDir . $file_pdf)

    ) {

        unlink($uploadDir . $file_pdf);
    }

    $file_pdf = $newFile;
}


// ======================================
// Update Database
// ======================================

$sql = "

UPDATE produk_hukum

SET

jenis='$jenis',

nomor='$nomor',

tahun='$tahun',

judul='$judul',

deskripsi='$deskripsi',

tanggal_ditetapkan=" . ($tanggal_ditetapkan ? "'$tanggal_ditetapkan'" : "NULL") . ",

tanggal_diundangkan=" . ($tanggal_diundangkan ? "'$tanggal_diundangkan'" : "NULL") . ",

file_pdf='$file_pdf',

status='$status',

updated_at=NOW()

WHERE id='$id'

";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Produk hukum berhasil diperbarui.";
} else {

    $_SESSION['success'] = "Gagal memperbarui produk hukum.";
}

header("Location: index.php");
exit;
