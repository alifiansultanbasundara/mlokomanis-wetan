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

        a.*,

        u.username AS author


    FROM village_assets a


    LEFT JOIN users u

    ON u.id = a.created_by


    WHERE a.slug='$slug'


    LIMIT 1

    "
);





if (!$query || mysqli_num_rows($query) == 0) {


    $_SESSION['error'] =
        "Data aset tidak ditemukan.";


    header("Location:index.php");
    exit;
}




$data = mysqli_fetch_assoc($query);





$title = "Detail Aset Desa";

$page = "aset-desa";


include APP_PATH .
    'includes/admin/layout-top.php';

?>





<div class="p-8">





    <!-- HEADER -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>


            <h2 class="text-3xl font-bold text-slate-900">

                Detail Aset Desa

            </h2>


            <p class="mt-2 text-slate-500">

                Informasi lengkap aset milik desa.

            </p>


        </div>




        <div class="flex gap-3">


            <a

                href="index.php"

                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700">


                Kembali


            </a>




            <a

                href="edit.php?slug=<?= urlencode($data['slug']); ?>"

                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white">


                Edit Aset


            </a>



        </div>



    </div>









    <div class="grid gap-8 lg:grid-cols-3">







        <!-- LEFT -->

        <div class="space-y-8 lg:col-span-2">





            <div class="rounded-2xl border bg-white">



                <div class="border-b px-6 py-5">



                    <span class="inline-flex rounded-full bg-teal-100 px-3 py-1 text-sm font-medium text-teal-700">

                        <?= htmlspecialchars($data['category']); ?>

                    </span>




                    <h1 class="mt-4 text-3xl font-bold text-slate-900">

                        <?= htmlspecialchars($data['title']); ?>

                    </h1>




                    <?php if (!empty($data['description'])): ?>


                        <p class="mt-4 leading-7 text-slate-600">

                            <?= nl2br(
                                htmlspecialchars($data['description'])
                            ); ?>

                        </p>


                    <?php endif; ?>



                </div>








                <div class="p-6">



                    <div class="grid gap-6 md:grid-cols-2">





                        <div class="rounded-xl bg-slate-50 p-5">


                            <p class="text-sm text-slate-500">

                                Kode Aset

                            </p>


                            <p class="mt-1 font-semibold">

                                <?= htmlspecialchars($data['asset_code']); ?>

                            </p>


                        </div>






                        <div class="rounded-xl bg-slate-50 p-5">


                            <p class="text-sm text-slate-500">

                                Lokasi

                            </p>


                            <p class="mt-1 font-semibold">

                                <?= htmlspecialchars($data['location']); ?>

                            </p>


                        </div>







                        <div class="rounded-xl bg-slate-50 p-5">


                            <p class="text-sm text-slate-500">

                                Tahun Perolehan

                            </p>


                            <p class="mt-1 font-semibold">

                                <?= $data['acquisition_year'] ?: '-'; ?>

                            </p>


                        </div>







                        <div class="rounded-xl bg-slate-50 p-5">


                            <p class="text-sm text-slate-500">

                                Kondisi

                            </p>


                            <p class="mt-1 font-semibold">

                                <?= htmlspecialchars($data['condition_status']); ?>

                            </p>


                        </div>







                    </div>



                </div>



            </div>









            <!-- Dokumen -->


            <?php if (!empty($data['document_file'])): ?>


                <div class="rounded-2xl border bg-white">


                    <div class="border-b px-6 py-5">


                        <h3 class="font-semibold text-slate-900">

                            Dokumen Aset

                        </h3>


                    </div>





                    <div class="p-6">



                        <a

                            href="download.php?file=<?= urlencode($data['document_file']); ?>"

                            class="inline-flex items-center gap-2 rounded-xl bg-red-500 px-5 py-3 text-white hover:bg-red-600">


                            <i class="bi bi-file-earmark-pdf"></i>


                            Download Dokumen PDF


                        </a>



                    </div>



                </div>


            <?php endif; ?>









        </div>









        <!-- RIGHT -->

        <div class="space-y-6">





            <div class="rounded-2xl border bg-white">



                <div class="border-b px-6 py-5">


                    <h3 class="font-semibold">

                        Informasi Aset

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

                            Status Kepemilikan

                        </p>


                        <p class="font-medium">

                            <?= htmlspecialchars($data['ownership_status']); ?>

                        </p>


                    </div>








                    <div>

                        <p class="text-sm text-slate-500">

                            Nilai Perolehan

                        </p>


                        <p class="font-medium text-teal-600">


                            Rp <?= number_format(
                                    $data['acquisition_value'],
                                    0,
                                    ',',
                                    '.'
                                ); ?>


                        </p>


                    </div>








                    <div>

                        <p class="text-sm text-slate-500">

                            Nilai Saat Ini

                        </p>


                        <p class="font-medium">

                            Rp <?= number_format(
                                    $data['current_value'],
                                    0,
                                    ',',
                                    '.'
                                ); ?>


                        </p>


                    </div>








                    <div>

                        <p class="text-sm text-slate-500">

                            Status Publikasi

                        </p>



                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium

<?= $data['status'] == 'Published'

    ? 'bg-emerald-100 text-emerald-700'

    : 'bg-yellow-100 text-yellow-700';

?>">


                            <?= $data['status']; ?>


                        </span>


                    </div>








                    <div>

                        <p class="text-sm text-slate-500">

                            Dibuat Oleh

                        </p>


                        <p class="font-medium">

                            <?= htmlspecialchars(
                                $data['author'] ?? '-'
                            ); ?>

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






<?php include APP_PATH .
    'includes/admin/layout-bottom.php'; ?>