<?php

require_once '../../../config/app.php';


// =====================================
// Validasi Slug
// =====================================

if (!isset($_GET['slug']) || empty($_GET['slug'])) {

    header("Location:index.php");
    exit;
}



$slug = mysqli_real_escape_string(
    $conn,
    $_GET['slug']
);





// =====================================
// Ambil Data
// =====================================

$query = mysqli_query(
    $conn,
    "
    SELECT

        f.*,

        u.username AS author


    FROM financial_managements f


    LEFT JOIN users u

    ON u.id = f.created_by



    WHERE f.slug='$slug'


    LIMIT 1

    "
);





if (!$query || mysqli_num_rows($query) == 0) {


    $_SESSION['error'] =
        "Data keuangan tidak ditemukan.";


    header("Location:index.php");
    exit;
}




$data = mysqli_fetch_assoc($query);





// =====================================
// Hitung Persentase Realisasi
// =====================================


$percentage = 0;


if ($data['total_budget'] > 0) {

    $percentage =

        ($data['realization'] /
            $data['total_budget']) * 100;
}



if ($percentage > 100) {

    $percentage = 100;
}





$title = "Detail Pengelolaan Keuangan";

$page = "pengelolaan-keuangan";


include APP_PATH . 'includes/admin/layout-top.php';

?>



<div class="p-8">





    <!-- HEADER -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h2 class="text-3xl font-bold text-slate-900">

                Detail Pengelolaan Keuangan

            </h2>


            <p class="mt-2 text-slate-500">

                Informasi lengkap laporan keuangan desa.

            </p>


        </div>





        <div class="flex gap-3">


            <a

                href="index.php"

                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 hover:bg-slate-50">

                Kembali

            </a>



            <a

                href="edit.php?slug=<?= $data['slug']; ?>"

                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

                Edit Data

            </a>



        </div>


    </div>









    <div class="grid gap-8 lg:grid-cols-3">






        <!-- LEFT -->

        <div class="space-y-8 lg:col-span-2">





            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">



                <div class="border-b px-6 py-5">



                    <span class="inline-flex rounded-full bg-teal-100 px-3 py-1 text-sm font-medium text-teal-700">

                        <?= htmlspecialchars($data['category']); ?>

                    </span>




                    <h1 class="mt-4 text-3xl font-bold text-slate-900">

                        <?= htmlspecialchars($data['title']); ?>

                    </h1>




                    <?php if (!empty($data['description'])): ?>


                        <p class="mt-4 text-slate-600">

                            <?= nl2br(
                                htmlspecialchars($data['description'])
                            ); ?>

                        </p>


                    <?php endif; ?>



                </div>









                <!-- Statistik -->

                <div class="grid gap-5 p-6 md:grid-cols-2">





                    <div class="rounded-xl bg-slate-50 p-5">


                        <p class="text-sm text-slate-500">

                            Total Anggaran

                        </p>


                        <h3 class="mt-2 text-2xl font-bold text-slate-900">

                            Rp <?= number_format($data['total_budget']); ?>

                        </h3>


                    </div>








                    <div class="rounded-xl bg-emerald-50 p-5">


                        <p class="text-sm text-slate-500">

                            Realisasi

                        </p>


                        <h3 class="mt-2 text-2xl font-bold text-emerald-700">

                            Rp <?= number_format($data['realization']); ?>

                        </h3>


                    </div>






                </div>








                <!-- Progress -->


                <div class="px-6 pb-6">


                    <div class="mb-2 flex justify-between">


                        <p class="font-medium text-slate-700">

                            Progress Realisasi

                        </p>


                        <span class="font-semibold text-teal-600">

                            <?= number_format($percentage, 2); ?>%

                        </span>


                    </div>




                    <div class="h-3 overflow-hidden rounded-full bg-slate-200">


                        <div

                            class="h-full rounded-full bg-teal-600"

                            style="width:<?= $percentage; ?>%">

                        </div>


                    </div>


                </div>









            </div>






            <!-- Dokumen -->

            <div class="rounded-2xl border bg-white">



                <div class="border-b px-6 py-5">


                    <h3 class="font-semibold text-slate-900">

                        Dokumen Pendukung

                    </h3>


                </div>





                <div class="p-6">



                    <?php if (!empty($data['file'])): ?>


                        <a

                            href="download.php?slug=<?= $data['slug']; ?>"

                            class="inline-flex items-center gap-3 rounded-xl bg-red-500 px-5 py-3 font-medium text-white hover:bg-red-600">


                            <i class="bi bi-file-earmark-pdf"></i>


                            Download PDF


                        </a>



                        <p class="mt-3 text-sm text-slate-500">

                            <?= htmlspecialchars($data['file']); ?>

                        </p>


                    <?php else: ?>


                        <p class="text-slate-500">

                            Tidak ada dokumen.


                        </p>


                    <?php endif; ?>



                </div>



            </div>








        </div>









        <!-- RIGHT -->

        <div class="space-y-6">






            <div class="rounded-2xl border bg-white">


                <div class="border-b px-6 py-5">


                    <h3 class="font-semibold text-slate-900">

                        Informasi

                    </h3>


                </div>







                <div class="space-y-5 p-6">






                    <div>

                        <p class="text-sm text-slate-500">

                            Kategori

                        </p>


                        <p class="font-medium">

                            <?= htmlspecialchars($data['category']); ?>

                        </p>


                    </div>







                    <div>

                        <p class="text-sm text-slate-500">

                            Tahun Anggaran

                        </p>


                        <p class="font-medium">

                            <?= $data['fiscal_year']; ?>

                        </p>


                    </div>







                    <div>

                        <p class="text-sm text-slate-500">

                            Sumber Dana

                        </p>


                        <p class="font-medium">

                            <?= htmlspecialchars($data['funding_source']); ?>

                        </p>


                    </div>







                    <div>

                        <p class="text-sm text-slate-500">

                            Status

                        </p>



                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium

<?= $data['status'] == 'Published'

    ? 'bg-emerald-100 text-emerald-700'

    : 'bg-yellow-100 text-yellow-700'; ?>">


                            <?= $data['status']; ?>


                        </span>


                    </div>







                    <div>

                        <p class="text-sm text-slate-500">

                            Dibuat Oleh

                        </p>


                        <p class="font-medium">

                            <?= htmlspecialchars($data['author'] ?? '-'); ?>

                        </p>


                    </div>







                    <div>

                        <p class="text-sm text-slate-500">

                            Tanggal Dibuat

                        </p>


                        <p class="font-medium">

                            <?= date(
                                'd F Y H:i',
                                strtotime($data['created_at'])
                            ); ?>

                        </p>


                    </div>







                    <div>

                        <p class="text-sm text-slate-500">

                            Terakhir Update

                        </p>


                        <p class="font-medium">

                            <?= date(
                                'd F Y H:i',
                                strtotime($data['updated_at'])
                            ); ?>

                        </p>


                    </div>







                    <div>


                        <p class="text-sm text-slate-500">

                            Slug

                        </p>


                        <div class="break-all rounded-xl bg-slate-100 p-3 text-sm">

                            <?= htmlspecialchars($data['slug']); ?>

                        </div>


                    </div>






                </div>



            </div>





        </div>









    </div>





</div>


<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>