<?php

include "../../auth/auth.php";
include "../../config/database.php";

// ======================================
// Validasi ID
// ======================================

if (!isset($_GET['id'])) {

    header("Location: index.php");
    exit;

}

$id = (int) $_GET['id'];

// ======================================
// Ambil Data
// ======================================

$query = mysqli_query($conn, "

SELECT *

FROM jenis_surat

WHERE id='$id'

");

if(mysqli_num_rows($query)==0){

    $_SESSION['success']="Data jenis surat tidak ditemukan.";

    header("Location:index.php");
    exit;

}

$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Detail Jenis Surat</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body class="bg-light">

<div class="container py-5">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3 class="mb-0">

Detail Jenis Surat

</h3>

</div>

<div class="card-body">

<h4 class="fw-bold">

<?= htmlspecialchars($data['icon']); ?>

<?= htmlspecialchars($data['nama']); ?>

</h4>

<hr>

<div class="row">

<div class="col-md-6">

<table class="table table-borderless">

<tr>

<th width="180">

Nama Surat

</th>

<td>

<?= htmlspecialchars($data['nama']); ?>

</td>

</tr>

<tr>

<th>

Slug

</th>

<td>

<?= htmlspecialchars($data['slug']); ?>

</td>

</tr>

<tr>

<th>

Estimasi

</th>

<td>

<?= $data['estimasi_hari']; ?> Hari

</td>

</tr>

<tr>

<th>

Urutan

</th>

<td>

<?= $data['urutan']; ?>

</td>

</tr>

</table>

</div>

<div class="col-md-6">

<table class="table table-borderless">

<tr>

<th width="180">

Status

</th>

<td>

<?php if($data['is_active']){ ?>

<span class="badge bg-success">

Aktif

</span>

<?php }else{ ?>

<span class="badge bg-secondary">

Nonaktif

</span>

<?php } ?>

</td>

</tr>

<tr>

<th>

Google Form

</th>

<td>

<?php

if(!empty($data['google_form'])){

?>

<a
href="<?= htmlspecialchars($data['google_form']); ?>"
target="_blank">

Buka Google Form

</a>

<?php

}else{

?>

<span class="text-muted">

Belum tersedia

</span>

<?php

}

?>

</td>

</tr>

<tr>

<th>

Dibuat

</th>

<td>

<?= !empty($data['created_at'])
? date("d F Y H:i", strtotime($data['created_at']))
: "-"; ?>

</td>

</tr>

<tr>

<th>

Diupdate

</th>

<td>

<?= !empty($data['updated_at'])
? date("d F Y H:i", strtotime($data['updated_at']))
: "-"; ?>

</td>

</tr>

</table>

</div>

</div>

<hr>

<h5>

Persyaratan

</h5>

<div style="line-height:1.8">

<?php

if(!empty($data['persyaratan'])){

    echo nl2br(htmlspecialchars($data['persyaratan']));

}else{

    echo "<span class='text-muted'>Belum ada persyaratan.</span>";

}

?>

</div>

<hr>

<h5>

Deskripsi

</h5>

<div style="line-height:1.8">

<?php

if(!empty($data['deskripsi'])){

    echo nl2br(htmlspecialchars($data['deskripsi']));

}else{

    echo "<span class='text-muted'>Belum ada deskripsi.</span>";

}

?>

</div>

<hr>

<div class="d-flex justify-content-between">

<a
href="index.php"
class="btn btn-secondary">

Kembali

</a>

<div>

<a
href="edit.php?id=<?= $data['id']; ?>"
class="btn btn-warning">

Edit

</a>

<a
href="delete.php?id=<?= $data['id']; ?>"
class="btn btn-danger"
onclick="return confirm('Yakin ingin menghapus jenis surat ini?')">

Hapus

</a>

</div>

</div>

</div>

</div>

</div>

</body>

</html>