<?php

require_once '../../../config/app.php';


// =====================================
// Pagination
// =====================================

$limit = 10;

$page = isset($_GET['page'])
    ? (int)$_GET['page']
    : 1;


if ($page < 1) {
    $page = 1;
}


$offset = ($page - 1) * $limit;






// =====================================
// Filter
// =====================================


$search = isset($_GET['search'])
    ? mysqli_real_escape_string(
        $conn,
        $_GET['search']
    )
    : '';



$category = isset($_GET['category'])
    ? mysqli_real_escape_string(
        $conn,
        $_GET['category']
    )
    : '';



$condition = isset($_GET['condition'])
    ? mysqli_real_escape_string(
        $conn,
        $_GET['condition']
    )
    : '';



$status = isset($_GET['status'])
    ? mysqli_real_escape_string(
        $conn,
        $_GET['status']
    )
    : '';





$where = [];



if ($search) {

    $where[] = "
    (
        a.title LIKE '%$search%'
        OR a.asset_code LIKE '%$search%'
        OR a.location LIKE '%$search%'
    )
    ";
}



if ($category) {

    $where[] =
        "a.category='$category'";
}



if ($condition) {

    $where[] =
        "a.condition_status='$condition'";
}



if ($status) {

    $where[] =
        "a.status='$status'";
}



$whereSql = '';



if (count($where) > 0) {

    $whereSql =
        "WHERE " . implode(
            " AND ",
            $where
        );
}









// =====================================
// Total Data
// =====================================


$totalQuery = mysqli_query(
    $conn,

    "
    SELECT COUNT(*) total

    FROM village_assets a

    $whereSql

    "
);



$totalData =
    mysqli_fetch_assoc($totalQuery)['total'];



$totalPage =
    ceil($totalData / $limit);









// =====================================
// Data
// =====================================


$query = mysqli_query(
    $conn,

    "
    SELECT

        a.*,

        u.username AS author


    FROM village_assets a


    LEFT JOIN users u

    ON u.id = a.created_by


    $whereSql


    ORDER BY a.created_at DESC


    LIMIT $offset,$limit

    "
);







// =====================================
// Statistik
// =====================================


$totalAsset = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) total
        FROM village_assets
        "
    )

)['total'];



$totalPublished = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) total
        FROM village_assets
        WHERE status='Published'
        "
    )

)['total'];



$totalValue = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "
        SELECT SUM(acquisition_value) total
        FROM village_assets
        "
    )

)['total'] ?? 0;




$title = "Aset Desa";

$page = "aset-desa";


include APP_PATH .
    'includes/admin/layout-top.php';

?>





