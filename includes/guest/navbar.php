<?php

$currentPage = $page ?? '';

$menus = [

    [
        'title' => 'Beranda',
        'icon'  => 'bi-house-door',
        'url'   => APP_URL . 'beranda.php',
        'page'  => 'beranda'
    ],

    [
        'title' => 'Profil Desa',
        'icon'  => 'bi-buildings',

        'children' => [

            [
                'title' => 'Tentang Desa',
                'url'   => APP_URL . 'profil-desa/tentang.php',
                'page'  => 'tentang'
            ],

            [
                'title' => 'Struktur Organisasi',
                'url'   => APP_URL . 'profil-desa/struktur.php',
                'page'  => 'struktur'
            ],

            [
                'title' => 'Lembaga Desa',
                'url'   => APP_URL . 'profil-desa/lembaga.php',
                'page'  => 'lembaga'
            ],

            [
                'title' => 'Indeks Desa Membangun',
                'url'   => APP_URL . 'profil-desa/idm.php',
                'page'  => 'idm'
            ],

            [
                'title' => 'Kewilayahan',
                'url'   => APP_URL . 'profil-desa/kewilayahan.php',
                'page'  => 'kewilayahan'
            ],

        ]

    ],

    [
        'title' => 'Informasi',
        'icon'  => 'bi-newspaper',

        'children' => [

            [
                'title' => 'Berita',
                'url'   => APP_URL . 'informasi/berita/index.php',
                'page'  => 'berita'
            ],

            [
                'title' => 'Pengelolaan Keuangan',
                'url'   => APP_URL . 'informasi/pengelolaan-keuangan/index.php',
                'page'  => 'pengelolaan-keuangan'
            ],

            [
                'title' => 'Pembangunan Desa',
                'url'   => APP_URL . 'informasi/pembangunan/index.php',
                'page'  => 'pembangunan'
            ],

            [
                'title' => 'Bantuan Sosial',
                'url'   => APP_URL . 'informasi/bantuan-sosial/index.php',
                'page'  => 'bantuan-sosial'
            ],

            [
                'title' => 'Aset Desa',
                'url'   => APP_URL . 'informasi/aset-desa/index.php',
                'page'  => 'aset-desa'
            ],

            [
                'title' => 'Produk Hukum',
                'url'   => APP_URL . 'informasi/produk-hukum/index.php',
                'page'  => 'produk-hukum'
            ],

            [
                'title' => 'Galeri',
                'url'   => APP_URL . 'informasi/galeri/index.php',
                'page'  => 'galeri'
            ],

        ]

    ],

    [
        'title' => 'Potensi Desa',
        'icon'  => 'bi-stars',
        'url'   => APP_URL . 'potensi/index.php',
        'page'  => 'potensi'
    ],

    [
        'title' => 'Layanan',
        'icon'  => 'bi-file-earmark-text',

        'children' => [

            [
                'title' => 'Pelayanan Surat',
                'url'   => APP_URL . 'layanan/',
                'page'  => 'layanan'
            ],

            [
                'title' => 'Tracking Pengajuan',
                'url'   => APP_URL . 'layanan/tracking-pengajuan.php',
                'page'  => 'tracking'
            ],

        ]

    ],

    [
        'title' => 'Kontak',
        'icon'  => 'bi-telephone',
        'url'   => APP_URL . 'kontak.php',
        'page'  => 'kontak'
    ],

];

