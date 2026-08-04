<?php

require_once '../../config/app.php';

// ======================================================
// Ambil Data User
// ======================================================

$userId = $_SESSION['user']['id'] ?? 1;

$query = mysqli_query($conn, "
    SELECT *
    FROM users
    WHERE id = '$userId'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = "Data pengguna tidak ditemukan.";

    header("Location:index.php");
    exit;
}

$user = mysqli_fetch_assoc($query);

$title = "Edit Profil";
$page  = "pengguna";

include APP_PATH . "includes/admin/layout-top.php";

?>

<main class="p-8">

    <!-- Header -->

    <div class="flex items-center justify-between mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Edit Profil
            </h1>

            <p class="text-slate-500 mt-2">
                Perbarui informasi akun administrator.
            </p>

        </div>

        <a
            href="index.php"
            class="px-5 py-3 border rounded-xl hover:bg-slate-50">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <!-- Form -->

    <form
        action="update.php"
        method="POST"
        class="space-y-8">

        <input
            type="hidden"
            name="id"
            value="<?= $user['id']; ?>">

        <div class="bg-white rounded-2xl border shadow-sm p-8">

            <div class="grid md:grid-cols-2 gap-6">

                <!-- Nama -->

                <div class="md:col-span-2">

                    <label class="block font-medium text-slate-700">

                        Nama Lengkap

                    </label>

                    <input
                        type="text"
                        name="nama"
                        required
                        value="<?= htmlspecialchars($user['nama']); ?>"
                        class="w-full mt-2 border rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                </div>

                <!-- Username -->

                <div>

                    <label class="block font-medium text-slate-700">

                        Username

                    </label>

                    <input
                        type="text"
                        name="username"
                        required
                        value="<?= htmlspecialchars($user['username']); ?>"
                        class="w-full mt-2 border rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                </div>

                <!-- Email -->

                <div>

                    <label class="block font-medium text-slate-700">

                        Email

                    </label>

                    <input
                        type="email"
                        name="email"
                        required
                        value="<?= htmlspecialchars($user['email']); ?>"
                        class="w-full mt-2 border rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                </div>

            </div>

        </div>

        <!-- Tombol -->

        <div class="flex justify-end gap-3">

            <a
                href="index.php"
                class="px-6 py-3 border rounded-xl hover:bg-slate-50">

                Batal

            </a>

            <button
                type="submit"
                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl">

                <i class="bi bi-check-circle"></i>

                Simpan Perubahan

            </button>

        </div>

    </form>

</main>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>