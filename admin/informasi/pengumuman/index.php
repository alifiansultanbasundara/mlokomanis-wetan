<?php
require_once '../../../config/app.php';

// =======================
// Pagination
// =======================
$limit = 10; // jumlah data per halaman

$currentPage = isset($_GET['page']) && is_numeric($_GET['page'])
    ? max(1, (int) $_GET['page'])
    : 1;

$offset = ($currentPage - 1) * $limit;

// =======================
// Filter Status
// =======================
$status = $_GET['status'] ?? '';

$where = "";

if ($status == "Published") {
    $where = "WHERE announcements.status = 'Published'";
} elseif ($status == "Draft") {
    $where = "WHERE announcements.status = 'Draft'";
}

// =======================
// Search
// =======================
$search = trim($_GET['search'] ?? '');

if (!empty($search)) {

    $keyword = mysqli_real_escape_string($conn, $search);

    if (empty($where)) {

        $where = "WHERE (
            announcements.title LIKE '%$keyword%' OR
            announcements.content LIKE '%$keyword%' OR
            announcements.type LIKE '%$keyword%'
        )";
    } else {

        $where .= " AND (
            announcements.title LIKE '%$keyword%' OR
            announcements.content LIKE '%$keyword%' OR
            announcements.type LIKE '%$keyword%'
        )";
    }
}

// =======================
// Total Data
// =======================
$totalQuery = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM announcements
    $where
");

$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$totalPage = ceil($totalData / $limit);

