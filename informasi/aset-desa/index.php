<?php

require_once "../../config/app.php";

$page = "aset-desa";

// ======================================
// Profil Desa
// ======================================

$profileQuery = mysqli_query($conn, "
    SELECT
        village_name
    FROM village_profiles
    LIMIT 1
");

$village = mysqli_fetch_assoc($profileQuery);

if (!$village) {

    $village = [
        'village_name' => 'Website Desa'
    ];
}


// ======================================
// Meta
// ======================================

$title = "Aset Desa {$village['village_name']}";
$metaTitle = "Aset Desa | {$village['village_name']}";
$metaDescription = "Informasi aset milik Desa {$village['village_name']}, meliputi tanah, bangunan, kendaraan, peralatan, dan aset lainnya sebagai bentuk transparansi pengelolaan aset desa.";


// ======================================
// Statistik
// ======================================

$summary = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT

        COUNT(*) AS total_assets,

        COALESCE(
            SUM(acquisition_value),
            0
        ) AS acquisition_value,

        COALESCE(
            SUM(current_value),
            0
        ) AS current_value,

        COALESCE(
            SUM(
                CASE
                    WHEN condition_status='Baik'
                    THEN 1
                    ELSE 0
                END
            ),
            0
        ) AS good_assets

    FROM village_assets

    WHERE status='Published'
"));


// ======================================
// Data Aset
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM village_assets
    WHERE status='Published'
    ORDER BY
        acquisition_year DESC,
        title ASC
");


// ======================================
// Badge Kondisi
// ======================================

function badgeCondition($status)
{
    switch ($status) {

        case "Baik":
            return "bg-green-100 text-green-700";

        case "Rusak Ringan":
            return "bg-yellow-100 text-yellow-700";

        default:
            return "bg-red-100 text-red-700";
    }
}

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

<body class="bg-slate-50">

    <?php include "../../includes/guest/navbar.php"; ?>



    <!-- HERO -->

    <section class="bg-gradient-to-r from-teal-700 to-teal-500 text-white pt-20">

        <div class="max-w-7xl mx-auto px-6 py-20">

            <h1 class="text-5xl font-bold">

                Aset Desa

            </h1>

            <p class="mt-5 max-w-3xl text-teal-100">

                Informasi inventaris aset milik desa yang dikelola
                sebagai bentuk transparansi kepada masyarakat.

            </p>

        </div>

    </section>



    <!-- STATISTIK -->

    <section class="py-16">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Total Aset

                    </p>

                    <h2 class="text-4xl font-bold text-teal-600 mt-2">

                        <?= number_format($summary['total_assets']) ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Nilai Perolehan

                    </p>

                    <h2 class="text-xl font-bold text-teal-600 mt-2">

                        Rp <?= number_format($summary['acquisition_value'], 0, ',', '.') ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Nilai Saat Ini

                    </p>

                    <h2 class="text-xl font-bold text-teal-600 mt-2">

                        Rp <?= number_format($summary['current_value'], 0, ',', '.') ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Kondisi Baik

                    </p>

                    <h2 class="text-4xl font-bold text-green-600 mt-2">

                        <?= number_format($summary['good_assets']) ?>

                    </h2>

                </div>

            </div>

        </div>

    </section>



    <!-- DAFTAR ASET -->

    <section class="pb-20">

        <div class="max-w-7xl mx-auto px-6">

            <?php if (mysqli_num_rows($query)): ?>

                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">

                    <?php while ($row = mysqli_fetch_assoc($query)): ?>

                        <div class="bg-white rounded-3xl shadow hover:shadow-xl transition">

                            <div class="p-8">

                                <div class="flex justify-between items-start">

                                    <span class="px-3 py-1 rounded-full bg-teal-100 text-teal-700 text-sm">

                                        <?= $row['category'] ?>

                                    </span>

                                    <span class="px-3 py-1 rounded-full text-xs <?= badgeCondition($row['condition_status']) ?>">

                                        <?= $row['condition_status'] ?>

                                    </span>

                                </div>

                                <h2 class="text-2xl font-bold mt-5">

                                    <?= htmlspecialchars($row['title']) ?>

                                </h2>

                                <?php if ($row['description']): ?>

                                    <p class="mt-4 text-slate-600 line-clamp-3">

                                        <?= htmlspecialchars($row['description']) ?>

                                    </p>

                                <?php endif; ?>

                                <div class="mt-6 space-y-3 text-sm">

                                    <?php if ($row['asset_code']): ?>

                                        <div class="flex justify-between">

                                            <span class="text-slate-500">

                                                Kode Aset

                                            </span>

                                            <strong><?= $row['asset_code'] ?></strong>

                                        </div>

                                    <?php endif; ?>

                                    <div class="flex justify-between">

                                        <span class="text-slate-500">

                                            Tahun

                                        </span>

                                        <strong><?= $row['acquisition_year'] ?: '-' ?></strong>

                                    </div>

                                    <div class="flex justify-between">

                                        <span class="text-slate-500">

                                            Lokasi

                                        </span>

                                        <strong><?= $row['location'] ?: '-' ?></strong>

                                    </div>

                                    <div class="flex justify-between">

                                        <span class="text-slate-500">

                                            Kepemilikan

                                        </span>

                                        <strong><?= $row['ownership_status'] ?></strong>

                                    </div>

                                </div>

                                <div class="mt-6 border-t pt-5 space-y-3">

                                    <div>

                                        <p class="text-xs text-slate-500">

                                            Nilai Perolehan

                                        </p>

                                        <p class="font-bold text-lg">

                                            Rp <?= number_format($row['acquisition_value'], 0, ',', '.') ?>

                                        </p>

                                    </div>

                                    <div>

                                        <p class="text-xs text-slate-500">

                                            Nilai Saat Ini

                                        </p>

                                        <p class="font-bold text-teal-600 text-lg">

                                            Rp <?= number_format($row['current_value'], 0, ',', '.') ?>

                                        </p>

                                    </div>

                                </div>

                                <div class="mt-8 flex gap-3">

                                    <a

                                        href="detail.php?slug=<?= $row['slug'] ?>"

                                        class="flex-1 rounded-xl bg-teal-600 py-3 text-center text-white font-semibold hover:bg-teal-700 transition">

                                        Detail

                                    </a>

                                    <?php if ($row['document_file']): ?>

                                        <a

                                            href="<?= APP_URL ?>uploads/informasi/aset-desa/<?= $row['document_file'] ?>"

                                            target="_blank"

                                            class="rounded-xl border border-slate-300 px-4 py-3 hover:bg-slate-100">

                                            <i class="bi bi-download"></i>

                                        </a>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else: ?>

                <div class="bg-white rounded-3xl shadow p-20 text-center">

                    <i class="bi bi-building text-6xl text-slate-300"></i>

                    <h2 class="text-3xl font-bold mt-6">

                        Belum Ada Data Aset

                    </h2>

                    <p class="text-slate-500 mt-3">

                        Data aset desa belum dipublikasikan.

                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>

    <?php include "../../includes/guest/footer.php"; ?>

</body>

</html>