<?php

include "../auth/auth.php";
include "../config/database.php";

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location: keadaan-wilayah.php");
    exit;
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);

$query = mysqli_query($conn, "
    SELECT *
    FROM wilayah
    WHERE slug='$slug'
    AND status='Published'
    LIMIT 1
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: keadaan-wilayah.php");
    exit;
}

function e($text)
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

$image = !empty($data['image'])
    ? "../uploads/wilayah/" . $data['image']
    : "../assets/img/no-image.webp";

$file = !empty($data['file'])
    ? "../uploads/wilayah/" . $data['file']
    : "";

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

<section class="relative py-24 bg-gradient-to-b from-emerald-50 via-cyan-50 to-white">

    <div class="max-w-6xl mx-auto px-6 text-center">

        <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-600 px-4 py-2 rounded-full text-sm font-medium">

            <i class="bi bi-map-fill"></i>

            <?= e($data['type']) ?>

        </span>

        <h1 class="mt-6 text-5xl font-extrabold text-gray-900">

            <?= e($data['title']) ?>

        </h1>

        <p class="mt-6 text-gray-600 max-w-3xl mx-auto leading-8">

            Informasi mengenai
            <?= e($data['title']) ?>
            Desa Mlokomanis Wetan.

        </p>

    </div>

</section>


<section class="-mt-10">

    <div class="max-w-6xl mx-auto px-6">

        <img
            src="<?= $image ?>"
            class="rounded-3xl shadow-2xl w-full h-[550px] object-cover">

    </div>

</section>

<section class="py-20">

    <div class="max-w-4xl mx-auto px-6">

        <div class="prose prose-lg max-w-none text-gray-700 leading-9">

            <?= nl2br(e($data['description'])) ?>

        </div>

    </div>

</section>

<section class="pb-20">

    <div class="max-w-6xl mx-auto px-6">

        <div class="grid md:grid-cols-3 gap-6">

            <div class="bg-white rounded-3xl border border-emerald-100 p-8 shadow-sm">

                <div class="text-emerald-600 text-3xl">
                    <i class="bi bi-tag-fill"></i>
                </div>

                <h4 class="mt-4 font-bold text-xl">
                    Jenis Data
                </h4>

                <p class="mt-2 text-gray-600">

                    <?= e($data['type']) ?>

                </p>

            </div>

            <div class="bg-white rounded-3xl border border-emerald-100 p-8 shadow-sm">

                <div class="text-emerald-600 text-3xl">
                    <i class="bi bi-calendar-event"></i>
                </div>

                <h4 class="mt-4 font-bold text-xl">
                    Dipublikasikan
                </h4>

                <p class="mt-2 text-gray-600">

                    <?= date('d F Y', strtotime($data['created_at'])) ?>

                </p>

            </div>

            <div class="bg-white rounded-3xl border border-emerald-100 p-8 shadow-sm">

                <div class="text-emerald-600 text-3xl">
                    <i class="bi bi-file-earmark-pdf"></i>
                </div>

                <h4 class="mt-4 font-bold text-xl">
                    Dokumen
                </h4>

                <p class="mt-2 text-gray-600">

                    <?= !empty($file) ? "Tersedia" : "Tidak tersedia"; ?>

                </p>

            </div>

        </div>

    </div>

</section>

<?php if (!empty($file)): ?>

    <section class="pb-20">

        <div class="max-w-6xl mx-auto px-6">

            <div class="bg-white rounded-3xl border border-emerald-100 shadow-sm overflow-hidden">

                <div class="p-8 border-b">

                    <h3 class="text-2xl font-bold">

                        Dokumen Wilayah

                    </h3>

                </div>

                <iframe
                    src="<?= $file ?>"
                    class="w-full h-[700px]">
                </iframe>

            </div>

        </div>

    </section>

<?php endif; ?>

<?php if (!empty($file)): ?>

    <section class="pb-24">

        <div class="max-w-6xl mx-auto px-6 text-center">

            <a
                href="<?= $file ?>"
                download
                class="inline-flex items-center gap-3 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 font-semibold transition">

                <i class="bi bi-download"></i>

                Download Dokumen

            </a>

        </div>

    </section>

<?php endif; ?>

<nav class="pt-10">

    <div class="max-w-6xl mx-auto px-6 text-sm text-gray-500">

        <a href="../index.php" class="hover:text-emerald-600">

            Beranda

        </a>

        <span class="mx-2">/</span>

        <a href="keadaan-wilayah.php" class="hover:text-emerald-600">

            Keadaan Wilayah

        </a>

        <span class="mx-2">/</span>

        <span class="text-gray-800">

            <?= e($data['title']) ?>

        </span>

    </div>

</nav>