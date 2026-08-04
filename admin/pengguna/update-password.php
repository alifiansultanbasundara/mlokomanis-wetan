<?php

require_once '../../config/app.php';

// ======================================================
// Validasi Request
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location:index.php");
    exit;
}

// ======================================================
// Ambil Data
// ======================================================

$id               = (int) $_POST['id'];
$oldPassword      = trim($_POST['old_password']);
$newPassword      = trim($_POST['new_password']);
$confirmPassword  = trim($_POST['confirm_password']);

// ======================================================
// Validasi Input
// ======================================================

if (
    empty($oldPassword) ||
    empty($newPassword) ||
    empty($confirmPassword)
) {

    $_SESSION['error'] = "Semua field wajib diisi.";

    header("Location:ganti-password.php");
    exit;
}

if (strlen($newPassword) < 6) {

    $_SESSION['error'] = "Password baru minimal 6 karakter.";

    header("Location:ganti-password.php");
    exit;
}

if ($newPassword !== $confirmPassword) {

    $_SESSION['error'] = "Konfirmasi password tidak sesuai.";

    header("Location:ganti-password.php");
    exit;
}

// ======================================================
// Ambil Data User
// ======================================================

$query = mysqli_query($conn, "
    SELECT *
    FROM users
    WHERE id = '$id'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = "Pengguna tidak ditemukan.";

    header("Location:index.php");
    exit;
}

$user = mysqli_fetch_assoc($query);

// ======================================================
// Cek Password Lama
// ======================================================

if (!password_verify($oldPassword, $user['password'])) {

    $_SESSION['error'] = "Password lama tidak benar.";

    header("Location:ganti-password.php");
    exit;
}

// ======================================================
// Password Baru Tidak Boleh Sama
// ======================================================

if (password_verify($newPassword, $user['password'])) {

    $_SESSION['error'] = "Password baru harus berbeda dari password lama.";

    header("Location:ganti-password.php");
    exit;
}

// ======================================================
// Hash Password Baru
// ======================================================

$newHash = password_hash($newPassword, PASSWORD_DEFAULT);

// ======================================================
// Update
// ======================================================

$update = mysqli_query($conn, "
    UPDATE users
    SET password = '$newHash'
    WHERE id = '$id'
");

// ======================================================
// Response
// ======================================================

if ($update) {

    $_SESSION['success'] = "Password berhasil diperbarui.";
} else {

    $_SESSION['error'] = "Gagal memperbarui password.";
}

header("Location:index.php");
exit;
