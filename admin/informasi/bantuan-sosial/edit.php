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

    FROM social_assistances

    WHERE slug='$slug'

    LIMIT 1

    "
);



if (!$query || mysqli_num_rows($query) == 0) {

    $_SESSION['error'] =
        "Data bantuan sosial tidak ditemukan.";

    header("Location:index.php");
    exit;
}



$data = mysqli_fetch_assoc($query);





$title = "Edit Bantuan Sosial";
$page  = "bantuan-sosial";


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
            name="old_slug"
            value="<?= htmlspecialchars($data['slug']); ?>">





        <!-- HEADER -->

        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


            <div>

                <h2 class="text-3xl font-bold text-slate-900">
                    Edit Program Bantuan Sosial
                </h2>

                <p class="mt-2 text-slate-500">
                    Perbarui informasi program bantuan sosial desa.
                </p>

            </div>




            <div class="flex gap-3">


                <a
                    href="index.php"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700">

                    Kembali

                </a>



                <button
                    type="submit"
                    class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

                    Simpan Perubahan

                </button>



            </div>


        </div>








        <div class="grid gap-8 lg:grid-cols-3">







            <!-- LEFT -->

            <div class="space-y-8 lg:col-span-2">





                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Informasi Program

                        </h3>

                    </div>





                    <div class="space-y-5 p-6">







                        <div>

                            <label class="mb-2 block font-medium">

                                Nama Program Bantuan

                            </label>


                            <input

                                id="title"

                                type="text"

                                name="title"

                                required

                                value="<?= htmlspecialchars($data['title']); ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>








                        <div>

                            <label class="mb-2 block font-medium">

                                Slug

                            </label>


                            <input

                                id="slug"

                                type="text"

                                name="slug"

                                value="<?= htmlspecialchars($data['slug']); ?>"

                                readonly

                                class="w-full rounded-xl border bg-slate-100 px-4 py-3 text-slate-500">


                        </div>







                        <div>

                            <label class="mb-2 block font-medium">

                                Deskripsi

                            </label>


                            <textarea

                                name="description"

                                rows="6"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3"><?= htmlspecialchars($data['description']); ?></textarea>


                        </div>






                    </div>


                </div>








                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Informasi Anggaran

                        </h3>

                    </div>





                    <div class="grid gap-5 p-6 md:grid-cols-2">





                        <div>


                            <label class="mb-2 block font-medium">

                                Tahun Program

                            </label>


                            <input

                                type="number"

                                name="year"

                                value="<?= $data['year']; ?>"

                                class="w-full rounded-xl border px-4 py-3">


                        </div>







                        <div>


                            <label class="mb-2 block font-medium">

                                Total Anggaran

                            </label>


                            <input

                                type="number"

                                name="total_budget"

                                value="<?= $data['total_budget']; ?>"

                                class="w-full rounded-xl border px-4 py-3">


                        </div>






                    </div>


                </div>







            </div>









            <!-- RIGHT -->

            <div class="space-y-8">






                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Detail Program

                        </h3>

                    </div>






                    <div class="space-y-5 p-6">








                        <div>


                            <label class="mb-2 block font-medium">

                                Kategori

                            </label>



                            <select

                                name="category"

                                class="w-full rounded-xl border px-4 py-3">



                                <?php

                                $categories = [

                                    'BLT Dana Desa',
                                    'PKH',
                                    'BPNT',
                                    'Bantuan Sembako',
                                    'Bantuan Kesehatan',
                                    'Bantuan Pendidikan',
                                    'Bantuan Rumah',
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


                            <label class="mb-2 block font-medium">

                                Sumber Dana

                            </label>



                            <select

                                name="funding_source"

                                class="w-full rounded-xl border px-4 py-3">


                                <?php

                                $fundings = [

                                    'Dana Desa',
                                    'APBD',
                                    'APBN',
                                    'Swadaya',
                                    'Lainnya'

                                ];


                                foreach ($fundings as $item):

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


                            <label class="mb-2 block font-medium">

                                Status

                            </label>


                            <select

                                name="status"

                                class="w-full rounded-xl border px-4 py-3">


                                <option
                                    value="Published"
                                    <?= $data['status'] == "Published" ? 'selected' : ''; ?>>

                                    Published

                                </option>


                                <option
                                    value="Draft"
                                    <?= $data['status'] == "Draft" ? 'selected' : ''; ?>>

                                    Draft

                                </option>


                            </select>


                        </div>






                    </div>


                </div>









                <!-- DOKUMEN -->


                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold">

                            Dokumen

                        </h3>

                    </div>





                    <div class="space-y-4 p-6">


                        <?php if (!empty($data['document_file'])): ?>


                            <div>

                                <p class="text-sm text-slate-500">
                                    Dokumen saat ini:
                                </p>


                                <a

                                    href="download.php?file=<?= urlencode($data['document_file']); ?>"

                                    class="text-teal-600 hover:underline">

                                    <?= htmlspecialchars($data['document_file']); ?>

                                </a>


                            </div>


                        <?php endif; ?>





                        <div>


                            <label class="mb-2 block font-medium">

                                Ganti Dokumen PDF

                            </label>


                            <input

                                type="file"

                                name="document"

                                accept=".pdf"

                                class="w-full rounded-xl border px-4 py-3">


                            <p class="mt-2 text-sm text-slate-500">

                                Kosongkan jika tidak ingin mengganti dokumen.

                            </p>


                        </div>



                    </div>


                </div>








            </div>






        </div>





    </form>

</div>





<script>
    const titleInput =
        document.getElementById('title');


    const slugInput =
        document.getElementById('slug');


    titleInput.addEventListener(
        'keyup',
        function() {

            slugInput.value =
                titleInput.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');

        });
</script>





<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>