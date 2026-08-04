<?php

require_once '../../../config/app.php';


// ===============================
// Filter
// ===============================

$search = $_GET['search'] ?? '';

$category = $_GET['category'] ?? '';



$where = [];



if (!empty($search)) {

    $searchSafe = mysqli_real_escape_string(
        $conn,
        $search
    );


    $where[] = "
        (
            name LIKE '%$searchSafe%'
            OR position LIKE '%$searchSafe%'
        )
    ";
}



if (!empty($category)) {


    $categorySafe = mysqli_real_escape_string(
        $conn,
        $category
    );


    $where[] =
        "category='$categorySafe'";
}



$whereSql = "";

if (count($where) > 0) {

    $whereSql =
        "WHERE " . implode(
            " AND ",
            $where
        );
}




// ===============================
// Data
// ===============================

$query = mysqli_query(
    $conn,
    "
    SELECT *
    FROM village_officials

    $whereSql

    ORDER BY
        CAST(sort_order AS UNSIGNED) ASC,
        id ASC
    "
);


// ===============================
// Statistik
// ===============================


$total = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) total
        FROM village_officials
        "
    )
)['total'] ?? 0;



$aktif = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) total
        FROM village_officials
        WHERE status='Aktif'
        "
    )
)['total'] ?? 0;



$categoryCount = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT COUNT(DISTINCT category) total
        FROM village_officials
        "
    )
)['total'] ?? 0;




// ===============================
// Category
// ===============================

$categories = mysqli_query(
    $conn,

    "
    SELECT DISTINCT category
    FROM village_officials
    ORDER BY category ASC
    "
);





$title = "Struktur Organisasi Desa";
$page  = "struktur-organisasi";


include APP_PATH . 'includes/admin/layout-top.php';

?>



