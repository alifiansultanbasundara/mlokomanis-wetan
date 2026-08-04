<?php

require_once "../../config/app.php";

// ===============================
// Validate ID
// ===============================

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {

    $_SESSION["error"] = "Data potensi desa tidak ditemukan.";

    header("Location: index.php");
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

    $_SESSION["error"] = "Data potensi desa tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// ===============================
// Layout
// ===============================

$title = "Detail Potensi Desa";
$page  = "potensi-desa";

include APP_PATH . "includes/admin/layout-top.php";
?>

<div class="p-8 space-y-8">
    <!-- HEADER -->
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-900">
                Detail Potensi Desa
            </h1>

            <p class="mt-2 text-slate-500">
                Informasi lengkap mengenai potensi desa.
            </p>

        </div>

        <div class="flex gap-3">

            <a
                href="index.php"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 transition hover:bg-slate-50">

                <i class="bi bi-arrow-left"></i>
                Kembali

            </a>

            <a
                href="edit.php?id=<?= $data["id"] ?>"
                class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-5 py-3 font-medium text-white transition hover:bg-amber-600">

                <i class="bi bi-pencil-square"></i>
                Edit

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
                        src="<?= APP_URL ?>uploads/potentials/<?= htmlspecialchars($data["image"]) ?>"
                        alt="<?= htmlspecialchars($data["title"]) ?>"
                        class="h-80 w-full rounded-2xl object-cover">

                <?php else: ?>

                    <div class="flex h-80 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">

                        <i class="bi bi-image text-6xl"></i>

                    </div>

                <?php endif; ?>

            </div>

            <!-- INFORMATION -->
            <div class="space-y-6 lg:col-span-2">

                <div>

                    <div class="mb-3 flex flex-wrap gap-2">

                        <span class="rounded-full bg-teal-100 px-3 py-1 text-sm font-medium text-teal-700">
                            <?= htmlspecialchars($data["category"]) ?>
                        </span>

                        <span class="rounded-full <?= $data["status"] === "Published"
                                                        ? "bg-emerald-100 text-emerald-700"
                                                        : "bg-amber-100 text-amber-700" ?> px-3 py-1 text-sm font-medium">

                            <?= htmlspecialchars($data["status"]) ?>

                        </span>

                        <?php if ($data["featured"] === "Yes"): ?>

                            <span class="rounded-full bg-indigo-100 px-3 py-1 text-sm font-medium text-indigo-700">

                                <i class="bi bi-star-fill me-1"></i>
                                Unggulan

                            </span>

                        <?php endif; ?>

                    </div>

                    <h2 class="text-3xl font-bold text-slate-900">
                        <?= htmlspecialchars($data["title"]) ?>
                    </h2>

                </div>

                <div class="leading-8 text-slate-600">

                    <?= nl2br(htmlspecialchars($data["description"] ?? "-")) ?>

                </div>

            </div>

        </div>

    </div>
    <!-- INFORMATION -->
    <div class="grid gap-8 lg:grid-cols-3">

        <!-- CONTACT -->
        <div class="rounded-2xl border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="text-lg font-semibold text-slate-900">
                    Informasi Kontak
                </h2>

            </div>

            <div class="space-y-5 p-6">

                <div>
                    <p class="text-sm text-slate-500">
                        Pemilik
                    </p>
                    <p class="mt-1 font-semibold text-slate-800">
                        <?= htmlspecialchars($data["owner_name"] ?: "-") ?>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Organisasi
                    </p>
                    <p class="mt-1 font-semibold text-slate-800">
                        <?= htmlspecialchars($data["organization"] ?: "-") ?>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Telepon
                    </p>
                    <p class="mt-1 font-semibold text-slate-800">
                        <?= htmlspecialchars($data["phone"] ?: "-") ?>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        WhatsApp
                    </p>
                    <p class="mt-1 font-semibold text-slate-800">
                        <?= htmlspecialchars($data["whatsapp"] ?: "-") ?>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Email
                    </p>
                    <p class="mt-1 font-semibold text-slate-800">
                        <?= htmlspecialchars($data["email"] ?: "-") ?>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Website
                    </p>

                    <?php if (!empty($data["website"])): ?>

                        <a
                            href="<?= htmlspecialchars($data["website"]) ?>"
                            target="_blank"
                            class="mt-1 inline-flex text-teal-600 hover:underline">

                            <?= htmlspecialchars($data["website"]) ?>

                        </a>

                    <?php else: ?>

                        <p class="mt-1 font-semibold text-slate-800">-</p>

                    <?php endif; ?>

                </div>

            </div>

        </div>

        <!-- LOCATION -->
        <div class="rounded-2xl border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="text-lg font-semibold text-slate-900">
                    Lokasi
                </h2>

            </div>

            <div class="space-y-5 p-6">

                <div>
                    <p class="text-sm text-slate-500">
                        Alamat
                    </p>
                    <p class="mt-1 leading-7 text-slate-800">
                        <?= nl2br(htmlspecialchars($data["address"] ?: "-")) ?>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Latitude
                    </p>
                    <p class="mt-1 font-semibold text-slate-800">
                        <?= htmlspecialchars($data["latitude"] ?: "-") ?>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Longitude
                    </p>
                    <p class="mt-1 font-semibold text-slate-800">
                        <?= htmlspecialchars($data["longitude"] ?: "-") ?>
                    </p>
                </div>

            </div>

        </div>

        <!-- OPERATION -->
        <div class="rounded-2xl border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="text-lg font-semibold text-slate-900">
                    Informasi Operasional
                </h2>

            </div>

            <div class="space-y-5 p-6">

                <div>
                    <p class="text-sm text-slate-500">
                        Tahun Berdiri
                    </p>
                    <p class="mt-1 font-semibold text-slate-800">
                        <?= htmlspecialchars($data["established_year"] ?: "-") ?>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Jam Operasional
                    </p>
                    <p class="mt-1 font-semibold text-slate-800">
                        <?= htmlspecialchars($data["operational_hours"] ?: "-") ?>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Rentang Harga
                    </p>
                    <p class="mt-1 font-semibold text-slate-800">
                        <?= htmlspecialchars($data["price_range"] ?: "-") ?>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Dilihat
                    </p>
                    <p class="mt-1 font-semibold text-slate-800">
                        <?= number_format($data["views"]) ?> kali
                    </p>
                </div>

            </div>

        </div>

    </div>

    <!-- FACILITIES -->
    <div class="rounded-2xl border border-slate-200 bg-white">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-xl font-bold text-slate-900">
                Fasilitas
            </h2>

        </div>

        <div class="p-6 leading-8 text-slate-600">

            <?= !empty($data["facilities"])
                ? nl2br(htmlspecialchars($data["facilities"]))
                : '<span class="text-slate-400">Belum ada informasi fasilitas.</span>' ?>

        </div>

    </div>


    <!-- GOOGLE MAPS -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-xl font-bold text-slate-900">
                Lokasi Potensi Desa
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Titik lokasi potensi desa pada Google Maps.
            </p>

        </div>

        <?php if (!empty($data["google_maps"])): ?>

            <div class="aspect-video w-full">

                <?= str_replace(
                    "<iframe",
                    '<iframe class="h-full w-full"',
                    $data["google_maps"]
                ) ?>

            </div>

        <?php else: ?>

            <div class="flex h-72 items-center justify-center">

                <div class="text-center text-slate-400">

                    <i class="bi bi-geo-alt text-5xl"></i>

                    <p class="mt-3">
                        Lokasi Google Maps belum tersedia.
                    </p>

                </div>

            </div>

        <?php endif; ?>

    </div>


    <!-- BROCHURE -->
    <div class="rounded-2xl border border-slate-200 bg-white">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-xl font-bold text-slate-900">
                Dokumen & Brosur
            </h2>

        </div>

        <div class="p-6">

            <?php if (!empty($data["brochure"])): ?>

                <a
                    href="<?= APP_URL ?>uploads/potentials/<?= htmlspecialchars($data["brochure"]) ?>"
                    target="_blank"
                    class="inline-flex items-center gap-3 rounded-xl bg-red-50 px-5 py-4 text-red-700 transition hover:bg-red-100">

                    <i class="bi bi-file-earmark-pdf text-2xl"></i>

                    <div>

                        <p class="font-semibold">
                            Lihat Brosur
                        </p>

                        <p class="text-sm text-red-600">
                            Klik untuk membuka dokumen PDF.
                        </p>

                    </div>

                </a>

            <?php else: ?>

                <div class="flex h-40 items-center justify-center rounded-xl border-2 border-dashed border-slate-200 text-slate-400">

                    <div class="text-center">

                        <i class="bi bi-file-earmark-pdf text-5xl"></i>

                        <p class="mt-3">
                            Brosur belum tersedia.
                        </p>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>