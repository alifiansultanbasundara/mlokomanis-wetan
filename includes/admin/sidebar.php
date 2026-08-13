<?php

$page = $page ?? '';

$menus = [

    // ==========================
    // DASHBOARD
    // ==========================

    [
        'title' => 'Dashboard',
        'icon'  => 'bi-grid-fill',
        'url'   => APP_URL . 'admin/dashboard.php',
        'page'  => 'dashboard'
    ],


    // ==========================
    // PROFIL DESA
    // ==========================

    [
        'title' => 'Profil Desa',
        'icon'  => 'bi-building',

        'children' => [

            [
                'title' => 'Tentang Desa',
                'url'   => APP_URL . 'admin/profil-desa/tentang-desa/',
                'page'  => 'tentang-desa'
            ],

            [
                'title' => 'Struktur Organisasi',
                'url'   => APP_URL . 'admin/profil-desa/struktur-organisasi/',
                'page'  => 'struktur-organisasi'
            ],

            [
                'title' => 'Lembaga Desa',
                'url'   => APP_URL . 'admin/profil-desa/lembaga-desa/',
                'page'  => 'lembaga-desa'
            ],

            [
                'title' => 'Indeks Desa Membangun (IDM)',
                'url'   => APP_URL . 'admin/profil-desa/idm/',
                'page'  => 'idm'
            ],

            [
                'title' => 'Kewilayahan',
                'url'   => APP_URL . 'admin/profil-desa/kewilayahan/',
                'page'  => 'kewilayahan'
            ],

        ]
    ],


    // ==========================
    // INFORMASI PUBLIK
    // ==========================

    [
        'title' => 'Informasi Publik',
        'icon'  => 'bi-newspaper',

        'children' => [

            [
                'title' => 'Berita',
                'url'   => APP_URL . 'admin/informasi/berita/',
                'page'  => 'berita'
            ],
            [
                'title' => 'Pengumuman',
                'url'   => APP_URL . 'admin/informasi/pengumuman/',
                'page'  => 'pengumuman'
            ],

            [
                'title' => 'Pengelolaan Keuangan',
                'url'   => APP_URL . 'admin/informasi/pengelolaan-keuangan/',
                'page'  => 'pengelolaan-keuangan'
            ],

            [
                'title' => 'Pembangunan Desa',
                'url'   => APP_URL . 'admin/informasi/pembangunan/',
                'page'  => 'pembangunan'
            ],

            [
                'title' => 'Bantuan Sosial',
                'url'   => APP_URL . 'admin/informasi/bantuan-sosial/',
                'page'  => 'bantuan-sosial'
            ],

            [
                'title' => 'Aset Desa',
                'url'   => APP_URL . 'admin/informasi/aset-desa/',
                'page'  => 'aset-desa'
            ],

            [
                'title' => 'Produk Hukum',
                'url'   => APP_URL . 'admin/informasi/produk-hukum/',
                'page'  => 'produk-hukum'
            ],

            [
                'title' => 'Galeri Kegiatan',
                'url'   => APP_URL . 'admin/informasi/galeri/',
                'page'  => 'galeri'
            ],

        ]
    ],


    // ==========================
    // POTENSI DESA
    // ==========================

    [
        'title' => 'Potensi Desa',
        'icon'  => 'bi-shop',

        'children' => [

            [
                'title' => 'UMKM & Produk Unggulan',
                'url'   => APP_URL . 'admin/potensi-desa/',
                'page'  => 'potensi'
            ],

        ]
    ],


    // ==========================
    // LAYANAN SURAT
    // ==========================

    [
        'title' => 'Layanan Surat',
        'icon'  => 'bi-envelope-paper',

        'children' => [

            [
                'title' => 'Data Kependudukan',
                'url'   => APP_URL . 'admin/layanan/kependudukan/',
                'page'  => 'kependudukan'
            ],

            [
                'title' => 'Jenis Surat',
                'url'   => APP_URL . 'admin/layanan/jenis-surat/',
                'page'  => 'jenis-surat'
            ],

            [
                'title' => 'Generate Surat',
                'url'   => APP_URL . 'admin/layanan/generate/',
                'page'  => 'generate-surat'
            ],

            [
                'title' => 'Riwayat Surat',
                'url'   => APP_URL . 'admin/layanan/riwayat/',
                'page'  => 'riwayat-surat'
            ],

        ]
    ],


    // ==========================
    // KONTAK
    // ==========================

    [
        'title' => 'Kontak',
        'icon'  => 'bi-telephone',
        'children' => [

            [
                'title' => 'Kelola Kontak',
                'url'   => APP_URL . 'admin/kontak/informasi-kontak/',
                'page'  => 'kontak'
            ],

            [
                'title' => 'Pesan',
                'url'   => APP_URL . 'admin/kontak/pesan-masuk/',
                'page'  => 'pesan'
            ],

        ]
    ],


    // ==========================
    // SYSTEM
    // ==========================

    [
        'title' => 'Pengguna',
        'icon'  => 'bi-person-gear',
        'url'   => APP_URL . 'admin/pengguna/',
        'page'  => 'pengguna'
    ],

];

