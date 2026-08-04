<?php

require_once '../../../config/app.php';

$title = "Pembangunan Desa";
$page  = "pembangunan";

include APP_PATH . 'includes/admin/layout-top.php';


// =====================================
// Pagination
// =====================================

$limit = 10;

$pageNumber = isset($_GET['page'])
    ? (int) $_GET['page']
    : 1;


if ($pageNumber < 1) {
    $pageNumber = 1;
}


$offset = ($pageNumber - 1) * $limit;




// =====================================
// Filter
// =====================================

$search = isset($_GET['search'])
    ? mysqli_real_escape_string($conn, $_GET['search'])
    : "";


$category = isset($_GET['category'])
    ? mysqli_real_escape_string($conn, $_GET['category'])
    : "";


$status = isset($_GET['status'])
    ? mysqli_real_escape_string($conn, $_GET['status'])
    : "";




// =====================================
// WHERE
// =====================================

$where = "WHERE 1=1";



if (!empty($search)) {

    $where .= "

    AND (

        p.title LIKE '%$search%'
        OR p.location LIKE '%$search%'
        OR p.description LIKE '%$search%'

    )

    ";
}




if (!empty($category)) {

    $where .= "

    AND p.category='$category'

    ";
}



if (!empty($status)) {

    $where .= "

    AND p.status='$status'

    ";
}





// =====================================
// Total Data
// =====================================

$totalQuery = mysqli_query(
    $conn,
    "

    SELECT COUNT(*) AS total

    FROM constructions p

    $where

    "
);


$totalData = mysqli_fetch_assoc($totalQuery)['total'];


$totalPage = ceil(
    $totalData / $limit
);





// =====================================
// Data
// =====================================

$query = mysqli_query(
    $conn,
    "

    SELECT

        p.*,

        u.username AS author


    FROM constructions p


    LEFT JOIN users u

    ON u.id = p.created_by


    $where


    ORDER BY p.created_at DESC


    LIMIT $offset,$limit

    "
);





// =====================================
// Statistik
// =====================================


$totalPembangunan = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) total FROM constructions"
    )
)['total'];



$selesai = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) total
        FROM constructions
        WHERE status='Selesai'
        "
    )
)['total'];



$berjalan = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) total
        FROM constructions
        WHERE status='Berjalan'
        "
    )
)['total'];


?>



