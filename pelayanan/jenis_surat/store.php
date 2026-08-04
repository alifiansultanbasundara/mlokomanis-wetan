<?php

include "../../auth/auth.php";
include "../../config/database.php";

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

$nama            = trim($_POST['nama']);
$slug            = trim($_POST['slug']);
$deskripsi       = trim($_POST['deskripsi']);
$icon            = trim($_POST['icon']);
$google_form     = trim($_POST['google_form']);
$estimasi_hari   = (int) $_POST['estimasi_hari'];
$persyaratan     = trim($_POST['persyaratan']);
$is_active       = (int) $_POST['is_active'];
$urutan          = (int) $_POST['urutan'];

// ======================================
// Validasi
// ======================================

if (

    empty($nama)

) {

    $_SESSION['success'] = "Nama surat wajib diisi.";

    header("Location: create.php");
    exit;
}

// ======================================
// Generate Slug
// ======================================

if (empty($slug)) {

    $slug = strtolower($nama);

    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

    $slug = trim($slug, '-');
}

// ======================================
// Cek Slug Duplikat
// ======================================

$cek = mysqli_query($conn, "

SELECT id

FROM jenis_surat

WHERE slug='$slug'

LIMIT 1

");

if (mysqli_num_rows($cek) > 0) {

    $slug .= "-" . time();
}

// ======================================
// Simpan Database
// ======================================

$sql = "

INSERT INTO jenis_surat(

nama,

slug,

deskripsi,

icon,

google_form,

estimasi_hari,

persyaratan,

is_active,

urutan,

created_at,

updated_at

)

VALUES(

'$nama',

'$slug',

'$deskripsi',

'$icon',

'$google_form',

'$estimasi_hari',

'$persyaratan',

'$is_active',

'$urutan',

NOW(),

NOW()

)

";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Jenis surat berhasil ditambahkan.";
} else {

    $_SESSION['success'] = "Gagal menambahkan jenis surat.";
}

header("Location:index.php");
exit;
