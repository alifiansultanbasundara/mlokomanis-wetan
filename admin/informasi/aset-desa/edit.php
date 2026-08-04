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

    FROM village_assets

    WHERE slug='$slug'

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



$title = "Edit Aset Desa";
$page  = "aset-desa";


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

                    Edit Aset Desa

                </h2>


                <p class="mt-2 text-slate-500">

                    Perbarui informasi aset desa.

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

                    class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white">

                    Update Aset

                </button>



            </div>


        </div>









        <div class="grid gap-8 lg:grid-cols-3">





            <!-- LEFT -->

            <div class="space-y-8 lg:col-span-2">





                <div class="rounded-2xl border bg-white">



                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Informasi Aset

                        </h3>

                    </div>






                    <div class="space-y-5 p-6">






                        <div>


                            <label class="mb-2 block font-medium">

                                Nama Aset

                            </label>


                            <input

                                id="title"

                                type="text"

                                name="title"

                                value="<?= htmlspecialchars($data['title']); ?>"

                                required

                                class="w-full rounded-xl border px-4 py-3">


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

                                class="w-full rounded-xl border bg-slate-100 px-4 py-3">


                        </div>








                        <div>


                            <label class="mb-2 block font-medium">

                                Deskripsi

                            </label>


                            <textarea

                                name="description"

                                rows="5"

                                class="w-full rounded-xl border px-4 py-3"><?= htmlspecialchars($data['description']); ?></textarea>


                        </div>






                    </div>



                </div>








                <!-- Detail -->

                <div class="rounded-2xl border bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold">

                            Detail Aset

                        </h3>

                    </div>






                    <div class="grid gap-5 p-6 md:grid-cols-2">





                        <div>

                            <label class="mb-2 block font-medium">

                                Kode Aset

                            </label>


                            <input

                                type="text"

                                name="asset_code"

                                value="<?= htmlspecialchars($data['asset_code']); ?>"

                                class="w-full rounded-xl border px-4 py-3">


                        </div>








                        <div>

                            <label class="mb-2 block font-medium">

                                Tahun Perolehan

                            </label>


                            <input

                                type="number"

                                name="acquisition_year"

                                value="<?= $data['acquisition_year']; ?>"

                                class="w-full rounded-xl border px-4 py-3">


                        </div>








                        <div class="md:col-span-2">


                            <label class="mb-2 block font-medium">

                                Lokasi

                            </label>


                            <input

                                type="text"

                                name="location"

                                value="<?= htmlspecialchars($data['location']); ?>"

                                class="w-full rounded-xl border px-4 py-3">


                        </div>








                        <div>


                            <label class="mb-2 block font-medium">

                                Nilai Perolehan

                            </label>


                            <input

                                type="number"

                                name="acquisition_value"

                                value="<?= $data['acquisition_value']; ?>"

                                class="w-full rounded-xl border px-4 py-3">


                        </div>








                        <div>


                            <label class="mb-2 block font-medium">

                                Nilai Saat Ini

                            </label>


                            <input

                                type="number"

                                name="current_value"

                                value="<?= $data['current_value']; ?>"

                                class="w-full rounded-xl border px-4 py-3">


                        </div>





                    </div>


                </div>








            </div>









            <!-- RIGHT -->

            <div class="space-y-8">





                <div class="rounded-2xl border bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold">

                            Kategori & Status

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

                                    'Tanah',
                                    'Bangunan',
                                    'Kendaraan',
                                    'Peralatan',
                                    'Fasilitas Umum',
                                    'Infrastruktur',
                                    'Lainnya'

                                ];


                                foreach ($categories as $item):

                                ?>

                                    <option

                                        value="<?= $item; ?>"

                                        <?= $data['category'] == $item ? 'selected' : '' ?>>

                                        <?= $item; ?>

                                    </option>


                                <?php endforeach; ?>


                            </select>


                        </div>








                        <div>

                            <label class="mb-2 block font-medium">

                                Kondisi

                            </label>


                            <select

                                name="condition_status"

                                class="w-full rounded-xl border px-4 py-3">


                                <?php

                                $conditions = [

                                    'Baik',
                                    'Rusak Ringan',
                                    'Rusak Berat'

                                ];


                                foreach ($conditions as $item):

                                ?>

                                    <option

                                        value="<?= $item; ?>"

                                        <?= $data['condition_status'] == $item ? 'selected' : '' ?>>

                                        <?= $item; ?>

                                    </option>


                                <?php endforeach; ?>


                            </select>


                        </div>








                        <div>

                            <label class="mb-2 block font-medium">

                                Kepemilikan

                            </label>


                            <select

                                name="ownership_status"

                                class="w-full rounded-xl border px-4 py-3">


                                <?php

                                $owners = [

                                    'Milik Desa',
                                    'Sewa',
                                    'Pinjam Pakai',
                                    'Lainnya'

                                ];


                                foreach ($owners as $item):

                                ?>

                                    <option

                                        value="<?= $item; ?>"

                                        <?= $data['ownership_status'] == $item ? 'selected' : '' ?>>

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

                                    <?= $data['status'] == 'Published' ? 'selected' : '' ?>>

                                    Published

                                </option>


                                <option

                                    value="Draft"

                                    <?= $data['status'] == 'Draft' ? 'selected' : '' ?>>

                                    Draft

                                </option>


                            </select>


                        </div>






                    </div>


                </div>









                <!-- Dokumen -->


                <div class="rounded-2xl border bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold">

                            Dokumen

                        </h3>

                    </div>





                    <div class="p-6">


                        <?php if (!empty($data['document_file'])): ?>


                            <div class="mb-4 rounded-xl bg-slate-100 p-4 text-sm">


                                <i class="bi bi-file-earmark-pdf text-red-500"></i>


                                <?= htmlspecialchars($data['document_file']); ?>


                            </div>


                        <?php endif; ?>





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



    </form>


</div>





<script>
    const title =
        document.getElementById('title');

    const slug =
        document.getElementById('slug');


    title.addEventListener(
        'keyup',
        () => {

            slug.value = title.value

                .toLowerCase()

                .trim()

                .replace(/[^a-z0-9]+/g, '-')

                .replace(/^-+|-+$/g, '');

        }

    );
</script>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>