<?php

require_once '../../../config/app.php';

// ======================================================
// VALIDASI PARAMETER
// ======================================================

$file = $_GET['file'] ?? '';

$letter_id = isset($_GET['letter_id'])
    ? (int) $_GET['letter_id']
    : 0;

$population_id = isset($_GET['population_id'])
    ? (int) $_GET['population_id']
    : 0;

// ======================================================
// AMBIL DATA GENERATED LETTERS
// ======================================================

$fileEscaped = mysqli_real_escape_string(
    $conn,
    $file
);

$queryGenerated = mysqli_query(
    $conn,
    "
    SELECT *
    FROM generated_letters
    WHERE file_name = '$fileEscaped'
      AND letter_type_id = '$letter_id'
      AND population_id = '$population_id'
    LIMIT 1
    "
);

if (!$queryGenerated) {
    die('Query generated_letters gagal: ' .
        mysqli_error($conn));
}

if (mysqli_num_rows($queryGenerated) === 0) {
    die('Riwayat surat tidak ditemukan.');
}

$generated_letters = mysqli_fetch_assoc(
    $queryGenerated
);

if (
    $file === '' ||
    $letter_id <= 0 ||
    $population_id <= 0
) {
    die('Parameter tidak lengkap.');
}


// ======================================================
// CEGAH PATH TRAVERSAL
// ======================================================

$file = basename($file);


// ======================================================
// AMBIL JENIS SURAT
// ======================================================

$queryLetter = mysqli_query(
    $conn,
    "SELECT *
     FROM letter_types
     WHERE id = '$letter_id'
     LIMIT 1"
);

if (!$queryLetter) {
    die('Query jenis surat gagal: ' .
        mysqli_error($conn));
}

if (mysqli_num_rows($queryLetter) === 0) {
    die('Jenis surat tidak ditemukan.');
}

$letter = mysqli_fetch_assoc($queryLetter);


// ======================================================
// AMBIL DATA PENDUDUK
// ======================================================

$queryPopulation = mysqli_query(
    $conn,
    "SELECT *
     FROM populations
     WHERE id = '$population_id'
     LIMIT 1"
);

if (!$queryPopulation) {
    die('Query penduduk gagal: ' .
        mysqli_error($conn));
}

if (mysqli_num_rows($queryPopulation) === 0) {
    die('Data penduduk tidak ditemukan.');
}

$population = mysqli_fetch_assoc($queryPopulation);


// ======================================================
// FILE HASIL DOCX
// ======================================================

$filePath =
    __DIR__ .
    '/../../../uploads/generated-letters/' .
    $file;

if (!file_exists($filePath)) {
    die('File surat hasil generate tidak ditemukan.');
}


// ======================================================
// URL FILE DOCX
// ======================================================

$fileUrl =
    APP_URL .
    'uploads/generated-letters/' .
    rawurlencode($file);


// ======================================================
// DATA SURAT
// ======================================================

$letterName =
    $letter['name']
    ?? $letter['nama']
    ?? 'Surat';

$letterDescription =
    $letter['description']
    ?? $letter['deskripsi']
    ?? '';


// ======================================================
// TANGGAL
// ======================================================

$today = date('d-m-Y');


// ======================================================
// FORMAT DATA
// ======================================================

$birthDate = '-';

if (!empty($population['birth_date'])) {

    $birthDate = date(
        'd-m-Y',
        strtotime($population['birth_date'])
    );
}


// ======================================================
// STATUS PERKAWINAN
// ======================================================

$maritalStatus =
    $population['marital_status'] ?? '';

if ($maritalStatus === 'Single') {

    $maritalStatus = 'Belum Menikah';
} elseif ($maritalStatus === 'Married') {

    $maritalStatus = 'Menikah';
} elseif ($maritalStatus === 'Divorced') {

    $maritalStatus = 'Cerai';
} elseif ($maritalStatus === 'Widowed') {

    $maritalStatus = 'Cerai Mati';
}


// ======================================================
// HELPER
// ======================================================

if (!function_exists('e')) {

    function e($value)
    {
        return htmlspecialchars(
            $value ?? '',
            ENT_QUOTES,
            'UTF-8'
        );
    }
}


// ======================================================
// LAYOUT ADMIN
// ======================================================

include APP_PATH . "includes/admin/layout-top.php";

?>

