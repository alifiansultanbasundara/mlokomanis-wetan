<?php

require_once '../../../config/app.php';


// =====================================
// Filter
// =====================================

$keyword = $_GET['keyword'] ?? '';

$category = $_GET['category'] ?? '';

$year = $_GET['year'] ?? '';



$where = [];



if (!empty($keyword)) {

    $keyword = mysqli_real_escape_string(
        $conn,
        $keyword
    );

    $where[] = "
    (
        f.title LIKE '%$keyword%'
        OR f.description LIKE '%$keyword%'
        OR f.funding_source LIKE '%$keyword%'
    )
    ";
}



if (!empty($category)) {

    $category = mysqli_real_escape_string(
        $conn,
        $category
    );

    $where[] =
        "f.category='$category'";
}



if (!empty($year)) {

    $year = (int)$year;

    $where[] =
        "f.fiscal_year='$year'";
}



$whereSQL = '';

if (count($where) > 0) {

    $whereSQL =
        "WHERE " . implode(
            " AND ",
            $where
        );
}







// =====================================
// Statistik
// =====================================


$totalData = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) total

        FROM financial_managements
        "
    )

)['total'];





$totalBudget = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "
        SELECT SUM(total_budget) total

        FROM financial_managements

        WHERE status='Published'
        "
    )

)['total'] ?? 0;





$totalRealization = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "
        SELECT SUM(realization) total

        FROM financial_managements

        WHERE status='Published'
        "
    )

)['total'] ?? 0;





$totalYear = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "
        SELECT COUNT(DISTINCT fiscal_year) total

        FROM financial_managements
        "
    )

)['total'];







// =====================================
// Pagination
// =====================================

$limit = 10;


$pageNumber = isset($_GET['page'])
    ? (int)$_GET['page']
    : 1;


$offset =
    ($pageNumber - 1) * $limit;





$countQuery = mysqli_query(
    $conn,
    "
    SELECT COUNT(*) total

    FROM financial_managements f

    $whereSQL
    "
);



$totalRows =
    mysqli_fetch_assoc($countQuery)['total'];



$totalPages =
    ceil(
        $totalRows / $limit
    );







// =====================================
// Data
// =====================================


$query = mysqli_query(

    $conn,

    "

SELECT

f.*,

u.username AS author


FROM financial_managements f


LEFT JOIN users u

ON u.id=f.created_by



$whereSQL


ORDER BY f.created_at DESC


LIMIT $offset,$limit


"

);





$title = "Pengelolaan Keuangan";

$page = "pengelolaan-keuangan";


include APP_PATH . 'includes/admin/layout-top.php';

?>



