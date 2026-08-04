<?php

require_once '../../../config/app.php';


// ===============================
// Validasi ID
// ===============================

if (!isset($_GET['id']) || empty($_GET['id'])) {

    header("Location: index.php");
    exit;
}


$id = (int) $_GET['id'];




// ===============================
// Ambil Data
// ===============================

$query = mysqli_query(
    $conn,

    "
    SELECT *
    FROM village_institutions
    WHERE id='$id'
    LIMIT 1
    "

);



if (!$query || mysqli_num_rows($query) == 0) {


    header("Location: index.php");
    exit;
}



$data = mysqli_fetch_assoc($query);




// ===============================
// Layout
// ===============================

$title = "Detail Lembaga Desa";

$page = "lembaga-desa";


include APP_PATH . 'includes/admin/layout-top.php';


?>



<div class="p-8">



    <!-- HEADER -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h1 class="text-3xl font-bold text-slate-900">

                Detail Lembaga Desa

            </h1>


            <p class="mt-2 text-slate-500">

                Informasi lengkap lembaga atau organisasi desa.

            </p>


        </div>




        <div class="flex gap-3">


            <a

                href="index.php"

                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 hover:bg-slate-50">

                Kembali

            </a>



            <a

                href="edit.php?id=<?= $data['id']; ?>"

                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

                <i class="bi bi-pencil"></i>

                Edit

            </a>


        </div>


    </div>









    <div class="grid gap-8 lg:grid-cols-3">







        <!-- LEFT -->

        <div class="space-y-8 lg:col-span-2">





            <!-- PROFILE CARD -->

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">



                <div class="border-b border-slate-200 p-6">


                    <div class="flex flex-col gap-5 md:flex-row md:items-center">


                        <?php if (!empty($data['image'])): ?>


                            <img

                                src="<?= APP_URL ?>uploads/village/institutions/<?= htmlspecialchars($data['image']); ?>"

                                class="h-32 w-32 rounded-2xl object-cover">


                        <?php else: ?>


                            <div

                                class="flex h-32 w-32 items-center justify-center rounded-2xl bg-teal-100 text-5xl text-teal-600">

                                <i class="bi bi-building"></i>

                            </div>


                        <?php endif; ?>




                        <div>


                            <span class="inline-flex rounded-full bg-teal-100 px-3 py-1 text-sm font-medium text-teal-700">

                                <?= htmlspecialchars($data['category']); ?>

                            </span>



                            <h2 class="mt-4 text-3xl font-bold text-slate-900">

                                <?= htmlspecialchars($data['name']); ?>

                            </h2>



                            <p class="mt-2 text-slate-500">

                                <?= htmlspecialchars($data['slug']); ?>

                            </p>



                        </div>


                    </div>


                </div>







                <div class="p-6">


                    <h3 class="mb-3 text-lg font-semibold text-slate-900">

                        Tentang Lembaga

                    </h3>


                    <div class="leading-8 text-slate-600">


                        <?= nl2br(
                            htmlspecialchars(
                                $data['description'] ?? '-'
                            )
                        ); ?>


                    </div>


                </div>



            </div>








            <!-- PENGURUS -->

            <div class="rounded-2xl border border-slate-200 bg-white">



                <div class="border-b border-slate-200 px-6 py-5">


                    <h3 class="font-semibold text-slate-900">

                        Informasi Pengurus

                    </h3>


                </div>





                <div class="grid gap-6 p-6 md:grid-cols-2">





                    <div>

                        <p class="text-sm text-slate-500">

                            Ketua Lembaga

                        </p>


                        <p class="mt-1 font-medium text-slate-800">

                            <?= htmlspecialchars($data['chairman'] ?? '-'); ?>

                        </p>


                    </div>






                    <div>

                        <p class="text-sm text-slate-500">

                            Sekretaris

                        </p>


                        <p class="mt-1 font-medium text-slate-800">

                            <?= htmlspecialchars($data['secretary'] ?? '-'); ?>

                        </p>


                    </div>







                    <div>

                        <p class="text-sm text-slate-500">

                            Nomor Telepon

                        </p>


                        <p class="mt-1 font-medium text-slate-800">

                            <?= htmlspecialchars($data['phone'] ?? '-'); ?>

                        </p>


                    </div>







                    <div>

                        <p class="text-sm text-slate-500">

                            Email

                        </p>


                        <p class="mt-1 font-medium text-slate-800">

                            <?= htmlspecialchars($data['email'] ?? '-'); ?>

                        </p>


                    </div>




                </div>



            </div>








            <!-- DOCUMENT -->

            <?php if (!empty($data['document'])): ?>


                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b border-slate-200 px-6 py-5">


                        <h3 class="font-semibold text-slate-900">

                            Dokumen Pendukung

                        </h3>


                    </div>




                    <div class="p-6">


                        <a

                            href="<?= APP_URL ?>uploads/village/institutions/<?= $data['document']; ?>"

                            target="_blank"

                            class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-5 py-3 text-white hover:bg-teal-700">

                            <i class="bi bi-file-earmark-text"></i>

                            Lihat Dokumen

                        </a>


                    </div>


                </div>


            <?php endif; ?>




        </div>









        <!-- RIGHT -->

        <div class="space-y-6">





            <!-- STATISTIC -->

            <div class="rounded-2xl border border-slate-200 bg-white">


                <div class="border-b border-slate-200 px-6 py-5">

                    <h3 class="font-semibold text-slate-900">

                        Informasi

                    </h3>

                </div>




                <div class="space-y-5 p-6">



                    <div>

                        <p class="text-sm text-slate-500">

                            Kategori

                        </p>


                        <p class="font-medium text-slate-800">

                            <?= htmlspecialchars($data['category']); ?>

                        </p>


                    </div>






                    <div>

                        <p class="text-sm text-slate-500">

                            Jumlah Anggota

                        </p>


                        <p class="font-medium text-slate-800">

                            <?= number_format($data['total_members']); ?> Orang

                        </p>


                    </div>






                    <div>

                        <p class="text-sm text-slate-500">

                            Status

                        </p>



                        <?php if ($data['status'] == "Active"): ?>


                            <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-700">

                                Aktif

                            </span>



                        <?php else: ?>


                            <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-700">

                                Tidak Aktif

                            </span>


                        <?php endif; ?>



                    </div>






                    <div>

                        <p class="text-sm text-slate-500">

                            Urutan Tampilan

                        </p>


                        <p class="font-medium text-slate-800">

                            <?= $data['sort_order']; ?>

                        </p>


                    </div>





                    <div>

                        <p class="text-sm text-slate-500">

                            Dibuat

                        </p>


                        <p class="font-medium text-slate-800">

                            <?= date(
                                'd F Y H:i',
                                strtotime($data['created_at'])
                            ); ?>

                        </p>


                    </div>






                    <div>

                        <p class="text-sm text-slate-500">

                            Diperbarui

                        </p>


                        <p class="font-medium text-slate-800">

                            <?= date(
                                'd F Y H:i',
                                strtotime($data['updated_at'])
                            ); ?>

                        </p>


                    </div>





                </div>



            </div>







        </div>



    </div>




</div>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>