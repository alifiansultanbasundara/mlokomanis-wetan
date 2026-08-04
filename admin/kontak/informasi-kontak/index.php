<?php

require_once '../../../config/app.php';

$query = mysqli_query($conn, "
    SELECT *
    FROM village_profiles
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = "Silakan lengkapi Profil Desa terlebih dahulu.";

    header("Location:" . APP_URL . "/admin/profil-desa/tentang-desa/index.php");
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
                Informasi kontak resmi Pemerintah Desa.
            </p>

        </div>

        <a
            href="edit.php"
            class="px-5 py-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white">

            <i class="bi bi-pencil-square"></i>

            Edit Informasi

        </a>

    </div>

    <div class="grid lg:grid-cols-2 gap-6">

        <!-- Informasi Kontak -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <h2 class="text-lg font-semibold mb-6">
                Informasi Kontak
            </h2>

            <div class="space-y-4">

                <div class="flex justify-between border-b pb-3">
                    <span class="text-slate-500">Telepon</span>
                    <span class="font-medium"><?= htmlspecialchars($data['phone']) ?: '-'; ?></span>
                </div>

                <div class="flex justify-between border-b pb-3">
                    <span class="text-slate-500">WhatsApp</span>
                    <span class="font-medium"><?= htmlspecialchars($data['whatsapp']) ?: '-'; ?></span>
                </div>

                <div class="flex justify-between border-b pb-3">
                    <span class="text-slate-500">Fax</span>
                    <span class="font-medium"><?= htmlspecialchars($data['fax']) ?: '-'; ?></span>
                </div>

                <div class="flex justify-between border-b pb-3">
                    <span class="text-slate-500">Email</span>
                    <span class="font-medium"><?= htmlspecialchars($data['email']) ?: '-'; ?></span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-500">Website</span>
                    <span class="font-medium"><?= htmlspecialchars($data['website']) ?: '-'; ?></span>
                </div>

            </div>

        </div>

        <!-- Jam Pelayanan -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <h2 class="text-lg font-semibold mb-6">
                Jam Pelayanan
            </h2>

            <div class="prose max-w-none text-slate-700">

                <?= !empty($data['office_hours'])
                    ? nl2br(htmlspecialchars($data['office_hours']))
                    : '-'; ?>
            </div>

        </div>

        <!-- Sosial Media -->

        <div class="bg-white rounded-2xl border shadow-sm p-6 lg:col-span-2">

            <h2 class="text-lg font-semibold mb-6">
                Sosial Media
            </h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-4">

                <div class="border rounded-xl p-4">

                    <div class="text-slate-500 text-sm">
                        Facebook
                    </div>

                    <div class="mt-2 break-all">

                        <?= htmlspecialchars($data['facebook']) ?: '-'; ?>

                    </div>

                </div>

                <div class="border rounded-xl p-4">

                    <div class="text-slate-500 text-sm">
                        Instagram
                    </div>

                    <div class="mt-2 break-all">

                        <?= htmlspecialchars($data['instagram']) ?: '-'; ?>

                    </div>

                </div>

                <div class="border rounded-xl p-4">

                    <div class="text-slate-500 text-sm">
                        YouTube
                    </div>

                    <div class="mt-2 break-all">

                        <?= htmlspecialchars($data['youtube']) ?: '-'; ?>

                    </div>

                </div>

                <div class="border rounded-xl p-4">

                    <div class="text-slate-500 text-sm">
                        Twitter / X
                    </div>

                    <div class="mt-2 break-all">

                        <?= htmlspecialchars($data['twitter']) ?: '-'; ?>

                    </div>

                </div>

                <div class="border rounded-xl p-4">

                    <div class="text-slate-500 text-sm">
                        TikTok
                    </div>

                    <div class="mt-2 break-all">

                        <?= htmlspecialchars($data['tiktok']) ?: '-'; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>