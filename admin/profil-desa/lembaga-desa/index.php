<?php

require_once "../../../config/app.php";

// ===============================
// Filter
// ===============================

$search = $_GET["search"] ?? "";

$category = $_GET["category"] ?? "";

$search = mysqli_real_escape_string($conn, $search);

$category = mysqli_real_escape_string($conn, $category);

// ===============================
// Pagination
// ===============================

$limit = 10;

$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

// ===============================
// Query Condition
// ===============================

$where = [];

if (!empty($search)) {
    $where[] = "

    (
        name LIKE '%$search%'
        OR chairman LIKE '%$search%'
        OR description LIKE '%$search%'
    )

    ";
}

if (!empty($category)) {
    $where[] = "
        category='$category'
    ";
}

$whereSQL = "";

if (count($where) > 0) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

// ===============================
// Total Data
// ===============================

$totalQuery = mysqli_query(
    $conn,

    "
    SELECT COUNT(*) AS total
    FROM village_institutions
    $whereSQL
    "
);

$totalData = mysqli_fetch_assoc($totalQuery)["total"];

$totalPage = ceil($totalData / $limit);

// ===============================
// Get Data
// ===============================

$query = mysqli_query(
    $conn,

    "
    SELECT *
    FROM village_institutions

    $whereSQL

    ORDER BY sort_order ASC, id DESC

    LIMIT $limit OFFSET $offset

    "
);

// ===============================
// Statistics
// ===============================

$totalInstitution = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM village_institutions")
)["total"];

$totalActive = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) total
        FROM village_institutions
        WHERE status='Active'
        "
    )
)["total"];

$totalMember =
    mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "
        SELECT SUM(total_members) total
        FROM village_institutions
        "
        )
    )["total"] ?? 0;

$totalCategory = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT COUNT(DISTINCT category) total
        FROM village_institutions
        "
    )
)["total"];

$title = "Lembaga Desa";

$page = "lembaga-desa";

include APP_PATH . "includes/admin/layout-top.php";
?>



