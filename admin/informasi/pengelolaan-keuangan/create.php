<?php

require_once '../../../config/app.php';

$title = "Tambah Pengelolaan Keuangan";
$page  = "pengelolaan-keuangan";

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

                    Tambah Pengelolaan Keuangan

                </h2>


                <p class="mt-2 text-slate-500">

                    Tambah laporan keuangan desa atau dokumen APBDes.

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

                    Simpan

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





                        <!-- Judul -->

                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Judul Laporan

                                <span class="text-red-500">*</span>

                            </label>



                            <input

                                type="text"

                                id="title"

                                name="title"

                                required

                                placeholder="Contoh: APBDes Desa Mlokomanis Wetan Tahun 2026"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-600">


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

                                placeholder="Penjelasan laporan keuangan..."

                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-600"></textarea>


                        </div>





                    </div>



                </div>







                <!-- Dokumen -->

                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Dokumen

                        </h3>

                    </div>



                    <div class="space-y-5 p-6">



                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                File PDF

                            </label>



                            <input

                                type="file"

                                name="file"

                                accept=".pdf"

                                class="block w-full rounded-xl border border-slate-300 px-4 py-3">


                            <p class="mt-2 text-sm text-slate-500">

                                Maksimal ukuran file 10 MB.

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





                        <!-- Kategori -->


                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Jenis Laporan

                            </label>



                            <select

                                name="category"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                                <option value="APBDes">

                                    APBDes

                                </option>


                                <option value="Pendapatan Desa">

                                    Pendapatan Desa

                                </option>


                                <option value="Belanja Desa">

                                    Belanja Desa

                                </option>


                                <option value="Pembiayaan Desa">

                                    Pembiayaan Desa

                                </option>


                                <option value="Realisasi Anggaran">

                                    Realisasi Anggaran

                                </option>


                                <option value="Laporan Keuangan">

                                    Laporan Keuangan

                                </option>


                                <option value="Lainnya">

                                    Lainnya

                                </option>



                            </select>


                        </div>







                        <!-- Tahun -->


                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Tahun Anggaran

                            </label>



                            <input

                                type="number"

                                name="fiscal_year"

                                value="<?= date('Y'); ?>"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>








                        <!-- Anggaran -->


                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Total Anggaran

                            </label>



                            <div class="relative">


                                <span class="absolute left-4 top-3 text-slate-500">

                                    Rp

                                </span>


                                <input

                                    type="number"

                                    name="total_budget"

                                    value="0"

                                    class="w-full rounded-xl border border-slate-300 py-3 pl-12 pr-4">


                            </div>


                        </div>








                        <!-- Realisasi -->

                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Realisasi

                            </label>


                            <div class="relative">


                                <span class="absolute left-4 top-3 text-slate-500">

                                    Rp

                                </span>


                                <input

                                    type="number"

                                    name="realization"

                                    value="0"

                                    class="w-full rounded-xl border border-slate-300 py-3 pl-12 pr-4">


                            </div>


                        </div>








                        <!-- Sumber Dana -->


                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Sumber Dana

                            </label>



                            <select

                                name="funding_source"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                                <option value="Dana Desa">

                                    Dana Desa

                                </option>


                                <option value="Alokasi Dana Desa">

                                    Alokasi Dana Desa

                                </option>


                                <option value="PADes">

                                    PADes

                                </option>


                                <option value="Bantuan Pemerintah">

                                    Bantuan Pemerintah

                                </option>


                                <option value="Bantuan Provinsi">

                                    Bantuan Provinsi

                                </option>


                                <option value="Bantuan Kabupaten">

                                    Bantuan Kabupaten

                                </option>


                                <option value="Lainnya">

                                    Lainnya

                                </option>



                            </select>


                        </div>







                        <!-- Status -->

                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Status

                            </label>



                            <select

                                name="status"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


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







            </div>







        </div>





    </form>


</div>





<script>
    // Auto slug

    const title = document.getElementById('title');

    const slug = document.getElementById('slug');


    title.addEventListener('keyup', function() {


        slug.value = title.value

            .toLowerCase()

            .replace(/[^a-z0-9]+/g, '-')

            .replace(/^-+|-+$/g, '');


    });
</script>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>