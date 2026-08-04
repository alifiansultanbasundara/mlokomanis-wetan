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
// Validasi Slug
// ======================================

if (
    !isset($_GET['slug']) ||
    trim($_GET['slug']) == ''
) {

    header("Location: index.php");
    exit;
}

$slug = mysqli_real_escape_string(
    $conn,
    $_GET['slug']
);


// ======================================
// Detail Album
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM galleries
    WHERE slug='$slug'
    AND status='Published'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);


// ======================================
// Daftar Foto
// ======================================

$images = mysqli_query($conn, "
    SELECT *
    FROM gallery_images
    WHERE gallery_id={$data['id']}
    ORDER BY
        priority DESC,
        sort_order ASC,
        id ASC
");

$totalPhoto = mysqli_num_rows($images);


// ======================================
// Album Lainnya
// ======================================

$related = mysqli_query($conn, "
    SELECT

        galleries.*,

        COUNT(gallery_images.id) AS total_photo

    FROM galleries

    LEFT JOIN gallery_images
        ON gallery_images.gallery_id = galleries.id

    WHERE galleries.status='Published'
    AND galleries.slug != '$slug'

    GROUP BY galleries.id

    ORDER BY
        galleries.priority DESC,
        galleries.created_at DESC

    LIMIT 3
");


// ======================================
// Meta
// ======================================

$title = htmlspecialchars($data['title']) . " | " . $profile['village_name'];

$metaTitle = $title;

$metaDescription = !empty($data['description'])
    ? substr(strip_tags($data['description']), 0, 160)
    : "Galeri kegiatan Desa " . $profile['village_name'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <?php include "../../includes/head.php"; ?>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="bg-slate-50 text-slate-800">

    <?php include "../../includes/guest/navbar.php"; ?>


    <!-- HERO -->

    <section class="bg-gradient-to-r from-teal-700 to-teal-500 text-white pt-20">

        <div class="max-w-7xl mx-auto px-6 py-20">

            <p class="text-teal-100">

                <a href="<?= APP_URL ?>beranda.php">
                    Beranda
                </a>

                /

                <a href="index.php">
                    Galeri
                </a>

            </p>

            <h1 class="mt-4 text-5xl font-bold">

                <?= htmlspecialchars($data['title']) ?>

            </h1>

            <?php if ($data['description']) : ?>

                <p class="mt-5 max-w-3xl text-teal-100">

                    <?= htmlspecialchars($data['description']) ?>

                </p>

            <?php endif; ?>

            <div class="mt-8 flex flex-wrap gap-3">

                <span class="rounded-full bg-white/20 px-4 py-2">

                    <?= $data['category'] ?>

                </span>

                <span class="rounded-full bg-white/20 px-4 py-2">

                    <?= $totalPhoto ?> Foto

                </span>

            </div>

        </div>

    </section>



    <!-- INFORMASI -->

    <section class="py-12">

        <div class="max-w-7xl mx-auto px-6">

            <div class="bg-white rounded-3xl shadow p-8">

                <div class="grid md:grid-cols-3 gap-6">

                    <div>

                        <p class="text-sm text-slate-500">

                            Kategori

                        </p>

                        <p class="font-semibold mt-1">

                            <?= $data['category'] ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">

                            Jumlah Foto

                        </p>

                        <p class="font-semibold mt-1">

                            <?= $totalPhoto ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">

                            Dipublikasikan

                        </p>

                        <p class="font-semibold mt-1">

                            <?= date('d F Y', strtotime($data['created_at'])) ?>

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- GALERI FOTO -->

    <section
        x-data="{ open:false, image:'' }"
        class="pb-20">

        <div class="max-w-7xl mx-auto px-6">

            <?php if ($totalPhoto > 0) : ?>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

                    <?php

                    mysqli_data_seek($images, 0);

                    while ($row = mysqli_fetch_assoc($images)) :

                    ?>

                        <div
                            @click="
                            image='<?= APP_URL ?>uploads/informasi/galeri/<?= $row['image'] ?>';
                            open=true;
                        "
                            class="group cursor-pointer overflow-hidden rounded-2xl bg-white shadow hover:shadow-xl transition">

                            <img
                                src="<?= APP_URL ?>uploads/informasi/galeri/<?= $row['image'] ?>"
                                class="aspect-square w-full object-cover transition duration-300 group-hover:scale-110">

                            <?php if ($row['caption']) : ?>

                                <div class="border-t p-3 text-sm text-slate-600">

                                    <?= htmlspecialchars($row['caption']) ?>

                                </div>

                            <?php endif; ?>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else : ?>

                <div class="rounded-3xl bg-white p-20 text-center shadow">

                    <i class="bi bi-image text-6xl text-slate-300"></i>

                    <h2 class="mt-6 text-3xl font-bold">

                        Belum Ada Foto

                    </h2>

                    <p class="mt-3 text-slate-500">

                        Album ini belum memiliki foto.

                    </p>

                </div>

            <?php endif; ?>

        </div>



        <!-- LIGHTBOX -->

        <div

            x-show="open"

            x-cloak

            x-transition

            @keydown.escape.window="open=false"

            @click.self="open=false"

            class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-6">

            <button

                @click="open=false"

                class="absolute top-6 right-6 text-white text-4xl">

                &times;

            </button>

            <img

                :src="image"

                class="max-h-[90vh] max-w-full rounded-2xl shadow-2xl">

        </div>

    </section>

    <?php include "../../includes/guest/footer.php"; ?>

</body>

</html>