<?php

require_once '../../../config/app.php';

// ======================================================
// VALIDASI ID
// ======================================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// ======================================================
// AMBIL DATA JENIS SURAT
// ======================================================

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM letter_types
     WHERE id = '$id'
     LIMIT 1"
);

if (!$query || mysqli_num_rows($query) === 0) {
    header("Location: index.php?error=not_found");
    exit;
}

$letter = mysqli_fetch_assoc($query);

// ======================================================
// DATA
// ======================================================

$name = $letter['name'] ?? '';
$slug = $letter['slug'] ?? '';
$icon = $letter['icon'] ?? 'bi-file-earmark-text';
$color = $letter['color'] ?? 'teal';
$description = $letter['description'] ?? '';
$template_body = $letter['template_body'] ?? '';
$file_path = $letter['file_path'] ?? '';
$placeholder_map = $letter['placeholder_map'] ?? '';
$status = $letter['status'] ?? 'Aktif';
$sort_order = $letter['sort_order'] ?? 0;

$errors = [];


// ======================================================
// PROSES UPDATE
// ======================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $slug = trim($_POST['slug'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $icon = trim($_POST['icon'] ?? 'bi-file-earmark-text');
    $color = trim($_POST['color'] ?? 'teal');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $template_body = trim($_POST['template_body'] ?? '');
    $placeholder_map = trim($_POST['placeholder_map'] ?? '');
    $status = $_POST['status'] ?? 'Aktif';
    $sort_order = (int) ($_POST['sort_order'] ?? 0);


    // ==================================================
    // VALIDASI
    // ==================================================

    if ($nama === '') {
        $errors[] = 'Nama jenis surat wajib diisi.';
    }


    // ==================================================
    // SLUG OTOMATIS
    // ==================================================

    if ($slug === '') {

        $slug = strtolower($nama);

        $slug = preg_replace(
            '/[^a-z0-9]+/',
            '-',
            $slug
        );

        $slug = trim($slug, '-');
    }


    // ==================================================
    // VALIDASI STATUS
    // ==================================================

    if (!in_array($status, ['Aktif', 'Nonaktif'])) {
        $status = 'Aktif';
    }


    // ==================================================
    // CEK SLUG
    // ==================================================

    if (empty($errors)) {

        $slugEscaped = mysqli_real_escape_string(
            $conn,
            $slug
        );

        $check = mysqli_query(
            $conn,
            "SELECT id
             FROM letter_types
             WHERE slug = '$slugEscaped'
             AND id != '$id'
             LIMIT 1"
        );

        if ($check && mysqli_num_rows($check) > 0) {

            $errors[] =
                'Slug sudah digunakan oleh jenis surat lain.';
        }
    }


    // ==================================================
    // UPLOAD TEMPLATE DOCX BARU
    // ==================================================

    $new_file_path = $file_path;

    if (
        isset($_FILES['template_file']) &&
        $_FILES['template_file']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['template_file']['error'] !== UPLOAD_ERR_OK) {

            $errors[] =
                'Template surat gagal diupload.';
        } else {

            $file = $_FILES['template_file'];

            $extension = strtolower(
                pathinfo(
                    $file['name'],
                    PATHINFO_EXTENSION
                )
            );

            if ($extension !== 'docx') {

                $errors[] =
                    'Template harus berupa file DOCX.';
            } elseif ($file['size'] > 10 * 1024 * 1024) {

                $errors[] =
                    'Ukuran template maksimal 10 MB.';
            } else {

                $uploadDir =
                    '../../../uploads/letter-templates/';

                if (!is_dir($uploadDir)) {

                    mkdir(
                        $uploadDir,
                        0777,
                        true
                    );
                }

                $filename =
                    $slug .
                    '-' .
                    date('YmdHis') .
                    '-' .
                    uniqid() .
                    '.docx';

                $destination =
                    $uploadDir . $filename;

                if (
                    move_uploaded_file(
                        $file['tmp_name'],
                        $destination
                    )
                ) {

                    $new_file_path =
                        'uploads/letter-templates/' .
                        $filename;
                } else {

                    $errors[] =
                        'Gagal menyimpan file template.';
                }
            }
        }
    }


    // ==================================================
    // UPDATE DATABASE
    // ==================================================

    if (empty($errors)) {

        $slugEscaped = mysqli_real_escape_string(
            $conn,
            $slug
        );

        $namaEscaped = mysqli_real_escape_string(
            $conn,
            $nama
        );

        $iconEscaped = mysqli_real_escape_string(
            $conn,
            $icon
        );

        $colorEscaped = mysqli_real_escape_string(
            $conn,
            $color
        );

        $deskripsiEscaped = mysqli_real_escape_string(
            $conn,
            $deskripsi
        );

        $templateBodyEscaped = mysqli_real_escape_string(
            $conn,
            $template_body
        );

        $placeholderMapEscaped = mysqli_real_escape_string(
            $conn,
            $placeholder_map
        );

        $statusEscaped = mysqli_real_escape_string(
            $conn,
            $status
        );

        $filePathSql = $new_file_path !== ''
            ? "'" . mysqli_real_escape_string(
                $conn,
                $new_file_path
            ) . "'"
            : "NULL";


        $update = "
            UPDATE letter_types SET

                slug = '$slugEscaped',

                nama = '$namaEscaped',

                icon = '$iconEscaped',

                color = '$colorEscaped',

                deskripsi = " .
            (
                $deskripsi !== ''
                ? "'$deskripsiEscaped'"
                : "NULL"
            ) . ",

                template_body = " .
            (
                $template_body !== ''
                ? "'$templateBodyEscaped'"
                : "NULL"
            ) . ",

                file_path = $filePathSql,

                placeholder_map = " .
            (
                $placeholder_map !== ''
                ? "'$placeholderMapEscaped'"
                : "NULL"
            ) . ",

                status = '$statusEscaped',

                sort_order = '$sort_order'

            WHERE id = '$id'
        ";


        if (mysqli_query($conn, $update)) {

            // ==========================================
            // HAPUS TEMPLATE LAMA
            // JIKA ADA TEMPLATE BARU
            // ==========================================

            if (
                $new_file_path !== $file_path &&
                !empty($file_path)
            ) {

                $oldFile =
                    '../../../' . $file_path;

                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }


            header(
                "Location: index.php?success=updated"
            );

            exit;
        } else {

            // Hapus file baru jika database gagal

            if (
                $new_file_path !== $file_path &&
                !empty($new_file_path)
            ) {

                $newFile =
                    '../../../' . $new_file_path;

                if (file_exists($newFile)) {
                    unlink($newFile);
                }
            }

            $errors[] =
                'Gagal memperbarui data: ' .
                mysqli_error($conn);
        }
    }
}


