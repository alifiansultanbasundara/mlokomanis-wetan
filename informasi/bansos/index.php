<?php

// include "../../auth/auth.php";
include "../../config/database.php";

// ======================================
// Profil Desa
// ======================================

$queryProfil = mysqli_query($conn, "
    SELECT *
    FROM profil_desa
    LIMIT 1
");

$profil = mysqli_fetch_assoc($queryProfil);

// ======================================
// Pagination
// ======================================

$limit = 6;

$page = isset($_GET['page'])
    ? max(1, (int)$_GET['page'])
    : 1;

$offset = ($page - 1) * $limit;

// ======================================
// Total Program
// ======================================

$queryTotal = mysqli_query($conn, "
    SELECT COUNT(*) total
    FROM bansos
    WHERE status IN ('Published','Selesai')
");

$total = mysqli_fetch_assoc($queryTotal);

$totalPage = ceil($total['total'] / $limit);

// ======================================
// Featured Program
// ======================================

$queryFeatured = mysqli_query($conn, "
    SELECT *
    FROM bansos
    WHERE status='Published'
    ORDER BY schedule_date ASC,
             created_at DESC
    LIMIT 1
");

$featured = mysqli_fetch_assoc($queryFeatured);

// ======================================
// Daftar Program
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM bansos
    WHERE status IN ('Published','Selesai')
    ORDER BY
        FIELD(status,'Published','Selesai'),
        schedule_date ASC,
        created_at DESC
    LIMIT $offset,$limit
");

$bansos = [];

while ($row = mysqli_fetch_assoc($query)) {
    $bansos[] = $row;
}

// ======================================
// Statistik
// ======================================

$queryStat = mysqli_query($conn, "
    SELECT
        COUNT(*) total,
        SUM(status='Published') aktif,
        SUM(status='Selesai') selesai,
        SUM(quota) kuota
    FROM bansos
");

$stat = mysqli_fetch_assoc($queryStat);

// ======================================
// Helper
// ======================================

function e($text)
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Primary Meta -->
    <title>Desa Mlokomanis Wetan</title>
    <meta name="title" content="Desa Mlokomanis Wetan" />
    <meta name="description" content="Website resmi Desa Mlokomanis Wetan, Kecamatan Ngadirojo, Kabupaten Wonogiri. Informasi desa, profil, layanan masyarakat, dan berbagai informasi terbaru desa." />

    <meta name="keywords" content="Desa Mlokomanis Wetan, Desa Wonogiri, Ngadirojo, Pemerintah Desa, Layanan Desa" />

    <meta name="author" content="Pemerintah Desa Mlokomanis Wetan" />

    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="assets/img/logo.webp" />
    <link rel="shortcut icon" href="assets/img/logo.webp" />
    <link rel="apple-touch-icon" href="assets/img/logo.webp" />

    <!-- Open Graph (WhatsApp, Facebook, LinkedIn) -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://desa-mlokomanis-wetan.vercel.app/" />
    <meta property="og:title" content="Desa Mlokomanis Wetan" />
    <meta property="og:description" content="Website resmi Desa Mlokomanis Wetan. Temukan informasi profil desa, layanan masyarakat, berita, dan informasi terbaru desa." />
    <meta property="og:image" content="https://desa-mlokomanis-wetan.vercel.app/assets/img/logo.webp" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Desa Mlokomanis Wetan" />
    <meta name="twitter:description" content="Website resmi Desa Mlokomanis Wetan, Kecamatan Ngadirojo, Kabupaten Wonogiri." />
    <meta name="twitter:image" content="https://desa-mlokomanis-wetan.vercel.app/assets/img/logo.webp" />

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs/dist/cdn.min.js"></script>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <style>
        html,
        body {
            scroll-behavior: smooth;
        }

        .group .absolute a {
            display: block;
            padding: 12px 20px;
            color: #374151;
            transition: .2s;
        }

        .group .absolute a:hover {
            background: #ECFDF5;
            color: #059669;
        }
    </style>
</head>

<body
    class="bg-stone-50 text-stone-800"
    x-data="{
        scrolled:true,
        isMobile: window.innerWidth < 1024
    }"
    @scroll.window="scrolled = window.scrollY > -10"
    @resize.window="isMobile = window.innerWidth < 1024">

    <nav
        x-data="{
        mobileMenu:false,
        profile:false,
        informasi:false,
        wilayah:false,
        surat:false
    }"
        :class="
        isMobile || scrolled || mobileMenu
            ? 'bg-white/90 backdrop-blur-xl shadow-lg border-b border-emerald-100'
            : 'bg-white/10 backdrop-blur-md'
    "
        class="fixed inset-x-0 top-0 z-50 transition-all duration-300">

        <div class="relative max-w-7xl mx-auto px-6">
            <div class="h-20 flex items-center justify-between">
                <!-- Logo -->
                <a href="../../index.php" class="flex items-center gap-3 z-20">
                    <img src="../../assets/img/logo.webp" class="w-12 h-12 object-contain rounded-xl bg-white p-1.5 shadow-md" />

                    <div>
                        <h1
                            :class="
                            isMobile || scrolled || mobileMenu
                                ? 'text-gray-900'
                                : 'text-white'
                        "
                            class="font-bold text-lg leading-none">
                            Desa Mlokomanis Wetan
                        </h1>

                        <p
                            :class="
                            isMobile || scrolled || mobileMenu
                                ? 'text-emerald-600'
                                : 'text-emerald-200'
                        "
                            class="text-xs">
                            Ngadirojo • Wonogiri
                        </p>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div
                    :class="scrolled ? 'text-gray-700' : 'text-white'"
                    class="hidden lg:flex absolute left-1/2 -translate-x-1/2 items-center gap-8 font-medium text-sm">

                    <!-- Beranda -->
                    <a href="../../index.php" class="hover:text-emerald-500 transition">
                        Beranda
                    </a>

                    <!-- Profil Desa -->
                    <div class="relative group">

                        <button class="flex items-center gap-1 hover:text-emerald-500 transition">
                            Profil Desa
                            <i class="bi bi-chevron-down text-xs"></i>
                        </button>

                        <div class="absolute left-0 mt-4 w-60 rounded-2xl bg-white shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200">

                            <a href="../../profil/sejarah.php" class="block px-5 py-3 hover:bg-emerald-50 rounded-2xl">
                                Sejarah Desa
                            </a>

                            <a href="../../profil/visi-misi.php" class="block px-5 py-3 hover:bg-emerald-50">
                                Visi & Misi
                            </a>

                            <a href="../../profil/struktur-organisasi.php" class="block px-5 py-3 hover:bg-emerald-50">
                                Struktur Organisasi
                            </a>

                            <a href="../../profil/keadaan-wilayah.php" class="block px-5 py-3 hover:bg-emerald-50 rounded-b-2xl">
                                Keadaan Wilayah
                            </a>

                        </div>

                    </div>

                    <!-- Informasi -->
                    <div class="relative group">

                        <button class="flex items-center gap-1 hover:text-emerald-500 transition">
                            Informasi
                            <i class="bi bi-chevron-down text-xs"></i>
                        </button>

                        <div class="absolute left-0 mt-4 w-72 rounded-2xl bg-white shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition">

                            <a href="../../berita" class="rounded-2xl">Berita Desa</a>
                            <a href="../../informasi/galeri">Galeri</a>
                            <a href="../../informasi/produk-hukum/">Produk Hukum</a>
                            <a href="../../informasi/pembangunan">Pembangunan</a>
                            <a href="../../informasi/pengelolaan-keuangan">Pengelolaan Keuangan</a>
                            <a href="../../informasi/aset-desa">Aset Desa</a>
                            <a href="../../informasi/bansos/" class="rounded-b-2xl">Bantuan Sosial</a>

                        </div>

                    </div>

                    <!-- Kewilayahan -->
                    <div class="relative group">

                        <button class="flex items-center gap-1 hover:text-emerald-500 transition">
                            Kewilayahan
                            <i class="bi bi-chevron-down text-xs"></i>
                        </button>

                        <div class="absolute left-0 mt-4 w-64 rounded-2xl bg-white shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition">

                            <a href="#" class="rounded-2xl">Peta Administrasi</a>
                            <a href="#">Peta Blok SPPT PBB</a>
                            <a href="#">Peta RT</a>
                            <a href="#" class="rounded-b-2xl">Peta Mata Pencaharian</a>

                        </div>

                    </div>

                    <!-- Pelayanan -->
                    <div class="relative group">

                        <button class="flex items-center gap-1 hover:text-emerald-500 transition">
                            Pelayanan Surat
                            <i class="bi bi-chevron-down text-xs"></i>
                        </button>

                        <div class="absolute left-0 mt-4 w-64 rounded-2xl bg-white shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition">

                            <a href="#" class="rounded-2xl">Daftar Jenis Surat</a>
                            <a href="#">Form Pengajuan Surat</a>
                            <a href="#" class="rounded-b-2xl">Cek Status Pengajuan</a>

                        </div>

                    </div>

                    <a href="kontak.php" class="hover:text-emerald-500 transition">
                        Kontak
                    </a>

                </div>

                <!-- Right -->
                <div class="flex items-center gap-3 z-20">
                    <!-- Desktop Button -->
                    <?php if (isset($_SESSION['login'])): ?>

                        <a
                            href="dashboard.php"
                            class="hidden lg:inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-white font-medium hover:bg-emerald-700 transition">

                            <i class="bi bi-speedometer2"></i>

                            Dashboard

                        </a>

                    <?php else: ?>

                        <a
                            href="auth/login.php"
                            class="hidden lg:inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-white font-medium hover:bg-emerald-700 transition">

                            <i class="bi bi-box-arrow-in-right"></i>

                            Login

                        </a>

                    <?php endif; ?>

                    <!-- Mobile Button -->
                    <button @click="mobileMenu=!mobileMenu" class="lg:hidden w-11 h-11 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center transition hover:bg-emerald-100 hover:text-emerald-600">
                        <i class="bi" :class="mobileMenu ? 'bi-x-lg' : 'bi-list'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div
            x-show="mobileMenu"
            x-transition
            @click.outside="mobileMenu=false"
            class="lg:hidden border-t border-emerald-100 bg-white shadow-xl">

            <div class="px-6 py-5 space-y-2">

                <!-- Beranda -->
                <a
                    href="index.php"
                    @click="mobileMenu=false"
                    class="block rounded-xl px-4 py-3 font-medium hover:bg-emerald-50 hover:text-emerald-600 transition">
                    <i class="bi bi-house-door me-2"></i>
                    Beranda
                </a>

                <!-- Profil Desa -->
                <div class="border border-slate-200 rounded-xl overflow-hidden">

                    <button
                        @click="profile=!profile"
                        class="w-full flex justify-between items-center px-4 py-3 font-medium hover:bg-emerald-50 transition">
                        <span>
                            <i class="bi bi-building me-2"></i>
                            Profil Desa
                        </span>

                        <i
                            class="bi transition"
                            :class="profile ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>

                    <div x-show="profile" x-collapse class="bg-slate-50">

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">
                            Sejarah Desa
                        </a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">
                            Visi & Misi
                        </a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">
                            Struktur Organisasi
                        </a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">
                            Keadaan Wilayah
                        </a>

                    </div>

                </div>

                <!-- Informasi -->
                <div class="border border-slate-200 rounded-xl overflow-hidden">

                    <button
                        @click="informasi=!informasi"
                        class="w-full flex justify-between items-center px-4 py-3 font-medium hover:bg-emerald-50 transition">
                        <span>
                            <i class="bi bi-newspaper me-2"></i>
                            Informasi
                        </span>

                        <i
                            class="bi transition"
                            :class="informasi ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>

                    <div x-show="informasi" x-collapse class="bg-slate-50">

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">Berita Desa</a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">Galeri</a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">Produk Hukum</a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">Pembangunan</a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">Pengelolaan Keuangan</a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">Aset Desa</a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">Bantuan Sosial</a>

                    </div>

                </div>

                <!-- Kewilayahan -->
                <div class="border border-slate-200 rounded-xl overflow-hidden">

                    <button
                        @click="wilayah=!wilayah"
                        class="w-full flex justify-between items-center px-4 py-3 font-medium hover:bg-emerald-50 transition">
                        <span>
                            <i class="bi bi-map me-2"></i>
                            Kewilayahan
                        </span>

                        <i
                            class="bi transition"
                            :class="wilayah ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>

                    <div x-show="wilayah" x-collapse class="bg-slate-50">

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">
                            Peta Administrasi
                        </a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">
                            Peta Blok SPPT PBB
                        </a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">
                            Peta RT
                        </a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">
                            Peta Mata Pencaharian
                        </a>

                    </div>

                </div>

                <!-- Pelayanan Surat -->
                <div class="border border-slate-200 rounded-xl overflow-hidden">

                    <button
                        @click="surat=!surat"
                        class="w-full flex justify-between items-center px-4 py-3 font-medium hover:bg-emerald-50 transition">
                        <span>
                            <i class="bi bi-envelope-paper me-2"></i>
                            Pelayanan Surat
                        </span>

                        <i
                            class="bi transition"
                            :class="surat ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>

                    <div x-show="surat" x-collapse class="bg-slate-50">

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">
                            Daftar Jenis Surat
                        </a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">
                            Form Pengajuan Surat
                        </a>

                        <a href="#" class="block px-10 py-3 hover:bg-emerald-100">
                            Cek Status Pengajuan
                        </a>

                    </div>

                </div>

                <!-- Kontak -->
                <a
                    href="kontak.php"
                    @click="mobileMenu=false"
                    class="block rounded-xl px-4 py-3 font-medium hover:bg-emerald-50 hover:text-emerald-600 transition">
                    <i class="bi bi-telephone me-2"></i>
                    Kontak
                </a>

                <!-- Login -->
                <?php if (isset($_SESSION['login'])): ?>

                    <a
                        href="dashboard.php"
                        @click="mobileMenu=false"
                        class="mt-5 flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-white font-semibold hover:bg-emerald-700 transition">

                        <i class="bi bi-speedometer2"></i>

                        Dashboard

                    </a>

                <?php else: ?>

                    <a
                        href="auth/login.php"
                        @click="mobileMenu=false"
                        class="mt-5 flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-white font-semibold hover:bg-emerald-700 transition">

                        <i class="bi bi-box-arrow-in-right"></i>

                        Login Admin

                    </a>

                <?php endif; ?>

            </div>

        </div>
    </nav>

</body>

<body>
    <section class="relative py-24 bg-gradient-to-b from-emerald-50 via-cyan-50 to-white">

        <div class="max-w-5xl mx-auto px-6 text-center">

            <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-600 px-4 py-2 rounded-full font-medium text-sm">

                <i class="bi bi-heart-pulse-fill"></i>

                Layanan Desa

            </span>

            <h1 class="mt-6 text-5xl font-extrabold text-gray-900">

                Program

                <span class="text-emerald-500">

                    Bantuan Sosial

                </span>

            </h1>

            <p class="mt-6 text-lg text-gray-600 leading-8">

                Informasi program bantuan sosial,
                jadwal penyaluran, persyaratan,
                serta kuota penerima bantuan
                di <?= e($profil['nama_desa']) ?>.

            </p>

        </div>

    </section>

    <section class="py-16 bg-white">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-emerald-50 rounded-3xl p-8 text-center">
                    <div class="text-5xl text-emerald-600">
                        <i class="bi bi-folder2-open"></i>
                    </div>

                    <h3 class="mt-5 text-4xl font-extrabold">
                        <?= number_format($stat['total']) ?>
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Total Program
                    </p>
                </div>

                <div class="bg-green-50 rounded-3xl p-8 text-center">

                    <div class="text-5xl text-green-600">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <h3 class="mt-5 text-4xl font-extrabold">
                        <?= number_format($stat['aktif']) ?>
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Program Aktif
                    </p>

                </div>

                <div class="bg-slate-50 rounded-3xl p-8 text-center">

                    <div class="text-5xl text-slate-600">
                        <i class="bi bi-check2-all"></i>
                    </div>

                    <h3 class="mt-5 text-4xl font-extrabold">
                        <?= number_format($stat['selesai']) ?>
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Program Selesai
                    </p>

                </div>

                <div class="bg-cyan-50 rounded-3xl p-8 text-center">

                    <div class="text-5xl text-cyan-600">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <h3 class="mt-5 text-4xl font-extrabold">
                        <?= number_format($stat['kuota']) ?>
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Total Kuota
                    </p>

                </div>

            </div>

        </div>

    </section>

    <?php if ($featured): ?>

        <section class="py-20 bg-gradient-to-b from-white to-emerald-50">

            <div class="max-w-7xl mx-auto px-6">

                <div class="text-center mb-12">

                    <span class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full">

                        ⭐ Program Utama

                    </span>

                    <h2 class="mt-5 text-4xl font-extrabold">

                        Program Bantuan Terdekat

                    </h2>

                </div>

                <?php

                $image = !empty($featured['image'])
                    ? "../../bansos/uploads/" . $featured['image']
                    : "../../assets/img/no-image.webp";

                ?>

                <div class="grid lg:grid-cols-2 gap-10 bg-white rounded-3xl overflow-hidden shadow-xl">

                    <img
                        src="<?= $image ?>"
                        class="w-full h-full object-cover min-h-[420px]">

                    <div class="p-10">

                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full">

                            <?= e($featured['status']) ?>

                        </span>

                        <h2 class="mt-6 text-4xl font-bold">

                            <?= e($featured['program_name']) ?>

                        </h2>

                        <p class="mt-5 text-gray-600 leading-8">

                            <?= nl2br(e($featured['description'])) ?>

                        </p>

                        <div class="mt-8 space-y-3">

                            <div>
                                📅
                                <?= date('d F Y', strtotime($featured['schedule_date'])) ?>
                            </div>

                            <div>
                                📍
                                <?= e($featured['location']) ?>
                            </div>

                            <div>
                                👥
                                <?= number_format($featured['quota']) ?>
                                Penerima
                            </div>

                        </div>

                        <a
                            href="detail.php?slug=<?= $featured['slug'] ?>"
                            class="inline-flex mt-8 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-full">

                            Lihat Detail

                        </a>

                    </div>

                </div>

            </div>

        </section>

    <?php endif; ?>

    <section class="py-20 bg-white">

        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-14">

                <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full">

                    <i class="bi bi-grid"></i>

                    Daftar Program

                </span>

                <h2 class="mt-5 text-4xl font-extrabold">

                    Semua Program Bantuan Sosial

                </h2>

            </div>

            <?php if (count($bansos)): ?>

                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">

                    <?php foreach ($bansos as $item):

                        $image = !empty($item['image'])
                            ? "../../bansos/uploads/" . $item['image']
                            : "../../assets/img/no-image.webp";

                    ?>

                        <article class="bg-white rounded-3xl border border-emerald-100 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-2 transition">

                            <img
                                src="<?= $image ?>"
                                class="h-60 w-full object-cover">

                            <div class="p-6">

                                <span class="<?= $item['status'] == 'Published'
                                                    ? 'bg-green-100 text-green-700'
                                                    : 'bg-slate-100 text-slate-700' ?>

                        px-3 py-1 rounded-full text-xs">

                                    <?= e($item['status']) ?>

                                </span>

                                <h3 class="mt-5 text-2xl font-bold">

                                    <?= e($item['program_name']) ?>

                                </h3>

                                <p class="mt-4 text-gray-600 line-clamp-3">

                                    <?= e($item['description']) ?>

                                </p>

                                <div class="mt-6 space-y-2 text-sm text-gray-500">

                                    <div>

                                        <i class="bi bi-calendar-event text-emerald-600"></i>

                                        <?= !empty($item['schedule_date']) ? date('d F Y', strtotime($item['schedule_date'])) : '-' ?>

                                    </div>

                                    <div>

                                        <i class="bi bi-geo-alt-fill text-emerald-600"></i>

                                        <?= e($item['location']) ?>

                                    </div>

                                    <div>

                                        <i class="bi bi-people-fill text-emerald-600"></i>

                                        <?= number_format($item['quota']) ?>

                                        Kuota

                                    </div>

                                </div>

                                <a
                                    href="detail.php?slug=<?= $item['slug'] ?>"
                                    class="inline-flex items-center gap-2 mt-8 text-emerald-600 font-semibold hover:text-emerald-700">

                                    Lihat Detail

                                    <i class="bi bi-arrow-right"></i>

                                </a>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="bg-white rounded-3xl border border-dashed border-emerald-200 p-20 text-center">

                    <i class="bi bi-heart-pulse text-6xl text-emerald-500"></i>

                    <h3 class="mt-6 text-3xl font-bold">

                        Belum Ada Program Bantuan

                    </h3>

                    <p class="mt-3 text-gray-500">

                        Saat ini belum terdapat program bantuan sosial.

                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>

    <?php if ($totalPage > 1): ?>

        <section class="pb-24 bg-white">

            <div class="flex justify-center">

                <div class="inline-flex rounded-2xl overflow-hidden border">

                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>

                        <a
                            href="?page=<?= $i ?>"
                            class="<?= $page == $i
                                        ? 'bg-emerald-600 text-white'
                                        : 'bg-white hover:bg-emerald-50 text-gray-700' ?>

                    px-5 py-3 transition">

                            <?= $i ?>

                        </a>

                    <?php endfor; ?>

                </div>

            </div>

        </section>

    <?php endif; ?>
</body>