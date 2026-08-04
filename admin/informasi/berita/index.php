<?php
require_once '../../../config/app.php';

// =======================
// Pagination
// =======================
$limit = 1; // jumlah data per halaman

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
    $where = "WHERE articles.status = 'Published'";
} elseif ($status == "Draft") {
    $where = "WHERE articles.status = 'Draft'";
}

// =======================
// Search
// =======================
$search = trim($_GET['search'] ?? '');

if (!empty($search)) {
    $keyword = mysqli_real_escape_string($conn, $search);

    if (empty($where)) {
        $where = "WHERE (
                articles.title LIKE '%$keyword%' OR
                articles.excerpt LIKE '%$keyword%' OR
                articles.category LIKE '%$keyword%'
            )";
    } else {
        $where .= " AND (
                articles.title LIKE '%$keyword%' OR
                articles.excerpt LIKE '%$keyword%' OR
                articles.category LIKE '%$keyword%'
            )";
    }
}

// =======================
// Total Data
// =======================
$totalQuery = mysqli_query($conn, "
            SELECT COUNT(*) AS total
            FROM articles
            $where
        ");
$totalData = mysqli_fetch_assoc($totalQuery)['total'];

$totalPage = ceil($totalData / $limit);

// =======================
// Data Berita
// =======================
$query = mysqli_query($conn, "
            SELECT
                articles.*,
                users.nama AS author
            FROM articles
            JOIN users
                ON users.id = articles.author_id
            $where
            ORDER BY articles.created_at DESC
            LIMIT $limit OFFSET $offset
        ");

// =======================
// Layout
// =======================
$title = "Berita Desa";
$page  = "berita";

include APP_PATH . 'includes/admin/layout-top.php';
?>
<main class="space-y-8 p-8">
    <!-- Header -->
    <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">
                Berita Desa
            </h2>
            <p class="mt-2 text-slate-500">
                Kelola seluruh berita, artikel, dan informasi yang ditampilkan pada website desa.
            </p>
        </div>
        <a
            href="create.php"
            class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-5 py-3 font-medium text-white transition hover:bg-teal-700">
            <i class="bi bi-plus-lg"></i>
            Tambah Berita
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
                    placeholder="Cari berita..."
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
                        <th class="px-6 py-4">Thumbnail</th>
                        <th class="px-6 py-4">Berita</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Penulis</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Views</th>
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
                                <td class="px-6 py-5 text-slate-500">
                                    <?= $no++; ?>
                                </td>
                                <td class="px-6 py-5">
                                    <?php if ($row['thumbnail']): ?>
                                        <img
                                            src="<?= APP_URL ?>uploads/informasi/berita/<?= htmlspecialchars($row['thumbnail']); ?>"
                                            alt="<?= htmlspecialchars($row['title']); ?>"
                                            class="h-16 w-24 rounded-lg object-cover">
                                    <?php else: ?>
                                        <div class="flex h-16 w-24 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5">
                                    <h4 class="font-semibold text-slate-900">
                                        <?= htmlspecialchars($row['title']) ?>
                                    </h4>
                                    <p class="mt-1 line-clamp-2 text-sm text-slate-500">
                                        <?= htmlspecialchars($row['excerpt']) ?>
                                    </p>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-sm">
                                        <?= $row['category']; ?>
                                    </span>
                                </td>
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
                                <td class="px-6 py-5">
                                    <?= htmlspecialchars($row['author']); ?>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-500">
                                    <?= tanggalIndonesia($row['created_at'], 'dd MMM yyyy') ?>
                                </td>
                                <td class="px-6 py-5">
                                    <?= $row['views']; ?>
                                </td>
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
                                            onclick="return confirm('Yakin ingin menghapus berita ini?')"
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
                                <i class="bi bi-newspaper text-5xl text-slate-300"></i>
                                <h3 class="mt-4 text-lg font-semibold text-slate-700">
                                    Belum ada berita
                                </h3>
                                <p class="mt-2 text-slate-500">
                                    Silakan tambahkan berita pertama.
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