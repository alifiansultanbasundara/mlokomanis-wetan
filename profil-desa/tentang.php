<?php

require_once "../config/app.php";

$page = "tentang";

// ===============================
// Ambil Data Profil Desa
// ===============================

$query = mysqli_query($conn, "
    SELECT *
    FROM village_profiles
    LIMIT 1
");

$village = mysqli_fetch_assoc($query);

// ===============================
// Struktur Organisasi Pemerintah Desa
// ===============================

$officialsQuery = mysqli_query($conn, "
    SELECT *
    FROM village_officials
    WHERE status='Aktif'
    ORDER BY
        level ASC,
        sort_order ASC,
        id ASC
");

$officials = [];

while ($row = mysqli_fetch_assoc($officialsQuery)) {

    $officials[] = $row;
}

// ===============================
// Default jika data kosong
// ===============================

if (!$village) {

    $village = [
        'village_name'      => 'Nama Desa',
        'village_head'      => '-',
        'office_photo'      => null,
        'description'       => 'Belum ada deskripsi desa.',
        'history'           => '',
        'vision'            => '',
        'mission'           => '',
        'total_population'  => 0,
        'total_rt'          => 0,
        'total_rw'          => 0,
        'total_hamlets'     => 0,
        'total_areas'       => 0,
        'office_address'    => '-',
    ];
}

// ===============================
// Meta SEO
// ===============================

$title = "Tentang Desa {$village['village_name']}";
$metaTitle = "Tentang Desa | {$village['village_name']}";
$metaDescription = "Profil lengkap Desa {$village['village_name']}, meliputi sejarah, visi, misi, kondisi wilayah, serta informasi pemerintahan desa.";

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

    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs/dist/cdn.min.js"></script>

    <!-- Alpine Collapse -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse/dist/cdn.min.js"></script>

</head>

<body class="bg-slate-50 text-slate-800">

    <?php include "../includes/guest/navbar.php"; ?>


    <!-- ========================= -->
    <!-- HERO -->
    <!-- ========================= -->

    <section class="relative overflow-hidden bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 text-white pt-20">


        <!-- Decoration -->

        <div class="absolute inset-0 opacity-20">

            <div class="absolute -right-20 -top-20 h-96 w-96 rounded-full bg-white"></div>

            <div class="absolute -left-20 bottom-0 h-72 w-72 rounded-full bg-white"></div>

        </div>



        <div class="relative max-w-7xl mx-auto px-6 py-24">


            <div class="max-w-4xl">


                <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-5 py-2 text-sm font-semibold backdrop-blur">

                    <i class="bi bi-buildings-fill"></i>

                    Profil Desa

                </span>



                <h1 class="mt-6 text-4xl md:text-5xl font-black leading-tight">

                    Tentang Desa

                    <br>

                    <?= htmlspecialchars($village['village_name']); ?>

                </h1>



                <p class="mt-6 max-w-3xl text-lg leading-8 text-teal-100">


                    Mengenal lebih dekat profil

                    <?= htmlspecialchars($village['village_name']); ?>

                    mulai dari sejarah desa, visi dan misi,

                    kondisi wilayah, hingga informasi pemerintahan

                    sebagai bentuk transparansi kepada masyarakat.


                </p>



                <div class="mt-8 flex flex-wrap gap-4">


                    <a
                        href="#sejarah"
                        class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-semibold text-teal-700 hover:bg-teal-50">


                        <i class="bi bi-book-fill"></i>

                        Sejarah Desa


                    </a>



                    <a
                        href="#visi-misi"
                        class="inline-flex items-center gap-2 rounded-xl border border-white/40 px-6 py-3 font-semibold text-white hover:bg-white/10">


                        <i class="bi bi-bullseye"></i>

                        Visi & Misi


                    </a>


                </div>


            </div>


        </div>


    </section>



    <!-- ========================= -->
    <!-- Tentang -->
    <!-- ========================= -->

    <section class="py-20">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <div>

                    <?php if (!empty($village['office_photo'])): ?>

                        <img

                            src="<?= APP_URL ?>uploads/village/<?= $village['office_photo'] ?>"

                            class="rounded-3xl shadow-lg w-full h-[420px] object-cover">

                    <?php else: ?>

                        <div class="rounded-3xl bg-slate-200 h-[420px] flex items-center justify-center">

                            Belum ada foto

                        </div>

                    <?php endif; ?>

                </div>

                <div>

                    <span class="text-teal-600 font-semibold">

                        Tentang Desa

                    </span>

                    <h2 class="text-4xl font-bold mt-3">

                        <?= htmlspecialchars($village['village_name']) ?>

                    </h2>

                    <div class="mt-6 prose max-w-none text-slate-600">

                        <?= nl2br(htmlspecialchars($village['description'])) ?>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- ========================= -->
    <!-- Sejarah -->
    <!-- ========================= -->

    <section class="bg-white py-20">

        <div class="max-w-5xl mx-auto px-6">

            <div class="text-center">

                <h2 class="text-4xl font-bold">

                    Sejarah Desa

                </h2>

                <div class="w-24 h-1 bg-teal-600 rounded mx-auto mt-4"></div>

            </div>

            <div class="mt-10 text-lg leading-9 text-slate-600">

                <?= nl2br(htmlspecialchars($village['history'])) ?>

            </div>

        </div>

    </section>



    <!-- ========================= -->
    <!-- Visi Misi -->
    <!-- ========================= -->

    <section class="py-20">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid lg:grid-cols-2 gap-8">

                <div class="bg-white rounded-3xl p-10 shadow">

                    <div class="flex items-center gap-3">

                        <i class="bi bi-bullseye text-4xl text-teal-600"></i>

                        <h2 class="text-3xl font-bold">

                            Visi

                        </h2>

                    </div>

                    <div class="mt-8 text-slate-600 leading-8">

                        <?= nl2br(htmlspecialchars($village['vision'])) ?>

                    </div>

                </div>

                <div class="bg-white rounded-3xl p-10 shadow">

                    <div class="flex items-center gap-3">

                        <i class="bi bi-list-check text-4xl text-teal-600"></i>

                        <h2 class="text-3xl font-bold">

                            Misi

                        </h2>

                    </div>

                    <div class="mt-8 text-slate-600 leading-8">

                        <?= nl2br(htmlspecialchars($village['mission'])) ?>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- ========================= -->
    <!-- Statistik -->
    <!-- ========================= -->

    <section class="bg-teal-700 text-white py-20">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">

                <div class="text-center">
                    <h3 class="text-5xl font-bold"><?= number_format($village['total_population']) ?></h3>
                    <p class="mt-3">Penduduk</p>
                </div>

                <div class="text-center">
                    <h3 class="text-5xl font-bold"><?= $village['total_hamlets'] ?></h3>
                    <p class="mt-3">Dusun</p>
                </div>

                <div class="text-center">
                    <h3 class="text-5xl font-bold"><?= $village['total_rw'] ?></h3>
                    <p class="mt-3">RW</p>
                </div>

                <div class="text-center">
                    <h3 class="text-5xl font-bold"><?= $village['total_rt'] ?></h3>
                    <p class="mt-3">RT</p>
                </div>

                <div class="text-center">
                    <h3 class="text-5xl font-bold"><?= $village['total_areas'] ?></h3>
                    <p class="mt-3">Wilayah</p>
                </div>

            </div>

        </div>

    </section>



    <!-- ========================= -->
    <!-- Batas Wilayah -->
    <!-- ========================= -->

    <section class="bg-white py-20">

        <div class="max-w-7xl mx-auto px-6">

            <h2 class="text-4xl font-bold text-center">

                Batas Wilayah

            </h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mt-12">

                <div class="rounded-2xl bg-slate-50 p-6">

                    <h3 class="font-bold text-teal-700">

                        Utara

                    </h3>

                    <p class="mt-3">

                        <?= htmlspecialchars($village['north_boundary']) ?>

                    </p>

                </div>

                <div class="rounded-2xl bg-slate-50 p-6">

                    <h3 class="font-bold text-teal-700">

                        Timur

                    </h3>

                    <p class="mt-3">

                        <?= htmlspecialchars($village['east_boundary']) ?>

                    </p>

                </div>

                <div class="rounded-2xl bg-slate-50 p-6">

                    <h3 class="font-bold text-teal-700">

                        Selatan

                    </h3>

                    <p class="mt-3">

                        <?= htmlspecialchars($village['south_boundary']) ?>

                    </p>

                </div>

                <div class="rounded-2xl bg-slate-50 p-6">

                    <h3 class="font-bold text-teal-700">

                        Barat

                    </h3>

                    <p class="mt-3">

                        <?= htmlspecialchars($village['west_boundary']) ?>

                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- ==========================================
 LOKASI & KONTAK DESA
=========================================== -->
    <section class="bg-slate-50 py-24">

        <div class="max-w-7xl mx-auto px-6">

            <!-- Heading -->

            <div class="max-w-3xl mx-auto text-center">

                <span class="inline-flex items-center gap-2 rounded-full bg-teal-100 px-4 py-2 text-sm font-semibold text-teal-700">

                    <i class="bi bi-geo-alt-fill"></i>

                    Lokasi & Kontak

                </span>

                <h2 class="mt-5 text-4xl font-bold text-slate-900">

                    Kantor Pemerintah Desa
                </h2>

                <p class="mt-4 text-lg leading-8 text-slate-500">

                    Kunjungi kantor kami atau hubungi melalui kontak yang tersedia.
                    Kami siap memberikan pelayanan terbaik kepada masyarakat.

                </p>

            </div>

            <!-- Content -->

            <div class="mt-16 grid gap-8 lg:grid-cols-5">

                <!-- ================= LEFT ================= -->

                <div class="lg:col-span-2">

                    <div class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-100 h-full">

                        <div class="flex items-center gap-4">

                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-teal-100">

                                <i class="bi bi-building-fill text-3xl text-teal-700"></i>

                            </div>

                            <div>

                                <h3 class="text-2xl font-bold text-slate-900">

                                    <?= htmlspecialchars($village['village_name']); ?>

                                </h3>

                                <p class="text-slate-500">

                                    Kantor Pemerintah Desa

                                </p>

                            </div>

                        </div>

                        <!-- Informasi -->

                        <div class="mt-10 space-y-6">

                            <!-- Alamat -->

                            <div class="flex items-start gap-4">

                                <div class="mt-1 text-teal-600">

                                    <i class="bi bi-geo-alt-fill"></i>

                                </div>

                                <div>

                                    <p class="text-sm text-slate-500">

                                        Alamat

                                    </p>

                                    <p class="mt-1 font-medium leading-7 text-slate-800">

                                        <?= nl2br(htmlspecialchars($village['office_address'])); ?>

                                    </p>

                                </div>

                            </div>

                            <!-- Telepon -->

                            <?php if (!empty($village['phone'])): ?>

                                <div class="flex items-start gap-4">

                                    <div class="mt-1 text-teal-600">

                                        <i class="bi bi-telephone-fill"></i>

                                    </div>

                                    <div>

                                        <p class="text-sm text-slate-500">

                                            Telepon

                                        </p>

                                        <a
                                            href="tel:<?= $village['phone']; ?>"
                                            class="mt-1 font-medium text-slate-800 hover:text-teal-700">

                                            <?= $village['phone']; ?>

                                        </a>

                                    </div>

                                </div>

                            <?php endif; ?>

                            <!-- Email -->

                            <?php if (!empty($village['email'])): ?>

                                <div class="flex items-start gap-4">

                                    <div class="mt-1 text-teal-600">

                                        <i class="bi bi-envelope-fill"></i>

                                    </div>

                                    <div>

                                        <p class="text-sm text-slate-500">

                                            Email

                                        </p>

                                        <a
                                            href="mailto:<?= $village['email']; ?>"
                                            class="mt-1 font-medium text-slate-800 hover:text-teal-700">

                                            <?= $village['email']; ?>

                                        </a>

                                    </div>

                                </div>

                            <?php endif; ?>

                            <!-- Jam Pelayanan -->

                            <div class="flex items-start gap-4">

                                <div class="mt-1 text-teal-600">

                                    <i class="bi bi-clock-fill"></i>

                                </div>


                                <div class="flex-1">

                                    <p class="text-sm text-slate-500 mb-2">

                                        Jam Pelayanan

                                    </p>


                                    <?php if (!empty($village['office_hours'])): ?>

                                        <div class="space-y-2">


                                            <?php

                                            $hours = explode("\n", $village['office_hours']);


                                            foreach ($hours as $hour):

                                                if (trim($hour) == '') continue;

                                            ?>


                                                <div class="flex items-center gap-2">


                                                    <i class="bi bi-check-circle-fill text-teal-600 text-sm"></i>


                                                    <p class="font-medium text-slate-800">

                                                        <?= htmlspecialchars(trim($hour)); ?>

                                                    </p>


                                                </div>


                                            <?php endforeach; ?>


                                        </div>


                                    <?php else: ?>


                                        <p class="text-slate-400">

                                            Jam pelayanan belum tersedia.

                                        </p>


                                    <?php endif; ?>


                                </div>


                            </div>
                        </div>


                    </div>

                </div>

                <!-- ================= MAP ================= -->
                <div class="lg:col-span-3">

                    <div class="overflow-hidden rounded-[2rem] bg-white shadow-lg ring-1 ring-slate-100 h-full">


                        <?php if (!empty($village['google_maps'])): ?>


                            <div class="h-[450px] w-full">


                                <?= str_replace(
                                    [
                                        '<iframe',
                                        'width="600"',
                                        'height="450"',
                                        'style="border:0;"'
                                    ],
                                    [
                                        '<iframe class="h-full w-full border-0"',
                                        '',
                                        '',
                                        ''
                                    ],
                                    $village['google_maps']
                                ); ?>


                            </div>



                        <?php else: ?>


                            <div class="flex h-[450px] items-center justify-center bg-slate-100">


                                <div class="text-center">


                                    <i class="bi bi-map text-7xl text-slate-400"></i>


                                    <p class="mt-4 text-slate-500">

                                        Google Maps belum tersedia.

                                    </p>


                                </div>


                            </div>



                        <?php endif; ?>


                    </div>


                </div>

            </div>

        </div>

    </section>

    <?php include "../includes/guest/footer.php"; ?>

</body>

</html>