<style>
    /* ==================================================
       PAPER
    ================================================== */

    .paper {

        width: 210mm;
        min-height: 297mm;

        margin: 0 auto;

        background: white;

        padding: 18mm 18mm 20mm 18mm;

        box-shadow:
            0 10px 30px rgba(15, 23, 42, 0.10);

        color: #111827;

        font-family:
            "Times New Roman",
            Times,
            serif;

    }


    /* ==================================================
       KOP
    ================================================== */

    .kop {

        text-align: center;

        padding-bottom: 10px;

        border-bottom: 3px solid #111827;

        margin-bottom: 24px;

    }

    .kop h1,
    .kop h2,
    .kop h3,
    .kop p {

        margin: 0;

    }

    .kop h1 {

        font-size: 17px;

        font-weight: 700;

        letter-spacing: 0.3px;

    }

    .kop h2 {

        font-size: 18px;

        font-weight: 700;

    }

    .kop h3 {

        font-size: 18px;

        font-weight: 700;

    }

    .kop p {

        font-size: 11px;

        margin-top: 3px;

    }


    /* ==================================================
       JUDUL SURAT
    ================================================== */

    .letter-title {

        text-align: center;

        margin-bottom: 20px;

    }

    .letter-title h1 {

        font-size: 16px;

        font-weight: 700;

        text-decoration: underline;

        margin: 0;

    }

    .letter-title p {

        margin-top: 4px;

        font-size: 12px;

    }


    /* ==================================================
       ISI
    ================================================== */

    .letter-body {

        font-size: 13px;

        line-height: 1.6;

        text-align: justify;

    }

    .letter-body p {

        margin: 0 0 12px 0;

    }


    /* ==================================================
       DATA PENDUDUK
    ================================================== */

    .identity {

        width: 100%;

        margin: 12px 0;

        font-size: 13px;

    }

    .identity td {

        vertical-align: top;

        padding: 3px 0;

    }

    .identity .number {

        width: 28px;

    }

    .identity .label {

        width: 180px;

    }

    .identity .colon {

        width: 15px;

    }


    /* ==================================================
       SIGNATURE
    ================================================== */

    .signature {

        width: 100%;

        margin-top: 45px;

    }

    .signature td {

        width: 50%;

        vertical-align: top;

        text-align: center;

        font-size: 13px;

    }

    .signature-space {

        height: 80px;

    }


    /* ==================================================
       TOOLBAR
    ================================================== */

    .no-print {

        display: block;

    }


    /* ==================================================
       PRINT
    ================================================== */

    @media print {

        @page {

            size: A4;

            margin: 0;

        }


        html,
        body {

            margin: 0 !important;

            padding: 0 !important;

            background: white !important;

        }


        body * {

            visibility: hidden;

        }


        .print-area,
        .print-area * {

            visibility: visible;

        }


        .print-area {

            position: absolute;

            left: 0;

            top: 0;

            width: 100%;

        }


        .paper {

            width: 210mm;

            min-height: 297mm;

            margin: 0;

            padding: 18mm 18mm 20mm 18mm;

            box-shadow: none !important;

        }


        .no-print {

            display: none !important;

        }

    }
</style>


<body class="bg-slate-100 min-h-screen">


    <!-- ==================================================
         TOOLBAR
    ================================================== -->

    <div class="no-print sticky top-0 z-50 border-b border-slate-200 bg-white">

        <div class="max-w-7xl mx-auto px-6 py-4">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>

                    <h1 class="text-xl font-bold text-slate-900">

                        Preview Surat

                    </h1>

                    <p class="text-sm text-slate-500">

                        <?= e($letterName) ?>

                    </p>

                </div>


                <div class="flex flex-wrap gap-2">

                    <!-- Kembali -->

                    <a
                        href="../riwayat/index.php"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">

                        <i class="bi bi-arrow-left"></i>

                        Kembali

                    </a>


                    <!-- Download DOCX -->

                    <a
                        href="<?= e($fileUrl) ?>"
                        download
                        class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-700">

                        <i class="bi bi-file-earmark-word"></i>

                        Download DOCX

                    </a>


                    <!-- Print -->

                    <button
                        type="button"
                        onclick="window.print()"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-900">

                        <i class="bi bi-printer"></i>

                        Print

                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- ==================================================
     PRINT AREA
