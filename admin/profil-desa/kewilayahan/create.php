<?php

require_once '../../../config/app.php';

$title = "Tambah Data Kewilayahan";
$page  = "kewilayahan";

include APP_PATH . 'includes/admin/layout-top.php';

?>

<div class="p-8">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">

        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Tambah Data Kewilayahan
            </h1>

            <p class="text-slate-500 mt-2">
                Tambahkan peta atau data kewilayahan desa.
            </p>
        </div>

        <a href="index.php"
            class="px-5 py-3 rounded-xl border border-slate-300 hover:bg-slate-50">

            Kembali

        </a>

    </div>


    <form
        action="store.php"
        method="POST"
        enctype="multipart/form-data"
        class="grid lg:grid-cols-3 gap-8">

        <!-- LEFT -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Informasi -->
            <div class="bg-white rounded-2xl border border-slate-200">

                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold">
                        Informasi Peta
                    </h2>

                </div>

                <div class="p-6 space-y-5">

                    <div>

                        <label class="block font-medium mb-2">
                            Judul Peta
                        </label>

                        <input
                            type="text"
                            name="title"
                            id="title"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                    <div>

                        <label class="block font-medium mb-2">
                            Slug
                        </label>

                        <input
                            type="text"
                            name="slug"
                            id="slug"
                            readonly
                            class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3">

                    </div>

                    <div>

                        <label class="block font-medium mb-2">
                            Kategori
                        </label>

                        <select
                            name="category"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                            <option value="Peta Administrasi">Peta Administrasi</option>
                            <option value="Peta RT/RW">Peta RT/RW</option>
                            <option value="Peta Blok SPPT PBB">Peta Blok SPPT PBB</option>
                            <option value="Peta Tata Guna Lahan">Peta Tata Guna Lahan</option>
                            <option value="Peta Mata Pencaharian">Peta Mata Pencaharian</option>
                            <option value="Peta Infrastruktur">Peta Infrastruktur</option>
                            <option value="Peta Rawan Bencana">Peta Rawan Bencana</option>
                            <option value="Peta Potensi Desa">Peta Potensi Desa</option>
                            <option value="Peta Sebaran Penduduk">Peta Sebaran Penduduk</option>
                            <option value="Peta Batas Dusun">Peta Batas Dusun</option>
                            <option value="Peta Fasilitas Umum">Peta Fasilitas Umum</option>
                            <option value="Lainnya">Lainnya</option>

                        </select>

                    </div>

                    <div>

                        <label class="block font-medium mb-2">
                            Deskripsi
                        </label>

                        <textarea
                            name="description"
                            rows="7"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3"></textarea>

                    </div>

                </div>

            </div>


            <!-- Lokasi -->
            <div class="bg-white rounded-2xl border border-slate-200">

                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold">
                        Lokasi
                    </h2>

                </div>

                <div class="p-6 grid md:grid-cols-2 gap-5">

                    <div>

                        <label class="block mb-2 font-medium">
                            Latitude
                        </label>

                        <input
                            type="text"
                            name="latitude"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">
                            Longitude
                        </label>

                        <input
                            type="text"
                            name="longitude"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                    <div class="md:col-span-2">

                        <label class="block mb-2 font-medium">
                            Link Google Maps
                        </label>

                        <textarea
                            name="google_maps"
                            rows="3"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3"></textarea>

                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="space-y-8">

            <div class="bg-white rounded-2xl border border-slate-200">

                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold">
                        Detail
                    </h2>

                </div>

                <div class="p-6 space-y-5">

                    <div>

                        <label class="block mb-2 font-medium">
                            Tahun
                        </label>

                        <input
                            type="number"
                            name="year"
                            value="<?= date('Y'); ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">
                            Skala
                        </label>

                        <input
                            type="text"
                            name="scale"
                            placeholder="Contoh : 1 : 5000"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                            <option value="Published">Published</option>
                            <option value="Draft">Draft</option>

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">
                            Urutan
                        </label>

                        <input
                            type="number"
                            name="sort_order"
                            value="0"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                </div>

            </div>

            <div class="bg-white rounded-2xl border border-slate-200">

                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold">
                        File
                    </h2>

                </div>

                <div class="p-6 space-y-5">

                    <div>

                        <label class="block mb-2 font-medium">
                            Preview Gambar
                        </label>

                        <input
                            type="file"
                            name="image"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">
                            File Peta
                        </label>

                        <input
                            type="file"
                            name="document"
                            accept=".pdf,.jpg,.jpeg,.png,.zip,.rar,.dwg"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                </div>

            </div>

            <button
                class="w-full rounded-xl bg-teal-600 hover:bg-teal-700 text-white py-4 font-semibold">

                Simpan Data

            </button>

        </div>

    </form>

</div>

<script>
    const title = document.getElementById('title');
    const slug = document.getElementById('slug');

    title.addEventListener('keyup', function() {

        slug.value = this.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');

    });
</script>

<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>