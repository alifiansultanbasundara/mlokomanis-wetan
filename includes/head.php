<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Primary Meta -->
<title><?= $title ?? 'Desa Mlokomanis Wetan' ?></title>

<meta name="title" content="<?= $metaTitle ?? 'Desa Mlokomanis Wetan' ?>">

<meta
    name="description"
    content="<?= $metaDescription ?? 'Website resmi Desa Mlokomanis Wetan, Kecamatan Ngadirojo, Kabupaten Wonogiri. Informasi desa, profil, layanan masyarakat, dan berbagai informasi terbaru desa.' ?>">

<meta
    name="keywords"
    content="<?= $metaKeywords ?? 'Desa Mlokomanis Wetan, Desa Wonogiri, Ngadirojo, Pemerintah Desa, Layanan Desa' ?>">

<meta
    name="author"
    content="<?= $metaAuthor ?? 'Pemerintah Desa Mlokomanis Wetan' ?>">

<?php
$favicon = APP_URL . 'assets/img/logo.webp';
?>

<!-- Favicon -->
<link rel="icon" type="image/webp" href="<?= $favicon ?>">
<link rel="shortcut icon" href="<?= $favicon ?>">
<link rel="apple-touch-icon" href="<?= $favicon ?>">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?= $ogUrl ?? 'https://desa-mlokomanis-wetan.vercel.app/' ?>">
<meta property="og:title" content="<?= $ogTitle ?? 'Desa Mlokomanis Wetan' ?>">
<meta property="og:description" content="<?= $ogDescription ?? 'Website resmi Desa Mlokomanis Wetan. Temukan informasi profil desa, layanan masyarakat, berita, dan informasi terbaru desa.' ?>">
<meta property="og:image" content="<?= $ogImage ?? 'https://desa-mlokomanis-wetan.vercel.app/assets/img/logo.webp' ?>">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= $twitterTitle ?? 'Desa Mlokomanis Wetan' ?>">
<meta name="twitter:description" content="<?= $twitterDescription ?? 'Website resmi Desa Mlokomanis Wetan, Kecamatan Ngadirojo, Kabupaten Wonogiri.' ?>">
<meta name="twitter:image" content="<?= $twitterImage ?? 'https://desa-mlokomanis-wetan.vercel.app/assets/img/logo.webp' ?>">

<!-- Bootstrap Icons -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<style>
    [x-cloak] {
        display: none !important;
    }

    html,
    body {
        scroll-behavior: smooth;
    }

    .group .absolute a {
        display: block;
        padding: 12px 20px;
        color: #374151;
        transition: .2s;
    }

    .group .absolute a:hover {
        background: #ECFDF5;
        color: #059669;
    }
</style>