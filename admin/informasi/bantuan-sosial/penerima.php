<?php

require_once '../../../config/app.php';


// =====================================
// Validasi ID Bantuan
// =====================================

if (!isset($_GET['id']) || empty($_GET['id'])) {

    header("Location:index.php");
    exit;
}


$id = (int) $_GET['id'];




// =====================================
// Ambil Data Program
// =====================================

$programQuery = mysqli_query(

    $conn,

    "
    SELECT *

    FROM social_assistances

    WHERE id='$id'

    LIMIT 1

    "

);



if (!$programQuery || mysqli_num_rows($programQuery) == 0) {

    $_SESSION['error'] =
        "Program bantuan tidak ditemukan.";

    header("Location:index.php");
    exit;
}


$program = mysqli_fetch_assoc($programQuery);








// =====================================
// Search
// =====================================

$search = isset($_GET['search'])

    ? mysqli_real_escape_string(
        $conn,
        $_GET['search']
    )

    : '';






// =====================================
// Pagination
// =====================================

$limit = 10;


$page = isset($_GET['page'])

    ? (int) $_GET['page']

    : 1;


$offset = ($page - 1) * $limit;







$where = "

WHERE assistance_id='$id'

";



if (!empty($search)) {


    $where .= "

    AND (

        name LIKE '%$search%'

        OR nik LIKE '%$search%'

        OR address LIKE '%$search%'

    )

    ";
}






// =====================================
// Total Data
// =====================================


$count = mysqli_query(

    $conn,

    "
    SELECT COUNT(*) total

    FROM social_assistance_recipients

    $where

    "

);



$totalData =
    mysqli_fetch_assoc($count)['total'];



$totalPage =
    ceil($totalData / $limit);







// =====================================
// Data Penerima
// =====================================


$query = mysqli_query(

    $conn,

    "
    SELECT *

    FROM social_assistance_recipients

    $where

    ORDER BY created_at DESC

    LIMIT $limit OFFSET $offset

    "

);








// =====================================
// Statistik
// =====================================


$totalRecipient = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "
        SELECT COUNT(*) total

        FROM social_assistance_recipients

        WHERE assistance_id='$id'

        "

    )

)['total'];







$title = "Penerima Bantuan Sosial";

$page = "bantuan-sosial";


include APP_PATH . 'includes/admin/layout-top.php';

?>



<div class="p-8">



    <!-- HEADER -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h2 class="text-3xl font-bold text-slate-900">

                Penerima Bantuan

            </h2>


            <p class="mt-2 text-slate-500">

                <?= htmlspecialchars($program['title']); ?>

            </p>


        </div>



        <div class="flex gap-3">


            <a

                href="detail.php?slug=<?= $program['slug']; ?>"

                class="rounded-xl border px-5 py-3 font-medium">

                Kembali

            </a>



            <a

                href="penerima-create.php?id=<?= $id; ?>"

                class="rounded-xl bg-teal-600 px-6 py-3 text-white font-medium">

                Tambah Penerima

            </a>


        </div>


    </div>








    <!-- STAT -->

    <div class="mb-8 grid md:grid-cols-3 gap-6">


        <div class="rounded-2xl border bg-white p-6">


            <p class="text-sm text-slate-500">

                Program

            </p>


            <h3 class="mt-2 font-bold text-xl">

                <?= htmlspecialchars($program['title']); ?>

            </h3>


        </div>




        <div class="rounded-2xl border bg-white p-6">


            <p class="text-sm text-slate-500">

                Total Penerima

            </p>


            <h3 class="mt-2 text-3xl font-bold">

                <?= number_format($totalRecipient); ?>

            </h3>


        </div>





        <div class="rounded-2xl border bg-white p-6">


            <p class="text-sm text-slate-500">

                Tahun

            </p>


            <h3 class="mt-2 text-3xl font-bold">

                <?= $program['year']; ?>

            </h3>


        </div>



    </div>









    <!-- SEARCH -->


    <form

        method="GET"

        class="mb-6 rounded-2xl border bg-white p-5">


        <input

            type="hidden"

            name="id"

            value="<?= $id; ?>">


        <input

            type="text"

            name="search"

            value="<?= htmlspecialchars($search); ?>"

            placeholder="Cari nama / NIK / alamat..."

            class="w-full rounded-xl border px-4 py-3">


    </form>









    <!-- TABLE -->


    <div class="overflow-x-auto rounded-2xl border bg-white">


        <table class="min-w-full">


            <thead class="bg-slate-50">


                <tr class="text-left text-sm font-semibold text-slate-600">


                    <th class="px-6 py-4">
                        No
                    </th>


                    <th class="px-6 py-4">
                        Nama
                    </th>


                    <th class="px-6 py-4">
                        NIK
                    </th>


                    <th class="px-6 py-4">
                        Alamat
                    </th>


                    <th class="px-6 py-4">
                        Keterangan
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

                        <tr class="hover:bg-slate-50">



                            <td class="px-6 py-5">

                                <?= $no++; ?>

                            </td>





                            <td class="px-6 py-5 font-semibold">

                                <?= htmlspecialchars($row['name']); ?>

                            </td>






                            <td class="px-6 py-5">

                                <?= htmlspecialchars($row['nik']); ?>

                            </td>






                            <td class="px-6 py-5">

                                <?= htmlspecialchars($row['address']); ?>

                            </td>







                            <td class="px-6 py-5">

                                <?= htmlspecialchars($row['description'] ?? '-'); ?>

                            </td>






                            <td class="px-6 py-5">


                                <div class="flex gap-2">


                                    <a

                                        href="penerima-edit.php?id=<?= $row['id']; ?>"

                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-500 text-white">


                                        <i class="bi bi-pencil"></i>


                                    </a>





                                    <a

                                        href="penerima-delete.php?id=<?= $row['id']; ?>&assistance_id=<?= $id; ?>"

                                        onclick="return confirm('Hapus penerima ini?')"

                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-500 text-white">


                                        <i class="bi bi-trash"></i>


                                    </a>



                                </div>


                            </td>



                        </tr>


                    <?php endwhile; ?>


                <?php else: ?>


                    <tr>

                        <td colspan="6" class="px-6 py-20 text-center">


                            <i class="bi bi-people text-5xl text-slate-300"></i>


                            <h3 class="mt-4 font-semibold">

                                Belum ada penerima

                            </h3>


                            <p class="text-slate-500">

                                Tambahkan penerima bantuan sosial.

                            </p>


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

                href="?id=<?= $id; ?>&page=<?= $i; ?>&search=<?= $search; ?>"

                class="rounded-lg border px-4 py-2

<?= $page == $i

                ? 'bg-teal-600 text-white'

                : ''; ?>">


                <?= $i; ?>


            </a>


        <?php endfor; ?>


    </div>





</div>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>