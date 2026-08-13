<?php

require_once '../../../config/app.php';

$currentPage = 'generate';

// ======================================================
// AMBIL JENIS SURAT
// ======================================================

$letterTypes = mysqli_query(
    $conn,
    "SELECT *
     FROM letter_types
     WHERE status = 'Aktif'
     ORDER BY sort_order ASC, name ASC"
);

// ======================================================
// DATA DEFAULT
// ======================================================

$selectedLetter = null;
$population = null;

$letterId = isset($_GET['letter_id'])
    ? (int) $_GET['letter_id']
    : 0;

$nik = trim($_GET['nik'] ?? '');

// ======================================================
// JIKA SUDAH MEMILIH JENIS SURAT
// ======================================================

if ($letterId > 0) {

    $query = mysqli_query(
        $conn,
        "SELECT *
         FROM letter_types
         WHERE id = $letterId
         AND status = 'Aktif'
         LIMIT 1"
    );

    if ($query && mysqli_num_rows($query) > 0) {
        $selectedLetter = mysqli_fetch_assoc($query);
    }
}

// ======================================================
// JIKA NIK DIISI
// ======================================================

if ($nik !== '') {

    $nikSafe = mysqli_real_escape_string($conn, $nik);

    $query = mysqli_query(
        $conn,
        "SELECT *
         FROM populations
         WHERE nik = '$nikSafe'
         LIMIT 1"
    );

    if ($query && mysqli_num_rows($query) > 0) {
        $population = mysqli_fetch_assoc($query);
    }
}

include APP_PATH . "includes/admin/layout-top.php";
?>

