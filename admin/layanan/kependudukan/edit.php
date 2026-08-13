<?php

require_once '../../../config/app.php';

$page = 'kependudukan';


// ======================================================
// VALIDASI ID
// ======================================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header('Location: index.php');
    exit;
}

$id = (int) $_GET['id'];


// ======================================================
// AMBIL DATA
// ======================================================

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM populations
     WHERE id = $id
     LIMIT 1"
);

if (!$query || mysqli_num_rows($query) === 0) {

    header('Location: index.php?error=not_found');
    exit;
}

$population = mysqli_fetch_assoc($query);


// ======================================================
// HELPER
// ======================================================

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Edit Penduduk</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>


<body class="bg-slate-50 text-slate-800">

    <div class="min-h-screen">


        <!-- ==================================================
         HEADER
    =================================================== -->

        <header class="border-b border-slate-200 bg-white">

            <div class="mx-auto max-w-5xl px-6 py-5">

                <div class="flex items-center gap-4">

                    <a
                        href="index.php"
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-50">

                        <i class="bi bi-arrow-left"></i>

                    </a>

                    <div>

                        <h1 class="text-2xl font-bold text-slate-900">
                            Edit Penduduk
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            Perbarui data penduduk.
                        </p>

                    </div>

                </div>

            </div>

        </header>


        <!-- ==================================================
         CONTENT
    =================================================== -->

        <main class="mx-auto max-w-5xl px-6 py-8">

            <form
                action="update.php"
                method="POST"
                class="space-y-6">


                <!-- ID -->

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $population['id'] ?>">


                <!-- ==================================================
                 DATA UTAMA
            =================================================== -->

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 px-6 py-5">

                        <h2 class="font-semibold text-slate-900">
                            Data Penduduk
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Informasi dasar penduduk.
                        </p>

                    </div>


                    <div class="grid gap-6 p-6 md:grid-cols-2">


                        <!-- NIK -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                NIK

                                <span class="text-red-500">*</span>

                            </label>

                            <input
                                type="text"
                                name="nik"
                                value="<?= e($population['nik']) ?>"
                                maxlength="16"
                                minlength="16"
                                pattern="[0-9]{16}"
                                required
                                placeholder="Masukkan 16 digit NIK"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                            <p class="mt-1 text-xs text-slate-500">
                                NIK harus terdiri dari 16 digit.
                            </p>

                        </div>


                        <!-- NO KK -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                No. KK
                            </label>

                            <input
                                type="text"
                                name="no_kk"
                                value="<?= e($population['no_kk'] ?? '') ?>"
                                maxlength="16"
                                minlength="16"
                                pattern="[0-9]{16}"
                                placeholder="Masukkan 16 digit No. KK"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                            <p class="mt-1 text-xs text-slate-500">
                                Nomor Kartu Keluarga terdiri dari 16 digit.
                            </p>

                        </div>


                        <!-- NAMA -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Nama Lengkap

                                <span class="text-red-500">*</span>

                            </label>

                            <input
                                type="text"
                                name="name"
                                value="<?= e($population['name']) ?>"
                                required
                                placeholder="Masukkan nama lengkap"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- KEPALA KELUARGA -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Kepala Keluarga
                            </label>

                            <input
                                type="text"
                                name="head_of_family"
                                value="<?= e($population['head_of_family'] ?? '') ?>"
                                maxlength="150"
                                placeholder="Masukkan nama kepala keluarga"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- TEMPAT LAHIR -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Tempat Lahir
                            </label>

                            <input
                                type="text"
                                name="birth_place"
                                value="<?= e($population['birth_place'] ?? '') ?>"
                                placeholder="Contoh: Surakarta"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- TANGGAL LAHIR -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Tanggal Lahir
                            </label>

                            <input
                                type="date"
                                name="birth_date"
                                value="<?= e($population['birth_date'] ?? '') ?>"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- JENIS KELAMIN -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Jenis Kelamin
                            </label>

                            <select
                                name="gender"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                                <option
                                    value="Laki-laki"
                                    <?= ($population['gender'] ?? '') === 'Laki-laki'
                                        ? 'selected'
                                        : '' ?>>

                                    Laki-laki

                                </option>

                                <option
                                    value="Perempuan"
                                    <?= ($population['gender'] ?? '') === 'Perempuan'
                                        ? 'selected'
                                        : '' ?>>

                                    Perempuan

                                </option>

                            </select>

                        </div>


                        <!-- AGAMA -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Agama
                            </label>

                            <select
                                name="religion"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                                <?php

                                $religions = [
                                    'Islam',
                                    'Kristen',
                                    'Katolik',
                                    'Hindu',
                                    'Buddha',
                                    'Konghucu'
                                ];

                                foreach ($religions as $religion):

                                ?>

                                    <option
                                        value="<?= e($religion) ?>"
                                        <?= ($population['religion'] ?? '') === $religion
                                            ? 'selected'
                                            : '' ?>>

                                        <?= e($religion) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- PENDIDIKAN -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Pendidikan
                            </label>

                            <select
                                name="education"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                                <option value="">
                                    Pilih Pendidikan
                                </option>

                                <?php

                                $educations = [
                                    'Tidak/Belum Sekolah',
                                    'SD',
                                    'SMP',
                                    'SMA',
                                    'D1',
                                    'D2',
                                    'D3',
                                    'D4',
                                    'S1',
                                    'S2',
                                    'S3'
                                ];

                                foreach ($educations as $education):

                                ?>

                                    <option
                                        value="<?= e($education) ?>"
                                        <?= ($population['education'] ?? '') === $education
                                            ? 'selected'
                                            : '' ?>>

                                        <?= e($education) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- PEKERJAAN -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Pekerjaan
                            </label>

                            <input
                                type="text"
                                name="occupation"
                                value="<?= e($population['occupation'] ?? '') ?>"
                                placeholder="Contoh: Petani"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                    </div>

                </div>


                <!-- ==================================================
                 ALAMAT
            =================================================== -->

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 px-6 py-5">

                        <h2 class="font-semibold text-slate-900">
                            Alamat
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Informasi tempat tinggal penduduk.
                        </p>

                    </div>


                    <div class="grid gap-6 p-6 md:grid-cols-2">


                        <!-- ALAMAT -->

                        <div class="md:col-span-2">

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Alamat
                            </label>

                            <textarea
                                name="address"
                                rows="3"
                                placeholder="Masukkan alamat lengkap"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100"><?= e($population['address'] ?? '') ?></textarea>

                        </div>


                        <!-- RT -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                RT
                            </label>

                            <input
                                type="text"
                                name="rt"
                                value="<?= e($population['rt'] ?? '') ?>"
                                maxlength="5"
                                placeholder="Contoh: 001"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- RW -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                RW
                            </label>

                            <input
                                type="text"
                                name="rw"
                                value="<?= e($population['rw'] ?? '') ?>"
                                maxlength="5"
                                placeholder="Contoh: 002"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- DUSUN -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Dusun
                            </label>

                            <input
                                type="text"
                                name="hamlet"
                                value="<?= e($population['hamlet'] ?? '') ?>"
                                placeholder="Contoh: Dusun Krajan"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- STATUS PERKAWINAN -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Status Perkawinan
                            </label>

                            <select
                                name="marital_status"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                                <?php

                                $maritalStatuses = [
                                    'Belum Kawin' => 'Belum Kawin',
                                    'Kawin'       => 'Kawin',
                                    'Cerai'       => 'Cerai',
                                    'Cerai Mati'  => 'Cerai Mati'
                                ];

                                foreach (
                                    $maritalStatuses
                                    as $value => $label
                                ):

                                ?>

                                    <option
                                        value="<?= e($value) ?>"
                                        <?= ($population['marital_status'] ?? '') === $value
                                            ? 'selected'
                                            : '' ?>>

                                        <?= e($label) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- KEWARGANEGARAAN -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Kewarganegaraan
                            </label>

                            <input
                                type="text"
                                name="citizenship"
                                value="<?= e($population['citizenship'] ?? 'WNI') ?>"
                                placeholder="Contoh: WNI"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>

                    </div>

                </div>


                <!-- ==================================================
                 BUTTON
            =================================================== -->

                <div class="flex items-center justify-end gap-3">

                    <a
                        href="index.php"
                        class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-semibold text-slate-600 transition hover:bg-slate-50">

                        Batal

                    </a>


                    <button
                        type="submit"
                        name="update"
                        class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-6 py-3 font-semibold text-white transition hover:bg-teal-700">

                        <i class="bi bi-check-lg"></i>

                        Simpan Perubahan

                    </button>

                </div>


            </form>

        </main>

    </div>

</body>

</html>