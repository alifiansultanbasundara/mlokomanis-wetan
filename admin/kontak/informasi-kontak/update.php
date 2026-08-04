<?php

require_once '../../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location:index.php");
    exit;
}

// ======================================================
// Validasi
// ======================================================

$id = (int) $_POST['id'];

$check = mysqli_query($conn, "
    SELECT id
    FROM village_profiles
    WHERE id = '$id'
    LIMIT 1
");

if (mysqli_num_rows($check) == 0) {

    $_SESSION['error'] = "Data profil desa tidak ditemukan.";

    header("Location:index.php");
    exit;
}

// ======================================================
// Ambil Data
// ======================================================

$phone         = mysqli_real_escape_string($conn, trim($_POST['phone']));
$whatsapp      = mysqli_real_escape_string($conn, trim($_POST['whatsapp']));
$fax           = mysqli_real_escape_string($conn, trim($_POST['fax']));
$email         = mysqli_real_escape_string($conn, trim($_POST['email']));
$website       = mysqli_real_escape_string($conn, trim($_POST['website']));
$office_hours  = mysqli_real_escape_string($conn, trim($_POST['office_hours']));

$facebook      = mysqli_real_escape_string($conn, trim($_POST['facebook']));
$instagram     = mysqli_real_escape_string($conn, trim($_POST['instagram']));
$youtube       = mysqli_real_escape_string($conn, trim($_POST['youtube']));
$twitter       = mysqli_real_escape_string($conn, trim($_POST['twitter']));
$tiktok        = mysqli_real_escape_string($conn, trim($_POST['tiktok']));

// ======================================================
// Update
// ======================================================

$query = "
UPDATE village_profiles
SET

    phone = '$phone',
    whatsapp = '$whatsapp',
    fax = '$fax',
    email = '$email',
    website = '$website',

    office_hours = '$office_hours',

    facebook = '$facebook',
    instagram = '$instagram',
    youtube = '$youtube',
    twitter = '$twitter',
    tiktok = '$tiktok',

    updated_at = CURRENT_TIMESTAMP

WHERE id = '$id'
";

if (mysqli_query($conn, $query)) {

    $_SESSION['success'] = "Informasi kontak berhasil diperbarui.";
} else {

    $_SESSION['error'] = "Gagal memperbarui informasi kontak.";
}

header("Location:index.php");
exit;
