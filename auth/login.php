<?php
require_once '../config/app.php';

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($query) == 1) {

        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['password'])) {

            // ===============================
            // Session Login
            // ===============================

            $_SESSION['login'] = true;

            $_SESSION['user'] = [
                'id'    => $user['id'],
                'nama'  => $user['nama'],
                'email' => $user['email'],
                'role'  => $user['role']
            ];


            // Session terpisah (opsional)
            $_SESSION['id']    = $user['id'];
            $_SESSION['nama']  = $user['nama'];
            $_SESSION['role']  = $user['role'];


            header("Location: " . APP_URL . "admin/dashboard.php");
            exit;
        }
    }

    $_SESSION['error'] = "Email atau Password salah.";
    header("Location: " . APP_URL . "auth/login.php");
    exit;
}

$page = 'berita';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <?php
    $title = "Selamat datang di Wbesite Desa Mlokomanis Wetan";
    $metaTitle = "Kontak | Desa Mlokomanis Wetan";
    $metaDescription = "Hubungi Pemerintah Desa Mlokomanis Wetan.";

    include APP_PATH . 'includes/head.php';
    ?>
</head>

<body>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-6">

        <div class="max-w-6xl w-full overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm lg:grid lg:grid-cols-2">

            <!-- Left -->
            <div class="hidden lg:flex flex-col justify-between border-r border-slate-200 bg-white p-12">

                <div>

                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-teal-50">

                        <img src="<?= APP_URL ?>assets/img/logo.webp"
                            alt="Logo Desa"
                            class="h-12 w-12">

                    </div>

                    <span class="mt-8 inline-flex items-center gap-2 rounded-full border border-teal-200 bg-teal-50 px-4 py-2 text-sm font-medium text-teal-700">

                        <i class="bi bi-building"></i>

                        Sistem Informasi Desa

                    </span>

                    <h1 class="mt-8 text-5xl font-bold leading-tight tracking-tight text-slate-900">

                        Desa
                        <br>
                        <span class="text-teal-700">Mlokomanis Wetan</span>

                    </h1>

                    <p class="mt-6 leading-8 text-slate-600">

                        Selamat datang di Sistem Informasi Desa Mlokomanis Wetan.
                        Platform ini digunakan untuk mengelola administrasi desa,
                        berita, pelayanan masyarakat, serta berbagai data pemerintahan
                        secara terpadu.

                    </p>

                </div>

            </div>

            <!-- Right -->
            <div class="flex items-center p-8 md:p-14">

                <div class="mx-auto w-full max-w-md">

                    <div class="flex items-center gap-4">

                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-50">

                            <i class="bi bi-person-lock text-2xl text-teal-700"></i>

                        </div>

                        <div>

                            <h2 class="text-3xl font-bold text-slate-900">

                                Login Administrator

                            </h2>

                            <p class="mt-1 text-slate-500">

                                Masukkan akun administrator untuk melanjutkan.

                            </p>

                        </div>

                    </div>

                    <!-- Alert Success -->
                    <?php if (isset($_SESSION['success'])) { ?>

                        <div class="mt-8 rounded-xl border border-teal-200 bg-teal-50 px-5 py-4 text-sm text-teal-700">

                            <i class="bi bi-check-circle-fill me-2"></i>

                            <?= $_SESSION['success']; ?>

                        </div>

                    <?php unset($_SESSION['success']);
                    } ?>

                    <!-- Alert Error -->
                    <?php if (isset($_SESSION['error'])) { ?>

                        <div class="mt-8 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">

                            <i class="bi bi-exclamation-circle-fill me-2"></i>

                            <?= $_SESSION['error']; ?>

                        </div>

                    <?php unset($_SESSION['error']);
                    } ?>

                    <form method="POST" class="mt-10 space-y-6">

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Email

                            </label>

                            <div class="relative">

                                <i class="bi bi-envelope absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>

                                <input
                                    type="email"
                                    name="email"
                                    required
                                    placeholder="Masukkan alamat email"
                                    class="w-full rounded-xl border border-slate-300 bg-white py-3.5 pl-14 pr-4 outline-none transition focus:border-teal-600">

                            </div>

                        </div>

                        <div
                            x-data="{ showPassword: false }">

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Password

                            </label>


                            <div class="relative">


                                <!-- Lock Icon -->

                                <i class="bi bi-lock absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>



                                <input

                                    :type="showPassword ? 'text' : 'password'"

                                    name="password"

                                    required

                                    placeholder="Masukkan password"

                                    class="w-full rounded-xl border border-slate-300 bg-white py-3.5 pl-14 pr-14 outline-none transition focus:border-teal-600">



                                <!-- Toggle Password -->

                                <button

                                    type="button"

                                    @click="showPassword = !showPassword"

                                    class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-teal-600">


                                    <i

                                        class="bi"

                                        :class="showPassword ? 'bi-eye-slash' : 'bi-eye'">

                                    </i>


                                </button>



                            </div>

                        </div>

                        <button
                            name="login"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-teal-700 py-3.5 font-medium text-white transition hover:bg-teal-800">

                            <i class="bi bi-box-arrow-in-right"></i>

                            Login ke Dashboard

                        </button>

                    </form>

                    <div class="mt-8 text-center">

                        <a
                            href="<?= APP_URL ?>"
                            class="inline-flex items-center gap-2 text-sm font-medium text-teal-700 transition hover:text-teal-800">

                            <i class="bi bi-arrow-left"></i>

                            Kembali ke Website

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <?php include APP_PATH . 'includes/scripts.php'; ?>
</body>

</html>