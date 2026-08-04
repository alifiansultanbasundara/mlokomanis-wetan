<?php

require_once '../../../config/app.php';


// ===============================
// Ambil daftar atasan
// ===============================

$parents = mysqli_query($conn, "

    SELECT 
        id,
        name,
        position

    FROM village_officials

    WHERE status='Aktif'

    ORDER BY sort_order ASC, id ASC

");


// ===============================
// Layout
// ===============================

$title = "Tambah Struktur Organisasi";
$page  = "struktur-organisasi";


include APP_PATH . 'includes/admin/layout-top.php';

?>


<main class="space-y-8 p-8">


    <!-- HEADER -->

    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h1 class="text-3xl font-bold text-slate-900">
                Tambah Perangkat Desa
            </h1>

            <p class="mt-2 text-slate-500">
                Tambahkan data struktur organisasi pemerintah desa.
            </p>

        </div>


        <div class="flex gap-3">


            <a href="index.php"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 hover:bg-slate-50">

                Kembali

            </a>


            <button
                form="formOfficial"
                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

                Simpan

            </button>


        </div>


    </div>





    <form
        id="formOfficial"
        action="store.php"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-8">





        <!-- DATA UTAMA -->

        <div class="rounded-2xl border border-slate-200 bg-white">


            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="font-semibold text-slate-900">
                    Informasi Perangkat Desa
                </h2>

            </div>



            <div class="grid gap-6 p-6 lg:grid-cols-2">



                <div>

                    <label class="mb-2 block font-medium text-slate-700">
                        Nama Lengkap
                        <span class="text-red-500">*</span>
                    </label>


                    <input
                        type="text"
                        name="name"
                        required
                        placeholder="Contoh: Budi Santoso"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-600">


                </div>





                <div>

                    <label class="mb-2 block font-medium text-slate-700">
                        Jabatan
                        <span class="text-red-500">*</span>
                    </label>


                    <input
                        type="text"
                        name="position"
                        required
                        placeholder="Contoh: Kepala Desa"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">


                </div>





                <div>


                    <label class="mb-2 block font-medium text-slate-700">
                        Kategori
                    </label>


                    <select
                        name="category"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        <option value="Kepala Desa">
                            Kepala Desa
                        </option>


                        <option value="Sekretariat Desa">
                            Sekretariat Desa
                        </option>


                        <option value="Kepala Urusan">
                            Kepala Urusan
                        </option>


                        <option value="Kepala Seksi">
                            Kepala Seksi
                        </option>


                        <option value="Kepala Dusun">
                            Kepala Dusun
                        </option>


                        <option value="Staf Desa">
                            Staf Desa
                        </option>


                        <option value="BPD">
                            BPD
                        </option>


                        <option value="Lainnya">
                            Lainnya
                        </option>


                    </select>


                </div>





                <div>


                    <label class="mb-2 block font-medium text-slate-700">
                        Atasan / Struktur Diatasnya
                    </label>


                    <select
                        name="parent_id"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        <option value="">
                            -- Tidak Ada (Paling Atas) --
                        </option>


                        <?php while ($row = mysqli_fetch_assoc($parents)): ?>


                            <option value="<?= $row['id']; ?>">

                                <?= htmlspecialchars($row['name']); ?>

                                -
                                <?= htmlspecialchars($row['position']); ?>

                            </option>


                        <?php endwhile; ?>


                    </select>


                </div>


            </div>


        </div>






        <!-- BIODATA -->

        <div class="rounded-2xl border border-slate-200 bg-white">


            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="font-semibold text-slate-900">
                    Biodata
                </h2>

            </div>



            <div class="grid gap-6 p-6 lg:grid-cols-2">





                <div>

                    <label class="mb-2 block font-medium text-slate-700">
                        NIP
                    </label>


                    <input
                        type="text"
                        name="nip"
                        placeholder="Nomor Induk Pegawai"
                        class="w-full rounded-xl border px-4 py-3">


                </div>





                <div>

                    <label class="mb-2 block font-medium text-slate-700">
                        Pendidikan Terakhir
                    </label>


                    <input
                        type="text"
                        name="education"
                        placeholder="Contoh: S1"
                        class="w-full rounded-xl border px-4 py-3">


                </div>





                <div>

                    <label class="mb-2 block font-medium text-slate-700">
                        Jenis Kelamin
                    </label>


                    <select
                        name="gender"
                        class="w-full rounded-xl border px-4 py-3" required>


                        <option value="">
                            -- Pilih --
                        </option>


                        <option value="Laki-laki">
                            Laki-laki
                        </option>


                        <option value="Perempuan">
                            Perempuan
                        </option>


                    </select>


                </div>





                <div>


                    <label class="mb-2 block font-medium text-slate-700">
                        Tanggal Lahir
                    </label>


                    <input
                        type="date"
                        name="birth_date"
                        class="w-full rounded-xl border px-4 py-3">


                </div>




            </div>





            <div class="px-6 pb-6">


                <label class="mb-2 block font-medium text-slate-700">
                    Alamat
                </label>


                <textarea
                    name="address"
                    rows="3"
                    class="w-full rounded-xl border px-4 py-3"></textarea>


            </div>



        </div>








        <!-- FOTO DAN PENGATURAN -->


        <div class="rounded-2xl border border-slate-200 bg-white">


            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="font-semibold text-slate-900">
                    Foto & Pengaturan
                </h2>

            </div>




            <div class="grid gap-6 p-6 lg:grid-cols-3">



                <div>


                    <label class="mb-2 block font-medium text-slate-700">
                        Foto
                    </label>


                    <input
                        type="file"
                        name="photo"
                        accept="image/*"
                        class="w-full rounded-xl border px-4 py-3">


                    <p class="mt-2 text-sm text-slate-500">
                        Maksimal 2 MB.
                    </p>


                </div>





                <div>


                    <label class="mb-2 block font-medium text-slate-700">
                        Urutan Tampilan
                    </label>


                    <input
                        type="number"
                        name="sort_order"
                        value="0"
                        class="w-full rounded-xl border px-4 py-3">


                </div>





                <div>


                    <label class="mb-2 block font-medium text-slate-700">
                        Status
                    </label>


                    <select
                        name="status"
                        class="w-full rounded-xl border px-4 py-3">


                        <option value="Aktif">
                            Aktif
                        </option>


                        <option value="Tidak Aktif">
                            Tidak Aktif
                        </option>


                    </select>


                </div>



            </div>



        </div>






    </form>



</main>


<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>