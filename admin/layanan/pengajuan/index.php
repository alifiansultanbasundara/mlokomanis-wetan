<?php

require_once '../../../config/app.php';


// ======================================================
// FILTER
// ======================================================


$keyword = isset($_GET['keyword'])
    ? mysqli_real_escape_string($conn, $_GET['keyword'])
    : '';



$status = isset($_GET['status'])
    ? mysqli_real_escape_string($conn, $_GET['status'])
    : '';



$service_id = isset($_GET['service_id'])
    ? (int)$_GET['service_id']
    : 0;





// ======================================================
// WHERE
// ======================================================


$where = "WHERE 1=1";





if ($keyword) {


    $where .= "

    AND (

        letter_trackings.tracking_code LIKE '%$keyword%'

        OR letter_trackings.applicant_name LIKE '%$keyword%'

        OR letter_trackings.nik LIKE '%$keyword%'

    )

    ";
}





if ($status) {


    $where .= "

    AND letter_trackings.status='$status'

    ";
}





if ($service_id) {


    $where .= "

    AND letter_trackings.service_id='$service_id'

    ";
}







// ======================================================
// PAGINATION
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
// STATISTIK
// ======================================================


$statQuery = mysqli_query($conn, "
    
    SELECT


    COUNT(*) AS total,


    SUM(status='Menunggu Verifikasi') AS waiting,


    SUM(status='Diproses') AS process,


    SUM(status='Selesai') AS completed,


    SUM(status='Ditolak') AS rejected



    FROM letter_trackings


");



$stat = mysqli_fetch_assoc($statQuery);








// ======================================================
// TOTAL DATA
// ======================================================


$totalQuery = mysqli_query($conn, "
    
    SELECT COUNT(*) AS total


    FROM letter_trackings


    $where


");



$totalData = mysqli_fetch_assoc($totalQuery)['total'];



$totalPage = ceil($totalData / $limit);








// ======================================================
// DATA SERVICE
// ======================================================


$services = mysqli_query($conn, "
    
    SELECT id,name

    FROM service_letters

    ORDER BY name ASC

");








// ======================================================
// DATA PENGAJUAN
// ======================================================


$applications = mysqli_query($conn, "
    
    SELECT


    letter_trackings.*,


    service_letters.name AS service_name,


    service_letters.icon,


    service_letters.color



    FROM letter_trackings



    LEFT JOIN service_letters

    ON service_letters.id = letter_trackings.service_id



    $where



    ORDER BY letter_trackings.id DESC



    LIMIT $limit OFFSET $offset


");







// ======================================================

$title = "Pengajuan Layanan Surat";

$page = "pengajuan";


include APP_PATH . "includes/admin/layout-top.php";


?>




<main class="p-8 space-y-6">




    <!-- HEADER -->


    <div class="flex justify-between items-center">


        <div>


            <h1 class="text-3xl font-bold text-slate-800">

                Pengajuan Layanan Surat

            </h1>


            <p class="text-slate-500 mt-2">

                Kelola permohonan surat dari masyarakat

            </p>


        </div>





        <a
            href="index.php"
            class="px-5 py-3 rounded-xl border hover:bg-slate-50">


            <i class="bi bi-arrow-clockwise"></i>


            Refresh


        </a>



    </div>








    <!-- STATISTIK -->


    <div class="grid md:grid-cols-5 gap-5">






        <div class="bg-white border rounded-2xl p-5">


            <p class="text-sm text-slate-500">

                Total Pengajuan

            </p>


            <h3 class="text-3xl font-bold mt-2">

                <?= $stat['total'] ?? 0; ?>

            </h3>


        </div>







        <div class="bg-white border rounded-2xl p-5">


            <p class="text-sm text-slate-500">

                Menunggu Verifikasi

            </p>


            <h3 class="text-3xl font-bold text-yellow-600 mt-2">

                <?= $stat['waiting'] ?? 0; ?>

            </h3>


        </div>







        <div class="bg-white border rounded-2xl p-5">


            <p class="text-sm text-slate-500">

                Diproses

            </p>


            <h3 class="text-3xl font-bold text-blue-600 mt-2">

                <?= $stat['process'] ?? 0; ?>

            </h3>


        </div>







        <div class="bg-white border rounded-2xl p-5">


            <p class="text-sm text-slate-500">

                Selesai

            </p>


            <h3 class="text-3xl font-bold text-emerald-600 mt-2">

                <?= $stat['completed'] ?? 0; ?>

            </h3>


        </div>







        <div class="bg-white border rounded-2xl p-5">


            <p class="text-sm text-slate-500">

                Ditolak

            </p>


            <h3 class="text-3xl font-bold text-red-600 mt-2">

                <?= $stat['rejected'] ?? 0; ?>

            </h3>


        </div>





    </div>









    <!-- FILTER -->


    <div class="bg-white border rounded-2xl p-6">



        <form
            method="GET"
            class="grid lg:grid-cols-4 gap-5">






            <!-- SEARCH -->


            <div>


                <label class="text-sm font-medium text-slate-700">

                    Pencarian

                </label>


                <input
                    type="text"
                    name="keyword"
                    value="<?= htmlspecialchars($keyword); ?>"
                    placeholder="Kode / Nama / NIK"
                    class="w-full mt-2 px-4 py-3 rounded-xl border">


            </div>








            <!-- STATUS -->


            <div>


                <label class="text-sm font-medium text-slate-700">

                    Status

                </label>


                <select
                    name="status"
                    class="w-full mt-2 px-4 py-3 rounded-xl border">



                    <option value="">

                        Semua Status

                    </option>



                    <option value="Menunggu Verifikasi"
                        <?= $status == "Menunggu Verifikasi" ? 'selected' : ''; ?>>

                        Menunggu Verifikasi

                    </option>





                    <option value="Diproses"
                        <?= $status == "Diproses" ? 'selected' : ''; ?>>

                        Diproses

                    </option>





                    <option value="Selesai"
                        <?= $status == "Selesai" ? 'selected' : ''; ?>>

                        Selesai

                    </option>





                    <option value="Ditolak"
                        <?= $status == "Ditolak" ? 'selected' : ''; ?>>

                        Ditolak

                    </option>



                </select>


            </div>









            <!-- SERVICE -->


            <div>


                <label class="text-sm font-medium text-slate-700">

                    Jenis Surat

                </label>



                <select
                    name="service_id"
                    class="w-full mt-2 px-4 py-3 rounded-xl border">



                    <option value="">

                        Semua Layanan

                    </option>




                    <?php while ($srv = mysqli_fetch_assoc($services)): ?>


                        <option
                            value="<?= $srv['id']; ?>"
                            <?= $service_id == $srv['id'] ? 'selected' : ''; ?>>


                            <?= htmlspecialchars($srv['name']); ?>


                        </option>



                    <?php endwhile; ?>



                </select>



            </div>








            <!-- BUTTON -->


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

                <?= mysqli_num_rows($applications); ?>

            </span>

            dari

            <span class="font-semibold text-slate-700">

                <?= $totalData; ?>

            </span>

            pengajuan



        </p>


    </div>








    <!-- TABLE -->


    <div class="bg-white border rounded-2xl overflow-hidden shadow-sm">


        <div class="overflow-x-auto">



            <table class="min-w-full">



                <thead class="bg-slate-50">


                    <tr class="text-left text-sm font-semibold text-slate-600">


                        <th class="px-6 py-4">

                            No

                        </th>


                        <th class="px-6 py-4">

                            Tracking

                        </th>


                        <th class="px-6 py-4">

                            Pemohon

                        </th>


                        <th class="px-6 py-4">

                            Layanan

                        </th>


                        <th class="px-6 py-4">

                            Tanggal

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



                    <?php if (mysqli_num_rows($applications) > 0): ?>



                        <?php

                        $no = $offset + 1;


                        while ($row = mysqli_fetch_assoc($applications)):

                        ?>



                            <tr class="border-t hover:bg-slate-50">







                                <td class="px-6 py-4">


                                    <?= $no++; ?>


                                </td>








                                <!-- TRACKING -->


                                <td class="px-6 py-4">



                                    <div>


                                        <span class="font-semibold text-teal-700">


                                            <?= htmlspecialchars($row['tracking_code']); ?>


                                        </span>



                                        <p class="text-xs text-slate-500">


                                            ID #<?= $row['id']; ?>


                                        </p>


                                    </div>



                                </td>









                                <!-- PEMOHON -->


                                <td class="px-6 py-4">



                                    <div>


                                        <p class="font-medium text-slate-800">


                                            <?= htmlspecialchars($row['applicant_name']); ?>


                                        </p>



                                        <?php if ($row['nik']): ?>


                                            <p class="text-xs text-slate-500">

                                                NIK:

                                                <?= htmlspecialchars($row['nik']); ?>

                                            </p>


                                        <?php endif; ?>





                                        <?php if ($row['phone']): ?>


                                            <p class="text-xs text-slate-500">

                                                <i class="bi bi-telephone"></i>

                                                <?= htmlspecialchars($row['phone']); ?>

                                            </p>


                                        <?php endif; ?>



                                    </div>



                                </td>









                                <!-- LAYANAN -->


                                <td class="px-6 py-4">


                                    <div class="flex items-center gap-3">



                                        <div
                                            class="w-10 h-10 rounded-xl bg-<?= $row['color']; ?>-100 flex items-center justify-center">


                                            <i class="<?= htmlspecialchars($row['icon']); ?> text-<?= $row['color']; ?>-600"></i>


                                        </div>




                                        <div>


                                            <p class="font-medium">

                                                <?= htmlspecialchars($row['service_name']); ?>

                                            </p>


                                        </div>



                                    </div>



                                </td>









                                <!-- TANGGAL -->


                                <td class="px-6 py-4 text-sm">



                                    <?= date(
                                        'd M Y',
                                        strtotime($row['submitted_at'])
                                    ); ?>


                                    <br>


                                    <span class="text-xs text-slate-500">


                                        <?= date(
                                            'H:i',
                                            strtotime($row['submitted_at'])
                                        ); ?>


                                    </span>



                                </td>









                                <!-- STATUS -->


                                <td class="px-6 py-4">



                                    <?php

                                    $statusClass = [

                                        "Menunggu Verifikasi" =>
                                        "bg-yellow-100 text-yellow-700",


                                        "Diproses" =>
                                        "bg-blue-100 text-blue-700",


                                        "Selesai" =>
                                        "bg-emerald-100 text-emerald-700",


                                        "Ditolak" =>
                                        "bg-red-100 text-red-700"

                                    ];



                                    $class = $statusClass[$row['status']]
                                        ?? "bg-slate-100 text-slate-700";


                                    ?>



                                    <span class="px-3 py-1 rounded-full text-xs <?= $class; ?>">


                                        <?= htmlspecialchars($row['status']); ?>


                                    </span>



                                </td>









                                <!-- ACTION -->


                                <td class="px-6 py-4">


                                    <div class="flex justify-center gap-2">





                                        <a
                                            href="../tracking-detail.php?id=<?= $row['id']; ?>"
                                            class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200">


                                            <i class="bi bi-eye"></i>


                                        </a>








                                        <a
                                            href="../tracking-edit.php?id=<?= $row['id']; ?>"
                                            class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 hover:bg-blue-100">


                                            <i class="bi bi-pencil"></i>


                                        </a>








                                        <a
                                            href="../tracking-delete.php?id=<?= $row['id']; ?>"
                                            onclick="return confirm('Hapus pengajuan ini?')"
                                            class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center text-red-600 hover:bg-red-100">


                                            <i class="bi bi-trash"></i>


                                        </a>



                                    </div>


                                </td>







                            </tr>





                        <?php endwhile; ?>






                    <?php else: ?>



                        <tr>


                            <td colspan="7"
                                class="px-6 py-16 text-center">



                                <i class="bi bi-inbox text-5xl text-slate-300 block mb-3"></i>



                                <p class="text-slate-500">

                                    Belum ada pengajuan layanan.

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


            <div class="flex items-center gap-2">





                <!-- PREVIOUS -->


                <?php if ($page > 1): ?>


                    <a
                        href="?page=<?= $page - 1; ?>&keyword=<?= urlencode($keyword); ?>&status=<?= urlencode($status); ?>&service_id=<?= $service_id; ?>"
                        class="px-4 py-2 rounded-xl border hover:bg-slate-50">


                        <i class="bi bi-chevron-left"></i>


                    </a>



                <?php endif; ?>










                <!-- NUMBER -->


                <?php for ($i = 1; $i <= $totalPage; $i++): ?>


                    <a
                        href="?page=<?= $i; ?>&keyword=<?= urlencode($keyword); ?>&status=<?= urlencode($status); ?>&service_id=<?= $service_id; ?>"
                        class="
px-4 py-2 rounded-xl border
<?= $page == $i
                        ? 'bg-teal-600 text-white border-teal-600'
                        : 'hover:bg-slate-50';
?>
">


                        <?= $i; ?>


                    </a>



                <?php endfor; ?>










                <!-- NEXT -->


                <?php if ($page < $totalPage): ?>


                    <a
                        href="?page=<?= $page + 1; ?>&keyword=<?= urlencode($keyword); ?>&status=<?= urlencode($status); ?>&service_id=<?= $service_id; ?>"
                        class="px-4 py-2 rounded-xl border hover:bg-slate-50">


                        <i class="bi bi-chevron-right"></i>


                    </a>



                <?php endif; ?>






            </div>


        </div>



    <?php endif; ?>







</main>





<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>