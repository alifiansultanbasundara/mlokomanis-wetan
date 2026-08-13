<?php

require_once '../../../config/app.php';

// ======================================================
// DATA DEFAULT
// ======================================================

$errors = [];

$name = '';
$slug = '';
$icon = 'bi-file-earmark-text';
$color = 'teal';
$description = '';
$template_body = '';
$placeholder_map = '';
$status = 'Aktif';
$sort_order = 0;


// ======================================================
// PROSES FORM
// ======================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $icon = trim($_POST['icon'] ?? 'bi-file-earmark-text');
    $color = trim($_POST['color'] ?? 'teal');
    $description = trim($_POST['description'] ?? '');
    $template_body = trim($_POST['template_body'] ?? '');
    $placeholder_map = trim($_POST['placeholder_map'] ?? '');
    $status = $_POST['status'] ?? 'Aktif';
    $sort_order = (int) ($_POST['sort_order'] ?? 0);


    // ==================================================
    // VALIDASI
    // ==================================================

    if ($name === '') {
        $errors[] = 'Nama jenis surat wajib diisi.';
    }

    if ($slug === '') {
        $slug = strtolower($name);

        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
    }

    if (!in_array($status, ['Aktif', 'Nonaktif'])) {
        $status = 'Aktif';
    }


    // ==================================================
    // CEK SLUG
    // ==================================================

    if (empty($errors)) {

        $slugEscaped = mysqli_real_escape_string($conn, $slug);

        $check = mysqli_query(
            $conn,
            "SELECT id FROM letter_types
             WHERE slug = '$slugEscaped'
             LIMIT 1"
        );

        if (mysqli_num_rows($check) > 0) {
            $errors[] = 'Slug sudah digunakan. Silakan gunakan slug lain.';
        }
    }


    // ==================================================
    // UPLOAD TEMPLATE DOCX
    // ==================================================

    $file_path = null;

    if (
        isset($_FILES['template_file']) &&
        $_FILES['template_file']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['template_file']['error'] !== UPLOAD_ERR_OK) {

            $errors[] = 'Template surat gagal diupload.';
        } else {

            $file = $_FILES['template_file'];

            $extension = strtolower(
                pathinfo($file['name'], PATHINFO_EXTENSION)
            );

            $allowedExtensions = ['docx'];

            if (!in_array($extension, $allowedExtensions)) {

                $errors[] = 'Template harus berupa file DOCX.';
            } elseif ($file['size'] > 10 * 1024 * 1024) {

                $errors[] = 'Ukuran template maksimal 10 MB.';
            } else {

                $uploadDir = '../../../uploads/letter-templates/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $filename =
                    $slug . '-' .
                    date('YmdHis') . '-' .
                    uniqid() .
                    '.docx';

                $destination = $uploadDir . $filename;

                if (move_uploaded_file($file['tmp_name'], $destination)) {

                    $file_path =
                        'uploads/letter-templates/' . $filename;
                } else {

                    $errors[] = 'Gagal menyimpan file template.';
                }
            }
        }
    }


    // ==================================================
    // SIMPAN
    // ==================================================

    if (empty($errors)) {

        $nameEscaped = mysqli_real_escape_string($conn, $name);
        $iconEscaped = mysqli_real_escape_string($conn, $icon);
        $colorEscaped = mysqli_real_escape_string($conn, $color);
        $descriptionEscaped = mysqli_real_escape_string($conn, $description);
        $templateBodyEscaped = mysqli_real_escape_string($conn, $template_body);
        $placeholderMapEscaped = mysqli_real_escape_string($conn, $placeholder_map);
        $statusEscaped = mysqli_real_escape_string($conn, $status);

        $filePathSql = $file_path !== null
            ? "'" . mysqli_real_escape_string($conn, $file_path) . "'"
            : "NULL";

        $query = "
            INSERT INTO letter_types (
                slug,
                name,
                icon,
                color,
                description,
                template_body,
                file_path,
                placeholder_map,
                status,
                sort_order
            ) VALUES (
                '$slugEscaped',
                '$nameEscaped',
                '$iconEscaped',
                '$colorEscaped',
                " . ($description !== '' ? "'$descriptionEscaped'" : "NULL") . ",
                " . ($template_body !== '' ? "'$templateBodyEscaped'" : "NULL") . ",
                $filePathSql,
                " . ($placeholder_map !== '' ? "'$placeholderMapEscaped'" : "NULL") . ",
                '$statusEscaped',
                '$sort_order'
            )
        ";

        if (mysqli_query($conn, $query)) {

            header(
                "Location: index.php?success=created"
            );

            exit;
        } else {

            // Hapus file jika database gagal
            if ($file_path !== null) {

                $uploadedFile =
                    '../../../' . $file_path;

                if (file_exists($uploadedFile)) {
                    unlink($uploadedFile);
                }
            }

            $errors[] =
                'Gagal menyimpan data: ' .
                mysqli_error($conn);
        }
    }
}

