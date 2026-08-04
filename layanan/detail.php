<?php

require_once "../config/app.php";

$page = "layanan";

// ======================================
// Validasi Slug
// ======================================

if (!isset($_GET['slug']) || empty($_GET['slug'])) {

    header("Location: index.php");
    exit;
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);


// ======================================
// Detail Layanan
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM service_letters
    WHERE
        slug='$slug'
        AND status='Published'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);


// ======================================
// Layanan Lainnya
// ======================================

$related = mysqli_query($conn, "
    SELECT
        id,
        name,
        slug,
        icon,
        color
    FROM service_letters
    WHERE
        slug != '{$slug}'
        AND status='Published'
    ORDER BY
        sort_order ASC,
        name ASC
    LIMIT 4
");


// ======================================
// Meta
// ======================================

$title = htmlspecialchars($data['name']);

$metaTitle = "{$data['name']} | Layanan Desa";

$metaDescription = !empty($data['description'])
    ? substr(strip_tags($data['description']), 0, 160)
    : "Informasi layanan administrasi desa.";


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


            <!-- Breadcrumb -->

            <div class="mb-8 flex items-center gap-2 text-sm text-teal-100">


                <a
                    href="<?= APP_URL ?>beranda.php"
                    class="hover:text-white transition">

                    Beranda

                </a>


                <i class="bi bi-chevron-right text-xs"></i>


                <a
                    href="index.php"
                    class="hover:text-white transition">

                    Layanan Surat

                </a>



            </div>






            <div class="flex flex-col gap-6 md:flex-row md:items-center">



                <!-- Icon -->


                <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-3xl bg-white/20 backdrop-blur">


                    <i class="bi <?= htmlspecialchars($data['icon'] ?? 'bi-file-earmark-text'); ?> text-5xl"></i>


                </div>







                <!-- Content -->


                <div class="max-w-4xl">



                    <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-5 py-2 text-sm font-semibold backdrop-blur">


                        <i class="bi bi-file-earmark-text-fill"></i>


                        Pelayanan Administrasi Desa


                    </span>






                    <h1 class="mt-5 text-4xl md:text-5xl font-black leading-tight">


                        <?= htmlspecialchars($data['name']); ?>


                    </h1>







                    <?php if (!empty($data['description'])): ?>


                        <p class="mt-5 text-lg leading-8 text-teal-100">


                            <?= nl2br(htmlspecialchars($data['description'])); ?>


                        </p>


                    <?php endif; ?>



                </div>




            </div>




        </div>




    </section>


    <!-- INFORMASI -->

    <section class="py-12">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid lg:grid-cols-4 gap-6">

                <div class="bg-white rounded-3xl shadow p-6">

                    <p class="text-sm text-slate-500">Biaya</p>

                    <h3 class="mt-2 text-xl font-bold text-teal-700">

                        <?= htmlspecialchars($data['fee']) ?>

                    </h3>

                </div>

                <div class="bg-white rounded-3xl shadow p-6">

                    <p class="text-sm text-slate-500">Estimasi</p>

                    <h3 class="mt-2 text-xl font-bold">

                        <?= htmlspecialchars($data['processing_time'] ?: '-') ?>

                    </h3>

                </div>

                <div class="bg-white rounded-3xl shadow p-6">

                    <p class="text-sm text-slate-500">Petugas</p>

                    <h3 class="mt-2 text-xl font-bold">

                        <?= htmlspecialchars($data['contact_person'] ?: '-') ?>

                    </h3>

                </div>

                <div class="bg-white rounded-3xl shadow p-6">

                    <p class="text-sm text-slate-500">Telepon</p>

                    <h3 class="mt-2 text-xl font-bold">

                        <?= htmlspecialchars($data['phone'] ?: '-') ?>

                    </h3>

                </div>

            </div>

        </div>

    </section>



    <!-- PERSYARATAN -->

    <?php if (!empty($data['requirements'])) : ?>

        <section class="pb-10">

            <div class="max-w-7xl mx-auto px-6">

                <div class="rounded-3xl bg-white shadow p-8">

                    <h2 class="text-2xl font-bold">

                        Persyaratan

                    </h2>

                    <div class="prose max-w-none mt-6 whitespace-pre-line">

                        <?= htmlspecialchars($data['requirements']) ?>

                    </div>

                </div>

            </div>

        </section>

    <?php endif; ?>



    <!-- PROSEDUR -->

    <?php if (!empty($data['service_procedure'])) : ?>

        <section class="pb-10">

            <div class="max-w-7xl mx-auto px-6">

                <div class="rounded-3xl bg-white shadow p-8">

                    <h2 class="text-2xl font-bold">

                        Prosedur Pelayanan

                    </h2>

                    <div class="prose max-w-none mt-6 whitespace-pre-line">

                        <?= htmlspecialchars($data['service_procedure']) ?>

                    </div>

                </div>

            </div>

        </section>

    <?php endif; ?>



    <!-- AKSI -->

    <section class="pb-16">

        <div class="max-w-7xl mx-auto px-6">

            <div class="rounded-3xl bg-white shadow p-8">

                <h2 class="text-2xl font-bold">

                    Layanan Online

                </h2>

                <div class="mt-8 grid md:grid-cols-2 xl:grid-cols-4 gap-5">

                    <?php if ($data['has_google_form'] == "Yes" && !empty($data['google_form_url'])) : ?>

                        <a
                            href="<?= htmlspecialchars($data['google_form_url']) ?>"
                            target="_blank"
                            class="rounded-2xl bg-emerald-600 p-5 text-center text-white hover:bg-emerald-700 transition">

                            <i class="bi bi-ui-checks-grid text-3xl"></i>

                            <div class="mt-3 font-semibold">

                                Isi Google Form

                            </div>

                        </a>

                    <?php endif; ?>



                    <?php if ($data['has_template'] == "Yes" && !empty($data['template_url'])) : ?>

                        <a
                            href="<?= htmlspecialchars($data['template_url']) ?>"
                            target="_blank"
                            class="rounded-2xl bg-blue-600 p-5 text-center text-white hover:bg-blue-700 transition">

                            <i class="bi bi-download text-3xl"></i>

                            <div class="mt-3 font-semibold">

                                Download Template

                            </div>

                        </a>

                    <?php endif; ?>



                    <?php if ($data['has_tracking'] == "Yes" && !empty($data['tracking_url'])) : ?>

                        <a
                            href="<?= htmlspecialchars($data['tracking_url']) ?>"
                            target="_blank"
                            class="rounded-2xl bg-amber-500 p-5 text-center text-white hover:bg-amber-600 transition">

                            <i class="bi bi-search text-3xl"></i>

                            <div class="mt-3 font-semibold">

                                Tracking Pengajuan

                            </div>

                        </a>

                    <?php endif; ?>



                    <?php if (!empty($data['guide_url'])) : ?>

                        <a
                            href="<?= htmlspecialchars($data['guide_url']) ?>"
                            target="_blank"
                            class="rounded-2xl bg-purple-600 p-5 text-center text-white hover:bg-purple-700 transition">

                            <i class="bi bi-book text-3xl"></i>

                            <div class="mt-3 font-semibold">

                                Panduan Layanan

                            </div>

                        </a>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </section>



    <?php include "../includes/guest/footer.php"; ?>

</body>

</html>