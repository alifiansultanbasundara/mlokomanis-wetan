<?php

require_once '../../../config/app.php';

// =====================================
// Hanya menerima POST
// =====================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// =====================================
// Ambil Data
// =====================================

$slugOld = mysqli_real_escape_string(
    $conn,
    $_GET['slug']
);

$data = mysqli_query(
    $conn,
    "SELECT id
     FROM announcements
     WHERE slug='$slugOld'"
);

$row = mysqli_fetch_assoc($data);

$id = $row['id'];

$title   = mysqli_real_escape_string($conn, trim($_POST['title']));
$slug    = mysqli_real_escape_string($conn, strtolower(trim($_POST['slug'])));
$content = mysqli_real_escape_string($conn, trim($_POST['content']));

$type     = mysqli_real_escape_string($conn, $_POST['type']);
$icon     = mysqli_real_escape_string($conn, $_POST['icon']);
$color    = mysqli_real_escape_string($conn, $_POST['color']);

$priority = (int) ($_POST['priority'] ?? 0);

$start_date = !empty($_POST['start_date'])
    ? "'" . mysqli_real_escape_string($conn, $_POST['start_date']) . "'"
    : "NULL";

$end_date = !empty($_POST['end_date'])
    ? "'" . mysqli_real_escape_string($conn, $_POST['end_date']) . "'"
    : "NULL";

$is_popup = isset($_POST['is_popup'])
    ? (int) $_POST['is_popup']
    : 0;

$status = mysqli_real_escape_string($conn, $_POST['status']);

$updated_by = $_SESSION['id'];

// =====================================
// Validasi
// =====================================

if (
    $id <= 0 ||
    empty($title) ||
    empty($slug) ||
    empty($content)
) {

    $_SESSION['error'] = "Mohon lengkapi seluruh data yang wajib diisi.";

    header("Location: edit.php?id=$id");
    exit;
}

// =====================================
// Cek Data
// =====================================

$cekData = mysqli_query(
    $conn,
    "SELECT id FROM announcements WHERE id='$id'"
);

if (mysqli_num_rows($cekData) == 0) {

    $_SESSION['error'] = "Data pengumuman tidak ditemukan.";

    header("Location: index.php");
    exit;
}

// =====================================
// Validasi Type
// =====================================

$allowedType = [
    'Pengumuman',
    'Informasi',
    'Agenda',
    'Bansos',
    'Kesehatan',
    'Pendidikan',
    'Darurat'
];

if (!in_array($type, $allowedType)) {

    $_SESSION['error'] = "Jenis pengumuman tidak valid.";

    header("Location: edit.php?id=$id");
    exit;
}

// =====================================
// Validasi Status
// =====================================

$allowedStatus = [
    'Draft',
    'Published'
];

if (!in_array($status, $allowedStatus)) {

    $_SESSION['error'] = "Status tidak valid.";

    header("Location: edit.php?id=$id");
    exit;
}

// =====================================
// Validasi Popup
// =====================================

if (!in_array($is_popup, [0, 1])) {
    $is_popup = 0;
}

// =====================================
// Validasi Tanggal
// =====================================

if (
    $start_date != "NULL" &&
    $end_date != "NULL"
) {

    if (strtotime($_POST['start_date']) > strtotime($_POST['end_date'])) {

        $_SESSION['error'] = "Tanggal mulai tidak boleh melebihi tanggal selesai.";

        header("Location: edit.php?id=$id");
        exit;
    }
}

// =====================================
// Cek Slug (Selain dirinya sendiri)
// =====================================

$check = mysqli_query(
    $conn,
    "SELECT id
     FROM announcements
     WHERE slug='$slug'
     AND id != '$id'"
);

if (mysqli_num_rows($check) > 0) {

    $_SESSION['error'] = "Slug sudah digunakan.";

    header("Location: edit.php?id=$id");
    exit;
}

// =====================================
// Update Database
// =====================================

$sql = "
UPDATE announcements
SET
    title       = '$title',
    slug        = '$slug',
    content     = '$content',
    type        = '$type',
    icon        = '$icon',
    color       = '$color',
    priority    = '$priority',
    start_date  = $start_date,
    end_date    = $end_date,
    is_popup    = '$is_popup',
    status      = '$status',
    updated_by  = '$updated_by'
WHERE id = '$id'
";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Pengumuman berhasil diperbarui.";
} else {

    $_SESSION['error'] = "Gagal memperbarui pengumuman.";
}

header("Location: index.php");
exit;
