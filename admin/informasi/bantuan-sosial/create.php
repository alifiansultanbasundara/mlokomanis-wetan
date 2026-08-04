<?php

require_once '../../../config/app.php';


$title = "Tambah Bantuan Sosial";
$page  = "bantuan-sosial";


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
                    Tambah Program Bantuan Sosial
                </h2>


                <p class="mt-2 text-slate-500">
                    Tambahkan program bantuan sosial yang diselenggarakan oleh desa.
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

                    Simpan Program

                </button>



            </div>


        </div>









        <div class="grid gap-8 lg:grid-cols-3">





            <!-- LEFT -->

            <div class="space-y-8 lg:col-span-2">





                <!-- INFORMASI -->

                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Informasi Program Bantuan

                        </h3>

                    </div>





                    <div class="space-y-5 p-6">






                        <div>

                            <label class="mb-2 block font-medium text-slate-700">

                                Nama Program Bantuan

                                <span class="text-red-500">*</span>

                            </label>


                            <input

                                id="title"

                                type="text"

                                name="title"

                                required

                                placeholder="Contoh: Bantuan Langsung Tunai Dana Desa 2026"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600 outline-none">


                        </div>








                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Slug

                            </label>


                            <input

                                id="slug"

                                type="text"

                                name="slug"

                                readonly

                                class="w-full rounded-xl border bg-slate-100 px-4 py-3 text-slate-500">


                        </div>








                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Deskripsi Program

                            </label>



                            <textarea

                                name="description"

                                rows="6"

                                placeholder="Jelaskan tujuan, sasaran, dan informasi program bantuan..."

                                class="w-full rounded-xl border border-slate-300 px-4 py-3"></textarea>


                        </div>







                    </div>


                </div>









                <!-- ANGGARAN -->

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

                                <span class="text-red-500">*</span>

                            </label>


                            <input

                                type="number"

                                name="year"

                                value="<?= date('Y'); ?>"

                                required

                                class="w-full rounded-xl border px-4 py-3">


                        </div>








                        <div>


                            <label class="mb-2 block font-medium">

                                Total Anggaran

                            </label>


                            <input

                                type="number"

                                name="total_budget"

                                placeholder="Contoh: 300000000"

                                class="w-full rounded-xl border px-4 py-3">


                            <p class="mt-2 text-sm text-slate-500">

                                Masukkan nominal tanpa titik.

                            </p>


                        </div>






                    </div>


                </div>








            </div>









            <!-- RIGHT -->

            <div class="space-y-8">






                <!-- DETAIL -->

                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold text-slate-900">

                            Detail Program

                        </h3>

                    </div>






                    <div class="space-y-5 p-6">







                        <div>


                            <label class="mb-2 block font-medium">

                                Kategori Bantuan

                            </label>


                            <select

                                name="category"

                                class="w-full rounded-xl border px-4 py-3">


                                <option value="BLT Dana Desa">
                                    BLT Dana Desa
                                </option>


                                <option value="PKH">
                                    PKH
                                </option>


                                <option value="BPNT">
                                    BPNT
                                </option>


                                <option value="Bantuan Sembako">
                                    Bantuan Sembako
                                </option>


                                <option value="Bantuan Kesehatan">
                                    Bantuan Kesehatan
                                </option>


                                <option value="Bantuan Pendidikan">
                                    Bantuan Pendidikan
                                </option>


                                <option value="Bantuan Rumah">
                                    Bantuan Rumah
                                </option>


                                <option value="Lainnya">
                                    Lainnya
                                </option>


                            </select>


                        </div>








                        <div>


                            <label class="mb-2 block font-medium">

                                Sumber Dana

                            </label>



                            <select

                                name="funding_source"

                                class="w-full rounded-xl border px-4 py-3">


                                <option value="Dana Desa">

                                    Dana Desa

                                </option>


                                <option value="APBD">

                                    APBD

                                </option>


                                <option value="APBN">

                                    APBN

                                </option>


                                <option value="Swadaya">

                                    Swadaya

                                </option>


                                <option value="Lainnya">

                                    Lainnya

                                </option>


                            </select>



                        </div>








                        <div>


                            <label class="mb-2 block font-medium">

                                Status

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









                <!-- DOKUMEN -->


                <div class="rounded-2xl border border-slate-200 bg-white">


                    <div class="border-b px-6 py-5">

                        <h3 class="font-semibold">

                            Dokumen Pendukung

                        </h3>

                    </div>






                    <div class="p-6">



                        <label class="mb-2 block font-medium">

                            Upload PDF

                        </label>


                        <input

                            type="file"

                            name="document"

                            accept=".pdf"

                            class="w-full rounded-xl border px-4 py-3">



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

            slugInput.value =
                titleInput.value

                .toLowerCase()

                .trim()

                .replace(/[^a-z0-9]+/g, '-')

                .replace(/^-+|-+$/g, '');


        });
</script>





<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>