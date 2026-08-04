<?php

require_once "../config/app.php";

$page = "layanan";


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
        COUNT(*) AS total_services,

        SUM(
            CASE
                WHEN has_google_form = 'Yes'
                THEN 1
                ELSE 0
            END
        ) AS online_services,

        SUM(
            CASE
                WHEN has_template = 'Yes'
                THEN 1
                ELSE 0
            END
        ) AS template_services,

        SUM(
            CASE
                WHEN has_tracking = 'Yes'
                THEN 1
                ELSE 0
            END
        ) AS tracking_services

    FROM service_letters

    WHERE status='Published'
"));

if (!$summary) {

    $summary = [
        'total_services'    => 0,
        'online_services'   => 0,
        'download_services' => 0,
        'offline_services'  => 0
    ];
}


// ======================================
// Data Layanan
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM service_letters
    WHERE status='Published'
    ORDER BY
        sort_order ASC,
        name ASC
");

$total = mysqli_num_rows($query);


// ======================================
// Meta
// ======================================

$title = "Layanan Administrasi Desa | " . $profile['village_name'];

$metaTitle = $title;

$metaDescription = "Daftar layanan administrasi, surat menyurat, dan pelayanan publik Desa " . $profile['village_name'];

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

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs/dist/cdn.min.js"></script>
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


                    <i class="bi bi-envelope-paper-fill"></i>


                    Pelayanan Publik


                </span>






                <h1 class="mt-6 text-4xl md:text-5xl font-black leading-tight">


                    Layanan Surat Desa


                    <br>


                    <span class="text-teal-100">


                        <?= htmlspecialchars($profile['village_name'] ?? ''); ?>


                    </span>


                </h1>






                <p class="mt-6 max-w-3xl text-lg leading-8 text-teal-100">


                    Akses berbagai layanan administrasi Desa
                    <?= htmlspecialchars($profile['village_name'] ?? ''); ?>.
                    Masyarakat dapat mengetahui jenis surat,
                    persyaratan, prosedur pengajuan, serta informasi
                    pelayanan secara mudah dan transparan.


                </p>



            </div>



        </div>



    </section>



    <!-- INFO -->

    <section class="py-12">

        <div class="max-w-7xl mx-auto px-6">

            <div class="rounded-3xl bg-white shadow p-8">

                <div class="grid md:grid-cols-3 gap-8 text-center">

                    <div>

                        <i class="bi bi-file-earmark-text text-4xl text-teal-600"></i>

                        <h3 class="mt-3 text-3xl font-bold">

                            <?= number_format($total) ?>

                        </h3>

                        <p class="text-slate-500">

                            Jenis Layanan

                        </p>

                    </div>

                    <div>

                        <i class="bi bi-clock-history text-4xl text-teal-600"></i>

                        <h3 class="mt-3 text-3xl font-bold">

                            Cepat

                        </h3>

                        <p class="text-slate-500">

                            Proses Pelayanan

                        </p>

                    </div>

                    <div>

                        <i class="bi bi-shield-check text-4xl text-teal-600"></i>

                        <h3 class="mt-3 text-3xl font-bold">

                            Transparan

                        </h3>

                        <p class="text-slate-500">

                            Informasi Lengkap

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- DAFTAR LAYANAN -->

    <section class="pb-20">

        <div class="max-w-7xl mx-auto px-6">

            <?php if ($total > 0) : ?>

                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">

                    <?php while ($row = mysqli_fetch_assoc($query)) : ?>

                        <div class="rounded-3xl bg-white shadow hover:shadow-xl transition p-8">

                            <div class="flex items-center justify-between">

                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-100 text-teal-700">

                                    <i class="bi <?= htmlspecialchars($row['icon']) ?> text-2xl"></i>

                                </div>

                                <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-700">

                                    <?= htmlspecialchars($row['fee']) ?>

                                </span>

                            </div>

                            <h2 class="mt-6 text-xl font-bold text-slate-800">

                                <?= htmlspecialchars($row['name']) ?>

                            </h2>

                            <?php if (!empty($row['description'])) : ?>

                                <p class="mt-3 line-clamp-3 text-slate-600">

                                    <?= htmlspecialchars($row['description']) ?>

                                </p>

                            <?php endif; ?>

                            <div class="mt-6 space-y-2 text-sm text-slate-600">

                                <div class="flex items-center gap-2">

                                    <i class="bi bi-clock"></i>

                                    <span><?= htmlspecialchars($row['processing_time'] ?: '-') ?></span>

                                </div>

                                <div class="flex items-center gap-2">

                                    <i class="bi bi-person"></i>

                                    <span><?= htmlspecialchars($row['contact_person'] ?: '-') ?></span>

                                </div>

                            </div>

                            <a
                                href="detail.php?slug=<?= urlencode($row['slug']) ?>"
                                class="mt-8 flex items-center justify-center gap-2 rounded-xl bg-teal-600 px-5 py-3 font-semibold text-white transition hover:bg-teal-700">

                                <i class="bi bi-arrow-right-circle"></i>

                                Lihat Detail

                            </a>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else : ?>

                <div class="rounded-3xl bg-white p-20 text-center shadow">

                    <i class="bi bi-inbox text-6xl text-slate-300"></i>

                    <h2 class="mt-6 text-3xl font-bold">

                        Belum Ada Layanan

                    </h2>

                    <p class="mt-3 text-slate-500">

                        Data layanan surat belum tersedia.

                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>



    <!-- PANDUAN -->

    <section class="bg-white border-t">

        <div class="max-w-7xl mx-auto px-6 py-16">

            <h2 class="text-3xl font-bold text-center">

                Alur Pelayanan Surat

            </h2>

            <div class="mt-12 grid md:grid-cols-4 gap-6">

                <div class="text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-teal-600 text-white font-bold">
                        1
                    </div>

                    <h3 class="mt-4 font-semibold">
                        Pilih Layanan
                    </h3>

                </div>

                <div class="text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-teal-600 text-white font-bold">
                        2
                    </div>

                    <h3 class="mt-4 font-semibold">
                        Lengkapi Persyaratan
                    </h3>

                </div>

                <div class="text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-teal-600 text-white font-bold">
                        3
                    </div>

                    <h3 class="mt-4 font-semibold">
                        Ajukan Permohonan
                    </h3>

                </div>

                <div class="text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-teal-600 text-white font-bold">
                        4
                    </div>

                    <h3 class="mt-4 font-semibold">
                        Ambil Surat
                    </h3>

                </div>

            </div>

        </div>

    </section>



    <?php include "../includes/guest/footer.php"; ?>

</body>

</html>