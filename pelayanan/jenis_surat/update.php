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

$id              = (int) $_POST['id'];
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

    header("Location: edit.php?id=" . $id);
    exit;
}

// ======================================
// Validasi Link Google Form
// ======================================

if (

    !empty($google_form)

    &&

    !filter_var($google_form, FILTER_VALIDATE_URL)

) {

    $_SESSION['success'] = "Link Google Form tidak valid.";

    header("Location: edit.php?id=" . $id);
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

AND id != '$id'

LIMIT 1

");

if (mysqli_num_rows($cek) > 0) {

    $slug .= "-" . time();
}

// ======================================
// Update Database
// ======================================

$sql = "

UPDATE jenis_surat

SET

nama='$nama',

slug='$slug',

deskripsi='$deskripsi',

icon='$icon',

google_form='$google_form',

estimasi_hari='$estimasi_hari',

persyaratan='$persyaratan',

is_active='$is_active',

urutan='$urutan',

updated_at=NOW()

WHERE id='$id'

";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Jenis surat berhasil diperbarui.";
} else {

    $_SESSION['success'] = "Gagal memperbarui jenis surat.";
}

header("Location: index.php");
exit;
