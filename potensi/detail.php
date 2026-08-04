<?php

require_once "../config/app.php";


$page = "potensi";


// ==================================================
// VALIDASI SLUG
// ==================================================

if (
    !isset($_GET['slug']) ||
    empty($_GET['slug'])
) {

    header("Location: index.php");
    exit;
}


$slug = mysqli_real_escape_string(
    $conn,
    $_GET['slug']
);



// ==================================================
// AMBIL DATA POTENSI
// ==================================================

$query = mysqli_query($conn, "

    SELECT *

    FROM village_potentials

    WHERE slug='$slug'

    AND status='Published'

    LIMIT 1

");



if (
    !$query ||
    mysqli_num_rows($query) == 0
) {

    header("Location: index.php");
    exit;
}



$potential = mysqli_fetch_assoc($query);



// ==================================================
// UPDATE VIEW
// ==================================================

mysqli_query($conn, "

    UPDATE village_potentials

    SET views = views + 1

    WHERE id='{$potential['id']}'

");




// ==================================================
// PROFIL DESA
// ==================================================

$profileQuery = mysqli_query($conn, "

    SELECT *

    FROM village_profiles

    LIMIT 1

");


$villageProfile = mysqli_fetch_assoc($profileQuery);



if (!$villageProfile) {

    $villageProfile = [
        'village_name' => 'Website Desa'
    ];
}



// ==================================================
// META
// ==================================================

$title = $potential['title'];

$metaTitle =
    $potential['title']
    . " | "
    . $villageProfile['village_name'];


$metaDescription =
    mb_substr(
        strip_tags($potential['description']),
        0,
        160
    );

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


</head>



<body class="bg-slate-50 text-slate-800">


    <?php include "../includes/guest/navbar.php"; ?>



    <!-- ================================================= -->
    <!-- HERO -->
    <!-- ================================================= -->

    <section class="relative overflow-hidden bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 pt-20 text-white">


        <div class="absolute inset-0 opacity-20">

            <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-white"></div>

            <div class="absolute -left-20 bottom-0 h-72 w-72 rounded-full bg-white"></div>

        </div>



        <div class="relative max-w-7xl mx-auto px-6 py-20">


            <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-2 text-sm font-semibold">

                <i class="bi bi-stars"></i>

                Potensi Desa

            </span>



            <h1 class="mt-6 text-4xl md:text-5xl font-black">

                <?= htmlspecialchars($potential['title']); ?>

            </h1>



            <p class="mt-5 max-w-3xl text-lg text-teal-100">

                <?= htmlspecialchars($potential['category']); ?>

            </p>



        </div>


    </section>





    <!-- ================================================= -->
    <!-- CONTENT -->
    <!-- ================================================= -->

    <section class="py-16">


        <div class="max-w-7xl mx-auto px-6">


            <div class="grid gap-10 lg:grid-cols-3">



                <!-- LEFT -->

                <div class="lg:col-span-2 space-y-8">



                    <!-- IMAGE -->

                    <div class="overflow-hidden rounded-3xl bg-white shadow">


                        <?php if (!empty($potential['image'])): ?>


                            <img

                                src="<?= APP_URL ?>uploads/potentials/<?= htmlspecialchars($potential['image']); ?>"

                                class="h-[450px] w-full object-cover">


                        <?php else: ?>


                            <div class="flex h-[450px] items-center justify-center bg-slate-100">

                                <i class="bi bi-image text-7xl text-slate-300"></i>

                            </div>


                        <?php endif; ?>


                    </div>





                    <!-- DESCRIPTION -->


                    <div class="rounded-3xl bg-white p-8 shadow-sm">


                        <h2 class="text-2xl font-bold text-slate-900">

                            Tentang Potensi

                        </h2>



                        <div class="mt-5 leading-8 text-slate-600">


                            <?= nl2br(
                                htmlspecialchars(
                                    $potential['description']
                                )
                            ); ?>


                        </div>


                    </div>




                    <!-- FACILITIES -->

                    <?php if (!empty($potential['facilities'])): ?>


                        <div class="rounded-3xl bg-white p-8 shadow-sm">


                            <h2 class="text-2xl font-bold">

                                Fasilitas

                            </h2>


                            <p class="mt-4 text-slate-600 leading-7">

                                <?= nl2br(
                                    htmlspecialchars(
                                        $potential['facilities']
                                    )
                                ); ?>


                            </p>


                        </div>


                    <?php endif; ?>




                </div>







                <!-- RIGHT SIDEBAR -->

                <div class="space-y-6">



                    <div class="rounded-3xl bg-white p-6 shadow-sm">


                        <h3 class="text-xl font-bold">

                            Informasi

                        </h3>



                        <div class="mt-6 space-y-5">



                            <?php if ($potential['owner_name']): ?>

                                <div class="flex gap-3">

                                    <i class="bi bi-person text-teal-600"></i>

                                    <div>

                                        <p class="text-sm text-slate-500">
                                            Pemilik
                                        </p>

                                        <p class="font-semibold">

                                            <?= htmlspecialchars($potential['owner_name']); ?>

                                        </p>

                                    </div>

                                </div>

                            <?php endif; ?>





                            <?php if ($potential['address']): ?>

                                <div class="flex gap-3">

                                    <i class="bi bi-geo-alt text-teal-600"></i>

                                    <div>

                                        <p class="text-sm text-slate-500">
                                            Alamat
                                        </p>

                                        <p class="font-semibold">

                                            <?= htmlspecialchars($potential['address']); ?>

                                        </p>

                                    </div>

                                </div>

                            <?php endif; ?>





                            <?php if ($potential['operational_hours']): ?>

                                <div class="flex gap-3">

                                    <i class="bi bi-clock text-teal-600"></i>

                                    <div>

                                        <p class="text-sm text-slate-500">
                                            Jam Operasional
                                        </p>

                                        <p class="font-semibold">

                                            <?= htmlspecialchars($potential['operational_hours']); ?>

                                        </p>

                                    </div>

                                </div>

                            <?php endif; ?>



                        </div>


                    </div>





                    <!-- CONTACT -->


                    <?php if (!empty($potential['whatsapp'])): ?>


                        <a

                            href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $potential['whatsapp']); ?>"

                            target="_blank"

                            class="flex items-center justify-center gap-2 rounded-2xl bg-green-500 px-6 py-4 font-bold text-white hover:bg-green-600">


                            <i class="bi bi-whatsapp text-xl"></i>

                            Hubungi Sekarang


                        </a>


                    <?php endif; ?>




                </div>



            </div>


        </div>


    </section>





    <?php include "../includes/guest/footer.php"; ?>


</body>

</html>