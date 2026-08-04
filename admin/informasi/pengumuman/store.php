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

$title       = mysqli_real_escape_string($conn, trim($_POST['title']));
$slug        = mysqli_real_escape_string($conn, strtolower(trim($_POST['slug'])));
$content     = mysqli_real_escape_string($conn, trim($_POST['content']));

$type        = mysqli_real_escape_string($conn, $_POST['type']);
$icon        = mysqli_real_escape_string($conn, $_POST['icon']);
$color       = mysqli_real_escape_string($conn, $_POST['color']);

$priority    = (int) ($_POST['priority'] ?? 0);

$start_date  = !empty($_POST['start_date'])
    ? "'" . mysqli_real_escape_string($conn, $_POST['start_date']) . "'"
    : "NULL";

$end_date = !empty($_POST['end_date'])
    ? "'" . mysqli_real_escape_string($conn, $_POST['end_date']) . "'"
    : "NULL";

$is_popup = isset($_POST['is_popup'])
    ? (int) $_POST['is_popup']
    : 0;

$status = mysqli_real_escape_string($conn, $_POST['status']);

$created_by = $_SESSION['id'];

// =====================================
// Validasi
// =====================================

if (
    empty($title) ||
    empty($slug) ||
    empty($content)
) {

    $_SESSION['error'] = "Mohon lengkapi seluruh data yang wajib diisi.";

    header("Location: create.php");
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

    header("Location: create.php");
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

    header("Location: create.php");
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

    if (strtotime(trim($_POST['start_date'])) > strtotime(trim($_POST['end_date']))) {

        $_SESSION['error'] = "Tanggal mulai tidak boleh melebihi tanggal selesai.";

        header("Location: create.php");
        exit;
    }
}

// =====================================
// Cek Slug
// =====================================

$check = mysqli_query(
    $conn,
    "SELECT id FROM announcements WHERE slug='$slug'"
);

if (mysqli_num_rows($check) > 0) {

    $_SESSION['error'] = "Slug sudah digunakan.";

    header("Location: create.php");
    exit;
}

// =====================================
// Simpan Database
// =====================================

$sql = "
INSERT INTO announcements
(
    title,
    slug,
    content,
    type,
    icon,
    color,
    priority,
    start_date,
    end_date,
    is_popup,
    status,
    created_by
)
VALUES
(
    '$title',
    '$slug',
    '$content',
    '$type',
    '$icon',
    '$color',
    '$priority',
    $start_date,
    $end_date,
    '$is_popup',
    '$status',
    '$created_by'
)
";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Pengumuman berhasil ditambahkan.";
} else {

    $_SESSION['error'] = "Gagal menambahkan pengumuman.";
}

header("Location: index.php");
exit;
