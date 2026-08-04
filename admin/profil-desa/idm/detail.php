<?php
require_once "../../../config/app.php";

// ===============================
// Validate ID
// ===============================

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {

    $_SESSION["error"] = "Data IDM tidak ditemukan.";

    header("Location: index.php");
    exit;
}

// ===============================
// Get IDM
// ===============================

$query = mysqli_query(
    $conn,
    "
    SELECT *
    FROM idms
    WHERE id = {$id}
    LIMIT 1
"
);

if (mysqli_num_rows($query) == 0) {

    $_SESSION["error"] = "Data IDM tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// ===============================
// Layout
// ===============================

$title = "Detail Indeks Desa Membangun";
$page  = "idm";

include APP_PATH . "includes/admin/layout-top.php";
?>

<div class="p-8 space-y-8">

    <!-- HEADER -->
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Indeks Desa Membangun
            </h1>

            <p class="mt-2 text-slate-500">
                Informasi capaian Indeks Desa Membangun (IDM).
            </p>
        </div>

        <a
            href="edit.php"
            class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-5 py-3 text-white hover:bg-teal-700">

            <i class="bi bi-pencil"></i>
            Edit IDM

        </a>

    </div>

    <?php if (isset($_SESSION["success"])): ?>

        <div class="rounded-xl border border-teal-200 bg-teal-50 px-5 py-4 text-teal-700">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= $_SESSION["success"] ?>
        </div>

        <?php unset($_SESSION["success"]); ?>

    <?php endif; ?>


    <!-- CARD -->
    <div class="rounded-2xl border border-slate-200 bg-white p-8">

        <h2 class="text-2xl font-bold text-slate-900">
            <?= htmlspecialchars($data["title"]) ?>
        </h2>

        <div class="mt-5 grid gap-5 md:grid-cols-3">

            <div>
                <p class="text-sm text-slate-500">
                    Tahun
                </p>

                <p class="text-lg font-semibold text-slate-800">
                    <?= htmlspecialchars($data["year"]) ?>
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">
                    Status IDM
                </p>

                <span class="inline-block rounded-full bg-teal-100 px-3 py-1 text-sm font-medium text-teal-700">
                    <?= htmlspecialchars($data["status_idm"]) ?>
                </span>
            </div>

            <div>
                <p class="text-sm text-slate-500">
                    Nilai IDM
                </p>

                <p class="text-3xl font-bold text-teal-600">
                    <?= number_format($data["idm_score"], 4) ?>
                </p>
            </div>

        </div>

    </div>


    <!-- SCORE -->
    <div class="grid gap-5 md:grid-cols-4">

        <?php
        $scores = [
            [
                "title" => "Sosial",
                "value" => $data["social_score"],
                "icon" => "bi-people",
            ],
            [
                "title" => "Ekonomi",
                "value" => $data["economic_score"],
                "icon" => "bi-cash-stack",
            ],
            [
                "title" => "Lingkungan",
                "value" => $data["environmental_score"],
                "icon" => "bi-tree",
            ],
            [
                "title" => "Target",
                "value" => $data["target_score"],
                "icon" => "bi-bullseye",
            ],
        ];

        foreach ($scores as $item):
        ?>

            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="mb-3 text-3xl text-teal-600">
                    <i class="bi <?= $item["icon"] ?>"></i>
                </div>

                <h3 class="text-2xl font-bold text-slate-900">
                    <?= number_format($item["value"], 4) ?>
                </h3>

                <p class="mt-1 text-slate-500">
                    <?= $item["title"] ?>
                </p>

            </div>

        <?php endforeach; ?>

    </div>


    <!-- RANKING -->
    <div class="grid gap-5 md:grid-cols-3">

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <p class="text-sm text-slate-500">Ranking Kabupaten</p>
            <h3 class="mt-2 text-3xl font-bold text-slate-900">
                <?= $data["ranking_regency"] ?: "-" ?>
            </h3>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <p class="text-sm text-slate-500">Ranking Provinsi</p>
            <h3 class="mt-2 text-3xl font-bold text-slate-900">
                <?= $data["ranking_province"] ?: "-" ?>
            </h3>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <p class="text-sm text-slate-500">Ranking Nasional</p>
            <h3 class="mt-2 text-3xl font-bold text-slate-900">
                <?= $data["ranking_national"] ?: "-" ?>
            </h3>
        </div>

    </div>


    <!-- DESCRIPTION -->
    <div class="rounded-2xl border border-slate-200 bg-white p-8">

        <h2 class="mb-5 text-xl font-bold text-slate-900">
            Deskripsi
        </h2>

        <div class="leading-8 text-slate-600">
            <?= nl2br(htmlspecialchars($data["description"])) ?>
        </div>

    </div>


    <!-- ANALISIS -->
    <div class="grid gap-6 lg:grid-cols-3">

        <div class="rounded-2xl border border-slate-200 bg-white p-6">

            <h3 class="mb-4 text-lg font-bold text-slate-900">
                Kekuatan
            </h3>

            <div class="leading-7 text-slate-600">
                <?= nl2br(htmlspecialchars($data["strengths"] ?? "")) ?>
            </div>

        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">

            <h3 class="mb-4 text-lg font-bold text-slate-900">
                Kelemahan
            </h3>

            <div class="leading-7 text-slate-600">
                <?= nl2br(htmlspecialchars($data["weaknesses"] ?? "")) ?>
            </div>

        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">

            <h3 class="mb-4 text-lg font-bold text-slate-900">
                Rekomendasi
            </h3>

            <div class="leading-7 text-slate-600">
                <?= nl2br(htmlspecialchars($data["recommendation"] ?? "")) ?>
            </div>

        </div>

    </div>

</div>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>