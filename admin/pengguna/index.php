<?php

require_once '../../config/app.php';

// ======================================================
// Ambil Data User
// ======================================================

// Sesuaikan dengan session login Anda
$userId = $_SESSION['user']['id'] ?? 1;

$query = mysqli_query($conn, "
    SELECT *
    FROM users
    WHERE id = '$userId'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = "Data pengguna tidak ditemukan.";

    header("Location:" . APP_URL . "/admin/dashboard/");
    exit;
}

$user = mysqli_fetch_assoc($query);

$title = "Profil Saya";
$page  = "pengguna";

include APP_PATH . "includes/admin/layout-top.php";

?>

<main class="p-8">

    <!-- Header -->

    <div class="flex items-center justify-between mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Profil Saya

            </h1>

            <p class="text-slate-500 mt-2">

                Kelola informasi akun administrator website.

            </p>

        </div>

        <?php if (isset($_SESSION["success"])): ?>
            <div class="rounded-xl border border-teal-200 bg-teal-50 px-5 py-4 text-teal-700">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= $_SESSION["success"] ?>
            </div>
            <?php unset($_SESSION["success"]); ?>
        <?php endif; ?>


        <div class="flex gap-3">

            <a
                href="edit.php"
                class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white">

                <i class="bi bi-pencil-square"></i>

                Edit Profil

            </a>

            <a
                href="ganti-password.php"
                class="px-5 py-3 rounded-xl border hover:bg-slate-50">

                <i class="bi bi-key"></i>

                Ganti Password

            </a>

        </div>

    </div>

    <!-- Profil -->

    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-8 py-10 text-white">

            <div class="flex items-center gap-6">

                <div class="w-24 h-24 rounded-full bg-white/20 flex items-center justify-center text-5xl">

                    <i class="bi bi-person-circle"></i>

                </div>

                <div>

                    <h2 class="text-3xl font-bold">

                        <?= htmlspecialchars($user['nama']); ?>

                    </h2>

                    <p class="mt-2 opacity-90">

                        Administrator Website Desa

                    </p>

                </div>

            </div>

        </div>

        <div class="p-8">

            <div class="grid md:grid-cols-2 gap-8">

                <div>

                    <label class="text-sm text-slate-500">

                        Nama Lengkap

                    </label>

                    <p class="mt-2 text-lg font-semibold">

                        <?= htmlspecialchars($user['nama']); ?>

                    </p>

                </div>

                <div>

                    <label class="text-sm text-slate-500">

                        Username

                    </label>

                    <p class="mt-2 text-lg font-semibold">

                        <?= htmlspecialchars($user['username']); ?>

                    </p>

                </div>

                <div>

                    <label class="text-sm text-slate-500">

                        Email

                    </label>

                    <p class="mt-2 text-lg font-semibold">

                        <?= htmlspecialchars($user['email']); ?>

                    </p>

                </div>

                <div>

                    <label class="text-sm text-slate-500">

                        Role

                    </label>

                    <p class="mt-2">

                        <?php if ($user['role'] == 1): ?>

                            <span class="inline-flex px-4 py-2 rounded-full bg-red-100 text-red-700 font-semibold">

                                Administrator

                            </span>

                        <?php else: ?>

                            <span class="inline-flex px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold">

                                Operator

                            </span>

                        <?php endif; ?>

                    </p>

                </div>

                <div class="md:col-span-2">

                    <label class="text-sm text-slate-500">

                        Akun Dibuat

                    </label>

                    <p class="mt-2 text-lg font-semibold">

                        <?= date('d F Y H:i', strtotime($user['created_at'])); ?>

                    </p>

                </div>

            </div>

        </div>

    </div>

</main>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>