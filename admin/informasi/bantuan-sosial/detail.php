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
// Ambil Data Bantuan
// =====================================


$query = mysqli_query(

    $conn,

    "
    SELECT

        s.*,

        u.username AS author


    FROM social_assistances s


    LEFT JOIN users u

    ON u.id = s.created_by


    WHERE s.slug='$slug'


    LIMIT 1

    "

);



if (!$query || mysqli_num_rows($query) == 0) {


    header("Location:index.php");
    exit;
}



$data = mysqli_fetch_assoc($query);







// =====================================
// Statistik Penerima
// =====================================


$totalRecipient = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "
        SELECT COUNT(*) total

        FROM social_assistance_recipients

        WHERE assistance_id='{$data['id']}'

        "

    )

)['total'];








// =====================================
// Data Penerima Terbaru
// =====================================


$recipients = mysqli_query(

    $conn,

    "
    SELECT *

    FROM social_assistance_recipients

    WHERE assistance_id='{$data['id']}'

    ORDER BY created_at DESC

    LIMIT 5

    "

);







$title = "Detail Bantuan Sosial";

$page = "bantuan-sosial";


include APP_PATH . 'includes/admin/layout-top.php';

?>



<div class="p-8">



    <!-- HEADER -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h2 class="text-3xl font-bold text-slate-900">

                Detail Bantuan Sosial

            </h2>


            <p class="mt-2 text-slate-500">

                Informasi lengkap program bantuan sosial desa.

            </p>


        </div>




        <div class="flex gap-3">


            <a

                href="index.php"

                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700">

                Kembali

            </a>




            <a

                href="edit.php?slug=<?= $data['slug']; ?>"

                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white">

                Edit

            </a>


        </div>


    </div>









    <div class="grid gap-8 lg:grid-cols-3">






        <!-- LEFT -->

        <div class="space-y-8 lg:col-span-2">





            <div class="rounded-2xl border bg-white overflow-hidden">


                <div class="border-b px-6 py-5">


                    <span class="inline-flex rounded-full bg-teal-100 px-3 py-1 text-sm font-medium text-teal-700">

                        <?= htmlspecialchars($data['category']); ?>

                    </span>



                    <h1 class="mt-4 text-3xl font-bold text-slate-900">

                        <?= htmlspecialchars($data['title']); ?>

                    </h1>


                </div>





                <div class="p-6 space-y-6">


                    <div>


                        <h3 class="font-semibold text-slate-900">

                            Deskripsi Program

                        </h3>


                        <p class="mt-3 leading-7 text-slate-600">

                            <?= nl2br(htmlspecialchars($data['description'])); ?>

                        </p>


                    </div>






                    <div class="grid gap-5 md:grid-cols-2">


                        <div class="rounded-xl bg-slate-50 p-5">


                            <p class="text-sm text-slate-500">

                                Tahun Program

                            </p>


                            <p class="mt-2 text-xl font-bold">

                                <?= $data['year']; ?>

                            </p>


                        </div>





                        <div class="rounded-xl bg-slate-50 p-5">


                            <p class="text-sm text-slate-500">

                                Jumlah Anggaran

                            </p>


                            <p class="mt-2 text-xl font-bold">

                                Rp <?= number_format($data['total_budget'], 0, ',', '.'); ?>

                            </p>


                        </div>


                    </div>







                    <?php if (!empty($data['document_file'])): ?>


                        <div>


                            <h3 class="mb-3 font-semibold">

                                Dokumen Pendukung

                            </h3>


                            <a

                                href="download.php?slug=<?= $data['slug']; ?>"

                                class="inline-flex items-center gap-2 rounded-xl bg-red-500 px-5 py-3 text-white">


                                <i class="bi bi-file-earmark-pdf"></i>


                                Download PDF


                            </a>


                        </div>


                    <?php endif; ?>





                </div>


            </div>










            <!-- PENERIMA -->

            <div class="rounded-2xl border bg-white">


                <div class="flex items-center justify-between border-b px-6 py-5">


                    <h3 class="font-semibold">

                        Penerima Bantuan

                    </h3>



                    <a

                        href="penerima.php?id=<?= $data['id']; ?>"

                        class="text-sm text-teal-600">

                        Kelola Semua

                    </a>


                </div>





                <div class="divide-y">


                    <?php if (mysqli_num_rows($recipients) > 0): ?>



                        <?php while ($item = mysqli_fetch_assoc($recipients)): ?>


                            <div class="px-6 py-4">


                                <h4 class="font-medium">

                                    <?= htmlspecialchars($item['name']); ?>

                                </h4>


                                <p class="text-sm text-slate-500">

                                    <?= htmlspecialchars($item['nik']); ?>

                                </p>


                            </div>



                        <?php endwhile; ?>



                    <?php else: ?>


                        <div class="px-6 py-10 text-center text-slate-500">


                            Belum ada penerima bantuan.


                        </div>



                    <?php endif; ?>


                </div>



            </div>







        </div>









        <!-- RIGHT -->

        <div class="space-y-6">





            <div class="rounded-2xl border bg-white">


                <div class="border-b px-6 py-5">


                    <h3 class="font-semibold">

                        Informasi Program

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

                            Sumber Dana

                        </p>


                        <p class="font-medium">

                            <?= htmlspecialchars($data['funding_source']); ?>

                        </p>


                    </div>






                    <div>

                        <p class="text-sm text-slate-500">

                            Jumlah Penerima

                        </p>


                        <p class="font-medium">

                            <?= number_format($totalRecipient); ?> Orang

                        </p>


                    </div>







                    <div>

                        <p class="text-sm text-slate-500">

                            Status

                        </p>


                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium

<?= $data['status'] == "Published"

    ?

    'bg-emerald-100 text-emerald-700'

    :

    'bg-yellow-100 text-yellow-700';

?>">


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