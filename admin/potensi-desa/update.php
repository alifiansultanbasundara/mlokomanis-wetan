<?php

require_once "../../config/app.php";

// ===============================
// Hanya POST
// ===============================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: index.php");
    exit;
}

// ===============================
// Helper
// ===============================

function clean($value)
{
    global $conn;

    return mysqli_real_escape_string(
        $conn,
        trim($value ?? "")
    );
}

function slugify($text)
{
    $text = strtolower(trim($text));

    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);

    return trim($text, '-');
}

// ===============================
// ID
// ===============================

$id = (int) ($_POST["id"] ?? 0);

if ($id <= 0) {

    $_SESSION["error"] = "Data tidak ditemukan.";

    header("Location: index.php");
    exit;
}

// ===============================
// Get Old Data
// ===============================

$oldQuery = mysqli_query(
    $conn,
    " SELECT image, brochure
        FROM village_potentials
        WHERE id={$id}
        LIMIT 1"
);

if (mysqli_num_rows($oldQuery) == 0) {

    $_SESSION["error"] = "Data tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$old = mysqli_fetch_assoc($oldQuery);

// ===============================
// Input
// ===============================

$title         = clean($_POST["title"]);
$slug          = slugify($title);
$category      = clean($_POST["category"]);
$description   = clean($_POST["description"]);

$owner_name    = clean($_POST["owner_name"]);
$organization  = clean($_POST["organization"]);

$phone         = clean($_POST["phone"]);
$email         = clean($_POST["email"]);

$address       = clean($_POST["address"]);

$website            = clean($_POST["website"]);
$whatsapp           = clean($_POST["whatsapp"]);

$latitude           = clean($_POST["latitude"]);
$longitude          = clean($_POST["longitude"]);
$google_maps        = clean($_POST["google_maps"]);

$established_year   = clean($_POST["established_year"]);
$operational_hours  = clean($_POST["operational_hours"]);
$price_range        = clean($_POST["price_range"]);
$facilities         = clean($_POST["facilities"]);

$sort_order         = (int) ($_POST["sort_order"] ?? 0);

$status             = clean($_POST["status"]);
$featured           = clean($_POST["featured"]);

// ===============================
// Validation
// ===============================

if (
    empty($title) ||
    empty($category)
) {

    $_SESSION["error"] = "Judul dan kategori wajib diisi.";

    header("Location: edit.php?id={$id}");
    exit;
}

// ===============================
// Upload Folder
// ===============================

$uploadDir = APP_PATH . "uploads/potentials/";

if (!is_dir($uploadDir)) {

    mkdir($uploadDir, 0777, true);
}

// ===============================
// Image
// ===============================

$image = $old["image"];

if (!empty($_FILES["image"]["name"])) {

    if (!empty($image) && file_exists($uploadDir . $image)) {

        unlink($uploadDir . $image);
    }

    $ext = strtolower(
        pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION)
    );

    $image = uniqid("potential_") . "." . $ext;

    move_uploaded_file(
        $_FILES["image"]["tmp_name"],
        $uploadDir . $image
    );
}

// ===============================
// Brochure
// ===============================

$brochure = $old["brochure"];

if (!empty($_FILES["brochure"]["name"])) {

    if (!empty($brochure) && file_exists($uploadDir . $brochure)) {

        unlink($uploadDir . $brochure);
    }

    $ext = strtolower(
        pathinfo($_FILES["brochure"]["name"], PATHINFO_EXTENSION)
    );

    $brochure = uniqid("brochure_") . "." . $ext;

    move_uploaded_file(
        $_FILES["brochure"]["tmp_name"],
        $uploadDir . $brochure
    );
}

// ===============================
// Update
// ===============================

$query = mysqli_query(
    $conn,
    "
    UPDATE village_potentials SET

    title               = '{$title}',
slug                = '{$slug}',
category            = '{$category}',
description         = '{$description}',

owner_name          = '{$owner_name}',
organization        = '{$organization}',

address             = '{$address}',
phone               = '{$phone}',
whatsapp            = '{$whatsapp}',
email               = '{$email}',
website             = '{$website}',

image               = '{$image}',
brochure            = '{$brochure}',

latitude            = '{$latitude}',
longitude           = '{$longitude}',
google_maps         = '{$google_maps}',

established_year    = " . (!empty($established_year) ? "'{$established_year}'" : "NULL") . ",
operational_hours   = '{$operational_hours}',
price_range         = '{$price_range}',
facilities          = '{$facilities}',

featured            = '{$featured}',
sort_order          = {$sort_order},
status              = '{$status}',

updated_by          = 1

    WHERE id={$id}
"
);

// ===============================
// Response
// ===============================

if ($query) {

    $_SESSION["success"] = "Potensi desa berhasil diperbarui.";
} else {

    $_SESSION["error"] = "Gagal memperbarui data.";
}

header("Location: index.php");
exit;
