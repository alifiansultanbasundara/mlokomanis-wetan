<?php

require_once '../../../config/app.php';


// =====================================
// Filter
// =====================================

$search = isset($_GET['search'])
    ? mysqli_real_escape_string($conn, $_GET['search'])
    : '';

$category = isset($_GET['category'])
    ? mysqli_real_escape_string($conn, $_GET['category'])
    : '';

$year = isset($_GET['year'])
    ? (int) $_GET['year']
    : '';




// =====================================
// Pagination
// =====================================

$limit = 10;

$page = isset($_GET['page'])
    ? (int) $_GET['page']
    : 1;


$offset = ($page - 1) * $limit;





// =====================================
// WHERE
// =====================================

$where = "WHERE 1=1";


if (!empty($search)) {

    $where .= "

    AND (

        s.title LIKE '%$search%'

        OR s.description LIKE '%$search%'

    )

    ";
}



if (!empty($category)) {

    $where .= "

    AND s.category='$category'

    ";
}



if (!empty($year)) {

    $where .= "

    AND s.year='$year'

    ";
}






// =====================================
// Total Data
// =====================================

$countQuery = mysqli_query(
    $conn,

    "
    SELECT COUNT(*) AS total

    FROM social_assistances s

    $where

    "
);


$totalData =
    mysqli_fetch_assoc($countQuery)['total'];



$totalPage =
    ceil($totalData / $limit);







// =====================================
// Data
// =====================================


$query = mysqli_query(

    $conn,

    "
    SELECT

        s.*,

        u.username AS author


    FROM social_assistances s


    LEFT JOIN users u

    ON u.id = s.created_by


    $where


    ORDER BY s.created_at DESC


    LIMIT $limit OFFSET $offset


    "

);








// =====================================
// Statistik
// =====================================


$totalProgram = mysqli_fetch_assoc(

    mysqli_query(
        $conn,

        "
        SELECT COUNT(*) total

        FROM social_assistances

        "

    )

)['total'];





$totalBudget = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "
        SELECT SUM(total_budget) total

        FROM social_assistances

        WHERE status='Published'

        "

    )

)['total'] ?? 0;







$totalRecipient = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "
        SELECT COUNT(*) total

        FROM social_assistance_recipients

        "

    )

)['total'];






$title = "Bantuan Sosial";

$page = "bantuan-sosial";


include APP_PATH . 'includes/admin/layout-top.php';

?>



<div class="p-8">





    <!-- HEADER -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h2 class="text-3xl font-bold text-slate-900">

                Bantuan Sosial

            </h2>


            <p class="mt-2 text-slate-500">

                Kelola program bantuan sosial desa.

            </p>


        </div>




        <a

            href="create.php"

            class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

            Tambah Bantuan

        </a>



    </div>







    <!-- STATISTIK -->


    <div class="mb-8 grid gap-6 md:grid-cols-3">


        <div class="rounded-2xl bg-white border p-6">

            <p class="text-sm text-slate-500">
                Total Program
            </p>


            <h3 class="mt-2 text-3xl font-bold">

                <?= number_format($totalProgram); ?>

            </h3>

        </div>




        <div class="rounded-2xl bg-white border p-6">

            <p class="text-sm text-slate-500">
                Total Anggaran
            </p>


            <h3 class="mt-2 text-3xl font-bold">

                Rp <?= number_format($totalBudget, 0, ',', '.'); ?>

            </h3>

        </div>





        <div class="rounded-2xl bg-white border p-6">

            <p class="text-sm text-slate-500">
                Total Penerima
            </p>


            <h3 class="mt-2 text-3xl font-bold">

                <?= number_format($totalRecipient); ?>

            </h3>

        </div>



    </div>








    <!-- FILTER -->


    <form

        method="GET"

        class="mb-6 grid gap-4 rounded-2xl border bg-white p-5 md:grid-cols-4">


        <input

            type="text"

            name="search"

            value="<?= htmlspecialchars($search); ?>"

            placeholder="Cari program bantuan..."

            class="rounded-xl border px-4 py-3">





        <select

            name="category"

            class="rounded-xl border px-4 py-3">


            <option value="">
                Semua Kategori
            </option>


            <?php

            $categories = mysqli_query(
                $conn,
                "SELECT DISTINCT category FROM social_assistances"
            );


            while ($cat = mysqli_fetch_assoc($categories)):

            ?>


                <option

                    value="<?= $cat['category']; ?>"

                    <?= $category == $cat['category'] ? 'selected' : ''; ?>>

                    <?= $cat['category']; ?>

                </option>


            <?php endwhile; ?>


        </select>







        <input

            type="number"

            name="year"

            value="<?= $year; ?>"

            placeholder="Tahun"

            class="rounded-xl border px-4 py-3">





        <button

            class="rounded-xl bg-slate-900 px-5 py-3 text-white">

            Filter

        </button>



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
                        Program
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

                        <tr class="hover:bg-slate-50">


                            <td class="px-6 py-5">

                                <?= $no++; ?>

                            </td>





                            <td class="px-6 py-5">


                                <h4 class="font-semibold">

                                    <?= htmlspecialchars($row['title']); ?>

                                </h4>


                                <p class="text-sm text-slate-500">

                                    <?= htmlspecialchars($row['author'] ?? '-'); ?>

                                </p>


                            </td>





                            <td class="px-6 py-5">


                                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm">

                                    <?= htmlspecialchars($row['category']); ?>

                                </span>


                            </td>






                            <td class="px-6 py-5">

                                <?= $row['year']; ?>

                            </td>






                            <td class="px-6 py-5">

                                Rp <?= number_format($row['total_budget'], 0, ',', '.'); ?>

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

                                        class="h-10 w-10 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center">

                                        <i class="bi bi-eye"></i>

                                    </a>




                                    <a

                                        href="penerima.php?id=<?= $row['id']; ?>"

                                        class="h-10 w-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">

                                        <i class="bi bi-people"></i>

                                    </a>




                                    <a

                                        href="edit.php?slug=<?= $row['slug']; ?>"

                                        class="h-10 w-10 rounded-lg bg-amber-500 text-white flex items-center justify-center">

                                        <i class="bi bi-pencil"></i>

                                    </a>





                                    <a

                                        onclick="return confirm('Hapus data bantuan ini?')"

                                        href="delete.php?slug=<?= $row['slug']; ?>"

                                        class="h-10 w-10 rounded-lg bg-red-500 text-white flex items-center justify-center">

                                        <i class="bi bi-trash"></i>

                                    </a>



                                </div>


                            </td>


                        </tr>


                    <?php endwhile; ?>


                <?php else: ?>


                    <tr>

                        <td colspan="7" class="px-6 py-20 text-center">


                            <i class="bi bi-gift text-5xl text-slate-300"></i>


                            <h3 class="mt-4 font-semibold">

                                Belum ada program bantuan

                            </h3>


                            <p class="text-slate-500">

                                Silakan tambahkan program bantuan sosial.

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

                href="?page=<?= $i; ?>&search=<?= $search; ?>&category=<?= $category; ?>&year=<?= $year; ?>"

                class="rounded-lg px-4 py-2
<?= $page == $i
                ? 'bg-teal-600 text-white'
                : 'bg-white border'; ?>">


                <?= $i; ?>


            </a>


        <?php endfor; ?>


    </div>





</div>



<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>