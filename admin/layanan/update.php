<?php

require_once '../../config/app.php';


// ======================================================
// Validasi Method
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location:index.php");
    exit;
}



// ======================================================
// Helper
// ======================================================

function clean($data)
{

    global $conn;

    return mysqli_real_escape_string(
        $conn,
        trim($data ?? '')
    );
}



function createSlug($text)
{

    $text = strtolower($text);

    $text = preg_replace(
        '/[^a-z0-9]+/',
        '-',
        $text
    );

    return trim($text, '-');
}





// ======================================================
// Ambil Input
// ======================================================


$id = (int) $_POST['id'];



$name = clean($_POST['name']);



$slug = clean($_POST['slug']);



// Jika slug kosong maka generate

if (empty($slug)) {

    $slug = createSlug($name);
}



$icon = clean($_POST['icon']);



$color = clean($_POST['color']);



$description = clean($_POST['description']);



$requirements = clean($_POST['requirements']);



$service_procedure = clean($_POST['service_procedure']);



$processing_time = clean($_POST['processing_time']);



$fee = clean($_POST['fee']);



$contact_person = clean($_POST['contact_person']);



$phone = clean($_POST['phone']);





// Link Online


$google_form_url = clean($_POST['google_form_url']);

$template_url = clean($_POST['template_url']);

$spreadsheet_url = clean($_POST['spreadsheet_url']);

$tracking_url = clean($_POST['tracking_url']);

$guide_url = clean($_POST['guide_url']);





// Setting


$has_google_form = clean($_POST['has_google_form']);

$has_template = clean($_POST['has_template']);

$has_tracking = clean($_POST['has_tracking']);

$status = clean($_POST['status']);



$sort_order = (int) $_POST['sort_order'];





// ======================================================
// User Login
// ======================================================

$updated_by = isset($_SESSION['user_id'])
    ? (int) $_SESSION['user_id']
    : "NULL";

// ======================================================
// Update Database
// ======================================================


$query = mysqli_query($conn, "
    
    UPDATE service_letters SET


        name='$name',

        slug='$slug',

        icon='$icon',

        color='$color',

        description='$description',

        requirements='$requirements',

        service_procedure='$service_procedure',

        processing_time='$processing_time',

        fee='$fee',

        contact_person='$contact_person',

        phone='$phone',

        google_form_url='$google_form_url',

        template_url='$template_url',

        spreadsheet_url='$spreadsheet_url',

        tracking_url='$tracking_url',

        guide_url='$guide_url',

        has_google_form='$has_google_form',

        has_template='$has_template',

        has_tracking='$has_tracking',

        status='$status',

        sort_order='$sort_order',

        updated_by=$updated_by


    WHERE id='$id'

");






// ======================================================
// Redirect
// ======================================================


if ($query) {


    echo "

    <script>

    alert('Layanan berhasil diperbarui');

    window.location='index.php';

    </script>

    ";
} else {


    echo "

    <script>

    alert('Gagal memperbarui layanan');

    history.back();

    </script>

    ";
}