function isActive($page, $target)
{
    return $page == $target;
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


$profileQuery = mysqli_query($conn, "
SELECT
    village_name,
    phone,
    email,
    facebook,
    instagram,
    youtube,
    twitter,
    tiktok
FROM village_profiles
LIMIT 1
");

$profile = mysqli_fetch_assoc($profileQuery);
?>

<div class="fixed top-0 z-50 w-full">
    <nav
        x-data="{
    desktopMenu: null,
    mobileMenu: false
}"
        class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-200 shadow-sm">

        <div class="max-w-7xl mx-auto px-6">

            <div class="h-20 flex justify-between items-center">

                <!-- Logo -->

                <a
                    href="<?= APP_URL ?>beranda.php"
                    class="flex items-center gap-3">

                    <img
                        src="<?= APP_URL ?>assets/img/logo.webp"
                        class="w-10 h-10 object-contain">

                    <div>

                        <h1 class="font-bold text-slate-900 whitespace-nowrap">
                            Mlokomanis Wetan
                        </h1>

                        <p class="text-sm text-slate-500">
                            Website Resmi Desa
                        </p>

                    </div>

                </a>

                <!-- Menu -->
                <div class="hidden lg:flex items-center">

                    <?php foreach ($menus as $index => $menu): ?>

                        <?php if (!isset($menu['children'])): ?>

                            <a
                                href="<?= $menu['url'] ?>"
                                class="flex items-center gap-2 rounded-xl px-4 py-2 font-medium transition-all duration-200 whitespace-nowrap
<?= isActive($currentPage, $menu['page'])
                                ? 'bg-teal-600 text-white shadow-lg'
                                : 'text-slate-700 hover:bg-teal-50 hover:text-teal-700'; ?>">

                                <i class="bi <?= $menu['icon'] ?>"></i>

                                <span><?= $menu['title'] ?></span>

                            </a>

                        <?php else: ?>

                            <div
                                class="relative"

                                @mouseenter="
            clearTimeout(window.navbarTimeout);
            desktopMenu='menu<?= $index ?>'
        "

                                @mouseleave="
            window.navbarTimeout=setTimeout(()=>{
                desktopMenu=null
            },150)
        ">

                                <!-- Button -->

                                <button
                                    type="button"
                                    class="flex items-center gap-2 rounded-xl px-4 py-2 font-medium transition-all duration-200 whitespace-nowrap
<?= hasActiveChild($menu['children'], $currentPage)
                                ? 'bg-teal-600 text-white shadow-lg'
                                : 'text-slate-700 hover:bg-teal-50 hover:text-teal-700'; ?>">

                                    <i class="bi <?= $menu['icon'] ?>"></i>

                                    <span><?= $menu['title'] ?></span>

                                    <i
                                        class="bi bi-chevron-down ml-1 text-xs transition duration-300"
                                        :class="desktopMenu==='menu<?= $index ?>' ? 'rotate-180' : ''">
                                    </i>

                                </button>

                                <!-- Dropdown -->

                                <div

                                    x-cloak

                                    x-show="desktopMenu==='menu<?= $index ?>'"

                                    @mouseenter="
                clearTimeout(window.navbarTimeout);
                desktopMenu='menu<?= $index ?>'
            "

                                    @mouseleave="
                window.navbarTimeout=setTimeout(()=>{
                    desktopMenu=null
                },150)
            "

                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"

                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-2 scale-95"

                                    class="absolute left-0 top-full mt-3 w-80 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">

                                    <!-- Header -->

                                    <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-4 py-2 text-white">

                                        <h3 class="font-semibold">

                                            <?= $menu['title'] ?>

                                        </h3>

                                        <p class="text-sm text-teal-100">

                                            Pilih menu yang ingin Anda buka

                                        </p>

                                    </div>

                                    <!-- Items -->

                                    <div class="py-2">

                                        <?php foreach ($menu['children'] as $child): ?>

                                            <a

                                                href="<?= $child['url'] ?>"

                                                class="mx-2 flex items-center justify-between rounded-xl px-4 py-3 transition-all duration-200

                        <?= isActive($currentPage, $child['page'])

                                                ? 'bg-teal-50 text-teal-700 font-semibold'

                                                : 'hover:bg-slate-50 hover:text-teal-700'; ?>">

                                                <span>

                                                    <?= $child['title'] ?>

                                                </span>

                                                <i class="bi bi-arrow-right-short text-xl"></i>

                                            </a>

                                        <?php endforeach; ?>

                                    </div>

                                </div>

                            </div>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>

                <div class="hidden lg:flex items-center gap-3">

                    <?php if (isset($_SESSION['user'])): ?>

                        <a
                            href="<?= APP_URL ?>admin/dashboard.php"
                            class="group flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2.5 font-semibold text-white shadow-lg transition hover:bg-teal-700">

                            <i class="bi bi-speedometer2 transition group-hover:rotate-12"></i>

                            <span>Dashboard</span>

                        </a>

                    <?php else: ?>

                        <a
                            href="<?= APP_URL ?>auth/login.php"
                            class="group flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2.5 font-semibold text-white shadow-lg transition hover:bg-teal-700">

                            <i class="bi bi-box-arrow-in-right transition group-hover:rotate-12"></i>

                            <span>Login Admin</span>

                        </a>

                    <?php endif; ?>

                </div>

                <!-- Tombol Mobile -->

                <button
                    @click="mobileMenu = !mobileMenu"
                    class="lg:hidden rounded-lg p-2 text-2xl hover:bg-slate-100">

                    <i
                        class="bi transition-all duration-300"
                        :class="mobileMenu ? 'bi-x-lg' : 'bi-list'">
                    </i>

                </button>

            </div>

        </div>

        <!-- Mobile Menu -->

        <!-- Mobile Menu -->
        <div
            x-cloak
            x-show="mobileMenu"
            x-collapse
            class="border-t border-slate-200 bg-white lg:hidden">

            <div class="p-4 space-y-2">

                <?php foreach ($menus as $index => $menu): ?>

                    <?php if (!isset($menu['children'])): ?>

                        <a
                            href="<?= $menu['url'] ?>"
                            class="block rounded-xl px-4 py-3 hover:bg-teal-50 hover:text-teal-700">

                            <?= $menu['title'] ?>

                        </a>

                    <?php else: ?>

                        <div
                            x-data="{ open:false }"
                            class="rounded-xl border border-slate-200 overflow-hidden">

                            <button
                                @click="open=!open"
                                class="flex w-full items-center justify-between px-4 py-3 text-left hover:bg-slate-50">

                                <span class="font-medium">
                                    <?= $menu['title'] ?>
                                </span>

                                <i
                                    class="bi bi-chevron-down transition duration-300"
                                    :class="open ? 'rotate-180' : ''">
                                </i>

                            </button>

                            <div
                                x-show="open"
                                x-collapse
                                class="border-t border-slate-100 bg-slate-50">

                                <?php foreach ($menu['children'] as $child): ?>

                                    <a
                                        href="<?= $child['url'] ?>"
                                        class="block px-6 py-3 hover:bg-teal-50 hover:text-teal-700">

                                        <?= $child['title'] ?>

                                    </a>

                                <?php endforeach; ?>

                            </div>

                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>

                <?php if (
                    isset($_SESSION['user'])
                ): ?>

                    <a
                        href="<?= APP_URL ?>admin/dashboard.php"
                        class="mt-4 flex items-center justify-center rounded-xl bg-teal-600 px-4 py-3 font-semibold text-white hover:bg-teal-700">

                        <i class="bi bi-speedometer2 mr-2"></i>

                        Dashboard

                    </a>

                <?php else: ?>

                    <a
                        href="<?= APP_URL ?>auth/login.php"
                        class="mt-4 flex items-center justify-center rounded-xl bg-teal-600 px-4 py-3 font-semibold text-white hover:bg-teal-700">

                        <i class="bi bi-box-arrow-in-right mr-2"></i>

                        Login Admin

                    </a>

                <?php endif; ?>

            </div>

        </div>

    </nav>
</div>