<?php

require_once "../../config/app.php";

$page = "pembangunan";

// ======================================
// Profil Desa
// ======================================

$profileQuery = mysqli_query($conn, "
    SELECT
        village_name
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
// Validasi Slug
// ======================================

if (
    !isset($_GET['slug']) ||
    trim($_GET['slug']) === ''
) {
    header("Location: pembangunan.php");
    exit;
}

$slug = mysqli_real_escape_string(
    $conn,
    trim($_GET['slug'])
);


// ======================================
// Detail Pembangunan
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM constructions
    WHERE
        slug = '$slug'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {

    header("Location: pembangunan.php");
    exit;
}

$data = mysqli_fetch_assoc($query);


// ======================================
// Meta
// ======================================

$title = "{$data['title']} | {$village['village_name']}";
$metaTitle = "{$data['title']} | {$village['village_name']}";
$metaDescription = !empty($data['description'])
    ? substr(strip_tags($data['description']), 0, 160)
    : "Detail pembangunan desa {$village['village_name']}.";


// ======================================
// Badge Status
// ======================================

function badgeStatus($status)
{
    switch ($status) {

        case "Selesai":
            return "bg-green-100 text-green-700";

        case "Berjalan":
            return "bg-blue-100 text-blue-700";

        case "Perencanaan":
            return "bg-yellow-100 text-yellow-700";

        default:
            return "bg-red-100 text-red-700";
    }
}


// ======================================
// Proyek Lainnya
// ======================================

$related = mysqli_query($conn, "
    SELECT
        title,
        slug,
        thumbnail,
        year,
        progress
    FROM constructions
    WHERE
        slug != '{$slug}'
    ORDER BY
        year DESC,
        created_at DESC
    LIMIT 3
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

    <!-- HERO -->

    <section class="bg-gradient-to-r from-teal-700 to-teal-500 text-white pt-20">

        <div class="max-w-7xl mx-auto px-6 py-16">

            <div class="text-sm text-teal-100">

                <a href="<?= APP_URL ?>beranda.php">Beranda</a>

                /

                <a href="index.php">Pembangunan Desa</a>

            </div>

            <h1 class="text-4xl font-bold mt-5">

                <?= htmlspecialchars($data['title']) ?>

            </h1>

            <div class="mt-6 flex flex-wrap gap-4">

                <span class="px-4 py-2 rounded-full bg-white/20">

                    <?= $data['category'] ?>

                </span>

                <span class="px-4 py-2 rounded-full bg-white/20">

                    <?= $data['year'] ?>

                </span>

            </div>

        </div>

    </section>



    <!-- CONTENT -->

    <section class="py-16">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid lg:grid-cols-3 gap-10">

                <div class="lg:col-span-2">

                    <div class="bg-white rounded-3xl shadow overflow-hidden">

                        <?php if ($data['thumbnail']): ?>

                            <img

                                src="<?= APP_URL ?>uploads/informasi/pembangunan/<?= $data['thumbnail'] ?>"

                                class="w-full max-h-[520px] object-cover">

                        <?php endif; ?>

                        <div class="p-10">

                            <h2 class="text-2xl font-bold">

                                Deskripsi Pembangunan

                            </h2>

                            <div class="mt-6 text-slate-600 leading-8">

                                <?= nl2br(htmlspecialchars($data['description'])) ?>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- SIDEBAR -->

                <div>

                    <div class="bg-white rounded-3xl shadow p-8 sticky top-28">

                        <h3 class="font-bold text-xl mb-6">

                            Informasi Proyek

                        </h3>

                        <div class="space-y-5 text-sm">

                            <div>

                                <p class="text-slate-500">

                                    Status

                                </p>

                                <span class="inline-block mt-2 px-3 py-1 rounded-full <?= badgeStatus($data['status']) ?>">

                                    <?= $data['status'] ?>

                                </span>

                            </div>

                            <div>

                                <p class="text-slate-500">

                                    Lokasi

                                </p>

                                <p class="font-semibold">

                                    <?= $data['location'] ?: '-' ?>

                                </p>

                            </div>

                            <div>

                                <p class="text-slate-500">

                                    Volume

                                </p>

                                <p class="font-semibold">

                                    <?= $data['volume'] ?: '-' ?>

                                </p>

                            </div>

                            <div>

                                <p class="text-slate-500">

                                    Anggaran

                                </p>

                                <p class="font-semibold">

                                    Rp <?= number_format($data['budget'], 0, ',', '.') ?>

                                </p>

                            </div>

                            <div>

                                <p class="text-slate-500">

                                    Sumber Dana

                                </p>

                                <p class="font-semibold">

                                    <?= $data['funding_source'] ?: '-' ?>

                                </p>

                            </div>

                            <div>

                                <p class="text-slate-500">

                                    Mulai

                                </p>

                                <p class="font-semibold">

                                    <?= $data['start_date'] ? date('d F Y', strtotime($data['start_date'])) : '-' ?>

                                </p>

                            </div>

                            <div>

                                <p class="text-slate-500">

                                    Selesai

                                </p>

                                <p class="font-semibold">

                                    <?= $data['end_date'] ? date('d F Y', strtotime($data['end_date'])) : '-' ?>

                                </p>

                            </div>

                            <div>

                                <div class="flex justify-between mb-2">

                                    <span>Progress</span>

                                    <span><?= $data['progress'] ?>%</span>

                                </div>

                                <div class="h-3 bg-slate-200 rounded-full overflow-hidden">

                                    <div

                                        class="h-full bg-teal-600"

                                        style="width:<?= min($data['progress'], 100) ?>%">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- PROYEK LAINNYA -->

    <section class="pb-20">

        <div class="max-w-7xl mx-auto px-6">

            <h2 class="text-3xl font-bold mb-8">

                Proyek Lainnya

            </h2>

            <div class="grid md:grid-cols-3 gap-8">

                <?php while ($row = mysqli_fetch_assoc($related)): ?>

                    <a

                        href="detail.php?slug=<?= $row['slug'] ?>"

                        class="bg-white rounded-3xl overflow-hidden shadow hover:-translate-y-1 hover:shadow-xl transition">

                        <?php if ($row['thumbnail']): ?>

                            <img

                                src="<?= APP_URL ?>uploads/informasi/pembangunan/<?= $row['thumbnail'] ?>"

                                class="h-52 w-full object-cover">

                        <?php endif; ?>

                        <div class="p-6">

                            <h3 class="font-bold line-clamp-2">

                                <?= htmlspecialchars($row['title']) ?>

                            </h3>

                            <p class="text-sm text-slate-500 mt-3">

                                <?= $row['year'] ?>

                            </p>

                            <div class="mt-4">

                                <div class="flex justify-between text-sm mb-2">

                                    <span>Progress</span>

                                    <span><?= $row['progress'] ?>%</span>

                                </div>

                                <div class="h-2 bg-slate-200 rounded-full overflow-hidden">

                                    <div

                                        class="h-full bg-teal-600"

                                        style="width:<?= min($row['progress'], 100) ?>%">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                <?php endwhile; ?>

            </div>

        </div>

    </section>

    <?php include "../../includes/guest/footer.php"; ?>


</body>

</html>