include APP_PATH . "includes/admin/layout-top.php";
?>

<main class="bg-slate-50">

    <div class="max-w-4xl mx-auto p-6">

        <!-- HEADER -->

        <div class="mb-6">

            <div class="flex items-center gap-3">

                <a
                    href="index.php"
                    class="text-slate-500 hover:text-teal-600">

                    <i class="bi bi-arrow-left"></i>

                </a>

                <div>

                    <h1 class="text-2xl font-bold text-slate-800">
                        Tambah Jenis Surat
                    </h1>

                    <p class="text-sm text-slate-500 mt-1">
                        Tambahkan jenis surat yang tersedia dalam layanan desa.
                    </p>

                </div>

            </div>

        </div>


        <!-- ERROR -->

        <?php if (!empty($errors)): ?>

            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

                <div class="flex gap-3">

                    <i class="bi bi-exclamation-circle-fill text-red-600"></i>

                    <div>

                        <p class="font-semibold text-red-700">
                            Terjadi kesalahan
                        </p>

                        <ul class="mt-2 list-disc pl-5 text-sm text-red-600">

                            <?php foreach ($errors as $error): ?>

                                <li>
                                    <?= htmlspecialchars($error) ?>
                                </li>

                            <?php endforeach; ?>

                        </ul>

                    </div>

                </div>

            </div>

        <?php endif; ?>


        <!-- FORM -->

        <form
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6">

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-6 py-4">

                    <h2 class="font-semibold text-slate-800">
                        Informasi Jenis Surat
                    </h2>

                </div>


                <div class="p-6 space-y-5">

                    <!-- NAMA -->

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Nama Jenis Surat
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="Contoh: Surat Keterangan Domisili"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"
                            required>

                    </div>


                    <!-- SLUG -->

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Slug
                        </label>

                        <input
                            type="text"
                            name="slug"
                            value="<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="surat-keterangan-domisili"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        <p class="mt-1 text-xs text-slate-500">
                            Kosongkan jika ingin dibuat otomatis dari nama surat.
                        </p>

                    </div>


                    <!-- ICON + COLOR -->

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Icon Bootstrap
                            </label>

                            <input
                                type="text"
                                name="icon"
                                value="<?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="bi-file-earmark-text"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Warna
                            </label>

                            <select
                                name="color"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                                <?php

                                $colors = [
                                    'teal',
                                    'emerald',
                                    'blue',
                                    'indigo',
                                    'purple',
                                    'amber',
                                    'orange',
                                    'red',
                                    'slate'
                                ];

                                foreach ($colors as $item):

                                ?>

                                    <option
                                        value="<?= $item ?>"
                                        <?= $color === $item ? 'selected' : '' ?>>

                                        <?= ucfirst($item) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                    </div>


                    <!-- DESKRIPSI -->

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Deskripsi
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            placeholder="Deskripsi singkat mengenai surat..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"><?= htmlspecialchars($description) ?></textarea>

                    </div>

                </div>

            </div>


            <!-- TEMPLATE -->

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-6 py-4">

                    <h2 class="font-semibold text-slate-800">
                        Template Surat
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Gunakan template teks atau upload template DOCX.
                    </p>

                </div>


                <div class="p-6 space-y-5">

                    <!-- TEMPLATE BODY -->

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Template Body
                        </label>

                        <textarea
                            name="template_body"
                            rows="8"
                            placeholder="Contoh:

