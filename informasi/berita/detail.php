<?php

require_once "../../config/app.php";

$page = "berita";

// ===============================
// Validasi Slug
// ===============================

if (!isset($_GET['slug']) || empty($_GET['slug'])) {

    header("Location: index.php");
    exit;
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);


// ===============================
// Tambah Views
// ===============================

mysqli_query($conn, "
UPDATE articles
SET views = views + 1
WHERE slug='$slug'
AND status='Published'
");


// ===============================
// Ambil Detail
// ===============================

$query = mysqli_query($conn, "
SELECT
    articles.*,
    users.nama AS author
FROM articles
JOIN users
ON users.id = articles.author_id
WHERE
    articles.slug='$slug'
AND articles.status='Published'
LIMIT 1
");

if (mysqli_num_rows($query) == 0) {

    header("Location: index.php");
    exit;
}

$article = mysqli_fetch_assoc($query);


// ===============================
// Berita Lainnya
// ===============================

$related = mysqli_query($conn, "
SELECT
    id,
    title,
    slug,
    thumbnail,
    created_at
FROM articles
WHERE
    status='Published'
AND category='Berita'
AND id != {$article['id']}
ORDER BY created_at DESC
LIMIT 4
");

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>

        <?= htmlspecialchars($article['title']) ?>

    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-slate-50">

    <?php include "../../layouts/navbar.php"; ?>


    <!-- HERO -->

    <section class="bg-gradient-to-r from-teal-700 to-teal-500 text-white">

        <div class="max-w-5xl mx-auto px-6 py-16">

            <div class="text-sm text-teal-100">

                <a href="<?= APP_URL ?>beranda.php">

                    Beranda

                </a>

                /

                <a href="index.php">

                    Berita

                </a>

            </div>

            <h1 class="mt-5 text-4xl font-bold leading-tight">

                <?= htmlspecialchars($article['title']) ?>

            </h1>

            <div class="mt-6 flex flex-wrap gap-6 text-teal-100">

                <span>

                    <i class="bi bi-calendar3"></i>

                    <?= date('d F Y', strtotime($article['created_at'])) ?>

                </span>

                <span>

                    <i class="bi bi-person"></i>

                    <?= htmlspecialchars($article['author']) ?>

                </span>

                <span>

                    <i class="bi bi-eye"></i>

                    <?= number_format($article['views']) ?>

                    kali dibaca

                </span>

            </div>

        </div>

    </section>



    <!-- CONTENT -->

    <section class="py-16">

        <div class="max-w-5xl mx-auto px-6">

            <div class="bg-white rounded-3xl shadow overflow-hidden">

                <?php if (!empty($article['thumbnail'])): ?>

                    <img

                        src="<?= APP_URL ?>uploads/informasi/berita/<?= $article['thumbnail'] ?>"

                        class="w-full max-h-[520px] object-cover">

                <?php endif; ?>


                <div class="p-10">

                    <?php if (!empty($article['excerpt'])): ?>

                        <div class="bg-slate-100 rounded-xl p-6 italic text-slate-700 border-l-4 border-teal-600 mb-8">

                            <?= htmlspecialchars($article['excerpt']) ?>

                        </div>

                    <?php endif; ?>


                    <div class="prose prose-lg max-w-none leading-8">

                        <?= $article['content'] ?>

                    </div>


                    <div class="mt-12 pt-8 border-t flex items-center gap-3">

                        <span class="font-semibold">

                            Bagikan :

                        </span>

                        <a

                            target="_blank"

                            href="https://wa.me/?text=<?= urlencode($article['title']) ?>"

                            class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center">

                            <i class="bi bi-whatsapp"></i>

                        </a>

                        <a

                            target="_blank"

                            href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(APP_URL . 'informasi/berita/detail.php?slug=' . $article['slug']) ?>"

                            class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center">

                            <i class="bi bi-facebook"></i>

                        </a>

                        <a

                            target="_blank"

                            href="https://twitter.com/intent/tweet?text=<?= urlencode($article['title']) ?>"

                            class="w-10 h-10 rounded-full bg-sky-500 text-white flex items-center justify-center">

                            <i class="bi bi-twitter-x"></i>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- BERITA LAINNYA -->

    <section class="pb-20">

        <div class="max-w-7xl mx-auto px-6">

            <h2 class="text-3xl font-bold mb-8">

                Berita Lainnya

            </h2>

            <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">

                <?php while ($row = mysqli_fetch_assoc($related)): ?>

                    <a

                        href="detail.php?slug=<?= $row['slug'] ?>"

                        class="bg-white rounded-2xl overflow-hidden shadow hover:-translate-y-1 hover:shadow-xl transition">

                        <?php if (!empty($row['thumbnail'])): ?>

                            <img

                                src="<?= APP_URL ?>uploads/informasi/berita/<?= $row['thumbnail'] ?>"

                                class="h-44 w-full object-cover">

                        <?php endif; ?>

                        <div class="p-5">

                            <h3 class="font-bold line-clamp-2">

                                <?= htmlspecialchars($row['title']) ?>

                            </h3>

                            <p class="text-sm text-slate-500 mt-3">

                                <?= date('d M Y', strtotime($row['created_at'])) ?>

                            </p>

                        </div>

                    </a>

                <?php endwhile; ?>

            </div>

        </div>

    </section>

    <?php include "../../layouts/footer.php"; ?>

</body>

</html>