function menuActive($item, $page)
{
    if (isset($item['active'])) {
        return in_array($page, $item['active']);
    }

    if (isset($item['children'])) {
        foreach ($item['children'] as $child) {
            if (in_array($page, $child['active'])) {
                return true;
            }
        }
    }

    return false;
}

?>

<?php

$page = $page ?? '';

function isActive($current, $target)
{
    return $current == $target;
}

function hasActiveChild($children, $page)
{
    foreach ($children as $child) {
        if ($child['page'] == $page) {
            return true;
        }
    }

    return false;
}
?>

<aside
    x-data
    x-cloak
    :class="$store.sidebar.open ? 'w-72' : 'w-20'"
    class="h-screen shrink-0 bg-white border-r border-slate-200 flex flex-col transition-all duration-300 sticky top-0 z-30">

    <!-- Logo -->
    <div class="border-b border-slate-200 px-6 py-4 h-20 flex justify-start items-center">

        <a href="<?= APP_URL ?>" class="flex items-center">

            <img
                src="<?= APP_URL ?>assets/img/logo.webp"
                class="w-10 h-10 object-contain">

            <div
                x-show="$store.sidebar.open"
                x-transition.opacity.duration.200ms
                class="ml-4 overflow-hidden">

                <h2 class="font-bold text-slate-900 whitespace-nowrap">
                    Desa Mlokomanis
                </h2>

                <p class="text-sm text-slate-500 whitespace-nowrap">
                    Dashboard Admin
                </p>

            </div>

        </a>

    </div>

    <!-- Menu -->
    <nav class="flex-1 overflow-y-auto p-4 space-y-2">

        <?php foreach ($menus as $menu): ?>

            <?php if (!isset($menu['children'])): ?>

                <a
                    href="<?= $menu['url'] ?>"
                    :class="$store.sidebar.open
        ? 'justify-start px-4'
        : 'justify-center px-0'"
                    class="flex items-center gap-3 rounded-xl py-3 transition
    <?= isActive($page, $menu['page'])
                    ? 'bg-teal-600 text-white'
                    : 'text-slate-600 hover:bg-slate-100'; ?>">

                    <i class="bi <?= $menu['icon'] ?> text-lg shrink-0"></i>

                    <span
                        x-show="$store.sidebar.open"
                        x-transition.opacity>

                        <?= $menu['title'] ?>

                    </span>

                </a>

            <?php else: ?>

                <details
                    class="group"
                    x-bind:open="$store.sidebar.open"
                    <?= hasActiveChild($menu['children'], $page) ? 'open' : '' ?>>

                    <summary
                        :class="$store.sidebar.open
        ? 'justify-between px-4'
        : 'justify-center px-0'"
                        class="flex cursor-pointer list-none items-center rounded-xl py-3 hover:bg-slate-100">

                        <div class="flex items-center gap-3">

                            <i class="bi <?= $menu['icon'] ?>"></i>

                            <span
                                x-show="$store.sidebar.open"
                                x-transition>

                                <?= $menu['title'] ?>

                            </span>

                        </div>

                        <i
                            x-show="$store.sidebar.open"
                            class="bi bi-chevron-down transition group-open:rotate-180">
                        </i>

                    </summary>

                    <div
                        x-show="$store.sidebar.open"
                        x-transition
                        class="ml-5 mt-2 border-l border-slate-200 pl-4 space-y-1">

                        <?php foreach ($menu['children'] as $child): ?>

                            <a
                                href="<?= $child['url'] ?>"
                                class="block rounded-lg px-3 py-2 transition
                                <?= isActive($page, $child['page'])
                                    ? 'bg-teal-50 text-teal-700 font-semibold'
                                    : 'text-slate-600 hover:bg-slate-100'; ?>">

                                <?= $child['title'] ?>

                            </a>

                        <?php endforeach; ?>

                    </div>

                </details>

            <?php endif; ?>

        <?php endforeach; ?>

    </nav>

    <!-- Footer -->
    <div class="border-t border-slate-200 p-5">

        <a
            href="<?= APP_URL ?>auth/logout.php"
            :class="$store.sidebar.open
        ? 'justify-center'
        : 'justify-center'"
            class="flex items-center gap-3 rounded-xl bg-red-500 py-3 font-medium text-white transition hover:bg-red-600">

            <i class="bi bi-box-arrow-right"></i>

            <span
                x-show="$store.sidebar.open"
                x-transition>

                Logout

            </span>

        </a>

    </div>

</aside>