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
    FROM village_institutions
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
// Layout
// ===============================

$title = "Edit Lembaga Desa";

$page = "lembaga-desa";


include APP_PATH . 'includes/admin/layout-top.php';


?>



<div class="p-8">


    <!-- Header -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h1 class="text-3xl font-bold text-slate-900">

                Edit Lembaga Desa

            </h1>


            <p class="mt-2 text-slate-500">

                Perbarui informasi lembaga desa.

            </p>


        </div>



        <div class="flex gap-3">


            <a
                href="index.php"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 hover:bg-slate-50">

                Kembali

            </a>



            <button
                form="formLembaga"
                type="submit"
                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

                Simpan Perubahan

            </button>


        </div>


    </div>







    <form

        id="formLembaga"

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





            <div class="rounded-2xl border border-slate-200 bg-white">


                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold text-slate-900">

                        Informasi Lembaga

                    </h2>

                </div>



                <div class="space-y-5 p-6">





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

                            value="<?= htmlspecialchars($data['name']); ?>"

                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600">

                    </div>







                    <div>

                        <label class="mb-2 block font-medium text-slate-700">

                            Slug

                        </label>


                        <input

                            type="text"

                            name="slug"

                            id="slug"

                            readonly

                            value="<?= htmlspecialchars($data['slug']); ?>"

                            class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-500">

                    </div>







                    <div>

                        <label class="mb-2 block font-medium text-slate-700">

                            Deskripsi

                        </label>


                        <textarea

                            name="description"

                            rows="6"

                            class="w-full rounded-xl border border-slate-300 px-4 py-3"><?= htmlspecialchars($data['description']); ?></textarea>


                    </div>





                </div>


            </div>









            <div class="rounded-2xl border border-slate-200 bg-white">


                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold text-slate-900">

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

                            value="<?= htmlspecialchars($data['chairman']); ?>"

                            class="w-full rounded-xl border px-4 py-3">

                    </div>





                    <div>

                        <label class="mb-2 block font-medium text-slate-700">

                            Sekretaris

                        </label>


                        <input

                            type="text"

                            name="secretary"

                            value="<?= htmlspecialchars($data['secretary']); ?>"

                            class="w-full rounded-xl border px-4 py-3">

                    </div>






                    <div>

                        <label class="mb-2 block font-medium text-slate-700">

                            Nomor Telepon

                        </label>


                        <input

                            type="text"

                            name="phone"

                            value="<?= htmlspecialchars($data['phone']); ?>"

                            class="w-full rounded-xl border px-4 py-3">

                    </div>





                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">

                            Email

                        </label>


                        <input

                            type="email"

                            name="email"

                            value="<?= htmlspecialchars($data['email']); ?>"

                            class="w-full rounded-xl border px-4 py-3">

                    </div>




                </div>


            </div>






        </div>









        <!-- RIGHT -->

        <div class="space-y-8">





            <div class="rounded-2xl border border-slate-200 bg-white">


                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold">

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

                            class="w-full rounded-xl border px-4 py-3">


                            <?php

                            $categories = [

                                'BPD',
                                'LPMD',
                                'PKK',
                                'Karang Taruna',
                                'RT/RW',
                                'Posyandu',
                                'Kelompok Tani',
                                'Lainnya'

                            ];


                            foreach ($categories as $cat):

                            ?>


                                <option

                                    value="<?= $cat; ?>"

                                    <?= $data['category'] == $cat ? 'selected' : '' ?>>

                                    <?= $cat; ?>

                                </option>


                            <?php endforeach; ?>


                        </select>


                    </div>








                    <div>

                        <label class="mb-2 block font-medium">

                            Jumlah Anggota

                        </label>


                        <input

                            type="number"

                            name="total_members"

                            value="<?= $data['total_members']; ?>"

                            class="w-full rounded-xl border px-4 py-3">


                    </div>







                    <div>

                        <label class="mb-2 block font-medium">

                            Status

                        </label>


                        <select

                            name="status"

                            class="w-full rounded-xl border px-4 py-3">


                            <option value="Active"
                                <?= $data['status'] == 'Active' ? 'selected' : '' ?>>

                                Aktif

                            </option>


                            <option value="Inactive"
                                <?= $data['status'] == 'Inactive' ? 'selected' : '' ?>>

                                Tidak Aktif

                            </option>


                        </select>


                    </div>







                    <div>

                        <label class="mb-2 block font-medium">

                            Urutan Tampilan

                        </label>


                        <input

                            type="number"

                            name="sort_order"

                            value="<?= $data['sort_order']; ?>"

                            class="w-full rounded-xl border px-4 py-3">


                    </div>



                </div>


            </div>









            <!-- FILE -->

            <div class="rounded-2xl border border-slate-200 bg-white">


                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold">

                        File Pendukung

                    </h2>

                </div>



                <div class="space-y-5 p-6">





                    <?php if (!empty($data['image'])): ?>


                        <div>


                            <p class="mb-2 text-sm text-slate-500">

                                Foto Saat Ini

                            </p>


                            <img

                                src="<?= APP_URL ?>uploads/village/institutions/<?= $data['image']; ?>"

                                class="h-40 w-full rounded-xl object-cover">


                        </div>


                    <?php endif; ?>





                    <div>

                        <label class="mb-2 block font-medium">

                            Ganti Foto

                        </label>


                        <input

                            type="file"

                            name="image"

                            accept="image/*"

                            class="w-full rounded-xl border px-4 py-3">


                    </div>






                    <?php if (!empty($data['document'])): ?>


                        <a

                            href="<?= APP_URL ?>uploads/village/institutions/<?= $data['document']; ?>"

                            target="_blank"

                            class="block rounded-xl bg-slate-100 p-4 text-sm text-teal-700">

                            <i class="bi bi-file-earmark-text"></i>

                            Lihat Dokumen Lama

                        </a>


                    <?php endif; ?>




                    <div>

                        <label class="mb-2 block font-medium">

                            Ganti Dokumen

                        </label>


                        <input

                            type="file"

                            name="document"

                            accept=".pdf,.doc,.docx"

                            class="w-full rounded-xl border px-4 py-3">


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