<div class="p-8">


    <!-- HEADER -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h2 class="text-3xl font-bold text-slate-900">

                Pembangunan Desa

            </h2>


            <p class="mt-2 text-slate-500">

                Kelola data pembangunan dan kegiatan desa.

            </p>


        </div>



        <a

            href="create.php"

            class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

            Tambah Pembangunan

        </a>



    </div>





    <!-- STATISTIK -->


    <div class="mb-8 grid gap-6 md:grid-cols-3">



        <div class="rounded-2xl bg-white p-6 border">

            <p class="text-sm text-slate-500">

                Total Pembangunan

            </p>


            <h3 class="mt-2 text-3xl font-bold">

                <?= $totalPembangunan; ?>

            </h3>

        </div>




        <div class="rounded-2xl bg-white p-6 border">

            <p class="text-sm text-slate-500">

                Sedang Berjalan

            </p>


            <h3 class="mt-2 text-3xl font-bold text-amber-600">

                <?= $berjalan; ?>

            </h3>

        </div>




        <div class="rounded-2xl bg-white p-6 border">

            <p class="text-sm text-slate-500">

                Selesai

            </p>


            <h3 class="mt-2 text-3xl font-bold text-emerald-600">

                <?= $selesai; ?>

            </h3>

        </div>



    </div>








    <!-- FILTER -->

    <div class="mb-6 rounded-2xl border bg-white p-6">


        <form method="GET" class="grid gap-4 md:grid-cols-4">



            <input

                type="text"

                name="search"

                value="<?= htmlspecialchars($search); ?>"

                placeholder="Cari pembangunan..."

                class="rounded-xl border px-4 py-3">





            <select

                name="category"

                class="rounded-xl border px-4 py-3">


                <option value="">

                    Semua Kategori

                </option>


                <?php

                $categories = [

                    'Infrastruktur',
                    'Sarana Prasarana',
                    'Pemberdayaan',
                    'Pemerintahan',
                    'Lainnya'

                ];


                foreach ($categories as $item):

                ?>


                    <option

                        value="<?= $item; ?>"

                        <?= $category == $item ? 'selected' : ''; ?>>

                        <?= $item; ?>

                    </option>


                <?php endforeach; ?>


            </select>






            <select

                name="status"

                class="rounded-xl border px-4 py-3">


                <option value="">

                    Semua Status

                </option>


                <option value="Perencanaan"
                    <?= $status == "Perencanaan" ? 'selected' : ''; ?>>

                    Perencanaan

                </option>


                <option value="Berjalan"
                    <?= $status == "Berjalan" ? 'selected' : ''; ?>>

                    Berjalan

                </option>


                <option value="Selesai"
                    <?= $status == "Selesai" ? 'selected' : ''; ?>>

                    Selesai

                </option>


                <option value="Ditunda"
                    <?= $status == "Ditunda" ? 'selected' : ''; ?>>

                    Ditunda

                </option>


            </select>






            <button

                class="rounded-xl bg-slate-900 px-5 py-3 text-white">

                Filter

            </button>



        </form>


    </div>








    <!-- TABLE -->


    <div class="overflow-x-auto rounded-2xl border bg-white">


        <table class="min-w-full">


            <thead class="bg-slate-50">


                <tr class="text-left text-sm font-semibold text-slate-600">


                    <th class="px-6 py-4">
                        No
                    </th>


                    <th class="px-6 py-4">
                        Pembangunan
                    </th>


                    <th class="px-6 py-4">
                        Kategori
                    </th>


                    <th class="px-6 py-4">
                        Tahun
                    </th>


                    <th class="px-6 py-4">
                        Anggaran
                    </th>


                    <th class="px-6 py-4">
                        Progress
                    </th>


                    <th class="px-6 py-4">
                        Status
                    </th>


                    <th class="px-6 py-4">
                        Aksi
                    </th>


                </tr>


            </thead>




            <tbody class="divide-y divide-slate-100">



                <?php


                $no = $offset + 1;


                if (mysqli_num_rows($query) > 0):

                    while ($row = mysqli_fetch_assoc($query)):


                ?>


                        <tr class="hover:bg-slate-50">



                            <td class="px-6 py-5">

                                <?= $no++; ?>

                            </td>





                            <td class="px-6 py-5">


                                <div class="font-semibold text-slate-900">

                                    <?= htmlspecialchars($row['title']); ?>

                                </div>


                                <p class="text-sm text-slate-500">

                                    <?= htmlspecialchars($row['location']); ?>

                                </p>


                            </td>





                            <td class="px-6 py-5">


                                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm">

                                    <?= $row['category']; ?>

                                </span>


                            </td>





                            <td class="px-6 py-5">

                                <?= $row['year']; ?>

                            </td>





                            <td class="px-6 py-5">

                                Rp <?= number_format($row['budget'], 0, ',', '.'); ?>

                            </td>





                            <td class="px-6 py-5">


                                <div class="w-32 rounded-full bg-slate-200">


                                    <div

                                        class="rounded-full bg-teal-600 px-2 py-1 text-xs text-white"

                                        style="width:<?= $row['progress']; ?>%">


                                        <?= $row['progress']; ?>%


                                    </div>


                                </div>


                            </td>





                            <td class="px-6 py-5">


                                <span class="rounded-full px-3 py-1 text-sm font-medium

<?= $row['status'] == 'Selesai'
                            ? 'bg-emerald-100 text-emerald-700'
                            : (
                                $row['status'] == 'Berjalan'
                                ? 'bg-amber-100 text-amber-700'
                                : 'bg-slate-100 text-slate-700'
                            );
?>">


                                    <?= $row['status']; ?>


                                </span>


                            </td>






                            <td class="px-6 py-5">


                                <div class="flex gap-2">


                                    <a

                                        href="detail.php?slug=<?= $row['slug']; ?>"

                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-100 text-sky-600">


                                        <i class="bi bi-eye"></i>


                                    </a>




                                    <a

                                        href="edit.php?slug=<?= $row['slug']; ?>"

                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-500 text-white">


                                        <i class="bi bi-pencil"></i>


                                    </a>





                                    <a

                                        onclick="return confirm('Hapus pembangunan ini?')"

                                        href="delete.php?slug=<?= $row['slug']; ?>"

                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-500 text-white">


                                        <i class="bi bi-trash"></i>


                                    </a>



                                </div>


                            </td>



                        </tr>


                    <?php endwhile;
                else: ?>


                    <tr>

                        <td colspan="8" class="px-6 py-20 text-center">


                            Belum ada data pembangunan.


                        </td>

                    </tr>


                <?php endif; ?>


            </tbody>


        </table>


    </div>






    <!-- PAGINATION -->

    <div class="mt-6 flex justify-center gap-2">


        <?php for ($i = 1; $i <= $totalPage; $i++): ?>


            <a

                href="?page=<?= $i; ?>&search=<?= $search; ?>&category=<?= $category; ?>&status=<?= $status; ?>"

                class="rounded-lg px-4 py-2

<?= $pageNumber == $i
                ? 'bg-teal-600 text-white'
                : 'bg-white border'; ?>">


                <?= $i; ?>


            </a>


        <?php endfor; ?>


    </div>




</div>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>