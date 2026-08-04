<?php

require_once '../../config/app.php';


// ======================================================
// Filter
// ======================================================

$keyword = isset($_GET['keyword'])
    ? mysqli_real_escape_string($conn, $_GET['keyword'])
    : '';



$status = isset($_GET['status'])
    ? mysqli_real_escape_string($conn, $_GET['status'])
    : '';




// ======================================================
// Pagination
// ======================================================

$limit = 10;


$page = isset($_GET['page'])
    ? (int)$_GET['page']
    : 1;


if ($page < 1) {

    $page = 1;
}


$offset = ($page - 1) * $limit;





// ======================================================
// WHERE
// ======================================================


$where = "WHERE 1=1";



if ($keyword) {


    $where .= "
    
    AND name LIKE '%$keyword%'

    ";
}



if ($status) {


    $where .= "
    
    AND status='$status'

    ";
}







// ======================================================
// Statistik
// ======================================================


$statQuery = mysqli_query($conn, "
    
    SELECT


    COUNT(*) AS total,


    SUM(status='Published') AS published,


    SUM(status='Draft') AS draft,


    SUM(has_google_form='Yes') AS google_form,


    SUM(has_tracking='Yes') AS tracking



    FROM service_letters


");



$stat = mysqli_fetch_assoc($statQuery);






// ======================================================
// Total Data
// ======================================================


$totalQuery = mysqli_query($conn, "
    
    SELECT COUNT(*) AS total

    FROM service_letters

    $where

");



$totalData = mysqli_fetch_assoc($totalQuery)['total'];



$totalPage = ceil($totalData / $limit);







// ======================================================
// Data Layanan
// ======================================================


$services = mysqli_query($conn, "
    
    SELECT *

    FROM service_letters

    $where

    ORDER BY sort_order ASC, id DESC

    LIMIT $limit OFFSET $offset


");






// ======================================================

$title = "Pelayanan Surat";

$page_name = "layanan";

include APP_PATH . "includes/admin/layout-top.php";

?>



<main class="p-8 space-y-6">




    <!-- HEADER -->


    <div class="flex justify-between items-center">


        <div>


            <h1 class="text-3xl font-bold text-slate-800">

                Pelayanan Surat

            </h1>


            <p class="text-slate-500 mt-2">

                Kelola layanan administrasi surat desa

            </p>


        </div>





        <a
            href="create.php"
            class="px-5 py-3 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">


            <i class="bi bi-plus-circle"></i>


            Tambah Layanan


        </a>


    </div>






    <!-- STATISTIC -->


    <div class="grid md:grid-cols-5 gap-5">



        <div class="bg-white border rounded-2xl p-5">


            <p class="text-sm text-slate-500">

                Total Layanan

            </p>


            <h3 class="text-3xl font-bold mt-2">

                <?= $stat['total'] ?? 0; ?>

            </h3>


        </div>





        <div class="bg-white border rounded-2xl p-5">


            <p class="text-sm text-slate-500">

                Published

            </p>


            <h3 class="text-3xl font-bold text-emerald-600 mt-2">

                <?= $stat['published'] ?? 0; ?>

            </h3>


        </div>






        <div class="bg-white border rounded-2xl p-5">


            <p class="text-sm text-slate-500">

                Draft

            </p>


            <h3 class="text-3xl font-bold text-yellow-600 mt-2">

                <?= $stat['draft'] ?? 0; ?>

            </h3>


        </div>






        <div class="bg-white border rounded-2xl p-5">


            <p class="text-sm text-slate-500">

                Google Form

            </p>


            <h3 class="text-3xl font-bold text-blue-600 mt-2">

                <?= $stat['google_form'] ?? 0; ?>

            </h3>


        </div>






        <div class="bg-white border rounded-2xl p-5">


            <p class="text-sm text-slate-500">

                Tracking

            </p>


            <h3 class="text-3xl font-bold text-purple-600 mt-2">

                <?= $stat['tracking'] ?? 0; ?>

            </h3>


        </div>



    </div>





    <!-- FILTER -->


    <div class="bg-white border rounded-2xl p-5">


        <form method="GET"
            class="grid md:grid-cols-3 gap-5">



            <div>


                <label class="text-sm text-slate-600">

                    Cari Layanan

                </label>


                <input
                    type="text"
                    name="keyword"
                    value="<?= htmlspecialchars($keyword); ?>"
                    placeholder="Contoh: Surat Keterangan Domisili"
                    class="w-full mt-2 px-4 py-3 rounded-xl border">


            </div>





            <div>


                <label class="text-sm text-slate-600">

                    Status

                </label>


                <select
                    name="status"
                    class="w-full mt-2 px-4 py-3 rounded-xl border">


                    <option value="">

                        Semua Status

                    </option>


                    <option value="Published"
                        <?= $status == "Published" ? 'selected' : ''; ?>>

                        Published

                    </option>


                    <option value="Draft"
                        <?= $status == "Draft" ? 'selected' : ''; ?>>

                        Draft

                    </option>


                </select>


            </div>





            <div class="flex items-end gap-3">


                <button
                    class="px-5 py-3 rounded-xl bg-teal-600 text-white">

                    <i class="bi bi-search"></i>

                    Filter

                </button>



                <a
                    href="index.php"
                    class="px-5 py-3 rounded-xl border">

                    Reset

                </a>


            </div>



        </form>


    </div>

    <!-- INFO DATA -->


    <div class="flex justify-between items-center">


        <p class="text-sm text-slate-500">

            Menampilkan

            <span class="font-semibold text-slate-700">

                <?= mysqli_num_rows($services); ?>

            </span>

            dari

            <span class="font-semibold text-slate-700">

                <?= $totalData; ?>

            </span>

            layanan


        </p>


    </div>








    <!-- TABLE -->


    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">


        <div class="overflow-x-auto">


            <table class="min-w-full">


                <thead class="bg-slate-50">


                    <tr class="text-left text-sm font-semibold text-slate-600">


                        <th class="px-6 py-4">

                            No

                        </th>


                        <th class="px-6 py-4">

                            Layanan

                        </th>


                        <th class="px-6 py-4">

                            Informasi

                        </th>


                        <th class="px-6 py-4">

                            Fitur

                        </th>


                        <th class="px-6 py-4">

                            Status

                        </th>


                        <th class="px-6 py-4 text-center">

                            Aksi

                        </th>


                    </tr>


                </thead>






                <tbody>



                    <?php if (mysqli_num_rows($services) > 0): ?>


                        <?php


                        $no = $offset + 1;


                        while ($row = mysqli_fetch_assoc($services)):

                        ?>



                            <tr class="border-t hover:bg-slate-50 transition">





                                <td class="px-6 py-4">


                                    <?= $no++; ?>


                                </td>







                                <!-- Layanan -->


                                <td class="px-6 py-4">


                                    <div class="flex items-center gap-4">



                                        <div
                                            class="w-12 h-12 rounded-xl flex items-center justify-center bg-<?= $row['color']; ?>-100">


                                            <i class="<?= htmlspecialchars($row['icon']); ?> text-xl text-<?= $row['color']; ?>-600"></i>


                                        </div>





                                        <div>


                                            <h3 class="font-semibold text-slate-800">

                                                <?= htmlspecialchars($row['name']); ?>

                                            </h3>


                                            <p class="text-xs text-slate-500">

                                                <?= htmlspecialchars($row['slug']); ?>

                                            </p>


                                        </div>



                                    </div>


                                </td>







                                <!-- Informasi -->


                                <td class="px-6 py-4">


                                    <div class="space-y-1 text-sm">



                                        <p>

                                            <i class="bi bi-clock text-slate-400"></i>

                                            <?= htmlspecialchars($row['processing_time'] ?? '-'); ?>


                                        </p>



                                        <p>

                                            <i class="bi bi-cash text-slate-400"></i>

                                            <?= htmlspecialchars($row['fee'] ?? 'Gratis'); ?>


                                        </p>



                                    </div>


                                </td>







                                <!-- Fitur -->


                                <td class="px-6 py-4">


                                    <div class="flex flex-wrap gap-2">



                                        <?php if ($row['has_google_form'] == "Yes"): ?>


                                            <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700">

                                                Google Form

                                            </span>


                                        <?php endif; ?>






                                        <?php if ($row['has_template'] == "Yes"): ?>


                                            <span class="px-3 py-1 rounded-full text-xs bg-purple-100 text-purple-700">

                                                Template

                                            </span>


                                        <?php endif; ?>






                                        <?php if ($row['has_tracking'] == "Yes"): ?>


                                            <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">

                                                Tracking

                                            </span>


                                        <?php endif; ?>



                                    </div>


                                </td>







                                <!-- Status -->


                                <td class="px-6 py-4">


                                    <?php if ($row['status'] == "Published"): ?>


                                        <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">


                                            Published


                                        </span>


                                    <?php else: ?>


                                        <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">


                                            Draft


                                        </span>


                                    <?php endif; ?>


                                </td>








                                <!-- ACTION -->

                                <!-- ACTION -->


                                <td class="px-6 py-4">


                                    <div class="flex justify-center gap-2">





                                        <!-- DETAIL -->

                                        <a
                                            href="detail.php?id=<?= $row['id']; ?>"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">


                                            <i class="bi bi-eye"></i>


                                        </a>







                                        <!-- TRACKING -->


                                        <a
                                            href="tracking.php?service_id=<?= $row['id']; ?>"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100"
                                            title="Tracking Pengajuan">


                                            <i class="bi bi-search"></i>


                                        </a>








                                        <!-- EDIT -->

                                        <a
                                            href="edit.php?id=<?= $row['id']; ?>"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">


                                            <i class="bi bi-pencil"></i>


                                        </a>








                                        <!-- DELETE -->

                                        <a
                                            href="delete.php?id=<?= $row['id']; ?>"
                                            onclick="return confirm('Hapus layanan ini?')"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100">


                                            <i class="bi bi-trash"></i>


                                        </a>





                                    </div>


                                </td>

                            </tr>




                        <?php endwhile; ?>



                    <?php else: ?>



                        <tr>


                            <td colspan="6"
                                class="px-6 py-16 text-center">



                                <i class="bi bi-envelope-paper text-5xl text-slate-300 block mb-3"></i>



                                <p class="text-slate-500">

                                    Belum ada layanan surat.

                                </p>



                            </td>


                        </tr>



                    <?php endif; ?>



                </tbody>



            </table>



        </div>


    </div>









    <!-- PAGINATION -->


    <?php if ($totalPage > 1): ?>


        <div class="flex justify-center">


            <div class="flex gap-2">





                <?php if ($page > 1): ?>


                    <a
                        href="?page=<?= $page - 1; ?>&keyword=<?= $keyword; ?>&status=<?= $status; ?>"
                        class="px-4 py-2 rounded-lg border hover:bg-slate-50">


                        <i class="bi bi-chevron-left"></i>


                    </a>


                <?php endif; ?>








                <?php for ($i = 1; $i <= $totalPage; $i++): ?>


                    <a
                        href="?page=<?= $i; ?>&keyword=<?= $keyword; ?>&status=<?= $status; ?>"
                        class="
px-4 py-2 rounded-lg border
<?= $i == $page
                        ? 'bg-teal-600 text-white border-teal-600'
                        : 'hover:bg-slate-50';
?>
">


                        <?= $i; ?>


                    </a>


                <?php endfor; ?>








                <?php if ($page < $totalPage): ?>


                    <a
                        href="?page=<?= $page + 1; ?>&keyword=<?= $keyword; ?>&status=<?= $status; ?>"
                        class="px-4 py-2 rounded-lg border hover:bg-slate-50">


                        <i class="bi bi-chevron-right"></i>


                    </a>


                <?php endif; ?>




            </div>


        </div>


    <?php endif; ?>






</main>



<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>