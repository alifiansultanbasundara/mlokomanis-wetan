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
// Ambil Data Perangkat
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

$parent = null;


if (!empty($data['parent_id'])) {


    $parentQuery = mysqli_query(
        $conn,

        "
        SELECT *
        FROM village_officials
        WHERE id='{$data['parent_id']}'
        LIMIT 1
        "
    );


    $parent = mysqli_fetch_assoc($parentQuery);
}




// ===============================
// Ambil Bawahan
// ===============================

$children = mysqli_query(
    $conn,

    "
    SELECT *
    FROM village_officials

    WHERE parent_id='$id'

    ORDER BY sort_order ASC

    "

);





// ===============================
// Layout
// ===============================

$title = "Detail Struktur Organisasi";

$page = "struktur-organisasi";


include APP_PATH . 'includes/admin/layout-top.php';


?>



<main class="space-y-8 p-8">



    <!-- HEADER -->

    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h1 class="text-3xl font-bold text-slate-900">

                Detail Perangkat Desa

            </h1>


            <p class="mt-2 text-slate-500">

                Informasi lengkap perangkat pemerintahan desa.

            </p>


        </div>



        <div class="flex gap-3">


            <a href="index.php"

                class="rounded-xl border border-slate-300 bg-white px-6 py-3 font-medium text-slate-700 hover:bg-slate-50">

                Kembali

            </a>


            <a href="edit.php?id=<?= $data['id'] ?>"

                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

                <i class="bi bi-pencil"></i>

                Edit

            </a>


        </div>


    </div>










    <div class="grid gap-8 lg:grid-cols-3">





        <!-- PROFILE -->

        <div class="lg:col-span-2 space-y-8">


            <div class="overflow-hidden rounded-2xl border bg-white">



                <div class="bg-gradient-to-r from-teal-600 to-emerald-600 p-8 text-white">


                    <div class="flex flex-col gap-6 md:flex-row md:items-center">


                        <?php if (!empty($data['photo'])): ?>


                            <img

                                src="<?= APP_URL ?>uploads/village/officials/<?= htmlspecialchars($data['photo']) ?>"

                                class="h-36 w-36 rounded-2xl object-cover border-4 border-white/30">


                        <?php else: ?>


                            <div class="flex h-36 w-36 items-center justify-center rounded-2xl bg-white/20">

                                <i class="bi bi-person text-6xl"></i>

                            </div>


                        <?php endif; ?>




                        <div>


                            <h2 class="text-3xl font-bold">

                                <?= htmlspecialchars($data['name']) ?>

                            </h2>


                            <p class="mt-2 text-lg text-white/80">

                                <?= htmlspecialchars($data['position']) ?>

                            </p>


                            <span class="mt-4 inline-flex rounded-full bg-white/20 px-4 py-2 text-sm">

                                <?= htmlspecialchars($data['category']) ?>

                            </span>


                        </div>



                    </div>


                </div>







                <div class="grid gap-6 p-8 md:grid-cols-2">



                    <div>

                        <p class="text-sm text-slate-500">

                            NIP / Identitas

                        </p>

                        <p class="font-medium text-slate-900">

                            <?= htmlspecialchars($data['nip'] ?: '-') ?>

                        </p>

                    </div>





                    <div>

                        <p class="text-sm text-slate-500">

                            Pendidikan

                        </p>


                        <p class="font-medium text-slate-900">

                            <?= htmlspecialchars($data['education'] ?: '-') ?>

                        </p>

                    </div>





                    <div>

                        <p class="text-sm text-slate-500">

                            Jenis Kelamin

                        </p>


                        <p class="font-medium text-slate-900">

                            <?= htmlspecialchars($data['gender'] ?: '-') ?>

                        </p>

                    </div>





                    <div>

                        <p class="text-sm text-slate-500">

                            Tanggal Lahir

                        </p>


                        <p class="font-medium text-slate-900">

                            <?= !empty($data['birth_date'])
                                ? date('d F Y', strtotime($data['birth_date']))
                                : '-';
                            ?>

                        </p>

                    </div>




                    <div class="md:col-span-2">


                        <p class="text-sm text-slate-500">

                            Alamat

                        </p>


                        <p class="leading-relaxed text-slate-800">

                            <?= nl2br(htmlspecialchars($data['address'] ?: '-')) ?>

                        </p>


                    </div>



                </div>


            </div>








            <!-- ATASAN -->

            <div class="rounded-2xl border bg-white">


                <div class="border-b px-6 py-5">


                    <h3 class="font-semibold text-slate-900">

                        Atasan Langsung

                    </h3>


                </div>



                <div class="p-6">


                    <?php if ($parent): ?>


                        <a href="detail.php?id=<?= $parent['id'] ?>"

                            class="flex items-center gap-4 rounded-xl bg-slate-50 p-4 hover:bg-slate-100">


                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-100 text-teal-600">

                                <i class="bi bi-person"></i>

                            </div>


                            <div>


                                <h4 class="font-semibold text-slate-900">

                                    <?= htmlspecialchars($parent['name']) ?>

                                </h4>


                                <p class="text-sm text-slate-500">

                                    <?= htmlspecialchars($parent['position']) ?>

                                </p>


                            </div>


                        </a>


                    <?php else: ?>


                        <p class="text-slate-500">

                            Tidak memiliki atasan.

                        </p>


                    <?php endif; ?>


                </div>


            </div>









            <!-- BAWAHAN -->

            <div class="rounded-2xl border bg-white">


                <div class="border-b px-6 py-5">


                    <h3 class="font-semibold text-slate-900">

                        Bawahan Langsung

                    </h3>


                </div>




                <div class="space-y-4 p-6">


                    <?php if (mysqli_num_rows($children) > 0): ?>


                        <?php while ($child = mysqli_fetch_assoc($children)): ?>


                            <a href="detail.php?id=<?= $child['id'] ?>"

                                class="flex items-center gap-4 rounded-xl bg-slate-50 p-4 hover:bg-slate-100">


                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">

                                    <i class="bi bi-person"></i>

                                </div>



                                <div>


                                    <h4 class="font-semibold text-slate-900">

                                        <?= htmlspecialchars($child['name']) ?>

                                    </h4>


                                    <p class="text-sm text-slate-500">

                                        <?= htmlspecialchars($child['position']) ?>

                                    </p>


                                </div>


                            </a>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <p class="text-slate-500">

                            Tidak memiliki bawahan.

                        </p>


                    <?php endif; ?>


                </div>


            </div>




        </div>









        <!-- SIDEBAR -->

        <div class="space-y-6">



            <div class="rounded-2xl border bg-white">


                <div class="border-b px-6 py-5">

                    <h3 class="font-semibold">

                        Informasi Jabatan

                    </h3>

                </div>




                <div class="space-y-5 p-6">



                    <div>

                        <p class="text-sm text-slate-500">

                            Kategori

                        </p>

                        <p class="font-medium">

                            <?= htmlspecialchars($data['category']) ?>

                        </p>

                    </div>





                    <div>

                        <p class="text-sm text-slate-500">

                            Status

                        </p>


                        <span class="<?= $data['status'] == 'Aktif'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-slate-100 text-slate-700'
                                        ?> rounded-full px-3 py-1 text-sm">


                            <?= $data['status'] == 'Aktif'
                                ? 'Aktif'
                                : 'Nonaktif'
                            ?>


                        </span>


                    </div>





                    <div>

                        <p class="text-sm text-slate-500">

                            Urutan Tampilan

                        </p>


                        <p class="font-medium">

                            <?= $data['sort_order'] ?>

                        </p>


                    </div>





                    <div>

                        <p class="text-sm text-slate-500">

                            Dibuat

                        </p>


                        <p class="font-medium">

                            <?= date('d F Y', strtotime($data['created_at'])) ?>

                        </p>


                    </div>





                    <div>

                        <p class="text-sm text-slate-500">

                            Diperbarui

                        </p>


                        <p class="font-medium">

                            <?= date('d F Y', strtotime($data['updated_at'])) ?>

                        </p>


                    </div>



                </div>


            </div>



        </div>





    </div>


</main>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>