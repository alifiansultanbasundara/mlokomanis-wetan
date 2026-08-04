<?php
$title = $title ?? 'Dashboard';
?>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<header
    x-data="{
        search:false,
        notif:false,
        profile:false
    }"
    class="sticky top-0 z-30 border-b border-slate-200 bg-white h-20">

    <div class="flex h-20 items-center justify-between px-8">

        <!-- Left -->
        <div class="flex items-center gap-5">

            <!-- Sidebar -->
            <button
                @click="$store.sidebar.toggle()"
                class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-100">

                <i class="bi bi-list text-xl"></i>

            </button>

            <div>

                <!-- <h1 class="text-2xl font-bold text-slate-900">
                    <?= $title ?>
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    <?= tanggalIndonesia() ?>
                </p> -->

            </div>

        </div>

        <!-- Right -->
        <div class="flex items-center gap-3">

            <!-- User -->
            <div class="relative">

                <button
                    @click="profile=!profile"
                    class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2 transition hover:bg-slate-50">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-600 font-semibold text-white">

                        <?= strtoupper(substr($_SESSION['nama'], 0, 1)); ?>

                    </div>

                    <div class="hidden lg:block text-left">

                        <p class="font-semibold text-slate-900">

                            <?= $_SESSION['nama']; ?>

                        </p>

                    </div>

                    <i
                        class="bi bi-chevron-down text-slate-500 transition"
                        :class="profile && 'rotate-180'">
                    </i>

                </button>

                <div
                    x-show="profile"
                    x-transition.origin.top.right
                    @click.outside="profile=false"
                    class="absolute right-0 mt-3 w-60 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">

                    <div class="border-b border-slate-100 p-5">

                        <p class="font-semibold text-slate-900">

                            <?= $_SESSION['nama']; ?>

                        </p>

                        <!-- <p class="text-sm text-slate-500">

                            <?= ucfirst($_SESSION['role']); ?>

                        </p> -->

                    </div>

                    <a
                        href="<?= APP_URL ?>/admin/pengguna/"
                        class="flex items-center gap-3 px-5 py-4 transition hover:bg-slate-50">

                        <i class="bi bi-person"></i>

                        Profil Saya

                    </a>

                    <a
                        href="<?= APP_URL ?>auth/logout.php"
                        class="flex items-center gap-3 border-t border-slate-100 px-5 py-4 text-red-600 transition hover:bg-red-50">

                        <i class="bi bi-box-arrow-right"></i>

                        Logout

                    </a>

                </div>

            </div>

        </div>

    </div>

</header>