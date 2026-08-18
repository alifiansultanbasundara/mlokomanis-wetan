<?php

require_once '../../../config/app.php';

$page = 'kependudukan';

// ======================================================
// Filter & Search
// ======================================================

$search = trim($_GET['search'] ?? '');
$gender = trim($_GET['gender'] ?? '');
$hamlet = trim($_GET['hamlet'] ?? '');


// ======================================================
// Query
// ======================================================

$where = [];

if ($search !== '') {

    $searchEscaped = mysqli_real_escape_string($conn, $search);

    $where[] = "(
        nik LIKE '%$searchEscaped%'
        OR name LIKE '%$searchEscaped%'
    )";
}

if ($gender !== '') {

    $genderEscaped = mysqli_real_escape_string($conn, $gender);

    $where[] = "gender = '$genderEscaped'";
}

if ($hamlet !== '') {

    $hamletEscaped = mysqli_real_escape_string($conn, $hamlet);

    $where[] = "hamlet = '$hamletEscaped'";
}


$whereSql = '';

if (!empty($where)) {
    $whereSql = 'WHERE ' . implode(' AND ', $where);
}


// ======================================================
// Ambil Data Penduduk
// ======================================================

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM populations
     $whereSql
     ORDER BY name ASC"
);


// ======================================================
// Ambil Data Dusun untuk Filter
// ======================================================

$hamlets = mysqli_query(
    $conn,
    "SELECT DISTINCT hamlet
     FROM populations
     WHERE hamlet IS NOT NULL
     AND hamlet != ''
     ORDER BY hamlet ASC"
);

include APP_PATH . "includes/admin/layout-top.php"

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Kependudukan - <?= APP_NAME ?? 'Admin' ?></title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>


