<?php
require_once "../../../config/app.php";

// ===============================
// Validate ID
// ===============================

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {

    $_SESSION["error"] = "Data kewilayahan tidak ditemukan.";

    header("Location: index.php");
    exit;
}

// ===============================
// Get Regional
// ===============================

$query = mysqli_query(
    $conn,
    "
    SELECT *
    FROM regionals
    WHERE id = {$id}
    LIMIT 1
    "
);

if (mysqli_num_rows($query) == 0) {

    $_SESSION["error"] = "Data kewilayahan tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// ===============================
// Layout
// ===============================

$title = "Detail Kewilayahan";
$page  = "kewilayahan";

include APP_PATH . "includes/admin/layout-top.php";
?>

<div class="p-8 space-y-8">
    <!-- HEADER -->
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-900">
                Detail Kewilayahan
            </h1>

            <p class="mt-2 text-slate-500">
                Informasi lengkap data kewilayahan desa.
            </p>

        </div>

        <div class="flex gap-3">

            <a
                href="index.php"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-300 px-5 py-3 font-medium text-slate-700 transition hover:bg-slate-100">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

            <a
                href="edit.php?id=<?= $data["id"] ?>"
                class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-5 py-3 font-medium text-white transition hover:bg-teal-700">

                <i class="bi bi-pencil"></i>

                Edit Data

            </a>

        </div>

    </div>


    <!-- PROFILE -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

        <div class="grid gap-8 p-8 lg:grid-cols-3">

            <!-- IMAGE -->
            <div>

                <?php if (!empty($data["image"])): ?>

                    <img
                        src="<?= APP_URL ?>uploads/village/regionals/<?= htmlspecialchars($data["image"]) ?>"
                        alt="<?= htmlspecialchars($data["title"]) ?>"
                        class="h-80 w-full rounded-2xl object-cover">

                <?php else: ?>

                    <div class="flex h-80 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">

                        <i class="bi bi-map text-6xl"></i>

                    </div>

                <?php endif; ?>

            </div>

            <!-- INFORMATION -->
            <div class="lg:col-span-2">

                <?php

                $categoryClass = "bg-slate-100 text-slate-700";

                switch ($data["category"]) {

                    case "Peta Administrasi":
                        $categoryClass = "bg-blue-100 text-blue-700";
                        break;

                    case "Peta RT/RW":
                        $categoryClass = "bg-emerald-100 text-emerald-700";
                        break;

                    case "Peta Blok SPPT":
                        $categoryClass = "bg-amber-100 text-amber-700";
                        break;

                    case "Peta Tata Guna Lahan":
                        $categoryClass = "bg-purple-100 text-purple-700";
                        break;

                    case "Peta Infrastruktur":
                        $categoryClass = "bg-cyan-100 text-cyan-700";
                        break;

                    case "Peta Potensi Desa":
                        $categoryClass = "bg-pink-100 text-pink-700";
                        break;
                }

                $statusClass = $data["status"] == "Published"
                    ? "bg-teal-100 text-teal-700"
                    : "bg-slate-100 text-slate-700";

                ?>

                <h2 class="text-3xl font-bold text-slate-900">

                    <?= htmlspecialchars($data["title"]) ?>

                </h2>

                <div class="mt-5 flex flex-wrap gap-3">

                    <span class="rounded-full px-4 py-2 text-sm font-semibold <?= $categoryClass ?>">

                        <?= htmlspecialchars($data["category"]) ?>

                    </span>

                    <span class="rounded-full px-4 py-2 text-sm font-semibold <?= $statusClass ?>">

                        <?= htmlspecialchars($data["status"]) ?>

                    </span>

                </div>

                <div class="mt-8 grid gap-6 md:grid-cols-2">

                    <div>

                        <p class="text-sm text-slate-500">
                            Tahun
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            <?= htmlspecialchars($data["year"] ?? "-") ?>
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Skala Peta
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            <?= htmlspecialchars($data["scale"] ?? "-") ?>
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Urutan Tampil
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            <?= (int) $data["sort_order"] ?>
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Slug
                        </p>

                        <p class="mt-1 break-all font-semibold text-slate-800">
                            <?= htmlspecialchars($data["slug"]) ?>
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- DESCRIPTION -->
    <div class="rounded-2xl border border-slate-200 bg-white p-8">

        <h2 class="mb-5 text-xl font-bold text-slate-900">
            Deskripsi
        </h2>

        <div class="leading-8 text-slate-600">

            <?= !empty($data["description"])
                ? nl2br(htmlspecialchars($data["description"]))
                : '<span class="italic text-slate-400">Belum ada deskripsi.</span>' ?>

        </div>

    </div>


    <!-- LOCATION -->
    <div class="grid gap-8 lg:grid-cols-2">

        <!-- Coordinate -->
        <div class="rounded-2xl border border-slate-200 bg-white p-8">

            <h2 class="mb-6 text-xl font-bold text-slate-900">
                Koordinat Lokasi
            </h2>

            <div class="space-y-6">

                <div>

                    <p class="text-sm text-slate-500">
                        Latitude
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">

                        <?= htmlspecialchars($data["latitude"] ?? "-") ?>

                    </p>

                </div>

                <div>

                    <p class="text-sm text-slate-500">
                        Longitude
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">

                        <?= htmlspecialchars($data["longitude"] ?? "-") ?>

                    </p>

                </div>

            </div>

        </div>

        <!-- Google Maps -->
        <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">

            <div class="border-b border-slate-200 px-8 py-6">

                <h2 class="text-xl font-bold text-slate-900">
                    Lokasi Peta
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Lokasi berdasarkan Google Maps.
                </p>

            </div>

            <?php if (!empty($data["google_maps"])): ?>

                <div class="aspect-video w-full">

                    <?= str_replace(
                        '<iframe',
                        '<iframe class="h-full w-full"',
                        $data["google_maps"]
                    ) ?>

                </div>

            <?php else: ?>

                <div class="flex h-72 items-center justify-center">

                    <div class="text-center text-slate-400">

                        <i class="bi bi-geo-alt text-5xl"></i>

                        <p class="mt-3">
                            Google Maps belum tersedia.
                        </p>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>
    <!-- FILE INFORMATION -->
    <div class="grid gap-8 lg:grid-cols-2">

        <!-- Supporting Document -->
        <div class="rounded-2xl border border-slate-200 bg-white p-8">

            <h2 class="mb-6 text-xl font-bold text-slate-900">
                Dokumen Pendukung
            </h2>

            <?php if (!empty($data["document"])): ?>

                <div class="flex items-center justify-between rounded-xl border border-slate-200 p-5">

                    <div class="flex items-center gap-4">

                        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-red-100 text-red-600">

                            <i class="bi bi-file-earmark-pdf text-2xl"></i>

                        </div>

                        <div>

                            <h3 class="font-semibold text-slate-900">
                                Dokumen Kewilayahan
                            </h3>

                            <p class="text-sm text-slate-500">
                                PDF
                            </p>

                        </div>

                    </div>

                    <a
                        href="<?= APP_URL ?>uploads/village/regionals/<?= htmlspecialchars($data["document"]) ?>"
                        target="_blank"
                        class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2 text-white transition hover:bg-teal-700">

                        <i class="bi bi-download"></i>

                        Lihat

                    </a>

                </div>

            <?php else: ?>

                <div class="flex h-40 items-center justify-center rounded-xl border border-dashed border-slate-300">

                    <div class="text-center text-slate-400">

                        <i class="bi bi-file-earmark-x text-5xl"></i>

                        <p class="mt-3">
                            Dokumen belum tersedia.
                        </p>

                    </div>

                </div>

            <?php endif; ?>

        </div>

        <!-- Metadata -->
        <div class="rounded-2xl border border-slate-200 bg-white p-8">

            <h2 class="mb-6 text-xl font-bold text-slate-900">
                Informasi Data
            </h2>

            <div class="space-y-6">

                <div>

                    <p class="text-sm text-slate-500">
                        Dibuat
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">

                        <?= !empty($data["created_at"])
                            ? date("d F Y H:i", strtotime($data["created_at"]))
                            : "-" ?>

                    </p>

                </div>

                <div>

                    <p class="text-sm text-slate-500">
                        Terakhir Diperbarui
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">

                        <?= !empty($data["updated_at"])
                            ? date("d F Y H:i", strtotime($data["updated_at"]))
                            : "-" ?>

                    </p>

                </div>

                <div>

                    <p class="text-sm text-slate-500">
                        ID Data
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">

                        #<?= (int) $data["id"] ?>

                    </p>

                </div>

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

        <a
            href="edit.php?id=<?= $data["id"] ?>"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-teal-600 px-6 py-3 font-medium text-white transition hover:bg-teal-700">

            <i class="bi bi-pencil-square"></i>

            Edit Data

        </a>

    </div>

</div>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>