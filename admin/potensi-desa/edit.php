<?php
require_once '../../config/app.php';

// ===============================
// Validate ID
// ===============================

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {

    $_SESSION['error'] = 'Data potensi desa tidak ditemukan.';

    header('Location: index.php');
    exit;
}

// ===============================
// Get Data
// ===============================

$query = mysqli_query(
    $conn,
    "
    SELECT *
    FROM village_potentials
    WHERE id = {$id}
    LIMIT 1
    "
);

if (mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = 'Data potensi desa tidak ditemukan.';

    header('Location: index.php');
    exit;
}

$data = mysqli_fetch_assoc($query);

// ===============================
// Layout
// ===============================

$title = 'Edit Potensi Desa';
$page  = 'potensi-desa';

include APP_PATH . 'includes/admin/layout-top.php';
?>

<main class="p-8 space-y-8">
    <!-- HEADER -->
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-900">
                Edit Potensi Desa
            </h1>

            <p class="mt-2 text-slate-500">
                Perbarui informasi potensi desa, UMKM, wisata, maupun potensi unggulan lainnya.
            </p>

        </div>

        <a
            href="index.php"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 px-5 py-3 font-medium text-slate-700 transition hover:bg-slate-100">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>


    <!-- FORM -->
    <form
        action="update.php"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-8">

        <input
            type="hidden"
            name="id"
            value="<?= $data["id"] ?>">

        <!-- INFORMASI DASAR -->
        <div class="rounded-2xl border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="font-semibold text-slate-900">
                    Informasi Dasar
                </h2>

            </div>

            <div class="grid gap-6 p-6 md:grid-cols-2">

                <!-- Judul -->
                <div class="md:col-span-2">

                    <label class="mb-2 block font-medium">
                        Judul Potensi <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="title"
                        required
                        value="<?= htmlspecialchars($data["title"]) ?>"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">

                </div>

                <!-- Slug -->
                <div>

                    <label class="mb-2 block font-medium">
                        Slug
                    </label>

                    <input
                        type="text"
                        name="slug"
                        value="<?= htmlspecialchars($data["slug"]) ?>"
                        class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3"
                        readonly>

                </div>

                <!-- Kategori -->
                <div>

                    <label class="mb-2 block font-medium">
                        Kategori <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="category"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                        <?php

                        $categories = [
                            "UMKM",
                            "Pertanian",
                            "Perkebunan",
                            "Peternakan",
                            "Perikanan",
                            "Pariwisata",
                            "Kerajinan",
                            "Industri Rumah Tangga",
                            "Kuliner",
                            "Jasa",
                            "BUMDes",
                            "Sumber Daya Alam",
                            "Energi",
                            "Ekonomi Kreatif",
                            "Lainnya",
                        ];

                        foreach ($categories as $item):

                        ?>

                            <option
                                value="<?= $item ?>"
                                <?= $data["category"] == $item ? "selected" : "" ?>>

                                <?= $item ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Status -->
                <div>

                    <label class="mb-2 block font-medium">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                        <option
                            value="Published"
                            <?= $data["status"] == "Published" ? "selected" : "" ?>>

                            Published

                        </option>

                        <option
                            value="Draft"
                            <?= $data["status"] == "Draft" ? "selected" : "" ?>>

                            Draft

                        </option>

                    </select>

                </div>

                <!-- Featured -->
                <div>

                    <label class="mb-2 block font-medium">
                        Featured
                    </label>

                    <select
                        name="featured"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                        <option
                            value="Yes"
                            <?= $data["featured"] == "Yes" ? "selected" : "" ?>>

                            Ya

                        </option>

                        <option
                            value="No"
                            <?= $data["featured"] == "No" ? "selected" : "" ?>>

                            Tidak

                        </option>

                    </select>

                </div>

            </div>

        </div>

        <!-- DESKRIPSI -->
        <div class="rounded-2xl border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="font-semibold text-slate-900">
                    Deskripsi Potensi
                </h2>
            </div>

            <div class="p-6">

                <textarea
                    name="description"
                    rows="8"
                    placeholder="Tuliskan deskripsi potensi desa..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500"><?= htmlspecialchars($data["description"] ?? "") ?></textarea>

            </div>

        </div>


        <!-- INFORMASI PEMILIK -->
        <div class="rounded-2xl border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="font-semibold text-slate-900">
                    Informasi Pemilik
                </h2>

            </div>

            <div class="grid gap-6 p-6 md:grid-cols-2">

                <div>

                    <label class="mb-2 block font-medium">
                        Nama Pemilik
                    </label>

                    <input
                        type="text"
                        name="owner_name"
                        value="<?= htmlspecialchars($data["owner_name"] ?? "") ?>"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

                <div>

                    <label class="mb-2 block font-medium">
                        Nama Organisasi / Kelompok
                    </label>

                    <input
                        type="text"
                        name="organization"
                        value="<?= htmlspecialchars($data["organization"] ?? "") ?>"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

                <div>

                    <label class="mb-2 block font-medium">
                        Nomor Telepon
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="<?= htmlspecialchars($data["phone"] ?? "") ?>"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

                <div>

                    <label class="mb-2 block font-medium">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="<?= htmlspecialchars($data["email"] ?? "") ?>"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

            </div>

        </div>


        <!-- LOKASI -->
        <div class="rounded-2xl border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="font-semibold text-slate-900">
                    Lokasi
                </h2>

            </div>

            <div class="grid gap-6 p-6 md:grid-cols-2">

                <!-- Alamat -->
                <div class="md:col-span-2">

                    <label class="mb-2 block font-medium">
                        Alamat
                    </label>

                    <textarea
                        name="address"
                        rows="3"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3"><?= htmlspecialchars($data["address"] ?? "") ?></textarea>

                </div>

                <!-- Website -->
                <div>

                    <label class="mb-2 block font-medium">
                        Website
                    </label>

                    <input
                        type="url"
                        name="website"
                        value="<?= htmlspecialchars($data["website"] ?? "") ?>"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

                <!-- WhatsApp -->
                <div>

                    <label class="mb-2 block font-medium">
                        WhatsApp
                    </label>

                    <input
                        type="text"
                        name="whatsapp"
                        value="<?= htmlspecialchars($data["whatsapp"] ?? "") ?>"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

                <!-- Latitude -->
                <div>

                    <label class="mb-2 block font-medium">
                        Latitude
                    </label>

                    <input
                        type="text"
                        name="latitude"
                        value="<?= htmlspecialchars($data["latitude"] ?? "") ?>"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

                <!-- Longitude -->
                <div>

                    <label class="mb-2 block font-medium">
                        Longitude
                    </label>

                    <input
                        type="text"
                        name="longitude"
                        value="<?= htmlspecialchars($data["longitude"] ?? "") ?>"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

                <!-- Google Maps -->
                <div class="md:col-span-2">

                    <label class="mb-2 block font-medium">
                        Google Maps (Embed)
                    </label>

                    <textarea
                        name="google_maps"
                        rows="4"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3"><?= htmlspecialchars($data["google_maps"] ?? "") ?></textarea>

                </div>

                <!-- Tahun Berdiri -->
                <div>

                    <label class="mb-2 block font-medium">
                        Tahun Berdiri
                    </label>

                    <input
                        type="number"
                        name="established_year"
                        min="1900"
                        max="<?= date("Y") ?>"
                        value="<?= htmlspecialchars($data["established_year"] ?? "") ?>"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

                <!-- Jam Operasional -->
                <div>

                    <label class="mb-2 block font-medium">
                        Jam Operasional
                    </label>

                    <input
                        type="text"
                        name="operational_hours"
                        value="<?= htmlspecialchars($data["operational_hours"] ?? "") ?>"
                        placeholder="08.00 - 16.00"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

                <!-- Rentang Harga -->
                <div>

                    <label class="mb-2 block font-medium">
                        Rentang Harga
                    </label>

                    <input
                        type="text"
                        name="price_range"
                        value="<?= htmlspecialchars($data["price_range"] ?? "") ?>"
                        placeholder="Rp10.000 - Rp50.000"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

                <!-- Urutan -->
                <div>

                    <label class="mb-2 block font-medium">
                        Urutan Tampil
                    </label>

                    <input
                        type="number"
                        name="sort_order"
                        value="<?= (int) ($data["sort_order"] ?? 0) ?>"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

            </div>

            <!-- Fasilitas -->
            <div class="border-t border-slate-200 p-6">

                <label class="mb-2 block font-medium">
                    Fasilitas
                </label>

                <textarea
                    name="facilities"
                    rows="5"
                    placeholder="Contoh: Parkir, Mushola, Toilet, WiFi, Gazebo, dll."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3"><?= htmlspecialchars($data["facilities"] ?? "") ?></textarea>

            </div>
        </div>
        <!-- FILE -->
        <div class="rounded-2xl border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="font-semibold text-slate-900">
                    Gambar & Dokumen
                </h2>

            </div>

            <div class="grid gap-8 p-6 lg:grid-cols-2">

                <!-- Image -->
                <div>

                    <label class="mb-2 block font-medium">
                        Gambar Utama
                    </label>

                    <?php if (!empty($data["image"])): ?>

                        <img
                            src="<?= APP_URL ?>uploads/potentials/<?= htmlspecialchars($data["image"]) ?>"
                            alt="<?= htmlspecialchars($data["title"]) ?>"
                            class="mb-4 h-56 w-full rounded-xl border object-cover">

                    <?php endif; ?>

                    <input
                        type="file"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    <p class="mt-2 text-xs text-slate-500">
                        JPG, JPEG, PNG atau WEBP (maks. 2 MB).
                    </p>

                </div>

                <!-- Brochure -->
                <div>

                    <label class="mb-2 block font-medium">
                        Dokumen Pendukung
                    </label>

                    <?php if (!empty($data["brochure"])): ?>

                        <a
                            href="<?= APP_URL ?>uploads/potentials/<?= htmlspecialchars($data["brochure"]) ?>"
                            target="_blank"
                            class="mb-4 inline-flex items-center gap-2 rounded-lg bg-red-50 px-4 py-3 text-red-700 hover:bg-red-100">

                            <i class="bi bi-file-earmark-pdf"></i>

                            Lihat Brosur Saat Ini

                        </a>

                    <?php endif; ?>

                    <input
                        type="file"
                        name="brochure"
                        accept=".pdf"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    <p class="mt-2 text-xs text-slate-500">
                        PDF (maks. 10 MB).
                    </p>

                </div>

            </div>

        </div>

        <!-- ACTION -->
        <div class="flex flex-col gap-3 border-t border-slate-200 pt-8 sm:flex-row">

            <a
                href="index.php"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 px-6 py-3 font-medium text-slate-700 transition hover:bg-slate-100">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-teal-600 px-6 py-3 font-medium text-white transition hover:bg-teal-700">

                <i class="bi bi-check-circle"></i>

                Update Data

            </button>

        </div>

    </form>

    </div>

    <?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>
</main>