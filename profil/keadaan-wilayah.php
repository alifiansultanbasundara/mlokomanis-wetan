<?php

// include "../auth/auth.php";
include "../config/database.php";

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
// Data Wilayah
// ======================================

$queryWilayah = mysqli_query($conn, "
    SELECT *
    FROM wilayah
    WHERE status = 'Published'
    ORDER BY created_at DESC
");

$wilayah = [];

while ($row = mysqli_fetch_assoc($queryWilayah)) {
    $wilayah[] = $row;
}

// ======================================
// Helper
// ======================================

function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
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
                <a href="../index.php" class="flex items-center gap-3 z-20">
                    <img src="../assets/img/logo.webp" class="w-12 h-12 object-contain rounded-xl bg-white p-1.5 shadow-md" />

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
                    <a href="../index.php" class="hover:text-emerald-500 transition">
                        Beranda
                    </a>

                    <!-- Profil Desa -->
                    <div class="relative group">

                        <button class="flex items-center gap-1 hover:text-emerald-500 transition">
                            Profil Desa
                            <i class="bi bi-chevron-down text-xs"></i>
                        </button>

                        <div class="absolute left-0 mt-4 w-60 rounded-2xl bg-white shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200">

                            <a href="sejarah.php" class="block px-5 py-3 hover:bg-emerald-50 rounded-2xl">
                                Sejarah Desa
                            </a>

                            <a href="visi-misi.php" class="block px-5 py-3 hover:bg-emerald-50">
                                Visi & Misi
                            </a>

                            <a href="struktur-organisasi.php" class="block px-5 py-3 hover:bg-emerald-50">
                                Struktur Organisasi
                            </a>

                            <a href="#" class="block px-5 py-3 hover:bg-emerald-50 rounded-b-2xl">
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

                            <a href="../berita" class="rounded-2xl">Berita Desa</a>
                            <a href="../informasi/galeri">Galeri</a>
                            <a href="../informasi/produk-hukum/">Produk Hukum</a>
                            <a href="../informasi/pembangunan">Pembangunan</a>
                            <a href="../informasi/pengelolaan-keuangan">Pengelolaan Keuangan</a>
                            <a href="../informasi/aset-desa">Aset Desa</a>
                            <a href="../informasi/bansos/" class="rounded-b-2xl">Bantuan Sosial</a>

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

<!-- ====================================== -->
<!-- Hero -->
<!-- ====================================== -->

<section class="relative py-24 overflow-hidden bg-gradient-to-b from-emerald-50 via-cyan-50 to-white">

    <!-- Background Decoration -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-emerald-200/30 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-cyan-200/30 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6">

        <div class="max-w-4xl mx-auto text-center">

            <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-600 px-4 py-2 rounded-full font-medium text-sm">
                <i class="bi bi-map-fill"></i>
                Profil Desa
            </span>

            <h1 class="mt-6 text-5xl md:text-6xl font-extrabold leading-tight text-gray-900">
                Keadaan
                <span class="text-emerald-500">
                    Wilayah
                </span>
            </h1>

            <p class="mt-6 text-lg leading-8 text-gray-600 max-w-3xl mx-auto">
                Informasi mengenai kondisi wilayah, peta administrasi, batas wilayah,
                serta berbagai data kewilayahan
                <span class="font-semibold text-emerald-600">
                    <?= e($profil['nama_desa']) ?>
                </span>
                sebagai sumber informasi bagi masyarakat maupun pengunjung.
            </p>

        </div>

    </div>

</section>

<!-- ====================================== -->
<!-- Statistik Desa -->
<!-- ====================================== -->

<section class="py-20 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14">

            <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-600 px-4 py-2 rounded-full font-medium text-sm">
                <i class="bi bi-bar-chart-fill"></i>
                Statistik Desa
            </span>

            <h2 class="mt-5 text-4xl font-extrabold text-gray-900">
                Gambaran Umum
                <span class="text-emerald-500">
                    Wilayah Desa
                </span>
            </h2>

            <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
                Informasi singkat mengenai kondisi wilayah dan kependudukan
                <?= e($profil['nama_desa']) ?>.
            </p>

        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

            <!-- Luas Wilayah -->
            <div class="group bg-white rounded-3xl border border-emerald-100 p-8 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">

                <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl">
                    <i class="bi bi-map"></i>
                </div>

                <h3 class="mt-6 text-3xl font-extrabold text-gray-900">
                    <?= $profil['luas_wilayah'] ? number_format($profil['luas_wilayah'], 2, ',', '.') : '-' ?>
                </h3>

                <p class="mt-2 text-gray-600">
                    Hektare Luas Wilayah
                </p>

            </div>

            <!-- Penduduk -->
            <div class="group bg-white rounded-3xl border border-emerald-100 p-8 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">

                <div class="w-16 h-16 rounded-2xl bg-cyan-100 text-cyan-600 flex items-center justify-center text-3xl">
                    <i class="bi bi-people-fill"></i>
                </div>

                <h3 class="mt-6 text-3xl font-extrabold text-gray-900">
                    <?= number_format($profil['jumlah_penduduk'] ?? 0, 0, ',', '.') ?>
                </h3>

                <p class="mt-2 text-gray-600">
                    Jumlah Penduduk
                </p>

            </div>

            <!-- Kepala Keluarga -->
            <div class="group bg-white rounded-3xl border border-emerald-100 p-8 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">

                <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-3xl">
                    <i class="bi bi-house-door-fill"></i>
                </div>

                <h3 class="mt-6 text-3xl font-extrabold text-gray-900">
                    <?= number_format($profil['jumlah_kk'] ?? 0, 0, ',', '.') ?>
                </h3>

                <p class="mt-2 text-gray-600">
                    Kepala Keluarga
                </p>

            </div>

            <!-- Alamat -->
            <div class="group bg-white rounded-3xl border border-emerald-100 p-8 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">

                <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-3xl">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>

                <h3 class="mt-6 text-xl font-bold text-gray-900 leading-8">
                    <?= e($profil['kecamatan']) ?>
                </h3>

                <p class="mt-2 text-gray-600">
                    Kecamatan <?= e($profil['kabupaten']) ?>
                </p>

            </div>

        </div>

    </div>

</section>


<!-- ====================================== -->
<!-- Daftar Wilayah -->
<!-- ====================================== -->

<section class="py-20 bg-gradient-to-b from-white to-emerald-50">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14">

            <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-600 px-4 py-2 rounded-full font-medium text-sm">
                <i class="bi bi-map-fill"></i>
                Informasi Wilayah
            </span>

            <h2 class="mt-5 text-4xl font-extrabold text-gray-900">
                Data &
                <span class="text-emerald-500">
                    Peta Wilayah
                </span>
            </h2>

            <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
                Berbagai informasi kewilayahan Desa
                <span class="font-semibold text-emerald-600">
                    <?= e($profil['nama_desa']) ?>
                </span>
                yang dapat diakses oleh masyarakat.
            </p>

        </div>

        <?php if (count($wilayah) > 0): ?>

            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">

                <?php foreach ($wilayah as $item): ?>

                    <?php

                    $image = !empty($item['image'])
                        ? "../uploads/wilayah/" . $item['image']
                        : "../assets/img/no-image.webp";

                    ?>

                    <article class="group bg-white rounded-3xl overflow-hidden border border-emerald-100 shadow-sm hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">

                        <!-- Thumbnail -->

                        <div class="relative overflow-hidden">

                            <img
                                src="<?= $image ?>"
                                alt="<?= e($item['title']) ?>"
                                class="w-full h-60 object-cover group-hover:scale-110 transition duration-500">

                            <span class="absolute top-4 left-4 bg-emerald-500 text-white text-xs font-semibold px-3 py-1 rounded-full">

                                <?= e($item['type']) ?>

                            </span>

                        </div>

                        <!-- Content -->

                        <div class="p-7">

                            <h3 class="text-2xl font-bold text-gray-900 group-hover:text-emerald-600 transition">

                                <?= e($item['title']) ?>

                            </h3>

                            <p class="mt-4 text-gray-600 leading-7 line-clamp-3">

                                <?= e($item['description']) ?>

                            </p>

                            <div class="mt-8 flex items-center justify-between">

                                <span class="text-sm text-gray-500">

                                    <i class="bi bi-calendar-event"></i>

                                    <?= date('d M Y', strtotime($item['created_at'])) ?>

                                </span>

                                <a
                                    href="detail-wilayah.php?slug=<?= urlencode($item['slug']) ?>"
                                    class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-3 rounded-full transition">

                                    Lihat Detail

                                    <i class="bi bi-arrow-right"></i>

                                </a>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="bg-white rounded-3xl border border-dashed border-emerald-200 p-20 text-center">

                <div class="w-24 h-24 mx-auto rounded-full bg-emerald-100 flex items-center justify-center">

                    <i class="bi bi-map text-5xl text-emerald-600"></i>

                </div>

                <h3 class="mt-8 text-2xl font-bold text-gray-800">

                    Belum Ada Data Wilayah

                </h3>

                <p class="mt-4 text-gray-500 max-w-lg mx-auto">

                    Informasi wilayah masih dalam proses pembaruan.
                    Silakan kembali beberapa waktu lagi.

                </p>

            </div>

        <?php endif; ?>

    </div>

</section>