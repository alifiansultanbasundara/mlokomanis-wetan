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
// Statistik
// ======================================

$summary = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT

        COUNT(*) AS total,

        SUM(category='Peraturan Desa') AS perdes,

        SUM(category='Peraturan Kepala Desa') AS perkades,

        SUM(category='Keputusan Kepala Desa') AS keputusan

    FROM legal_instruments

    WHERE status='Published'
"));

if (!$summary) {

    $summary = [
        'total'      => 0,
        'perdes'     => 0,
        'perkades'   => 0,
        'keputusan'  => 0
    ];
}


// ======================================
// Data Produk Hukum
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM legal_instruments
    WHERE status='Published'
    ORDER BY
        document_year DESC,
        effective_date DESC,
        title ASC
");


// ======================================
// Meta
// ======================================

$title = "Produk Hukum | " . $profile['village_name'];

$metaTitle = $title;

$metaDescription = "Kumpulan produk hukum, peraturan desa, peraturan kepala desa, dan keputusan kepala desa di " . $profile['village_name'];

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

            <h1 class="text-5xl font-bold">

                Produk Hukum Desa

            </h1>

            <p class="mt-5 text-teal-100 max-w-3xl">

                Kumpulan Peraturan Desa, Peraturan Kepala Desa,
                Keputusan Kepala Desa dan dokumen hukum lainnya.

            </p>

        </div>

    </section>



    <!-- STATISTIK -->

    <section class="py-16">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Total Dokumen

                    </p>

                    <h2 class="text-4xl font-bold text-teal-600 mt-2">

                        <?= number_format($summary['total']) ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Peraturan Desa

                    </p>

                    <h2 class="text-4xl font-bold text-teal-600 mt-2">

                        <?= number_format($summary['perdes']) ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Peraturan Kepala Desa

                    </p>

                    <h2 class="text-4xl font-bold text-teal-600 mt-2">

                        <?= number_format($summary['perkades']) ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Keputusan

                    </p>

                    <h2 class="text-4xl font-bold text-teal-600 mt-2">

                        <?= number_format($summary['keputusan']) ?>

                    </h2>

                </div>

            </div>

        </div>

    </section>



    <!-- TABLE -->

    <section class="pb-20">

        <div class="max-w-7xl mx-auto px-6">

            <div class="bg-white rounded-3xl shadow overflow-hidden">

                <div class="px-8 py-6 border-b">

                    <h2 class="text-2xl font-bold">

                        Daftar Produk Hukum

                    </h2>

                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead class="bg-slate-100">

                            <tr>

                                <th class="px-6 py-4 text-left">

                                    No

                                </th>

                                <th class="px-6 py-4 text-left">

                                    Judul

                                </th>

                                <th class="px-6 py-4 text-center">

                                    Kategori

                                </th>

                                <th class="px-6 py-4 text-center">

                                    Nomor

                                </th>

                                <th class="px-6 py-4 text-center">

                                    Tahun

                                </th>

                                <th class="px-6 py-4 text-center">

                                    Download

                                </th>

                                <th class="px-6 py-4 text-center">

                                    Aksi

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            $no = 1;

                            while ($row = mysqli_fetch_assoc($query)) :

                            ?>

                                <tr class="border-t hover:bg-slate-50">

                                    <td class="px-6 py-5">

                                        <?= $no++ ?>

                                    </td>

                                    <td class="px-6 py-5">

                                        <div>

                                            <h3 class="font-semibold">

                                                <?= htmlspecialchars($row['title']) ?>

                                            </h3>

                                            <?php if ($row['description']) : ?>

                                                <p class="text-sm text-slate-500 mt-1">

                                                    <?= htmlspecialchars(mb_strimwidth($row['description'], 0, 80, "...")) ?>

                                                </p>

                                            <?php endif; ?>

                                        </div>

                                    </td>

                                    <td class="px-6 py-5 text-center">

                                        <span class="rounded-full bg-teal-100 px-3 py-1 text-sm text-teal-700">

                                            <?= $row['category'] ?>

                                        </span>

                                    </td>

                                    <td class="px-6 py-5 text-center">

                                        <?= $row['document_number'] ?: '-' ?>

                                    </td>

                                    <td class="px-6 py-5 text-center">

                                        <?= $row['document_year'] ?: '-' ?>

                                    </td>

                                    <td class="px-6 py-5 text-center">

                                        <?= number_format($row['download_count']) ?>

                                    </td>

                                    <td class="px-6 py-5">

                                        <div class="flex justify-center gap-2">

                                            <a
                                                href="detail.php?slug=<?= $row['slug'] ?>"
                                                class="rounded-lg bg-teal-600 px-4 py-2 text-white hover:bg-teal-700">

                                                Detail

                                            </a>

                                            <?php if ($row['file']) : ?>

                                                <a
                                                    href="<?= APP_URL ?>uploads/informasi/produk-hukum/<?= $row['file'] ?>"
                                                    target="_blank"
                                                    class="rounded-lg border border-slate-300 px-4 py-2 hover:bg-slate-100">

                                                    <i class="bi bi-download"></i>

                                                </a>

                                            <?php endif; ?>

                                        </div>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </section>

    <?php include "../../includes/guest/footer.php"; ?>


</body>

</html>