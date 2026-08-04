<?php

require_once "../config/app.php";


$page = "lembaga";


// ======================================================
// Profil Desa
// ======================================================

$profileQuery = mysqli_query($conn, "

    SELECT *

    FROM village_profiles

    LIMIT 1

");


$village = mysqli_fetch_assoc($profileQuery);



if (!$village) {

    $village = [

        'village_name' => 'Nama Desa'

    ];
}



// ======================================================
// Ambil Data Lembaga
// ======================================================


$institutionQuery = mysqli_query($conn, "

    SELECT *

    FROM village_institutions

    WHERE status='Active'

    ORDER BY

        sort_order ASC,

        id ASC

");



$institutions = [];



while ($row = mysqli_fetch_assoc($institutionQuery)) {


    $institutions[] = $row;
}



// ======================================================
// Statistik
// ======================================================


$totalInstitution = count($institutions);



$totalMemberQuery = mysqli_query($conn, "

    SELECT COUNT(*) total

    FROM village_institution_members

    WHERE status='Active'

");



$totalMember = mysqli_fetch_assoc($totalMemberQuery)['total'];




// ======================================================
// SEO
// ======================================================


$title = "Lembaga Desa {$village['village_name']}";


$metaTitle = "Lembaga Desa | {$village['village_name']}";


$metaDescription = "

Informasi lembaga desa {$village['village_name']}

beserta organisasi, pengurus, dan anggota lembaga masyarakat desa.

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


            <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-white"></div>


            <div class="absolute -left-20 bottom-0 h-72 w-72 rounded-full bg-white"></div>


        </div>





        <div class="relative max-w-7xl mx-auto px-6 py-24">


            <div class="max-w-4xl">


                <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-5 py-2 text-sm font-semibold text-white backdrop-blur">


                    <i class="bi bi-building-fill-check"></i>


                    Kelembagaan Desa


                </span>





                <h1 class="mt-6 text-4xl md:text-5xl font-black leading-tight text-white">


                    Lembaga Desa


                    <br>


                    <?= htmlspecialchars($village['village_name']); ?>


                </h1>





                <p class="mt-6 max-w-3xl text-lg leading-8 text-teal-100">


                    Mengenal berbagai lembaga kemasyarakatan desa yang berperan

                    dalam mendukung pembangunan, pelayanan masyarakat,

                    serta pemberdayaan warga Desa

                    <?= htmlspecialchars($village['village_name']); ?>.


                </p>


            </div>


        </div>


    </section>






    <!-- ================================================= -->
    <!-- STATISTIK -->
    <!-- ================================================= -->


    <section class="relative -mt-12 pb-12">


        <div class="max-w-5xl mx-auto px-6">


            <div class="grid gap-6 md:grid-cols-2">



                <!-- Total Lembaga -->


                <div class="rounded-3xl bg-white p-8 shadow-xl ring-1 ring-slate-100">


                    <div class="flex items-center gap-5">


                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-teal-100">


                            <i class="bi bi-buildings-fill text-3xl text-teal-700"></i>


                        </div>



                        <div>


                            <p class="text-slate-500">

                                Total Lembaga Desa

                            </p>


                            <h3 class="mt-1 text-4xl font-black text-slate-900">


                                <?= $totalInstitution; ?>


                            </h3>


                        </div>


                    </div>


                </div>







                <!-- Total Anggota -->


                <div class="rounded-3xl bg-white p-8 shadow-xl ring-1 ring-slate-100">


                    <div class="flex items-center gap-5">


                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100">


                            <i class="bi bi-people-fill text-3xl text-emerald-700"></i>


                        </div>



                        <div>


                            <p class="text-slate-500">

                                Total Anggota Lembaga

                            </p>


                            <h3 class="mt-1 text-4xl font-black text-slate-900">


                                <?= $totalMember; ?>


                            </h3>


                        </div>


                    </div>


                </div>




            </div>


        </div>


    </section>

    <!-- ================================================= -->
    <!-- DAFTAR LEMBAGA DESA -->
    <!-- ================================================= -->


    <section class="py-20">


        <div class="max-w-7xl mx-auto px-6">


            <!-- Heading -->


            <div class="max-w-3xl mx-auto text-center">


                <span class="inline-flex items-center gap-2 rounded-full bg-teal-100 px-4 py-2 text-sm font-semibold text-teal-700">


                    <i class="bi bi-diagram-3-fill"></i>


                    Organisasi Masyarakat


                </span>




                <h2 class="mt-5 text-4xl font-black text-slate-900">


                    Daftar

                    <span class="text-teal-600">

                        Lembaga Desa

                    </span>


                </h2>




                <p class="mt-5 text-lg leading-8 text-slate-500">


                    Lembaga desa merupakan wadah partisipasi masyarakat

                    dalam membantu pemerintah desa menjalankan pembangunan

                    dan pemberdayaan masyarakat.


                </p>


            </div>





            <!-- Cards -->
            <div class="mt-16 grid gap-8 md:grid-cols-2 lg:grid-cols-3">


                <?php if (!empty($institutions)): ?>


                    <?php foreach ($institutions as $institution): ?>


                        <?php

                        $memberQuery = mysqli_query($conn, "

    SELECT *

    FROM village_institution_members

    WHERE institution_id='{$institution['id']}'

    AND status='Active'

    ORDER BY sort_order ASC, id ASC

");


                        $members = [];

                        while ($member = mysqli_fetch_assoc($memberQuery)) {

                            $members[] = $member;
                        }

                        ?>


                        <div
                            class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-100">


                            <!-- Foto -->

                            <div class="flex justify-center">


                                <?php if (!empty($institution['image'])): ?>


                                    <img
                                        src="<?= APP_URL ?>uploads/informasi/village/institutions/<?= htmlspecialchars($institution['image']); ?>"
                                        class="h-32 w-32 rounded-3xl object-cover ring-4 ring-teal-100">


                                <?php else: ?>


                                    <div class="flex h-32 w-32 items-center justify-center rounded-3xl bg-teal-100">

                                        <i class="bi bi-building text-5xl text-teal-600"></i>

                                    </div>


                                <?php endif; ?>


                            </div>



                            <!-- Nama -->

                            <div class="mt-6 text-center">


                                <span class="rounded-full bg-teal-50 px-4 py-1 text-xs font-semibold text-teal-700">

                                    <?= htmlspecialchars($institution['category']); ?>

                                </span>



                                <h3 class="mt-4 text-xl font-bold">

                                    <?= htmlspecialchars($institution['name']); ?>

                                </h3>


                            </div>



                            <!-- Info -->


                            <div class="mt-6 space-y-3 border-t pt-6 text-sm">


                                <?php if ($institution['chairman']): ?>

                                    <div class="flex gap-3">

                                        <i class="bi bi-person-fill text-teal-600"></i>

                                        Ketua:
                                        <strong>
                                            <?= htmlspecialchars($institution['chairman']); ?>
                                        </strong>

                                    </div>

                                <?php endif; ?>



                                <div class="flex gap-3">

                                    <i class="bi bi-people-fill text-teal-600"></i>

                                    <?= count($members); ?> Anggota

                                </div>


                            </div>




                            <!-- Anggota -->


                            <?php if (!empty($members)): ?>


                                <div class="mt-8 border-t pt-6">


                                    <h4 class="font-bold mb-4">

                                        Daftar Anggota

                                    </h4>



                                    <div class="space-y-4">


                                        <?php foreach ($members as $member): ?>


                                            <div class="flex items-center gap-4">


                                                <?php if (!empty($member['photo'])): ?>


                                                    <img
                                                        src="<?= APP_URL ?>uploads/informasi/institution/members/<?= htmlspecialchars($member['photo']); ?>"
                                                        class="h-12 w-12 rounded-full object-cover">


                                                <?php else: ?>


                                                    <div class="h-12 w-12 rounded-full bg-teal-100 flex items-center justify-center">

                                                        <i class="bi bi-person text-teal-600"></i>

                                                    </div>


                                                <?php endif; ?>


                                                <div>


                                                    <p class="font-semibold">

                                                        <?= htmlspecialchars($member['name']); ?>

                                                    </p>


                                                    <p class="text-sm text-slate-500">

                                                        <?= htmlspecialchars($member['position']); ?>

                                                    </p>


                                                </div>


                                            </div>


                                        <?php endforeach; ?>


                                    </div>


                                </div>


                            <?php endif; ?>



                        </div>


                    <?php endforeach; ?>



                <?php else: ?>


                    <div class="col-span-full text-center bg-white rounded-3xl p-10">

                        <i class="bi bi-building text-5xl text-slate-400"></i>

                        <p class="mt-4 text-slate-500">

                            Belum ada data lembaga desa.

                        </p>

                    </div>


                <?php endif; ?>


            </div>


        </div>

    </section>


    <!-- ================================================= -->
    <!-- FOOTER -->
    <!-- ================================================= -->

    <?php include "../includes/guest/footer.php"; ?>


</body>

</html>