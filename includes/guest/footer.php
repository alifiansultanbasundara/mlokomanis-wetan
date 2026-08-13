<?php
$profile = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT *
    FROM village_profiles
    LIMIT 1
"));
?>

<footer class="bg-teal-900 text-teal-300">

    <!-- Top -->
    <div class="max-w-7xl mx-auto px-6 py-16">

        <div class="grid gap-10 lg:grid-cols-12">

            <!-- Desa -->
            <div class="lg:col-span-5">

                <div class="flex items-center gap-3 mb-5">

                    <img
                        src="<?= APP_URL ?>assets/img/logo.webp"
                        class="w-14 h-14 object-contain">

                    <div>

                        <h3 class="text-lg font-bold text-white">
                            <?= $profile['village_name'] ?>
                        </h3>

                        <p class="text-sm text-teal-100">
                            Website Resmi Desa
                        </p>

                    </div>

                </div>

                <p class="text-sm leading-7 text-teal-100">

                    Desa Mlokomanis Wetan merupakan salah satu desa yang berada di wilayah Kecamatan Nguntoronadi, Kabupaten Wonogiri, Provinsi Jawa Tengah.
                </p>

            </div>

            <!-- Menu -->
            <div class="lg:col-span-2">

                <h4 class="mb-5 font-semibold text-lg text-white">
                    Menu
                </h4>

                <ul class="space-y-3">

                    <li>
                        <a href="<?= APP_URL ?>landingpage/beranda.php"
                            class="hover:text-white transition text-teal-100/80">
                            Beranda
                        </a>
                    </li>

                    <li>
                        <a href="<?= APP_URL ?>landingpage/profil-desa/tentang.php"
                            class="hover:text-white transition text-teal-100/80">
                            Profil Desa
                        </a>
                    </li>

                    <li>
                        <a href="<?= APP_URL ?>landingpage/informasi/berita.php"
                            class="hover:text-white transition text-teal-100/80">
                            Informasi
                        </a>
                    </li>

                    <li>
                        <a href="<?= APP_URL ?>landingpage/layanan/"
                            class="hover:text-white transition text-teal-100/80">
                            Layanan
                        </a>
                    </li>

                    <li>
                        <a href="<?= APP_URL ?>landingpage/kontak.php"
                            class="hover:text-white transition text-teal-100/80">
                            Kontak
                        </a>
                    </li>

                </ul>

            </div>

            <!-- Kontak -->
            <div class="lg:col-span-3">

                <h4 class="mb-5 font-semibold text-lg text-white">
                    Hubungi Kami
                </h4>

                <div class="space-y-4">

                    <?php if (!empty($profile['office_address'])): ?>

                        <div class="flex items-start gap-3">

                            <i class="bi bi-geo-alt-fill mt-1 text-teal-100"></i>

                            <span class="text-sm leading-6 text-teal-100/80">
                                <?= $profile['office_address'] ?>
                            </span>

                        </div>

                    <?php endif; ?>

                    <?php if (!empty($profile['phone'])): ?>

                        <div class="flex items-center gap-3">

                            <i class="bi bi-telephone-fill text-teal-100"></i>

                            <a
                                href="tel:<?= $profile['phone'] ?>"
                                class="hover:text-white text-teal-100/80">

                                <?= $profile['phone'] ?>

                            </a>

                        </div>

                    <?php endif; ?>

                    <?php if (!empty($profile['email'])): ?>

                        <div class="flex items-center gap-3">

                            <i class="bi bi-envelope-fill text-teal-100"></i>

                            <a
                                href="mailto:<?= $profile['email'] ?>"
                                class="hover:text-white">

                                <?= $profile['email'] ?>

                            </a>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <!-- Sosial Media -->
            <div class="lg:col-span-2">

                <h4 class="mb-5 font-semibold text-lg text-white">
                    Ikuti Kami
                </h4>

                <div class="flex flex-wrap gap-3">

                    <?php
                    $socials = [
                        'facebook'  => 'facebook',
                        'instagram' => 'instagram',
                        'youtube'   => 'youtube',
                        'twitter'   => 'twitter-x',
                        'tiktok'    => 'tiktok',
                    ];

                    foreach ($socials as $field => $icon):

                        if (!empty($profile[$field])):
                    ?>

                            <a
                                href="<?= $profile[$field] ?>"
                                target="_blank"
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-800 text-lg transition hover:bg-teal-600 hover:text-white">

                                <i class="bi bi-<?= $icon ?>"></i>

                            </a>

                    <?php
                        endif;
                    endforeach;
                    ?>

                </div>

            </div>

        </div>

    </div>

    <!-- Bottom -->
    <!-- Bottom -->
    <div class="border-t border-teal-800">

        <div class="max-w-7xl mx-auto px-6 py-6">

            <div class="flex flex-col gap-3 text-center text-sm text-teal-100 md:flex-row md:items-center md:justify-between">

                <p>

                    © <?= date('Y') ?>

                    <span class="font-semibold text-white">
                        <?= $profile['village_name'] ?>
                    </span>

                    • Seluruh Hak Cipta Dilindungi.

                </p>

                <p>
                    Dikembangkan oleh
                    <span class="font-semibold text-white">
                        KKN UNS 269 Tahun 2026
                    </span>
                </p>

            </div>

        </div>

    </div>

</footer>