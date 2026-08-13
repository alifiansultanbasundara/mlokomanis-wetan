<?php

require_once '../../../config/app.php';

// ======================================================
// DATA
// ======================================================

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM letter_types
     ORDER BY sort_order ASC, id DESC"
);

if (!$query) {
    die('Query gagal: ' . mysqli_error($conn));
}

// ======================================================
// HELPER
// ======================================================


include APP_PATH . "includes/admin/layout-top.php";
?>

<main class="bg-slate-50">

    <div class="max-w-7xl mx-auto px-6 py-8">

        <!-- ==================================================
             HEADER
        =================================================== -->

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Jenis Surat
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Kelola jenis surat yang tersedia untuk pelayanan desa.
                </p>

            </div>

            <a
                href="create.php"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-teal-700 transition">

                <i class="bi bi-plus-lg"></i>

                Tambah Jenis Surat

            </a>

        </div>


        <!-- ==================================================
             FILTER & SEARCH
        =================================================== -->

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-6">

            <form
                method="GET"
                class="grid grid-cols-1 md:grid-cols-3 gap-3">

                <!-- Search -->

                <div class="md:col-span-2 relative">

                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>

                    <input
                        type="text"
                        name="search"
                        value="<?= e($_GET['search'] ?? '') ?>"
                        placeholder="Cari nama jenis surat..."
                        class="w-full rounded-lg border border-slate-300 pl-10 pr-4 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">

                </div>


                <!-- Status -->

                <select
                    name="status"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="Aktif"
                        <?= ($_GET['status'] ?? '') === 'Aktif' ? 'selected' : '' ?>>
                        Aktif
                    </option>

                    <option
                        value="Nonaktif"
                        <?= ($_GET['status'] ?? '') === 'Nonaktif' ? 'selected' : '' ?>>
                        Nonaktif
                    </option>

                </select>


                <!-- Button -->

                <div class="md:col-span-3 flex gap-2">

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-900">

                        <i class="bi bi-search"></i>

                        Cari

                    </button>

                    <a
                        href="index.php"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">

                        <i class="bi bi-arrow-clockwise"></i>

                        Reset

                    </a>

                </div>

            </form>

        </div>


        <!-- ==================================================
             TABLE
        =================================================== -->

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50 border-b border-slate-200">

                        <tr>

                            <th class="px-5 py-4 text-left font-semibold text-slate-600">
                                No
                            </th>

                            <th class="px-5 py-4 text-left font-semibold text-slate-600">
                                Jenis Surat
                            </th>

                            <th class="px-5 py-4 text-left font-semibold text-slate-600">
                                Template
                            </th>

                            <th class="px-5 py-4 text-center font-semibold text-slate-600">
                                Status
                            </th>

                            <th class="px-5 py-4 text-center font-semibold text-slate-600">
                                Urutan
                            </th>

                            <th class="px-5 py-4 text-center font-semibold text-slate-600">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        <?php

                        $no = 1;

                        while ($row = mysqli_fetch_assoc($query)):

                            // Search
                            $search = trim($_GET['search'] ?? '');

                            if (
                                $search !== '' &&
                                stripos($row['name'], $search) === false
                            ) {
                                continue;
                            }

                            // Filter status
                            $status = $_GET['status'] ?? '';

                            if (
                                $status !== '' &&
                                $row['status'] !== $status
                            ) {
                                continue;
                            }

                        ?>

                            <tr class="hover:bg-slate-50 transition">

                                <!-- No -->

                                <td class="px-5 py-4 text-slate-500">
                                    <?= $no++ ?>
                                </td>


                                <!-- Jenis Surat -->

                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-teal-50 text-teal-600">

                                            <i class="bi <?= e($row['icon'] ?: 'bi-file-earmark-text') ?>"></i>

                                        </div>

                                        <div>

                                            <p class="font-semibold text-slate-800">
                                                <?= e($row['name']) ?>
                                            </p>

                                            <?php if (!empty($row['description'])): ?>

                                                <p class="text-xs text-slate-500 mt-1 max-w-md truncate">
                                                    <?= e($row['description']) ?>
                                                </p>

                                            <?php endif; ?>

                                        </div>

                                    </div>

                                </td>


                                <!-- Template -->

                                <td class="px-5 py-4">

                                    <?php if (!empty($row['file_path'])): ?>

                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">

                                            <i class="bi bi-file-earmark-word"></i>

                                            DOCX

                                        </span>

                                    <?php elseif (!empty($row['template_body'])): ?>

                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">

                                            <i class="bi bi-file-text"></i>

                                            Template Body

                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-500">

                                            <i class="bi bi-file-earmark-x"></i>

                                            Belum Ada

                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- Status -->

                                <td class="px-5 py-4 text-center">

                                    <?php if ($row['status'] === 'Aktif'): ?>

                                        <span
                                            class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">

                                            Aktif

                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">

                                            Nonaktif

                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- Sort Order -->

                                <td class="px-5 py-4 text-center text-slate-600">

                                    <?= (int) $row['sort_order'] ?>

                                </td>


                                <!-- Action -->

                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-center gap-2">

                                        <!-- Edit -->

                                        <a
                                            href="edit.php?id=<?= (int) $row['id'] ?>"
                                            title="Edit"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        <!-- Delete -->

                                        <a
                                            href="delete.php?id=<?= (int) $row['id'] ?>"
                                            title="Hapus"
                                            onclick="return confirm('Yakin ingin menghapus jenis surat ini?')"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100">

                                            <i class="bi bi-trash"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>


                        <!-- Empty -->

                        <?php if ($no === 1): ?>

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-5 py-12 text-center">

                                    <div class="flex flex-col items-center">

                                        <div
                                            class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400 text-xl">

                                            <i class="bi bi-file-earmark-text"></i>

                                        </div>

                                        <p class="mt-3 font-medium text-slate-700">
                                            Belum ada jenis surat
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            Silakan tambahkan jenis surat terlebih dahulu.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</main>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>