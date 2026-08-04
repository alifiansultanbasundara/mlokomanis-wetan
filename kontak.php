<?php

require_once "config/app.php";

$page = "kontak";


// ==================================================
// PROFIL DESA
// ==================================================

$queryProfile = mysqli_query($conn, "
    SELECT *
    FROM village_profiles
    LIMIT 1
");


$villageProfile = mysqli_fetch_assoc($queryProfile);


if (!$villageProfile) {

    $villageProfile = [

        'village_name'   => 'Website Desa',
        'office_address' => '',
        'phone'          => '',
        'whatsapp'       => '',
        'email'          => '',
        'website'        => '',
        'office_hours'   => '',
        'google_maps'    => ''

    ];
}


// ==================================================
// META
// ==================================================

$title = "Kontak";

$metaTitle = "Kontak | " . $villageProfile['village_name'];

$metaDescription =
    "Hubungi Pemerintah Desa " .
    $villageProfile['village_name'] .
    " melalui informasi kontak resmi.";

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <?php include "includes/head.php"; ?>


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


    <?php include "includes/guest/navbar.php"; ?>

    <!-- ================================================= -->
    <!-- HERO -->
    <!-- ================================================= -->

    <section class="relative overflow-hidden bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 pt-20 text-white">


        <!-- Decoration -->

        <div class="absolute inset-0 opacity-20">


            <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-white"></div>


            <div class="absolute -left-20 bottom-0 h-72 w-72 rounded-full bg-white"></div>


        </div>





        <div class="relative max-w-7xl mx-auto px-6 py-24 text-center">



            <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-5 py-2 text-sm font-semibold backdrop-blur">


                <i class="bi bi-envelope-paper-fill"></i>


                Hubungi Kami


            </span>







            <h1 class="mt-6 text-4xl md:text-5xl font-black leading-tight">


                Kontak Pemerintah Desa


                <br>


                <span class="text-teal-100">


                    <?= htmlspecialchars($villageProfile['village_name']); ?>


                </span>


            </h1>







            <p class="mx-auto mt-6 max-w-3xl text-lg leading-8 text-teal-100">


                Sampaikan pertanyaan, aspirasi, maupun informasi kepada Pemerintah Desa
                <?= htmlspecialchars($villageProfile['village_name']); ?>.
                Kami siap memberikan pelayanan terbaik,
                menerima masukan masyarakat, dan membantu kebutuhan administrasi desa.


            </p>




        </div>



    </section>




    <!-- ================================================= -->
    <!-- CONTENT -->
    <!-- ================================================= -->

    <section class="py-24">

        <div class="max-w-7xl mx-auto px-6 grid gap-10 lg:grid-cols-5">



            <!-- ========================================== -->
            <!-- INFORMASI KONTAK -->
            <!-- ========================================== -->


            <div class="lg:col-span-2">


                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">



                    <!-- Header -->


                    <div class="flex items-center gap-4">


                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-teal-100">

                            <i class="bi bi-building-fill text-3xl text-teal-700"></i>

                        </div>



                        <div>


                            <h2 class="text-2xl font-bold text-slate-900">

                                <?= htmlspecialchars($villageProfile['village_name']); ?>

                            </h2>


                            <p class="text-slate-500">

                                Kantor Pemerintah Desa

                            </p>


                        </div>


                    </div>




                    <!-- Detail -->

                    <div class="mt-10 space-y-7">



                        <!-- ALAMAT -->


                        <div class="flex items-start gap-4">


                            <div class="mt-1 text-teal-600">

                                <i class="bi bi-geo-alt-fill text-xl"></i>

                            </div>



                            <div class="flex-1">


                                <p class="text-sm text-slate-500">

                                    Alamat

                                </p>



                                <?php if (!empty($villageProfile['office_address'])): ?>


                                    <p class="mt-1 font-medium leading-7 text-slate-800">

                                        <?= nl2br(
                                            htmlspecialchars(
                                                $villageProfile['office_address']
                                            )
                                        ); ?>

                                    </p>


                                <?php else: ?>


                                    <p class="mt-1 text-slate-400">

                                        Alamat belum tersedia.

                                    </p>


                                <?php endif; ?>


                            </div>


                        </div>





                        <!-- TELEPON -->


                        <?php if (!empty($villageProfile['phone'])): ?>


                            <div class="flex items-start gap-4">


                                <div class="mt-1 text-teal-600">

                                    <i class="bi bi-telephone-fill text-xl"></i>

                                </div>



                                <div>


                                    <p class="text-sm text-slate-500">

                                        Telepon

                                    </p>



                                    <a
                                        href="tel:<?= $villageProfile['phone']; ?>"
                                        class="font-medium text-slate-800 hover:text-teal-700">


                                        <?= htmlspecialchars(
                                            $villageProfile['phone']
                                        ); ?>


                                    </a>


                                </div>


                            </div>


                        <?php endif; ?>




                        <!-- WHATSAPP -->


                        <?php if (!empty($villageProfile['whatsapp'])): ?>


                            <div class="flex items-start gap-4">


                                <div class="mt-1 text-green-600">

                                    <i class="bi bi-whatsapp text-xl"></i>

                                </div>



                                <div>


                                    <p class="text-sm text-slate-500">

                                        WhatsApp

                                    </p>



                                    <a
                                        target="_blank"
                                        href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $villageProfile['whatsapp']); ?>"
                                        class="font-medium text-slate-800 hover:text-green-700">


                                        <?= htmlspecialchars(
                                            $villageProfile['whatsapp']
                                        ); ?>


                                    </a>


                                </div>


                            </div>


                        <?php endif; ?>

                        <!-- EMAIL -->


                        <?php if (!empty($villageProfile['email'])): ?>


                            <div class="flex items-start gap-4">


                                <div class="mt-1 text-teal-600">

                                    <i class="bi bi-envelope-fill text-xl"></i>

                                </div>



                                <div>


                                    <p class="text-sm text-slate-500">

                                        Email

                                    </p>



                                    <a
                                        href="mailto:<?= htmlspecialchars($villageProfile['email']); ?>"
                                        class="font-medium text-slate-800 hover:text-teal-700">


                                        <?= htmlspecialchars($villageProfile['email']); ?>


                                    </a>


                                </div>


                            </div>


                        <?php endif; ?>





                        <!-- WEBSITE -->


                        <?php if (!empty($villageProfile['website'])): ?>


                            <div class="flex items-start gap-4">


                                <div class="mt-1 text-teal-600">

                                    <i class="bi bi-globe text-xl"></i>

                                </div>



                                <div>


                                    <p class="text-sm text-slate-500">

                                        Website

                                    </p>



                                    <a
                                        href="<?= htmlspecialchars($villageProfile['website']); ?>"
                                        target="_blank"
                                        class="font-medium text-slate-800 hover:text-teal-700">


                                        <?= htmlspecialchars($villageProfile['website']); ?>


                                    </a>


                                </div>


                            </div>


                        <?php endif; ?>






                        <!-- JAM PELAYANAN -->


                        <?php if (!empty($villageProfile['office_hours'])): ?>


                            <div class="flex items-start gap-4">


                                <div class="mt-1 text-teal-600">

                                    <i class="bi bi-clock-fill text-xl"></i>

                                </div>



                                <div class="flex-1">


                                    <p class="text-sm text-slate-500">

                                        Jam Pelayanan

                                    </p>




                                    <div class="mt-2 rounded-xl bg-slate-50 p-4">


                                        <?php

                                        $hours = explode(
                                            "\n",
                                            $villageProfile['office_hours']
                                        );


                                        foreach ($hours as $hour):

                                            if (trim($hour) != ''):

                                        ?>


                                                <p class="leading-7 text-slate-700">

                                                    <?= htmlspecialchars(trim($hour)); ?>

                                                </p>


                                        <?php

                                            endif;

                                        endforeach;

                                        ?>


                                    </div>


                                </div>


                            </div>


                        <?php endif; ?>




                    </div>


                </div>


            </div>





            <!-- ========================================== -->
            <!-- FORM KONTAK -->
            <!-- ========================================== -->


            <div class="lg:col-span-3">


                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">


                    <h2 class="text-2xl font-bold text-slate-900">

                        Kirim Pesan

                    </h2>



                    <p class="mt-2 text-slate-500">

                        Silakan isi formulir berikut untuk menghubungi Pemerintah Desa.

                    </p>





                    <form
                        action="kontak-store.php"
                        method="POST"
                        class="mt-8 grid gap-6 md:grid-cols-2">





                        <!-- Nama -->


                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Nama Lengkap

                            </label>


                            <input
                                type="text"
                                name="name"
                                required
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-500 focus:outline-none">


                        </div>





                        <!-- Email -->


                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Email

                            </label>


                            <input
                                type="email"
                                name="email"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-500 focus:outline-none">


                        </div>





                        <!-- Telepon -->


                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Nomor HP

                            </label>


                            <input
                                type="text"
                                name="phone"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-500 focus:outline-none">


                        </div>





                        <!-- Subjek -->


                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Subjek

                            </label>


                            <input
                                type="text"
                                name="subject"
                                required
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-500 focus:outline-none">


                        </div>





                        <!-- Pesan -->


                        <div class="md:col-span-2">


                            <label class="mb-2 block font-medium text-slate-700">

                                Pesan

                            </label>


                            <textarea
                                name="message"
                                rows="6"
                                required
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-500 focus:outline-none"></textarea>


                        </div>





                        <!-- Button -->


                        <div class="md:col-span-2">


                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-8 py-4 font-semibold text-white hover:bg-teal-700">


                                <i class="bi bi-send-fill"></i>


                                Kirim Pesan


                            </button>


                        </div>




                    </form>



                </div>



            </div>

            <!-- ========================================== -->
            <!-- GOOGLE MAPS -->
            <!-- ========================================== -->


            <?php if (!empty($villageProfile['google_maps'])): ?>


                <div class="lg:col-span-5">


                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100">


                        <div class="border-b border-slate-200 px-8 py-6">


                            <div class="flex items-center gap-3">


                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-100">

                                    <i class="bi bi-map-fill text-xl text-teal-700"></i>

                                </div>


                                <div>


                                    <h2 class="text-xl font-bold text-slate-900">

                                        Lokasi Kantor Desa

                                    </h2>


                                    <p class="text-sm text-slate-500">

                                        Peta lokasi Kantor Pemerintah Desa

                                    </p>


                                </div>


                            </div>


                        </div>




                        <div class="h-[450px] w-full">


                            <?= str_replace(
                                '<iframe',
                                '<iframe class="h-full w-full border-0"',
                                $villageProfile['google_maps']
                            ); ?>


                        </div>



                    </div>


                </div>


            <?php else: ?>


                <div class="lg:col-span-5">


                    <div class="flex h-72 items-center justify-center rounded-3xl bg-white shadow-sm ring-1 ring-slate-100">


                        <div class="text-center text-slate-400">


                            <i class="bi bi-map text-6xl"></i>


                            <p class="mt-4">

                                Lokasi Google Maps belum tersedia.

                            </p>


                        </div>


                    </div>


                </div>


            <?php endif; ?>



        </div>

    </section>





    <!-- ================================================= -->
    <!-- FOOTER -->
    <!-- ================================================= -->


    <?php include "includes/guest/footer.php"; ?>



</body>


</html>