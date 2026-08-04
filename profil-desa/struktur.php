<?php

require_once "../config/app.php";

$page = "struktur";


// ======================================================
// Profil Desa
// ======================================================

$profileQuery = mysqli_query($conn, "
    SELECT *
    FROM village_profiles
    LIMIT 1
");

$village = mysqli_fetch_assoc($profileQuery);


// ======================================================
// Struktur Organisasi Desa
// ======================================================

$officialsQuery = mysqli_query($conn, "

    SELECT *

    FROM village_officials

    WHERE status='Aktif'

    ORDER BY

        CASE 

            WHEN category='Kepala Desa' THEN 1

            WHEN category='Sekretariat Desa' THEN 2

            WHEN category='Kepala Urusan' THEN 3

            WHEN category='Kepala Seksi' THEN 4

            ELSE 5

        END,

        level ASC,

        sort_order ASC,

        id ASC

");


$officials = [];

while ($row = mysqli_fetch_assoc($officialsQuery)) {

    $officials[] = $row;
}



// ======================================================
// Default Data
// ======================================================

if (!$village) {

    $village = [

        'village_name' => 'Nama Desa'

    ];
}



// ======================================================
// SEO
// ======================================================

$title = "Struktur Organisasi Desa {$village['village_name']}";

$metaTitle = "Struktur Organisasi Pemerintah Desa | {$village['village_name']}";


$metaDescription = "

Struktur organisasi Pemerintah Desa {$village['village_name']} 

beserta perangkat desa dan jajaran pemerintahan.

";
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


    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs/dist/cdn.min.js">
    </script>


</head>



<body class="bg-slate-50 text-slate-800">


    <?php include "../includes/guest/navbar.php"; ?>



    <!-- ================================================= -->
    <!-- HERO -->
    <!-- ================================================= -->


    <section class="relative overflow-hidden bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 pt-20">


        <div class="absolute inset-0 opacity-20">

            <div class="absolute -right-20 -top-20 h-96 w-96 rounded-full bg-white"></div>

            <div class="absolute -left-20 bottom-0 h-72 w-72 rounded-full bg-white"></div>

        </div>



        <div class="relative max-w-7xl mx-auto px-6 py-24">


            <div class="max-w-4xl">


                <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-5 py-2 text-sm font-semibold text-white backdrop-blur">


                    <i class="bi bi-diagram-3-fill"></i>


                    Pemerintahan Desa


                </span>




                <h1 class="mt-6 text-4xl md:text-5xl font-black leading-tight text-white">


                    Struktur Organisasi

                    <br>


                    Pemerintah Desa

                    <span class="text-teal-100">


                        <?= htmlspecialchars($village['village_name']); ?>


                    </span>


                </h1>




                <p class="mt-6 max-w-3xl text-lg leading-8 text-teal-100">


                    Mengenal susunan organisasi Pemerintah Desa

                    <?= htmlspecialchars($village['village_name']); ?>

                    mulai dari Kepala Desa, Sekretariat Desa,

                    hingga perangkat desa lainnya dalam menjalankan

                    pelayanan kepada masyarakat.


                </p>


            </div>


        </div>


    </section>





    <!-- ================================================= -->
    <!-- INTRO -->
    <!-- ================================================= -->


    <section class="py-20 bg-white">


        <div class="max-w-7xl mx-auto px-6">


            <div class="max-w-3xl mx-auto text-center">


                <span class="inline-flex items-center gap-2 rounded-full bg-teal-100 px-4 py-2 text-sm font-semibold text-teal-700">


                    <i class="bi bi-people-fill"></i>


                    Perangkat Desa


                </span>



                <h2 class="mt-5 text-4xl font-black text-slate-900">


                    Struktur Organisasi

                    <span class="text-teal-600">

                        Desa

                    </span>


                </h2>




                <p class="mt-5 text-lg leading-8 text-slate-500">


                    Pemerintah Desa memiliki perangkat yang bekerja

                    bersama dalam memberikan pelayanan administrasi,

                    pembangunan, serta pemberdayaan masyarakat.


                </p>


            </div>


        </div>


    </section>

    <!-- ================================================= -->
    <!-- STRUKTUR ORGANISASI -->
    <!-- ================================================= -->


    <section class="pb-24 bg-white">


        <div class="max-w-7xl mx-auto px-6">


            <?php if (!empty($officials)): ?>


                <!-- ========================= -->
                <!-- Kepala Desa -->
                <!-- ========================= -->


                <?php foreach ($officials as $official): ?>


                    <?php if ($official['category'] == 'Kepala Desa'): ?>


                        <div class="mb-16 rounded-[2.5rem] bg-gradient-to-r from-teal-700 to-emerald-600 p-8 md:p-12 text-white shadow-xl">


                            <div class="grid md:grid-cols-3 gap-10 items-center">


                                <!-- Foto -->


                                <div class="flex justify-center">


                                    <?php if (!empty($official['photo'])): ?>


                                        <img
                                            src="<?= APP_URL ?>uploads/village/officials/<?= htmlspecialchars($official['photo']); ?>"
                                            alt="<?= htmlspecialchars($official['name']); ?>"
                                            class="h-64 w-64 rounded-full object-cover ring-8 ring-white/30 shadow-xl">


                                    <?php else: ?>


                                        <div class="h-64 w-64 rounded-full bg-white/20 flex items-center justify-center">


                                            <i class="bi bi-person-fill text-8xl"></i>


                                        </div>


                                    <?php endif; ?>


                                </div>





                                <!-- Informasi -->


                                <div class="md:col-span-2">


                                    <span class="inline-flex rounded-full bg-white/20 px-4 py-2 text-sm">


                                        Kepala Desa


                                    </span>




                                    <h2 class="mt-5 text-4xl font-black">


                                        <?= htmlspecialchars($official['name']); ?>


                                    </h2>




                                    <p class="mt-2 text-xl text-teal-100">


                                        <?= htmlspecialchars($official['position']); ?>


                                    </p>





                                    <div class="mt-8 grid md:grid-cols-2 gap-5 text-sm">


                                        <?php if (!empty($official['education'])): ?>


                                            <div class="flex gap-3">


                                                <i class="bi bi-mortarboard-fill text-xl"></i>


                                                <div>

                                                    <p class="text-teal-100">
                                                        Pendidikan
                                                    </p>

                                                    <p class="font-semibold">
                                                        <?= htmlspecialchars($official['education']); ?>
                                                    </p>

                                                </div>


                                            </div>


                                        <?php endif; ?>





                                        <?php if (!empty($official['birth_date'])): ?>


                                            <div class="flex gap-3">


                                                <i class="bi bi-calendar-fill text-xl"></i>


                                                <div>

                                                    <p class="text-teal-100">
                                                        Tanggal Lahir
                                                    </p>

                                                    <p class="font-semibold">

                                                        <?= date(
                                                            'd F Y',
                                                            strtotime($official['birth_date'])
                                                        ); ?>

                                                    </p>

                                                </div>


                                            </div>


                                        <?php endif; ?>






                                        <?php if (!empty($official['address'])): ?>


                                            <div class="flex gap-3 md:col-span-2">


                                                <i class="bi bi-geo-alt-fill text-xl"></i>


                                                <div>

                                                    <p class="text-teal-100">
                                                        Alamat
                                                    </p>

                                                    <p class="font-semibold leading-7">

                                                        <?= htmlspecialchars($official['address']); ?>


                                                    </p>

                                                </div>


                                            </div>


                                        <?php endif; ?>


                                    </div>


                                </div>


                            </div>


                        </div>



                    <?php endif; ?>


                <?php endforeach; ?>





                <!-- ========================= -->
                <!-- Perangkat Desa -->
                <!-- ========================= -->


                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">


                    <?php foreach ($officials as $official): ?>


                        <?php if ($official['category'] != 'Kepala Desa'): ?>


                            <div class="group rounded-[2rem] bg-slate-50 p-8 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-2 hover:shadow-xl">


                                <!-- Foto -->


                                <div class="flex justify-center">


                                    <?php if (!empty($official['photo'])): ?>


                                        <img
                                            src="<?= APP_URL ?>uploads/village/officials/<?= htmlspecialchars($official['photo']); ?>"
                                            class="h-32 w-32 rounded-full object-cover ring-4 ring-teal-100">


                                    <?php else: ?>


                                        <div class="h-32 w-32 rounded-full bg-teal-100 flex items-center justify-center">


                                            <i class="bi bi-person-fill text-5xl text-teal-600"></i>


                                        </div>


                                    <?php endif; ?>


                                </div>





                                <div class="mt-6 text-center">


                                    <span class="rounded-full bg-teal-100 px-4 py-1 text-xs font-semibold text-teal-700">


                                        <?= htmlspecialchars($official['category']); ?>


                                    </span>




                                    <h3 class="mt-4 text-xl font-bold">


                                        <?= htmlspecialchars($official['name']); ?>


                                    </h3>




                                    <p class="mt-2 text-teal-600 font-medium">


                                        <?= htmlspecialchars($official['position']); ?>


                                    </p>


                                </div>



                                <!-- Detail Biodata -->


                                <div class="mt-6 border-t pt-6 space-y-3 text-sm text-slate-600">


                                    <?php if (!empty($official['education'])): ?>

                                        <div class="flex items-center gap-3">


                                            <i class="bi bi-mortarboard-fill text-teal-600"></i>


                                            <span>

                                                <?= htmlspecialchars($official['education']); ?>

                                            </span>


                                        </div>


                                    <?php endif; ?>





                                    <?php if (!empty($official['gender'])): ?>


                                        <div class="flex items-center gap-3">


                                            <i class="bi bi-person-fill text-teal-600"></i>


                                            <span>

                                                <?= htmlspecialchars($official['gender']); ?>

                                            </span>


                                        </div>


                                    <?php endif; ?>





                                    <?php if (!empty($official['nip'])): ?>


                                        <div class="flex items-center gap-3">


                                            <i class="bi bi-card-text text-teal-600"></i>


                                            <span>

                                                NIP:
                                                <?= htmlspecialchars($official['nip']); ?>

                                            </span>


                                        </div>


                                    <?php endif; ?>





                                    <?php if (!empty($official['birth_place'])): ?>


                                        <div class="flex items-center gap-3">


                                            <i class="bi bi-geo-alt-fill text-teal-600"></i>


                                            <span>


                                                <?= htmlspecialchars($official['birth_place']); ?>


                                            </span>


                                        </div>


                                    <?php endif; ?>


                                </div>


                            </div>


                        <?php endif; ?>


                    <?php endforeach; ?>


                </div>



            <?php else: ?>



                <!-- Empty State -->


                <div class="rounded-3xl bg-slate-50 p-12 text-center">


                    <i class="bi bi-people-fill text-6xl text-slate-300"></i>


                    <h3 class="mt-5 text-xl font-bold text-slate-700">


                        Data Struktur Belum Tersedia


                    </h3>


                    <p class="mt-2 text-slate-500">


                        Informasi perangkat Pemerintah Desa belum ditambahkan.


                    </p>


                </div>



            <?php endif; ?>


        </div>


    </section>





    <!-- ================================================= -->
    <!-- FOOTER -->
    <!-- ================================================= -->


    <?php include "../includes/guest/footer.php"; ?>



</body>


</html>