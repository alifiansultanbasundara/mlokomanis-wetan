<?php

require_once "../../config/app.php";

$page = "galeri";


// ======================================
// Profil Desa
// ======================================

$profile = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT *
    FROM village_profiles
    LIMIT 1
"));

if (!$profile) {

    $profile = [
        'village_name' => 'Website Desa'
    ];
}


// ======================================
// Statistik
// ======================================

$summary = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT

        COUNT(*) AS total_gallery,

        (
            SELECT COUNT(*)
            FROM gallery_images
        ) AS total_image

    FROM galleries

    WHERE status='Published'
"));

if (!$summary) {

    $summary = [
        'total_gallery' => 0,
        'total_image'   => 0
    ];
}


// ======================================
// Data Album
// ======================================

$query = mysqli_query($conn, "
    SELECT

        galleries.*,

        COUNT(gallery_images.id) AS total_photo

    FROM galleries

    LEFT JOIN gallery_images
        ON gallery_images.gallery_id = galleries.id

    WHERE galleries.status='Published'

    GROUP BY galleries.id

    ORDER BY
        galleries.priority DESC,
        galleries.created_at DESC
");


// ======================================
// Album Unggulan
// ======================================

$featured = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT

        galleries.*,

        COUNT(gallery_images.id) AS total_photo

    FROM galleries

    LEFT JOIN gallery_images
        ON gallery_images.gallery_id = galleries.id

    WHERE galleries.status='Published'

    GROUP BY galleries.id

    ORDER BY
        galleries.priority DESC,
        galleries.created_at DESC

    LIMIT 1
"));


// ======================================
// Meta
// ======================================

$title = "Galeri Desa | " . $profile['village_name'];

$metaTitle = $title;

$metaDescription = "Galeri foto kegiatan, pembangunan, pelayanan masyarakat, dan berbagai dokumentasi Desa " . $profile['village_name'];

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

<body class="bg-slate-50 text-slate-800">

    <?php include "../../includes/guest/navbar.php"; ?>


    <!-- HERO -->

    <section class="bg-gradient-to-r from-teal-700 to-teal-500 text-white pt-20">

        <div class="max-w-7xl mx-auto px-6 py-20">

            <h1 class="text-5xl font-bold">

                Galeri Desa

            </h1>

            <p class="mt-5 max-w-3xl text-teal-100">

                Dokumentasi kegiatan, pembangunan, pendidikan,
                kesehatan, serta berbagai aktivitas di Desa.

            </p>

        </div>

    </section>



    <!-- STATISTIK -->

    <section class="py-16">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid md:grid-cols-2 gap-6">

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Total Album

                    </p>

                    <h2 class="mt-2 text-4xl font-bold text-teal-600">

                        <?= number_format($summary['total_gallery']) ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Total Foto

                    </p>

                    <h2 class="mt-2 text-4xl font-bold text-teal-600">

                        <?= number_format($summary['total_image']) ?>

                    </h2>

                </div>

            </div>

        </div>

    </section>



    <!-- GRID -->

    <section class="pb-20">

        <div class="max-w-7xl mx-auto px-6">

            <?php if (mysqli_num_rows($query)) : ?>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">

                    <?php while ($row = mysqli_fetch_assoc($query)) : ?>

                        <div class="overflow-hidden rounded-3xl bg-white shadow hover:-translate-y-1 hover:shadow-xl transition">

                            <div class="aspect-[4/3] bg-slate-100 overflow-hidden">

                                <?php if ($row['cover_image']) : ?>

                                    <img
                                        src="<?= APP_URL ?>uploads/informasi/galeri/cover/<?= $row['cover_image'] ?>"
                                        class="h-full w-full object-cover transition duration-300 hover:scale-110">

                                <?php else : ?>

                                    <div class="flex h-full items-center justify-center">

                                        <i class="bi bi-images text-6xl text-slate-300"></i>

                                    </div>

                                <?php endif; ?>

                            </div>

                            <div class="p-6">

                                <div class="flex items-center justify-between">

                                    <span class="rounded-full bg-teal-100 px-3 py-1 text-xs font-medium text-teal-700">

                                        <?= $row['category'] ?>

                                    </span>

                                    <span class="text-sm text-slate-500">

                                        <?= $row['total_photo'] ?> Foto

                                    </span>

                                </div>

                                <h2 class="mt-5 text-2xl font-bold">

                                    <?= htmlspecialchars($row['title']) ?>

                                </h2>

                                <?php if ($row['description']) : ?>

                                    <p class="mt-3 text-slate-600 line-clamp-3">

                                        <?= htmlspecialchars($row['description']) ?>

                                    </p>

                                <?php endif; ?>

                                <div class="mt-6 flex items-center justify-between text-sm text-slate-500">

                                    <span>

                                        <i class="bi bi-calendar3 me-1"></i>

                                        <?= date('d M Y', strtotime($row['created_at'])) ?>

                                    </span>

                                </div>

                                <a

                                    href="detail.php?slug=<?= $row['slug'] ?>"

                                    class="mt-6 flex items-center justify-center gap-2 rounded-xl bg-teal-600 py-3 font-semibold text-white transition hover:bg-teal-700">

                                    <i class="bi bi-images"></i>

                                    Lihat Album

                                </a>

                            </div>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else : ?>

                <div class="rounded-3xl bg-white p-20 text-center shadow">

                    <i class="bi bi-images text-6xl text-slate-300"></i>

                    <h2 class="mt-6 text-3xl font-bold">

                        Belum Ada Album

                    </h2>

                    <p class="mt-3 text-slate-500">

                        Album galeri belum dipublikasikan.

                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>

    <?php include "../../includes/guest/footer.php"; ?>

</body>

</html>