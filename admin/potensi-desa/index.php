<?php
require_once "../../config/app.php";

// ===============================
// Filter & Pagination
// ===============================

$limit = 10;

$pageNumber = max(1, (int) ($_GET["page"] ?? 1));

$offset = ($pageNumber - 1) * $limit;

$keyword  = trim($_GET["keyword"] ?? "");
$category = trim($_GET["category"] ?? "");
$featured = trim($_GET["featured"] ?? "");
$status   = trim($_GET["status"] ?? "");

// ===============================
// Build WHERE
// ===============================

$where = "WHERE 1=1";

if ($keyword !== "") {

    $keyword = mysqli_real_escape_string($conn, $keyword);

    $where .= "
        AND (
            title LIKE '%{$keyword}%'
            OR owner_name LIKE '%{$keyword}%'
            OR organization LIKE '%{$keyword}%'
            OR category LIKE '%{$keyword}%'
        )
    ";
}

if ($category !== "") {

    $category = mysqli_real_escape_string($conn, $category);

    $where .= " AND category='{$category}'";
}

if ($featured !== "") {

    $featured = mysqli_real_escape_string($conn, $featured);

    $where .= " AND featured='{$featured}'";
}

if ($status !== "") {

    $status = mysqli_real_escape_string($conn, $status);

    $where .= " AND status='{$status}'";
}

// ===============================
// Total Data
// ===============================

$totalQuery = mysqli_query(
    $conn,
    "
    SELECT COUNT(*) AS total
    FROM village_potentials
    {$where}
"
);

$totalData = mysqli_fetch_assoc($totalQuery)["total"];

$totalPages = max(1, ceil($totalData / $limit));

// ===============================
// Get Data
// ===============================

$query = mysqli_query(
    $conn,
    "
    SELECT *
    FROM village_potentials
    {$where}
    ORDER BY
        featured DESC,
        created_at DESC,
        id DESC
    LIMIT {$limit}
    OFFSET {$offset}
"
);

// ===============================
// Helper
// ===============================

$startNumber = $totalData > 0
    ? ($offset + 1)
    : 0;

$endNumber = min($offset + $limit, $totalData);

// ===============================
// Layout
// ===============================

$title = "Potensi Desa";
$page  = "potensi-desa";

include APP_PATH . "includes/admin/layout-top.php";
?>

