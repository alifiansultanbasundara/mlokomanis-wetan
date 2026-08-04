<?php

require_once '../../config/app.php';

// ==============================================
// Validasi Request
// ==============================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location:index.php");
    exit;
}

// ==============================================
// Ambil Data
// ==============================================

$id       = (int) $_POST['id'];
$nama     = mysqli_real_escape_string($conn, trim($_POST['nama']));
$username = mysqli_real_escape_string($conn, trim($_POST['username']));
$email    = mysqli_real_escape_string($conn, trim($_POST['email']));

// ==============================================
// Validasi
// ==============================================

if (empty($nama) || empty($username) || empty($email)) {

    $_SESSION['error'] = "Semua field wajib diisi.";

    header("Location:edit.php");
    exit;
}

// ==============================================
// Cek User
// ==============================================

$checkUser = mysqli_query($conn, "
    SELECT id
    FROM users
    WHERE id = '$id'
    LIMIT 1
");

if (mysqli_num_rows($checkUser) == 0) {

    $_SESSION['error'] = "Pengguna tidak ditemukan.";

    header("Location:index.php");
    exit;
}

// ==============================================
// Username sudah dipakai?
// ==============================================

$checkUsername = mysqli_query($conn, "
    SELECT id
    FROM users
    WHERE username = '$username'
    AND id != '$id'
    LIMIT 1
");

if (mysqli_num_rows($checkUsername) > 0) {

    $_SESSION['error'] = "Username sudah digunakan.";

    header("Location:edit.php");
    exit;
}

// ==============================================
// Email sudah dipakai?
// ==============================================

$checkEmail = mysqli_query($conn, "
    SELECT id
    FROM users
    WHERE email = '$email'
    AND id != '$id'
    LIMIT 1
");

if (mysqli_num_rows($checkEmail) > 0) {

    $_SESSION['error'] = "Email sudah digunakan.";

    header("Location:edit.php");
    exit;
}

// ==============================================
// Update
// ==============================================

$query = mysqli_query($conn, "
    UPDATE users
    SET
        nama = '$nama',
        username = '$username',
        email = '$email'
    WHERE id = '$id'
");

// ==============================================
// Response
// ==============================================

if ($query) {

    // Jika menggunakan session login,
    // update juga datanya agar tidak logout.

    if (isset($_SESSION['user'])) {

        $_SESSION['user']['nama'] = $nama;
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['email'] = $email;
    }

    $_SESSION['success'] = "Profil berhasil diperbarui.";
} else {

    $_SESSION['error'] = "Gagal memperbarui profil.";
}

header("Location:index.php");
exit;
