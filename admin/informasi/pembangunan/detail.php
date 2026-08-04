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
        p.*,
        u.username AS author

    FROM constructions p

    LEFT JOIN users u
    ON u.id = p.created_by

    WHERE p.slug='$slug'

    LIMIT 1
    "
);



if (!$query || mysqli_num_rows($query) == 0) {


    $_SESSION['error'] =
        "Data pembangunan tidak ditemukan.";


    header("Location:index.php");
    exit;
}



$data = mysqli_fetch_assoc($query);




$title = "Detail Pembangunan";

$page = "pembangunan";


include APP_PATH . 'includes/admin/layout-top.php';


?>



<div class="p-8">



    <!-- Header -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h2 class="text-3xl font-bold text-slate-900">

                Detail Pembangunan

            </h2>


            <p class="mt-2 text-slate-500">

                Informasi lengkap kegiatan pembangunan desa.

            </p>


        </div>



        <div class="flex gap-3">


            <a

                href="index.php"

                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 hover:bg-slate-50">

                Kembali

            </a>




            <a

                href="edit.php?slug=<?= urlencode($data['slug']); ?>"

                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

                Edit Pembangunan

            </a>


        </div>


    </div>







    <div class="grid gap-8 lg:grid-cols-3">






        <!-- LEFT -->

        <div class="space-y-8 lg:col-span-2">





            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">





                <?php if (!empty($data['thumbnail'])): ?>


                    <img

                        src="<?= APP_URL .
                                    'uploads/informasi/pembangunan/' .
                                    htmlspecialchars($data['thumbnail']); ?>"

                        alt="<?= htmlspecialchars($data['title']); ?>"

                        class="h-[420px] w-full object-cover">


                <?php endif; ?>






                <div class="border-b border-slate-200 p-6">



                    <span class="inline-flex rounded-full bg-teal-100 px-3 py-1 text-sm font-medium text-teal-700">


                        <?= htmlspecialchars($data['category']); ?>


                    </span>





                    <h1 class="mt-4 text-3xl font-bold text-slate-900">

                        <?= htmlspecialchars($data['title']); ?>

                    </h1>





                    <p class="mt-3 text-slate-500">

                        <?= nl2br(htmlspecialchars($data['description'])); ?>

                    </p>




                </div>






                <!-- Progress -->


                <div class="p-6">


                    <div class="mb-2 flex justify-between">


                        <span class="font-medium text-slate-700">

                            Progress Pembangunan

                        </span>


                        <span class="font-bold text-teal-600">

                            <?= $data['progress']; ?>%

                        </span>


                    </div>




                    <div class="h-4 rounded-full bg-slate-200">


                        <div

                            class="h-4 rounded-full bg-teal-600"

                            style="width:<?= $data['progress']; ?>%">


                        </div>


                    </div>


                </div>






            </div>






        </div>









        <!-- RIGHT -->

        <div class="space-y-6">





            <div class="rounded-2xl border border-slate-200 bg-white">


                <div class="border-b border-slate-200 px-6 py-5">


                    <h3 class="font-semibold text-slate-900">

                        Informasi Pembangunan

                    </h3>


                </div>






                <div class="space-y-5 p-6">





                    <div>

                        <p class="text-sm text-slate-500">

                            Lokasi

                        </p>


                        <p class="font-medium text-slate-800">

                            <?= htmlspecialchars($data['location']); ?>

                        </p>


                    </div>







                    <div>

                        <p class="text-sm text-slate-500">

                            Tahun

                        </p>


                        <p class="font-medium text-slate-800">

                            <?= $data['year']; ?>

                        </p>


                    </div>







                    <div>

                        <p class="text-sm text-slate-500">

                            Anggaran

                        </p>


                        <p class="font-medium text-slate-800">

                            Rp <?= number_format($data['budget'], 0, ',', '.'); ?>

                        </p>


                    </div>







                    <div>

                        <p class="text-sm text-slate-500">

                            Sumber Dana

                        </p>


                        <p class="font-medium text-slate-800">

                            <?= htmlspecialchars($data['funding_source']); ?>

                        </p>


                    </div>






                    <div>

                        <p class="text-sm text-slate-500">

                            Volume

                        </p>


                        <p class="font-medium text-slate-800">

                            <?= htmlspecialchars($data['volume']); ?>

                        </p>


                    </div>








                    <div>

                        <p class="text-sm text-slate-500">

                            Status

                        </p>


                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium

<?= $data['status'] == "Selesai"

    ?

    'bg-emerald-100 text-emerald-700'

    : (
        $data['status'] == "Berjalan"

        ?

        'bg-amber-100 text-amber-700'

        :

        'bg-slate-100 text-slate-700'

    )

?>">


                            <?= htmlspecialchars($data['status']); ?>


                        </span>


                    </div>






                    <div>

                        <p class="text-sm text-slate-500">

                            Tanggal Pelaksanaan

                        </p>


                        <p class="font-medium text-slate-800">


                            <?=

                            $data['start_date']
                                ?

                                date(
                                    'd F Y',
                                    strtotime($data['start_date'])
                                )

                                :

                                '-'

                            ?>


                            s/d


                            <?=

                            $data['end_date']
                                ?

                                date(
                                    'd F Y',
                                    strtotime($data['end_date'])
                                )

                                :

                                '-'

                            ?>



                        </p>


                    </div>






                </div>


            </div>








            <!-- Metadata -->


            <div class="rounded-2xl border border-slate-200 bg-white">


                <div class="border-b border-slate-200 px-6 py-5">


                    <h3 class="font-semibold text-slate-900">

                        Metadata

                    </h3>


                </div>




                <div class="space-y-5 p-6">





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

                            Terakhir Diperbarui

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