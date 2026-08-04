<?php

require_once "../../config/app.php";

$page = "produk-hukum";


// ======================================
// Profil Desa
// ======================================

$profile = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT *
    FROM village_profiles
    LIMIT 1
"));

if (!$profile) {

    $profile = [
        'village_name' => 'Website Desa'
    ];
}


// ======================================
// Validasi Slug
// ======================================

if (
    !isset($_GET['slug']) ||
    trim($_GET['slug']) == ''
) {

    header("Location: index.php");
    exit;
}

$slug = mysqli_real_escape_string(
    $conn,
    $_GET['slug']
);


// ======================================
// Detail Produk Hukum
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM legal_instruments
    WHERE slug='$slug'
    AND status='Published'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);


// ======================================
// Produk Hukum Lainnya
// ======================================

$related = mysqli_query($conn, "
    SELECT
        title,
        slug,
        category,
        document_year,
        effective_date
    FROM legal_instruments
    WHERE status='Published'
    AND slug != '$slug'
    ORDER BY
        document_year DESC,
        effective_date DESC
    LIMIT 3
");


// ======================================
// Badge Kategori
// ======================================

function badgeCategory($category)
{
    switch ($category) {

        case "Peraturan Desa":
            return "bg-blue-100 text-blue-700";

        case "Peraturan Kepala Desa":
            return "bg-emerald-100 text-emerald-700";

        case "Keputusan Kepala Desa":
            return "bg-amber-100 text-amber-700";

        default:
            return "bg-slate-100 text-slate-700";
    }
}


// ======================================
// Meta
// ======================================

$title = htmlspecialchars($data['title']) . " | " . $profile['village_name'];

$metaTitle = $title;

$metaDescription = !empty($data['description'])
    ? substr(strip_tags($data['description']), 0, 160)
    : "Produk Hukum Desa " . $profile['village_name'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <?php include "../../includes/head.php"; ?>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs/dist/cdn.min.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse/dist/cdn.min.js"></script>

</head>

<body class="bg-slate-50 text-slate-800">

    <?php include "../../includes/guest/navbar.php"; ?>



    <!-- HERO -->

    <section class="bg-gradient-to-r from-teal-700 to-teal-500 text-white">

        <div class="max-w-7xl mx-auto px-6 py-20">

            <p class="text-teal-100">

                <a href="<?= APP_URL ?>beranda.php">Beranda</a>

                /

                <a href="index.php">Produk Hukum</a>

            </p>

            <h1 class="mt-4 text-5xl font-bold">

                <?= htmlspecialchars($data['title']) ?>

            </h1>

            <div class="mt-8 flex flex-wrap gap-3">

                <span class="rounded-full bg-white/20 px-4 py-2">

                    <?= $data['category'] ?>

                </span>

                <?php if ($data['document_year']) : ?>

                    <span class="rounded-full bg-white/20 px-4 py-2">

                        Tahun <?= $data['document_year'] ?>

                    </span>

                <?php endif; ?>

            </div>

        </div>

    </section>



    <!-- CONTENT -->

    <section class="py-16">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid lg:grid-cols-3 gap-8">

                <!-- Informasi -->

                <div class="lg:col-span-2 bg-white rounded-3xl shadow p-8">

                    <h2 class="text-2xl font-bold mb-8">

                        Informasi Produk Hukum

                    </h2>

                    <div class="grid md:grid-cols-2 gap-6">

                        <div>

                            <p class="text-sm text-slate-500">

                                Kategori

                            </p>

                            <p class="mt-1 font-semibold">

                                <?= $data['category'] ?>

                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-slate-500">

                                Nomor Dokumen

                            </p>

                            <p class="mt-1 font-semibold">

                                <?= $data['document_number'] ?: '-' ?>

                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-slate-500">

                                Tahun

                            </p>

                            <p class="mt-1 font-semibold">

                                <?= $data['document_year'] ?: '-' ?>

                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-slate-500">

                                Tanggal Berlaku

                            </p>

                            <p class="mt-1 font-semibold">

                                <?= $data['effective_date']
                                    ? date('d F Y', strtotime($data['effective_date']))
                                    : '-'; ?>

                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-slate-500">

                                Jumlah Download

                            </p>

                            <p class="mt-1 font-semibold">

                                <?= number_format($data['download_count']) ?> kali

                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-slate-500">

                                Ukuran File

                            </p>

                            <p class="mt-1 font-semibold">

                                <?=
                                $data['file_size']
                                    ? number_format($data['file_size'] / 1024 / 1024, 2) . " MB"
                                    : "-";
                                ?>

                            </p>

                        </div>

                    </div>

                    <?php if (!empty($data['description'])) : ?>

                        <div class="mt-10 border-t pt-8">

                            <h3 class="text-xl font-bold mb-4">

                                Deskripsi

                            </h3>

                            <div class="leading-8 text-slate-700 whitespace-pre-line">

                                <?= htmlspecialchars($data['description']) ?>

                            </div>

                        </div>

                    <?php endif; ?>

                </div>



                <!-- Sidebar -->

                <div class="space-y-6">

                    <div class="bg-white rounded-3xl shadow p-8">

                        <h3 class="text-xl font-bold mb-6">

                            Dokumen

                        </h3>

                        <?php if ($data['file']) : ?>

                            <a

                                href="<?= APP_URL ?>uploads/informasi/produk-hukum/<?= $data['file'] ?>"

                                target="_blank"

                                class="flex items-center justify-center gap-2 rounded-xl bg-teal-600 py-3 text-white font-semibold hover:bg-teal-700 transition">

                                <i class="bi bi-download"></i>

                                Unduh Dokumen

                            </a>

                        <?php else : ?>

                            <div class="rounded-xl bg-slate-100 p-4 text-center text-slate-500">

                                Dokumen belum tersedia.

                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="bg-white rounded-3xl shadow p-8">

                        <h3 class="text-xl font-bold mb-4">

                            Ringkasan

                        </h3>

                        <div class="space-y-4 text-sm">

                            <div class="flex justify-between">

                                <span class="text-slate-500">

                                    Kategori

                                </span>

                                <strong><?= $data['category'] ?></strong>

                            </div>

                            <div class="flex justify-between">

                                <span class="text-slate-500">

                                    Tahun

                                </span>

                                <strong><?= $data['document_year'] ?: '-' ?></strong>

                            </div>

                            <div class="flex justify-between">

                                <span class="text-slate-500">

                                    Download

                                </span>

                                <strong><?= number_format($data['download_count']) ?></strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <?php include "../../includes/guest/footer.php"; ?>


</body>

</html>