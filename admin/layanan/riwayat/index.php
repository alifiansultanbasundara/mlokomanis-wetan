<?php

require_once '../../../config/app.php';

// ======================================================
// PAGINATION
// ======================================================

$limit = 10;

$page = isset($_GET['page']) && is_numeric($_GET['page'])
    ? (int) $_GET['page']
    : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

// ======================================================
// SEARCH
// ======================================================

$search = trim($_GET['search'] ?? '');

$where = '';

if ($search !== '') {

    $searchEscaped = mysqli_real_escape_string(
        $conn,
        $search
    );

    $where = "
        WHERE
            gl.file_name LIKE '%$searchEscaped%'
            OR lt.name LIKE '%$searchEscaped%'
            OR p.name LIKE '%$searchEscaped%'
            OR p.nik LIKE '%$searchEscaped%'
    ";
}

// ======================================================
// TOTAL DATA
// ======================================================

$countQuery = mysqli_query(
    $conn,
    "
    SELECT COUNT(*) AS total
    FROM generated_letters gl

    LEFT JOIN letter_types lt
        ON lt.id = gl.letter_type_id

    LEFT JOIN populations p
        ON p.id = gl.population_id

    $where
    "
);

$countData = mysqli_fetch_assoc($countQuery);

$totalData = (int) $countData['total'];

$totalPages = ceil($totalData / $limit);

// ======================================================
// DATA
// ======================================================

$query = mysqli_query(
    $conn,
    "
    SELECT
        gl.*,

        lt.name AS letter_name,

        p.nik,
        p.name AS population_name

    FROM generated_letters gl

    LEFT JOIN letter_types lt
        ON lt.id = gl.letter_type_id

    LEFT JOIN populations p
        ON p.id = gl.population_id

    $where

    ORDER BY gl.generated_at DESC

    LIMIT $offset, $limit
    "
);

include APP_PATH . "includes/admin/layout-top.php";

?>


