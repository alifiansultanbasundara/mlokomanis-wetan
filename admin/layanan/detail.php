<?php

require_once '../../config/app.php';


// ======================================================
// Validasi ID
// ======================================================

if (!isset($_GET['id'])) {

    header("Location:index.php");

    exit;
}


$id = (int) $_GET['id'];




// ======================================================
// Ambil Data Layanan
// ======================================================

$query = mysqli_query($conn, "
    SELECT *
    FROM service_letters
    WHERE id='$id'
    LIMIT 1
");



if (mysqli_num_rows($query) == 0) {

    header("Location:index.php");

    exit;
}



$service = mysqli_fetch_assoc($query);






// ======================================================
// Page
// ======================================================


$title = "Detail Pelayanan Surat";

$page = "layanan";


include APP_PATH . "includes/admin/layout-top.php";


?>



<main class="p-8 space-y-6">





    <!-- HEADER -->


    <div class="flex justify-between items-center">


        <div class="flex items-center gap-5">



            <div
                class="w-20 h-20 rounded-2xl bg-<?= $service['color']; ?>-100 flex items-center justify-center">


                <i class="<?= htmlspecialchars($service['icon']); ?> text-4xl text-<?= $service['color']; ?>-600"></i>


            </div>





            <div>


                <h1 class="text-3xl font-bold text-slate-800">


                    <?= htmlspecialchars($service['name']); ?>


                </h1>



                <p class="text-slate-500 mt-2">


                    <?= htmlspecialchars($service['slug']); ?>


                </p>



            </div>


        </div>






        <div class="flex gap-3">



            <a
                href="edit.php?id=<?= $service['id']; ?>"
                class="px-5 py-3 rounded-xl bg-blue-600 text-white">


                <i class="bi bi-pencil"></i>


                Edit


            </a>





            <a
                href="index.php"
                class="px-5 py-3 rounded-xl border">


                <i class="bi bi-arrow-left"></i>


                Kembali


            </a>



        </div>



    </div>








    <div class="grid lg:grid-cols-3 gap-8">






        <!-- LEFT CONTENT -->


        <div class="lg:col-span-2 space-y-6">







            <!-- DESKRIPSI -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="text-lg font-semibold text-slate-800 mb-4">

                    Tentang Layanan

                </h2>



                <p class="text-slate-600 leading-relaxed">


                    <?= nl2br(
                        htmlspecialchars(
                            $service['description'] ?? '-'
                        )
                    ); ?>


                </p>


            </div>









            <!-- PERSYARATAN -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="text-lg font-semibold text-slate-800 mb-4">

                    Persyaratan

                </h2>




                <?php if (!empty($service['requirements'])): ?>


                    <div class="text-slate-600 leading-relaxed whitespace-pre-line">


                        <?= htmlspecialchars($service['requirements']); ?>


                    </div>



                <?php else: ?>


                    <p class="text-slate-400">

                        Belum ada persyaratan.

                    </p>



                <?php endif; ?>



            </div>






            <!-- PROSEDUR -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="text-lg font-semibold text-slate-800 mb-4">

                    Prosedur Pelayanan

                </h2>




                <?php if (!empty($service['service_procedure'])): ?>


                    <div class="text-slate-600 leading-relaxed whitespace-pre-line">


                        <?= htmlspecialchars($service['service_procedure']); ?>


                    </div>



                <?php else: ?>


                    <p class="text-slate-400">

                        Belum ada prosedur.

                    </p>



                <?php endif; ?>



            </div>






            <!-- INFORMASI OPERASIONAL -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="text-lg font-semibold text-slate-800 mb-5">

                    Informasi Pelayanan

                </h2>




                <div class="grid md:grid-cols-2 gap-5">





                    <div class="p-4 rounded-xl bg-slate-50">


                        <p class="text-sm text-slate-500">

                            Waktu Proses

                        </p>


                        <p class="font-semibold text-slate-800 mt-1">


                            <?= htmlspecialchars(
                                $service['processing_time'] ?? '-'
                            ); ?>


                        </p>


                    </div>







                    <div class="p-4 rounded-xl bg-slate-50">


                        <p class="text-sm text-slate-500">

                            Biaya

                        </p>


                        <p class="font-semibold text-slate-800 mt-1">


                            <?= htmlspecialchars(
                                $service['fee'] ?? 'Gratis'
                            ); ?>


                        </p>


                    </div>








                    <div class="p-4 rounded-xl bg-slate-50">


                        <p class="text-sm text-slate-500">

                            Petugas

                        </p>


                        <p class="font-semibold text-slate-800 mt-1">


                            <?= htmlspecialchars(
                                $service['contact_person'] ?? '-'
                            ); ?>


                        </p>


                    </div>








                    <div class="p-4 rounded-xl bg-slate-50">


                        <p class="text-sm text-slate-500">

                            Telepon

                        </p>


                        <p class="font-semibold text-slate-800 mt-1">


                            <?= htmlspecialchars(
                                $service['phone'] ?? '-'
                            ); ?>


                        </p>


                    </div>





                </div>


            </div>








            <!-- LINK ONLINE -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="text-lg font-semibold text-slate-800 mb-5">

                    Layanan Online

                </h2>





                <div class="space-y-3">





                    <?php if (!empty($service['google_form_url'])): ?>


                        <a
                            href="<?= htmlspecialchars($service['google_form_url']); ?>"
                            target="_blank"
                            class="flex items-center justify-between p-4 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100">


                            <div class="flex items-center gap-3">


                                <i class="bi bi-google text-xl"></i>


                                <span>

                                    Google Form

                                </span>


                            </div>



                            <i class="bi bi-box-arrow-up-right"></i>


                        </a>



                    <?php endif; ?>







                    <?php if (!empty($service['template_url'])): ?>


                        <a
                            href="<?= htmlspecialchars($service['template_url']); ?>"
                            target="_blank"
                            class="flex items-center justify-between p-4 rounded-xl bg-purple-50 text-purple-700 hover:bg-purple-100">


                            <div class="flex items-center gap-3">


                                <i class="bi bi-file-earmark-text text-xl"></i>


                                <span>

                                    Template Surat

                                </span>


                            </div>



                            <i class="bi bi-box-arrow-up-right"></i>


                        </a>



                    <?php endif; ?>







                    <?php if (!empty($service['spreadsheet_url'])): ?>


                        <a
                            href="<?= htmlspecialchars($service['spreadsheet_url']); ?>"
                            target="_blank"
                            class="flex items-center justify-between p-4 rounded-xl bg-green-50 text-green-700 hover:bg-green-100">


                            <div class="flex items-center gap-3">


                                <i class="bi bi-table text-xl"></i>


                                <span>

                                    Spreadsheet

                                </span>


                            </div>



                            <i class="bi bi-box-arrow-up-right"></i>


                        </a>



                    <?php endif; ?>








                    <?php if (!empty($service['tracking_url'])): ?>


                        <a
                            href="<?= htmlspecialchars($service['tracking_url']); ?>"
                            target="_blank"
                            class="flex items-center justify-between p-4 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100">


                            <div class="flex items-center gap-3">


                                <i class="bi bi-search text-xl"></i>


                                <span>

                                    Tracking Surat

                                </span>


                            </div>



                            <i class="bi bi-box-arrow-up-right"></i>


                        </a>



                    <?php endif; ?>








                    <?php if (!empty($service['guide_url'])): ?>


                        <a
                            href="<?= htmlspecialchars($service['guide_url']); ?>"
                            target="_blank"
                            class="flex items-center justify-between p-4 rounded-xl bg-yellow-50 text-yellow-700 hover:bg-yellow-100">


                            <div class="flex items-center gap-3">


                                <i class="bi bi-question-circle text-xl"></i>


                                <span>

                                    Panduan

                                </span>


                            </div>



                            <i class="bi bi-box-arrow-up-right"></i>


                        </a>



                    <?php endif; ?>




                </div>


            </div>






        </div>
        <!-- END LEFT -->








        <!-- RIGHT SIDEBAR -->


        <div class="space-y-6">






            <!-- STATUS -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="font-semibold text-lg mb-5">

                    Status

                </h2>





                <?php if ($service['status'] == "Published"): ?>


                    <span class="px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-sm">


                        Published


                    </span>


                <?php else: ?>


                    <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm">


                        Draft


                    </span>


                <?php endif; ?>



            </div>









            <!-- FITUR -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="font-semibold text-lg mb-5">

                    Fitur

                </h2>




                <div class="flex flex-wrap gap-2">



                    <?php if ($service['has_google_form'] == "Yes"): ?>


                        <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700">

                            Google Form

                        </span>


                    <?php endif; ?>






                    <?php if ($service['has_template'] == "Yes"): ?>


                        <span class="px-3 py-1 rounded-full text-xs bg-purple-100 text-purple-700">

                            Template

                        </span>


                    <?php endif; ?>






                    <?php if ($service['has_tracking'] == "Yes"): ?>


                        <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">

                            Tracking

                        </span>


                    <?php endif; ?>



                </div>


            </div>








            <!-- INFORMASI -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="font-semibold text-lg mb-5">

                    Informasi Data

                </h2>



                <div class="space-y-3 text-sm">



                    <div class="flex justify-between">

                        <span class="text-slate-500">

                            ID

                        </span>


                        <span>

                            #<?= $service['id']; ?>

                        </span>


                    </div>





                    <div class="flex justify-between">

                        <span class="text-slate-500">

                            Urutan

                        </span>


                        <span>

                            <?= $service['sort_order']; ?>

                        </span>


                    </div>





                    <div class="flex justify-between">

                        <span class="text-slate-500">

                            Dibuat

                        </span>


                        <span>

                            <?= date(
                                'd M Y',
                                strtotime($service['created_at'])
                            ); ?>

                        </span>


                    </div>



                </div>


            </div>









            <!-- ACTION -->


            <div class="bg-white border rounded-2xl p-6">


                <a
                    href="delete.php?id=<?= $service['id']; ?>"
                    onclick="return confirm('Hapus layanan ini?')"
                    class="block text-center px-5 py-3 rounded-xl bg-red-600 text-white">


                    <i class="bi bi-trash"></i>


                    Hapus Layanan


                </a>


            </div>







        </div>



    </div>




</main>



<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>