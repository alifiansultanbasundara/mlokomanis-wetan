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
// Filter
// ======================================

$category = isset($_GET['category'])
    ? mysqli_real_escape_string($conn, $_GET['category'])
    : '';

$where = "status='Published'";

if (!empty($category)) {
    $where .= " AND category='$category'";
}

// ======================================
// Pagination
// ======================================

$limit = 12;

$page = isset($_GET['page'])
    ? max(1, (int)$_GET['page'])
    : 1;

$offset = ($page - 1) * $limit;

// ======================================
// Total Data
// ======================================

$queryTotal = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM galleries
    WHERE $where
");

$total = mysqli_fetch_assoc($queryTotal);

$totalPage = ceil($total['total'] / $limit);

// ======================================
// Kategori
// ======================================

$queryKategori = mysqli_query($conn, "
    SELECT DISTINCT category
    FROM galleries
    WHERE status='Published'
    AND category IS NOT NULL
    AND category!=''
    ORDER BY category ASC
");

$kategori = [];

while ($row = mysqli_fetch_assoc($queryKategori)) {
    $kategori[] = $row['category'];
}

// ======================================
// Data Galeri
// ======================================

$queryGaleri = mysqli_query($conn, "
    SELECT *
    FROM galleries
    WHERE $where
    ORDER BY activity_date DESC,
             created_at DESC
    LIMIT $offset,$limit
");

$galeri = [];

while ($row = mysqli_fetch_assoc($queryGaleri)) {
    $galeri[] = $row;
}

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

<section class="relative py-24 bg-gradient-to-b from-emerald-50 via-cyan-50 to-white">

    <div class="max-w-5xl mx-auto px-6 text-center">

        <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-600 px-4 py-2 rounded-full text-sm font-medium">

            <i class="bi bi-images"></i>

            Dokumentasi Desa

        </span>

        <h1 class="mt-6 text-5xl font-extrabold text-gray-900">

            Galeri

            <span class="text-emerald-500">

                Desa

            </span>

        </h1>

        <p class="mt-6 text-lg text-gray-600 leading-8">

            Dokumentasi berbagai kegiatan, pembangunan,
            pelayanan masyarakat, serta aktivitas
            <?= e($profil['nama_desa']) ?>.

        </p>

    </div>

</section>

<!-- ====================================== -->
<!-- Grid Galeri -->
<!-- ====================================== -->

<section class="pb-20 bg-gradient-to-b from-white to-emerald-50">

    <div class="max-w-7xl mx-auto px-6">

        <?php if (count($galeri) > 0): ?>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">

                <?php foreach ($galeri as $item):

                    $image = !empty($item['image'])
                        ? "../../galeri/uploads/" . $item['image']
                        : "../assets/img/no-image.webp";

                ?>

                    <article
                        class="group bg-white rounded-3xl overflow-hidden border border-emerald-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition duration-300">

                        <!-- Thumbnail -->

                        <div class="relative overflow-hidden">

                            <img
                                src="<?= $image ?>"
                                alt="<?= e($item['title']) ?>"
                                class="w-full h-72 object-cover group-hover:scale-110 transition duration-700">

                            <!-- Overlay -->

                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end">

                                <div class="p-6 text-white w-full">

                                    <span class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs">

                                        <i class="bi bi-images"></i>

                                        <?= e($item['category']) ?>

                                    </span>

                                    <a
                                        href="detail.php?slug=<?= urlencode($item['slug']) ?>"
                                        class="block mt-4 text-lg font-bold hover:text-emerald-300 transition">

                                        <?= e($item['title']) ?>

                                    </a>

                                </div>

                            </div>

                        </div>

                        <!-- Content -->

                        <div class="p-6">

                            <h3 class="font-bold text-xl text-gray-900 line-clamp-2">

                                <?= e($item['title']) ?>

                            </h3>

                            <div class="mt-4 space-y-2 text-sm text-gray-500">

                                <?php if (!empty($item['activity_date'])): ?>

                                    <div>

                                        <i class="bi bi-calendar-event text-emerald-600"></i>

                                        <?= date('d F Y', strtotime($item['activity_date'])) ?>

                                    </div>

                                <?php endif; ?>

                                <?php if (!empty($item['location'])): ?>

                                    <div>

                                        <i class="bi bi-geo-alt-fill text-emerald-600"></i>

                                        <?= e($item['location']) ?>

                                    </div>

                                <?php endif; ?>

                            </div>

                            <a
                                href="detail.php?slug=<?= urlencode($item['slug']) ?>"
                                class="inline-flex items-center gap-2 mt-6 text-emerald-600 hover:text-emerald-700 font-semibold">

                                Lihat Dokumentasi

                                <i class="bi bi-arrow-right"></i>

                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="bg-white rounded-3xl border border-dashed border-emerald-200 p-20 text-center">

                <i class="bi bi-images text-6xl text-emerald-500"></i>

                <h3 class="mt-6 text-3xl font-bold text-gray-900">

                    Belum Ada Dokumentasi

                </h3>

                <p class="mt-4 text-gray-500">

                    Galeri kegiatan desa belum tersedia.

                </p>

            </div>

        <?php endif; ?>

    </div>

</section>

<!-- ====================================== -->
<!-- Pagination -->
<!-- ====================================== -->

<?php if ($totalPage > 1): ?>

    <section class="pb-24 bg-emerald-50">

        <div class="max-w-7xl mx-auto px-6">

            <div class="flex justify-center">

                <div class="inline-flex overflow-hidden rounded-2xl border border-emerald-100 shadow">

                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>

                        <a
                            href="?page=<?= $i ?><?= !empty($category) ? '&category=' . urlencode($category) : '' ?>"
                            class="<?= $page == $i
                                        ? 'bg-emerald-600 text-white'
                                        : 'bg-white text-gray-700 hover:bg-emerald-100' ?>
                               px-5 py-3 font-medium transition">

                            <?= $i ?>

                        </a>

                    <?php endfor; ?>

                </div>

            </div>

        </div>

    </section>

<?php endif; ?>
</body>