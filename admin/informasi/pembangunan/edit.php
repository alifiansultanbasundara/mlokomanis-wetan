<?php

require_once '../../../config/app.php';


// =====================================
// Validasi Slug
// =====================================

if (!isset($_GET['slug']) || empty($_GET['slug'])) {

    header("Location: index.php");
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
    FROM constructions
    WHERE slug='$slug'
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



$title = "Edit Pembangunan";
$page  = "pembangunan";


include APP_PATH . 'includes/admin/layout-top.php';


?>


<div class="p-8">


    <!-- Header -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h2 class="text-3xl font-bold text-slate-900">

                Edit Pembangunan Desa

            </h2>


            <p class="mt-2 text-slate-500">

                Perbarui informasi pembangunan desa.

            </p>


        </div>



        <div class="flex gap-3">


            <a
                href="index.php"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 hover:bg-slate-50">

                Kembali

            </a>


            <button
                form="formEdit"
                type="submit"
                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

                Simpan Perubahan

            </button>


        </div>


    </div>





    <form
        id="formEdit"
        action="update.php"
        method="POST"
        enctype="multipart/form-data">



        <input
            type="hidden"
            name="old_slug"
            value="<?= htmlspecialchars($data['slug']); ?>">



        <div class="grid gap-8 lg:grid-cols-3">





            <!-- LEFT -->

            <div class="space-y-8 lg:col-span-2">





                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b border-slate-200 px-6 py-5">

                        <h3 class="text-lg font-semibold text-slate-900">

                            Informasi Pembangunan

                        </h3>

                    </div>




                    <div class="space-y-5 p-6">





                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Nama Pembangunan

                                <span class="text-red-500">*</span>

                            </label>


                            <input

                                type="text"

                                name="title"

                                id="title"

                                value="<?= htmlspecialchars($data['title']); ?>"

                                required

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">

                        </div>





                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Slug

                            </label>


                            <input

                                type="text"

                                name="slug"

                                id="slug"

                                readonly

                                value="<?= htmlspecialchars($data['slug']); ?>"

                                class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3">

                        </div>





                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

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


                    <div class="border-b border-slate-200 px-6 py-5">

                        <h3 class="text-lg font-semibold text-slate-900">

                            Pelaksanaan

                        </h3>

                    </div>




                    <div class="grid gap-5 p-6 md:grid-cols-2">





                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Lokasi

                            </label>


                            <input

                                type="text"

                                name="location"

                                value="<?= htmlspecialchars($data['location']); ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>





                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Tahun

                            </label>


                            <input

                                type="number"

                                name="year"

                                value="<?= $data['year']; ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>





                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Tanggal Mulai

                            </label>


                            <input

                                type="date"

                                name="start_date"

                                value="<?= $data['start_date']; ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>





                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Tanggal Selesai

                            </label>


                            <input

                                type="date"

                                name="end_date"

                                value="<?= $data['end_date']; ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>



                    </div>


                </div>





            </div>








            <!-- RIGHT -->

            <div class="space-y-8">





                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b border-slate-200 px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Detail Pembangunan

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

                                    'Infrastruktur',
                                    'Sarana Prasarana',
                                    'Pemberdayaan',
                                    'Pemerintahan',
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

                                Anggaran

                            </label>


                            <input

                                type="number"

                                name="budget"

                                value="<?= $data['budget']; ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>





                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Sumber Dana

                            </label>


                            <input

                                type="text"

                                name="funding_source"

                                value="<?= htmlspecialchars($data['funding_source']); ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>






                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Volume

                            </label>


                            <input

                                type="text"

                                name="volume"

                                value="<?= htmlspecialchars($data['volume']); ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>






                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Progress (%)

                            </label>


                            <input

                                type="number"

                                name="progress"

                                min="0"

                                max="100"

                                value="<?= $data['progress']; ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>






                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Status

                            </label>


                            <select

                                name="status"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                                <?php

                                $statusList = [

                                    'Perencanaan',
                                    'Berjalan',
                                    'Selesai',
                                    'Ditunda'

                                ];


                                foreach ($statusList as $item):

                                ?>

                                    <option

                                        value="<?= $item; ?>"

                                        <?= $data['status'] == $item ? 'selected' : ''; ?>>

                                        <?= $item; ?>

                                    </option>


                                <?php endforeach; ?>


                            </select>


                        </div>





                    </div>


                </div>








                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b border-slate-200 px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Dokumentasi

                        </h3>

                    </div>



                    <div class="space-y-5 p-6">


                        <?php if (!empty($data['thumbnail'])): ?>


                            <img

                                src="<?= APP_URL . 'uploads/informasi/pembangunan/' . $data['thumbnail']; ?>"

                                class="h-48 w-full rounded-xl object-cover">


                        <?php endif; ?>



                        <input

                            type="file"

                            name="thumbnail"

                            accept="image/png,image/jpeg,image/webp"

                            class="block w-full rounded-xl border border-slate-300 px-4 py-3">


                        <p class="text-sm text-slate-500">

                            Kosongkan jika tidak ingin mengganti foto.

                        </p>


                    </div>


                </div>





            </div>





        </div>



    </form>


</div>




<script>
    const titleInput = document.getElementById('title');

    const slugInput = document.getElementById('slug');


    titleInput.addEventListener('keyup', function() {

        let slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');


        slugInput.value = slug;


    });
</script>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>