SURAT KETERANGAN DOMISILI

Yang bertanda tangan di bawah ini menerangkan bahwa:

Nama       : {{name}}
NIK        : {{nik}}
Tempat/Tgl : {{birth_place}}, {{birth_date}}
Alamat     : {{address}}

Demikian surat keterangan ini dibuat..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 font-mono text-sm outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"><?= htmlspecialchars($template_body) ?></textarea>

                        <p class="mt-2 text-xs text-slate-500">
                            Gunakan placeholder seperti
                            <code>{{name}}</code>,
                            <code>{{nik}}</code>,
                            <code>{{address}}</code>
                            untuk data penduduk.
                        </p>

                    </div>


                    <!-- PLACEHOLDER MAP -->

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Placeholder Map
                        </label>

                        <textarea
                            name="placeholder_map"
                            rows="5"
                            placeholder='Contoh JSON:
{
    "name": "Nama Penduduk",
    "nik": "NIK",
    "birth_place": "Tempat Lahir",
    "birth_date": "Tanggal Lahir",
    "address": "Alamat",
    "rt": "RT",
    "rw": "RW"
}'
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 font-mono text-sm outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"><?= htmlspecialchars($placeholder_map) ?></textarea>

                    </div>


                    <!-- UPLOAD DOCX -->

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Template DOCX
                        </label>

                        <input
                            type="file"
                            name="template_file"
                            accept=".docx"
                            class="block w-full rounded-xl border border-slate-300 bg-white text-sm text-slate-600 file:mr-4 file:border-0 file:bg-teal-50 file:px-5 file:py-3 file:font-medium file:text-teal-700 hover:file:bg-teal-100">

                        <p class="mt-2 text-xs text-slate-500">
                            Format yang diperbolehkan: DOCX. Maksimal 10 MB.
                        </p>

                    </div>

                </div>

            </div>


            <!-- PENGATURAN -->

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-6 py-4">

                    <h2 class="font-semibold text-slate-800">
                        Pengaturan
                    </h2>

                </div>


                <div class="p-6">

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <!-- STATUS -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Status
                            </label>

                            <select
                                name="status"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                                <option
                                    value="Aktif"
                                    <?= $status === 'Aktif' ? 'selected' : '' ?>>

                                    Aktif

                                </option>

                                <option
                                    value="Nonaktif"
                                    <?= $status === 'Nonaktif' ? 'selected' : '' ?>>

                                    Nonaktif

                                </option>

                            </select>

                        </div>


                        <!-- SORT -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Urutan
                            </label>

                            <input
                                type="number"
                                name="sort_order"
                                value="<?= htmlspecialchars($sort_order, ENT_QUOTES, 'UTF-8') ?>"
                                min="0"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>

                    </div>

                </div>

            </div>


            <!-- ACTION -->

            <div class="flex items-center justify-end gap-3">

                <a
                    href="index.php"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 hover:bg-slate-50">

                    Batal

                </a>

                <button
                    type="submit"
                    class="flex items-center gap-2 rounded-xl bg-teal-600 px-6 py-3 font-semibold text-white shadow-sm transition hover:bg-teal-700">

                    <i class="bi bi-save"></i>

                    Simpan Jenis Surat

                </button>

            </div>

        </form>

    </div>

</main>
<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>