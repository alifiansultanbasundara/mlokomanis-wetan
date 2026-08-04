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
// Ambil Data
// ===============================

$query = mysqli_query($conn, "

    SELECT *
    FROM legal_instruments
    WHERE slug = '$slug'
    LIMIT 1

");


if (!$query || mysqli_num_rows($query) == 0) {

    header("Location: index.php");
    exit;
}


$data = mysqli_fetch_assoc($query);



$title = "Edit Produk Hukum";
$page  = "produk-hukum";


include APP_PATH . 'includes/admin/layout-top.php';

?>


<div class="p-8">


    <!-- Header -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h2 class="text-3xl font-bold text-slate-900">
                Edit Produk Hukum
            </h2>

            <p class="mt-2 text-slate-500">
                Perbarui informasi dokumen produk hukum desa.
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
            name="slug"
            value="<?= htmlspecialchars($data['slug']); ?>">



        <div class="grid gap-8 lg:grid-cols-3">


            <!-- LEFT -->

            <div class="space-y-8 lg:col-span-2">


                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b border-slate-200 px-6 py-5">

                        <h3 class="text-lg font-semibold text-slate-900">

                            Informasi Produk Hukum

                        </h3>

                    </div>



                    <div class="space-y-5 p-6">



                        <!-- Judul -->

                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Judul Produk Hukum

                            </label>


                            <input

                                type="text"

                                name="title"

                                required

                                value="<?= htmlspecialchars($data['title']); ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600 outline-none">

                        </div>




                        <!-- Slug -->

                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Slug

                            </label>


                            <input

                                type="text"

                                readonly

                                value="<?= htmlspecialchars($data['slug']); ?>"

                                class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-500">

                        </div>





                        <!-- Deskripsi -->

                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Deskripsi

                            </label>


                            <textarea

                                name="description"

                                rows="5"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">

<?= htmlspecialchars($data['description']); ?>

</textarea>


                        </div>



                    </div>

                </div>


            </div>






            <!-- RIGHT -->

            <div class="space-y-8">


                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b border-slate-200 px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Detail Dokumen

                        </h3>

                    </div>




                    <div class="space-y-5 p-6">



                        <!-- Category -->

                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Jenis Produk

                            </label>


                            <select

                                name="category"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                                <?php

                                $categories = [

                                    'Peraturan Desa',

                                    'Peraturan Kepala Desa',

                                    'Keputusan Kepala Desa',

                                    'Surat Keputusan',

                                    'Instruksi',

                                    'SOP',

                                    'Dokumen Lain'

                                ];


                                foreach ($categories as $category):

                                ?>


                                    <option

                                        value="<?= $category; ?>"

                                        <?= $data['category'] == $category ? 'selected' : ''; ?>>

                                        <?= $category; ?>

                                    </option>


                                <?php endforeach; ?>


                            </select>


                        </div>





                        <!-- Nomor -->

                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Nomor Dokumen

                            </label>


                            <input

                                type="text"

                                name="document_number"

                                value="<?= htmlspecialchars($data['document_number']); ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>





                        <!-- Tahun -->

                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Tahun

                            </label>


                            <input

                                type="number"

                                name="document_year"

                                value="<?= $data['document_year']; ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>





                        <!-- Effective Date -->

                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Tanggal Berlaku

                            </label>


                            <input

                                type="date"

                                name="effective_date"

                                value="<?= $data['effective_date']; ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>





                        <!-- Status -->

                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Status

                            </label>


                            <select

                                name="status"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


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







                <!-- FILE -->

                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b border-slate-200 px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Dokumen PDF

                        </h3>

                    </div>



                    <div class="space-y-5 p-6">


                        <?php if (!empty($data['file'])): ?>


                            <div class="rounded-xl bg-slate-50 p-4">


                                <p class="text-sm text-slate-500">

                                    File Saat Ini

                                </p>


                                <a

                                    target="_blank"

                                    href="<?= APP_URL . 'uploads/informasi/produk-hukum/' . $data['file']; ?>"

                                    class="mt-2 inline-flex items-center gap-2 font-medium text-teal-600">

                                    <i class="bi bi-file-earmark-pdf"></i>

                                    <?= htmlspecialchars($data['file']); ?>

                                </a>


                            </div>


                        <?php endif; ?>




                        <label class="mb-2 block font-medium text-slate-700">

                            Ganti File PDF (Opsional)

                        </label>


                        <input

                            type="file"

                            name="file"

                            accept=".pdf"

                            class="block w-full rounded-xl border border-slate-300 px-4 py-3">


                        <p class="text-sm text-slate-500">

                            Kosongkan jika tidak ingin mengganti file.

                        </p>


                    </div>


                </div>



            </div>



        </div>


    </form>


</div>

<script>
    const title = document.getElementById('title');
    const slug = document.getElementById('slug');

    title.addEventListener('keyup', () => {

        slug.value = title.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');

    });

    const pdf = document.getElementById('pdf');
    const pdfName = document.getElementById('pdfName');

    pdf.addEventListener('change', function() {

        pdfName.textContent = this.files.length ?
            this.files[0].name :
            'Belum ada file dipilih.';

    });

    const thumbnail = document.getElementById('thumbnail');
    const preview = document.getElementById('thumbnailPreview');

    thumbnail.addEventListener('change', function() {

        if (!this.files.length) {

            preview.classList.add('hidden');
            return;

        }

        const reader = new FileReader();

        reader.onload = function(e) {

            preview.src = e.target.result;
            preview.classList.remove('hidden');

        }

        reader.readAsDataURL(thumbnail.files[0]);

    });
</script>

<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>