<div class="p-8">



    <!-- HEADER -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h2 class="text-3xl font-bold text-slate-900">

                Aset Desa

            </h2>


            <p class="mt-2 text-slate-500">

                Kelola seluruh data aset milik desa.

            </p>


        </div>



        <a

            href="create.php"

            class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

            + Tambah Aset

        </a>


    </div>









    <!-- STATISTIK -->


    <div class="mb-8 grid gap-6 md:grid-cols-3">


        <div class="rounded-2xl border bg-white p-6">

            <p class="text-sm text-slate-500">

                Total Aset

            </p>

            <h3 class="mt-2 text-3xl font-bold">

                <?= number_format($totalAsset); ?>

            </h3>

        </div>





        <div class="rounded-2xl border bg-white p-6">


            <p class="text-sm text-slate-500">

                Aset Published

            </p>


            <h3 class="mt-2 text-3xl font-bold text-emerald-600">

                <?= number_format($totalPublished); ?>

            </h3>


        </div>





        <div class="rounded-2xl border bg-white p-6">


            <p class="text-sm text-slate-500">

                Total Nilai Perolehan

            </p>


            <h3 class="mt-2 text-xl font-bold">

                Rp <?= number_format($totalValue, 0, ',', '.'); ?>

            </h3>


        </div>


    </div>









    <!-- FILTER -->


    <form method="GET"

        class="mb-6 rounded-2xl border bg-white p-6">



        <div class="grid gap-4 md:grid-cols-4">





            <input

                type="text"

                name="search"

                value="<?= htmlspecialchars($search); ?>"

                placeholder="Cari aset..."

                class="rounded-xl border px-4 py-3">







            <select

                name="category"

                class="rounded-xl border px-4 py-3">


                <option value="">Semua Kategori</option>


                <?php foreach (
                    [

                        'Tanah',
                        'Bangunan',
                        'Kendaraan',
                        'Peralatan',
                        'Fasilitas Umum',
                        'Infrastruktur',
                        'Lainnya'

                    ] as $item
                ): ?>


                    <option

                        value="<?= $item; ?>"

                        <?= $category == $item ? 'selected' : '' ?>>

                        <?= $item; ?>

                    </option>


                <?php endforeach; ?>


            </select>








            <select

                name="condition"

                class="rounded-xl border px-4 py-3">


                <option value="">

                    Semua Kondisi

                </option>



                <option value="Baik"
                    <?= $condition == 'Baik' ? 'selected' : '' ?>>

                    Baik

                </option>


                <option value="Rusak Ringan"
                    <?= $condition == 'Rusak Ringan' ? 'selected' : '' ?>>

                    Rusak Ringan

                </option>


                <option value="Rusak Berat"
                    <?= $condition == 'Rusak Berat' ? 'selected' : '' ?>>

                    Rusak Berat

                </option>


            </select>








            <select

                name="status"

                class="rounded-xl border px-4 py-3">


                <option value="">

                    Semua Status

                </option>


                <option value="Published"
                    <?= $status == 'Published' ? 'selected' : '' ?>>

                    Published

                </option>



                <option value="Draft"
                    <?= $status == 'Draft' ? 'selected' : '' ?>>

                    Draft

                </option>


            </select>



        </div>



        <button

            class="mt-4 rounded-xl bg-slate-800 px-5 py-3 text-white">

            Filter

        </button>


    </form>









    <!-- TABLE -->


    <div class="overflow-x-auto rounded-2xl border bg-white">


        <table class="min-w-full">


            <thead class="bg-slate-50">


                <tr class="text-left text-sm font-semibold text-slate-600">


                    <th class="px-6 py-4">No</th>

                    <th class="px-6 py-4">Nama Aset</th>

                    <th class="px-6 py-4">Kategori</th>

                    <th class="px-6 py-4">Kode</th>

                    <th class="px-6 py-4">Nilai</th>

                    <th class="px-6 py-4">Kondisi</th>

                    <th class="px-6 py-4">Status</th>

                    <th class="px-6 py-4">Aksi</th>


                </tr>


            </thead>





            <tbody class="divide-y">



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


                                <h4 class="font-semibold">

                                    <?= htmlspecialchars($row['title']); ?>

                                </h4>


                                <p class="text-sm text-slate-500">

                                    <?= htmlspecialchars($row['location']); ?>

                                </p>


                            </td>






                            <td class="px-6 py-5">

                                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm">

                                    <?= htmlspecialchars($row['category']); ?>

                                </span>


                            </td>






                            <td class="px-6 py-5">

                                <?= htmlspecialchars($row['asset_code']); ?>

                            </td>






                            <td class="px-6 py-5">

                                Rp <?= number_format(
                                        $row['acquisition_value'],
                                        0,
                                        ',',
                                        '.'
                                    ); ?>

                            </td>






                            <td class="px-6 py-5">


                                <?= htmlspecialchars($row['condition_status']); ?>


                            </td>






                            <td class="px-6 py-5">


                                <span class="rounded-full px-3 py-1 text-sm
<?= $row['status'] == 'Published'
                            ? 'bg-emerald-100 text-emerald-700'
                            : 'bg-yellow-100 text-yellow-700';
?>">


                                    <?= $row['status']; ?>


                                </span>


                            </td>







                            <td class="px-6 py-5">


                                <div class="flex gap-2">



                                    <a

                                        href="detail.php?slug=<?= urlencode($row['slug']); ?>"

                                        class="h-10 w-10 flex items-center justify-center rounded-lg bg-sky-100 text-sky-600">

                                        <i class="bi bi-eye"></i>

                                    </a>




                                    <a

                                        href="edit.php?slug=<?= urlencode($row['slug']); ?>"

                                        class="h-10 w-10 flex items-center justify-center rounded-lg bg-amber-500 text-white">

                                        <i class="bi bi-pencil"></i>

                                    </a>





                                    <a

                                        href="delete.php?slug=<?= urlencode($row['slug']); ?>"

                                        onclick="return confirm('Hapus aset ini?')"

                                        class="h-10 w-10 flex items-center justify-center rounded-lg bg-red-500 text-white">


                                        <i class="bi bi-trash"></i>


                                    </a>


                                </div>


                            </td>


                        </tr>



                    <?php endwhile; ?>



                <?php else: ?>


                    <tr>

                        <td colspan="8"

                            class="px-6 py-20 text-center">


                            <i class="bi bi-building text-5xl text-slate-300"></i>


                            <h3 class="mt-4 font-semibold">

                                Belum ada data aset

                            </h3>


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

                href="?page=<?= $i; ?>&search=<?= $search; ?>&category=<?= $category; ?>&condition=<?= $condition; ?>&status=<?= $status; ?>"

                class="rounded-lg px-4 py-2 
<?= $page == $i
                ? 'bg-teal-600 text-white'
                : 'bg-white border';
?>">


                <?= $i; ?>


            </a>


        <?php endfor; ?>


    </div>





</div>





<?php include APP_PATH .
    'includes/admin/layout-bottom.php'; ?>