include APP_PATH . "includes/admin/layout-top.php";

?>

<body class="bg-slate-100 text-slate-800">

    <div class="max-w-4xl mx-auto px-6 py-8">

        <!-- HEADER -->

        <div class="mb-6">

            <a
                href="index.php"
                class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-teal-600 mb-4">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

            <h1 class="text-2xl font-bold text-slate-900">
                Edit Jenis Surat
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Perbarui informasi jenis surat yang tersedia pada layanan surat.
            </p>

        </div>


        <!-- FORM -->

        <form
            action="update.php"
            method="POST"
            enctype="multipart/form-data"
            class="bg-white rounded-2xl border border-slate-200 shadow-sm">

            <input
                type="hidden"
                name="id"
                value="<?= htmlspecialchars($id) ?>">

            <div class="p-6 space-y-6">

                <!-- NAMA -->

                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Nama Jenis Surat
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="<?= htmlspecialchars($name) ?>"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"
                        placeholder="Contoh: Surat Keterangan Domisili">

                </div>


                <!-- SLUG -->

                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Slug
                    </label>

                    <input
                        type="text"
                        name="slug"
                        value="<?= htmlspecialchars($slug) ?>"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"
                        placeholder="surat-keterangan-domisili">

                </div>


                <!-- ICON & COLOR -->

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Icon
                        </label>

                        <div class="relative">

                            <i class="bi <?= htmlspecialchars($icon) ?> absolute left-4 top-1/2 -translate-y-1/2 text-teal-600"></i>

                            <input
                                type="text"
                                name="icon"
                                value="<?= htmlspecialchars($icon) ?>"
                                class="w-full rounded-xl border border-slate-300 pl-11 pr-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"
                                placeholder="bi-file-earmark-text">

                        </div>

                    </div>


                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Warna
                        </label>

                        <input
                            type="text"
                            name="color"
                            value="<?= htmlspecialchars($color) ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"
                            placeholder="teal">

                    </div>

                </div>


                <!-- DESKRIPSI -->

                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Deskripsi
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"
                        placeholder="Jelaskan kegunaan surat ini..."><?= htmlspecialchars($description) ?></textarea>

                </div>


                <!-- TEMPLATE BODY -->

                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Template Surat
                    </label>

                    <textarea
                        name="template_body"
                        rows="12"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 font-mono text-sm outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"
                        placeholder="Isi template surat..."><?= htmlspecialchars($template_body) ?></textarea>

                    <p class="text-xs text-slate-500 mt-2">
                        Gunakan placeholder seperti
                        <code class="bg-slate-100 px-1.5 py-0.5 rounded">
                            {{nama}}
                        </code>,
                        <code class="bg-slate-100 px-1.5 py-0.5 rounded">
                            {{nik}}
                        </code>,
                        <code class="bg-slate-100 px-1.5 py-0.5 rounded">
                            {{alamat}}
                        </code>.
                    </p>

                </div>


                <!-- FILE TEMPLATE -->

                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Upload Template DOCX
                    </label>

                    <?php if ($file_path !== ''): ?>

                        <div class="mb-3 flex items-center gap-3 rounded-xl bg-teal-50 border border-teal-100 px-4 py-3">

                            <i class="bi bi-file-earmark-word text-xl text-teal-600"></i>

                            <div class="flex-1 min-w-0">

                                <p class="text-sm font-medium text-slate-700">
                                    Template saat ini
                                </p>

                                <p class="text-xs text-slate-500 truncate">
                                    <?= htmlspecialchars($file_path) ?>
                                </p>

                            </div>

                            <a
                                href="<?= APP_URL . htmlspecialchars($file_path) ?>"
                                target="_blank"
                                class="text-sm text-teal-600 hover:text-teal-700">

                                Lihat

                            </a>

                        </div>

                    <?php endif; ?>

                    <input
                        type="file"
                        name="template_file"
                        accept=".docx"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm">

                    <p class="text-xs text-slate-500 mt-2">
                        Kosongkan jika tidak ingin mengganti template DOCX.
                    </p>

                </div>


                <!-- PLACEHOLDER MAP -->

                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Placeholder Map
                    </label>

                    <textarea
                        name="placeholder_map"
                        rows="5"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 font-mono text-sm outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"
                        placeholder='{"nama":"name","nik":"nik","alamat":"address"}'><?= htmlspecialchars($placeholder_map) ?></textarea>

                    <p class="text-xs text-slate-500 mt-2">
                        Digunakan untuk menghubungkan placeholder template dengan data penduduk.
                    </p>

                </div>


                <!-- STATUS & SORT -->

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-white outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

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


                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Urutan
                        </label>

                        <input
                            type="number"
                            name="sort_order"
                            value="<?= htmlspecialchars($sort_order) ?>"
                            min="0"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                    </div>

                </div>

            </div>


            <!-- FOOTER -->

            <div class="border-t border-slate-200 px-6 py-5 flex items-center justify-end gap-3">

                <a
                    href="index.php"
                    class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">

                    Batal

                </a>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-teal-700">

                    <i class="bi bi-check-lg"></i>

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </div>

</body>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>