<div class="p-6">

    <!-- HEADER -->
    <div class="mb-6">

        <div class="flex items-center justify-between">

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Riwayat Surat
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Daftar surat yang telah dibuat melalui layanan surat.
                </p>

            </div>

            <a
                href="../generate/"
                class="inline-flex items-center gap-2
                       rounded-lg bg-teal-600 px-4 py-2
                       text-sm font-medium text-white
                       hover:bg-teal-700">

                <i class="bi bi-file-earmark-plus"></i>

                Buat Surat

            </a>

        </div>

    </div>


    <!-- FILTER -->
    <div class="mb-4">

        <form
            method="GET"
            class="flex gap-2">

            <div class="relative flex-1">

                <i class="bi bi-search
                          absolute left-3 top-1/2
                          -translate-y-1/2
                          text-slate-400"></i>

                <input
                    type="text"
                    name="search"
                    value="<?= htmlspecialchars($search) ?>"
                    placeholder="Cari nama, NIK, atau jenis surat..."
                    class="w-full rounded-lg border
                           border-slate-300
                           py-2.5 pl-10 pr-4
                           text-sm
                           focus:border-teal-500
                           focus:ring-teal-500">

            </div>

            <button
                type="submit"
                class="rounded-lg bg-slate-700
                       px-5 py-2.5
                       text-sm font-medium text-white
                       hover:bg-slate-800">

                Cari

            </button>

            <?php if ($search !== ''): ?>

                <a
                    href="index.php"
                    class="rounded-lg border
                           border-slate-300
                           px-4 py-2.5
                           text-sm text-slate-600
                           hover:bg-slate-50">

                    Reset

                </a>

            <?php endif; ?>

        </form>

    </div>


    <!-- TABLE -->
    <div
        class="overflow-hidden rounded-xl
               border border-slate-200
               bg-white">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-4 py-3 text-left">
                            No
                        </th>

                        <th class="px-4 py-3 text-left">
                            Jenis Surat
                        </th>

                        <th class="px-4 py-3 text-left">
                            Penduduk
                        </th>

                        <th class="px-4 py-3 text-left">
                            NIK
                        </th>

                        <th class="px-4 py-3 text-left">
                            Tanggal
                        </th>

                        <th class="px-4 py-3 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    <?php if (mysqli_num_rows($query) > 0): ?>

                        <?php
                        $no = $offset + 1;
                        ?>

                        <?php while ($row = mysqli_fetch_assoc($query)): ?>

                            <tr class="hover:bg-slate-50">

                                <td class="px-4 py-3 text-slate-500">
                                    <?= $no++ ?>
                                </td>

                                <td class="px-4 py-3">

                                    <div class="font-medium text-slate-800">

                                        <?= htmlspecialchars(
                                            $row['letter_name'] ?? '-'
                                        ) ?>

                                    </div>

                                </td>

                                <td class="px-4 py-3">

                                    <div class="font-medium text-slate-700">

                                        <?= htmlspecialchars(
                                            $row['population_name'] ?? '-'
                                        ) ?>

                                    </div>

                                </td>

                                <td class="px-4 py-3 text-slate-600">

                                    <?= htmlspecialchars(
                                        $row['nik'] ?? '-'
                                    ) ?>

                                </td>

                                <td class="px-4 py-3 text-slate-600">

                                    <?= !empty($row['generated_at'])
                                        ? date(
                                            'd-m-Y H:i',
                                            strtotime($row['generated_at'])
                                        )
                                        : '-'
                                    ?>

                                </td>

                                <td class="px-4 py-3">

                                    <div class="flex items-center justify-center gap-2">

                                        <!-- Preview -->

                                        <a
                                            href="../generate/preview.php?file=<?= urlencode($row['file_name']) ?>&letter_id=<?= (int) $row['letter_type_id'] ?>&population_id=<?= (int) $row['population_id'] ?>"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100"
                                            title="Lihat">

                                            <i class="bi bi-eye"></i>

                                        </a>


                                        <!-- Download -->

                                        <a
                                            href="<?= APP_URL ?>uploads/generated-letters/<?= rawurlencode($row['file_name']) ?>"
                                            download
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100"
                                            title="Download">

                                            <i class="bi bi-download"></i>

                                        </a>


                                        <!-- Print -->
                                        <a
                                            href="../generate/preview.php?file=<?= urlencode($row['file_name']) ?>&letter_id=<?= (int) $row['letter_type_id'] ?>&population_id=<?= (int) $row['population_id'] ?>&print=1"
                                            target="_blank"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100"
                                            title="Print">

                                            <i class="bi bi-printer"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="6"
                                class="px-4 py-12 text-center">

                                <div class="text-slate-400">

                                    <i
                                        class="bi bi-file-earmark-text
                                               text-4xl"></i>

                                </div>

                                <p class="mt-3 font-medium text-slate-600">
                                    Belum ada riwayat surat
                                </p>

                                <p class="mt-1 text-sm text-slate-400">
                                    Surat yang berhasil dibuat akan muncul di sini.
                                </p>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>


    <!-- PAGINATION -->

    <?php if ($totalPages > 1): ?>

        <div class="mt-5 flex items-center justify-between">

            <p class="text-sm text-slate-500">

                Menampilkan
                <?= $totalData > 0 ? $offset + 1 : 0 ?>
                -
                <?= min($offset + $limit, $totalData) ?>
                dari
                <?= $totalData ?>
                data

            </p>


            <div class="flex gap-1">

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                    <a
                        href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
                        class="
                            rounded-lg px-3 py-2 text-sm
                            <?= $i == $page
                                ? 'bg-teal-600 text-white'
                                : 'border border-slate-200 text-slate-600 hover:bg-slate-50'
                            ?>
                        ">

                        <?= $i ?>

                    </a>

                <?php endfor; ?>

            </div>

        </div>

    <?php endif; ?>

</div>
<script>
    const params = new URLSearchParams(window.location.search);

    if (params.get('print') === '1') {

        window.addEventListener('load', function() {

            setTimeout(function() {
                window.print();
            }, 1500);

        });

    }
</script>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>