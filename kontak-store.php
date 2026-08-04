<?php

require_once 'config/app.php';

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: kontak.php");
    exit;
}

function clean($value)
{
    global $conn;

    return mysqli_real_escape_string(
        $conn,
        trim($value ?? '')
    );
}

$name = clean($_POST['name']);
$email = clean($_POST['email']);
$phone = clean($_POST['phone']);
$subject = clean($_POST['subject']);
$message = clean($_POST['message']);

$ip = $_SERVER['REMOTE_ADDR'];

mysqli_query($conn, "
INSERT INTO contact_messages
(
name,
email,
phone,
subject,
message,
ip_address
)

VALUES
(
'$name',
'$email',
'$phone',
'$subject',
'$message',
'$ip'
)
");

$profile = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT whatsapp
FROM village_profiles
LIMIT 1
"));

$wa = preg_replace('/[^0-9]/', '', $profile['whatsapp']);

if (substr($wa, 0, 1) == "0") {
    $wa = "62" . substr($wa, 1);
}

$text =
    "Halo Admin Desa%0A%0A" .
    "*Nama:* " . $name . "%0A" .
    "*Email:* " . $email . "%0A" .
    "*HP:* " . $phone . "%0A" .
    "*Subjek:* " . $subject . "%0A%0A" .
    $message;

header("Location: https://wa.me/" . $wa . "?text=" . $text);
exit;
