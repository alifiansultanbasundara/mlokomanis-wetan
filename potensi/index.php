<?php

require_once "../config/app.php";

$page = "potensi";


// ==================================================
// PROFIL DESA
// ==================================================

$queryProfile = mysqli_query($conn, "

    SELECT *

    FROM village_profiles

    LIMIT 1

");


$villageProfile = mysqli_fetch_assoc($queryProfile);



if (!$villageProfile) {


    $villageProfile = [

        'village_name' => 'Website Desa'

    ];
}




// ==================================================
// FILTER KATEGORI
// ==================================================

$category = $_GET['category'] ?? '';



$allowedCategory = [

    'UMKM',
    'Pertanian',
    'Perkebunan',
    'Peternakan',
    'Perikanan',
    'Wisata'

];





// ==================================================
// QUERY POTENSI DESA
// ==================================================

$where = "

    WHERE status='Published'

";




if (

    !empty($category)

    &&

    in_array($category, $allowedCategory)

) {


    $safeCategory = mysqli_real_escape_string(
        $conn,
        $category
    );


    $where .= "

        AND category='$safeCategory'

    ";
}





$potentialQuery = mysqli_query($conn, "

    SELECT *

    FROM village_potentials

    $where

    ORDER BY

        featured='Yes' DESC,

        sort_order ASC,

        id DESC

");





// ==================================================
// TOTAL DATA
// ==================================================

$totalPotential = mysqli_num_rows($potentialQuery);





// ==================================================
// META
// ==================================================

$title = "Potensi Desa";


$metaTitle =

    "Potensi Desa | " .

    $villageProfile['village_name'];



$metaDescription =

    "Informasi potensi unggulan Desa " .

    $villageProfile['village_name'] .

    " mulai dari UMKM, pertanian, peternakan, dan berbagai potensi masyarakat desa.";



?>



<!DOCTYPE html>

<html lang="id">


<head>


    <?php include "../includes/head.php"; ?>


    <style>
        [x-cloak] {

            display: none !important;

        }
    </style>


    <script

        defer

        src="https://cdn.jsdelivr.net/npm/alpinejs/dist/cdn.min.js">

    </script>



</head>




<body class="bg-slate-50 text-slate-800">



    <?php include "../includes/guest/navbar.php"; ?>





    <!-- ================================================= -->
    <!-- HERO -->
    <!-- ================================================= -->


    <section

        class="relative overflow-hidden bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 pt-20 text-white">



        <!-- Decoration -->


        <div class="absolute inset-0 opacity-20">


            <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-white"></div>


            <div class="absolute -left-20 bottom-0 h-72 h-72 rounded-full bg-white"></div>


        </div>





        <div class="relative max-w-7xl mx-auto px-6 py-24 text-center">





            <span

                class="inline-flex items-center gap-2 rounded-full bg-white/20 px-5 py-2 text-sm font-semibold backdrop-blur">


                <i class="bi bi-stars"></i>


                Potensi Desa


            </span>







            <h1

                class="mt-6 text-4xl md:text-5xl font-black leading-tight">



                Potensi Unggulan Desa


                <br>


                <span class="text-teal-100">


                    <?= htmlspecialchars(
                        $villageProfile['village_name']
                    ); ?>


                </span>



            </h1>







            <p

                class="mx-auto mt-6 max-w-3xl text-lg leading-8 text-teal-100">


                Jelajahi berbagai potensi unggulan masyarakat desa,

                mulai dari usaha mikro, pertanian, peternakan,

                hingga berbagai produk lokal yang menjadi kekuatan ekonomi desa.



            </p>






        </div>



    </section>

    <?php

    // ==================================================
    // FILTER & SEARCH
    // ==================================================

    $keyword = $_GET['keyword'] ?? '';
    $category = $_GET['category'] ?? '';


    // ===============================
    // WHERE
    // ===============================

    $where = [];

    $where[] = "status='Published'";


    if (!empty($keyword)) {

        $key = mysqli_real_escape_string(
            $conn,
            $keyword
        );

        $where[] = "
        (
            title LIKE '%$key%'
            OR description LIKE '%$key%'
            OR owner_name LIKE '%$key%'
            OR organization LIKE '%$key%'
        )
    ";
    }


    if (!empty($category)) {

        $cat = mysqli_real_escape_string(
            $conn,
            $category
        );

        $where[] = "
        category='$cat'
    ";
    }


    $whereSQL = implode(
        " AND ",
        $where
    );


    // ==================================================
    // PAGINATION
    // ==================================================

    $limit = 9;

    $pageNumber = isset($_GET['page'])
        ? (int) $_GET['page']
        : 1;


    if ($pageNumber < 1) {
        $pageNumber = 1;
    }


    $offset = ($pageNumber - 1) * $limit;



    // ==================================================
    // HITUNG TOTAL DATA
    // ==================================================

    $countQuery = mysqli_query($conn, "

    SELECT COUNT(*) AS total

    FROM village_potentials

    WHERE $whereSQL

");


    $countData = mysqli_fetch_assoc($countQuery);


    $totalData = $countData['total'];


    $totalPages = ceil(
        $totalData / $limit
    );



    // ==================================================
    // QUERY DATA DENGAN LIMIT
    // ==================================================

    $potensiQuery = mysqli_query($conn, "

    SELECT *

    FROM village_potentials

    WHERE $whereSQL

    ORDER BY
        featured='Yes' DESC,
        sort_order ASC,
        id DESC

    LIMIT $limit OFFSET $offset

");




    // ==================================================
    // AMBIL DATA POTENSI
    // ==================================================

    $potensiQuery = mysqli_query($conn, "

    SELECT *

    FROM village_potentials

    WHERE $whereSQL

    ORDER BY
        featured='Yes' DESC,
        sort_order ASC,
        id DESC

");



    // ==================================================
    // KATEGORI
    // ==================================================

    $categoryQuery = mysqli_query($conn, "

    SELECT DISTINCT category

    FROM village_potentials

    WHERE status='Published'

    ORDER BY category ASC

");

    ?>


    <section class="py-20 bg-slate-50">


        <div class="max-w-7xl mx-auto px-6">



            <!-- Heading -->

            <div class="max-w-3xl">

                <span class="inline-flex items-center gap-2 rounded-full bg-teal-100 px-4 py-2 text-sm font-semibold text-teal-700">

                    <i class="bi bi-grid-fill"></i>

                    Potensi Desa

                </span>


                <h2 class="mt-5 text-4xl font-black text-slate-900">

                    Jelajahi Potensi
                    <?= htmlspecialchars($villageProfile['village_name']); ?>

                </h2>


                <p class="mt-4 text-lg text-slate-500">

                    Temukan berbagai potensi unggulan desa mulai dari UMKM,
                    pertanian, peternakan, hingga produk kreatif masyarakat.

                </p>


            </div>




            <!-- FILTER -->

            <form
                method="GET"
                class="mt-10 rounded-3xl bg-white p-6 shadow-sm border border-slate-200">


                <div class="grid gap-4 md:grid-cols-3">



                    <!-- Search -->

                    <div class="relative">


                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>


                        <input

                            type="text"

                            name="keyword"

                            value="<?= htmlspecialchars($keyword); ?>"

                            placeholder="Cari potensi desa..."

                            class="w-full rounded-xl border border-slate-300 py-3 pl-12 pr-4 focus:border-teal-600 outline-none">


                    </div>




                    <!-- Category -->

                    <select

                        name="category"

                        class="rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600">


                        <option value="">
                            Semua Kategori
                        </option>


                        <?php while ($cat = mysqli_fetch_assoc($categoryQuery)): ?>


                            <option

                                value="<?= htmlspecialchars($cat['category']); ?>"

                                <?= $category == $cat['category'] ? 'selected' : ''; ?>>

                                <?= htmlspecialchars($cat['category']); ?>


                            </option>


                        <?php endwhile; ?>


                    </select>




                    <!-- Button -->

                    <button

                        class="rounded-xl bg-teal-600 px-6 py-3 font-semibold text-white hover:bg-teal-700">


                        <i class="bi bi-filter"></i>

                        Tampilkan


                    </button>



                </div>


            </form>






            <!-- CARD -->

            <div class="mt-12 grid gap-8 md:grid-cols-2 lg:grid-cols-3">



                <?php if (mysqli_num_rows($potensiQuery) > 0): ?>



                    <?php while ($row = mysqli_fetch_assoc($potensiQuery)): ?>



                        <article

                            class="overflow-hidden rounded-3xl bg-white shadow-sm border border-slate-200 transition hover:-translate-y-1 hover:shadow-xl">



                            <!-- Image -->

                            <div class="relative h-56 overflow-hidden">


                                <?php if (!empty($row['image'])): ?>


                                    <img

                                        src="<?= APP_URL ?>uploads/potentials/<?= htmlspecialchars($row['image']); ?>"

                                        class="h-full w-full object-cover transition duration-500 hover:scale-110">


                                <?php else: ?>


                                    <div class="flex h-full items-center justify-center bg-slate-100">


                                        <i class="bi bi-image text-6xl text-slate-300"></i>


                                    </div>


                                <?php endif; ?>




                                <span

                                    class="absolute left-4 top-4 rounded-full bg-teal-600 px-4 py-1 text-xs font-bold text-white">


                                    <?= htmlspecialchars($row['category']); ?>


                                </span>



                            </div>






                            <!-- Content -->


                            <div class="p-6">



                                <h3 class="text-xl font-bold text-slate-900">


                                    <?= htmlspecialchars($row['title']); ?>


                                </h3>




                                <p class="mt-3 text-sm leading-6 text-slate-500">


                                    <?= mb_substr(

                                        strip_tags($row['description'] ?? ''),

                                        0,

                                        120

                                    ); ?>...


                                </p>




                                <?php if (!empty($row['owner_name'])): ?>


                                    <div class="mt-5 flex items-center gap-2 text-sm text-slate-600">


                                        <i class="bi bi-person-circle text-teal-600"></i>


                                        <?= htmlspecialchars($row['owner_name']); ?>


                                    </div>


                                <?php endif; ?>





                                <?php if (!empty($row['whatsapp'])): ?>


                                    <a

                                        href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $row['whatsapp']); ?>"

                                        target="_blank"

                                        class="mt-5 inline-flex items-center gap-2 rounded-xl bg-green-500 px-4 py-2 text-sm font-semibold text-white hover:bg-green-600">


                                        <i class="bi bi-whatsapp"></i>

                                        Hubungi


                                    </a>


                                <?php endif; ?>
                                <div class="mt-3 w-full">

                                    <a
                                        href="<?= APP_URL ?>potensi/detail.php?slug=<?= $row['slug']; ?>"
                                        class="block w-full rounded-xl bg-teal-600 py-3 text-center font-semibold text-white transition hover:bg-teal-700">

                                        Detail

                                    </a>

                                </div>


                            </div>


                        </article>



                    <?php endwhile; ?>



                <?php else: ?>



                    <div class="col-span-full rounded-3xl bg-white p-12 text-center">


                        <i class="bi bi-search text-6xl text-slate-300"></i>


                        <h3 class="mt-5 text-xl font-bold">

                            Potensi Tidak Ditemukan

                        </h3>


                        <p class="mt-2 text-slate-500">

                            Coba gunakan kata kunci atau kategori lainnya.

                        </p>


                    </div>



                <?php endif; ?>



            </div>

            <!-- ================================================= -->
            <!-- PAGINATION -->
            <!-- ================================================= -->


            <?php if ($totalPages > 1): ?>


                <div class="mt-12 flex justify-center">


                    <nav class="flex items-center gap-2">


                        <!-- Previous -->

                        <?php if ($pageNumber > 1): ?>


                            <a

                                href="?page=<?= $pageNumber - 1; ?>&keyword=<?= urlencode($keyword); ?>&category=<?= urlencode($category); ?>"

                                class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-teal-50 hover:text-teal-700">


                                <i class="bi bi-chevron-left"></i>


                            </a>


                        <?php endif; ?>






                        <!-- Number -->

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>


                            <?php if (
                                $i == 1 ||
                                $i == $totalPages ||
                                abs($i - $pageNumber) <= 2
                            ): ?>


                                <a

                                    href="?page=<?= $i; ?>&keyword=<?= urlencode($keyword); ?>&category=<?= urlencode($category); ?>"

                                    class="flex h-10 w-10 items-center justify-center rounded-xl font-semibold transition

                    <?= $i == $pageNumber

                                    ? 'bg-teal-600 text-white'

                                    : 'border border-slate-300 bg-white text-slate-700 hover:bg-teal-50 hover:text-teal-700';

                    ?>">


                                    <?= $i; ?>


                                </a>



                            <?php elseif (
                                $i == $pageNumber - 3 ||
                                $i == $pageNumber + 3
                            ): ?>


                                <span class="px-2 text-slate-400">

                                    ...

                                </span>



                            <?php endif; ?>


                        <?php endfor; ?>






                        <!-- Next -->

                        <?php if ($pageNumber < $totalPages): ?>


                            <a

                                href="?page=<?= $pageNumber + 1; ?>&keyword=<?= urlencode($keyword); ?>&category=<?= urlencode($category); ?>"

                                class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-teal-50 hover:text-teal-700">


                                <i class="bi bi-chevron-right"></i>


                            </a>


                        <?php endif; ?>


                    </nav>


                </div>


            <?php endif; ?>


        </div>


    </section>

    <?php include "../includes/guest/footer.php"; ?>

</body>

</html>