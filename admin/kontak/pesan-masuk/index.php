<?php

require_once '../../../config/app.php';

$title = "Pesan Masuk";
$page  = "pesan";

// ===============================================
// Search & Filter
// ===============================================

$search = trim($_GET['search'] ?? '');
$status = trim($_GET['status'] ?? '');

$where = "WHERE 1=1";

if ($search != '') {

    $keyword = mysqli_real_escape_string($conn, $search);

    $where .= " AND (

        name LIKE '%$keyword%' OR
        subject LIKE '%$keyword%' OR
        phone LIKE '%$keyword%' OR
        email LIKE '%$keyword%'

    )";
}

if ($status != '') {

    $status = mysqli_real_escape_string($conn, $status);

    $where .= " AND status='$status'";
}

// ===============================================
// Pagination
// ===============================================

$limit = 10;

$pageNumber = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$offset = ($pageNumber - 1) * $limit;

$totalQuery = mysqli_query($conn, "
SELECT COUNT(*) total
FROM contact_messages
$where
");

$totalData = mysqli_fetch_assoc($totalQuery)['total'];

$totalPages = ceil($totalData / $limit);

// ===============================================
// Statistik
// ===============================================

$total = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM contact_messages
"))['total'];

$unread = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM contact_messages
WHERE status='Belum Dibaca'
"))['total'];

$read = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM contact_messages
WHERE status='Sudah Dibaca'
"))['total'];

$follow = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM contact_messages
WHERE status='Ditindaklanjuti'
"))['total'];

// ===============================================
// Data
// ===============================================

$query = mysqli_query($conn, "
SELECT *
FROM contact_messages

$where

ORDER BY id DESC

LIMIT $offset,$limit
");

include APP_PATH . "includes/admin/layout-top.php";

?>

<main class="p-8">

    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-3xl font-bold">

                Pesan Masuk

            </h1>

            <p class="text-slate-500 mt-2">

                Kelola pesan dari masyarakat.

            </p>

        </div>

    </div>

    <!-- Statistik -->

    <div class="grid md:grid-cols-4 gap-6 mb-8">

        <div class="bg-white border rounded-2xl p-6">

            <h3 class="text-slate-500">Total</h3>

            <p class="text-3xl font-bold mt-2">

                <?= $total ?>

            </p>

        </div>

        <div class="bg-white border rounded-2xl p-6">

            <h3 class="text-slate-500">

                Belum Dibaca

            </h3>

            <p class="text-3xl font-bold text-red-600 mt-2">

                <?= $unread ?>

            </p>

        </div>

        <div class="bg-white border rounded-2xl p-6">

            <h3 class="text-slate-500">

                Sudah Dibaca

            </h3>

            <p class="text-3xl font-bold text-blue-600 mt-2">

                <?= $read ?>

            </p>

        </div>

        <div class="bg-white border rounded-2xl p-6">

            <h3 class="text-slate-500">

                Ditindaklanjuti

            </h3>

            <p class="text-3xl font-bold text-emerald-600 mt-2">

                <?= $follow ?>

            </p>

        </div>

    </div>

    <!-- Filter -->

    <form class="flex gap-4 mb-6">

        <input
            type="text"
            name="search"
            value="<?= htmlspecialchars($search) ?>"
            placeholder="Cari pesan..."
            class="flex-1 border rounded-xl px-4 py-3">

        <select
            name="status"
            class="border rounded-xl px-4 py-3">

            <option value="">Semua Status</option>

            <option <?= $status == "Belum Dibaca" ? "selected" : "" ?>>
                Belum Dibaca
            </option>

            <option <?= $status == "Sudah Dibaca" ? "selected" : "" ?>>
                Sudah Dibaca
            </option>

            <option <?= $status == "Ditindaklanjuti" ? "selected" : "" ?>>
                Ditindaklanjuti
            </option>

        </select>

        <button class="bg-emerald-600 text-white px-6 rounded-xl">

            Filter

        </button>

    </form>

    <!-- Table -->

    <div class="bg-white rounded-2xl border overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-4">No</th>

                        <th class="px-6 py-4">Nama</th>

                        <th class="px-6 py-4">Subjek</th>

                        <th class="px-6 py-4">HP</th>

                        <th class="px-6 py-4">Status</th>

                        <th class="px-6 py-4">Tanggal</th>

                        <th class="px-6 py-4 text-center">

                            Aksi

                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $no = $offset + 1;

                    while ($row = mysqli_fetch_assoc($query)):

                        $badge = "bg-slate-100";

                        if ($row['status'] == "Belum Dibaca") {

                            $badge = "bg-red-100 text-red-700";
                        }

                        if ($row['status'] == "Sudah Dibaca") {

                            $badge = "bg-blue-100 text-blue-700";
                        }

                        if ($row['status'] == "Ditindaklanjuti") {

                            $badge = "bg-emerald-100 text-emerald-700";
                        }

                    ?>

                        <tr class="border-t">

                            <td class="px-6 py-4"><?= $no++ ?></td>

                            <td class="px-6 py-4">

                                <?= htmlspecialchars($row['name']) ?>

                            </td>

                            <td class="px-6 py-4">

                                <?= htmlspecialchars($row['subject']) ?>

                            </td>

                            <td class="px-6 py-4">

                                <?= htmlspecialchars($row['phone']) ?>

                            </td>

                            <td class="px-6 py-4">

                                <span class="px-3 py-1 rounded-full text-xs <?= $badge ?>">

                                    <?= $row['status'] ?>

                                </span>

                            </td>

                            <td class="px-6 py-4">

                                <?= date('d M Y', strtotime($row['created_at'])) ?>

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex justify-center gap-2">

                                    <a
                                        href="detail.php?id=<?= $row['id'] ?>"
                                        class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">

                                        <i class="bi bi-eye"></i>

                                    </a>

                                    <a
                                        href="delete.php?id=<?= $row['id'] ?>"
                                        onclick="return confirm('Hapus pesan ini?')"
                                        class="w-9 h-9 rounded-lg bg-red-100 text-red-700 flex items-center justify-center">

                                        <i class="bi bi-trash"></i>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</main>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>