<?php
require_once '../../../config/app.php';
require_once APP_PATH . 'config/database.php';

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location: index.php");
    exit;
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);

$query = mysqli_query($conn, "
        SELECT
            a.*,
            u.username AS author
        FROM articles a
        INNER JOIN users u ON u.id = a.author_id
        WHERE a.slug = '$slug'
        LIMIT 1
    ");

if (!$query || mysqli_num_rows($query) == 0) {
    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// Tambah jumlah views
mysqli_query($conn, "
        UPDATE articles
        SET views = views + 1
        WHERE id = {$data['id']}
    ");

$data['views']++;

$title = "Detail Berita";
$page  = "berita";

include APP_PATH . 'includes/admin/layout-top.php';
?>
<div class="p-8">
    <!-- Header -->
    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">
                Detail Berita
            </h2>
            <p class="mt-2 text-slate-500">
                Informasi lengkap berita yang telah dipublikasikan.
            </p>
        </div>
        <div class="flex gap-3">
            <a
                href="index.php"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 transition hover:bg-slate-50">
                Kembali
            </a>
            <a href="edit.php?slug=<?= $slug ?>"
                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white transition hover:bg-teal-700">
                Edit Berita
            </a>
        </div>
    </div>
    <div class="grid gap-8 lg:grid-cols-3">
        <!-- LEFT -->
        <div class="space-y-8 lg:col-span-2">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <span class="inline-flex rounded-full bg-teal-100 px-3 py-1 text-sm font-medium text-teal-700">
                        <?= htmlspecialchars($data['category'], ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <h1 class="mt-4 text-3xl font-bold text-slate-900">
                        <?= htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8'); ?>
                    </h1>
                    <?php if (!empty($data['excerpt'])) : ?>
                        <p class="mt-4 text-slate-600">
                            <?= nl2br(htmlspecialchars($data['excerpt'], ENT_QUOTES, 'UTF-8')); ?>
                        </p>
                    <?php endif; ?>
                </div>
                <?php if (!empty($data['thumbnail'])) : ?>
                    <img
                        src="<?= APP_URL . 'uploads/informasi/berita/' . htmlspecialchars($data['thumbnail'], ENT_QUOTES, 'UTF-8'); ?>"
                        alt="<?= htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8'); ?>"
                        class="h-[420px] w-full object-cover">
                <?php endif; ?>
                <div class="p-6 leading-8 text-slate-700">
                    <?= nl2br($data['content']); ?>
                </div>
            </div>
        </div>
        <!-- RIGHT -->
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="font-semibold text-slate-900">
                        Informasi
                    </h3>
                </div>
                <div class="space-y-5 p-6">
                    <div>
                        <p class="text-sm text-slate-500">
                            Penulis
                        </p>
                        <p class="font-medium text-slate-800">
                            <?= htmlspecialchars($data['author'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">
                            Status
                        </p>
                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium <?= $data['status'] === 'Published'
                                                                                                ? 'bg-emerald-100 text-emerald-700'
                                                                                                : 'bg-yellow-100 text-yellow-700'; ?>">
                            <?= htmlspecialchars($data['status'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">
                            Kategori
                        </p>
                        <p class="font-medium text-slate-800">
                            <?= htmlspecialchars($data['category'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">
                            Dilihat
                        </p>
                        <p class="font-medium text-slate-800">
                            <?= number_format($data['views']); ?> kali
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">
                            Tanggal Dibuat
                        </p>
                        <p class="font-medium text-slate-800">
                            <?= date('d F Y H:i', strtotime($data['created_at'])); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">
                            Terakhir Diperbarui
                        </p>
                        <p class="font-medium text-slate-800">
                            <?= date('d F Y H:i', strtotime($data['updated_at'])); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">
                            Slug
                        </p>
                        <div class="break-all rounded-xl bg-slate-100 p-3 text-sm text-slate-700">
                            <?= htmlspecialchars($data['slug'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>