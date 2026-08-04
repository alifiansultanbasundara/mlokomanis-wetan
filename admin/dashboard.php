<?php

require_once '../config/app.php';

$title = "Dashboard";
$page  = "dashboard";

/*
|--------------------------------------------------------------------------
| Statistik
|--------------------------------------------------------------------------
*/

$totalArticles = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM articles
"))['total'] ?? 0;

$totalAnnouncements = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM announcements
"))['total'] ?? 0;

$totalGallery = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM galleries
"))['total'] ?? 0;

$totalLaws = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM legal_instruments
"))['total'] ?? 0;

$totalRegions = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM regionals
"))['total'] ?? 0;

$totalPotentials = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM village_potentials
"))['total'] ?? 0;

$totalLetters = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM service_letters
"))['total'] ?? 0;

$totalMessages = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM contact_messages
"))['total'] ?? 0;

/*
|--------------------------------------------------------------------------
| Profil Desa
|--------------------------------------------------------------------------
*/

$profile = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT *
FROM village_profiles
LIMIT 1
"));

include APP_PATH . 'includes/admin/layout-top.php';

?>

<main class="p-8">

    <!-- Header -->

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Dashboard

            </h1>

            <p class="text-slate-500 mt-2">

                Selamat datang di Sistem Informasi Desa.

            </p>

        </div>

        <div class="flex gap-3">

            <a
                href="<?= APP_URL ?>beranda.php"
                target="_blank"
                class="px-5 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl">

                <i class="bi bi-globe"></i>

                Lihat Website

            </a>

        </div>

    </div>

    <!-- Statistik -->

    <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <!-- Berita -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <div class="flex justify-between">

                <div>

                    <p class="text-slate-500">

                        Berita

                    </p>

                    <h2 class="text-3xl font-bold mt-2">

                        <?= number_format($totalArticles) ?>

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">

                    <i class="bi bi-newspaper text-blue-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <!-- Pengumuman -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <div class="flex justify-between">

                <div>

                    <p class="text-slate-500">

                        Pengumuman

                    </p>

                    <h2 class="text-3xl font-bold mt-2">

                        <?= number_format($totalAnnouncements) ?>

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center">

                    <i class="bi bi-megaphone text-amber-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <!-- Galeri -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <div class="flex justify-between">

                <div>

                    <p class="text-slate-500">

                        Galeri

                    </p>

                    <h2 class="text-3xl font-bold mt-2">

                        <?= number_format($totalGallery) ?>

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-pink-100 flex items-center justify-center">

                    <i class="bi bi-images text-pink-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <!-- Produk Hukum -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <div class="flex justify-between">

                <div>

                    <p class="text-slate-500">

                        Produk Hukum

                    </p>

                    <h2 class="text-3xl font-bold mt-2">

                        <?= number_format($totalLaws) ?>

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-violet-100 flex items-center justify-center">

                    <i class="bi bi-bank text-violet-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <!-- Kewilayahan -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <div class="flex justify-between">

                <div>

                    <p class="text-slate-500">

                        Kewilayahan

                    </p>

                    <h2 class="text-3xl font-bold mt-2">

                        <?= number_format($totalRegions) ?>

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-cyan-100 flex items-center justify-center">

                    <i class="bi bi-map text-cyan-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <!-- Potensi -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <div class="flex justify-between">

                <div>

                    <p class="text-slate-500">

                        Potensi Desa

                    </p>

                    <h2 class="text-3xl font-bold mt-2">

                        <?= number_format($totalPotentials) ?>

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">

                    <i class="bi bi-tree text-green-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <!-- Surat -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <div class="flex justify-between">

                <div>

                    <p class="text-slate-500">

                        Pelayanan Surat

                    </p>

                    <h2 class="text-3xl font-bold mt-2">

                        <?= number_format($totalLetters) ?>

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-teal-100 flex items-center justify-center">

                    <i class="bi bi-envelope-paper text-teal-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <!-- Pesan -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <div class="flex justify-between">

                <div>

                    <p class="text-slate-500">

                        Pesan Website

                    </p>

                    <h2 class="text-3xl font-bold mt-2">

                        <?= number_format($totalMessages) ?>

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center">

                    <i class="bi bi-chat-dots text-red-600 text-2xl"></i>

                </div>

            </div>

        </div>

    </div>

    <!-- Profil Desa & Quick Menu -->

    <div class="grid xl:grid-cols-3 gap-6">

        <!-- Profil Desa -->

        <div class="xl:col-span-2 bg-white rounded-2xl border shadow-sm p-8">

            <div class="flex items-center justify-between mb-8">

                <h2 class="text-xl font-bold">

                    Profil Singkat Desa

                </h2>

                <a
                    href="<?= APP_URL ?>admin/profil-desa/tentang-desa/"
                    class="text-teal-600 font-semibold">

                    Kelola

                </a>

            </div>

            <div class="grid md:grid-cols-2 gap-6">

                <div>

                    <p class="text-slate-500">

                        Nama Desa

                    </p>

                    <h3 class="text-xl font-semibold mt-2">

                        <?= htmlspecialchars($profile['village_name'] ?? '-') ?>

                    </h3>

                </div>

                <div>

                    <p class="text-slate-500">

                        Kepala Desa

                    </p>

                    <h3 class="text-xl font-semibold mt-2">

                        <?= htmlspecialchars($profile['village_head'] ?? '-') ?>

                    </h3>

                </div>

                <div>

                    <p class="text-slate-500">

                        Jumlah Penduduk

                    </p>

                    <h3 class="text-xl font-semibold mt-2">

                        <?= number_format($profile['total_population'] ?? 0) ?>

                        Jiwa

                    </h3>

                </div>

                <div>

                    <p class="text-slate-500">

                        RT / RW

                    </p>

                    <h3 class="text-xl font-semibold mt-2">

                        <?= $profile['total_rt'] ?? 0 ?> RT /
                        <?= $profile['total_rw'] ?? 0 ?> RW

                    </h3>

                </div>

            </div>

        </div>

        <!-- Quick Menu -->

        <div class="bg-white rounded-2xl border shadow-sm p-8">

            <h2 class="text-xl font-bold mb-6">

                Menu Cepat

            </h2>

            <div class="grid gap-3">

                <a href="<?= APP_URL ?>admin/berita/create.php" class="p-4 rounded-xl border hover:bg-slate-50">
                    📰 Tambah Berita
                </a>

                <a href="<?= APP_URL ?>admin/pengumuman/create.php" class="p-4 rounded-xl border hover:bg-slate-50">
                    📢 Tambah Pengumuman
                </a>

                <a href="<?= APP_URL ?>admin/galeri/create.php" class="p-4 rounded-xl border hover:bg-slate-50">
                    🖼 Tambah Galeri
                </a>

                <a href="<?= APP_URL ?>admin/potensi-desa/create.php" class="p-4 rounded-xl border hover:bg-slate-50">
                    🌱 Tambah Potensi
                </a>

                <a href="<?= APP_URL ?>admin/pelayanan-surat/create.php" class="p-4 rounded-xl border hover:bg-slate-50">
                    📄 Tambah Pelayanan Surat
                </a>

                <a href="<?= APP_URL ?>admin/profil-desa/tentang-desa/" class="p-4 rounded-xl border hover:bg-slate-50">
                    🏛 Edit Profil Desa
                </a>

            </div>

        </div>

    </div>

    <!-- ====================================================== -->
    <!-- Aktivitas Terbaru -->
    <!-- ====================================================== -->

    <?php

    $latestArticles = mysqli_query($conn, "
        SELECT title, created_at
        FROM articles
        ORDER BY id DESC
        LIMIT 5
    ");

    $latestAnnouncements = mysqli_query($conn, "
        SELECT title, created_at
        FROM announcements
        ORDER BY id DESC
        LIMIT 5
    ");

    ?>

    <div class="grid xl:grid-cols-2 gap-6 mt-8">

        <!-- Berita Terbaru -->

        <div class="bg-white rounded-2xl border shadow-sm p-8">

            <div class="flex justify-between items-center mb-6">

                <h2 class="text-xl font-bold">

                    Berita Terbaru

                </h2>

                <a
                    href="<?= APP_URL ?>admin/berita/"
                    class="text-teal-600 font-semibold">

                    Lihat Semua

                </a>

            </div>

            <?php if (mysqli_num_rows($latestArticles) > 0): ?>

                <div class="divide-y">

                    <?php while ($row = mysqli_fetch_assoc($latestArticles)): ?>

                        <div class="py-4">

                            <h4 class="font-semibold text-slate-800">

                                <?= htmlspecialchars($row['title']) ?>

                            </h4>

                            <p class="text-sm text-slate-500 mt-1">

                                <?= date('d M Y H:i', strtotime($row['created_at'])) ?>

                            </p>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else: ?>

                <p class="text-slate-500">

                    Belum ada berita.

                </p>

            <?php endif; ?>

        </div>

        <!-- Pengumuman -->

        <div class="bg-white rounded-2xl border shadow-sm p-8">

            <div class="flex justify-between items-center mb-6">

                <h2 class="text-xl font-bold">

                    Pengumuman Terbaru

                </h2>

                <a
                    href="<?= APP_URL ?>admin/pengumuman/"
                    class="text-teal-600 font-semibold">

                    Lihat Semua

                </a>

            </div>

            <?php if (mysqli_num_rows($latestAnnouncements) > 0): ?>

                <div class="divide-y">

                    <?php while ($row = mysqli_fetch_assoc($latestAnnouncements)): ?>

                        <div class="py-4">

                            <h4 class="font-semibold text-slate-800">

                                <?= htmlspecialchars($row['title']) ?>

                            </h4>

                            <p class="text-sm text-slate-500 mt-1">

                                <?= date('d M Y H:i', strtotime($row['created_at'])) ?>

                            </p>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else: ?>

                <p class="text-slate-500">

                    Belum ada pengumuman.

                </p>

            <?php endif; ?>

        </div>

    </div>

    <!-- ====================================================== -->
    <!-- Surat & Pesan -->
    <!-- ====================================================== -->

    <?php

    $latestTracking = mysqli_query($conn, "
SELECT
    lt.applicant_name,
    ls.name AS service_name,
    lt.status,
    lt.created_at
FROM letter_trackings lt
JOIN service_letters ls
ON ls.id = lt.service_id
ORDER BY lt.id DESC
LIMIT 5
");

    $latestMessages = mysqli_query($conn, "
        SELECT name, subject, status, created_at
        FROM contact_messages
        ORDER BY id DESC
        LIMIT 5
    ");

    ?>

    <div class="grid xl:grid-cols-2 gap-6 mt-8">

        <!-- Surat -->

        <div class="bg-white rounded-2xl border shadow-sm p-8">

            <div class="flex justify-between items-center mb-6">

                <h2 class="text-xl font-bold">

                    Pengajuan Surat Terbaru

                </h2>

                <a
                    href="<?= APP_URL ?>admin/pelayanan-surat/tracking.php"
                    class="text-teal-600 font-semibold">

                    Lihat Semua

                </a>

            </div>

            <?php if (mysqli_num_rows($latestTracking) > 0): ?>

                <div class="space-y-4">

                    <?php while ($row = mysqli_fetch_assoc($latestTracking)): ?>

                        <div class="border rounded-xl p-4">

                            <div class="font-semibold">

                                <?= htmlspecialchars($row['applicant_name']) ?>

                            </div>

                            <div class="text-sm text-slate-500 mt-1">

                                <?= htmlspecialchars($row['service_name']) ?>

                            </div>

                            <div class="flex justify-between mt-3">

                                <span class="text-sm">

                                    <?= htmlspecialchars($row['status']) ?>

                                </span>

                                <span class="text-sm text-slate-500">

                                    <?= date('d M', strtotime($row['created_at'])) ?>

                                </span>

                            </div>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else: ?>

                <p class="text-slate-500">

                    Belum ada pengajuan surat.

                </p>

            <?php endif; ?>

        </div>

        <!-- Pesan -->

        <div class="bg-white rounded-2xl border shadow-sm p-8">

            <div class="flex justify-between items-center mb-6">

                <h2 class="text-xl font-bold">

                    Pesan Website

                </h2>

                <a
                    href="<?= APP_URL ?>admin/kontak/pesan-masuk/"
                    class="text-teal-600 font-semibold">

                    Lihat Semua

                </a>

            </div>

            <?php if (mysqli_num_rows($latestMessages) > 0): ?>

                <div class="space-y-4">

                    <?php while ($row = mysqli_fetch_assoc($latestMessages)): ?>

                        <div class="border rounded-xl p-4">

                            <div class="flex justify-between">

                                <h4 class="font-semibold">

                                    <?= htmlspecialchars($row['name']) ?>

                                </h4>

                                <span class="text-sm text-slate-500">

                                    <?= date('d M', strtotime($row['created_at'])) ?>

                                </span>

                            </div>

                            <p class="text-slate-600 mt-2">

                                <?= htmlspecialchars($row['subject']) ?>

                            </p>

                            <span class="inline-block mt-3 px-3 py-1 rounded-full bg-slate-100 text-sm">

                                <?= htmlspecialchars($row['status']) ?>

                            </span>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else: ?>

                <p class="text-slate-500">

                    Belum ada pesan.

                </p>

            <?php endif; ?>

        </div>

    </div>

    <!-- ====================================================== -->
    <!-- Informasi Sistem -->
    <!-- ====================================================== -->

    <div class="bg-white rounded-2xl border shadow-sm p-8 mt-8">

        <h2 class="text-xl font-bold mb-6">

            Informasi Sistem

        </h2>

        <div class="grid md:grid-cols-4 gap-6">

            <div>

                <p class="text-slate-500">

                    PHP

                </p>

                <h3 class="text-lg font-semibold mt-2">

                    <?= phpversion(); ?>

                </h3>

            </div>

            <div>

                <p class="text-slate-500">

                    Database

                </p>

                <h3 class="text-lg font-semibold mt-2">

                    MySQL

                </h3>

            </div>

            <div>

                <p class="text-slate-500">

                    Server Time

                </p>

                <h3 class="text-lg font-semibold mt-2">

                    <?= date('d M Y H:i') ?>

                </h3>

            </div>

            <div>

                <p class="text-slate-500">

                    Status

                </p>

                <h3 class="text-lg font-semibold text-green-600 mt-2">

                    Online

                </h3>

            </div>

        </div>

    </div>

</main>

<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>