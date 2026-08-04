<?php

require_once '../../../config/app.php';


$title = "Tambah Aset Desa";
$page  = "aset-desa";


include APP_PATH . 'includes/admin/layout-top.php';

?>



<div class="p-8">


    <form
        action="store.php"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-8">



        <!-- HEADER -->

        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


            <div>

                <h2 class="text-3xl font-bold text-slate-900">

                    Tambah Aset Desa

                </h2>


                <p class="mt-2 text-slate-500">

                    Tambahkan data inventaris aset milik desa.

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

                    Simpan Aset

                </button>



            </div>


        </div>









        <div class="grid gap-8 lg:grid-cols-3">





            <!-- LEFT -->

            <div class="space-y-8 lg:col-span-2">





                <div class="rounded-2xl border border-slate-200 bg-white">



                    <div class="border-b px-6 py-5">

                        <h3 class="text-lg font-semibold text-slate-900">

                            Informasi Aset

                        </h3>

                    </div>





                    <div class="space-y-5 p-6">






                        <!-- Judul -->


                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Nama Aset

                                <span class="text-red-500">*</span>

                            </label>



                            <input

                                id="title"

                                type="text"

                                name="title"

                                required

                                placeholder="Contoh: Gedung Balai Desa Mlokomanis Wetan"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600 outline-none">


                        </div>








                        <!-- Slug -->


                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Slug

                            </label>



                            <input

                                id="slug"

                                name="slug"

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

                                rows="5"

                                placeholder="Deskripsi aset desa..."

                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600 outline-none"></textarea>


                        </div>






                    </div>


                </div>








                <!-- Detail Aset -->


                <div class="rounded-2xl border border-slate-200 bg-white">



                    <div class="border-b px-6 py-5">


                        <h3 class="text-lg font-semibold text-slate-900">

                            Detail Aset

                        </h3>


                    </div>





                    <div class="grid gap-5 p-6 md:grid-cols-2">







                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Kode Aset

                            </label>



                            <input

                                type="text"

                                name="asset_code"

                                placeholder="Contoh: AST-001"

                                class="w-full rounded-xl border px-4 py-3">


                        </div>








                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Tahun Perolehan

                            </label>



                            <input

                                type="number"

                                name="acquisition_year"

                                placeholder="2026"

                                class="w-full rounded-xl border px-4 py-3">


                        </div>







                        <div class="md:col-span-2">


                            <label class="mb-2 block font-medium text-slate-700">

                                Lokasi Aset

                            </label>



                            <input

                                type="text"

                                name="location"

                                placeholder="Lokasi aset"

                                class="w-full rounded-xl border px-4 py-3">


                        </div>








                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Nilai Perolehan

                            </label>



                            <input

                                type="number"

                                name="acquisition_value"

                                placeholder="0"

                                class="w-full rounded-xl border px-4 py-3">


                        </div>








                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Nilai Saat Ini

                            </label>



                            <input

                                type="number"

                                name="current_value"

                                placeholder="0"

                                class="w-full rounded-xl border px-4 py-3">


                        </div>





                    </div>


                </div>







            </div>









            <!-- RIGHT -->

            <div class="space-y-8">






                <!-- Kategori -->


                <div class="rounded-2xl border bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Kategori & Status

                        </h3>

                    </div>





                    <div class="space-y-5 p-6">






                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Kategori Aset

                            </label>


                            <select

                                name="category"

                                class="w-full rounded-xl border px-4 py-3">


                                <option value="Tanah">
                                    Tanah
                                </option>


                                <option value="Bangunan">
                                    Bangunan
                                </option>


                                <option value="Kendaraan">
                                    Kendaraan
                                </option>


                                <option value="Peralatan">
                                    Peralatan
                                </option>


                                <option value="Fasilitas Umum">
                                    Fasilitas Umum
                                </option>


                                <option value="Infrastruktur">
                                    Infrastruktur
                                </option>


                                <option value="Lainnya">
                                    Lainnya
                                </option>


                            </select>


                        </div>








                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Kondisi

                            </label>



                            <select

                                name="condition_status"

                                class="w-full rounded-xl border px-4 py-3">


                                <option value="Baik">

                                    Baik

                                </option>


                                <option value="Rusak Ringan">

                                    Rusak Ringan

                                </option>


                                <option value="Rusak Berat">

                                    Rusak Berat

                                </option>


                            </select>


                        </div>








                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Status Kepemilikan

                            </label>



                            <select

                                name="ownership_status"

                                class="w-full rounded-xl border px-4 py-3">


                                <option value="Milik Desa">

                                    Milik Desa

                                </option>


                                <option value="Sewa">

                                    Sewa

                                </option>


                                <option value="Pinjam Pakai">

                                    Pinjam Pakai

                                </option>


                                <option value="Lainnya">

                                    Lainnya

                                </option>


                            </select>


                        </div>








                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Status Publikasi

                            </label>



                            <select

                                name="status"

                                class="w-full rounded-xl border px-4 py-3">


                                <option value="Published">

                                    Published

                                </option>


                                <option value="Draft">

                                    Draft

                                </option>


                            </select>


                        </div>





                    </div>


                </div>









                <!-- Dokumen -->


                <div class="rounded-2xl border bg-white">


                    <div class="border-b px-6 py-5">


                        <h3 class="font-semibold text-slate-900">

                            Dokumen Pendukung

                        </h3>


                    </div>





                    <div class="p-6">


                        <label class="mb-2 block font-medium text-slate-700">

                            File PDF

                        </label>


                        <input

                            type="file"

                            name="document"

                            accept=".pdf"

                            class="block w-full rounded-xl border px-4 py-3">



                        <p class="mt-2 text-sm text-slate-500">

                            Format PDF maksimal 10 MB.

                        </p>


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

            let slug = this.value

                .toLowerCase()

                .trim()

                .replace(/[^a-z0-9]+/g, '-')

                .replace(/^-+|-+$/g, '');


            slugInput.value = slug;


        }

    );
</script>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>