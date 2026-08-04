<?php

// include "../../auth/auth.php";
include "../../config/database.php";

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location: index.php");
    exit;
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);

$query = mysqli_query($conn, "
    SELECT *
    FROM galleries
    WHERE slug='$slug'
    AND status='Published'
    LIMIT 1
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: index.php");
    exit;
}

// ======================================
// Galeri Lainnya
// ======================================

$queryLainnya = mysqli_query($conn, "
    SELECT
        id,
        title,
        slug,
        image,
        activity_date,
        category
    FROM galleries
    WHERE status='Published'
    AND slug != '$slug'
    ORDER BY activity_date DESC,
             created_at DESC
    LIMIT 4
");

$galeriLain = [];

while ($row = mysqli_fetch_assoc($queryLainnya)) {
    $galeriLain[] = $row;
}

function e($text)
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

$image = !empty($data['image'])
    ? "../../galeri/uploads/" . $data['image']
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
<section class="bg-gray-50 border-b">

    <div class="max-w-7xl mx-auto px-6 py-4 text-sm text-gray-500">

        <a href="../index.php" class="hover:text-emerald-600">

            Beranda

        </a>

        <span class="mx-2">/</span>

        <a href="index.php" class="hover:text-emerald-600">

            Galeri

        </a>

        <span class="mx-2">/</span>

        <span class="text-gray-700">

            <?= e($data['title']) ?>

        </span>

    </div>

</section>

<section class="-mt-8">

    <div class="max-w-6xl mx-auto px-6">

        <img
            src="<?= $image ?>"
            alt="<?= e($data['title']) ?>"
            class="rounded-3xl shadow-2xl w-full h-[650px] object-cover">

    </div>

</section>

<section class="py-16">

    <div class="max-w-6xl mx-auto px-6">

        <div class="grid md:grid-cols-3 gap-6">

            <div class="bg-white border border-emerald-100 rounded-3xl p-8 shadow-sm">

                <div class="text-emerald-600 text-3xl">

                    <i class="bi bi-calendar-event"></i>

                </div>

                <h3 class="mt-5 font-bold text-xl">

                    Tanggal Kegiatan

                </h3>

                <p class="mt-3 text-gray-600">

                    <?= !empty($data['activity_date']) ? date('d F Y', strtotime($data['activity_date'])) : '-' ?>

                </p>

            </div>

            <div class="bg-white border border-emerald-100 rounded-3xl p-8 shadow-sm">

                <div class="text-emerald-600 text-3xl">

                    <i class="bi bi-geo-alt-fill"></i>

                </div>

                <h3 class="mt-5 font-bold text-xl">

                    Lokasi

                </h3>

                <p class="mt-3 text-gray-600">

                    <?= e($data['location']) ?: '-' ?>

                </p>

            </div>

            <div class="bg-white border border-emerald-100 rounded-3xl p-8 shadow-sm">

                <div class="text-emerald-600 text-3xl">

                    <i class="bi bi-tag-fill"></i>

                </div>

                <h3 class="mt-5 font-bold text-xl">

                    Kategori

                </h3>

                <p class="mt-3 text-gray-600">

                    <?= e($data['category']) ?>

                </p>

            </div>

        </div>

    </div>

</section>

<?php if (!empty($data['description'])): ?>

    <section class="pb-20">

        <div class="max-w-4xl mx-auto px-6">

            <div class="bg-white rounded-3xl shadow-sm border border-emerald-100 p-10">

                <h2 class="text-3xl font-bold text-gray-900">

                    Deskripsi Kegiatan

                </h2>

                <div class="mt-8 text-lg text-gray-700 leading-9 whitespace-pre-line">

                    <?= e($data['description']) ?>

                </div>

            </div>

        </div>

    </section>

<?php endif; ?>

<section class="pb-20">

    <div class="max-w-4xl mx-auto px-6">

        <div class="bg-emerald-50 rounded-3xl p-8 text-center">

            <h3 class="text-2xl font-bold">

                Bagikan Dokumentasi

            </h3>

            <div class="flex justify-center flex-wrap gap-4 mt-8">

                <a
                    target="_blank"
                    href="https://wa.me/?text=<?= urlencode($data['title']) ?>"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-full">

                    WhatsApp

                </a>

                <a
                    target="_blank"
                    href="https://www.facebook.com/sharer/sharer.php"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full">

                    Facebook

                </a>

            </div>

        </div>

    </div>

</section>

<section class="pb-24 bg-gradient-to-b from-white to-emerald-50">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-12">

            <h2 class="text-4xl font-extrabold text-gray-900">

                Dokumentasi Lainnya

            </h2>

            <p class="mt-4 text-gray-600">

                Jelajahi dokumentasi kegiatan Desa Mlokomanis Wetan lainnya.

            </p>

        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">

            <?php foreach ($galeriLain as $item):

                $thumb = !empty($item['image'])
                    ? "../uploads/galeri/" . $item['image']
                    : "../assets/img/no-image.webp";

            ?>

                <article class="group bg-white rounded-3xl overflow-hidden shadow-sm border border-emerald-100 hover:-translate-y-2 hover:shadow-xl transition">

                    <div class="overflow-hidden">

                        <img
                            src="<?= $thumb ?>"
                            alt="<?= e($item['title']) ?>"
                            class="w-full h-56 object-cover group-hover:scale-110 transition duration-700">

                    </div>

                    <div class="p-6">

                        <span class="inline-block bg-emerald-100 text-emerald-700 text-xs px-3 py-1 rounded-full">

                            <?= e($item['category']) ?>

                        </span>

                        <h3 class="mt-4 text-lg font-bold text-gray-900 line-clamp-2">

                            <?= e($item['title']) ?>

                        </h3>

                        <p class="mt-3 text-sm text-gray-500">

                            <i class="bi bi-calendar-event"></i>

                            <?= !empty($item['activity_date']) ? date('d M Y', strtotime($item['activity_date'])) : '-' ?>

                        </p>

                        <a
                            href="detail.php?slug=<?= urlencode($item['slug']) ?>"
                            class="inline-flex items-center gap-2 mt-6 text-emerald-600 hover:text-emerald-700 font-semibold">

                            Lihat Detail

                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    </div>

</section>
</body>