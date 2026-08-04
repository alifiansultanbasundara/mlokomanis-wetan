<?php

require_once "../../config/app.php";

$page = "bantuan-sosial";

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

    header("Location: bantuan-sosial.php");
    exit;
}

$slug = mysqli_real_escape_string(
    $conn,
    trim($_GET['slug'])
);


// ======================================
// Detail Bantuan Sosial
// ======================================

$query = mysqli_query($conn, "
    SELECT *
    FROM social_assistances
    WHERE
        slug = '$slug'
        AND status='Published'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {

    header("Location: bantuan-sosial.php");
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
    : "Informasi program bantuan sosial Desa {$village['village_name']}.";


// ======================================
// Statistik
// ======================================

$summary = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT

        COUNT(*) AS total_recipients,

        COALESCE(
            SUM(
                CASE
                    WHEN beneficiary_status='Aktif'
                    THEN 1
                    ELSE 0
                END
            ),
            0
        ) AS active_recipients,

        COALESCE(
            SUM(assistance_amount),
            0
        ) AS total_amount

    FROM social_assistance_recipients

    WHERE assistance_id={$data['id']}
"));


// ======================================
// Daftar Penerima
// ======================================

$recipients = mysqli_query($conn, "
    SELECT *
    FROM social_assistance_recipients
    WHERE assistance_id={$data['id']}
    ORDER BY name ASC
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

        <div class="max-w-7xl mx-auto px-6 py-20">

            <p class="text-teal-100">

                <a href="<?= APP_URL ?>beranda.php">Beranda</a>

                /

                <a href="index.php">Bantuan Sosial</a>

            </p>

            <h1 class="text-5xl font-bold mt-4">

                <?= htmlspecialchars($data['title']) ?>

            </h1>

            <p class="mt-5 text-teal-100 max-w-3xl">

                <?= htmlspecialchars($data['description']) ?>

            </p>

            <div class="flex flex-wrap gap-3 mt-8">

                <span class="px-4 py-2 rounded-full bg-white/20">

                    <?= $data['category'] ?>

                </span>

                <span class="px-4 py-2 rounded-full bg-white/20">

                    <?= $data['year'] ?>

                </span>

            </div>

        </div>

    </section>



    <!-- STATISTIK -->

    <section class="py-16">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid md:grid-cols-3 gap-6">

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Jumlah Penerima

                    </p>

                    <h2 class="text-4xl font-bold text-teal-600 mt-2">

                        <?= number_format($summary['total_recipients']) ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Penerima Aktif

                    </p>

                    <h2 class="text-4xl font-bold text-green-600 mt-2">

                        <?= number_format($summary['active_recipients']) ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Total Dana Tersalurkan

                    </p>

                    <h2 class="text-2xl font-bold text-teal-600 mt-2">

                        Rp <?= number_format($summary['total_amount'], 0, ',', '.') ?>

                    </h2>

                </div>

            </div>

        </div>

    </section>



    <!-- INFORMASI -->

    <section class="pb-12">

        <div class="max-w-7xl mx-auto px-6">

            <div class="bg-white rounded-3xl shadow p-8">

                <h2 class="text-2xl font-bold mb-6">

                    Informasi Program

                </h2>

                <div class="grid md:grid-cols-2 gap-6">

                    <div>

                        <p class="text-slate-500 text-sm">

                            Kategori

                        </p>

                        <p class="font-semibold">

                            <?= $data['category'] ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-slate-500 text-sm">

                            Tahun

                        </p>

                        <p class="font-semibold">

                            <?= $data['year'] ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-slate-500 text-sm">

                            Total Anggaran

                        </p>

                        <p class="font-semibold">

                            Rp <?= number_format($data['total_budget'], 0, ',', '.') ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-slate-500 text-sm">

                            Sumber Dana

                        </p>

                        <p class="font-semibold">

                            <?= $data['funding_source'] ?>

                        </p>

                    </div>

                </div>

                <?php if ($data['document_file']): ?>

                    <a

                        href="<?= APP_URL ?>uploads/informasi/bantuan-sosial/<?= $data['document_file'] ?>"

                        target="_blank"

                        class="inline-flex items-center gap-2 mt-8 rounded-xl bg-teal-600 px-5 py-3 text-white hover:bg-teal-700">

                        <i class="bi bi-download"></i>

                        Unduh Dokumen

                    </a>

                <?php endif; ?>

            </div>

        </div>

    </section>



    <!-- PENERIMA -->

    <section class="pb-20" x-data="{ search:'' }">

        <div class="max-w-7xl mx-auto px-6">

            <div class="bg-white rounded-3xl shadow">

                <div class="p-8 border-b">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <h2 class="text-2xl font-bold">

                            Daftar Penerima Bantuan

                        </h2>

                        <input

                            x-model="search"

                            type="text"

                            placeholder="Cari nama penerima..."

                            class="rounded-xl border border-slate-300 px-4 py-3 w-full md:w-80 focus:border-teal-500 focus:outline-none">

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead class="bg-slate-100">

                            <tr>

                                <th class="px-6 py-4 text-left">Nama</th>

                                <th class="px-6 py-4">Dusun</th>

                                <th class="px-6 py-4">RT/RW</th>

                                <th class="px-6 py-4">Nominal</th>

                                <th class="px-6 py-4">Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php while ($row = mysqli_fetch_assoc($recipients)): ?>

                                <tr

                                    x-show="'<?= strtolower(addslashes($row['name'])) ?>'.includes(search.toLowerCase())"

                                    class="border-t">

                                    <td class="px-6 py-4 font-medium">

                                        <?= htmlspecialchars($row['name']) ?>

                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        <?= $row['dusun'] ?: '-' ?>

                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        <?= ($row['rt'] ?: '-') ?>/<?= ($row['rw'] ?: '-') ?>

                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        Rp <?= number_format($row['assistance_amount'], 0, ',', '.') ?>

                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        <span class="px-3 py-1 rounded-full text-xs <?= $row['beneficiary_status'] == 'Aktif'
                                                                                        ? 'bg-green-100 text-green-700'
                                                                                        : 'bg-red-100 text-red-700' ?>">

                                            <?= $row['beneficiary_status'] ?>

                                        </span>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </section>

    <?php include "../../includes/guest/footer.php"; ?>

</body>

</html>