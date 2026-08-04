<?php
session_start();
include "../config/database.php";

if (isset($_POST['register'])) {

    $role = 1;
    $nama = htmlspecialchars($_POST['nama']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);

    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password != $confirm) {
        $_SESSION['error'] = "Konfirmasi password tidak sama.";
        header("Location: register.php");
        exit;
    }

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' OR username='$username'");

    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "Email atau Username sudah digunakan.";
        header("Location: register.php");
        exit;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO users(role,nama,username,email,password)
    VALUES('$role','$nama','$username','$email','$password')");

    $_SESSION['success'] = "Registrasi berhasil.";
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
</head>

<body>

    <h2>Register</h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo $_SESSION['error'];
        unset($_SESSION['error']);
    }
    ?>

    <form method="POST">

        Nama <br>
        <input type="text" name="nama" required><br><br>

        Username <br>
        <input type="text" name="username" required><br><br>

        Email <br>
        <input type="email" name="email" required><br><br>

        Password <br>
        <input type="password" name="password" required><br><br>

        Verifikasi Password <br>
        <input type="password" name="confirm_password" required><br><br>

        <button name="register">Register</button>

    </form>

    <a href="login.php">Login</a>

</body>

</html>