<body class="bg-slate-50 text-slate-800">

    <div class="min-h-screen">


        <!-- ==================================================
             HEADER
        =================================================== -->

        <header class="border-b border-slate-200 bg-white">

            <div class="mx-auto max-w-7xl px-6 py-5">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h1 class="text-2xl font-bold text-slate-900">
                            Data Kependudukan
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            Kelola data penduduk desa.
                        </p>

                    </div>


                    <div class="flex flex-wrap items-center gap-3">

                        <a
                            href="create.php"
                            class="inline-flex items-center gap-2 rounded-lg bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-700">

                            <i class="bi bi-person-plus"></i>

                            Tambah Penduduk

                        </a>


                        <a
                            href="template.php"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">

                            <i class="bi bi-file-earmark-excel"></i>

                            Download Template

                        </a>


                        <button
                            type="button"
                            onclick="document.getElementById('excelInput').click()"
                            class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">

                            <i class="bi bi-upload"></i>

                            Import Excel

                        </button>

                    </div>


                    <form
                        action="import.php"
                        method="POST"
                        enctype="multipart/form-data"
                        class="hidden">

                        <input
                            type="file"
                            id="excelInput"
                            name="excel_file"
                            accept=".xlsx,.xls"
                            onchange="this.form.submit()">

                    </form>

                </div>

            </div>

        </header>

        <?php if (isset($_SESSION["success"])): ?>
            <div class="mx-auto max-w-7xl px-6 py-5">
                <div class="rounded-xl border border-teal-200 bg-teal-50 px-5 py-4 text-teal-700">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= $_SESSION["success"] ?>
                </div>
                <?php unset($_SESSION["success"]); ?>
            </div>
        <?php endif; ?>


        <!-- ==================================================
             CONTENT
        =================================================== -->

        <main class="mx-auto max-w-7xl px-6 py-8">


            <!-- ==================================================
                 FILTER
            =================================================== -->

            <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <form
                    method="GET"
                    class="grid gap-4 md:grid-cols-4">


                    <!-- Search -->

                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Cari Penduduk
                        </label>

                        <div class="relative">

                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                type="text"
                                name="search"
                                value="<?= htmlspecialchars($search) ?>"
                                placeholder="Cari NIK atau nama..."
                                class="w-full rounded-xl border border-slate-300 py-3 pl-11 pr-4 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>

                    </div>


                    <!-- Gender -->

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Jenis Kelamin
                        </label>

                        <select
                            name="gender"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                            <option value="">
                                Semua
                            </option>

                            <option
                                value="Laki-laki"
                                <?= $gender === 'Laki-laki' ? 'selected' : '' ?>>
                                Laki-laki
                            </option>

                            <option
                                value="Perempuan"
                                <?= $gender === 'Perempuan' ? 'selected' : '' ?>>
                                Perempuan
                            </option>

                        </select>

                    </div>


                    <!-- Dusun -->

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Dusun
                        </label>

                        <select
                            name="hamlet"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                            <option value="">
                                Semua Dusun
                            </option>

                            <?php while ($row = mysqli_fetch_assoc($hamlets)): ?>

                                <option
                                    value="<?= htmlspecialchars($row['hamlet']) ?>"
                                    <?= $hamlet === $row['hamlet'] ? 'selected' : '' ?>>

                                    <?= htmlspecialchars($row['hamlet']) ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <!-- Button -->

                    <div class="flex items-end gap-2 md:col-span-4">

                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-5 py-3 font-semibold text-white transition hover:bg-teal-700">

                            <i class="bi bi-funnel"></i>

                            Filter

                        </button>


                        <?php if ($search !== '' || $gender !== '' || $hamlet !== ''): ?>

                            <a
                                href="index.php"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 font-semibold text-slate-600 transition hover:bg-slate-50">

                                <i class="bi bi-arrow-counterclockwise"></i>

                                Reset

                            </a>

                        <?php endif; ?>

                    </div>

                </form>

            </div>


            <!-- ==================================================
                 TABLE
            =================================================== -->

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">


                <!-- Table Header -->

                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">

                    <div>

                        <h2 class="font-semibold text-slate-900">
                            Daftar Penduduk
                        </h2>

                        <p class="text-sm text-slate-500">
                            Data penduduk yang terdaftar.
                        </p>

                    </div>

                </div>


                <!-- Table -->

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[900px] text-left text-sm">

                        <thead class="bg-slate-50 text-xs uppercase text-slate-500">

                            <tr>

                                <th class="px-6 py-4">
                                    No
                                </th>

                                <th class="px-6 py-4">
                                    NIK
                                </th>

                                <th class="px-6 py-4">
                                    Nama
                                </th>

                                <th class="px-6 py-4">
                                    Jenis Kelamin
                                </th>

                                <th class="px-6 py-4">
                                    RT / RW
                                </th>

                                <th class="px-6 py-4">
                                    Dusun
                                </th>

                                <th class="px-6 py-4 text-center">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            <?php if (mysqli_num_rows($query) > 0): ?>

                                <?php $no = 1; ?>

                                <?php while ($row = mysqli_fetch_assoc($query)): ?>

                                    <tr class="transition hover:bg-slate-50">

                                        <!-- No -->

                                        <td class="px-6 py-4 text-slate-500">
                                            <?= $no++ ?>
                                        </td>


                                        <!-- NIK -->

                                        <td class="px-6 py-4 font-mono text-sm text-slate-600">

                                            <?= htmlspecialchars($row['nik']) ?>

                                        </td>


                                        <!-- Nama -->

                                        <td class="px-6 py-4">

                                            <div class="font-semibold text-slate-900">

                                                <?= htmlspecialchars($row['name']) ?>

                                            </div>

                                            <?php if (!empty($row['occupation'])): ?>

                                                <div class="mt-1 text-xs text-slate-500">

                                                    <?= htmlspecialchars($row['occupation']) ?>

                                                </div>

                                            <?php endif; ?>

                                        </td>


                                        <!-- Gender -->

                                        <td class="px-6 py-4">

                                            <?php if ($row['gender'] === 'Laki-laki'): ?>

                                                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">

                                                    Laki-laki

                                                </span>

                                            <?php else: ?>

                                                <span class="inline-flex items-center rounded-full bg-pink-50 px-3 py-1 text-xs font-semibold text-pink-700">

                                                    Perempuan

                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <!-- RT RW -->

                                        <td class="px-6 py-4 text-slate-600">

                                            RT <?= htmlspecialchars($row['rt'] ?: '-') ?>

                                            /

                                            RW <?= htmlspecialchars($row['rw'] ?: '-') ?>

                                        </td>


                                        <!-- Dusun -->

                                        <td class="px-6 py-4 text-slate-600">

                                            <?= htmlspecialchars($row['hamlet'] ?: '-') ?>

                                        </td>


                                        <!-- Action -->

                                        <td class="px-6 py-4">

                                            <div class="flex items-center justify-center gap-2">


                                                <!-- Detail -->

                                                <a
                                                    href="detail.php?id=<?= $row['id'] ?>"
                                                    title="Detail"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition hover:bg-blue-100">

                                                    <i class="bi bi-eye"></i>

                                                </a>


                                                <!-- Edit -->

                                                <a
                                                    href="edit.php?id=<?= $row['id'] ?>"
                                                    title="Edit"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600 transition hover:bg-amber-100">

                                                    <i class="bi bi-pencil"></i>

                                                </a>


                                                <!-- Delete -->

                                                <form
                                                    action="delete.php"
                                                    method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus data penduduk ini?')">
                                                    <input
                                                        type="hidden"
                                                        name="id"
                                                        value="<?= $row['id'] ?>">

                                                    <button
                                                        type="submit"
                                                        title="Hapus"
                                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>

                                    <td
                                        colspan="7"
                                        class="px-6 py-12 text-center">

                                        <div class="flex flex-col items-center">

                                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">

                                                <i class="bi bi-people text-2xl text-slate-400"></i>

                                            </div>

                                            <h3 class="font-semibold text-slate-700">
                                                Data penduduk tidak ditemukan
                                            </h3>

                                            <p class="mt-1 text-sm text-slate-500">
                                                Belum ada data yang sesuai dengan pencarian.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

</body>

</html>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>