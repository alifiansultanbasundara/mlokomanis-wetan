<?php

require_once "../config/app.php";

$page = "tracking";

// ======================================
// Inisialisasi
// ======================================

$tracking = null;
$notFound = false;
$kode = '';


// ======================================
// Warna Status
// ======================================

$statusColor = [
    'Menunggu Verifikasi' => 'bg-yellow-100 text-yellow-700',
    'Diproses'            => 'bg-blue-100 text-blue-700',
    'Menunggu TTD'        => 'bg-purple-100 text-purple-700',
    'Selesai'             => 'bg-green-100 text-green-700',
    'Ditolak'             => 'bg-red-100 text-red-700'
];


// ======================================
// Step Progress
// ======================================

$statusStep = [
    'Menunggu Verifikasi' => 1,
    'Diproses'            => 2,
    'Menunggu TTD'        => 3,
    'Selesai'             => 4,
    'Ditolak'             => 4
];


// ======================================
// Cari Tracking
// ======================================

if (!empty($_GET['kode'])) {

    $kode = mysqli_real_escape_string(
        $conn,
        trim($_GET['kode'])
    );

    $query = mysqli_query($conn, "
        SELECT
            lt.*,
            sl.name AS service_name
        FROM letter_trackings lt
        INNER JOIN service_letters sl
            ON sl.id = lt.service_id
        WHERE lt.tracking_code = '{$kode}'
        LIMIT 1
    ");

    if (mysqli_num_rows($query) > 0) {

        $tracking = mysqli_fetch_assoc($query);
    } else {

        $notFound = true;
    }
}

// ======================================
// Ambil Semua Tracking Terbaru
// ======================================

$listQuery = mysqli_query($conn, "

    SELECT
        lt.*,
        sl.name AS service_name

    FROM letter_trackings lt

    INNER JOIN service_letters sl
        ON sl.id = lt.service_id

    ORDER BY lt.created_at DESC

    LIMIT 10

");


$trackings = [];


while ($row = mysqli_fetch_assoc($listQuery)) {

    $trackings[] = $row;
}

// ======================================
// Meta
// ======================================

$title = "Tracking Pengajuan Surat";

$metaTitle = "Tracking Pengajuan Surat";

$metaDescription = "Lacak status pengajuan surat secara online melalui kode tracking yang telah diberikan.";

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

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs/dist/cdn.min.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse/dist/cdn.min.js"></script>

</head>

<body class="bg-slate-50 text-slate-800">

    <?php include "../includes/guest/navbar.php"; ?>

    <!-- ================================================= -->
    <!-- HERO -->
    <!-- ================================================= -->

    <section class="relative overflow-hidden bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 pt-20 text-white">


        <!-- Decoration -->

        <div class="absolute inset-0 opacity-20">

            <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-white"></div>

            <div class="absolute -left-20 bottom-0 h-72 w-72 rounded-full bg-white"></div>

        </div>



        <div class="relative max-w-7xl mx-auto px-6 py-24 text-center">


            <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-5 py-2 text-sm font-semibold backdrop-blur">


                <i class="bi bi-search-heart-fill"></i>


                Layanan Online Desa


            </span>





            <h1 class="mt-6 text-4xl md:text-5xl font-black leading-tight">


                Tracking Pengajuan Surat


                <br>


                <span class="text-teal-100">


                    <?= htmlspecialchars($profile['village_name'] ?? ''); ?>


                </span>


            </h1>





            <p class="mx-auto mt-6 max-w-3xl text-lg leading-8 text-teal-100">


                Lacak proses pengajuan surat Anda secara mudah dan transparan.
                Masukkan kode tracking yang diberikan setelah melakukan pengajuan
                layanan administrasi desa.


            </p>



        </div>


    </section>

    <!-- ================================================= -->
    <!-- FORM TRACKING -->
    <!-- ================================================= -->


    <section class="relative -mt-12 pb-16">


        <div class="max-w-3xl mx-auto px-6">


            <div class="rounded-[2rem] bg-white p-8 shadow-xl ring-1 ring-slate-100">


                <div class="mb-6 text-center">


                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-100">


                        <i class="bi bi-upc-scan text-3xl text-teal-700"></i>


                    </div>



                    <h2 class="mt-4 text-xl font-bold text-slate-900">


                        Masukkan Kode Tracking


                    </h2>



                    <p class="mt-2 text-sm text-slate-500">


                        Contoh kode: TRK240001


                    </p>


                </div>





                <form
                    method="GET"
                    class="flex flex-col gap-4 md:flex-row">



                    <div class="relative flex-1">


                        <i class="bi bi-ticket-perforated absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>


                        <input

                            type="text"

                            name="kode"

                            value="<?= htmlspecialchars($kode) ?>"

                            placeholder="Masukkan kode tracking"

                            class="w-full rounded-xl border border-slate-300 py-4 pl-12 pr-5 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">


                    </div>





                    <button

                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-teal-600 px-8 py-4 font-semibold text-white transition hover:bg-teal-700">


                        <i class="bi bi-search"></i>


                        Cari Status


                    </button>




                </form>



            </div>



        </div>


    </section>



    <?php if ($tracking): ?>

        <section class="pb-20">

            <div class="max-w-5xl mx-auto px-6">

                <!-- ================================================= -->
                <!-- RINGKASAN PENGAJUAN -->
                <!-- ================================================= -->

                <div class="grid gap-8 lg:grid-cols-2">


                    <!-- ================= INFORMASI PENGAJUAN ================= -->


                    <div class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-100">


                        <div class="flex items-center gap-4 mb-8">


                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-100">


                                <i class="bi bi-file-earmark-text-fill text-3xl text-teal-700"></i>


                            </div>


                            <div>


                                <h3 class="text-xl font-bold text-slate-900">

                                    Informasi Pengajuan

                                </h3>


                                <p class="text-sm text-slate-500">

                                    Detail permohonan surat

                                </p>


                            </div>


                        </div>





                        <div class="space-y-6">


                            <!-- Kode -->


                            <div class="flex items-start gap-4">


                                <div class="mt-1 text-teal-600">

                                    <i class="bi bi-upc-scan"></i>

                                </div>


                                <div>


                                    <p class="text-sm text-slate-500">

                                        Kode Tracking

                                    </p>


                                    <p class="mt-1 font-bold text-slate-900">

                                        <?= htmlspecialchars($tracking['tracking_code']); ?>

                                    </p>


                                </div>


                            </div>






                            <!-- Pemohon -->


                            <div class="flex items-start gap-4">


                                <div class="mt-1 text-teal-600">

                                    <i class="bi bi-person-fill"></i>

                                </div>


                                <div>


                                    <p class="text-sm text-slate-500">

                                        Nama Pemohon

                                    </p>


                                    <p class="mt-1 font-semibold text-slate-900">

                                        <?= htmlspecialchars($tracking['applicant_name']); ?>

                                    </p>


                                </div>


                            </div>






                            <!-- Surat -->


                            <div class="flex items-start gap-4">


                                <div class="mt-1 text-teal-600">

                                    <i class="bi bi-file-earmark-check-fill"></i>

                                </div>


                                <div>


                                    <p class="text-sm text-slate-500">

                                        Jenis Surat

                                    </p>


                                    <p class="mt-1 font-semibold text-slate-900">

                                        <?= htmlspecialchars($tracking['service_name']); ?>

                                    </p>


                                </div>


                            </div>






                            <!-- Tanggal -->


                            <div class="flex items-start gap-4">


                                <div class="mt-1 text-teal-600">

                                    <i class="bi bi-calendar-event-fill"></i>

                                </div>


                                <div>


                                    <p class="text-sm text-slate-500">

                                        Tanggal Pengajuan

                                    </p>


                                    <p class="mt-1 font-semibold text-slate-900">

                                        <?= date(
                                            'd F Y H:i',
                                            strtotime($tracking['submitted_at'])
                                        ); ?>

                                    </p>


                                </div>


                            </div>



                        </div>


                    </div>






                    <!-- ================= STATUS ================= -->


                    <div class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-100">


                        <div class="flex items-center gap-4 mb-8">


                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100">


                                <i class="bi bi-hourglass-split text-3xl text-emerald-700"></i>


                            </div>


                            <div>


                                <h3 class="text-xl font-bold text-slate-900">

                                    Status Pengajuan

                                </h3>


                                <p class="text-sm text-slate-500">

                                    Proses pelayanan surat

                                </p>


                            </div>


                        </div>







                        <!-- Status Badge -->


                        <div class="rounded-2xl bg-slate-50 p-6 text-center">


                            <p class="text-sm text-slate-500 mb-3">

                                Status Saat Ini

                            </p>



                            <span
                                class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-bold <?= $statusColor[$tracking['status']] ?>">


                                <?php if ($tracking['status'] == 'Selesai'): ?>

                                    <i class="bi bi-check-circle-fill"></i>

                                <?php elseif ($tracking['status'] == 'Ditolak'): ?>

                                    <i class="bi bi-x-circle-fill"></i>

                                <?php else: ?>

                                    <i class="bi bi-clock-fill"></i>

                                <?php endif; ?>


                                <?= htmlspecialchars($tracking['status']); ?>


                            </span>


                        </div>







                        <?php if (!empty($tracking['completed_at'])): ?>


                            <div class="mt-6 flex items-center gap-4 rounded-2xl bg-green-50 p-5">


                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100">


                                    <i class="bi bi-calendar-check text-xl text-green-700"></i>


                                </div>


                                <div>


                                    <p class="text-sm text-green-700">

                                        Selesai Pada

                                    </p>


                                    <p class="font-bold text-green-800">


                                        <?= date(
                                            'd F Y H:i',
                                            strtotime($tracking['completed_at'])
                                        ); ?>


                                    </p>


                                </div>


                            </div>


                        <?php endif; ?>



                    </div>



                </div>
            </div>

        </section>

    <?php elseif ($notFound): ?>

        <section class="pb-20">

            <div class="max-w-3xl mx-auto px-6">

                <div class="bg-white rounded-3xl shadow p-16 text-center">

                    <i class="bi bi-search text-6xl text-slate-300"></i>

                    <h2 class="mt-6 text-3xl font-bold">

                        Data Tidak Ditemukan

                    </h2>

                    <p class="mt-4 text-slate-500">

                        Pastikan kode tracking yang Anda masukkan sudah benar.

                    </p>

                </div>

            </div>

        </section>

    <?php endif; ?>


    <!-- ================================================= -->
    <!-- DAFTAR PENGAJUAN TERBARU -->
    <!-- ================================================= -->

    <section class="pb-20">

        <div class="max-w-7xl mx-auto px-6">


            <div class="rounded-[2rem] bg-white shadow-xl ring-1 ring-slate-100 overflow-hidden">


                <!-- Header -->

                <div class="flex items-center gap-4 border-b p-8">


                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-100">

                        <i class="bi bi-table text-3xl text-teal-700"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-slate-900">

                            Riwayat Pengajuan Surat

                        </h2>


                        <p class="text-slate-500">

                            Daftar layanan surat yang sedang diproses

                        </p>

                    </div>


                </div>





                <div class="overflow-x-auto">


                    <table class="w-full text-left">


                        <thead class="bg-slate-50">


                            <tr>


                                <th class="px-6 py-4 text-sm font-semibold">
                                    No
                                </th>


                                <th class="px-6 py-4 text-sm font-semibold">
                                    Kode
                                </th>


                                <th class="px-6 py-4 text-sm font-semibold">
                                    Pemohon
                                </th>


                                <th class="px-6 py-4 text-sm font-semibold">
                                    Jenis Surat
                                </th>


                                <th class="px-6 py-4 text-sm font-semibold">
                                    Tanggal
                                </th>


                                <th class="px-6 py-4 text-sm font-semibold">
                                    Status
                                </th>


                                <th class="px-6 py-4 text-sm font-semibold">
                                    Aksi
                                </th>


                            </tr>


                        </thead>



                        <tbody class="divide-y">


                            <?php if (!empty($trackings)): ?>


                                <?php foreach ($trackings as $i => $item): ?>


                                    <tr class="hover:bg-slate-50">


                                        <td class="px-6 py-5">

                                            <?= $i + 1; ?>

                                        </td>



                                        <td class="px-6 py-5 font-semibold text-teal-700">

                                            <?= htmlspecialchars($item['tracking_code']); ?>

                                        </td>




                                        <td class="px-6 py-5">

                                            <?= htmlspecialchars($item['applicant_name']); ?>

                                        </td>




                                        <td class="px-6 py-5">

                                            <?= htmlspecialchars($item['service_name']); ?>

                                        </td>




                                        <td class="px-6 py-5 text-sm text-slate-500">


                                            <?= date(
                                                'd M Y',
                                                strtotime($item['submitted_at'])
                                            ); ?>


                                        </td>




                                        <td class="px-6 py-5">


                                            <span
                                                class="rounded-full px-4 py-2 text-xs font-bold <?= $statusColor[$item['status']] ?>">


                                                <?= htmlspecialchars($item['status']); ?>


                                            </span>


                                        </td>




                                        <td class="px-6 py-5">


                                            <a
                                                href="?kode=<?= urlencode($item['tracking_code']); ?>"
                                                class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700">


                                                <i class="bi bi-eye"></i>

                                                Detail


                                            </a>


                                        </td>



                                    </tr>



                                <?php endforeach; ?>


                            <?php else: ?>


                                <tr>

                                    <td colspan="7"
                                        class="px-6 py-10 text-center text-slate-500">


                                        Belum ada pengajuan surat.


                                    </td>

                                </tr>


                            <?php endif; ?>



                        </tbody>



                    </table>



                </div>


            </div>


        </div>


    </section>

    <?php include "../includes/guest/footer.php"; ?>

</body>

</html>