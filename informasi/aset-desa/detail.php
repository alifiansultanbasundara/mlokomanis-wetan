<?php

require_once "../../config/app.php";

$page = "aset-desa";

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
// Detail Aset
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM village_assets
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
// Aset Lainnya
// ======================================

$related = mysqli_query($conn, "
    SELECT
        id,
        title,
        slug,
        category,
        current_value
    FROM village_assets
    WHERE status='Published'
    AND slug != '$slug'
    ORDER BY
        acquisition_year DESC,
        title ASC
    LIMIT 3
");


// ======================================
// Badge Kondisi
// ======================================

function badgeCondition($status)
{
    switch ($status) {

        case 'Baik':
            return 'bg-green-100 text-green-700';

        case 'Rusak Ringan':
            return 'bg-yellow-100 text-yellow-700';

        default:
            return 'bg-red-100 text-red-700';
    }
}


// ======================================
// Meta
// ======================================

$title = htmlspecialchars($data['title']) . " | " . $profile['village_name'];

$metaTitle = $title;

$metaDescription = !empty($data['description'])
    ? substr(strip_tags($data['description']), 0, 160)
    : "Informasi aset desa " . $profile['village_name'];

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

    <section class="bg-gradient-to-r from-teal-700 to-teal-500 text-white pt-20">

        <div class="max-w-7xl mx-auto px-6 py-20">

            <p class="text-teal-100">

                <a href="<?= APP_URL ?>beranda.php">Beranda</a>

                /

                <a href="index.php">Aset Desa</a>

            </p>

            <h1 class="mt-4 text-5xl font-bold">

                <?= htmlspecialchars($data['title']) ?>

            </h1>

            <?php if (!empty($data['description'])) : ?>

                <p class="mt-5 max-w-3xl text-teal-100">

                    <?= htmlspecialchars($data['description']) ?>

                </p>

            <?php endif; ?>

            <div class="mt-8 flex flex-wrap gap-3">

                <span class="rounded-full bg-white/20 px-4 py-2">

                    <?= $data['category'] ?>

                </span>

                <span class="rounded-full bg-white/20 px-4 py-2">

                    <?= $data['acquisition_year'] ?: '-' ?>

                </span>

            </div>

        </div>

    </section>



    <!-- INFORMASI -->

    <section class="py-16">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid lg:grid-cols-3 gap-8">

                <!-- Detail -->

                <div class="lg:col-span-2 bg-white rounded-3xl shadow p-8">

                    <h2 class="text-2xl font-bold mb-8">

                        Informasi Aset

                    </h2>

                    <div class="grid md:grid-cols-2 gap-6">

                        <div>

                            <p class="text-sm text-slate-500">

                                Kategori

                            </p>

                            <p class="font-semibold mt-1">

                                <?= $data['category'] ?>

                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-slate-500">

                                Kode Aset

                            </p>

                            <p class="font-semibold mt-1">

                                <?= $data['asset_code'] ?: '-' ?>

                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-slate-500">

                                Tahun Perolehan

                            </p>

                            <p class="font-semibold mt-1">

                                <?= $data['acquisition_year'] ?: '-' ?>

                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-slate-500">

                                Lokasi

                            </p>

                            <p class="font-semibold mt-1">

                                <?= $data['location'] ?: '-' ?>

                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-slate-500">

                                Nilai Perolehan

                            </p>

                            <p class="font-semibold mt-1">

                                Rp <?= number_format($data['acquisition_value'], 0, ',', '.') ?>

                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-slate-500">

                                Nilai Saat Ini

                            </p>

                            <p class="font-semibold text-teal-600 mt-1">

                                Rp <?= number_format($data['current_value'], 0, ',', '.') ?>

                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-slate-500">

                                Kondisi

                            </p>

                            <span class="inline-flex mt-2 rounded-full px-3 py-1 text-sm <?= badgeCondition($data['condition_status']) ?>">

                                <?= $data['condition_status'] ?>

                            </span>

                        </div>

                        <div>

                            <p class="text-sm text-slate-500">

                                Status Kepemilikan

                            </p>

                            <p class="font-semibold mt-1">

                                <?= $data['ownership_status'] ?>

                            </p>

                        </div>

                    </div>

                    <?php if (!empty($data['description'])) : ?>

                        <div class="mt-10 border-t pt-8">

                            <h3 class="text-xl font-bold mb-4">

                                Deskripsi

                            </h3>

                            <div class="prose max-w-none text-slate-700 leading-8">

                                <?= nl2br(htmlspecialchars($data['description'])) ?>

                            </div>

                        </div>

                    <?php endif; ?>

                </div>



                <!-- Sidebar -->

                <div class="space-y-6">

                    <div class="bg-white rounded-3xl shadow p-8">

                        <h3 class="font-bold text-xl mb-5">

                            Ringkasan

                        </h3>

                        <div class="space-y-5">

                            <div>

                                <p class="text-sm text-slate-500">

                                    Kategori

                                </p>

                                <p class="font-semibold">

                                    <?= $data['category'] ?>

                                </p>

                            </div>

                            <div>

                                <p class="text-sm text-slate-500">

                                    Tahun

                                </p>

                                <p class="font-semibold">

                                    <?= $data['acquisition_year'] ?: '-' ?>

                                </p>

                            </div>

                            <div>

                                <p class="text-sm text-slate-500">

                                    Kondisi

                                </p>

                                <span class="inline-flex rounded-full px-3 py-1 text-sm <?= badgeCondition($data['condition_status']) ?>">

                                    <?= $data['condition_status'] ?>

                                </span>

                            </div>

                        </div>

                    </div>

                    <?php if (!empty($data['document_file'])) : ?>

                        <div class="bg-white rounded-3xl shadow p-8">

                            <h3 class="font-bold text-xl mb-5">

                                Dokumen

                            </h3>

                            <a
                                href="<?= APP_URL ?>uploads/informasi/aset-desa/<?= $data['document_file'] ?>"
                                target="_blank"
                                class="flex items-center justify-center gap-2 rounded-xl bg-teal-600 px-5 py-3 font-semibold text-white transition hover:bg-teal-700">

                                <i class="bi bi-download"></i>

                                Unduh Dokumen

                            </a>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </section>

    <?php include "../../includes/guest/footer.php"; ?>

</body>

</html>