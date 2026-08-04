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
    SELECT *

    FROM financial_managements

    WHERE slug='$slug'

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



$title = "Edit Pengelolaan Keuangan";

$page = "pengelolaan-keuangan";


include APP_PATH . 'includes/admin/layout-top.php';


?>



<div class="p-8">


    <form

        action="update.php"

        method="POST"

        enctype="multipart/form-data"

        class="space-y-8">



        <input

            type="hidden"

            name="slug"

            value="<?= htmlspecialchars($data['slug']); ?>">







        <!-- HEADER -->

        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


            <div>

                <h2 class="text-3xl font-bold text-slate-900">

                    Edit Pengelolaan Keuangan

                </h2>


                <p class="mt-2 text-slate-500">

                    Perbarui data laporan keuangan desa.

                </p>


            </div>




            <div class="flex gap-3">


                <a

                    href="index.php"

                    class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 hover:bg-slate-50">

                    Kembali

                </a>




                <button

                    type="submit"

                    class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

                    Update

                </button>



            </div>


        </div>









        <div class="grid gap-8 lg:grid-cols-3">






            <!-- LEFT -->

            <div class="space-y-8 lg:col-span-2">





                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Informasi Keuangan

                        </h3>

                    </div>





                    <div class="space-y-5 p-6">





                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Judul Laporan

                            </label>


                            <input

                                type="text"

                                id="title"

                                name="title"

                                required

                                value="<?= htmlspecialchars($data['title']); ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>







                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Slug

                            </label>



                            <input

                                name="slug_display"

                                readonly

                                value="<?= htmlspecialchars($data['slug']); ?>"

                                class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-500">


                        </div>







                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Deskripsi

                            </label>


                            <textarea

                                name="description"

                                rows="5"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3"><?= htmlspecialchars($data['description']); ?></textarea>


                        </div>







                    </div>


                </div>








                <!-- File -->

                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Dokumen

                        </h3>

                    </div>





                    <div class="space-y-5 p-6">





                        <?php if (!empty($data['file'])): ?>


                            <div class="rounded-xl bg-slate-100 p-4">


                                <p class="text-sm text-slate-500">

                                    File Saat Ini

                                </p>


                                <a

                                    target="_blank"

                                    href="<?= APP_URL .
                                                'uploads/informasi/pengelolaan-keuangan/' .
                                                $data['file']; ?>"

                                    class="mt-2 inline-flex items-center gap-2 font-medium text-teal-600">


                                    <i class="bi bi-file-earmark-pdf"></i>

                                    <?= htmlspecialchars($data['file']); ?>


                                </a>


                            </div>


                        <?php endif; ?>







                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Ganti File PDF

                            </label>



                            <input

                                type="file"

                                name="file"

                                accept=".pdf"

                                class="block w-full rounded-xl border border-slate-300 px-4 py-3">


                            <p class="mt-2 text-sm text-slate-500">

                                Kosongkan jika tidak ingin mengganti file.

                            </p>


                        </div>






                    </div>


                </div>







            </div>









            <!-- RIGHT -->

            <div class="space-y-8">





                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Detail Keuangan

                        </h3>


                    </div>





                    <div class="space-y-5 p-6">






                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Kategori

                            </label>



                            <select

                                name="category"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                                <?php

                                $categories = [

                                    'APBDes',

                                    'Pendapatan Desa',

                                    'Belanja Desa',

                                    'Pembiayaan Desa',

                                    'Realisasi Anggaran',

                                    'Laporan Keuangan',

                                    'Lainnya'

                                ];


                                foreach ($categories as $item):

                                ?>


                                    <option

                                        value="<?= $item; ?>"

                                        <?= $data['category'] == $item ? 'selected' : ''; ?>>

                                        <?= $item; ?>

                                    </option>


                                <?php endforeach; ?>


                            </select>

                        </div>








                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Tahun Anggaran

                            </label>


                            <input

                                type="number"

                                name="fiscal_year"

                                value="<?= $data['fiscal_year']; ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>








                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Total Anggaran

                            </label>


                            <input

                                type="number"

                                name="total_budget"

                                value="<?= $data['total_budget']; ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>








                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Realisasi

                            </label>


                            <input

                                type="number"

                                name="realization"

                                value="<?= $data['realization']; ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>







                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Sumber Dana

                            </label>



                            <select

                                name="funding_source"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                                <?php

                                $sources = [

                                    'Dana Desa',

                                    'Alokasi Dana Desa',

                                    'PADes',

                                    'Bantuan Pemerintah',

                                    'Bantuan Provinsi',

                                    'Bantuan Kabupaten',

                                    'Lainnya'

                                ];


                                foreach ($sources as $item):

                                ?>


                                    <option

                                        value="<?= $item; ?>"

                                        <?= $data['funding_source'] == $item ? 'selected' : ''; ?>>

                                        <?= $item; ?>

                                    </option>


                                <?php endforeach; ?>


                            </select>



                        </div>







                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Status

                            </label>


                            <select

                                name="status"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                                <option

                                    value="Published"

                                    <?= $data['status'] == 'Published' ? 'selected' : ''; ?>>

                                    Published

                                </option>


                                <option

                                    value="Draft"

                                    <?= $data['status'] == 'Draft' ? 'selected' : ''; ?>>

                                    Draft

                                </option>


                            </select>



                        </div>






                    </div>



                </div>






            </div>







        </div>






    </form>


</div>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>