<?php

require_once "../config/app.php";

$page = "idm";

// ===============================
// Ambil Profil Desa
// ===============================

$profileQuery = mysqli_query($conn, "
    SELECT *
    FROM village_profiles
    LIMIT 1
");

$profile = mysqli_fetch_assoc($profileQuery);

// ===============================
// Default Profil
// ===============================

if (!$profile) {

    $profile = [
        'village_name' => 'Nama Desa'
    ];
}

// ===============================
// Ambil IDM Terbaru
// ===============================

$idmQuery = mysqli_query($conn, "
    SELECT *
    FROM idms
    WHERE status='Published'
    ORDER BY year DESC
    LIMIT 1
");

$idm = mysqli_fetch_assoc($idmQuery);

// ===============================
// Default IDM
// ===============================

if (!$idm) {

    $idm = [
        'year' => date('Y'),
        'idm_score' => 0,
        'status_idm' => '-',
        'ike_score' => 0,
        'iks_score' => 0,
        'ikl_score' => 0,
        'description' => '',
    ];
}

// ===============================
// Riwayat IDM
// ===============================

$historyQuery = mysqli_query($conn, "
    SELECT
        year,
        idm_score,
        status_idm
    FROM idms
    WHERE status='Published'
    ORDER BY year DESC
");

// ===============================
// Badge Warna
// ===============================

function badgeColor($status)
{
    return match ($status) {

        'Desa Mandiri'     => 'bg-emerald-100 text-emerald-700',
        'Desa Maju'        => 'bg-green-100 text-green-700',
        'Desa Berkembang'  => 'bg-yellow-100 text-yellow-700',
        'Desa Tertinggal'  => 'bg-orange-100 text-orange-700',

        default            => 'bg-red-100 text-red-700',
    };
}

// ===============================
// Meta SEO
// ===============================

$title = "Indeks Desa Membangun {$profile['village_name']}";
$metaTitle = "IDM | {$profile['village_name']}";
$metaDescription = "Informasi Indeks Desa Membangun (IDM), nilai indeks, status desa, serta riwayat perkembangan Desa {$profile['village_name']}.";

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <?php include "../includes/head.php"; ?>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs/dist/cdn.min.js"></script>

    <!-- Alpine Collapse -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse/dist/cdn.min.js"></script>

</head>

<body class="bg-slate-50 text-slate-800">


    <?php include "../includes/guest/navbar.php"; ?>


    <!-- ========================= -->
    <!-- HERO -->
    <!-- ========================= -->

    <section class="relative overflow-hidden bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 text-white pt-20">


        <!-- Decoration -->

        <div class="absolute inset-0 opacity-20">

            <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-white"></div>

            <div class="absolute -left-20 bottom-0 h-72 w-72 rounded-full bg-white"></div>

        </div>



        <div class="relative max-w-7xl mx-auto px-6 py-24">


            <div class="max-w-4xl">


                <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-5 py-2 text-sm font-semibold backdrop-blur">

                    <i class="bi bi-bar-chart-fill"></i>

                    Profil Desa

                </span>




                <h1 class="mt-6 text-4xl md:text-5xl font-black leading-tight">

                    Indeks Desa Membangun (IDM)

                    <br>

                    <span class="text-teal-100">

                        <?= htmlspecialchars($profile['village_name']); ?>

                    </span>

                </h1>




                <p class="mt-6 max-w-3xl text-lg leading-8 text-teal-100">


                    Informasi perkembangan dan klasifikasi Desa
                    <?= htmlspecialchars($profile['village_name']); ?>
                    berdasarkan indikator Indeks Desa Membangun (IDM)
                    yang mencerminkan kondisi sosial, ekonomi, dan lingkungan desa.


                </p>


            </div>


        </div>


    </section>

    <?php if ($idm): ?>

        <!-- NILAI IDM -->

        <section class="py-16">

            <div class="max-w-7xl mx-auto px-6">

                <div class="bg-white rounded-3xl shadow p-10">

                    <div class="flex flex-col lg:flex-row justify-between gap-10">

                        <div>

                            <p class="text-slate-500">

                                IDM Tahun <?= $idm['year'] ?>

                            </p>

                            <h2 class="text-6xl font-bold text-teal-600 mt-3">

                                <?= number_format($idm['idm_score'], 4) ?>

                            </h2>

                            <span class="inline-block mt-5 px-4 py-2 rounded-full text-sm font-semibold <?= badgeColor($idm['status_idm']) ?>">

                                <?= $idm['status_idm'] ?>

                            </span>

                        </div>

                        <div class="grid grid-cols-3 gap-6 flex-1">

                            <div class="bg-slate-50 rounded-xl p-5">

                                <p class="text-sm text-slate-500">

                                    Sosial

                                </p>

                                <h3 class="text-3xl font-bold mt-2 text-teal-600">

                                    <?= number_format($idm['social_score'], 4) ?>

                                </h3>

                            </div>

                            <div class="bg-slate-50 rounded-xl p-5">

                                <p class="text-sm text-slate-500">

                                    Ekonomi

                                </p>

                                <h3 class="text-3xl font-bold mt-2 text-teal-600">

                                    <?= number_format($idm['economic_score'], 4) ?>

                                </h3>

                            </div>

                            <div class="bg-slate-50 rounded-xl p-5">

                                <p class="text-sm text-slate-500">

                                    Lingkungan

                                </p>

                                <h3 class="text-3xl font-bold mt-2 text-teal-600">

                                    <?= number_format($idm['environmental_score'], 4) ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>



        <!-- DESKRIPSI -->

        <?php if (!empty($idm['description'])): ?>

            <section class="pb-16">

                <div class="max-w-7xl mx-auto px-6">

                    <div class="bg-white rounded-3xl shadow p-10">

                        <h2 class="text-2xl font-bold mb-5">

                            Deskripsi

                        </h2>

                        <div class="leading-8 text-slate-600">

                            <?= nl2br(htmlspecialchars($idm['description'])) ?>

                        </div>

                    </div>

                </div>

            </section>

        <?php endif; ?>



        <!-- RANKING -->

        <section class="pb-16">

            <div class="max-w-7xl mx-auto px-6">

                <div class="grid md:grid-cols-3 gap-6">

                    <div class="bg-white rounded-2xl shadow p-8 text-center">

                        <h3 class="text-slate-500">

                            Kabupaten

                        </h3>

                        <p class="text-5xl font-bold text-teal-600 mt-4">

                            <?= $idm['ranking_regency'] ?: "-" ?>

                        </p>

                    </div>

                    <div class="bg-white rounded-2xl shadow p-8 text-center">

                        <h3 class="text-slate-500">

                            Provinsi

                        </h3>

                        <p class="text-5xl font-bold text-teal-600 mt-4">

                            <?= $idm['ranking_province'] ?: "-" ?>

                        </p>

                    </div>

                    <div class="bg-white rounded-2xl shadow p-8 text-center">

                        <h3 class="text-slate-500">

                            Nasional

                        </h3>

                        <p class="text-5xl font-bold text-teal-600 mt-4">

                            <?= $idm['ranking_national'] ?: "-" ?>

                        </p>

                    </div>

                </div>

            </div>

        </section>



        <!-- KEKUATAN & KELEMAHAN -->

        <section class="pb-16">

            <div class="max-w-7xl mx-auto px-6">

                <div class="grid lg:grid-cols-2 gap-8">

                    <div class="bg-white rounded-2xl shadow p-8">

                        <h2 class="text-2xl font-bold text-green-700 mb-5">

                            Kekuatan

                        </h2>

                        <div class="leading-8 text-slate-600">

                            <?= nl2br(htmlspecialchars($idm['strengths'] ?? "")) ?>

                        </div>

                    </div>

                    <div class="bg-white rounded-2xl shadow p-8">

                        <h2 class="text-2xl font-bold text-red-600 mb-5">

                            Kelemahan

                        </h2>

                        <div class="leading-8 text-slate-600">

                            <?= nl2br(htmlspecialchars($idm['weaknesses'] ?? "")) ?>

                        </div>

                    </div>

                </div>

            </div>

        </section>



        <!-- REKOMENDASI -->

        <?php if (!empty($idm['recommendation'])): ?>

            <section class="pb-16">

                <div class="max-w-7xl mx-auto px-6">

                    <div class="bg-teal-600 rounded-3xl p-10 text-white">

                        <h2 class="text-3xl font-bold">

                            Rekomendasi

                        </h2>

                        <div class="mt-6 leading-8 text-teal-100">

                            <?= nl2br(htmlspecialchars($idm['recommendation'])) ?>

                        </div>

                    </div>

                </div>

            </section>

        <?php endif; ?>



        <!-- RIWAYAT -->

        <section class="pb-20">

            <div class="max-w-7xl mx-auto px-6">

                <div class="bg-white rounded-3xl shadow overflow-hidden">

                    <div class="px-8 py-6 border-b">

                        <h2 class="text-2xl font-bold">

                            Riwayat IDM

                        </h2>

                    </div>

                    <table class="w-full">

                        <thead class="bg-slate-50">

                            <tr>

                                <th class="text-left px-6 py-4">Tahun</th>

                                <th class="text-left px-6 py-4">Nilai</th>

                                <th class="text-left px-6 py-4">Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php while ($row = mysqli_fetch_assoc($historyQuery)): ?>

                                <tr class="border-t">

                                    <td class="px-6 py-4">

                                        <?= $row['year'] ?>

                                    </td>

                                    <td class="px-6 py-4">

                                        <?= number_format($row['idm_score'], 4) ?>

                                    </td>

                                    <td class="px-6 py-4">

                                        <span class="px-3 py-1 rounded-full text-sm <?= badgeColor($row['status_idm']) ?>">

                                            <?= $row['status_idm'] ?>

                                        </span>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

    <?php else: ?>

        <section class="py-20">

            <div class="max-w-4xl mx-auto px-6">

                <div class="bg-white rounded-3xl shadow p-12 text-center">

                    <i class="bi bi-bar-chart text-6xl text-slate-300"></i>

                    <h2 class="text-2xl font-bold mt-5">

                        Data IDM Belum Tersedia

                    </h2>

                    <p class="mt-3 text-slate-500">

                        Data Indeks Desa Membangun belum dipublikasikan.

                    </p>

                </div>

            </div>

        </section>

    <?php endif; ?>

    <?php include "../includes/guest/footer.php"; ?>


</body>

</html>