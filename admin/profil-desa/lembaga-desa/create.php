<?php

require_once '../../../config/app.php';

$title = "Tambah Lembaga Desa";
$page  = "lembaga-desa";

include APP_PATH . 'includes/admin/layout-top.php';

?>

<div class="p-8">

    <!-- Header -->
    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Tambah Lembaga Desa
            </h1>

            <p class="mt-2 text-slate-500">
                Tambahkan informasi lembaga atau organisasi yang ada di desa.
            </p>
        </div>


        <div class="flex gap-3">

            <a
                href="index.php"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 transition hover:bg-slate-50">

                Kembali

            </a>


            <button
                form="formLembaga"
                type="submit"
                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white transition hover:bg-teal-700">

                Simpan Lembaga

            </button>

        </div>

    </div>



    <form
        id="formLembaga"
        action="store.php"
        method="POST"
        enctype="multipart/form-data"
        class="grid gap-8 lg:grid-cols-3">



        <!-- LEFT -->
        <div class="space-y-8 lg:col-span-2">


            <!-- Informasi Utama -->
            <div class="rounded-2xl border border-slate-200 bg-white">


                <div class="border-b border-slate-200 px-6 py-5">

                    <h2 class="text-lg font-semibold text-slate-900">
                        Informasi Lembaga
                    </h2>

                </div>


                <div class="space-y-5 p-6">


                    <!-- Nama -->
                    <div>

                        <label class="mb-2 block font-medium text-slate-700">

                            Nama Lembaga
                            <span class="text-red-500">*</span>

                        </label>


                        <input
                            type="text"
                            name="name"
                            id="name"
                            required
                            placeholder="Contoh: Badan Permusyawaratan Desa"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-600">


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
                            placeholder="Jelaskan profil dan kegiatan lembaga..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-600"></textarea>


                    </div>



                </div>

            </div>






            <!-- Kontak -->
            <div class="rounded-2xl border border-slate-200 bg-white">


                <div class="border-b border-slate-200 px-6 py-5">

                    <h2 class="text-lg font-semibold text-slate-900">
                        Informasi Pengurus
                    </h2>

                </div>



                <div class="grid gap-5 p-6 md:grid-cols-2">


                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Ketua Lembaga
                        </label>


                        <input
                            type="text"
                            name="chairman"
                            placeholder="Nama ketua"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>



                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Sekretaris
                        </label>


                        <input
                            type="text"
                            name="secretary"
                            placeholder="Nama sekretaris"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>



                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Nomor Telepon
                        </label>


                        <input
                            type="text"
                            name="phone"
                            placeholder="08xxxxxxxxxx"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>




                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Email
                        </label>


                        <input
                            type="email"
                            name="email"
                            placeholder="email@desa.id"
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

                    <h2 class="font-semibold text-slate-900">
                        Detail Lembaga
                    </h2>

                </div>



                <div class="space-y-5 p-6">



                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Kategori
                        </label>


                        <select
                            name="category"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">


                            <option value="BPD">
                                BPD
                            </option>

                            <option value="LPMD">
                                LPMD
                            </option>


                            <option value="PKK">
                                PKK
                            </option>


                            <option value="Karang Taruna">
                                Karang Taruna
                            </option>


                            <option value="RT/RW">
                                RT/RW
                            </option>


                            <option value="Posyandu">
                                Posyandu
                            </option>


                            <option value="Kelompok Tani">
                                Kelompok Tani
                            </option>


                            <option value="Lainnya">
                                Lainnya
                            </option>


                        </select>

                    </div>





                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Jumlah Anggota
                        </label>


                        <input
                            type="number"
                            name="total_members"
                            value="0"
                            min="0"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">


                    </div>





                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Status
                        </label>


                        <select
                            name="status"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">


                            <option value="Active">
                                Aktif
                            </option>


                            <option value="Inactive">
                                Tidak Aktif
                            </option>


                        </select>

                    </div>





                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Urutan Tampilan
                        </label>


                        <input
                            type="number"
                            name="sort_order"
                            value="0"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">


                    </div>



                </div>


            </div>







            <!-- Upload -->
            <div class="rounded-2xl border border-slate-200 bg-white">


                <div class="border-b border-slate-200 px-6 py-5">

                    <h2 class="font-semibold text-slate-900">
                        File Pendukung
                    </h2>

                </div>



                <div class="space-y-5 p-6">


                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Logo / Foto Lembaga
                        </label>


                        <input
                            type="file"
                            name="image"
                            accept="image/*"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">


                    </div>




                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Dokumen Pendukung
                        </label>


                        <input
                            type="file"
                            name="document"
                            accept=".pdf,.doc,.docx"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">


                    </div>


                </div>


            </div>




        </div>



    </form>


</div>




<script>
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');


    nameInput.addEventListener('keyup', function() {


        slugInput.value = this.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');


    });
</script>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>