<?php

require_once '../../../config/app.php';

$query = mysqli_query($conn, "
    SELECT *
    FROM village_profiles
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = "Silakan lengkapi Profil Desa terlebih dahulu.";

    header("Location:../../profil-desa/tentang-desa/index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

$title = "Informasi Kontak";
$page  = "kontak";

include APP_PATH . "includes/admin/layout-top.php";

?>

<main class="p-8">

    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Informasi Kontak
            </h1>

            <p class="text-slate-500 mt-2">
                Lengkapi informasi kontak resmi Pemerintah Desa.
            </p>

        </div>

        <a
            href="index.php"
            class="px-5 py-3 border rounded-xl hover:bg-slate-50">

            <i class="bi bi-arrow-left"></i>
            Kembali

        </a>

    </div>

    <form
        action="update.php"
        method="POST"
        class="space-y-8">

        <input type="hidden" name="id" value="<?= $data['id']; ?>">

        <!-- Kontak -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <h2 class="font-semibold text-lg mb-6">

                Informasi Kontak

            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <div>

                    <label>Nomor Telepon</label>

                    <input
                        type="text"
                        name="phone"
                        value="<?= htmlspecialchars($data['phone']); ?>"
                        class="w-full mt-2 border rounded-xl px-4 py-3">

                </div>

                <div>

                    <label>WhatsApp</label>

                    <input
                        type="text"
                        name="whatsapp"
                        value="<?= htmlspecialchars($data['whatsapp']); ?>"
                        class="w-full mt-2 border rounded-xl px-4 py-3">

                </div>

                <div>

                    <label>Fax</label>

                    <input
                        type="text"
                        name="fax"
                        value="<?= htmlspecialchars($data['fax']); ?>"
                        class="w-full mt-2 border rounded-xl px-4 py-3">

                </div>

                <div>

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        value="<?= htmlspecialchars($data['email']); ?>"
                        class="w-full mt-2 border rounded-xl px-4 py-3">

                </div>

                <div class="md:col-span-2">

                    <label>Website</label>

                    <input
                        type="url"
                        name="website"
                        value="<?= htmlspecialchars($data['website']); ?>"
                        class="w-full mt-2 border rounded-xl px-4 py-3">

                </div>

            </div>

        </div>

        <!-- Jam Pelayanan -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <h2 class="font-semibold text-lg mb-6">

                Jam Pelayanan

            </h2>

            <textarea
                name="office_hours"
                rows="5"
                placeholder="Contoh:
Senin - Kamis : 08.00 - 15.00
Jumat : 08.00 - 11.00
Sabtu - Minggu : Libur"
                class="w-full border rounded-xl px-4 py-3"><?= htmlspecialchars($data['office_hours']); ?></textarea>

        </div>

        <!-- Sosial Media -->

        <!-- Sosial Media -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <h2 class="font-semibold text-lg mb-6">
                Sosial Media Resmi
            </h2>


            <div class="grid md:grid-cols-2 gap-6">


                <!-- Facebook -->

                <div>

                    <label class="block font-medium text-slate-700 mb-2">

                        Facebook

                    </label>


                    <input
                        type="url"
                        name="facebook"
                        value="<?= htmlspecialchars($data['facebook']); ?>"
                        placeholder="https://facebook.com/nama-akun"
                        class="w-full border rounded-xl px-4 py-3">

                </div>





                <!-- Instagram -->

                <div>

                    <label class="block font-medium text-slate-700 mb-2">

                        Instagram

                    </label>


                    <input
                        type="url"
                        name="instagram"
                        value="<?= htmlspecialchars($data['instagram']); ?>"
                        placeholder="https://instagram.com/nama-akun"
                        class="w-full border rounded-xl px-4 py-3">

                </div>







                <!-- Youtube -->

                <div>

                    <label class="block font-medium text-slate-700 mb-2">

                        YouTube

                    </label>


                    <input
                        type="url"
                        name="youtube"
                        value="<?= htmlspecialchars($data['youtube']); ?>"
                        placeholder="https://youtube.com/@channel"
                        class="w-full border rounded-xl px-4 py-3">

                </div>







                <!-- Twitter / X -->

                <div>

                    <label class="block font-medium text-slate-700 mb-2">

                        Twitter / X

                    </label>


                    <input
                        type="url"
                        name="twitter"
                        value="<?= htmlspecialchars($data['twitter']); ?>"
                        placeholder="https://twitter.com/nama-akun"
                        class="w-full border rounded-xl px-4 py-3">

                </div>








                <!-- TikTok -->

                <div>

                    <label class="block font-medium text-slate-700 mb-2">

                        TikTok

                    </label>


                    <input
                        type="url"
                        name="tiktok"
                        value="<?= htmlspecialchars($data['tiktok']); ?>"
                        placeholder="https://tiktok.com/@nama-akun"
                        class="w-full border rounded-xl px-4 py-3">

                </div>



            </div>


        </div>

        <div class="flex justify-end gap-3">

            <a
                href="index.php"
                class="px-6 py-3 border rounded-xl">

                Batal

            </a>

            <button
                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl">

                <i class="bi bi-check-circle"></i>

                Simpan

            </button>

        </div>

    </form>

</main>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>