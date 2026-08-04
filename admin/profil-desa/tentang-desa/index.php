<?php
require_once "../../../config/app.php";

// ===============================
// Get Village Profile
// ===============================

$query = mysqli_query(
    $conn,
    "
    SELECT *
    FROM village_profiles
    LIMIT 1
",
);

$data = mysqli_fetch_assoc($query);

// Default jika belum ada data

if (!$data) {
    $data = [
        "id" => null,
        "village_name" => "",
        "village_head" => "",
        "office_photo" => "",
        "description" => "",
        "history" => "",
        "vision" => "",
        "mission" => "",
        "office_address" => "",

        "latitude" => "",
        "longitude" => "",
        "google_maps" => "",

        "total_areas" => 0,
        "total_hamlets" => 0,
        "total_rw" => 0,
        "total_rt" => 0,
        "total_population" => 0,

        "north_boundary" => "",
        "east_boundary" => "",
        "south_boundary" => "",
        "west_boundary" => "",
    ];
}

// ===============================
// Get Hamlets
// ===============================

$hamlets = mysqli_query(
    $conn,
    "

    SELECT *
    FROM hamlets
    ORDER BY id ASC

",
);

// =======================
// Layout
// =======================

$title = "Tentang Desa";
$page = "tentang-desa";

include APP_PATH . "includes/admin/layout-top.php";
?>

<div class="p-8 space-y-8">

    <!-- HEADER -->
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Tentang Desa
            </h1>

            <p class="mt-2 text-slate-500">
                Kelola informasi profil, sejarah, visi misi, dan wilayah desa.
            </p>
        </div>

        <a
            href="edit.php"
            class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-5 py-3 font-medium text-white transition hover:bg-teal-700">
            <i class="bi bi-pencil"></i>
            Edit Profil
        </a>

    </div>

    <?php if (isset($_SESSION["success"])): ?>
        <div class="rounded-xl border border-teal-200 bg-teal-50 px-5 py-4 text-teal-700">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= $_SESSION["success"] ?>
        </div>
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>


    <!-- PROFILE -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

        <div class="grid gap-8 p-8 lg:grid-cols-3">

            <!-- PHOTO -->
            <div>

                <?php if (!empty($data["office_photo"])): ?>

                    <img
                        src="<?= APP_URL ?>uploads/village/<?= htmlspecialchars($data["office_photo"]) ?>"
                        alt="<?= htmlspecialchars($data["village_name"]) ?>"
                        class="h-72 w-full rounded-2xl object-cover">

                <?php else: ?>

                    <div class="flex h-72 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <i class="bi bi-image text-5xl"></i>
                    </div>

                <?php endif; ?>

            </div>

            <!-- INFORMATION -->
            <div class="lg:col-span-2">

                <h2 class="text-3xl font-bold text-slate-900">
                    <?= htmlspecialchars($data["village_name"]) ?>
                </h2>

                <p class="mt-4 leading-8 text-slate-600">
                    <?= nl2br(htmlspecialchars($data["description"])) ?>
                </p>

                <div class="mt-6 grid gap-5 md:grid-cols-2">

                    <div>
                        <p class="text-sm text-slate-500">
                            Kepala Desa
                        </p>

                        <p class="font-semibold text-slate-800">
                            <?= htmlspecialchars($data["village_head"]) ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">
                            Alamat Kantor Desa
                        </p>

                        <p class="font-semibold text-slate-800">
                            <?= htmlspecialchars($data["office_address"]) ?>
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- STATISTICS -->
    <?php
    $statistics = [
        [
            "title" => "Wilayah",
            "value" => $data["total_areas"],
            "icon"  => "bi-map",
        ],
        [
            "title" => "Dusun",
            "value" => $data["total_hamlets"],
            "icon"  => "bi-house",
        ],
        [
            "title" => "RW",
            "value" => $data["total_rw"],
            "icon"  => "bi-diagram-3",
        ],
        [
            "title" => "RT",
            "value" => $data["total_rt"],
            "icon"  => "bi-people",
        ],
        [
            "title" => "Penduduk",
            "value" => number_format($data["total_population"]),
            "icon"  => "bi-person",
        ],
    ];
    ?>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-5">

        <?php foreach ($statistics as $item): ?>

            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="mb-4 text-3xl text-teal-600">
                    <i class="bi <?= $item["icon"] ?>"></i>
                </div>

                <h3 class="text-3xl font-bold text-slate-900">
                    <?= $item["value"] ?>
                </h3>

                <p class="mt-1 text-slate-500">
                    <?= $item["title"] ?>
                </p>

            </div>

        <?php endforeach; ?>

    </div>


    <!-- HISTORY -->
    <div class="rounded-2xl border border-slate-200 bg-white p-8">

        <h2 class="mb-5 text-xl font-bold text-slate-900">
            Sejarah Desa
        </h2>

        <div class="leading-8 text-slate-600">
            <?= nl2br(htmlspecialchars($data["history"])) ?>
        </div>

    </div>

    <!-- VISION & MISSION -->
    <div class="grid gap-8 lg:grid-cols-2">

        <div class="rounded-2xl border border-slate-200 bg-white p-8">

            <h2 class="mb-5 text-xl font-bold text-slate-900">
                Visi Desa
            </h2>

            <p class="leading-8 text-slate-600">
                <?= nl2br(htmlspecialchars($data["vision"])) ?>
            </p>

        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-8">

            <h2 class="mb-5 text-xl font-bold text-slate-900">
                Misi Desa
            </h2>

            <p class="leading-8 text-slate-600">
                <?= nl2br(htmlspecialchars($data["mission"])) ?>
            </p>

        </div>

    </div>

    <!-- GOOGLE MAPS -->
    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">

        <div class="border-b border-slate-200 px-8 py-6">
            <h2 class="text-xl font-bold text-slate-900">
                Lokasi Kantor Desa
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Peta lokasi Kantor Desa.
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

            <div class="flex h-72 items-center justify-center text-slate-400">
                <div class="text-center">
                    <i class="bi bi-geo-alt text-5xl"></i>
                    <p class="mt-3">
                        Lokasi Google Maps belum tersedia.
                    </p>
                </div>
            </div>

        <?php endif; ?>

    </div>


    <!-- BOUNDARY -->
    <?php
    $boundaries = [
        "Utara"   => $data["north_boundary"],
        "Timur"   => $data["east_boundary"],
        "Selatan" => $data["south_boundary"],
        "Barat"   => $data["west_boundary"],
    ];
    ?>

    <div class="rounded-2xl border border-slate-200 bg-white p-8">

        <h2 class="mb-6 text-xl font-bold text-slate-900">
            Batas Wilayah Desa
        </h2>

        <div class="grid gap-6 md:grid-cols-2">

            <?php foreach ($boundaries as $direction => $boundary): ?>

                <div>

                    <p class="text-sm text-slate-500">
                        <?= $direction ?>
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        <?= htmlspecialchars($boundary) ?>
                    </p>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>


<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>