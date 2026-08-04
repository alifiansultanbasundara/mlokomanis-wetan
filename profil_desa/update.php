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

$id                     = (int) $_POST['id'];

$nama_desa              = trim($_POST['nama_desa']);
$kecamatan              = trim($_POST['kecamatan']);
$kabupaten              = trim($_POST['kabupaten']);
$provinsi               = trim($_POST['provinsi']);
$kode_pos               = trim($_POST['kode_pos']);
$alamat                 = trim($_POST['alamat']);

$luas_wilayah           = $_POST['luas_wilayah'];
$jumlah_penduduk        = (int) $_POST['jumlah_penduduk'];
$jumlah_kk              = (int) $_POST['jumlah_kk'];

$sejarah                = trim($_POST['sejarah']);
$visi                   = trim($_POST['visi']);
$misi                   = trim($_POST['misi']);
$motto                  = trim($_POST['motto']);

$nama_kepala_desa       = trim($_POST['nama_kepala_desa']);
$sambutan_kepala        = trim($_POST['sambutan_kepala']);

$telepon                = trim($_POST['telepon']);
$email                  = trim($_POST['email']);
$website                = trim($_POST['website']);
$google_maps            = trim($_POST['google_maps']);

$facebook               = trim($_POST['facebook']);
$instagram              = trim($_POST['instagram']);
$youtube                = trim($_POST['youtube']);
$tiktok                 = trim($_POST['tiktok']);

$foto_kepala = $_POST['old_foto_kepala'];
$logo_desa   = $_POST['old_logo_desa'];
$foto_kantor = $_POST['old_foto_kantor'];

$uploadDir = "uploads/profil_desa/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// ======================================
// Upload Foto Kepala Desa
// ======================================

if (!empty($_FILES['foto_kepala']['name'])) {

    if (!empty($foto_kepala) && file_exists($uploadDir . $foto_kepala)) {
        unlink($uploadDir . $foto_kepala);
    }

    $ext = pathinfo($_FILES['foto_kepala']['name'], PATHINFO_EXTENSION);
    $foto_kepala = time() . "_kepala." . $ext;

    move_uploaded_file(
        $_FILES['foto_kepala']['tmp_name'],
        $uploadDir . $foto_kepala
    );
}

// ======================================
// Upload Logo Desa
// ======================================

if (!empty($_FILES['logo_desa']['name'])) {

    if (!empty($logo_desa) && file_exists($uploadDir . $logo_desa)) {
        unlink($uploadDir . $logo_desa);
    }

    $ext = pathinfo($_FILES['logo_desa']['name'], PATHINFO_EXTENSION);
    $logo_desa = time() . "_logo." . $ext;

    move_uploaded_file(
        $_FILES['logo_desa']['tmp_name'],
        $uploadDir . $logo_desa
    );
}

// ======================================
// Upload Foto Kantor
// ======================================

if (!empty($_FILES['foto_kantor']['name'])) {

    if (!empty($foto_kantor) && file_exists($uploadDir . $foto_kantor)) {
        unlink($uploadDir . $foto_kantor);
    }

    $ext = pathinfo($_FILES['foto_kantor']['name'], PATHINFO_EXTENSION);
    $foto_kantor = time() . "_kantor." . $ext;

    move_uploaded_file(
        $_FILES['foto_kantor']['tmp_name'],
        $uploadDir . $foto_kantor
    );
}

// ======================================
// Update Database
// ======================================

$sql = "

UPDATE profil_desa SET

nama_desa='$nama_desa',

kecamatan='$kecamatan',

kabupaten='$kabupaten',

provinsi='$provinsi',

kode_pos='$kode_pos',

alamat='$alamat',

luas_wilayah='$luas_wilayah',

jumlah_penduduk='$jumlah_penduduk',

jumlah_kk='$jumlah_kk',

sejarah='$sejarah',

visi='$visi',

misi='$misi',

motto='$motto',

nama_kepala_desa='$nama_kepala_desa',

sambutan_kepala='$sambutan_kepala',

foto_kepala='$foto_kepala',

logo_desa='$logo_desa',

foto_kantor='$foto_kantor',

telepon='$telepon',

email='$email',

website='$website',

google_maps='$google_maps',

facebook='$facebook',

instagram='$instagram',

youtube='$youtube',

tiktok='$tiktok',

updated_at=NOW()

WHERE id='$id'

";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Profil desa berhasil diperbarui.";
} else {

    $_SESSION['success'] = "Gagal memperbarui profil desa.";
}

header("Location: index.php");
exit;