<main class="space-y-8 p-8">


    <!-- HEADER -->

    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h1 class="text-3xl font-bold text-slate-900">

                Struktur Organisasi Desa

            </h1>


            <p class="mt-2 text-slate-500">

                Kelola perangkat desa dan susunan organisasi pemerintahan desa.

            </p>

        </div>



        <a href="create.php"

            class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

            <i class="bi bi-plus-lg"></i>

            Tambah Perangkat

        </a>


    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="rounded-xl border border-teal-200 bg-teal-50 px-5 py-4 text-teal-700">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= $_SESSION['success']; ?>
        </div>
    <?php unset($_SESSION['success']);
    endif; ?>






    <!-- STATISTIC -->


    <div class="grid gap-6 md:grid-cols-3">


        <div class="rounded-2xl border bg-white p-6">


            <div class="mb-3 text-3xl text-teal-600">

                <i class="bi bi-people"></i>

            </div>


            <h3 class="text-3xl font-bold text-slate-900">

                <?= $total ?>

            </h3>


            <p class="text-slate-500">

                Total Perangkat

            </p>


        </div>





        <div class="rounded-2xl border bg-white p-6">


            <div class="mb-3 text-3xl text-emerald-600">

                <i class="bi bi-person-check"></i>

            </div>


            <h3 class="text-3xl font-bold text-slate-900">

                <?= $aktif ?>

            </h3>


            <p class="text-slate-500">

                Perangkat Aktif

            </p>


        </div>





        <div class="rounded-2xl border bg-white p-6">


            <div class="mb-3 text-3xl text-indigo-600">

                <i class="bi bi-diagram-3"></i>

            </div>


            <h3 class="text-3xl font-bold text-slate-900">

                <?= $categoryCount ?>

            </h3>


            <p class="text-slate-500">

                Kategori Jabatan

            </p>


        </div>



    </div>









    <!-- FILTER -->

    <div class="rounded-2xl border bg-white p-6">


        <form method="GET"

            class="grid gap-4 md:grid-cols-3">


            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">

                    Cari

                </label>


                <input

                    type="text"

                    name="search"

                    value="<?= htmlspecialchars($search) ?>"

                    placeholder="Cari nama atau jabatan..."

                    class="w-full rounded-xl border px-4 py-3 focus:border-teal-600 outline-none">


            </div>





            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">

                    Kategori

                </label>


                <select

                    name="category"

                    class="w-full rounded-xl border px-4 py-3">


                    <option value="">

                        Semua Kategori

                    </option>


                    <?php while ($cat = mysqli_fetch_assoc($categories)): ?>

                        <option

                            value="<?= htmlspecialchars($cat['category']) ?>"

                            <?= $category == $cat['category'] ? 'selected' : '' ?>>

                            <?= htmlspecialchars($cat['category']) ?>

                        </option>


                    <?php endwhile; ?>


                </select>


            </div>





            <div class="flex items-end gap-3">


                <button

                    class="rounded-xl bg-teal-600 px-6 py-3 text-white">

                    <i class="bi bi-search"></i>

                    Filter

                </button>



                <a href="index.php"

                    class="rounded-xl border px-6 py-3">

                    Reset

                </a>


            </div>



        </form>


    </div>









    <!-- TABLE -->


    <div class="overflow-hidden rounded-2xl border bg-white">


        <div class="overflow-x-auto">


            <table class="w-full text-left">


                <thead class="bg-slate-50 text-sm text-slate-600">


                    <tr>


                        <th class="px-6 py-4">

                            No

                        </th>


                        <th class="px-6 py-4">

                            Perangkat Desa

                        </th>


                        <th class="px-6 py-4">

                            Jabatan

                        </th>


                        <th class="px-6 py-4">

                            Kategori

                        </th>


                        <th class="px-6 py-4">

                            Status

                        </th>


                        <th class="px-6 py-4 text-center">

                            Aksi

                        </th>


                    </tr>


                </thead>




                <tbody class="divide-y divide-slate-100">


                    <?php


                    $no = 1;


                    if (mysqli_num_rows($query) > 0):

                        while ($row = mysqli_fetch_assoc($query)):


                    ?>


                            <tr class="hover:bg-slate-50">


                                <td class="px-6 py-5">

                                    <?= $no++; ?>

                                </td>





                                <td class="px-6 py-5">


                                    <div class="flex items-center gap-4">


                                        <?php if (!empty($row['photo'])): ?>


                                            <img

                                                src="<?= APP_URL ?>uploads/village/officials/<?= htmlspecialchars($row['photo']) ?>"

                                                class="h-14 w-14 rounded-xl object-cover">


                                        <?php else: ?>


                                            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-slate-100">

                                                <i class="bi bi-person text-2xl text-slate-400"></i>

                                            </div>


                                        <?php endif; ?>


                                        <div>

                                            <h4 class="font-semibold text-slate-900">

                                                <?= htmlspecialchars($row['name']) ?>

                                            </h4>


                                            <p class="text-sm text-slate-500">

                                                <?= htmlspecialchars($row['nip'] ?? '-') ?>

                                            </p>


                                        </div>


                                    </div>


                                </td>





                                <td class="px-6 py-5 font-medium">

                                    <?= htmlspecialchars($row['position']) ?>

                                </td>





                                <td class="px-6 py-5">


                                    <span class="rounded-full bg-teal-100 px-3 py-1 text-sm text-teal-700">

                                        <?= htmlspecialchars($row['category']) ?>

                                    </span>


                                </td>





                                <td class="px-6 py-5">


                                    <?php if ($row['status'] == "Aktif"): ?>


                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm text-emerald-700">

                                            Aktif

                                        </span>


                                    <?php else: ?>


                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-600">

                                            Nonaktif

                                        </span>


                                    <?php endif; ?>


                                </td>





                                <td class="px-6 py-5">


                                    <div class="flex justify-center gap-2">


                                        <a href="detail.php?id=<?= $row['id'] ?>"

                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-100 text-sky-600">

                                            <i class="bi bi-eye"></i>

                                        </a>


                                        <a href="edit.php?id=<?= $row['id'] ?>"

                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-500 text-white">

                                            <i class="bi bi-pencil"></i>

                                        </a>




                                        <a href="delete.php?id=<?= $row['id'] ?>"

                                            onclick="return confirm('Hapus data perangkat desa?')"

                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-500 text-white">

                                            <i class="bi bi-trash"></i>

                                        </a>


                                    </div>


                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <tr>

                            <td colspan="6"

                                class="px-6 py-20 text-center">


                                <i class="bi bi-people text-5xl text-slate-300"></i>


                                <h3 class="mt-4 text-lg font-semibold text-slate-700">

                                    Belum ada data perangkat desa

                                </h3>


                                <p class="text-slate-500">

                                    Silakan tambahkan struktur organisasi.

                                </p>


                            </td>

                        </tr>


                    <?php endif; ?>


                </tbody>


            </table>


        </div>


    </div>



</main>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>