<div class="p-8">



    <!-- HEADER -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h1 class="text-3xl font-bold text-slate-900">

                Lembaga Desa

            </h1>


            <p class="mt-2 text-slate-500">

                Kelola data organisasi dan lembaga masyarakat desa.

            </p>


        </div>




        <a
            href="create.php"

            class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

            <i class="bi bi-plus-lg"></i>

            Tambah Lembaga

        </a>



    </div>







    <!-- STATISTICS -->

    <div class="mb-8 grid gap-5 md:grid-cols-4">



        <?php
        $stats = [
            [
                "title" => "Total Lembaga",
                "value" => $totalInstitution,
                "icon" => "bi-building",
            ],

            [
                "title" => "Lembaga Aktif",
                "value" => $totalActive,
                "icon" => "bi-check-circle",
            ],

            [
                "title" => "Kategori",
                "value" => $totalCategory,
                "icon" => "bi-grid",
            ],

            [
                "title" => "Total Anggota",
                "value" => number_format($totalMember),
                "icon" => "bi-people",
            ],
        ];

        foreach ($stats as $item): ?>


            <div class="rounded-2xl border border-slate-200 bg-white p-6">


                <div class="mb-3 text-3xl text-teal-600">

                    <i class="bi <?= $item["icon"] ?>"></i>

                </div>


                <h3 class="text-3xl font-bold text-slate-900">

                    <?= $item["value"] ?>

                </h3>


                <p class="text-slate-500">

                    <?= $item["title"] ?>

                </p>


            </div>



        <?php endforeach;
        ?>


    </div>









    <!-- FILTER -->

    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5">


        <form
            method="GET"
            class="grid gap-4 md:grid-cols-3">


            <input

                type="text"

                name="search"

                value="<?= htmlspecialchars($search) ?>"

                placeholder="Cari lembaga..."

                class="rounded-xl border border-slate-300 px-4 py-3">





            <select

                name="category"

                class="rounded-xl border border-slate-300 px-4 py-3">


                <option value="">
                    Semua Kategori
                </option>


                <?php
                $categories = mysqli_query(
                    $conn,
                    "
    SELECT DISTINCT category
    FROM village_institutions
    ORDER BY category ASC
    "
                );

                while ($cat = mysqli_fetch_assoc($categories)): ?>


                    <option

                        value="<?= $cat["category"] ?>"

                        <?= $category == $cat["category"] ? "selected" : "" ?>>

                        <?= $cat["category"] ?>

                    </option>


                <?php endwhile;
                ?>


            </select>





            <button

                class="rounded-xl bg-slate-900 px-5 py-3 text-white">

                Filter

            </button>


        </form>


    </div>









    <!-- TABLE -->

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">


        <div class="overflow-x-auto">


            <table class="w-full text-left">


                <thead class="bg-slate-50">


                    <tr class="text-sm text-slate-600">


                        <th class="px-6 py-4">
                            No
                        </th>


                        <th class="px-6 py-4">
                            Lembaga
                        </th>


                        <th class="px-6 py-4">
                            Kategori
                        </th>


                        <th class="px-6 py-4">
                            Ketua
                        </th>


                        <th class="px-6 py-4">
                            Anggota
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
                    $no = $offset + 1;

                    if (mysqli_num_rows($query) > 0):
                        while ($row = mysqli_fetch_assoc($query)): ?>


                            <tr class="hover:bg-slate-50">



                                <td class="px-6 py-5 text-slate-500">

                                    <?= $no++ ?>

                                </td>






                                <td class="px-6 py-5">


                                    <div class="flex items-center gap-4">


                                        <?php if (!empty($row["image"])): ?>


                                            <img

                                                src="<?= APP_URL ?>uploads/village/institutions/<?= $row["image"] ?>"

                                                class="h-12 w-12 rounded-xl object-cover">


                                        <?php else: ?>


                                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-100 text-teal-600">

                                                <i class="bi bi-building"></i>

                                            </div>


                                        <?php endif; ?>



                                        <div>


                                            <h3 class="font-semibold text-slate-900">

                                                <?= htmlspecialchars(
                                                    $row["name"]
                                                ) ?>

                                            </h3>


                                            <p class="text-sm text-slate-500">

                                                <?= htmlspecialchars(
                                                    $row["chairman"]
                                                ) ?>

                                            </p>


                                        </div>


                                    </div>


                                </td>







                                <td class="px-6 py-5">


                                    <span class="rounded-full bg-teal-100 px-3 py-1 text-sm text-teal-700">

                                        <?= $row["category"] ?>

                                    </span>


                                </td>







                                <td class="px-6 py-5">

                                    <?= htmlspecialchars(
                                        $row["chairman"] ?? "-"
                                    ) ?>

                                </td>







                                <td class="px-6 py-5">

                                    <?= number_format($row["total_members"]) ?>

                                </td>







                                <td class="px-6 py-5">


                                    <?php if ($row["status"] == "Active"): ?>


                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm text-emerald-700">

                                            Aktif

                                        </span>


                                    <?php else: ?>


                                        <span class="rounded-full bg-yellow-100 px-3 py-1 text-sm text-yellow-700">

                                            Tidak Aktif

                                        </span>


                                    <?php endif; ?>


                                </td>







                                <td class="px-6 py-5">


                                    <div class="flex justify-center gap-2">



                                        <a

                                            href="detail.php?id=<?= $row["id"] ?>"

                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-100 text-sky-600">

                                            <i class="bi bi-eye"></i>

                                        </a>
                                        <a

                                            href="create-member.php?id=<?= $row['id']; ?>"

                                            class="h-10 w-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">

                                            <i class="bi bi-people"></i>

                                        </a>





                                        <a

                                            href="edit.php?id=<?= $row["id"] ?>"

                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-500 text-white">

                                            <i class="bi bi-pencil"></i>

                                        </a>





                                        <a

                                            onclick="return confirm('Yakin ingin menghapus lembaga ini?')"

                                            href="delete.php?id=<?= $row["id"] ?>"

                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-500 text-white">

                                            <i class="bi bi-trash"></i>

                                        </a>




                                    </div>


                                </td>




                            </tr>



                        <?php endwhile; ?>


                    <?php
                    else:
                    ?>


                        <tr>

                            <td colspan="7" class="px-6 py-20 text-center">


                                <i class="bi bi-building text-5xl text-slate-300"></i>


                                <h3 class="mt-4 text-lg font-semibold text-slate-700">

                                    Belum ada lembaga desa

                                </h3>


                                <p class="text-slate-500">

                                    Silakan tambahkan lembaga desa pertama.

                                </p>


                            </td>

                        </tr>


                    <?php
                    endif;
                    ?>


                </tbody>


            </table>


        </div>


    </div>









    <!-- PAGINATION -->

    <?php if ($totalPage > 1): ?>


        <div class="mt-6 flex justify-center gap-2">


            <?php for ($i = 1; $i <= $totalPage; $i++): ?>


                <a

                    href="?page=<?= $i ?>&search=<?= $search ?>&category=<?= $category ?>"

                    class="rounded-lg px-4 py-2 <?= $page == $i
                                                    ? "bg-teal-600 text-white"
                                                    : "bg-white border text-slate-700" ?>">

                    <?= $i ?>

                </a>


            <?php endfor; ?>


        </div>


    <?php endif; ?>




</div>



<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>