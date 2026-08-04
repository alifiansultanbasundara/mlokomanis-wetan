<?php

// include "../../auth/auth.php";
include "../../config/database.php";

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location:index.php");
    exit;
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);

$query = mysqli_query($conn, "
    SELECT *
    FROM bansos
    WHERE slug='$slug'
    AND status IN ('Published','Selesai')
    LIMIT 1
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location:index.php");
    exit;
}

// ========================================
// Program Lainnya
// ========================================

$queryLain = mysqli_query($conn, "
    SELECT
        id,
        title,
        slug,
        image,
        schedule_date,
        location,
        quota,
        status
    FROM bansos
    WHERE slug!='$slug'
    AND status IN ('Published','Selesai')
    ORDER BY
        FIELD(status,'Published','Selesai'),
        schedule_date ASC
    LIMIT 4
");

$lainnya = [];

while ($row = mysqli_fetch_assoc($queryLain)) {
    $lainnya[] = $row;
}

// ========================================
// Jumlah Penerima
// ========================================

$queryJumlah = mysqli_query($conn, "
    SELECT COUNT(*) total
    FROM bansos_recipients
    WHERE bansos_id=" . $data['id'] . "
");

$jumlah = mysqli_fetch_assoc($queryJumlah);

// ========================================
// Helper
// ========================================

function e($text)
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

$image = !empty($data['image'])
    ? "../../bansos/uploads/" . $data['image']
    : "../assets/img/no-image.webp";
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

    <section class="bg-gray-50 border-b">

        <div class="max-w-7xl mx-auto px-6 py-4 text-sm text-gray-500">

            <a href="../index.php" class="hover:text-emerald-600">
                Beranda
            </a>

            <span class="mx-2">/</span>

            <a href="index.php" class="hover:text-emerald-600">
                Bantuan Sosial
            </a>

            <span class="mx-2">/</span>

            <span class="text-gray-700">

                <?= e($data['program_name']) ?>

            </span>

        </div>

    </section>

    <section class="py-20 bg-gradient-to-b from-emerald-50 via-cyan-50 to-white">

        <div class="max-w-5xl mx-auto px-6 text-center">

            <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full">

                <i class="bi bi-heart-pulse-fill"></i>

                Program Bantuan Sosial

            </span>

            <h1 class="mt-6 text-5xl font-extrabold text-gray-900">

                <?= e($data['program_name']) ?>

            </h1>

            <p class="mt-6 text-lg text-gray-600">

                <?= e($data['title']) ?>

            </p>

        </div>

    </section>

    <section class="-mt-8">

        <div class="max-w-6xl mx-auto px-6">

            <img
                src="<?= $image ?>"
                class="rounded-3xl shadow-2xl w-full h-[500px] object-cover">

        </div>

    </section>

    <section class="py-16">

        <div class="max-w-6xl mx-auto px-6">

            <div class="grid md:grid-cols-4 gap-6">

                <div class="bg-white rounded-3xl border border-emerald-100 p-8">

                    <i class="bi bi-calendar-event text-3xl text-emerald-600"></i>

                    <h3 class="mt-4 font-bold">

                        Jadwal

                    </h3>

                    <p class="mt-2 text-gray-600">

                        <?= !empty($data['schedule_date']) ? date('d F Y', strtotime($data['schedule_date'])) : '-' ?>

                    </p>

                </div>

                <div class="bg-white rounded-3xl border border-emerald-100 p-8">

                    <i class="bi bi-geo-alt-fill text-3xl text-emerald-600"></i>

                    <h3 class="mt-4 font-bold">

                        Lokasi

                    </h3>

                    <p class="mt-2 text-gray-600">

                        <?= e($data['location']) ?: '-' ?>

                    </p>

                </div>

                <div class="bg-white rounded-3xl border border-emerald-100 p-8">

                    <i class="bi bi-people-fill text-3xl text-emerald-600"></i>

                    <h3 class="mt-4 font-bold">

                        Kuota

                    </h3>

                    <p class="mt-2 text-gray-600">

                        <?= number_format($data['quota']) ?>

                        Penerima

                    </p>

                </div>

                <div class="bg-white rounded-3xl border border-emerald-100 p-8">

                    <i class="bi bi-person-check-fill text-3xl text-emerald-600"></i>

                    <h3 class="mt-4 font-bold">

                        Terdaftar

                    </h3>

                    <p class="mt-2 text-gray-600">

                        <?= number_format($jumlah['total']) ?>

                        Orang

                    </p>

                </div>

            </div>

        </div>

    </section>

    <section class="pb-20">

        <div class="max-w-4xl mx-auto px-6">

            <div class="bg-white rounded-3xl border border-emerald-100 p-10">

                <h2 class="text-3xl font-bold">

                    Tentang Program

                </h2>

                <div class="mt-8 leading-9 text-gray-700 whitespace-pre-line">

                    <?= nl2br(e($data['description'])) ?>

                </div>

            </div>

        </div>

    </section>


    <?php if (!empty($data['requirements'])): ?>

        <section class="pb-20">

            <div class="max-w-4xl mx-auto px-6">

                <div class="bg-emerald-50 rounded-3xl p-10">

                    <h2 class="text-3xl font-bold">

                        Persyaratan

                    </h2>

                    <div class="mt-8 leading-9 whitespace-pre-line">

                        <?= nl2br(e($data['requirements'])) ?>

                    </div>

                </div>

            </div>

        </section>

    <?php endif; ?>

    <?php
    $queryPenerima = mysqli_query($conn, "
    SELECT *
    FROM bansos_recipients
    WHERE bansos_id=" . $data['id'] . "
    ORDER BY recipient_name ASC
");

    $penerima = [];

    while ($row = mysqli_fetch_assoc($queryPenerima)) {
        $penerima[] = $row;
    }
    ?>

    <section class="pb-20">

        <div class="max-w-6xl mx-auto px-6">

            <div class="text-center mb-10">

                <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full">

                    <i class="bi bi-people-fill"></i>

                    Transparansi Bantuan Sosial

                </span>

                <h2 class="mt-5 text-4xl font-extrabold">

                    Daftar Penerima Bantuan

                </h2>

                <p class="mt-4 text-gray-600">

                    Data penerima bantuan sosial pada program
                    <strong><?= e($data['program_name']) ?></strong>.

                </p>

            </div>

            <?php if (count($penerima) > 0): ?>

                <div class="overflow-x-auto rounded-3xl shadow border border-emerald-100 bg-white">

                    <table class="w-full">

                        <thead class="bg-emerald-600 text-white">

                            <tr>

                                <th class="px-6 py-4 text-left">No</th>

                                <th class="px-6 py-4 text-left">Nama</th>

                                <th class="px-6 py-4 text-left">RT/RW</th>

                                <th class="px-6 py-4 text-left">Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($penerima as $i => $item): ?>

                                <tr class="border-t hover:bg-emerald-50">

                                    <td class="px-6 py-4">

                                        <?= $i + 1 ?>

                                    </td>

                                    <td class="px-6 py-4 font-medium">

                                        <?= e($item['recipient_name']) ?>

                                    </td>

                                    <td class="px-6 py-4">

                                        <?= sprintf("%03d", $item['rt']) ?>

                                        /

                                        <?= sprintf("%03d", $item['rw']) ?>

                                    </td>

                                    <td class="px-6 py-4">

                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

                                            <?= e($item['status']) ?>

                                        </span>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <div class="bg-white rounded-3xl border border-dashed border-emerald-200 p-16 text-center">

                    <i class="bi bi-people text-6xl text-emerald-500"></i>

                    <h3 class="mt-6 text-2xl font-bold">

                        Belum Ada Data Penerima

                    </h3>

                </div>

            <?php endif; ?>

        </div>

    </section>
</body>