<?php

require_once '../../../config/app.php';
require_once APP_PATH . 'config/database.php';


// ===============================
// Validasi Slug
// ===============================

if (!isset($_GET['slug']) || empty($_GET['slug'])) {

    header("Location: index.php");
    exit;
}


$slug = mysqli_real_escape_string($conn, $_GET['slug']);


// ===============================
// Ambil Data Produk Hukum
// ===============================

$query = mysqli_query($conn, "

    SELECT
        l.*,
        u.username AS author

    FROM legal_instruments l

    LEFT JOIN users u
        ON u.id = l.created_by

    WHERE l.slug = '$slug'

    LIMIT 1

");


if (!$query || mysqli_num_rows($query) == 0) {

    header("Location: index.php");
    exit;
}


$data = mysqli_fetch_assoc($query);



$title = "Detail Produk Hukum";
$page  = "produk-hukum";


include APP_PATH . 'includes/admin/layout-top.php';

?>


<div class="p-8">


    <!-- Header -->
    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h2 class="text-3xl font-bold text-slate-900">
                Detail Produk Hukum
            </h2>

            <p class="mt-2 text-slate-500">
                Informasi lengkap dokumen produk hukum desa.
            </p>

        </div>


        <div class="flex gap-3">

            <a
                href="index.php"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 transition hover:bg-slate-50">

                Kembali

            </a>


            <a
                href="edit.php?slug=<?= urlencode($slug); ?>"
                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white transition hover:bg-teal-700">

                Edit Produk Hukum

            </a>


        </div>


    </div>



    <div class="grid gap-8 lg:grid-cols-3">



        <!-- LEFT -->
        <div class="space-y-8 lg:col-span-2">


            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">


                <!-- Header -->
                <div class="border-b border-slate-200 px-6 py-5">


                    <span
                        class="inline-flex rounded-full bg-teal-100 px-3 py-1 text-sm font-medium text-teal-700">

                        <?= htmlspecialchars($data['category'], ENT_QUOTES, 'UTF-8'); ?>

                    </span>


                    <h1 class="mt-4 text-3xl font-bold text-slate-900">

                        <?= htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8'); ?>

                    </h1>



                    <?php if (!empty($data['description'])): ?>

                        <p class="mt-4 text-slate-600">

                            <?= nl2br(htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8')); ?>

                        </p>

                    <?php endif; ?>


                </div>




                <!-- Detail Dokumen -->
                <div class="space-y-6 p-6">


                    <div class="grid gap-5 md:grid-cols-2">


                        <div>

                            <p class="text-sm text-slate-500">
                                Nomor Dokumen
                            </p>

                            <p class="font-medium text-slate-800">

                                <?= htmlspecialchars($data['document_number'] ?: '-', ENT_QUOTES, 'UTF-8'); ?>

                            </p>

                        </div>



                        <div>

                            <p class="text-sm text-slate-500">
                                Tahun
                            </p>

                            <p class="font-medium text-slate-800">

                                <?= htmlspecialchars($data['document_year'] ?: '-', ENT_QUOTES, 'UTF-8'); ?>

                            </p>

                        </div>



                        <div>

                            <p class="text-sm text-slate-500">
                                Tanggal Berlaku
                            </p>

                            <p class="font-medium text-slate-800">

                                <?= !empty($data['effective_date'])
                                    ? date('d F Y', strtotime($data['effective_date']))
                                    : '-';
                                ?>

                            </p>

                        </div>



                        <div>

                            <p class="text-sm text-slate-500">
                                Ukuran File
                            </p>

                            <p class="font-medium text-slate-800">

                                <?= !empty($data['file_size'])
                                    ? round($data['file_size'] / 1024, 2) . ' KB'
                                    : '-';
                                ?>

                            </p>

                        </div>


                    </div>



                    <!-- PDF -->
                    <?php if (!empty($data['file'])): ?>


                        <div class="rounded-xl bg-slate-50 p-5">


                            <div class="flex items-center justify-between">


                                <div class="flex items-center gap-3">

                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100">

                                        <i class="bi bi-file-earmark-pdf text-2xl text-red-600"></i>

                                    </div>


                                    <div>

                                        <p class="font-semibold text-slate-800">

                                            Dokumen PDF

                                        </p>


                                        <p class="text-sm text-slate-500">

                                            <?= htmlspecialchars($data['file']); ?>

                                        </p>


                                    </div>


                                </div>

                                <div class="flex gap-2">

                                    <a
                                        href="<?= APP_URL . 'uploads/informasi/produk-hukum/' . $data['file']; ?>"
                                        target="_blank"
                                        class="rounded-xl border-red-200 border bg-red-50 px-5 py-3 text-red-500 transition hover:bg-red-600 hover:text-white">


                                        <i class="bi bi-eye"></i>

                                        Lihat PDF


                                    </a>

                                    <a
                                        href="download.php?slug=<?= urlencode($data['slug']); ?>"
                                        class="rounded-xl bg-red-600 px-5 py-3 text-white transition hover:bg-red-700">

                                        <i class="bi bi-download"></i>

                                        Download PDF

                                    </a>
                                </div>


                            </div>


                        </div>


                    <?php endif; ?>


                </div>


            </div>


        </div>





        <!-- RIGHT -->
        <div class="space-y-6">


            <div class="rounded-2xl border border-slate-200 bg-white">


                <div class="border-b border-slate-200 px-6 py-5">

                    <h3 class="font-semibold text-slate-900">

                        Informasi

                    </h3>

                </div>



                <div class="space-y-5 p-6">


                    <div>

                        <p class="text-sm text-slate-500">
                            Dibuat Oleh
                        </p>


                        <p class="font-medium text-slate-800">

                            <?= htmlspecialchars($data['author'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>

                        </p>


                    </div>



                    <div>

                        <p class="text-sm text-slate-500">
                            Status
                        </p>


                        <span
                            class="inline-flex rounded-full px-3 py-1 text-sm font-medium
                            <?= $data['status'] === 'Published'
                                ? 'bg-emerald-100 text-emerald-700'
                                : 'bg-yellow-100 text-yellow-700';
                            ?>">


                            <?= htmlspecialchars($data['status']); ?>


                        </span>


                    </div>



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
                            Jumlah Download
                        </p>


                        <p class="font-medium text-slate-800">

                            <?= number_format($data['download_count']); ?> kali

                        </p>


                    </div>



                    <div>

                        <p class="text-sm text-slate-500">
                            Dibuat
                        </p>


                        <p class="font-medium text-slate-800">

                            <?= date('d F Y H:i', strtotime($data['created_at'])); ?>

                        </p>


                    </div>



                    <div>

                        <p class="text-sm text-slate-500">
                            Terakhir Diperbarui
                        </p>


                        <p class="font-medium text-slate-800">

                            <?= date('d F Y H:i', strtotime($data['updated_at'])); ?>

                        </p>


                    </div>



                    <div>

                        <p class="text-sm text-slate-500">
                            Slug
                        </p>


                        <div class="break-all rounded-xl bg-slate-100 p-3 text-sm text-slate-700">

                            <?= htmlspecialchars($data['slug']); ?>

                        </div>


                    </div>



                </div>


            </div>


        </div>


    </div>


</div>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>