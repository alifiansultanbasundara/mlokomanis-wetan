<?php

require_once '../config/app.php';

$page = "kontak";

$query = mysqli_query($conn, "
SELECT *
FROM village_profiles
LIMIT 1
");

$profile = mysqli_fetch_assoc($query);

include "../layouts/header.php";
include "../layouts/navbar.php";

?>

<form
    action="kontak-store.php"
    method="POST"
    class="space-y-5">

    <div>
        <label>Nama</label>
        <input
            type="text"
            name="name"
            required
            class="w-full rounded-xl border p-3">
    </div>

    <div>
        <label>Email</label>
        <input
            type="email"
            name="email"
            class="w-full rounded-xl border p-3">
    </div>

    <div>
        <label>No HP</label>
        <input
            type="text"
            name="phone"
            class="w-full rounded-xl border p-3">
    </div>

    <div>
        <label>Subjek</label>
        <input
            type="text"
            name="subject"
            class="w-full rounded-xl border p-3">
    </div>

    <div>
        <label>Pesan</label>

        <textarea
            name="message"
            rows="6"
            required
            class="w-full rounded-xl border p-3"></textarea>

    </div>

    <button
        class="rounded-xl bg-teal-600 px-6 py-3 font-semibold text-white hover:bg-teal-700">

        Kirim Pesan

    </button>

</form>