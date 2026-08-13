<?php

require_once '../../../config/app.php';

// ======================================================
// VALIDASI ID
// ======================================================

if (
    !isset($_GET['id']) ||
    !ctype_digit((string) $_GET['id']) ||
    (int) $_GET['id'] <= 0
) {
    header('Location: index.php?error=invalid_id');
    exit;
}

$id = (int) $_GET['id'];


// ======================================================
// AMBIL DATA PENDUDUK
// ======================================================

$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM populations
     WHERE id = ?
     LIMIT 1"
);

if (!$stmt) {
    die('Prepare statement gagal: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, 'i', $id);

if (!mysqli_stmt_execute($stmt)) {
    die('Query gagal: ' . mysqli_stmt_error($stmt));
}

$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {

    mysqli_stmt_close($stmt);

    header('Location: index.php?error=not_found');
    exit;
}

$population = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// ======================================================
// FORMAT TANGGAL LAHIR
// ======================================================

$birthDate = '-';

if (!empty($population['birth_date'])) {

    $date = DateTime::createFromFormat(
        'Y-m-d',
        $population['birth_date']
    );

    if ($date !== false) {
        $birthDate = $date->format('d-m-Y');
    }
}

include APP_PATH . "includes/admin/layout-top.php";

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Detail Penduduk - <?= e($population['name']) ?>
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-slate-50">

    <div class="p-4 sm:p-6">

        <!-- ==================================================
             HEADER
        =================================================== -->

        <div class="mb-6">

            <a
                href="index.php"
                class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-teal-600 transition">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

            <div class="mt-4">

                <h1 class="text-2xl font-bold text-slate-900">
                    Detail Penduduk
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Informasi lengkap data penduduk.
                </p>

            </div>

        </div>


        <!-- ==================================================
             CARD
        =================================================== -->

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

            <!-- ==================================================
                 PROFILE HEADER
            =================================================== -->

            <div class="px-5 sm:px-6 py-5 border-b border-slate-200">

                <div class="flex items-center gap-4">

                    <div
                        class="w-12 h-12 shrink-0 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">

                        <i class="bi bi-person text-2xl"></i>

                    </div>

                    <div class="min-w-0">

                        <h2 class="font-bold text-lg text-slate-900 truncate">
                            <?= e($population['name']) ?>
                        </h2>

                        <p class="text-sm text-slate-500 mt-0.5">
                            NIK:
                            <?= e($population['nik']) ?>
                        </p>

                    </div>

                </div>

            </div>


            <!-- ==================================================
                 DATA
            =================================================== -->

            <div class="p-5 sm:p-6">


                <!-- ==================================================
                     IDENTITAS PENDUDUK
                =================================================== -->

                <div>

                    <h3 class="font-semibold text-slate-900 mb-5">
                        Identitas Penduduk
                    </h3>

                    <div class="grid md:grid-cols-2 gap-x-8 gap-y-5">

                        <!-- NIK -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                NIK
                            </p>

                            <p class="font-medium text-slate-900 break-all">
                                <?= e($population['nik']) ?>
                            </p>

                        </div>


                        <!-- Nama -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                Nama Lengkap
                            </p>

                            <p class="font-medium text-slate-900">
                                <?= e($population['name']) ?>
                            </p>

                        </div>


                        <!-- Tempat Lahir -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                Tempat Lahir
                            </p>

                            <p class="text-slate-700">
                                <?= e($population['birth_place']) ?>
                            </p>

                        </div>


                        <!-- Tanggal Lahir -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                Tanggal Lahir
                            </p>

                            <p class="text-slate-700">
                                <?= e($birthDate) ?>
                            </p>

                        </div>


                        <!-- Jenis Kelamin -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                Jenis Kelamin
                            </p>

                            <p class="text-slate-700">
                                <?= e($population['gender']) ?>
                            </p>

                        </div>


                        <!-- Agama -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                Agama
                            </p>

                            <p class="text-slate-700">
                                <?= e($population['religion']) ?>
                            </p>

                        </div>


                        <!-- Pekerjaan -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                Pekerjaan
                            </p>

                            <p class="text-slate-700">
                                <?= e($population['occupation']) ?>
                            </p>

                        </div>


                        <!-- Kewarganegaraan -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                Kewarganegaraan
                            </p>

                            <p class="text-slate-700">
                                <?= e($population['citizenship']) ?>
                            </p>

                        </div>

                    </div>

                </div>


                <!-- ==================================================
                     DATA KELUARGA & PENDIDIKAN
                =================================================== -->

                <div class="mt-8 pt-6 border-t border-slate-200">

                    <h3 class="font-semibold text-slate-900 mb-5">
                        Data Keluarga & Pendidikan
                    </h3>

                    <div class="grid md:grid-cols-2 gap-x-8 gap-y-5">

                        <!-- No KK -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                Nomor Kartu Keluarga
                            </p>

                            <p class="text-slate-700 break-all">
                                <?= e($population['no_kk']) ?>
                            </p>

                        </div>


                        <!-- Kepala Keluarga -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                Kepala Keluarga
                            </p>

                            <p class="text-slate-700">
                                <?= e($population['head_of_family']) ?>
                            </p>

                        </div>


                        <!-- Pendidikan -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                Pendidikan Terakhir
                            </p>

                            <p class="text-slate-700">
                                <?= e($population['education']) ?>
                            </p>

                        </div>


                        <!-- Status Perkawinan -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                Status Perkawinan
                            </p>

                            <p class="text-slate-700">
                                <?= e($population['marital_status']) ?>
                            </p>

                        </div>

                    </div>

                </div>


                <!-- ==================================================
                     ALAMAT
                =================================================== -->

                <div class="mt-8 pt-6 border-t border-slate-200">

                    <h3 class="font-semibold text-slate-900 mb-5">
                        Alamat
                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">


                        <!-- Alamat Lengkap -->

                        <div class="md:col-span-2">

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                Alamat Lengkap
                            </p>

                            <p class="text-slate-700 leading-relaxed">

                                <?= nl2br(
                                    e($population['address'])
                                ) ?>

                            </p>

                        </div>


                        <!-- RT -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                RT
                            </p>

                            <p class="text-slate-700">
                                <?= e($population['rt']) ?>
                            </p>

                        </div>


                        <!-- RW -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                RW
                            </p>

                            <p class="text-slate-700">
                                <?= e($population['rw']) ?>
                            </p>

                        </div>


                        <!-- Dusun -->

                        <div>

                            <p class="text-xs font-medium text-slate-500 mb-1">
                                Dusun
                            </p>

                            <p class="text-slate-700">
                                <?= e($population['hamlet']) ?>
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ==================================================
                 FOOTER ACTION
            =================================================== -->

            <div
                class="px-5 sm:px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-col-reverse sm:flex-row justify-end gap-3">

                <a
                    href="index.php"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

                    <i class="bi bi-arrow-left"></i>

                    Kembali

                </a>

                <a
                    href="edit.php?id=<?= (int) $population['id'] ?>"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-teal-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-teal-700 transition">

                    <i class="bi bi-pencil"></i>

                    Edit Data

                </a>

            </div>

        </div>

    </div>

</body>

</html>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>