<div class="p-8 space-y-8">
    <!-- HEADER -->
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-900">
                Potensi Desa
            </h1>

            <p class="mt-2 text-slate-500">
                Kelola data potensi desa seperti UMKM, wisata, pertanian, peternakan, dan potensi lainnya.
            </p>

        </div>

        <a
            href="create.php"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-teal-600 px-5 py-3 font-medium text-white transition hover:bg-teal-700">

            <i class="bi bi-plus-circle"></i>

            Tambah Data

        </a>

    </div>


    <!-- SUCCESS -->
    <?php if (isset($_SESSION["success"])): ?>

        <div class="rounded-xl border border-teal-200 bg-teal-50 px-5 py-4 text-teal-700">

            <i class="bi bi-check-circle-fill me-2"></i>

            <?= $_SESSION["success"] ?>

        </div>

        <?php unset($_SESSION["success"]); ?>

    <?php endif; ?>


    <!-- FILTER -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6">

        <form
            method="GET"
            class="grid gap-4 lg:grid-cols-12">

            <!-- Keyword -->
            <div class="lg:col-span-4">

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Pencarian
                </label>

                <input
                    type="text"
                    name="keyword"
                    value="<?= htmlspecialchars($keyword) ?>"
                    placeholder="Cari judul, pemilik atau organisasi..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100">

            </div>

            <!-- Category -->
            <div class="lg:col-span-3">

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Kategori
                </label>

                <select
                    name="category"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100">

                    <option value="">
                        Semua Kategori
                    </option>

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
                            <?= $category == $item ? "selected" : "" ?>>

                            <?= $item ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <!-- Featured -->
            <div class="lg:col-span-2">

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Featured
                </label>

                <select
                    name="featured"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100">

                    <option value="">
                        Semua
                    </option>

                    <option
                        value="Yes"
                        <?= $featured == "Yes" ? "selected" : "" ?>>

                        Ya

                    </option>

                    <option
                        value="No"
                        <?= $featured == "No" ? "selected" : "" ?>>

                        Tidak

                    </option>

                </select>

            </div>

            <!-- Status -->
            <div class="lg:col-span-2">

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100">

                    <option value="">
                        Semua
                    </option>

                    <option
                        value="Published"
                        <?= $status == "Published" ? "selected" : "" ?>>

                        Published

                    </option>

                    <option
                        value="Draft"
                        <?= $status == "Draft" ? "selected" : "" ?>>

                        Draft

                    </option>

                </select>

            </div>

            <!-- Button -->
            <div class="flex items-end gap-3 lg:col-span-1">

                <button
                    type="submit"
                    class="inline-flex flex-1 items-center justify-center rounded-xl bg-teal-600 px-4 py-3 text-white transition hover:bg-teal-700">

                    <i class="bi bi-search"></i>

                </button>

                <a
                    href="index.php"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-slate-600 transition hover:bg-slate-100">

                    <i class="bi bi-arrow-clockwise"></i>

                </a>

            </div>

        </form>

    </div>
    <!-- TABLE -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    <tr class="text-left text-sm font-semibold text-slate-600">

                        <th class="w-16 px-6 py-4">No</th>
                        <th class="px-6 py-4">Foto</th>
                        <th class="px-6 py-4">Potensi Desa</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Pemilik</th>
                        <th class="px-6 py-4">Featured</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="w-40 px-6 py-4 text-center">Aksi</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200 bg-white">

                    <?php if (mysqli_num_rows($query) > 0): ?>

                        <?php $no = $startNumber; ?>

                        <?php while ($row = mysqli_fetch_assoc($query)): ?>

                            <?php

                            $categoryClass = "bg-slate-100 text-slate-700";

                            switch ($row["category"]) {

                                case "UMKM":
                                    $categoryClass = "bg-emerald-100 text-emerald-700";
                                    break;

                                case "Pertanian":
                                    $categoryClass = "bg-green-100 text-green-700";
                                    break;

                                case "Perkebunan":
                                    $categoryClass = "bg-lime-100 text-lime-700";
                                    break;

                                case "Peternakan":
                                    $categoryClass = "bg-orange-100 text-orange-700";
                                    break;

                                case "Perikanan":
                                    $categoryClass = "bg-sky-100 text-sky-700";
                                    break;

                                case "Pariwisata":
                                    $categoryClass = "bg-purple-100 text-purple-700";
                                    break;

                                case "Kerajinan":
                                    $categoryClass = "bg-pink-100 text-pink-700";
                                    break;

                                case "Industri Rumah Tangga":
                                    $categoryClass = "bg-amber-100 text-amber-700";
                                    break;

                                case "Kuliner":
                                    $categoryClass = "bg-red-100 text-red-700";
                                    break;

                                case "Jasa":
                                    $categoryClass = "bg-cyan-100 text-cyan-700";
                                    break;

                                case "BUMDes":
                                    $categoryClass = "bg-indigo-100 text-indigo-700";
                                    break;

                                case "Sumber Daya Alam":
                                    $categoryClass = "bg-yellow-100 text-yellow-700";
                                    break;

                                case "Energi":
                                    $categoryClass = "bg-violet-100 text-violet-700";
                                    break;

                                case "Ekonomi Kreatif":
                                    $categoryClass = "bg-rose-100 text-rose-700";
                                    break;
                            }

                            $featuredClass = $row["featured"] == "Yes"
                                ? "bg-yellow-100 text-yellow-700"
                                : "bg-slate-100 text-slate-600";

                            $statusClass = $row["status"] == "Published"
                                ? "bg-teal-100 text-teal-700"
                                : "bg-slate-100 text-slate-700";

                            ?>

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-4 font-medium text-slate-700">
                                    <?= $no++ ?>
                                </td>

                                <!-- Image -->
                                <td class="px-6 py-4">

                                    <?php if (!empty($row["image"])): ?>

                                        <img
                                            src="<?= APP_URL ?>uploads/potentials/<?= htmlspecialchars($row["image"]) ?>"
                                            alt="<?= htmlspecialchars($row["title"]) ?>"
                                            class="h-14 w-20 rounded-lg border object-cover">

                                    <?php else: ?>

                                        <div class="flex h-14 w-20 items-center justify-center rounded-lg border bg-slate-100 text-slate-400">

                                            <i class="bi bi-image"></i>

                                        </div>

                                    <?php endif; ?>

                                </td>

                                <!-- Title -->
                                <td class="px-6 py-4">

                                    <div class="font-semibold text-slate-800">

                                        <?= htmlspecialchars($row["title"]) ?>

                                    </div>

                                    <?php if (!empty($row["organization"])): ?>

                                        <div class="mt-1 text-xs text-slate-500">

                                            <?= htmlspecialchars($row["organization"]) ?>

                                        </div>

                                    <?php endif; ?>

                                </td>

                                <!-- Category -->
                                <td class="px-6 py-4">

                                    <span class="rounded-full px-3 py-1 text-xs font-semibold <?= $categoryClass ?>">

                                        <?= htmlspecialchars($row["category"]) ?>

                                    </span>

                                </td>

                                <!-- Owner -->
                                <td class="px-6 py-4">

                                    <?= htmlspecialchars($row["owner_name"] ?: "-") ?>

                                </td>

                                <!-- Featured -->
                                <td class="px-6 py-4">

                                    <span class="rounded-full px-3 py-1 text-xs font-semibold <?= $featuredClass ?>">

                                        <?= $row["featured"] == "Yes" ? "⭐ Ya" : "Tidak" ?>

                                    </span>

                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">

                                    <span class="rounded-full px-3 py-1 text-xs font-semibold <?= $statusClass ?>">

                                        <?= htmlspecialchars($row["status"]) ?>

                                    </span>

                                </td>

                                <!-- Action -->
                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-2">

                                        <!-- Detail -->
                                        <a
                                            href="detail.php?id=<?= $row["id"] ?>"
                                            class="rounded-lg bg-sky-100 px-3 py-2 text-sky-700 transition hover:bg-sky-200"
                                            title="Detail">

                                            <i class="bi bi-eye"></i>

                                        </a>

                                        <!-- Edit -->
                                        <a
                                            href="edit.php?id=<?= $row["id"] ?>"
                                            class="rounded-lg bg-amber-100 px-3 py-2 text-amber-700 transition hover:bg-amber-200"
                                            title="Edit">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>

                                        <!-- Produk -->
                                        <a
                                            href="produk.php?potential_id=<?= $row["id"] ?>"
                                            class="rounded-lg bg-indigo-100 px-3 py-2 text-indigo-700 transition hover:bg-indigo-200"
                                            title="Kelola Produk">

                                            <i class="bi bi-box-seam"></i>

                                        </a>

                                        <!-- Delete -->
                                        <a
                                            href="delete.php?id=<?= $row["id"] ?>"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')"
                                            class="rounded-lg bg-red-100 px-3 py-2 text-red-700 transition hover:bg-red-200"
                                            title="Hapus">

                                            <i class="bi bi-trash"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="8" class="px-6 py-16 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">

                                        <i class="bi bi-box-seam text-4xl text-slate-400"></i>

                                    </div>

                                    <h3 class="text-lg font-semibold text-slate-700">

                                        Belum ada data potensi desa

                                    </h3>

                                    <p class="mt-2 text-slate-500">

                                        Silakan tambahkan data potensi desa terlebih dahulu.

                                    </p>

                                    <a
                                        href="create.php"
                                        class="mt-6 inline-flex items-center gap-2 rounded-xl bg-teal-600 px-5 py-3 text-white transition hover:bg-teal-700">

                                        <i class="bi bi-plus-circle"></i>

                                        Tambah Data

                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- FOOTER -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <!-- Information -->
        <div class="text-sm text-slate-500">

            Menampilkan

            <span class="font-semibold text-slate-700">
                <?= $startNumber ?>
            </span>

            -

            <span class="font-semibold text-slate-700">
                <?= $endNumber ?>
            </span>

            dari

            <span class="font-semibold text-slate-700">
                <?= number_format($totalData) ?>
            </span>

            data.

        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>

            <div class="flex flex-wrap items-center gap-2">

                <!-- Previous -->
                <?php if ($pageNumber > 1): ?>

                    <a
                        href="?<?= http_build_query([
                                    "keyword" => $keyword,
                                    "category" => $category,
                                    "featured" => $featured,
                                    "status" => $status,
                                    "page" => $pageNumber - 1
                                ]) ?>"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-slate-600 transition hover:bg-slate-100">

                        <i class="bi bi-chevron-left"></i>

                    </a>

                <?php endif; ?>

                <!-- Number -->
                <?php

                $start = max(1, $pageNumber - 2);
                $finish = min($totalPages, $pageNumber + 2);

                for ($i = $start; $i <= $finish; $i++):

                ?>

                    <a
                        href="?<?= http_build_query([
                                    "keyword" => $keyword,
                                    "category" => $category,
                                    "featured" => $featured,
                                    "status" => $status,
                                    "page" => $i
                                ]) ?>"
                        class="rounded-lg px-4 py-2 transition <?= $i == $pageNumber
                                                                    ? "bg-teal-600 text-white"
                                                                    : "border border-slate-300 text-slate-600 hover:bg-slate-100" ?>">

                        <?= $i ?>

                    </a>

                <?php endfor; ?>

                <!-- Next -->
                <?php if ($pageNumber < $totalPages): ?>

                    <a
                        href="?<?= http_build_query([
                                    "keyword" => $keyword,
                                    "category" => $category,
                                    "featured" => $featured,
                                    "status" => $status,
                                    "page" => $pageNumber + 1
                                ]) ?>"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-slate-600 transition hover:bg-slate-100">

                        <i class="bi bi-chevron-right"></i>

                    </a>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>