// =======================
// Data Pengumuman
// =======================
$query = mysqli_query($conn, "
    SELECT
        announcements.*,
        users.nama AS author
    FROM announcements
    LEFT JOIN users
        ON users.id = announcements.created_by
    $where
    ORDER BY
        announcements.priority ASC,
        announcements.created_at DESC
    LIMIT $limit OFFSET $offset
");

// =======================
// Layout
// =======================
$title = "Pengumuman Desa";
$page  = "pengumuman";

include APP_PATH . 'includes/admin/layout-top.php';
?>
<main class="space-y-8 p-8">
    <!-- Header -->
    <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">
                Pengumuman Desa
            </h2>
            <p class="mt-2 text-slate-500">
                Kelola seluruh pengumuman, dan informasi yang ditampilkan pada website desa.
            </p>
        </div>
        <a
            href="create.php"
            class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-5 py-3 font-medium text-white transition hover:bg-teal-700">
            <i class="bi bi-plus-lg"></i>
            Tambah Pengumuman
        </a>
    </div>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="rounded-xl border border-teal-200 bg-teal-50 px-5 py-4 text-teal-700">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= $_SESSION['success']; ?>
        </div>
    <?php unset($_SESSION['success']);
    endif; ?>
    <!-- Card -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <!-- Toolbar -->
        <div class="flex flex-col gap-4 border-b border-slate-200 p-6 lg:flex-row lg:items-center lg:justify-between">
            <form method="GET" class="relative">
                <input
                    type="hidden"
                    name="status"
                    value="<?= htmlspecialchars($status) ?>">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input
                    id="search"
                    type="text"
                    name="search"
                    value="<?= htmlspecialchars($search) ?>"
                    placeholder="Cari pengumuman..."
                    class="w-full rounded-xl border border-slate-200 py-3 pl-11 pr-4 outline-none transition focus:border-teal-600 lg:w-80">
            </form>
            <select
                onchange="window.location=this.value"
                class="rounded-xl border border-slate-200 px-4 py-3">
                <option
                    value="?status=&search=<?= urlencode($search) ?>"
                    <?= $status == '' ? 'selected' : '' ?>>
                    Semua Status
                </option>
                <option
                    value="?status=Published&search=<?= urlencode($search) ?>"
                    <?= $status == 'Published' ? 'selected' : '' ?>>
                    Published
                </option>
                <option
                    value="?status=Draft&search=<?= urlencode($search) ?>"
                    <?= $status == 'Draft' ? 'selected' : '' ?>>
                    Draft
                </option>
            </select>
        </div>
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr class="text-left text-sm font-semibold text-slate-600">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Pengumuman</th>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4">Periode</th>
                        <th class="px-6 py-4 text-center">Urutan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Penulis</th>
                        <th class="px-6 py-4">Dibuat</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    <?php
                    $no = $offset + 1;

                    if (mysqli_num_rows($query) > 0):

                        while ($row = mysqli_fetch_assoc($query)):
                    ?>

                            <tr class="transition hover:bg-slate-50">

                                <!-- No -->
                                <td class="px-6 py-5 text-slate-500">
                                    <?= $no++; ?>
                                </td>

                                <!-- Judul -->
                                <td class="px-6 py-5">
                                    <h4 class="font-semibold text-slate-900">
                                        <?= htmlspecialchars($row['title']) ?>
                                    </h4>

                                    <p class="mt-1 line-clamp-2 text-sm text-slate-500">
                                        <?= htmlspecialchars(mb_strimwidth(strip_tags($row['content']), 0, 80, "...")) ?>
                                    </p>
                                </td>

                                <!-- Jenis -->
                                <td class="px-6 py-5">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-sm">
                                        <?= htmlspecialchars($row['type']) ?>
                                    </span>
                                </td>

                                <!-- Periode -->
                                <td class="px-6 py-5 text-sm text-slate-500">
                                    <?php if (!empty($row['start_date']) && !empty($row['end_date'])): ?>

                                        <?= tanggalIndonesia($row['start_date'], 'dd MMM yyyy'); ?>
                                        <br>
                                        <span class="text-xs text-slate-400">
                                            s/d
                                            <?= tanggalIndonesia($row['end_date'], 'dd MMM yyyy'); ?>
                                        </span>

                                    <?php else: ?>

                                        <span class="text-slate-400">-</span>

                                    <?php endif; ?>
                                </td>

                                <!-- Urutan -->
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-teal-100 font-semibold text-teal-700">
                                        <?= $row['priority']; ?>
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-5">

                                    <?php if ($row['status'] == "Published"): ?>

                                        <span class="rounded-full bg-teal-100 px-3 py-1 text-sm font-medium text-teal-700">
                                            Published
                                        </span>

                                    <?php else: ?>

                                        <span class="rounded-full bg-slate-200 px-3 py-1 text-sm">
                                            Draft
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <!-- Penulis -->
                                <td class="px-6 py-5">
                                    <?= htmlspecialchars($row['author']); ?>
                                </td>

                                <!-- Dibuat -->
                                <td class="px-6 py-5 text-sm text-slate-500">
                                    <?= tanggalIndonesia($row['created_at'], 'dd MMM yyyy') ?>
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-5">

                                    <div class="flex justify-center gap-2">

                                        <a
                                            href="detail.php?slug=<?= $row['slug'] ?>"
                                            class="flex h-10 w-10 items-center justify-center rounded-lg border bg-sky-100 text-sky-600 hover:bg-sky-200">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a
                                            href="edit.php?slug=<?= $row['slug'] ?>"
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-500 text-white hover:bg-amber-600">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a
                                            href="delete.php?slug=<?= $row['slug'] ?>"
                                            onclick="return confirm('Yakin ingin menghapus pengumuman ini?')"
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-500 text-white hover:bg-red-600">
                                            <i class="bi bi-trash"></i>
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="9" class="px-6 py-20 text-center">

                                <i class="bi bi-megaphone text-5xl text-slate-300"></i>

                                <h3 class="mt-4 text-lg font-semibold text-slate-700">
                                    Belum ada pengumuman
                                </h3>

                                <p class="mt-2 text-slate-500">
                                    Silakan tambahkan pengumuman pertama.
                                </p>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>
        </div>
        <!-- Toolbar -->
        <div class="flex flex-col gap-4 border-t border-slate-200 p-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="relative">
            </div>
            <div class="flex items-center gap-2">
                <!-- Previous -->
                <?php if ($currentPage > 1): ?>
                    <a
                        href="?page=<?= $currentPage - 1 ?>&status=<?= urlencode($status) ?>&search=<?= urlencode($search) ?>"
                        class="rounded-lg border border-slate-300 px-4 py-2 hover:bg-slate-100">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                <?php endif; ?>
                <!-- Nomor Halaman -->
                <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="rounded-lg bg-teal-600 px-4 py-2 text-white">
                            <?= $i ?>
                        </span>
                    <?php else: ?>
                        <a
                            href="?page=<?= $i ?>&status=<?= urlencode($status) ?>&search=<?= urlencode($search) ?>"
                            class="rounded-lg border border-slate-300 px-4 py-2 hover:bg-slate-100">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
                <!-- Next -->
                <?php if ($currentPage < $totalPage): ?>
                    <a
                        href="?page=<?= $currentPage + 1 ?>&status=<?= urlencode($status) ?>&search=<?= urlencode($search) ?>"
                        class="rounded-lg border border-slate-300 px-4 py-2 hover:bg-slate-100">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<script>
    const search = document.getElementById('search');

    let timeout;

    search.addEventListener('keyup', function() {

        clearTimeout(timeout);

        timeout = setTimeout(() => {

            const keyword = this.value;
            const status = "<?= urlencode($status) ?>";

            window.location =
                "?search=" + encodeURIComponent(keyword) +
                "&status=" + status;

        }, 1000);

    });
</script>
<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>