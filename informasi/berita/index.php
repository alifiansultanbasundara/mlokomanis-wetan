<?php

require_once "../../config/app.php";

$page = "berita";

// ======================================
// Profil Desa
// ======================================

$profileQuery = mysqli_query($conn, "
    SELECT village_name
    FROM village_profiles
    LIMIT 1
");

$village = mysqli_fetch_assoc($profileQuery);

if (!$village) {
    $village = [
        'village_name' => 'Website Desa'
    ];
}

// ======================================
// Meta
// ======================================

$title = "Berita Desa {$village['village_name']}";
$metaTitle = "Berita | {$village['village_name']}";
$metaDescription = "Informasi, kegiatan, pembangunan, serta berita terbaru Desa {$village['village_name']}.";


// ======================================
// Pagination
// ======================================

$limit = 9;

$currentPage = isset($_GET['page'])
    ? max(1, (int) $_GET['page'])
    : 1;

$offset = ($currentPage - 1) * $limit;


// ======================================
// Search
// ======================================

$search = trim($_GET['search'] ?? '');

$where = "
WHERE
    articles.status='Published'
    AND articles.category='Berita'
";

if ($search != '') {

    $keyword = mysqli_real_escape_string($conn, $search);

    $where .= "
        AND (
            articles.title LIKE '%{$keyword}%'
            OR articles.excerpt LIKE '%{$keyword}%'
        )
    ";
}


// ======================================
// Featured
// ======================================

$featuredQuery = mysqli_query($conn, "
    SELECT
        articles.*,
        users.nama AS author
    FROM articles
    JOIN users
        ON users.id = articles.author_id
    $where
    ORDER BY
        articles.created_at DESC
    LIMIT 1
");

$featured = mysqli_fetch_assoc($featuredQuery);


// ======================================
// Hindari Featured muncul dua kali
// ======================================

$excludeFeatured = "";

if (!empty($featured)) {
    $excludeFeatured = " AND articles.id != {$featured['id']} ";
}


// ======================================
// Total Artikel
// ======================================

$totalQuery = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM articles
    $where
    $excludeFeatured
");

$totalData = mysqli_fetch_assoc($totalQuery)['total'];

$totalPage = max(1, ceil($totalData / $limit));


// ======================================
// List Artikel
// ======================================

$query = mysqli_query($conn, "
    SELECT
        articles.*,
        users.nama AS author
    FROM articles
    JOIN users
        ON users.id = articles.author_id
    $where
    $excludeFeatured
    ORDER BY
        articles.created_at DESC
    LIMIT $limit
    OFFSET $offset
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <?php include "../../includes/head.php"; ?>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse/dist/cdn.min.js"></script>

</head>

<body class="bg-slate-50">

    <?php include "../../includes/guest/navbar.php"; ?>

    <section class="bg-gradient-to-r from-teal-700 to-teal-500 text-white pt-20">

        <div class="max-w-7xl mx-auto px-6 py-20">

            <h1 class="text-5xl font-bold">

                Berita Desa

            </h1>

            <p class="mt-5 text-teal-100 max-w-2xl">

                Berita terbaru, kegiatan, dan informasi resmi Pemerintah Desa.

            </p>

        </div>

    </section>

    <section class="py-10">

        <div class="max-w-7xl mx-auto px-6">

            <form>

                <div class="relative">

                    <i class="bi bi-search absolute left-5 top-4 text-slate-400"></i>

                    <input
                        name="search"
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                        placeholder="Cari berita..."
                        class="w-full rounded-2xl border border-slate-300 pl-14 pr-5 py-4 focus:border-teal-500 outline-none">

                </div>

            </form>

        </div>

    </section>

    <?php if ($featured): ?>

        <section class="pb-16">

            <div class="max-w-7xl mx-auto px-6">

                <a
                    href="detail.php?slug=<?= $featured['slug'] ?>"
                    class="grid lg:grid-cols-2 bg-white rounded-3xl overflow-hidden shadow hover:shadow-lg transition">

                    <img
                        src="<?= APP_URL ?>uploads/informasi/berita/<?= $featured['thumbnail'] ?>"
                        class="h-full w-full object-cover">

                    <div class="p-10">

                        <span class="bg-teal-100 text-teal-700 px-3 py-1 rounded-full text-xs">

                            Berita Terbaru

                        </span>

                        <h2 class="text-4xl font-bold mt-5">

                            <?= $featured['title'] ?>

                        </h2>

                        <p class="mt-5 text-slate-600">

                            <?= $featured['excerpt'] ?>

                        </p>

                        <div class="mt-8 flex gap-5 text-sm text-slate-500">

                            <span>

                                <i class="bi bi-calendar3"></i>

                                <?= date('d M Y', strtotime($featured['created_at'])) ?>

                            </span>

                            <span>

                                <i class="bi bi-person"></i>

                                <?= $featured['author'] ?>

                            </span>

                        </div>

                    </div>

                </a>

            </div>

        </section>

    <?php endif; ?>

    <section class="pb-20">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">

                <?php while ($row = mysqli_fetch_assoc($query)): ?>

                    <a
                        href="detail.php?slug=<?= $row['slug'] ?>"
                        class="bg-white rounded-3xl overflow-hidden shadow hover:-translate-y-1 hover:shadow-xl transition">

                        <img
                            src="<?= APP_URL ?>uploads/informasi/berita/<?= $row['thumbnail'] ?>"
                            class="h-56 w-full object-cover">

                        <div class="p-6">

                            <div class="flex justify-between text-sm text-slate-500">

                                <span>

                                    <?= date('d M Y', strtotime($row['created_at'])) ?>

                                </span>

                                <span>

                                    <i class="bi bi-eye"></i>

                                    <?= number_format($row['views']) ?>

                                </span>

                            </div>

                            <h3 class="mt-4 font-bold text-xl">

                                <?= $row['title'] ?>

                            </h3>

                            <p class="mt-3 text-slate-600 line-clamp-3">

                                <?= $row['excerpt'] ?>

                            </p>

                            <div class="mt-6 flex justify-between items-center">

                                <span class="text-sm text-slate-500">

                                    <?= $row['author'] ?>

                                </span>

                                <span class="text-teal-600 font-semibold">

                                    Baca →

                                </span>

                            </div>

                        </div>

                    </a>

                <?php endwhile; ?>

            </div>

        </div>

    </section>

    <?php if ($totalPage > 1): ?>

        <div class="pb-20">

            <div class="flex justify-center gap-2">

                <?php for ($i = 1; $i <= $totalPage; $i++): ?>

                    <a
                        href="?page=<?= $i ?>&search=<?= urlencode($_GET['search'] ?? '') ?>"
                        class="w-11 h-11 rounded-xl flex items-center justify-center
<?= $i == $currentPage
                        ? 'bg-teal-600 text-white'
                        : 'bg-white border hover:bg-slate-100' ?>">

                        <?= $i ?>

                    </a>

                <?php endfor; ?>

            </div>

        </div>

    <?php endif; ?>

    <?php include "../../includes/guest/footer.php"; ?>