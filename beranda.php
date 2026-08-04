<?php
require_once 'config/app.php';

$page = 'beranda';

// ===============================
// Ambil Data Profil Desa
// ===============================
$query = mysqli_query($conn, "
    SELECT *
    FROM village_profiles
    LIMIT 1
");

$village = mysqli_fetch_assoc($query);

// ===============================
// Default jika data kosong
// ===============================
if (!$village) {

    $village = [

        'village_name'      => 'Nama Desa',
        'village_head'      => '-',
        'office_photo'      => null,
        'description'       => 'Belum ada deskripsi desa.',
        'total_population'  => 0,
        'total_rt'          => 0,
        'total_rw'          => 0,
        'total_hamlets'     => 0,
        'total_areas'       => 0,
        'office_address'    => '-',
        'whatsapp'          => ''

    ];
}


// ===============================
// WhatsApp Desa
// ===============================

$whatsapp = '';

if (!empty($village['whatsapp'])) {


    $number = preg_replace(
        '/[^0-9]/',
        '',
        $village['whatsapp']
    );


    // Jika format Indonesia 08xxx
    if (substr($number, 0, 1) == '0') {

        $number = '62' . substr($number, 1);
    }


    $whatsapp = $number;
}

// ===============================
// Meta
// ===============================
$title = "Selamat Datang di Website Desa {$village['village_name']}";
$metaTitle = "Beranda | {$village['village_name']}";
$metaDescription = "Website resmi Desa {$village['village_name']}.";


// ===============================================
// Popup Pengumuman
// ===============================================

$today = date('Y-m-d');


$popupQuery = mysqli_query($conn, "

    SELECT *

    FROM announcements

    WHERE status='Published'

    AND is_popup=1

    AND (
        start_date IS NULL
        OR start_date <= '$today'
    )

    AND (
        end_date IS NULL
        OR end_date >= '$today'
    )

    ORDER BY priority DESC, id DESC

");


$popups = [];

while ($row = mysqli_fetch_assoc($popupQuery)) {

    $popups[] = $row;
}


$popup = mysqli_fetch_assoc($popupQuery);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <?php include 'includes/head.php'; ?>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs/dist/cdn.min.js"></script>

    <!-- Alpine Collapse -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-50 text-slate-800">

    <?php if (!empty($whatsapp)): ?>

        <a
            href="https://wa.me/<?= $whatsapp ?>"
            target="_blank"
            class="fixed bottom-6 right-6 z-50 flex h-16 w-16 items-center justify-center rounded-full bg-green-500 text-white shadow-xl transition hover:scale-110 hover:bg-green-600"
            aria-label="WhatsApp Desa">


            <i class="bi bi-whatsapp text-3xl"></i>


        </a>


    <?php endif; ?>

    <!-- ============================= -->
    <!-- LOADING SCREEN -->
    <!-- ============================= -->

    <div
        id="loading-screen"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-teal-700 transition-opacity duration-700">

        <div class="text-center">

            <img
                src="<?= APP_URL ?>assets/img/logo.webp"
                alt="Logo Desa"
                class="mx-auto h-24 w-24 object-contain animate-pulse">


            <h2 class="mt-6 text-xl font-bold text-white">
                <?= htmlspecialchars($village['village_name'] ?? 'Pemerintah Desa'); ?>
            </h2>


            <p class="mt-2 text-sm text-teal-100">
                Memuat informasi desa...
            </p>


            <div class="mt-6 flex justify-center gap-2">

                <span class="h-3 w-3 rounded-full bg-white animate-bounce"></span>

                <span class="h-3 w-3 rounded-full bg-white animate-bounce [animation-delay:150ms]"></span>

                <span class="h-3 w-3 rounded-full bg-white animate-bounce [animation-delay:300ms]"></span>

            </div>

        </div>

    </div>

    <!-- ======= NAVBAR ======= -->
    <?php include APP_PATH . 'includes/guest/navbar.php'; ?>

    <!-- ======= HERO ======= -->
    <section class="relative overflow-hidden bg-gradient-to-br from-teal-900 via-teal-700 to-emerald-600 text-white">

        <!-- Background Decoration -->
        <div class="absolute inset-0 overflow-hidden">

            <div class="absolute -top-32 -left-24 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>

            <div class="absolute bottom-0 right-0 h-[28rem] w-[28rem] rounded-full bg-emerald-300/10 blur-3xl"></div>

            <div class="absolute inset-0 bg-[radial-gradient(rgba(255,255,255,.08)_1px,transparent_1px)] bg-[size:24px_24px]"></div>

        </div>

        <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-28">

            <div class="grid items-center gap-16 lg:grid-cols-2">

                <!-- ================= LEFT ================= -->

                <div>

                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 backdrop-blur">

                        <i class="bi bi-patch-check-fill text-yellow-300"></i>

                        <span class="text-sm font-medium">
                            Website Resmi Pemerintah Desa
                        </span>

                    </div>

                    <!-- Title -->

                    <h1 class="mt-7 text-5xl font-black leading-tight lg:text-6xl">

                        Selamat Datang di <br>

                        <span class="text-teal-200">

                            <?= htmlspecialchars($village['village_name']); ?>

                        </span>

                    </h1>

                    <!-- Copywriting -->

                    <p class="mt-6 max-w-2xl text-lg leading-8 text-teal-50/90">

                        Portal resmi Pemerintah
                        <strong><?= htmlspecialchars($village['village_name']); ?></strong>
                        yang menghadirkan informasi desa, pelayanan administrasi,
                        transparansi pemerintahan, serta berbagai potensi desa
                        dalam satu platform digital yang mudah diakses oleh seluruh masyarakat.

                    </p>

                    <!-- CTA -->

                    <div class="mt-10 flex flex-wrap gap-4">

                        <a
                            href="<?= APP_URL ?>profil-desa/tentang.php"
                            class="inline-flex items-center gap-2 rounded-2xl bg-white px-7 py-4 font-semibold text-teal-700 shadow-xl transition hover:-translate-y-1 hover:bg-teal-50">

                            Jelajahi Desa

                            <i class="bi bi-arrow-right"></i>

                        </a>

                        <a
                            href="<?= APP_URL ?>layanan/"
                            class="inline-flex items-center gap-2 rounded-2xl border border-white/30 bg-white/10 px-7 py-4 font-semibold backdrop-blur transition hover:bg-white/20">

                            Pelayanan Online

                            <i class="bi bi-file-earmark-text"></i>

                        </a>

                    </div>

                    <!-- Mini Statistik -->

                    <div class="mt-12 grid grid-cols-3 gap-8">

                        <div>

                            <h3 class="text-3xl font-bold">

                                <?= number_format($village['total_population']); ?>

                            </h3>

                            <p class="mt-2 text-sm uppercase tracking-wider text-teal-100/80">

                                Penduduk

                            </p>

                        </div>

                        <div>

                            <h3 class="text-3xl font-bold">

                                <?= $village['total_hamlets']; ?>

                            </h3>

                            <p class="mt-2 text-sm uppercase tracking-wider text-teal-100/80">

                                Dusun

                            </p>

                        </div>

                        <div>

                            <h3 class="text-3xl font-bold">

                                <?= $village['total_rt']; ?>

                            </h3>

                            <p class="mt-2 text-sm uppercase tracking-wider text-teal-100/80">

                                RT

                            </p>

                        </div>

                    </div>

                </div>

                <!-- ================= RIGHT ================= -->

                <div class="relative">

                    <!-- Foto -->
                    <!-- Foto -->

                    <div class="overflow-hidden rounded-[2rem] border border-white/20 bg-white/10 p-3 shadow-2xl backdrop-blur">

                        <?php if (!empty($village['office_photo'])): ?>

                            <img
                                src="<?= APP_URL ?>uploads/village/<?= htmlspecialchars($village['office_photo']); ?>"
                                alt="<?= htmlspecialchars($village['village_name']); ?>"
                                class="h-[520px] w-full rounded-[1.5rem] object-cover">

                        <?php else: ?>

                            <div class="flex h-[520px] items-center justify-center rounded-[1.5rem] bg-teal-600">

                                <div class="text-center text-white">

                                    <i class="bi bi-building text-7xl"></i>

                                    <p class="mt-4 text-lg">
                                        Foto Kantor Desa
                                    </p>

                                </div>

                            </div>

                        <?php endif; ?>

                    </div>

                    <!-- Floating Card 1 -->

                    <div class="absolute -left-6 top-10 rounded-3xl bg-white p-5 shadow-2xl">

                        <div class="flex items-center gap-4">

                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-100">

                                <i class="bi bi-people-fill text-2xl text-teal-700"></i>

                            </div>

                            <div>

                                <p class="text-sm text-slate-500">

                                    Total Penduduk

                                </p>

                                <h3 class="text-2xl font-bold text-slate-800">

                                    <?= number_format($village['total_population']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <!-- Floating Card 2 -->

                    <div class="absolute -right-6 bottom-10 rounded-3xl bg-white p-5 shadow-2xl">

                        <div class="flex items-center gap-4">

                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100">

                                <i class="bi bi-patch-check-fill text-2xl text-emerald-600"></i>

                            </div>

                            <div>

                                <p class="text-sm text-slate-500">

                                    Pelayanan

                                </p>

                                <h3 class="font-bold text-slate-800">

                                    Digital & Transparan

                                </h3>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Wave -->

        <div class="absolute bottom-0 left-0 right-0">

            <svg viewBox="0 0 1440 120" fill="none">

                <path
                    fill="#F8FAFC"
                    d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,53.3C1120,53,1280,75,1360,85.3L1440,96L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z">
                </path>

            </svg>

        </div>

    </section>

    <!-- ======= QUICK MENU ======= -->
    <section class="relative z-20">

        <div class="max-w-7xl mx-auto px-6">

            <div class="rounded-[2rem] bg-white shadow-2xl border border-slate-100 p-8">

                <!-- Header -->

                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">

                    <div>

                        <span class="inline-flex items-center gap-2 rounded-full bg-teal-100 px-4 py-2 text-sm font-semibold text-teal-700">

                            <i class="bi bi-grid-fill"></i>

                            Menu Cepat

                        </span>

                        <h2 class="mt-4 text-3xl font-bold text-slate-900">

                            Akses Layanan & Informasi Desa

                        </h2>

                        <p class="mt-3 max-w-2xl text-slate-500 leading-7">

                            Temukan berbagai layanan administrasi, informasi terbaru,
                            galeri kegiatan, hingga produk hukum desa melalui menu
                            yang tersedia di bawah ini.

                        </p>

                    </div>

                </div>

                <!-- Menu -->

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

                    <!-- Surat -->

                    <a href="<?= APP_URL ?>layanan/"

                        class="group rounded-3xl border border-slate-100 bg-gradient-to-br from-teal-50 to-white p-7 transition duration-300 hover:-translate-y-2 hover:border-teal-200 hover:shadow-xl">

                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-teal-600 text-white shadow-lg">

                            <i class="bi bi-file-earmark-text text-3xl"></i>

                        </div>

                        <h3 class="mt-6 text-xl font-bold text-slate-900">

                            Pelayanan Surat

                        </h3>

                        <p class="mt-3 text-sm leading-6 text-slate-500">

                            Ajukan berbagai kebutuhan administrasi desa secara mudah
                            dan cepat.

                        </p>

                        <div class="mt-6 flex items-center gap-2 font-semibold text-teal-700">

                            Selengkapnya

                            <i class="bi bi-arrow-right group-hover:translate-x-1 transition"></i>

                        </div>

                    </a>

                    <!-- Tracking -->

                    <a href="<?= APP_URL ?>layanan/tracking.php"

                        class="group rounded-3xl border border-slate-100 bg-gradient-to-br from-blue-50 to-white p-7 transition duration-300 hover:-translate-y-2 hover:border-blue-200 hover:shadow-xl">

                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg">

                            <i class="bi bi-search text-3xl"></i>

                        </div>

                        <h3 class="mt-6 text-xl font-bold">

                            Tracking Surat

                        </h3>

                        <p class="mt-3 text-sm leading-6 text-slate-500">

                            Pantau proses pengajuan surat secara online menggunakan
                            nomor registrasi.

                        </p>

                        <div class="mt-6 flex items-center gap-2 font-semibold text-blue-700">

                            Cek Status

                            <i class="bi bi-arrow-right group-hover:translate-x-1 transition"></i>

                        </div>

                    </a>

                    <!-- Berita -->

                    <a href="<?= APP_URL ?>informasi/berita.php"

                        class="group rounded-3xl border border-slate-100 bg-gradient-to-br from-amber-50 to-white p-7 transition duration-300 hover:-translate-y-2 hover:border-amber-200 hover:shadow-xl">

                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg">

                            <i class="bi bi-newspaper text-3xl"></i>

                        </div>

                        <h3 class="mt-6 text-xl font-bold">

                            Berita Desa

                        </h3>

                        <p class="mt-3 text-sm leading-6 text-slate-500">

                            Ikuti perkembangan kegiatan, pengumuman, dan informasi
                            terbaru dari pemerintah desa.

                        </p>

                        <div class="mt-6 flex items-center gap-2 font-semibold text-amber-600">

                            Baca Berita

                            <i class="bi bi-arrow-right group-hover:translate-x-1 transition"></i>

                        </div>

                    </a>

                    <!-- Kontak -->

                    <a href="<?= APP_URL ?>kontak.php"

                        class="group rounded-3xl border border-slate-100 bg-gradient-to-br from-emerald-50 to-white p-7 transition duration-300 hover:-translate-y-2 hover:border-emerald-200 hover:shadow-xl">

                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg">

                            <i class="bi bi-headset text-3xl"></i>

                        </div>

                        <h3 class="mt-6 text-xl font-bold">

                            Hubungi Kami

                        </h3>

                        <p class="mt-3 text-sm leading-6 text-slate-500">

                            Sampaikan pertanyaan, masukan, atau hubungi pemerintah
                            desa melalui berbagai kanal komunikasi.

                        </p>

                        <div class="mt-6 flex items-center gap-2 font-semibold text-emerald-700">

                            Hubungi

                            <i class="bi bi-arrow-right group-hover:translate-x-1 transition"></i>

                        </div>

                    </a>

                </div>

            </div>

        </div>

    </section>

    <!-- ======= STATISTIK DESA ======= -->
    <section class="bg-slate-50 py-24">

        <div class="max-w-7xl mx-auto px-6">

            <!-- Heading -->

            <div class="max-w-3xl mx-auto text-center">

                <span class="inline-flex items-center gap-2 rounded-full bg-teal-100 px-4 py-2 text-sm font-semibold text-teal-700">

                    <i class="bi bi-bar-chart-fill"></i>

                    Statistik Desa

                </span>

                <h2 class="mt-5 text-4xl font-bold text-slate-900">

                    Gambaran Umum Desa
                    <?= htmlspecialchars($village['village_name']); ?>

                </h2>

                <p class="mt-5 text-lg leading-8 text-slate-500">

                    Data statistik memberikan gambaran singkat mengenai kondisi
                    wilayah dan kependudukan Desa
                    <?= htmlspecialchars($village['village_name']); ?>.

                </p>

            </div>

            <!-- Cards -->

            <div class="mt-16 grid gap-6 sm:grid-cols-2 xl:grid-cols-5">

                <!-- Penduduk -->

                <div class="group rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-2 hover:shadow-xl">

                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-teal-100">

                        <i class="bi bi-people-fill text-3xl text-teal-700"></i>

                    </div>

                    <h3 class="mt-8 text-5xl font-black text-slate-900">

                        <?= number_format($village['total_population']); ?>

                    </h3>

                    <p class="mt-3 font-semibold text-slate-700">

                        Penduduk

                    </p>

                    <div class="mt-6 border-t pt-4 text-sm text-slate-500">

                        Jumlah warga desa

                    </div>

                </div>

                <!-- RT -->

                <div class="group rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-2 hover:shadow-xl">

                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100">

                        <i class="bi bi-house-door-fill text-3xl text-blue-700"></i>

                    </div>

                    <h3 class="mt-8 text-5xl font-black text-slate-900">

                        <?= $village['total_rt']; ?>

                    </h3>

                    <p class="mt-3 font-semibold text-slate-700">

                        RT

                    </p>

                    <div class="mt-6 border-t pt-4 text-sm text-slate-500">

                        Rukun Tetangga

                    </div>

                </div>

                <!-- RW -->

                <div class="group rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-2 hover:shadow-xl">

                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-100">

                        <i class="bi bi-buildings-fill text-3xl text-indigo-700"></i>

                    </div>

                    <h3 class="mt-8 text-5xl font-black text-slate-900">

                        <?= $village['total_rw']; ?>

                    </h3>

                    <p class="mt-3 font-semibold text-slate-700">

                        RW

                    </p>

                    <div class="mt-6 border-t pt-4 text-sm text-slate-500">

                        Rukun Warga

                    </div>

                </div>

                <!-- Dusun -->

                <div class="group rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-2 hover:shadow-xl">

                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-100">

                        <i class="bi bi-tree-fill text-3xl text-amber-600"></i>

                    </div>

                    <h3 class="mt-8 text-5xl font-black text-slate-900">

                        <?= $village['total_hamlets']; ?>

                    </h3>

                    <p class="mt-3 font-semibold text-slate-700">

                        Dusun

                    </p>

                    <div class="mt-6 border-t pt-4 text-sm text-slate-500">

                        Wilayah dusun

                    </div>

                </div>

                <!-- Wilayah -->

                <div class="group rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-2 hover:shadow-xl">

                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100">

                        <i class="bi bi-map-fill text-3xl text-emerald-700"></i>

                    </div>

                    <h3 class="mt-8 text-5xl font-black text-slate-900">

                        <?= $village['total_areas']; ?>

                    </h3>

                    <p class="mt-3 font-semibold text-slate-700">

                        Wilayah

                    </p>

                    <div class="mt-6 border-t pt-4 text-sm text-slate-500">

                        Area administrasi

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- ======= SAMBUTAN KEPALA DESA ======= -->
    <section class="bg-white py-24">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid items-center gap-16 lg:grid-cols-5">

                <!-- ================= FOTO ================= -->

                <div class="lg:col-span-2">

                    <?php
                    // Ambil Kepala Desa

                    $officialQuery = mysqli_query($conn, "
                        SELECT *
                        FROM village_officials
                        WHERE category='Kepala Desa'
                        AND status='Aktif'
                        LIMIT 1
                    ");

                    $headman = mysqli_fetch_assoc($officialQuery);
                    ?>

                    <div class="relative">

                        <!-- Background Decoration -->

                        <div class="absolute -left-5 -top-5 h-full w-full rounded-[2rem] bg-teal-100"></div>

                        <div class="relative overflow-hidden rounded-[2rem] shadow-xl">

                            <?php if (!empty($headman['photo'])): ?>

                                <img
                                    src="<?= APP_URL ?>uploads/village/officials/<?= htmlspecialchars($headman['photo']); ?>"
                                    alt="<?= htmlspecialchars($headman['name']); ?>"
                                    class="h-[580px] w-full object-cover">


                            <?php else: ?>

                                <div class="flex h-[580px] items-center justify-center bg-gradient-to-br from-teal-600 to-emerald-600">

                                    <div class="text-center text-white">

                                        <i class="bi bi-person-circle text-8xl"></i>

                                        <p class="mt-5 text-xl font-semibold">

                                            Kepala Desa

                                        </p>

                                    </div>

                                </div>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

                <!-- ================= CONTENT ================= -->

                <div class="lg:col-span-3">

                    <span class="inline-flex items-center gap-2 rounded-full bg-teal-100 px-4 py-2 text-sm font-semibold text-teal-700">

                        <i class="bi bi-chat-left-quote-fill"></i>

                        Sambutan Kepala Desa

                    </span>

                    <h2 class="mt-5 text-4xl font-black leading-tight text-slate-900">

                        Bersama Membangun Desa yang
                        Maju, Transparan, dan Sejahtera

                    </h2>

                    <!-- Quote -->

                    <div class="mt-10 relative">

                        <i class="bi bi-quote absolute -top-6 left-0 text-6xl text-teal-100"></i>

                        <p class="relative pl-10 text-lg leading-9 text-slate-600">

                            Selamat datang di Website Resmi
                            <strong><?= htmlspecialchars($village['village_name']); ?></strong>.

                            Website ini kami hadirkan sebagai media informasi,
                            transparansi pemerintahan, serta sarana pelayanan publik
                            yang dapat diakses oleh seluruh masyarakat.

                            Kami percaya bahwa pelayanan yang baik dimulai dari
                            keterbukaan informasi, komunikasi yang efektif,
                            dan partisipasi aktif masyarakat dalam membangun desa.

                            Semoga website ini memberikan manfaat serta menjadi
                            jembatan yang mempererat hubungan antara pemerintah desa
                            dan seluruh warga.

                        </p>

                    </div>

                    <!-- Profile -->

                    <div class="mt-10 flex flex-wrap items-center justify-between gap-6 border-t border-slate-200 pt-8">

                        <div>

                            <h3 class="text-2xl font-bold text-slate-900">

                                <?= htmlspecialchars($village['village_head']); ?>

                            </h3>

                            <p class="mt-2 text-slate-500">

                                Kepala Desa
                                <?= htmlspecialchars($village['village_name']); ?>

                            </p>

                        </div>

                        <a
                            href="<?= APP_URL ?>profil-desa/tentang.php"
                            class="inline-flex items-center gap-2 rounded-2xl bg-teal-600 px-6 py-4 font-semibold text-white transition hover:bg-teal-700">

                            Profil Desa

                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <?php

    $news = mysqli_query($conn, "
    SELECT *
    FROM articles
    WHERE status='Published'
    ORDER BY
        updated_at DESC
    LIMIT 6
");

    ?>

    <!-- ============================= -->
    <!-- Section Header -->
    <!-- ============================= -->

    <section class="py-24 bg-slate-50">

        <div class="max-w-7xl mx-auto px-6">

            <!-- Heading -->

            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">

                <div class="max-w-2xl">

                    <span class="inline-flex items-center gap-2 rounded-full bg-teal-100 px-4 py-2 text-sm font-semibold text-teal-700">

                        <i class="bi bi-newspaper"></i>

                        Berita Desa

                    </span>

                    <h2 class="mt-5 text-4xl font-black leading-tight text-slate-900">

                        Kabar &
                        <span class="text-teal-600">
                            Informasi Terbaru
                        </span>

                    </h2>

                    <p class="mt-5 text-lg leading-8 text-slate-500">

                        Ikuti berbagai kegiatan pemerintahan desa,
                        pembangunan, pelayanan masyarakat,
                        serta informasi terbaru yang disampaikan secara resmi.

                    </p>

                </div>

                <div class="flex items-center gap-3">

                    <div class="hidden lg:flex items-center gap-2 rounded-2xl bg-white px-5 py-3 shadow-sm ring-1 ring-slate-200">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-100">

                            <i class="bi bi-journal-text text-xl text-teal-700"></i>

                        </div>

                        <div>

                            <p class="text-xs uppercase tracking-wider text-slate-500">

                                Total Berita

                            </p>

                            <h4 class="font-bold text-slate-900">

                                <?= mysqli_num_rows($news); ?> Artikel

                            </h4>

                        </div>

                    </div>

                    <a
                        href="<?= APP_URL ?>informasi/berita.php"
                        class="inline-flex items-center gap-2 rounded-2xl bg-teal-600 px-6 py-3.5 font-semibold text-white transition hover:-translate-y-1 hover:bg-teal-700">

                        Semua Berita

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

        </div>

    </section>


    <?php
    // ======================================
    // Ambil Potensi Desa
    // ======================================

    $potentialQuery = mysqli_query($conn, "

    SELECT *

    FROM village_potentials

    WHERE status='Published'

    ORDER BY

        featured='Yes' DESC,

        sort_order ASC,

        id DESC

    LIMIT 6

");

    ?>


    <section class="py-24 bg-slate-50">


        <div class="max-w-7xl mx-auto px-6">


            <!-- Header -->

            <div class="max-w-3xl mx-auto text-center">


                <span
                    class="inline-flex items-center gap-2 rounded-full bg-teal-100 px-5 py-2 text-sm font-semibold text-teal-700">

                    <i class="bi bi-stars"></i>

                    Potensi Desa

                </span>



                <h2 class="mt-6 text-4xl font-black text-slate-900">


                    Potensi Unggulan

                    <span class="text-teal-600">

                        <?= htmlspecialchars($village['village_name']); ?>

                    </span>


                </h2>



                <p class="mt-5 text-lg leading-8 text-slate-500">


                    Mengenal berbagai potensi unggulan desa mulai dari
                    UMKM, pertanian, peternakan, wisata, hingga ekonomi kreatif
                    masyarakat.


                </p>


            </div>





            <!-- Cards -->

            <div class="mt-16 grid gap-8 md:grid-cols-2 lg:grid-cols-3">


                <?php while ($row = mysqli_fetch_assoc($potentialQuery)): ?>


                    <article
                        class="group overflow-hidden rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-2 hover:shadow-xl">



                        <!-- Image -->

                        <div class="relative h-56 overflow-hidden">


                            <?php if (!empty($row['image'])): ?>


                                <img

                                    src="<?= APP_URL ?>uploads/potentials/<?= htmlspecialchars($row['image']); ?>"

                                    alt="<?= htmlspecialchars($row['title']); ?>"

                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-110">


                            <?php else: ?>


                                <div
                                    class="flex h-full items-center justify-center bg-teal-50">


                                    <i class="bi bi-image text-6xl text-teal-300"></i>


                                </div>


                            <?php endif; ?>



                            <!-- Category -->


                            <span
                                class="absolute left-5 top-5 rounded-full bg-white/90 px-4 py-2 text-xs font-bold text-teal-700 shadow">


                                <?= htmlspecialchars($row['category']); ?>


                            </span>


                        </div>





                        <!-- Content -->


                        <div class="p-7">


                            <h3
                                class="text-xl font-bold text-slate-900">


                                <?= htmlspecialchars($row['title']); ?>


                            </h3>




                            <p
                                class="mt-3 line-clamp-3 text-sm leading-6 text-slate-500">


                                <?= mb_substr(
                                    strip_tags($row['description'] ?? ''),
                                    0,
                                    130
                                ); ?>


                            </p>




                            <?php if (!empty($row['owner_name'])): ?>


                                <div
                                    class="mt-5 flex items-center gap-3 text-sm text-slate-600">


                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-teal-100">


                                        <i class="bi bi-person-fill text-teal-700"></i>


                                    </div>


                                    <span>


                                        <?= htmlspecialchars($row['owner_name']); ?>


                                    </span>


                                </div>


                            <?php endif; ?>





                            <a

                                href="<?= APP_URL ?>potensi/detail.php?slug=<?= urlencode($row['slug']); ?>"

                                class="mt-6 inline-flex items-center gap-2 font-semibold text-teal-600 hover:text-teal-700">


                                Lihat Detail


                                <i class="bi bi-arrow-right"></i>


                            </a>


                        </div>


                    </article>



                <?php endwhile; ?>


            </div>





            <!-- Button Semua -->

            <div class="mt-14 text-center">


                <a

                    href="<?= APP_URL ?>potensi/index.php"

                    class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-8 py-4 font-semibold text-white transition hover:bg-teal-700">


                    Lihat Semua Potensi


                    <i class="bi bi-arrow-right"></i>


                </a>


            </div>


        </div>


    </section>

    <!-- ==========================================
 LOKASI & KONTAK DESA
=========================================== -->
    <section class="bg-slate-50 py-24">

        <div class="max-w-7xl mx-auto px-6">

            <!-- Heading -->

            <div class="max-w-3xl mx-auto text-center">

                <span class="inline-flex items-center gap-2 rounded-full bg-teal-100 px-4 py-2 text-sm font-semibold text-teal-700">

                    <i class="bi bi-geo-alt-fill"></i>

                    Lokasi & Kontak

                </span>

                <h2 class="mt-5 text-4xl font-bold text-slate-900">

                    Kantor Pemerintah Desa
                </h2>

                <p class="mt-4 text-lg leading-8 text-slate-500">

                    Kunjungi kantor kami atau hubungi melalui kontak yang tersedia.
                    Kami siap memberikan pelayanan terbaik kepada masyarakat.

                </p>

            </div>

            <!-- Content -->

            <div class="mt-16 grid gap-8 lg:grid-cols-5">

                <!-- ================= LEFT ================= -->

                <div class="lg:col-span-2">

                    <div class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-100 h-full">

                        <div class="flex items-center gap-4">

                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-teal-100">

                                <i class="bi bi-building-fill text-3xl text-teal-700"></i>

                            </div>

                            <div>

                                <h3 class="text-2xl font-bold text-slate-900">

                                    <?= htmlspecialchars($village['village_name']); ?>

                                </h3>

                                <p class="text-slate-500">

                                    Kantor Pemerintah Desa

                                </p>

                            </div>

                        </div>

                        <!-- Informasi -->

                        <div class="mt-10 space-y-6">

                            <!-- Alamat -->

                            <div class="flex items-start gap-4">

                                <div class="mt-1 text-teal-600">

                                    <i class="bi bi-geo-alt-fill"></i>

                                </div>

                                <div>

                                    <p class="text-sm text-slate-500">

                                        Alamat

                                    </p>

                                    <p class="mt-1 font-medium leading-7 text-slate-800">

                                        <?= nl2br(htmlspecialchars($village['office_address'])); ?>

                                    </p>

                                </div>

                            </div>

                            <!-- Telepon -->

                            <?php if (!empty($village['phone'])): ?>

                                <div class="flex items-start gap-4">

                                    <div class="mt-1 text-teal-600">

                                        <i class="bi bi-telephone-fill"></i>

                                    </div>

                                    <div>

                                        <p class="text-sm text-slate-500">

                                            Telepon

                                        </p>

                                        <a
                                            href="tel:<?= $village['phone']; ?>"
                                            class="mt-1 font-medium text-slate-800 hover:text-teal-700">

                                            <?= $village['phone']; ?>

                                        </a>

                                    </div>

                                </div>

                            <?php endif; ?>

                            <!-- Email -->

                            <?php if (!empty($village['email'])): ?>

                                <div class="flex items-start gap-4">

                                    <div class="mt-1 text-teal-600">

                                        <i class="bi bi-envelope-fill"></i>

                                    </div>

                                    <div>

                                        <p class="text-sm text-slate-500">

                                            Email

                                        </p>

                                        <a
                                            href="mailto:<?= $village['email']; ?>"
                                            class="mt-1 font-medium text-slate-800 hover:text-teal-700">

                                            <?= $village['email']; ?>

                                        </a>

                                    </div>

                                </div>

                            <?php endif; ?>

                            <!-- Jam Pelayanan -->

                            <div class="flex items-start gap-4">

                                <div class="mt-1 text-teal-600">

                                    <i class="bi bi-clock-fill"></i>

                                </div>


                                <div class="flex-1">

                                    <p class="text-sm text-slate-500 mb-2">

                                        Jam Pelayanan

                                    </p>


                                    <?php if (!empty($village['office_hours'])): ?>

                                        <div class="space-y-2">


                                            <?php

                                            $hours = explode("\n", $village['office_hours']);


                                            foreach ($hours as $hour):

                                                if (trim($hour) == '') continue;

                                            ?>


                                                <div class="flex items-center gap-2">


                                                    <i class="bi bi-check-circle-fill text-teal-600 text-sm"></i>


                                                    <p class="font-medium text-slate-800">

                                                        <?= htmlspecialchars(trim($hour)); ?>

                                                    </p>


                                                </div>


                                            <?php endforeach; ?>


                                        </div>


                                    <?php else: ?>


                                        <p class="text-slate-400">

                                            Jam pelayanan belum tersedia.

                                        </p>


                                    <?php endif; ?>


                                </div>


                            </div>
                        </div>


                    </div>

                </div>

                <!-- ================= MAP ================= -->
                <div class="lg:col-span-3">

                    <div class="overflow-hidden rounded-[2rem] bg-white shadow-lg ring-1 ring-slate-100 h-full">


                        <?php if (!empty($village['google_maps'])): ?>


                            <div class="h-[450px] w-full">


                                <?= str_replace(
                                    [
                                        '<iframe',
                                        'width="600"',
                                        'height="450"',
                                        'style="border:0;"'
                                    ],
                                    [
                                        '<iframe class="h-full w-full border-0"',
                                        '',
                                        '',
                                        ''
                                    ],
                                    $village['google_maps']
                                ); ?>


                            </div>

                        <?php else: ?>


                            <div class="flex h-[450px] items-center justify-center bg-slate-100">


                                <div class="text-center">


                                    <i class="bi bi-map text-7xl text-slate-400"></i>


                                    <p class="mt-4 text-slate-500">

                                        Google Maps belum tersedia.

                                    </p>


                                </div>


                            </div>



                        <?php endif; ?>


                    </div>


                </div>

            </div>

        </div>

    </section>

    <?php if (!empty($popups)): ?>


        <div
            x-data="announcementPopup()"
            x-show="show"
            x-cloak
            class="fixed inset-0 z-[999] flex items-center justify-center px-6">


            <!-- Overlay -->

            <div
                class="absolute inset-0 bg-black/50"
                @click="closePopup()">
            </div>




            <!-- Modal -->

            <div
                class="relative z-10 w-full max-w-lg rounded-3xl bg-white shadow-2xl overflow-hidden">


                <!-- Header -->

                <div class="flex items-start justify-between p-6 border-b">


                    <div class="flex items-center gap-4">


                        <div
                            class="w-14 h-14 rounded-2xl bg-teal-100 flex items-center justify-center">


                            <i
                                :class="current.icon"
                                class="text-2xl text-teal-600">
                            </i>


                        </div>



                        <div>


                            <p class="text-sm text-slate-500"
                                x-text="current.type">
                            </p>



                            <h2
                                class="text-xl font-bold text-slate-900"
                                x-text="current.title">
                            </h2>


                        </div>


                    </div>





                    <!-- Close -->


                    <button
                        @click="closePopup()"
                        class="w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center">


                        <i class="bi bi-x-lg text-xl"></i>


                    </button>


                </div>





                <!-- Content -->


                <div class="p-6">


                    <div
                        class="text-slate-600 leading-7"
                        x-html="current.content">
                    </div>


                </div>





                <!-- Footer -->


                <div class="px-6 pb-6 flex justify-between items-center">


                    <p class="text-sm text-slate-400">

                        <span x-text="index + 1"></span>
                        /
                        <?= count($popups); ?>

                    </p>



                    <button
                        @click="closePopup()"
                        class="px-6 py-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white">


                        Mengerti


                    </button>


                </div>



            </div>



        </div>





        <script>
            function announcementPopup() {


                let announcements = <?= json_encode($popups); ?>;


                let closed = JSON.parse(
                    localStorage.getItem('closed_announcements') || '[]'
                );


                announcements = announcements.filter(item => {

                    return !closed.includes(
                        Number(item.id)
                    );

                });



                return {


                    announcements,


                    index: 0,


                    show: announcements.length > 0,


                    get current() {


                        return this.announcements[this.index];


                    },



                    closePopup() {


                        let id = Number(this.current.id);



                        let closed = JSON.parse(
                            localStorage.getItem('closed_announcements') || '[]'
                        );



                        if (!closed.includes(id)) {


                            closed.push(id);


                            localStorage.setItem(
                                'closed_announcements',
                                JSON.stringify(closed)
                            );


                        }




                        this.index++;




                        if (this.index >= this.announcements.length) {


                            this.show = false;


                        }



                    }



                }


            }
        </script>


    <?php endif; ?>


    <!-- ======= FOOTER ======= -->
    <?php include APP_PATH . 'includes/guest/footer.php'; ?>




    <script>
        window.addEventListener("load", function() {

            const loader = document.getElementById("loading-screen");


            setTimeout(() => {

                loader.classList.add(
                    "opacity-0",
                    "pointer-events-none"
                );


                setTimeout(() => {

                    loader.remove();

                }, 700);


            }, 650);

        });
    </script>
</body>

</html>