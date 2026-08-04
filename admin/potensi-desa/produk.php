<?php

require_once '../../config/app.php';


// ======================================================
// Validasi Potential
// ======================================================

if (!isset($_GET['potential_id'])) {

    header("Location:index.php");
    exit;
}


$potential_id = (int) $_GET['potential_id'];



// ======================================================
// Ambil Potensi Desa
// ======================================================

$potentialQuery = mysqli_query($conn, "
    SELECT *
    FROM village_potentials
    WHERE id='$potential_id'
    LIMIT 1
");


if (mysqli_num_rows($potentialQuery) == 0) {

    header("Location:index.php");
    exit;
}


$potential = mysqli_fetch_assoc($potentialQuery);



// ======================================================
// Filter
// ======================================================

$keyword = isset($_GET['keyword'])
    ? mysqli_real_escape_string($conn, $_GET['keyword'])
    : '';



$category = isset($_GET['category'])
    ? mysqli_real_escape_string($conn, $_GET['category'])
    : '';



$status = isset($_GET['status'])
    ? mysqli_real_escape_string($conn, $_GET['status'])
    : '';



// ======================================================
// Pagination
// ======================================================

$limit = 10;


$pageNumber = isset($_GET['page'])
    ? (int)$_GET['page']
    : 1;


if ($pageNumber < 1) {

    $pageNumber = 1;
}


$offset = ($pageNumber - 1) * $limit;



// ======================================================
// WHERE QUERY
// ======================================================

$where = "
    WHERE potential_id='$potential_id'
";



if ($keyword) {

    $where .= "
        AND name LIKE '%$keyword%'
    ";
}



if ($category) {

    $where .= "
        AND category='$category'
    ";
}



if ($status) {

    $where .= "
        AND status='$status'
    ";
}



// ======================================================
// Total Data
// ======================================================

$totalQuery = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM village_potential_products
    $where
");


$totalData = mysqli_fetch_assoc($totalQuery)['total'];



$totalPage = ceil($totalData / $limit);



// ======================================================
// Ambil Produk
// ======================================================

$products = mysqli_query($conn, "
    SELECT *
    FROM village_potential_products
    $where
    ORDER BY id DESC
    LIMIT $limit OFFSET $offset
");



// ======================================================
// Ambil Kategori
// ======================================================

$categories = mysqli_query($conn, "
    SELECT DISTINCT category
    FROM village_potential_products
    WHERE potential_id='$potential_id'
    ORDER BY category ASC
");




// ======================================================

$title = "Produk Potensi Desa";
$page  = "potensi-desa";

include APP_PATH . "includes/admin/layout-top.php";

?>



<main class="p-8 space-y-6">



    <!-- HEADER -->

    <div class="flex justify-between items-center">


        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Produk Potensi Desa

            </h1>


            <p class="text-slate-500 mt-2">

                <?= htmlspecialchars($potential['title']); ?>

                <span class="mx-2">•</span>

                <?= htmlspecialchars($potential['category']); ?>

            </p>


        </div>



        <div class="flex gap-3">


            <a
                href="index.php"
                class="px-5 py-3 rounded-xl border">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>



            <a
                href="produk-create.php?potential_id=<?= $potential_id ?>"
                class="px-5 py-3 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">


                <i class="bi bi-plus-circle"></i>

                Tambah Produk


            </a>


        </div>


    </div>





    <!-- FILTER -->

    <div class="bg-white rounded-2xl border shadow-sm p-5">


        <form method="GET"
            class="grid md:grid-cols-4 gap-4">


            <input type="hidden"
                name="potential_id"
                value="<?= $potential_id ?>">



            <!-- Search -->

            <div>

                <label class="text-sm text-slate-600">

                    Cari Produk

                </label>


                <input
                    type="text"
                    name="keyword"
                    value="<?= htmlspecialchars($keyword); ?>"
                    placeholder="Nama produk..."
                    class="w-full mt-2 px-4 py-3 rounded-xl border">

            </div>




            <!-- Category -->

            <div>


                <label class="text-sm text-slate-600">

                    Kategori

                </label>


                <select
                    name="category"
                    class="w-full mt-2 px-4 py-3 rounded-xl border">


                    <option value="">

                        Semua Kategori

                    </option>


                    <?php while ($cat = mysqli_fetch_assoc($categories)): ?>


                        <option
                            value="<?= $cat['category']; ?>"
                            <?= $category == $cat['category'] ? 'selected' : ''; ?>>

                            <?= htmlspecialchars($cat['category']); ?>

                        </option>


                    <?php endwhile; ?>


                </select>


            </div>





            <!-- Status -->


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





            <div class="flex items-end gap-2">


                <button
                    class="px-5 py-3 bg-teal-600 text-white rounded-xl">

                    <i class="bi bi-search"></i>

                    Filter

                </button>



                <a
                    href="produk.php?potential_id=<?= $potential_id ?>"
                    class="px-5 py-3 border rounded-xl">

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
                <?= mysqli_num_rows($products); ?>
            </span>

            dari

            <span class="font-semibold text-slate-700">
                <?= $totalData; ?>
            </span>

            produk

        </p>


    </div>





    <!-- TABLE -->

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">


        <div class="overflow-x-auto">


            <table class="min-w-full">


                <thead class="bg-slate-50">


                    <tr class="text-left text-sm font-semibold text-slate-600">


                        <th class="px-6 py-4">
                            No
                        </th>


                        <th class="px-6 py-4">
                            Foto
                        </th>


                        <th class="px-6 py-4">
                            Nama Produk
                        </th>


                        <th class="px-6 py-4">
                            Kategori
                        </th>


                        <th class="px-6 py-4">
                            Harga
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


                    <?php if (mysqli_num_rows($products) > 0): ?>


                        <?php

                        $no = $offset + 1;


                        while ($row = mysqli_fetch_assoc($products)):

                        ?>



                            <tr class="border-t hover:bg-slate-50 transition">


                                <td class="px-6 py-4">

                                    <?= $no++; ?>

                                </td>





                                <td class="px-6 py-4">


                                    <?php if (!empty($row['image'])): ?>


                                        <img
                                            src="<?= APP_URL ?>uploads/potentials/products/<?= $row['image']; ?>"
                                            class="w-16 h-16 rounded-xl object-cover">


                                    <?php else: ?>


                                        <div
                                            class="w-16 h-16 rounded-xl bg-slate-100 flex items-center justify-center">


                                            <i class="bi bi-image text-slate-400 text-xl"></i>


                                        </div>


                                    <?php endif; ?>


                                </td>





                                <td class="px-6 py-4">


                                    <div class="font-semibold text-slate-800">

                                        <?= htmlspecialchars($row['name']); ?>

                                    </div>


                                    <?php if (!empty($row['description'])): ?>


                                        <p class="text-xs text-slate-500 mt-1 max-w-xs truncate">

                                            <?= htmlspecialchars($row['description']); ?>

                                        </p>


                                    <?php endif; ?>


                                </td>





                                <td class="px-6 py-4">


                                    <span class="px-3 py-1 rounded-full text-xs bg-slate-100 text-slate-700">


                                        <?= htmlspecialchars($row['category']); ?>


                                    </span>


                                </td>





                                <td class="px-6 py-4 font-medium">


                                    Rp <?= number_format($row['price'], 0, ',', '.'); ?>


                                </td>





                                <td class="px-6 py-4">


                                    <?php if ($row['status'] == "Published"): ?>


                                        <span
                                            class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs">


                                            Published


                                        </span>


                                    <?php else: ?>


                                        <span
                                            class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">


                                            Draft


                                        </span>


                                    <?php endif; ?>


                                </td>





                                <td class="px-6 py-4">


                                    <div class="flex justify-center gap-2">


                                        <a
                                            href="produk-edit.php?id=<?= $row['id']; ?>"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">


                                            <i class="bi bi-pencil"></i>


                                        </a>




                                        <a
                                            href="produk-delete.php?id=<?= $row['id']; ?>"
                                            onclick="return confirm('Hapus produk ini?')"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100">


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


                                <i class="bi bi-box text-5xl text-slate-300 block mb-3"></i>


                                <p class="text-slate-500">

                                    Belum ada produk ditemukan.

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



                <?php if ($pageNumber > 1): ?>


                    <a
                        href="?potential_id=<?= $potential_id; ?>&page=<?= $pageNumber - 1; ?>&keyword=<?= $keyword; ?>&category=<?= $category; ?>&status=<?= $status; ?>"
                        class="px-4 py-2 rounded-lg border hover:bg-slate-50">


                        <i class="bi bi-chevron-left"></i>


                    </a>


                <?php endif; ?>





                <?php for ($i = 1; $i <= $totalPage; $i++): ?>


                    <a
                        href="?potential_id=<?= $potential_id; ?>&page=<?= $i; ?>&keyword=<?= $keyword; ?>&category=<?= $category; ?>&status=<?= $status; ?>"
                        class="
px-4 py-2 rounded-lg border
<?= $i == $pageNumber
                        ? 'bg-teal-600 text-white border-teal-600'
                        : 'hover:bg-slate-50';
?>
">


                        <?= $i; ?>


                    </a>



                <?php endfor; ?>





                <?php if ($pageNumber < $totalPage): ?>


                    <a
                        href="?potential_id=<?= $potential_id; ?>&page=<?= $pageNumber + 1; ?>&keyword=<?= $keyword; ?>&category=<?= $category; ?>&status=<?= $status; ?>"
                        class="px-4 py-2 rounded-lg border hover:bg-slate-50">


                        <i class="bi bi-chevron-right"></i>


                    </a>


                <?php endif; ?>



            </div>


        </div>


    <?php endif; ?>



</main>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>