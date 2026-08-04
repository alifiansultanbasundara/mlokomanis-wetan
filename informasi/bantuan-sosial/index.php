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
// Meta
// ======================================

$title = "Bantuan Sosial Desa {$village['village_name']}";
$metaTitle = "Bantuan Sosial | {$village['village_name']}";
$metaDescription = "Informasi program bantuan sosial, penerima bantuan, serta transparansi penyaluran bantuan sosial di Desa {$village['village_name']}.";


// ======================================
// Statistik
// ======================================

$summary = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(*) AS total_programs,
        COALESCE(SUM(total_budget),0) AS total_budget
    FROM social_assistances
    WHERE status='Published'
"));

$recipient = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(*) AS total_recipients
    FROM social_assistance_recipients
"));


// ======================================
// Data Bantuan Sosial
// ======================================

$query = mysqli_query($conn, "
    SELECT
        sa.*,

        (
            SELECT COUNT(*)
            FROM social_assistance_recipients r
            WHERE r.assistance_id = sa.id
        ) AS total_recipients

    FROM social_assistances sa

    WHERE sa.status='Published'

    ORDER BY
        sa.year DESC,
        sa.created_at DESC
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

            <h1 class="text-5xl font-bold">

                Bantuan Sosial Desa

            </h1>

            <p class="mt-5 text-teal-100 max-w-3xl">

                Informasi berbagai program bantuan sosial
                yang disalurkan kepada masyarakat desa
                beserta jumlah penerima dan sumber pendanaannya.

            </p>

        </div>

    </section>



    <!-- STATISTIK -->

    <section class="py-16">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid md:grid-cols-3 gap-6">

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Program Bantuan

                    </p>

                    <h2 class="text-4xl font-bold text-teal-600 mt-2">

                        <?= number_format($summary['total_programs']) ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Total Penerima

                    </p>

                    <h2 class="text-4xl font-bold text-teal-600 mt-2">

                        <?= number_format($recipient['total_recipients']) ?>

                    </h2>

                </div>

                <div class="bg-white rounded-3xl shadow p-8">

                    <p class="text-slate-500">

                        Total Anggaran

                    </p>

                    <h2 class="text-2xl font-bold text-teal-600 mt-2">

                        Rp <?= number_format($summary['total_budget'], 0, ',', '.') ?>

                    </h2>

                </div>

            </div>

        </div>

    </section>



    <!-- PROGRAM -->

    <section class="pb-20">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid lg:grid-cols-2 gap-8">

                <?php if (mysqli_num_rows($query)): ?>

                    <?php while ($row = mysqli_fetch_assoc($query)): ?>

                        <div class="bg-white rounded-3xl shadow p-8">

                            <div class="flex justify-between items-start gap-5">

                                <div>

                                    <span class="inline-block px-3 py-1 rounded-full bg-teal-100 text-teal-700 text-sm">

                                        <?= $row['category'] ?>

                                    </span>

                                    <h2 class="text-2xl font-bold mt-4">

                                        <?= htmlspecialchars($row['title']) ?>

                                    </h2>

                                </div>

                                <span class="bg-slate-100 rounded-full px-4 py-2">

                                    <?= $row['year'] ?>

                                </span>

                            </div>

                            <?php if ($row['description']): ?>

                                <p class="mt-5 text-slate-600">

                                    <?= htmlspecialchars($row['description']) ?>

                                </p>

                            <?php endif; ?>

                            <div class="grid grid-cols-2 gap-5 mt-8">

                                <div>

                                    <p class="text-slate-500 text-sm">

                                        Penerima

                                    </p>

                                    <p class="font-bold text-lg">

                                        <?= number_format($row['total_recipients']) ?> Orang

                                    </p>

                                </div>

                                <div>

                                    <p class="text-slate-500 text-sm">

                                        Sumber Dana

                                    </p>

                                    <p class="font-semibold">

                                        <?= $row['funding_source'] ?>

                                    </p>

                                </div>

                                <div>

                                    <p class="text-slate-500 text-sm">

                                        Total Anggaran

                                    </p>

                                    <p class="font-bold">

                                        Rp <?= number_format($row['total_budget'], 0, ',', '.') ?>

                                    </p>

                                </div>

                            </div>

                            <div class="mt-8 flex gap-3">

                                <a

                                    href="detail.php?slug=<?= $row['slug'] ?>"

                                    class="rounded-xl bg-teal-600 px-5 py-3 text-white font-semibold hover:bg-teal-700 transition">

                                    <i class="bi bi-eye"></i>

                                    Detail

                                </a>

                                <?php if ($row['document_file']): ?>

                                    <a

                                        href="<?= APP_URL ?>uploads/informasi/bantuan-sosial/<?= $row['document_file'] ?>"

                                        target="_blank"

                                        class="rounded-xl border border-slate-300 px-5 py-3 hover:bg-slate-100">

                                        <i class="bi bi-download"></i>

                                        Dokumen

                                    </a>

                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <div class="lg:col-span-2 bg-white rounded-3xl shadow p-20 text-center">

                        <i class="bi bi-inbox text-6xl text-slate-300"></i>

                        <h2 class="text-2xl font-bold mt-6">

                            Belum Ada Program Bantuan

                        </h2>

                        <p class="text-slate-500 mt-3">

                            Program bantuan sosial belum dipublikasikan.

                        </p>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </section>

    <?php include "../../includes/guest/footer.php"; ?>

</body>

</html>