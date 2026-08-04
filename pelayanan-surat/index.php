<?php

include "../auth/auth.php";
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
// Filter
// ======================================

$metode = $_GET['metode'] ?? '';

$where = "WHERE is_active = 1";

if (!empty($metode)) {

    $metode = mysqli_real_escape_string($conn, $metode);

    $where .= " AND metode = '$metode'";
}

// ======================================
// Pagination
// ======================================

$limit = 9;

$page = isset($_GET['page'])
    ? max(1, (int)$_GET['page'])
    : 1;

$offset = ($page - 1) * $limit;

// ======================================
// Total Data
// ======================================

$queryTotal = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM jenis_surat
    $where
");

$total = mysqli_fetch_assoc($queryTotal);

$totalData = (int) ($total['total'] ?? 0);

$totalPage = ceil($totalData / $limit);

// ======================================
// Daftar Pelayanan
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM jenis_surat
    $where
    ORDER BY urutan ASC, nama ASC
    LIMIT $offset, $limit
");

$surat = [];

while ($row = mysqli_fetch_assoc($query)) {
    $surat[] = $row;
}

// ======================================
// Statistik
// ======================================

$queryStat = mysqli_query($conn, "
    SELECT
        COUNT(*) AS totalSurat,
        ROUND(AVG(estimasi_hari),0) AS estimasi,

        SUM(metode='Download Template') AS download,
        SUM(metode='Google Form') AS googleform,
        SUM(metode='Offline') AS offline,
        SUM(metode='Sistem') AS sistem

    FROM jenis_surat

    WHERE is_active = 1
");

$stat = mysqli_fetch_assoc($queryStat);

$totalSurat = (int) ($stat['totalSurat'] ?? 0);
$estimasi = (int) ($stat['estimasi'] ?? 0);

$totalDownload = (int) ($stat['download'] ?? 0);
$totalGoogle = (int) ($stat['googleform'] ?? 0);
$totalOffline = (int) ($stat['offline'] ?? 0);
$totalSistem = (int) ($stat['sistem'] ?? 0);

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

<body>
    <section class="relative py-24 bg-gradient-to-b from-emerald-50 via-cyan-50 to-white">

        <div class="max-w-5xl mx-auto px-6 text-center">

            <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full font-medium">

                <i class="bi bi-envelope-paper-fill"></i>

                Pelayanan Administrasi Desa

            </span>

            <h1 class="mt-6 text-5xl font-extrabold text-gray-900">

                Pelayanan

                <span class="text-emerald-600">

                    Surat Online

                </span>

            </h1>

            <p class="mt-6 text-lg text-gray-600 leading-8">

                Ajukan berbagai layanan administrasi desa secara online.
                Pilih jenis surat sesuai kebutuhan, lengkapi persyaratan,
                kemudian kirim permohonan melalui formulir yang telah disediakan.

            </p>

        </div>

    </section>

    <section class="py-16 bg-white">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Total Surat -->
                <div class="bg-emerald-50 rounded-3xl p-8 text-center">
                    <i class="bi bi-file-earmark-text text-5xl text-emerald-600"></i>

                    <h3 class="mt-5 text-4xl font-extrabold">
                        <?= $totalSurat ?>
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Jenis Surat
                    </p>
                </div>

                <!-- Download -->
                <div class="bg-blue-50 rounded-3xl p-8 text-center">
                    <i class="bi bi-download text-5xl text-blue-600"></i>

                    <h3 class="mt-5 text-4xl font-extrabold">
                        <?= $totalDownload ?>
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Download Template
                    </p>
                </div>

                <!-- Google Form -->
                <div class="bg-violet-50 rounded-3xl p-8 text-center">
                    <i class="bi bi-google text-5xl text-violet-600"></i>

                    <h3 class="mt-5 text-4xl font-extrabold">
                        <?= $totalGoogle ?>
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Google Form
                    </p>
                </div>

                <!-- Estimasi -->
                <div class="bg-yellow-50 rounded-3xl p-8 text-center">
                    <i class="bi bi-clock-history text-5xl text-yellow-600"></i>

                    <h3 class="mt-5 text-4xl font-extrabold">
                        <?= $estimasi ?> Hari
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Estimasi Rata-rata
                    </p>
                </div>

            </div>

        </div>

    </section>


    <section class="py-20 bg-gradient-to-b from-white to-emerald-50">

        <div class="max-w-7xl mx-auto px-6">

            <!-- Heading -->
            <div class="text-center mb-14">

                <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full font-medium">
                    <i class="bi bi-diagram-3-fill"></i>
                    Panduan Pelayanan
                </span>

                <h2 class="mt-5 text-4xl font-extrabold text-gray-900">
                    Alur Pelayanan Surat
                </h2>

                <p class="mt-4 text-gray-600 max-w-3xl mx-auto leading-8">
                    Ikuti tahapan berikut agar proses pelayanan administrasi desa
                    berjalan dengan mudah, cepat, dan sesuai ketentuan yang berlaku.
                </p>

            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">

                <!-- Step 1 -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-emerald-100 text-center hover:-translate-y-2 hover:shadow-xl transition">

                    <div class="w-20 h-20 mx-auto rounded-2xl bg-emerald-100 flex items-center justify-center">

                        <i class="bi bi-file-earmark-text text-4xl text-emerald-600"></i>

                    </div>

                    <span class="inline-block mt-6 text-sm font-semibold text-emerald-600">
                        Langkah 1
                    </span>

                    <h3 class="mt-3 text-xl font-bold">
                        Pilih Jenis Surat
                    </h3>

                    <p class="mt-3 text-gray-600 leading-7">
                        Pilih layanan surat yang sesuai dengan kebutuhan administrasi Anda.
                    </p>

                </div>

                <!-- Step 2 -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-emerald-100 text-center hover:-translate-y-2 hover:shadow-xl transition">

                    <div class="w-20 h-20 mx-auto rounded-2xl bg-blue-100 flex items-center justify-center">

                        <i class="bi bi-folder-check text-4xl text-blue-600"></i>

                    </div>

                    <span class="inline-block mt-6 text-sm font-semibold text-blue-600">
                        Langkah 2
                    </span>

                    <h3 class="mt-3 text-xl font-bold">
                        Lengkapi Persyaratan
                    </h3>

                    <p class="mt-3 text-gray-600 leading-7">
                        Siapkan seluruh dokumen yang dipersyaratkan sebelum mengajukan surat.
                    </p>

                </div>

                <!-- Step 3 -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-emerald-100 text-center hover:-translate-y-2 hover:shadow-xl transition">

                    <div class="w-20 h-20 mx-auto rounded-2xl bg-violet-100 flex items-center justify-center">

                        <i class="bi bi-gear text-4xl text-violet-600"></i>

                    </div>

                    <span class="inline-block mt-6 text-sm font-semibold text-violet-600">
                        Langkah 3
                    </span>

                    <h3 class="mt-3 text-xl font-bold">
                        Ikuti Metode Pelayanan
                    </h3>

                    <p class="mt-3 text-gray-600 leading-7">
                        Lakukan proses sesuai metode yang tersedia, seperti download template,
                        Google Form, sistem online, atau datang langsung ke kantor desa.
                    </p>

                </div>

                <!-- Step 4 -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-emerald-100 text-center hover:-translate-y-2 hover:shadow-xl transition">

                    <div class="w-20 h-20 mx-auto rounded-2xl bg-yellow-100 flex items-center justify-center">

                        <i class="bi bi-patch-check text-4xl text-yellow-600"></i>

                    </div>

                    <span class="inline-block mt-6 text-sm font-semibold text-yellow-600">
                        Langkah 4
                    </span>

                    <h3 class="mt-3 text-xl font-bold">
                        Surat Diproses
                    </h3>

                    <p class="mt-3 text-gray-600 leading-7">
                        Petugas desa akan memverifikasi data dan memproses permohonan hingga surat selesai.
                    </p>

                </div>

            </div>

        </div>

    </section>

    <section class="py-20 bg-white">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">

                <?php foreach ($surat as $item): ?>

                    <?php

                    $btnText = "Detail";
                    $btnLink = "detail.php?slug=" . e($item['slug']);
                    $btnTarget = "_self";
                    $badgeColor = "bg-emerald-100 text-emerald-700";

                    switch ($item['metode']) {

                        case 'Download Template':
                            $btnText = 'Download';
                            $btnLink = !empty($item['template_file'])
                                ? "../uploads/template-surat/" . e($item['template_file'])
                                : "detail.php?slug=" . e($item['slug']);
                            $btnTarget = "_blank";
                            $badgeColor = "bg-blue-100 text-blue-700";
                            break;

                        case 'Google Form':
                            $btnText = 'Isi Form';
                            $btnLink = !empty($item['google_form'])
                                ? e($item['google_form'])
                                : "detail.php?slug=" . e($item['slug']);
                            $btnTarget = "_blank";
                            $badgeColor = "bg-violet-100 text-violet-700";
                            break;

                        case 'Offline':
                            $btnText = 'Lihat Detail';
                            $btnLink = "detail.php?slug=" . e($item['slug']);
                            $badgeColor = "bg-orange-100 text-orange-700";
                            break;

                        case 'Sistem':
                            $btnText = 'Ajukan Online';
                            $btnLink = "detail.php?slug=" . e($item['slug']);
                            $badgeColor = "bg-cyan-100 text-cyan-700";
                            break;
                    }

                    ?>

                    <div class="bg-white rounded-3xl border border-emerald-100 shadow-sm hover:shadow-2xl transition hover:-translate-y-2 p-8 flex flex-col">

                        <div class="flex items-center justify-between">

                            <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl">

                                <i class="<?= e($item['icon']) ?>"></i>

                            </div>

                            <span class="px-3 py-1 rounded-full text-sm font-medium <?= $badgeColor ?>">

                                <?= e($item['metode']) ?>

                            </span>

                        </div>

                        <h3 class="mt-6 text-2xl font-bold text-gray-900">

                            <?= e($item['nama']) ?>

                        </h3>

                        <p class="mt-4 text-gray-600 line-clamp-3 flex-grow">

                            <?= e($item['deskripsi']) ?>

                        </p>

                        <div class="mt-6 flex flex-wrap gap-2">

                            <span class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">

                                <i class="bi bi-clock-history"></i>

                                <?= (int)$item['estimasi_hari'] ?> Hari

                            </span>

                        </div>

                        <div class="mt-8 flex gap-3">

                            <a
                                href="detail.php?slug=<?= e($item['slug']) ?>"
                                class="flex-1 border border-emerald-600 text-emerald-600 py-3 rounded-xl text-center hover:bg-emerald-50 transition">

                                Detail

                            </a>

                            <a
                                href="<?= $btnLink ?>"
                                target="<?= $btnTarget ?>"
                                class="flex-1 bg-emerald-600 text-white py-3 rounded-xl text-center hover:bg-emerald-700 transition">

                                <?= $btnText ?>

                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </section>

    <section class="pb-24">

        <div class="max-w-6xl mx-auto px-6">

            <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 rounded-3xl p-10 text-white">

                <h2 class="text-3xl font-bold">

                    Jam Pelayanan Kantor Desa

                </h2>

                <div class="grid md:grid-cols-2 gap-10 mt-8">

                    <div>

                        <p>Senin - Kamis</p>

                        <p class="font-bold">

                            08.00 - 15.00 WIB

                        </p>

                    </div>

                    <div>

                        <p>Jumat</p>

                        <p class="font-bold">

                            08.00 - 11.00 WIB

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>
</body>