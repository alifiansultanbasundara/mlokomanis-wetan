<?php

$menus = [

    [
        'title' => 'Beranda',
        'url'   => 'beranda.php'
    ],

    [
        'title' => 'Profil Desa',
        'children' => [

            [
                'title' => 'Sejarah Desa',
                'url' => 'profil/sejarah.php'
            ],

            [
                'title' => 'Visi & Misi',
                'url' => 'profil/visi-misi.php'
            ],

            [
                'title' => 'Struktur Organisasi',
                'url' => 'profil/struktur-organisasi.php'
            ],

            [
                'title' => 'Keadaan Wilayah',
                'url' => 'profil/keadaan-wilayah.php'
            ]

        ]
    ],

    [
        'title' => 'Kontak',
        'url' => 'kontak.php'
    ]

];