<body class="bg-slate-50 text-slate-800">

    <div class="min-h-screen">

        <!-- ==================================================
         HEADER
    ================================================== -->

        <div class="bg-teal-700 text-white">

            <div class="max-w-7xl mx-auto px-6 py-6">

                <div class="flex items-center gap-4">

                    <a
                        href="<?= APP_URL ?>admin/layanan/"
                        class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center">

                        <i class="bi bi-arrow-left"></i>

                    </a>

                    <div>

                        <h1 class="text-xl font-bold">
                            Generate Surat
                        </h1>

                        <p class="text-sm text-teal-100">
                            Buat surat berdasarkan data penduduk
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <!-- ==================================================
         CONTENT
    ================================================== -->

        <main class="max-w-7xl mx-auto px-6 py-8">

            <!-- ==================================================
             STEPPER
        ================================================== -->

            <div class="mb-8">

                <div class="flex items-center">

                    <!-- STEP 1 -->

                    <div class="flex items-center gap-3">

                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center font-bold
                        <?= !$selectedLetter
                            ? 'bg-teal-600 text-white'
                            : 'bg-teal-100 text-teal-700' ?>">

                            1

                        </div>

                        <div class="hidden sm:block">

                            <p class="text-sm font-semibold">
                                Jenis Surat
                            </p>

                            <p class="text-xs text-slate-500">
                                Pilih surat
                            </p>

                        </div>

                    </div>


                    <div class="flex-1 h-px bg-slate-200 mx-4"></div>


                    <!-- STEP 2 -->

                    <div class="flex items-center gap-3">

                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center font-bold
                        <?= $selectedLetter
                            ? 'bg-teal-600 text-white'
                            : 'bg-slate-200 text-slate-500' ?>">

                            2

                        </div>

                        <div class="hidden sm:block">

                            <p class="text-sm font-semibold">
                                Penduduk
                            </p>

                            <p class="text-xs text-slate-500">
                                Masukkan NIK
                            </p>

                        </div>

                    </div>


                    <div class="flex-1 h-px bg-slate-200 mx-4"></div>


                    <!-- STEP 3 -->

                    <div class="flex items-center gap-3">

                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center font-bold
                        <?= $population
                            ? 'bg-teal-600 text-white'
                            : 'bg-slate-200 text-slate-500' ?>">

                            3

                        </div>

                        <div class="hidden sm:block">

                            <p class="text-sm font-semibold">
                                Konfirmasi
                            </p>

                            <p class="text-xs text-slate-500">
                                Generate surat
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ==================================================
             STEP 1
        ================================================== -->

            <?php if (!$selectedLetter): ?>

                <div class="mb-6">

                    <h2 class="text-xl font-bold text-slate-900">
                        Pilih Jenis Surat
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Pilih jenis surat yang ingin dibuat.
                    </p>

                </div>


                <?php if ($letterTypes && mysqli_num_rows($letterTypes) > 0): ?>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">

                        <?php while ($letter = mysqli_fetch_assoc($letterTypes)): ?>

                            <a
                                href="?letter_id=<?= $letter['id'] ?>"
                                class="group bg-white border border-slate-200 rounded-2xl p-5 hover:border-teal-400 hover:shadow-lg transition">

                                <div class="flex items-start justify-between">

                                    <div
                                        class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">

                                        <i class="bi <?= e($letter['icon'] ?: 'bi-file-earmark-text') ?>"></i>

                                    </div>

                                    <i
                                        class="bi bi-arrow-up-right text-slate-300 group-hover:text-teal-600 transition">
                                    </i>

                                </div>


                                <h3 class="font-semibold text-slate-900 mt-5">

                                    <?= e($letter['name']) ?>

                                </h3>


                                <?php if (!empty($letter['description'])): ?>

                                    <p class="text-sm text-slate-500 mt-2 line-clamp-2">

                                        <?= e($letter['description']) ?>

                                    </p>

                                <?php endif; ?>

                            </a>

                        <?php endwhile; ?>

                    </div>

                <?php else: ?>

                    <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center">

                        <i class="bi bi-file-earmark-x text-4xl text-slate-300"></i>

                        <p class="font-semibold mt-4">
                            Belum ada jenis surat
                        </p>

                        <p class="text-sm text-slate-500 mt-1">
                            Silakan tambahkan jenis surat terlebih dahulu.
                        </p>

                    </div>

                <?php endif; ?>


                <!-- ==================================================
             STEP 2
        ================================================== -->

            <?php else: ?>

                <div class="grid lg:grid-cols-3 gap-6">

                    <!-- INFORMASI SURAT -->

                    <div>

                        <div class="bg-white border border-slate-200 rounded-2xl p-5">

                            <p class="text-xs font-semibold text-teal-600 uppercase">
                                Jenis Surat
                            </p>

                            <div class="flex items-center gap-3 mt-4">

                                <div
                                    class="w-11 h-11 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">

                                    <i class="bi <?= e($selectedLetter['icon'] ?: 'bi-file-earmark-text') ?>"></i>

                                </div>

                                <div>

                                    <h2 class="font-bold">
                                        <?= e($selectedLetter['name']) ?>
                                    </h2>

                                    <p class="text-xs text-slate-500">
                                        Surat yang akan dibuat
                                    </p>

                                </div>

                            </div>


                            <a
                                href="index.php"
                                class="inline-flex items-center gap-2 text-sm text-teal-600 hover:text-teal-700 mt-5">

                                <i class="bi bi-arrow-left"></i>

                                Ganti jenis surat

                            </a>

                        </div>

                    </div>


                    <!-- FORM NIK -->

                    <div class="lg:col-span-2">

                        <div class="bg-white border border-slate-200 rounded-2xl p-6">

                            <div class="mb-6">

                                <h2 class="text-lg font-bold">
                                    Masukkan NIK Penduduk
                                </h2>

                                <p class="text-sm text-slate-500 mt-1">
                                    Data penduduk akan diambil secara otomatis dari database kependudukan.
                                </p>

                            </div>


                            <form
                                method="GET"
                                action="index.php">

                                <input
                                    type="hidden"
                                    name="letter_id"
                                    value="<?= $selectedLetter['id'] ?>">


                                <label class="block text-sm font-medium mb-2">

                                    NIK

                                </label>


                                <div class="flex gap-3">

                                    <input
                                        type="text"
                                        name="nik"
                                        value="<?= e($nik) ?>"
                                        maxlength="16"
                                        placeholder="Masukkan 16 digit NIK"
                                        class="flex-1 rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500"
                                        required>


                                    <button
                                        type="submit"
                                        class="px-5 py-3 rounded-xl bg-teal-600 text-white font-semibold hover:bg-teal-700">

                                        <i class="bi bi-search mr-1"></i>

                                        Cari

                                    </button>

                                </div>

                            </form>


                            <?php if ($nik !== '' && !$population): ?>

                                <div
                                    class="mt-5 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">

                                    <i class="bi bi-exclamation-circle mr-2"></i>

                                    Data penduduk dengan NIK tersebut tidak ditemukan.

                                </div>

                            <?php endif; ?>


                            <!-- ==================================================
                             PREVIEW DATA PENDUDUK
                        ================================================== -->

                            <?php if ($population): ?>

                                <div class="mt-8">

                                    <div class="flex items-center justify-between mb-4">

                                        <div>

                                            <h3 class="font-bold text-slate-900">
                                                Preview Data Penduduk
                                            </h3>

                                            <p class="text-sm text-slate-500">
                                                Pastikan data sudah benar sebelum generate surat.
                                            </p>

                                        </div>

                                        <span
                                            class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold">

                                            Data ditemukan

                                        </span>

                                    </div>


                                    <div class="grid sm:grid-cols-2 gap-4">

                                        <div class="bg-slate-50 rounded-xl p-4">

                                            <p class="text-xs text-slate-500">
                                                NIK
                                            </p>

                                            <p class="font-semibold mt-1">
                                                <?= e($population['nik']) ?>
                                            </p>

                                        </div>


                                        <div class="bg-slate-50 rounded-xl p-4">

                                            <p class="text-xs text-slate-500">
                                                Nama
                                            </p>

                                            <p class="font-semibold mt-1">
                                                <?= e($population['name']) ?>
                                            </p>

                                        </div>


                                        <div class="bg-slate-50 rounded-xl p-4">

                                            <p class="text-xs text-slate-500">
                                                Tempat, Tanggal Lahir
                                            </p>

                                            <p class="font-semibold mt-1">

                                                <?= e($population['birth_place'] ?? '-') ?>,

                                                <?php
                                                if (!empty($population['birth_date'])) {
                                                    echo date(
                                                        'd-m-Y',
                                                        strtotime($population['birth_date'])
                                                    );
                                                } else {
                                                    echo '-';
                                                }
                                                ?>

                                            </p>

                                        </div>


                                        <div class="bg-slate-50 rounded-xl p-4">

                                            <p class="text-xs text-slate-500">
                                                Jenis Kelamin
                                            </p>

                                            <p class="font-semibold mt-1">
                                                <?= e($population['gender'] ?? '-') ?>
                                            </p>

                                        </div>


                                        <div class="bg-slate-50 rounded-xl p-4">

                                            <p class="text-xs text-slate-500">
                                                Agama
                                            </p>

                                            <p class="font-semibold mt-1">
                                                <?= e($population['religion'] ?? '-') ?>
                                            </p>

                                        </div>


                                        <div class="bg-slate-50 rounded-xl p-4">

                                            <p class="text-xs text-slate-500">
                                                Pekerjaan
                                            </p>

                                            <p class="font-semibold mt-1">
                                                <?= e($population['occupation'] ?? '-') ?>
                                            </p>

                                        </div>


                                        <div class="bg-slate-50 rounded-xl p-4 sm:col-span-2">

                                            <p class="text-xs text-slate-500">
                                                Alamat
                                            </p>

                                            <p class="font-semibold mt-1">
                                                <?= e($population['address'] ?? '-') ?>
                                            </p>

                                        </div>

                                    </div>


                                    <!-- ==================================================
                                     GENERATE
                                ================================================== -->

                                    <div
                                        class="mt-6 pt-6 border-t border-slate-200 flex justify-end">
                                        <form
                                            action="generate.php"
                                            method="POST"
                                            class="mt-6">

                                            <input
                                                type="hidden"
                                                name="letter_id"
                                                value="<?= (int) $selectedLetter['id'] ?>">

                                            <input
                                                type="hidden"
                                                name="population_id"
                                                value="<?= (int) $population['id'] ?>">

                                            <!-- KEPERLUAN -->

                                            <div>

                                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                                    Keperluan Surat
                                                </label>

                                                <textarea
                                                    name="purpose"
                                                    rows="4"
                                                    required
                                                    placeholder="Contoh: Untuk keperluan izin mengadakan kegiatan keramaian..."
                                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"></textarea>

                                            </div>


                                            <div class="mt-6 flex justify-end">

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-6 py-3 font-semibold text-white hover:bg-teal-700">

                                                    <i class="bi bi-file-earmark-plus"></i>

                                                    Generate Surat

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </main>

    </div>

</body>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>