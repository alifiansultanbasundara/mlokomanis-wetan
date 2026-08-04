<?php

require_once '../../../config/app.php';


// ==========================
// VALIDASI INSTITUTION ID
// ==========================

if (!isset($_GET['id'])) {

    header("Location: index.php");
    exit;
}


$institution_id = (int) $_GET['id'];


// Ambil data lembaga

$query = mysqli_query($conn, "

    SELECT id, name, category
    FROM village_institutions
    WHERE id = '$institution_id'

");


$institution = mysqli_fetch_assoc($query);



if (!$institution) {

    header("Location: index.php");
    exit;
}

$title = "Tambah Lembaga Desa";
$page  = "lembaga-desa";

include APP_PATH . 'includes/admin/layout-top.php';

?>



<main class="p-8 space-y-8">



    <!-- HEADER -->

    <div class="flex items-center justify-between">


        <div>

            <h1 class="text-3xl font-bold text-slate-900">

                Tambah Anggota Lembaga

            </h1>


            <p class="mt-2 text-slate-500">

                Tambahkan anggota untuk lembaga
                <?= htmlspecialchars($institution['name']); ?>

            </p>


        </div>




        <a href="detail.php?id=<?= $institution_id; ?>"
            class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 hover:bg-slate-50">


            Kembali


        </a>

        <button
            form="formAnggota"
            type="submit"
            class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white transition hover:bg-teal-700">

            Simpan Anggota

        </button>



    </div>









    <form
        id="formAnggota"
        action="member-store.php"

        method="POST"

        enctype="multipart/form-data"

        class="grid gap-8 lg:grid-cols-3">


        <input
            type="hidden"
            name="institution_id"
            value="<?= $institution_id; ?>">






        <!-- LEFT -->

        <div class="space-y-8 lg:col-span-2">





            <!-- INFORMASI ANGGOTA -->

            <div class="rounded-2xl border bg-white">


                <div class="border-b px-6 py-5">


                    <h2 class="text-lg font-semibold text-slate-900">

                        Informasi Anggota

                    </h2>


                </div>




                <div class="space-y-5 p-6">





                    <!-- Nama -->

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

                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600">


                    </div>






                    <!-- Jabatan -->

                    <div>


                        <label class="mb-2 block font-medium text-slate-700">

                            Jabatan Dalam Lembaga

                        </label>


                        <input

                            type="text"

                            name="position"

                            placeholder="Contoh: Ketua, Sekretaris, Anggota"

                            class="w-full rounded-xl border border-slate-300 px-4 py-3">


                    </div>







                    <!-- Gender -->

                    <div>


                        <label class="mb-2 block font-medium text-slate-700">

                            Jenis Kelamin

                        </label>


                        <select

                            name="gender"

                            class="w-full rounded-xl border border-slate-300 px-4 py-3">


                            <option value="">

                                -- Pilih Jenis Kelamin --

                            </option>


                            <option value="Laki-laki">

                                Laki-laki

                            </option>


                            <option value="Perempuan">

                                Perempuan

                            </option>


                        </select>


                    </div>







                    <!-- Alamat -->


                    <div>


                        <label class="mb-2 block font-medium text-slate-700">

                            Alamat

                        </label>


                        <textarea

                            name="address"

                            rows="4"

                            placeholder="Masukkan alamat anggota"

                            class="w-full rounded-xl border border-slate-300 px-4 py-3"></textarea>


                    </div>




                </div>


            </div>










            <!-- KONTAK -->


            <div class="rounded-2xl border bg-white">


                <div class="border-b px-6 py-5">


                    <h2 class="text-lg font-semibold">

                        Kontak Anggota

                    </h2>


                </div>




                <div class="grid gap-5 p-6 md:grid-cols-2">



                    <div>


                        <label class="mb-2 block font-medium">

                            Nomor Telepon

                        </label>


                        <input

                            type="text"

                            name="phone"

                            placeholder="08xxxxxxxxxx"

                            class="w-full rounded-xl border px-4 py-3">


                    </div>





                    <div>


                        <label class="mb-2 block font-medium">

                            Urutan Tampilan

                        </label>


                        <input

                            type="number"

                            name="sort_order"

                            value="0"

                            class="w-full rounded-xl border px-4 py-3">


                    </div>




                </div>


            </div>






        </div>










        <!-- RIGHT -->


        <div class="space-y-8">





            <!-- DETAIL -->

            <div class="rounded-2xl border bg-white">


                <div class="border-b px-6 py-5">


                    <h2 class="font-semibold">

                        Detail

                    </h2>


                </div>




                <div class="space-y-5 p-6">





                    <div>


                        <label class="mb-2 block font-medium">

                            Status

                        </label>


                        <select

                            name="status"

                            class="w-full rounded-xl border px-4 py-3">


                            <option value="Active">

                                Aktif

                            </option>


                            <option value="Inactive">

                                Tidak Aktif

                            </option>


                        </select>


                    </div>





                    <div>


                        <label class="mb-2 block font-medium">

                            Lembaga

                        </label>


                        <input

                            type="text"

                            readonly

                            value="<?= htmlspecialchars($institution['name']); ?>"

                            class="w-full rounded-xl border bg-slate-100 px-4 py-3 text-slate-500">


                    </div>




                </div>


            </div>








            <!-- FOTO -->


            <div class="rounded-2xl border bg-white">


                <div class="border-b px-6 py-5">


                    <h2 class="font-semibold">

                        Foto Anggota

                    </h2>


                </div>




                <div class="p-6">


                    <label class="mb-2 block font-medium">

                        Upload Foto

                    </label>



                    <input

                        type="file"

                        name="photo"

                        accept="image/*"

                        class="w-full rounded-xl border px-4 py-3">



                    <p class="mt-2 text-sm text-slate-500">

                        Format JPG/PNG maksimal 2MB.

                    </p>


                </div>


            </div>







        </div>





    </form>



</main>

<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>