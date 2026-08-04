<?php

require_once "../config/app.php";

$page = "kewilayahan";

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
// Data Kewilayahan
// ===============================

$regionalQuery = mysqli_query($conn, "
    SELECT *
    FROM regionals
    WHERE status = 'Published'
    ORDER BY
        sort_order ASC,
        year DESC,
        id DESC
");

// ===============================
// Meta SEO
// ===============================

$title = "Kewilayahan Desa {$profile['village_name']}";
$metaTitle = "Kewilayahan | {$profile['village_name']}";
$metaDescription = "Informasi kewilayahan Desa {$profile['village_name']}, meliputi peta wilayah, batas administrasi, serta data kewilayahan desa.";

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

    <!-- Alpine JS -->
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

            <div class="absolute -left-20 bottom-0 h-72 h-72 rounded-full bg-white"></div>

        </div>




        <div class="relative max-w-7xl mx-auto px-6 py-24">


            <div class="max-w-4xl">


                <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-5 py-2 text-sm font-semibold backdrop-blur">


                    <i class="bi bi-map-fill"></i>


                    Profil Desa


                </span>





                <h1 class="mt-6 text-4xl md:text-5xl font-black leading-tight">


                    Kewilayahan Desa


                    <br>


                    <span class="text-teal-100">


                        <?= htmlspecialchars($profile['village_name']); ?>


                    </span>


                </h1>






                <p class="mt-6 max-w-3xl text-lg leading-8 text-teal-100">


                    Informasi mengenai wilayah administrasi Desa
                    <?= htmlspecialchars($profile['village_name']); ?>,
                    meliputi data dusun, RT/RW, batas wilayah,
                    peta administrasi, serta informasi kewilayahan lainnya.


                </p>



            </div>



        </div>



    </section>



    <!-- CONTENT -->

    <section class="py-16">

        <div class="max-w-7xl mx-auto px-6">

            <?php if (mysqli_num_rows($regionalQuery) > 0): ?>

                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">

                    <?php while ($row = mysqli_fetch_assoc($regionalQuery)): ?>

                        <div class="bg-white rounded-3xl shadow hover:shadow-lg transition overflow-hidden">

                            <!-- Image -->

                            <?php if (!empty($row['image'])): ?>

                                <img

                                    src="<?= APP_URL ?>uploads/village/regionals/<?= $row['image'] ?>"

                                    class="w-full h-56 object-cover">

                            <?php else: ?>

                                <div class="h-56 bg-slate-200 flex items-center justify-center">

                                    <i class="bi bi-map text-6xl text-slate-400"></i>

                                </div>

                            <?php endif; ?>


                            <div class="p-6">

                                <span class="inline-block px-3 py-1 rounded-full bg-teal-100 text-teal-700 text-xs font-semibold">

                                    <?= $row['category'] ?>

                                </span>

                                <h2 class="text-xl font-bold mt-4">

                                    <?= htmlspecialchars($row['title']) ?>

                                </h2>

                                <?php if (!empty($row['description'])): ?>

                                    <p class="text-slate-600 mt-3 line-clamp-3">

                                        <?= htmlspecialchars($row['description']) ?>

                                    </p>

                                <?php endif; ?>

                                <div class="mt-6 space-y-2 text-sm text-slate-500">

                                    <?php if (!empty($row['year'])): ?>

                                        <p>

                                            <i class="bi bi-calendar3 text-teal-600"></i>

                                            Tahun :
                                            <?= $row['year'] ?>

                                        </p>

                                    <?php endif; ?>

                                    <?php if (!empty($row['scale'])): ?>

                                        <p>

                                            <i class="bi bi-arrows-fullscreen text-teal-600"></i>

                                            Skala :
                                            <?= $row['scale'] ?>

                                        </p>

                                    <?php endif; ?>

                                </div>


                                <div class="mt-6 flex gap-3">

                                    <?php if (!empty($row['google_maps'])): ?>

                                        <a

                                            href="<?= $row['google_maps'] ?>"

                                            target="_blank"

                                            class="flex-1 rounded-xl bg-teal-600 px-4 py-3 text-center font-semibold text-white hover:bg-teal-700">

                                            <i class="bi bi-geo-alt-fill"></i>

                                            Lihat

                                        </a>

                                    <?php endif; ?>


                                    <?php if (!empty($row['document'])): ?>

                                        <a

                                            href="<?= APP_URL ?>uploads/village/regionals/<?= $row['document'] ?>"

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

                <div class="bg-white rounded-3xl shadow p-16 text-center">

                    <i class="bi bi-map text-6xl text-slate-300"></i>

                    <h2 class="text-2xl font-bold mt-5">

                        Data Kewilayahan Belum Tersedia

                    </h2>

                    <p class="mt-3 text-slate-500">

                        Belum ada data kewilayahan yang dipublikasikan.

                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>

    <?php include "../includes/guest/footer.php"; ?>
</body>

</html>