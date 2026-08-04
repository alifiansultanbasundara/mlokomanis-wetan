<?php

require_once '../../config/app.php';

// ======================================================
// Ambil Data User
// ======================================================

$userId = $_SESSION['user']['id'] ?? 1;

$query = mysqli_query($conn, "
    SELECT id, nama, username
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

$title = "Ganti Password";
$page  = "pengguna";

include APP_PATH . "includes/admin/layout-top.php";

?>

<main class="p-8">

    <!-- Header -->

    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Ganti Password
            </h1>

            <p class="text-slate-500 mt-2">
                Perbarui password akun administrator.
            </p>

        </div>

        <a
            href="index.php"
            class="px-5 py-3 border rounded-xl hover:bg-slate-50">

            <i class="bi bi-arrow-left"></i>
            Kembali

        </a>

    </div>

    <!-- Informasi Akun -->

    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-8">

        <div class="flex items-center gap-3">

            <i class="bi bi-person-circle text-4xl text-blue-600"></i>

            <div>

                <h3 class="font-semibold text-lg">

                    <?= htmlspecialchars($user['nama']); ?>

                </h3>

                <p class="text-slate-600">

                    Username :
                    <strong><?= htmlspecialchars($user['username']); ?></strong>

                </p>

            </div>

        </div>

    </div>

    <!-- Form -->

    <form
        action="update-password.php"
        method="POST"
        class="space-y-8">

        <input
            type="hidden"
            name="id"
            value="<?= $user['id']; ?>">


        <div class="bg-white rounded-2xl border shadow-sm p-8">


            <h2 class="text-lg font-semibold mb-6">

                Password Baru

            </h2>


            <div class="space-y-6">



                <!-- Password Lama -->

                <div
                    x-data="{ show:false }">

                    <label class="block font-medium text-slate-700">

                        Password Lama

                    </label>


                    <div class="relative mt-2">


                        <input
                            :type="show ? 'text' : 'password'"
                            name="old_password"
                            required
                            class="w-full border rounded-xl px-4 py-3 pr-12">



                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700">


                            <i
                                class="bi"
                                :class="show ? 'bi-eye-slash' : 'bi-eye'">
                            </i>


                        </button>


                    </div>


                </div>






                <!-- Password Baru -->


                <div
                    x-data="{ show:false }">


                    <label class="block font-medium text-slate-700">

                        Password Baru

                    </label>


                    <div class="relative mt-2">


                        <input
                            :type="show ? 'text' : 'password'"
                            name="new_password"
                            required
                            minlength="6"
                            class="w-full border rounded-xl px-4 py-3 pr-12">



                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700">


                            <i
                                class="bi"
                                :class="show ? 'bi-eye-slash' : 'bi-eye'">
                            </i>


                        </button>


                    </div>



                    <p class="text-sm text-slate-500 mt-2">

                        Minimal 6 karakter.

                    </p>


                </div>








                <!-- Konfirmasi Password -->


                <div
                    x-data="{ show:false }">


                    <label class="block font-medium text-slate-700">

                        Konfirmasi Password Baru

                    </label>


                    <div class="relative mt-2">


                        <input
                            :type="show ? 'text' : 'password'"
                            name="confirm_password"
                            required
                            minlength="6"
                            class="w-full border rounded-xl px-4 py-3 pr-12">





                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700">


                            <i
                                class="bi"
                                :class="show ? 'bi-eye-slash' : 'bi-eye'">
                            </i>


                        </button>


                    </div>


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


                <i class="bi bi-key"></i>


                Simpan Password


            </button>



        </div>



    </form>

</main>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>