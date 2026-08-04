<?php

require_once "../../config/app.php";

$page = "pembangunan";

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

$title = "Pembangunan Desa {$village['village_name']}";
$metaTitle = "Pembangunan Desa | {$village['village_name']}";
$metaDescription = "Informasi pembangunan desa, proyek infrastruktur, realisasi pembangunan, dan perkembangan pembangunan di Desa {$village['village_name']}.";


// ======================================
// Statistik
// ======================================

$summary = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(*) AS total_projects,

        COALESCE(SUM(budget),0) AS total_budget,

        SUM(
            CASE
                WHEN status='Berjalan'
                THEN 1
                ELSE 0
            END
        ) AS running_projects,

        SUM(
            CASE
                WHEN status='Selesai'
                THEN 1
                ELSE 0
            END
        ) AS finished_projects

    FROM constructions
"));


// ======================================
// Data
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM constructions
    ORDER BY
        year DESC,
        created_at DESC
");


// ======================================
// Badge Status
// ======================================

function badgeStatus($status)
{
    switch ($status) {

        case "Selesai":
            return "bg-green-100 text-green-700";

        case "Berjalan":
            return "bg-blue-100 text-blue-700";

        case "Perencanaan":
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

    <section class="bg-gradient-to-r from-teal-700 to-teal-500 text-white pt-20">

        <div class="max-w-7xl mx-auto px-6 py-20">

            <h1 class="text-5xl font-bold">

                Pembangunan Desa

            </h1>

            <p class="mt-5 max-w-3xl text-teal-100">

                Informasi program pembangunan,
                infrastruktur,
                serta kegiatan pembangunan desa
                yang sedang maupun telah dilaksanakan.

            </p>

        </div>

    </section>

    <section class="py-16">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid md:grid-cols-4 gap-6">

                <div class="bg-white rounded-3xl shadow p-7">

                    <p class="text-slate-500">

                        Total Proyek

                    </p>

                    <h2 class="text-4xl font-bold text-teal-600 mt-2">

                        <?= number_format($summary['total_projects']) ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-7">

                    <p class="text-slate-500">

                        Berjalan

                    </p>

                    <h2 class="text-4xl font-bold text-blue-600 mt-2">

                        <?= number_format($summary['running_projects']) ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-7">

                    <p class="text-slate-500">

                        Selesai

                    </p>

                    <h2 class="text-4xl font-bold text-green-600 mt-2">

                        <?= number_format($summary['finished_projects']) ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-7">

                    <p class="text-slate-500">

                        Total Anggaran

                    </p>

                    <h2 class="text-xl font-bold text-teal-600 mt-2">

                        Rp <?= number_format($summary['total_budget'], 0, ',', '.') ?>

                    </h2>

                </div>

            </div>

        </div>

    </section>

    <section class="pb-20">

        <div class="max-w-7xl mx-auto px-6">

            <?php if (mysqli_num_rows($query)): ?>

                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">

                    <?php while ($row = mysqli_fetch_assoc($query)): ?>

                        <a href="detail.php?slug=<?= $row['slug'] ?>">
                            <div class="bg-white rounded-3xl shadow overflow-hidden hover:shadow-xl transition">

                                <?php if ($row['thumbnail']): ?>

                                    <img

                                        src="<?= APP_URL ?>uploads/informasi/pembangunan/<?= $row['thumbnail'] ?>"

                                        class="w-full h-56 object-cover">

                                <?php else: ?>

                                    <div class="h-56 bg-slate-200 flex items-center justify-center">

                                        <i class="bi bi-building text-6xl text-slate-400"></i>

                                    </div>

                                <?php endif; ?>

                                <div class="p-6">

                                    <div class="flex justify-between items-center">

                                        <span class="px-3 py-1 rounded-full text-xs bg-teal-100 text-teal-700">

                                            <?= $row['category'] ?>

                                        </span>

                                        <span class="px-3 py-1 rounded-full text-xs <?= badgeStatus($row['status']) ?>">

                                            <?= $row['status'] ?>

                                        </span>

                                    </div>

                                    <h2 class="mt-5 text-xl font-bold">

                                        <?= htmlspecialchars($row['title']) ?>

                                    </h2>

                                    <?php if ($row['description']): ?>

                                        <p class="mt-3 text-slate-600 line-clamp-3">

                                            <?= htmlspecialchars($row['description']) ?>

                                        </p>

                                    <?php endif; ?>

                                    <div class="mt-6 space-y-2 text-sm">

                                        <?php if ($row['location']): ?>

                                            <p>

                                                <i class="bi bi-geo-alt text-teal-600"></i>

                                                <?= $row['location'] ?>

                                            </p>

                                        <?php endif; ?>

                                        <p>

                                            <i class="bi bi-calendar3 text-teal-600"></i>

                                            <?= $row['year'] ?>

                                        </p>

                                        <?php if ($row['volume']): ?>

                                            <p>

                                                <i class="bi bi-rulers text-teal-600"></i>

                                                <?= $row['volume'] ?>

                                            </p>

                                        <?php endif; ?>

                                        <p>

                                            <i class="bi bi-cash-stack text-teal-600"></i>

                                            Rp <?= number_format($row['budget'], 0, ',', '.') ?>

                                        </p>

                                    </div>

                                    <div class="mt-6">

                                        <div class="flex justify-between text-sm mb-2">

                                            <span>Progress</span>

                                            <span><?= $row['progress'] ?>%</span>

                                        </div>

                                        <div class="h-3 rounded-full bg-slate-200 overflow-hidden">

                                            <div

                                                class="h-full bg-teal-600"

                                                style="width:<?= min($row['progress'], 100) ?>%">

                                            </div>

                                        </div>

                                    </div>

                                    <?php if ($row['funding_source']): ?>

                                        <div class="mt-5 rounded-xl bg-slate-100 p-4">

                                            <p class="text-xs text-slate-500">

                                                Sumber Dana

                                            </p>

                                            <p class="font-semibold">

                                                <?= htmlspecialchars($row['funding_source']) ?>

                                            </p>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>
                        </a>

                    <?php endwhile; ?>

                </div>

            <?php else: ?>

                <div class="bg-white rounded-3xl shadow p-16 text-center">

                    <i class="bi bi-building text-6xl text-slate-300"></i>

                    <h2 class="text-2xl font-bold mt-6">

                        Belum Ada Data Pembangunan

                    </h2>

                    <p class="text-slate-500 mt-3">

                        Data pembangunan desa belum dipublikasikan.

                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>

    <?php include "../../includes/guest/footer.php"; ?>
</body>