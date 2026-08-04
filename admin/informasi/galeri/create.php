<?php
require_once '../../../config/app.php';

$title = "";
$page  = "galeri";

include APP_PATH . 'includes/admin/layout-top.php';
?>
<form
    action="store.php"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-8 p-8">
    <!-- Header -->
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">
                Tambah Album Galeri
            </h2>
            <p class="mt-2 text-slate-500">
                Buat Album Galeri yang akan ditampilkan pada website desa.
            </p>
        </div>
        <div class="flex gap-3">
            <a
                href="index.php"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 transition hover:bg-slate-50">
                Kembali
            </a>
            <button
                type="submit"
                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white transition hover:bg-teal-700">
                Simpan Album
            </button>
        </div>
    </div>
    <div class="grid gap-8 lg:grid-cols-3">

        <!-- LEFT -->
        <div class="space-y-8 lg:col-span-2">

            <!-- Informasi Album -->
            <div class="rounded-2xl border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Informasi Album
                    </h3>
                </div>

                <div class="space-y-5 p-6">

                    <!-- Judul -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Judul Album <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="title"
                            type="text"
                            name="title"
                            required
                            placeholder="Contoh: Kegiatan HUT RI Tahun 2026"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-600">
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Slug
                        </label>

                        <input
                            id="slug"
                            readonly
                            name="slug"
                            placeholder="Slug dibuat otomatis..."
                            class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-500">
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Deskripsi Album
                        </label>

                        <textarea
                            rows="6"
                            name="description"
                            placeholder="Deskripsi singkat album galeri..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-600"></textarea>
                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="space-y-8">

            <!-- Publikasi -->
            <div class="rounded-2xl border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="font-semibold text-slate-900">
                        Publikasi
                    </h3>
                </div>

                <div class="space-y-5 p-6">

                    <!-- Status -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                            <option value="Draft">
                                Draft
                            </option>

                            <option value="Published">
                                Published
                            </option>

                        </select>
                    </div>

                    <!-- Urutan -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Urutan Tampil
                        </label>

                        <input
                            type="number"
                            name="priority"
                            value="0"
                            min="0"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                    </div>

                </div>

            </div>

            <!-- Cover Album -->

            <div class="rounded-2xl border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-6 py-5">

                    <h3 class="font-semibold text-slate-900">

                        Cover Album

                    </h3>

                </div>


                <div class="space-y-5 p-6">


                    <div>

                        <label class="mb-2 block font-medium text-slate-700">

                            Foto Cover

                        </label>


                        <input
                            type="file"
                            name="cover_image"
                            accept="image/*"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        <p class="mt-2 text-sm text-slate-500">

                            Upload foto utama album. Format JPG, PNG, maksimal 2MB.

                        </p>


                    </div>


                    <!-- Preview -->

                    <div id="preview"
                        class="hidden overflow-hidden rounded-xl border">


                        <img
                            id="previewImage"
                            class="h-48 w-full object-cover">


                    </div>


                </div>


            </div>

            <!-- Informasi -->
            <div class="rounded-2xl border border-slate-200 bg-slate-50">

                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="font-semibold text-slate-900">
                        Upload Foto
                    </h3>
                </div>

                <div class="space-y-3 p-6 text-sm text-slate-600">

                    <div class="flex items-start gap-3">
                        <i class="bi bi-info-circle mt-0.5 text-teal-600"></i>

                        <p>
                            Album akan dibuat terlebih dahulu.
                        </p>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="bi bi-images mt-0.5 text-teal-600"></i>

                        <p>
                            Setelah album berhasil dibuat, Anda dapat menambahkan banyak foto sekaligus pada halaman Edit Album.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</form>
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
</script>

<script>
    const coverInput = document.querySelector('input[name="cover_image"]');

    const preview = document.getElementById('preview');

    const previewImage = document.getElementById('previewImage');


    coverInput.addEventListener('change', function(e) {


        const file = e.target.files[0];


        if (file) {


            preview.classList.remove('hidden');


            previewImage.src = URL.createObjectURL(file);


        }


    });
</script>
<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>