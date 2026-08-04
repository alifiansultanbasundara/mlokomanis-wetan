<?php

require_once '../../../config/app.php';

$title = "Tambah Pembangunan";
$page  = "pembangunan";

include APP_PATH . 'includes/admin/layout-top.php';

?>

<div class="p-8">

    <!-- Header -->
    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <h2 class="text-3xl font-bold text-slate-900">
                Tambah Pembangunan Desa
            </h2>

            <p class="mt-2 text-slate-500">
                Tambahkan informasi pembangunan atau kegiatan fisik desa.
            </p>
        </div>


        <div class="flex gap-3">

            <a
                href="index.php"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 transition hover:bg-slate-50">

                Kembali

            </a>


            <button
                form="formCreate"
                type="submit"
                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white transition hover:bg-teal-700">

                Simpan Pembangunan

            </button>

        </div>

    </div>




    <form
        id="formCreate"
        action="store.php"
        method="POST"
        enctype="multipart/form-data">


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



                        <!-- Judul -->

                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Nama Pembangunan

                                <span class="text-red-500">*</span>

                            </label>


                            <input

                                type="text"

                                name="title"

                                id="title"

                                required

                                placeholder="Contoh: Pembangunan Jalan Desa RT 05"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600 outline-none">


                        </div>





                        <!-- Slug -->

                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Slug

                            </label>


                            <input

                                type="text"

                                name="slug"

                                id="slug"

                                readonly

                                class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-500">


                        </div>





                        <!-- Deskripsi -->

                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Deskripsi

                            </label>


                            <textarea

                                name="description"

                                rows="6"

                                placeholder="Jelaskan detail pembangunan..."

                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600 outline-none"></textarea>


                        </div>



                    </div>


                </div>





                <!-- Pelaksanaan -->

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

                                placeholder="Contoh: Dusun Krajan"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">

                        </div>




                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Tahun

                                <span class="text-red-500">*</span>

                            </label>


                            <input

                                type="number"

                                name="year"

                                value="<?= date('Y'); ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">

                        </div>




                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Tanggal Mulai

                            </label>


                            <input

                                type="date"

                                name="start_date"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">

                        </div>




                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Tanggal Selesai

                            </label>


                            <input

                                type="date"

                                name="end_date"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">

                        </div>



                    </div>


                </div>



            </div>






            <!-- RIGHT -->

            <div class="space-y-8">



                <!-- Detail -->

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


                                <option value="Infrastruktur">
                                    Infrastruktur
                                </option>


                                <option value="Sarana Prasarana">
                                    Sarana Prasarana
                                </option>


                                <option value="Pemberdayaan">
                                    Pemberdayaan
                                </option>


                                <option value="Pemerintahan">
                                    Pemerintahan
                                </option>


                                <option value="Lainnya">
                                    Lainnya
                                </option>


                            </select>

                        </div>





                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Anggaran

                            </label>


                            <input

                                type="number"

                                name="budget"

                                placeholder="Contoh: 50000000"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>





                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Sumber Dana

                            </label>


                            <input

                                type="text"

                                name="funding_source"

                                placeholder="Contoh: Dana Desa"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>





                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Volume

                            </label>


                            <input

                                type="text"

                                name="volume"

                                placeholder="Contoh: 200 Meter"

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

                                value="0"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>




                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Status

                            </label>


                            <select

                                name="status"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                                <option value="Perencanaan">
                                    Perencanaan
                                </option>


                                <option value="Berjalan">
                                    Berjalan
                                </option>


                                <option value="Selesai">
                                    Selesai
                                </option>


                                <option value="Ditunda">
                                    Ditunda
                                </option>


                            </select>


                        </div>


                    </div>


                </div>






                <!-- Dokumentasi -->

                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b border-slate-200 px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Dokumentasi

                        </h3>

                    </div>



                    <div class="space-y-5 p-6">


                        <label class="mb-2 block font-medium text-slate-700">

                            Foto Utama

                        </label>


                        <input

                            type="file"

                            name="thumbnail"

                            accept="image/png,image/jpeg,image/webp"

                            class="block w-full rounded-xl border border-slate-300 px-4 py-3">


                        <img
                            id="preview"
                            class="mt-4 hidden h-48 w-full rounded-xl border object-cover">


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