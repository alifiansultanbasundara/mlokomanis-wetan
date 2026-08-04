<?php

require_once '../../../config/app.php';


// ===============================
// Validasi ID
// ===============================

if (!isset($_GET['id']) || empty($_GET['id'])) {

    header("Location: index.php");
    exit;
}


$id = (int) $_GET['id'];



// ===============================
// Ambil Data
// ===============================

$query = mysqli_query(
    $conn,

    "
    SELECT *
    FROM village_officials
    WHERE id='$id'
    LIMIT 1
    "

);



if (!$query || mysqli_num_rows($query) == 0) {

    header("Location: index.php");
    exit;
}



$data = mysqli_fetch_assoc($query);




// ===============================
// Ambil Atasan
// ===============================

$parents = mysqli_query(
    $conn,

    "
    SELECT id,name,position
    FROM village_officials
    WHERE id != '$id'
    ORDER BY sort_order ASC
    "

);



// ===============================
// Layout
// ===============================

$title = "Edit Struktur Organisasi";
$page  = "struktur-organisasi";


include APP_PATH . 'includes/admin/layout-top.php';


?>



<main class="space-y-8 p-8">


    <!-- HEADER -->

    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h1 class="text-3xl font-bold text-slate-900">

                Edit Perangkat Desa

            </h1>


            <p class="mt-2 text-slate-500">

                Perbarui data struktur organisasi pemerintahan desa.

            </p>


        </div>




        <a href="index.php"

            class="rounded-xl border border-slate-300 bg-white px-6 py-3 font-medium text-slate-700 hover:bg-slate-50">

            Kembali

        </a>


    </div>







    <form

        action="update.php"

        method="POST"

        enctype="multipart/form-data"

        class="grid gap-8 lg:grid-cols-3">


        <input

            type="hidden"

            name="id"

            value="<?= $data['id']; ?>">






        <!-- LEFT -->

        <div class="space-y-8 lg:col-span-2">


            <div class="rounded-2xl border bg-white">


                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold text-slate-900">

                        Informasi Perangkat Desa

                    </h2>

                </div>




                <div class="space-y-5 p-6">





                    <div>

                        <label class="mb-2 block font-medium text-slate-700">

                            Nama Lengkap

                            <span class="text-red-500">*</span>

                        </label>


                        <input

                            type="text"

                            name="name"

                            required

                            value="<?= htmlspecialchars($data['name'] ?? '') ?>"

                            class="w-full rounded-xl border px-4 py-3 focus:border-teal-600 outline-none">


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

                            value="<?= htmlspecialchars($data['position'] ?? '') ?>"

                            class="w-full rounded-xl border px-4 py-3 focus:border-teal-600 outline-none">


                    </div>







                    <div>

                        <label class="mb-2 block font-medium text-slate-700">

                            NIP / Identitas

                        </label>


                        <input

                            type="text"

                            name="nip"

                            value="<?= htmlspecialchars($data['nip'] ?? '') ?>"

                            class="w-full rounded-xl border px-4 py-3">


                    </div>







                    <div>

                        <label class="mb-2 block font-medium text-slate-700">

                            Pendidikan Terakhir

                        </label>


                        <input

                            type="text"

                            name="education"

                            value="<?= htmlspecialchars($data['education'] ?? '') ?>"

                            class="w-full rounded-xl border px-4 py-3">


                    </div>







                    <div>

                        <label class="mb-2 block font-medium text-slate-700">

                            Alamat

                        </label>


                        <textarea

                            name="address"

                            rows="4"

                            class="w-full rounded-xl border px-4 py-3"><?= htmlspecialchars($data['address'] ?? '') ?></textarea>


                    </div>





                </div>


            </div>





        </div>










        <!-- RIGHT -->

        <div class="space-y-8">


            <div class="rounded-2xl border bg-white">


                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold text-slate-900">

                        Detail Jabatan

                    </h2>

                </div>




                <div class="space-y-5 p-6">





                    <div>

                        <label class="mb-2 block font-medium">

                            Kategori

                        </label>


                        <select

                            name="category"

                            class="w-full rounded-xl border px-4 py-3">


                            <?php

                            $categories = [

                                'Kepala Desa',
                                'Sekretariat Desa',
                                'Kepala Urusan',
                                'Kepala Seksi',
                                'Kepala Dusun',
                                'Staf Desa',
                                'BPD',
                                'Lainnya'

                            ];


                            foreach ($categories as $cat):

                            ?>


                                <option

                                    value="<?= $cat ?>"

                                    <?= $data['category'] == $cat ? 'selected' : '' ?>>

                                    <?= $cat ?>

                                </option>


                            <?php endforeach; ?>


                        </select>


                    </div>








                    <div>

                        <label class="mb-2 block font-medium">

                            Atasan

                        </label>


                        <select

                            name="parent_id"

                            class="w-full rounded-xl border px-4 py-3">


                            <option value="">

                                Tidak Ada

                            </option>


                            <?php while ($parent = mysqli_fetch_assoc($parents)): ?>


                                <option

                                    value="<?= $parent['id'] ?>"

                                    <?= $data['parent_id'] == $parent['id'] ? 'selected' : '' ?>>

                                    <?= htmlspecialchars($parent['name']) ?>

                                    -
                                    <?= htmlspecialchars($parent['position']) ?>


                                </option>


                            <?php endwhile; ?>


                        </select>


                    </div>








                    <div>

                        <label class="mb-2 block font-medium">

                            Urutan Tampilan

                        </label>


                        <input

                            type="number"

                            name="sort_order"

                            value="<?= $data['sort_order'] ?? 0 ?>"

                            class="w-full rounded-xl border px-4 py-3">


                    </div>








                    <div>

                        <label class="mb-2 block font-medium">

                            Status

                        </label>


                        <select

                            name="status"

                            class="w-full rounded-xl border px-4 py-3">


                            <option value="Aktif"

                                <?= $data['status'] == "Aktif" ? 'selected' : '' ?>>

                                Aktif

                            </option>


                            <option value="Tidak Aktif"

                                <?= $data['status'] == "Tidak Aktif" ? 'selected' : '' ?>>

                                Nonaktif

                            </option>


                        </select>


                    </div>



                </div>


            </div>








            <!-- FOTO -->

            <div class="rounded-2xl border bg-white">


                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold">

                        Foto

                    </h2>

                </div>




                <div class="p-6">



                    <?php if (!empty($data['photo'])): ?>


                        <img

                            src="<?= APP_URL ?>uploads/village/officials/<?= htmlspecialchars($data['photo']) ?>"

                            class="mb-5 h-48 w-full rounded-xl object-cover">


                    <?php endif; ?>





                    <input

                        type="file"

                        name="photo"

                        accept="image/*"

                        class="w-full rounded-xl border px-4 py-3">


                    <p class="mt-2 text-sm text-slate-500">

                        Kosongkan jika tidak ingin mengganti foto.

                    </p>



                </div>


            </div>







            <button

                type="submit"

                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">


                <i class="bi bi-save"></i>

                Simpan Perubahan


            </button>




        </div>





    </form>



</main>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>