================================================== -->

    <main class="print-area py-8">

        <div class="paper">

            <!-- ==================================================
         KOP SURAT
    ================================================== -->

            <div class="kop">

                <table class="kop-table">

                    <tr>

                        <!-- LOGO -->

                        <td class="kop-logo">

                            <img
                                src="<?= APP_URL ?>assets/img/logo.webp"
                                class="w-20 h-20 object-contain"
                                alt="Logo Desa">

                        </td>


                        <!-- TEXT -->

                        <td class="kop-text">

                            <h1>
                                PEMERINTAH KABUPATEN WONOGIRI
                            </h1>

                            <h2>
                                KECAMATAN NGADIROJO
                            </h2>

                            <h3>
                                DESA MLOKOMANIS WETAN
                            </h3>

                            <p>
                                Jl.Sumber Agung Desa Mlokomanis Wetan
                                Email: desamlokomaniswetan@gmail.com
                                Kode Pos: 57681
                            </p>

                        </td>

                    </tr>

                </table>

            </div>


            <!-- ==================================================
         JUDUL SURAT
    ================================================== -->

            <div class="letter-title">

                <h1>
                    <?= e(strtoupper($letterName)) ?>
                </h1>

                <p>
                    Nomor : ........................................
                </p>

            </div>


            <!-- ==================================================
         ISI SURAT
    ================================================== -->

            <div class="letter-body">

                <p>
                    Yang bertanda tangan di bawah ini Kepala Desa
                    Mlokomanis Wetan, Kecamatan Ngadirojo,
                    Kabupaten Wonogiri, Provinsi Jawa Tengah
                    menerangkan dengan sebenarnya bahwa :
                </p>


                <!-- ==================================================
             DATA PENDUDUK
        ================================================== -->

                <table class="identity">

                    <!-- 1. NAMA -->

                    <tr>

                        <td class="number">
                            1.
                        </td>

                        <td class="label">
                            Nama Lengkap
                        </td>

                        <td class="colon">
                            :
                        </td>

                        <td>
                            <?= e($population['name'] ?? '') ?>
                        </td>

                    </tr>


                    <!-- 2. NIK -->

                    <tr>

                        <td class="number">
                            2.
                        </td>

                        <td class="label">
                            NIK / No. KTP
                        </td>

                        <td class="colon">
                            :
                        </td>

                        <td>
                            <?= e($population['nik'] ?? '') ?>
                        </td>

                    </tr>


                    <!-- 3. NO KK -->

                    <tr>

                        <td class="number">
                            3.
                        </td>

                        <td class="label">
                            No. KK
                        </td>

                        <td class="colon">
                            :
                        </td>

                        <td>
                            <?= e($population['no_kk'] ?? '') ?>
                        </td>

                    </tr>


                    <!-- 4. KEPALA KELUARGA -->

                    <tr>

                        <td class="number">
                            4.
                        </td>

                        <td class="label">
                            Kepala Keluarga
                        </td>

                        <td class="colon">
                            :
                        </td>

                        <td>
                            <?= e($population['head_of_family'] ?? '') ?>
                        </td>

                    </tr>


                    <!-- 5. TEMPAT/TANGGAL LAHIR -->

                    <tr>

                        <td class="number">
                            5.
                        </td>

                        <td class="label">
                            Tempat/Tanggal Lahir
                        </td>

                        <td class="colon">
                            :
                        </td>

                        <td>

                            <?= e($population['birth_place'] ?? '') ?>

                            <?php if (!empty($population['birth_date'])): ?>

                                ,
                                <?= e(
                                    date(
                                        'd-m-Y',
                                        strtotime($population['birth_date'])
                                    )
                                ) ?>

                            <?php endif; ?>

                        </td>

                    </tr>


                    <!-- 6. JENIS KELAMIN -->

                    <tr>

                        <td class="number">
                            6.
                        </td>

                        <td class="label">
                            Jenis Kelamin
                        </td>

                        <td class="colon">
                            :
                        </td>

                        <td>
                            <?php
                            if (($population['gender'] ?? '') === 'Male') {
                                echo 'Laki-laki';
                            } elseif (($population['gender'] ?? '') === 'Female') {
                                echo 'Perempuan';
                            } else {
                                echo e($population['gender'] ?? '');
                            }
                            ?>
                        </td>

                    </tr>


                    <!-- 7. ALAMAT -->

                    <tr>

                        <td class="number">
                            7.
                        </td>

                        <td class="label">
                            Alamat/Tempat Tinggal
                        </td>

                        <td class="colon">
                            :
                        </td>

                        <td>

                            <?= e($population['address'] ?? '') ?>

                            <?php if (!empty($population['rt'])): ?>

                                , RT <?= e($population['rt']) ?>

                            <?php endif; ?>

                            <?php if (!empty($population['rw'])): ?>

                                / RW <?= e($population['rw']) ?>

                            <?php endif; ?>

                            <?php if (!empty($population['hamlet'])): ?>

                                , <?= e($population['hamlet']) ?>

                            <?php endif; ?>

                        </td>

                    </tr>


                    <!-- 8. AGAMA -->

                    <tr>

                        <td class="number">
                            8.
                        </td>

                        <td class="label">
                            Agama
                        </td>

                        <td class="colon">
                            :
                        </td>

                        <td>
                            <?= e($population['religion'] ?? '') ?>
                        </td>

                    </tr>


                    <!-- 9. STATUS -->

                    <tr>

                        <td class="number">
                            9.
                        </td>

                        <td class="label">
                            Status
                        </td>

                        <td class="colon">
                            :
                        </td>

                        <td>
                            <?= e($maritalStatus) ?>
                        </td>

                    </tr>


                    <!-- 10. PENDIDIKAN -->

                    <tr>

                        <td class="number">
                            10.
                        </td>

                        <td class="label">
                            Pendidikan
                        </td>

                        <td class="colon">
                            :
                        </td>

                        <td>
                            <?= e($population['education'] ?? '') ?>
                        </td>

                    </tr>


                    <!-- 11. PEKERJAAN -->

                    <tr>

                        <td class="number">
                            11.
                        </td>

                        <td class="label">
                            Pekerjaan
                        </td>

                        <td class="colon">
                            :
                        </td>

                        <td>
                            <?= e($population['occupation'] ?? '') ?>
                        </td>

                    </tr>


                    <!-- 12. KEWARGANEGARAAN -->

                    <tr>

                        <td class="number">
                            12.
                        </td>

                        <td class="label">
                            Kewarganegaraan
                        </td>

                        <td class="colon">
                            :
                        </td>

                        <td>

                            <?php

                            $citizenship =
                                $population['citizenship'] ?? '';

                            if (
                                $citizenship === 'Indonesia' ||
                                $citizenship === 'WNI'
                            ) {

                                echo 'WNI';
                            } elseif ($citizenship !== '') {

                                echo e($citizenship);
                            }

                            ?>

                        </td>

                    </tr>


                    <!-- 13. KEPERLUAN -->

                    <tr>

                        <td class="number">
                            13.
                        </td>

                        <td class="label">
                            Keperluan
                        </td>

                        <td class="colon">
                            :
                        </td>

                        <td>
                            <?= e($generated_letters['purpose'] ?? '') ?>
                        </td>

                    </tr>

                </table>


                <!-- ==================================================
             PENUTUP
        ================================================== -->

                <p>

                    Orang tersebut adalah benar-benar warga
                    Desa Mlokomanis Wetan dengan data seperti di atas.

                </p>


                <p>

                    Demikian surat keterangan ini dibuat, untuk
                    dipergunakan sebagaimana mestinya.

                </p>


                <!-- ==================================================
             TANDA TANGAN
        ================================================== -->

                <table class="signature">

                    <tr>

                        <!-- PEMEGANG SURAT -->

                        <td>

                            <div>
                                <br>
                                Pemegang Surat
                            </div>


                            <div class="signature-space"></div>


                            <div>

                                <strong>
                                    <?= e($population['name'] ?? '') ?>
                                </strong>

                            </div>

                        </td>


                        <!-- KEPALA DESA -->

                        <td>

                            <div>

                                Mlokomanis Wetan,

                                <?= date('d F Y') ?>

                            </div>


                            <div>

                                Kepala Desa Mlokomanis Wetan

                            </div>


                            <div class="signature-space"></div>


                            <div>

                                <strong>
                                    SUWARNO
                                </strong>

                            </div>

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </main>


    <script>
        const params = new URLSearchParams(
            window.location.search
        );

        // ==========================================
        // MODE PRINT
        // ==========================================

        if (params.get('print') === '1') {

            window.addEventListener(
                'load',
                function() {

                    setTimeout(
                        function() {
                            window.print();
                        },
                        500
                    );

                }
            );

        }
    </script>


</body>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>