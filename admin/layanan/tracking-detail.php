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
// Ambil Data Tracking
// ======================================================

$query = mysqli_query($conn, "
    
    SELECT


        letter_trackings.*,


        service_letters.name AS service_name,

        service_letters.icon,

        service_letters.color,

        service_letters.description AS service_description



    FROM letter_trackings



    LEFT JOIN service_letters

    ON service_letters.id = letter_trackings.service_id



    WHERE letter_trackings.id='$id'


    LIMIT 1


");





if (mysqli_num_rows($query) == 0) {


    header("Location:index.php");


    exit;
}



$tracking = mysqli_fetch_assoc($query);





$title = "Detail Tracking Surat";

$page  = "pelayanan-surat";



include APP_PATH . "includes/admin/layout-top.php";

?>





<main class="p-8 space-y-6">






    <!-- HEADER -->


    <div class="flex justify-between items-center">



        <div>


            <h1 class="text-3xl font-bold text-slate-800">


                Detail Pengajuan Surat


            </h1>



            <p class="text-slate-500 mt-2">


                <?= htmlspecialchars($tracking['service_name']); ?>


            </p>



        </div>





        <div class="flex gap-3">



            <a
                href="tracking-edit.php?id=<?= $tracking['id']; ?>"
                class="px-5 py-3 rounded-xl bg-blue-600 text-white">


                <i class="bi bi-pencil"></i>


                Edit


            </a>




            <a
                href="tracking.php?service_id=<?= $tracking['service_id']; ?>"
                class="px-5 py-3 rounded-xl border">


                <i class="bi bi-arrow-left"></i>


                Kembali


            </a>




        </div>


    </div>









    <!-- TOP CARD -->


    <div class="bg-white border rounded-2xl p-6">


        <div class="flex items-center justify-between">





            <div class="flex items-center gap-5">



                <div
                    class="w-16 h-16 rounded-2xl bg-<?= $tracking['color']; ?>-100 flex items-center justify-center">


                    <i class="<?= htmlspecialchars($tracking['icon']); ?> text-3xl text-<?= $tracking['color']; ?>-600"></i>


                </div>





                <div>


                    <h2 class="text-xl font-bold text-slate-800">


                        <?= htmlspecialchars($tracking['service_name']); ?>


                    </h2>


                    <p class="text-slate-500 mt-1">


                        Kode Tracking:

                        <span class="font-semibold text-teal-700">


                            <?= htmlspecialchars($tracking['tracking_code']); ?>


                        </span>


                    </p>


                </div>



            </div>








            <div>



                <?php


                $statusClass = [

                    "Menunggu Verifikasi" => "bg-yellow-100 text-yellow-700",

                    "Diproses" => "bg-blue-100 text-blue-700",

                    "Menunggu Dokumen" => "bg-orange-100 text-orange-700",

                    "Selesai" => "bg-emerald-100 text-emerald-700",

                    "Ditolak" => "bg-red-100 text-red-700"


                ];


                $class = $statusClass[$tracking['status']]
                    ?? "bg-slate-100 text-slate-700";


                ?>



                <span class="px-4 py-2 rounded-full text-sm <?= $class; ?>">


                    <?= htmlspecialchars($tracking['status']); ?>


                </span>



            </div>





        </div>


    </div>







    <div class="grid lg:grid-cols-3 gap-6">






        <!-- LEFT CONTENT -->


        <div class="lg:col-span-2 space-y-6">







            <!-- DATA PEMOHON -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="text-lg font-semibold text-slate-800 mb-5">


                    Data Pemohon


                </h2>





                <div class="grid md:grid-cols-2 gap-5">





                    <div>


                        <p class="text-sm text-slate-500">

                            Nama Pemohon

                        </p>


                        <p class="font-semibold text-slate-800 mt-1">


                            <?= htmlspecialchars($tracking['applicant_name']); ?>


                        </p>


                    </div>








                    <div>


                        <p class="text-sm text-slate-500">

                            NIK

                        </p>


                        <p class="font-semibold text-slate-800 mt-1">


                            <?= $tracking['nik']
                                ? htmlspecialchars($tracking['nik'])
                                : '-'; ?>


                        </p>


                    </div>








                    <div>


                        <p class="text-sm text-slate-500">

                            Nomor HP

                        </p>


                        <p class="font-semibold text-slate-800 mt-1">


                            <?= $tracking['phone']
                                ? htmlspecialchars($tracking['phone'])
                                : '-'; ?>


                        </p>


                    </div>








                    <div>


                        <p class="text-sm text-slate-500">

                            Email

                        </p>


                        <p class="font-semibold text-slate-800 mt-1">


                            <?= $tracking['email']
                                ? htmlspecialchars($tracking['email'])
                                : '-'; ?>


                        </p>


                    </div>






                </div>


            </div>









            <!-- INFORMASI LAYANAN -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="text-lg font-semibold text-slate-800 mb-5">


                    Informasi Layanan


                </h2>





                <p class="text-slate-600 leading-relaxed">


                    <?= nl2br(
                        htmlspecialchars(
                            $tracking['service_description'] ?? '-'
                        )
                    ); ?>


                </p>



            </div>









            <!-- TIMELINE STATUS -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="text-lg font-semibold text-slate-800 mb-6">


                    Progress Pengajuan


                </h2>





                <div class="space-y-5">





                    <?php


                    $steps = [

                        "Menunggu Verifikasi",

                        "Diproses",

                        "Menunggu Dokumen",

                        "Selesai"

                    ];



                    $current = array_search(
                        $tracking['status'],
                        $steps
                    );



                    foreach ($steps as $index => $step):

                    ?>


                        <div class="flex gap-4">





                            <div
                                class="
w-10 h-10 rounded-full flex items-center justify-center

<?=

                        $index <= $current

                            ?

                            'bg-emerald-600 text-white'

                            :

                            'bg-slate-100 text-slate-400'

?>

">


                                <?php if ($index <= $current): ?>


                                    <i class="bi bi-check-lg"></i>


                                <?php else: ?>


                                    <?= $index + 1; ?>


                                <?php endif; ?>


                            </div>








                            <div>


                                <p class="font-medium

<?=

                        $index <= $current

                            ?

                            'text-slate-800'

                            :

                            'text-slate-400'

?>

">


                                    <?= $step; ?>


                                </p>




                                <?php if ($tracking['status'] == $step): ?>


                                    <p class="text-sm text-teal-600 mt-1">

                                        Status saat ini

                                    </p>


                                <?php endif; ?>



                            </div>



                        </div>




                    <?php endforeach; ?>





                </div>


            </div>









            <!-- CATATAN -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="text-lg font-semibold text-slate-800 mb-4">


                    Catatan


                </h2>



                <?php if ($tracking['notes']): ?>


                    <p class="text-slate-600 whitespace-pre-line">


                        <?= htmlspecialchars($tracking['notes']); ?>


                    </p>



                <?php else: ?>


                    <p class="text-slate-400">


                        Tidak ada catatan.


                    </p>



                <?php endif; ?>



            </div>







        </div>








        <!-- SIDEBAR -->


        <div class="space-y-6">






            <!-- WAKTU -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="font-semibold text-lg mb-5">


                    Informasi Waktu


                </h2>





                <div class="space-y-4 text-sm">





                    <div class="flex justify-between">


                        <span class="text-slate-500">

                            Diajukan

                        </span>


                        <span class="font-medium">


                            <?= date(
                                'd M Y H:i',
                                strtotime($tracking['submitted_at'])
                            ); ?>


                        </span>


                    </div>







                    <div class="flex justify-between">


                        <span class="text-slate-500">

                            Selesai

                        </span>



                        <span class="font-medium">


                            <?= $tracking['completed_at']

                                ?

                                date(
                                    'd M Y H:i',
                                    strtotime($tracking['completed_at'])
                                )

                                :

                                '-';

                            ?>


                        </span>


                    </div>







                </div>


            </div>








            <!-- DATA -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="font-semibold text-lg mb-5">


                    Data


                </h2>




                <div class="space-y-3 text-sm">



                    <div class="flex justify-between">

                        <span class="text-slate-500">

                            ID

                        </span>


                        <span>

                            #<?= $tracking['id']; ?>

                        </span>


                    </div>





                    <div class="flex justify-between">

                        <span class="text-slate-500">

                            Service ID

                        </span>


                        <span>

                            <?= $tracking['service_id']; ?>

                        </span>


                    </div>






                </div>


            </div>








            <!-- ACTION -->


            <div class="bg-white border rounded-2xl p-6">


                <a
                    href="tracking-delete.php?id=<?= $tracking['id']; ?>"
                    onclick="return confirm('Hapus pengajuan ini?')"
                    class="block text-center px-5 py-3 rounded-xl bg-red-600 text-white">


                    <i class="bi bi-trash"></i>


                    Hapus Pengajuan


                </a>


            </div>





        </div>






    </div>





</main>





<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>