<div class="p-8">



    <!-- HEADER -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h2 class="text-3xl font-bold text-slate-900">

                Pengelolaan Keuangan

            </h2>


            <p class="mt-2 text-slate-500">

                Kelola laporan dan informasi keuangan desa.

            </p>

        </div>



        <a

            href="create.php"

            class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

            Tambah Data

        </a>



    </div>







    <!-- STATISTIC -->

    <div class="grid gap-6 md:grid-cols-4 mb-8">



        <div class="rounded-2xl bg-white border p-6">

            <p class="text-sm text-slate-500">

                Total Laporan

            </p>

            <h3 class="mt-2 text-3xl font-bold">

                <?= number_format($totalData); ?>

            </h3>

        </div>




        <div class="rounded-2xl bg-white border p-6">

            <p class="text-sm text-slate-500">

                Total Anggaran

            </p>

            <h3 class="mt-2 text-xl font-bold">

                Rp <?= number_format($totalBudget); ?>

            </h3>

        </div>




        <div class="rounded-2xl bg-white border p-6">

            <p class="text-sm text-slate-500">

                Total Realisasi

            </p>

            <h3 class="mt-2 text-xl font-bold">

                Rp <?= number_format($totalRealization); ?>

            </h3>

        </div>




        <div class="rounded-2xl bg-white border p-6">

            <p class="text-sm text-slate-500">

                Jumlah Tahun

            </p>

            <h3 class="mt-2 text-3xl font-bold">

                <?= $totalYear; ?>

            </h3>

        </div>



    </div>









    <!-- FILTER -->

    <div class="mb-6 rounded-2xl border bg-white p-6">


        <form method="GET"

            class="grid gap-4 md:grid-cols-4">



            <input

                type="text"

                name="keyword"

                value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>"

                placeholder="Cari laporan..."

                class="rounded-xl border px-4 py-3">





            <select

                name="category"

                class="rounded-xl border px-4 py-3">


                <option value="">

                    Semua Kategori

                </option>


                <?php

                $categories = [

                    'APBDes',

                    'Pendapatan Desa',

                    'Belanja Desa',

                    'Pembiayaan Desa',

                    'Realisasi Anggaran',

                    'Laporan Keuangan',

                    'Lainnya'

                ];


                foreach ($categories as $item):

                ?>


                    <option

                        value="<?= $item ?>"

                        <?= $category == $item ? 'selected' : '' ?>>

                        <?= $item ?>

                    </option>


                <?php endforeach; ?>


            </select>






            <input

                type="number"

                name="year"

                value="<?= htmlspecialchars($year) ?>"

                placeholder="Tahun"

                class="rounded-xl border px-4 py-3">






            <button

                class="rounded-xl bg-slate-900 text-white">

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

                        Judul

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

                        Status

                    </th>


                    <th class="px-6 py-4">

                        Aksi

                    </th>


                </tr>


            </thead>







            <tbody class="divide-y">



                <?php


                $no = $offset + 1;


                if (mysqli_num_rows($query) > 0):

                    while ($row = mysqli_fetch_assoc($query)):


                ?>

                        <tr>


                            <td class="px-6 py-5">

                                <?= $no++; ?>

                            </td>




                            <td class="px-6 py-5">


                                <h4 class="font-semibold">

                                    <?= htmlspecialchars($row['title']); ?>

                                </h4>


                                <p class="text-sm text-slate-500">

                                    <?= htmlspecialchars($row['funding_source']); ?>

                                </p>


                            </td>





                            <td class="px-6 py-5">

                                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm">

                                    <?= $row['category']; ?>

                                </span>

                            </td>





                            <td class="px-6 py-5">

                                <?= $row['fiscal_year']; ?>

                            </td>





                            <td class="px-6 py-5">

                                Rp <?= number_format($row['total_budget']); ?>

                            </td>





                            <td class="px-6 py-5">


                                <span class="rounded-full px-3 py-1 text-sm
<?= $row['status'] == 'Published'
                            ? 'bg-emerald-100 text-emerald-700'
                            : 'bg-yellow-100 text-yellow-700'; ?>">


                                    <?= $row['status']; ?>


                                </span>


                            </td>





                            <td class="px-6 py-5">


                                <div class="flex gap-2">



                                    <a

                                        href="detail.php?slug=<?= $row['slug']; ?>"

                                        class="h-10 w-10 flex items-center justify-center rounded-lg bg-sky-100 text-sky-600">


                                        <i class="bi bi-eye"></i>

                                    </a>





                                    <a

                                        href="edit.php?slug=<?= $row['slug']; ?>"

                                        class="h-10 w-10 flex items-center justify-center rounded-lg bg-amber-500 text-white">


                                        <i class="bi bi-pencil"></i>

                                    </a>






                                    <a

                                        onclick="return confirm('Hapus data ini?')"

                                        href="delete.php?slug=<?= $row['slug']; ?>"

                                        class="h-10 w-10 flex items-center justify-center rounded-lg bg-red-500 text-white">


                                        <i class="bi bi-trash"></i>

                                    </a>



                                </div>


                            </td>



                        </tr>


                    <?php endwhile;
                else: ?>


                    <tr>

                        <td colspan="7"

                            class="px-6 py-20 text-center">


                            Belum ada data keuangan.


                        </td>

                    </tr>


                <?php endif; ?>


            </tbody>


        </table>


    </div>









    <!-- PAGINATION -->

    <div class="mt-6 flex justify-center gap-2">


        <?php for ($i = 1; $i <= $totalPages; $i++): ?>


            <a

                href="?page=<?= $i ?>&keyword=<?= $keyword ?>&category=<?= $category ?>&year=<?= $year ?>"

                class="rounded-lg px-4 py-2
<?= $pageNumber == $i
                ? 'bg-teal-600 text-white'
                : 'bg-slate-100'; ?>">


                <?= $i ?>


            </a>


        <?php endfor; ?>


    </div>




</div>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>