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
// Validasi Slug
// ======================================

if (empty($_GET['slug'])) {
    header("Location:index.php");
    exit;
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);

// ======================================
// Detail Surat
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM jenis_surat
    WHERE slug='$slug'
    AND is_active=1
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {
    header("Location:index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// ======================================
// Konfigurasi Tombol Berdasarkan Metode
// ======================================

$actionText   = "";
$actionLink   = "";
$actionTarget = "_self";
$badgeColor   = "bg-gray-100 text-gray-700";

switch ($data['metode']) {

    case 'Download Template':

        $actionText   = "Download Template";
        $actionLink   = !empty($data['template_file'])
            ? "../uploads/template-surat/" . $data['template_file']
            : "#";
        $actionTarget = "_blank";
        $badgeColor   = "bg-blue-100 text-blue-700";

        break;

    case 'Google Form':

        $actionText   = "Isi Google Form";
        $actionLink   = !empty($data['google_form'])
            ? $data['google_form']
            : "#";
        $actionTarget = "_blank";
        $badgeColor   = "bg-violet-100 text-violet-700";

        break;

    case 'Offline':

        $actionText   = "Datang ke Kantor Desa";
        $actionLink   = "#";
        $badgeColor   = "bg-orange-100 text-orange-700";

        break;

    case 'Sistem':

        $actionText   = "Ajukan Online";
        $actionLink   = "#";
        $badgeColor   = "bg-cyan-100 text-cyan-700";

        break;
}

// ======================================
// Surat Lainnya
// ======================================

$queryLain = mysqli_query($conn, "
    SELECT *
    FROM jenis_surat
    WHERE is_active=1
    AND id <> {$data['id']}
    ORDER BY urutan ASC,nama ASC
    LIMIT 3
");

$lainnya = [];

while ($row = mysqli_fetch_assoc($queryLain)) {
    $lainnya[] = $row;
}

// ======================================
// Statistik Tambahan
// ======================================

$biaya = (float) ($data['biaya'] ?? 0);

$isGratis = $biaya <= 0;

$estimasi = (int) ($data['estimasi_hari'] ?? 1);

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
    <section class="relative py-24 bg-gradient-to-b from-emerald-50 via-cyan-50 to-white overflow-hidden">

        <div class="max-w-5xl mx-auto px-6 text-center">

            <!-- Badge -->
            <div class="flex flex-wrap justify-center gap-3">

                <span class="inline-flex items-center gap-2 <?= $badgeColor ?> px-4 py-2 rounded-full font-medium">

                    <i class="<?= e($data['icon']) ?>"></i>

                    <?= e($data['metode']) ?>

                </span>

                <span class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full font-medium">

                    <i class="bi bi-clock-history"></i>

                    <?= $estimasi ?> Hari

                </span>

                <span class="inline-flex items-center gap-2 bg-cyan-100 text-cyan-700 px-4 py-2 rounded-full font-medium">

                    <i class="bi bi-cash-stack"></i>

                    <?= $isGratis ? 'Gratis' : 'Rp ' . number_format($biaya, 0, ',', '.') ?>

                </span>

            </div>

            <!-- Judul -->
            <h1 class="mt-8 text-5xl font-extrabold text-gray-900 leading-tight">

                <?= e($data['nama']) ?>

            </h1>

            <!-- Deskripsi -->
            <p class="mt-6 text-lg text-gray-600 leading-8 max-w-3xl mx-auto">

                <?= e($data['deskripsi']) ?>

            </p>

            <!-- Info -->
            <div class="mt-10 flex flex-wrap justify-center gap-8 text-gray-600">

                <div class="flex items-center gap-3">

                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">

                        <i class="bi bi-calendar-check text-emerald-600"></i>

                    </div>

                    <div class="text-left">

                        <p class="text-sm text-gray-500">
                            Estimasi
                        </p>

                        <p class="font-semibold">

                            <?= $estimasi ?> Hari

                        </p>

                    </div>

                </div>

                <div class="flex items-center gap-3">

                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">

                        <i class="bi bi-clock text-blue-600"></i>

                    </div>

                    <div class="text-left">

                        <p class="text-sm text-gray-500">
                            Jam Pelayanan
                        </p>

                        <p class="font-semibold">

                            <?= e($data['jam_pelayanan'] ?: '-') ?>

                        </p>

                    </div>

                </div>

                <div class="flex items-center gap-3">

                    <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center">

                        <i class="bi bi-diagram-3"></i>

                    </div>

                    <div class="text-left">

                        <p class="text-sm text-gray-500">
                            Metode
                        </p>

                        <p class="font-semibold">

                            <?= e($data['metode']) ?>

                        </p>

                    </div>

                </div>

            </div>

            <!-- CTA -->
            <div class="mt-12 flex flex-wrap justify-center gap-4">

                <a
                    href="<?= e($actionLink) ?>"
                    target="<?= $actionTarget ?>"
                    class="inline-flex items-center gap-3 bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-2xl font-semibold transition">

                    <?php
                    switch ($data['metode']) {

                        case 'Download Template':
                            echo '<i class="bi bi-download"></i>';
                            break;

                        case 'Google Form':
                            echo '<i class="bi bi-google"></i>';
                            break;

                        case 'Offline':
                            echo '<i class="bi bi-geo-alt"></i>';
                            break;

                        default:
                            echo '<i class="bi bi-send"></i>';
                    }
                    ?>

                    <?= $actionText ?>

                </a>

                <?php if (!empty($data['contoh_file'])): ?>

                    <a
                        href="../uploads/contoh-surat/<?= e($data['contoh_file']) ?>"
                        target="_blank"
                        class="inline-flex items-center gap-3 border border-emerald-600 text-emerald-600 hover:bg-emerald-50 px-8 py-4 rounded-2xl font-semibold transition">

                        <i class="bi bi-file-earmark-text"></i>

                        Contoh Surat

                    </a>

                <?php endif; ?>

            </div>

        </div>

    </section>

    <section class="py-16 bg-white">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Estimasi -->
                <div class="bg-emerald-50 rounded-3xl p-8 text-center">

                    <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-100 flex items-center justify-center">

                        <i class="bi bi-clock-history text-4xl text-emerald-600"></i>

                    </div>

                    <h3 class="mt-5 text-3xl font-extrabold">

                        <?= $estimasi ?> Hari

                    </h3>

                    <p class="mt-2 text-gray-600">

                        Estimasi Penyelesaian

                    </p>

                </div>

                <!-- Biaya -->
                <div class="bg-blue-50 rounded-3xl p-8 text-center">

                    <div class="w-16 h-16 mx-auto rounded-2xl bg-blue-100 flex items-center justify-center">

                        <i class="bi bi-cash-stack text-4xl text-blue-600"></i>

                    </div>

                    <h3 class="mt-5 text-2xl font-extrabold">

                        <?= $isGratis ? 'Gratis' : 'Rp ' . number_format($biaya, 0, ',', '.') ?>

                    </h3>

                    <p class="mt-2 text-gray-600">

                        Biaya Pelayanan

                    </p>

                </div>

                <!-- Metode -->
                <div class="bg-violet-50 rounded-3xl p-8 text-center">

                    <div class="w-16 h-16 mx-auto rounded-2xl bg-violet-100 flex items-center justify-center">

                        <?php

                        switch ($data['metode']) {

                            case 'Download Template':
                                echo '<i class="bi bi-download text-4xl text-violet-600"></i>';
                                break;

                            case 'Google Form':
                                echo '<i class="bi bi-google text-4xl text-violet-600"></i>';
                                break;

                            case 'Offline':
                                echo '<i class="bi bi-building text-4xl text-violet-600"></i>';
                                break;

                            default:
                                echo '<i class="bi bi-laptop text-4xl text-violet-600"></i>';
                        }

                        ?>

                    </div>

                    <h3 class="mt-5 text-xl font-extrabold">

                        <?= e($data['metode']) ?>

                    </h3>

                    <p class="mt-2 text-gray-600">

                        Metode Pelayanan

                    </p>

                </div>

                <!-- Jam -->
                <div class="bg-yellow-50 rounded-3xl p-8 text-center">

                    <div class="w-16 h-16 mx-auto rounded-2xl bg-yellow-100 flex items-center justify-center">

                        <i class="bi bi-calendar-week text-4xl text-yellow-600"></i>

                    </div>

                    <h3 class="mt-5 text-lg font-extrabold">

                        <?= e($data['jam_pelayanan'] ?: '-') ?>

                    </h3>

                    <p class="mt-2 text-gray-600">

                        Jam Pelayanan

                    </p>

                </div>

            </div>

        </div>

    </section>

    <section class="pb-16 bg-white">

        <div class="max-w-5xl mx-auto px-6">

            <div class="flex items-center gap-3 mb-8">

                <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center">

                    <i class="bi bi-info-circle text-2xl text-emerald-600"></i>

                </div>

                <h2 class="text-3xl font-bold text-gray-900">

                    Tentang Pelayanan

                </h2>

            </div>


            <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100">

                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">

                    <?= nl2br(e($data['deskripsi'])) ?>

                </div>

            </div>

        </div>

    </section>



    <section class="py-20 bg-gradient-to-b from-white to-emerald-50">

        <div class="max-w-5xl mx-auto px-6">


            <div class="flex items-center gap-3 mb-10">

                <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center">

                    <i class="bi bi-clipboard-check text-2xl text-emerald-600"></i>

                </div>

                <h2 class="text-3xl font-bold text-gray-900">

                    Persyaratan

                </h2>

            </div>



            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">


                <?php if (!empty($data['persyaratan'])): ?>

                    <div class="prose prose-lg max-w-none text-gray-700">

                        <?= nl2br(e($data['persyaratan'])) ?>

                    </div>


                <?php else: ?>

                    <div class="text-center py-8">

                        <i class="bi bi-file-earmark-x text-5xl text-gray-300"></i>

                        <p class="mt-4 text-gray-500">

                            Belum ada persyaratan yang ditambahkan.

                        </p>

                    </div>

                <?php endif; ?>


            </div>


        </div>

    </section>




    <section class="py-20 bg-white">


        <div class="max-w-4xl mx-auto px-6">


            <div class="relative overflow-hidden bg-gradient-to-r from-emerald-600 to-emerald-500 rounded-[2rem] p-10 md:p-14 text-center text-white shadow-xl">


                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full"></div>

                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-white/10 rounded-full"></div>



                <div class="relative">


                    <div class="w-20 h-20 mx-auto rounded-3xl bg-white/20 flex items-center justify-center">

                        <i class="bi bi-file-earmark-arrow-up text-5xl"></i>

                    </div>


                    <h2 class="mt-8 text-4xl font-bold">

                        Ajukan Surat Sekarang

                    </h2>


                    <p class="mt-4 text-emerald-100 text-lg">

                        Pastikan seluruh dokumen persyaratan telah disiapkan sebelum melakukan pengajuan.

                    </p>



                    <a

                        href="<?= e($data['google_form']) ?>"

                        target="_blank"

                        class="inline-flex items-center mt-8 bg-white text-emerald-600 px-8 py-4 rounded-full font-bold shadow-lg hover:bg-gray-100 transition">


                        <i class="bi bi-box-arrow-up-right mr-2"></i>

                        Ajukan Surat


                    </a>


                </div>


            </div>


        </div>


    </section>




    <section class="py-20 bg-emerald-50">


        <div class="max-w-6xl mx-auto px-6">


            <h2 class="text-4xl font-bold text-center text-gray-900 mb-14">

                Alur Pengajuan

            </h2>



            <div class="grid md:grid-cols-4 gap-8">


                <div class="bg-white rounded-3xl p-8 text-center shadow-sm border border-gray-100">


                    <div class="w-20 h-20 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto text-3xl font-bold">

                        1

                    </div>


                    <i class="bi bi-folder2-open text-3xl text-emerald-600 mt-6"></i>


                    <p class="mt-4 font-semibold text-gray-800">

                        Siapkan Berkas

                    </p>


                    <p class="text-sm text-gray-500 mt-2">

                        Lengkapi seluruh dokumen persyaratan.

                    </p>


                </div>



                <div class="bg-white rounded-3xl p-8 text-center shadow-sm border border-gray-100">


                    <div class="w-20 h-20 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto text-3xl font-bold">

                        2

                    </div>


                    <i class="bi bi-pencil-square text-3xl text-emerald-600 mt-6"></i>


                    <p class="mt-4 font-semibold text-gray-800">

                        Isi Formulir

                    </p>


                    <p class="text-sm text-gray-500 mt-2">

                        Masukkan data pengajuan dengan benar.

                    </p>


                </div>



                <div class="bg-white rounded-3xl p-8 text-center shadow-sm border border-gray-100">


                    <div class="w-20 h-20 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto text-3xl font-bold">

                        3

                    </div>


                    <i class="bi bi-shield-check text-3xl text-emerald-600 mt-6"></i>


                    <p class="mt-4 font-semibold text-gray-800">

                        Verifikasi

                    </p>


                    <p class="text-sm text-gray-500 mt-2">

                        Data akan diperiksa oleh petugas.

                    </p>


                </div>




                <div class="bg-white rounded-3xl p-8 text-center shadow-sm border border-gray-100">


                    <div class="w-20 h-20 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto text-3xl font-bold">

                        4

                    </div>


                    <i class="bi bi-file-earmark-check text-3xl text-emerald-600 mt-6"></i>


                    <p class="mt-4 font-semibold text-gray-800">

                        Surat Selesai

                    </p>


                    <p class="text-sm text-gray-500 mt-2">

                        Surat siap diterima pemohon.

                    </p>


                </div>



            </div>


        </div>


    </section>

    <section class="py-24 bg-white">

        <div class="max-w-7xl mx-auto px-6">

            <h2 class="text-4xl font-bold text-center mb-14">

                Pelayanan Lainnya

            </h2>

            <div class="grid md:grid-cols-3 gap-8">

                <?php foreach ($lainnya as $item): ?>

                    <div class="bg-white rounded-3xl border border-emerald-100 shadow-sm hover:shadow-xl transition p-8">

                        <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl text-emerald-600">

                            <i class="<?= e($item['icon']) ?>"></i>

                        </div>

                        <h3 class="mt-6 text-2xl font-bold">

                            <?= e($item['nama']) ?>

                        </h3>

                        <p class="mt-4 text-gray-600 line-clamp-3">

                            <?= e($item['deskripsi']) ?>

                        </p>

                        <a

                            href="detail.php?slug=<?= e($item['slug']) ?>"

                            class="inline-flex mt-8 text-emerald-600 font-semibold">

                            Lihat Detail

                            <i class="bi bi-arrow-right ms-2"></i>

                        </a>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </section>
</body>