<?php

require_once "../../config/app.php";

$page = "pengelolaan-keuangan";

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

$title = "Pengelolaan Keuangan Desa {$village['village_name']}";
$metaTitle = "Pengelolaan Keuangan | {$village['village_name']}";
$metaDescription = "Informasi APBDes, realisasi anggaran, laporan keuangan, dan transparansi pengelolaan keuangan Desa {$village['village_name']}.";


// ======================================
// Statistik
// ======================================

$summary = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(*) AS total_documents,
        COALESCE(SUM(total_budget),0) AS total_budget,
        COALESCE(SUM(realization),0) AS total_realization
    FROM financial_managements
    WHERE status='Published'
"));


// ======================================
// Data
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM financial_managements
    WHERE status='Published'
    ORDER BY
        fiscal_year DESC,
        created_at DESC
");
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

                Pengelolaan Keuangan Desa

            </h1>

            <p class="mt-5 max-w-3xl text-teal-100">

                Informasi APBDes, pendapatan, belanja, pembiayaan,
                serta laporan keuangan desa yang dipublikasikan sebagai
                bentuk transparansi kepada masyarakat.

            </p>

        </div>

    </section>



    <!-- STATISTIK -->

    <section class="py-16">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid md:grid-cols-3 gap-6">

                <div class="bg-white rounded-3xl shadow p-8">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-slate-500">

                                Total Dokumen

                            </p>

                            <h2 class="text-4xl font-bold mt-2 text-teal-600">

                                <?= number_format($summary['total_documents']) ?>

                            </h2>

                        </div>

                        <i class="bi bi-folder2-open text-5xl text-teal-200"></i>

                    </div>

                </div>

                <div class="bg-white rounded-3xl shadow p-8">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-slate-500">

                                Total Anggaran

                            </p>

                            <h2 class="text-2xl font-bold mt-2 text-teal-600">

                                Rp <?= number_format($summary['total_budget'], 0, ',', '.') ?>

                            </h2>

                        </div>

                        <i class="bi bi-cash-stack text-5xl text-teal-200"></i>

                    </div>

                </div>

                <div class="bg-white rounded-3xl shadow p-8">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-slate-500">

                                Total Realisasi

                            </p>

                            <h2 class="text-2xl font-bold mt-2 text-teal-600">

                                Rp <?= number_format($summary['total_realization'], 0, ',', '.') ?>

                            </h2>

                        </div>

                        <i class="bi bi-graph-up-arrow text-5xl text-teal-200"></i>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- DAFTAR -->

    <section class="pb-20">

        <div class="max-w-7xl mx-auto px-6">

            <div class="space-y-6">

                <?php if (mysqli_num_rows($query) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($query)): ?>

                        <?php

                        $percent = 0;

                        if ($row['total_budget'] > 0) {

                            $percent = ($row['realization'] / $row['total_budget']) * 100;
                        }

                        ?>

                        <div class="bg-white rounded-3xl shadow p-8">

                            <div class="flex flex-col lg:flex-row justify-between gap-8">

                                <div class="flex-1">

                                    <div class="flex flex-wrap gap-2">

                                        <span class="bg-teal-100 text-teal-700 px-3 py-1 rounded-full text-sm">

                                            <?= $row['category'] ?>

                                        </span>

                                        <span class="bg-slate-100 px-3 py-1 rounded-full text-sm">

                                            <?= $row['fiscal_year'] ?>

                                        </span>

                                    </div>

                                    <h2 class="text-2xl font-bold mt-4">

                                        <?= htmlspecialchars($row['title']) ?>

                                    </h2>

                                    <?php if ($row['description']): ?>

                                        <p class="mt-4 text-slate-600">

                                            <?= htmlspecialchars($row['description']) ?>

                                        </p>

                                    <?php endif; ?>

                                    <div class="mt-6 grid md:grid-cols-2 gap-6 text-sm">

                                        <div>

                                            <p class="text-slate-500">

                                                Total Anggaran

                                            </p>

                                            <h4 class="font-bold text-lg">

                                                Rp <?= number_format($row['total_budget'], 0, ',', '.') ?>

                                            </h4>

                                        </div>

                                        <div>

                                            <p class="text-slate-500">

                                                Realisasi

                                            </p>

                                            <h4 class="font-bold text-lg">

                                                Rp <?= number_format($row['realization'], 0, ',', '.') ?>

                                            </h4>

                                        </div>

                                    </div>

                                    <div class="mt-6">

                                        <div class="flex justify-between text-sm mb-2">

                                            <span>Realisasi</span>

                                            <span><?= number_format($percent, 1) ?>%</span>

                                        </div>

                                        <div class="h-3 rounded-full bg-slate-200 overflow-hidden">

                                            <div
                                                class="h-full bg-teal-600 rounded-full"
                                                style="width:<?= min($percent, 100) ?>%">

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="lg:w-56 flex flex-col justify-center gap-3">

                                    <?php if ($row['funding_source']): ?>

                                        <div class="text-center rounded-xl bg-slate-100 p-4">

                                            <p class="text-sm text-slate-500">

                                                Sumber Dana

                                            </p>

                                            <p class="font-semibold mt-1">

                                                <?= $row['funding_source'] ?>

                                            </p>

                                        </div>

                                    <?php endif; ?>

                                    <?php if ($row['file']): ?>

                                        <a
                                            href="<?= APP_URL ?>uploads/informasi/pengelolaan-keuangan/<?= $row['file'] ?>"
                                            target="_blank"
                                            class="rounded-xl bg-teal-600 py-3 text-center font-semibold text-white hover:bg-teal-700 transition">

                                            <i class="bi bi-download"></i>

                                            Download Dokumen

                                        </a>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <div class="bg-white rounded-3xl shadow p-16 text-center">

                        <i class="bi bi-folder-x text-6xl text-slate-300"></i>

                        <h2 class="text-2xl font-bold mt-6">

                            Belum Ada Data

                        </h2>

                        <p class="text-slate-500 mt-3">

                            Data pengelolaan keuangan belum dipublikasikan.

                        </p>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </section>

    <?php include "../../includes/guest/footer.php"; ?>
</body>

</html>