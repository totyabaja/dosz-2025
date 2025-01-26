<?php

return [
    'general_settings' => [
        'title' => 'Általános beállítások',
        'heading' => 'Általános beállítások',
        'subheading' => 'Kezelje az általános webhely -beállításokat itt.',
        'navigationLabel' => 'Általános',
        'sections' => [
            'site' => [
                'title' => 'Telek',
                'description' => 'Alapbeállítások kezelése.',
            ],
            'theme' => [
                'title' => 'Téma',
                'description' => 'Az alapértelmezett téma módosítása.',
            ],
        ],
        'fields' => [
            'brand_name' => 'Márkanév',
            'site_active' => 'Webhely állapota',
            'brand_logoHeight' => 'Márka logómagasság',
            'brand_logo' => 'Márka logó',
            'site_favicon' => 'Favicon oldal',
            'primary' => 'Elsődleges',
            'secondary' => 'Másodlagos',
            'gray' => 'Szürke',
            'success' => 'Siker',
            'danger' => 'Veszély',
            'info' => 'Információ',
            'warning' => 'Figyelmeztetés',
        ],
    ],
    'mail_settings' => [
        'title' => 'Levélbeállítások',
        'heading' => 'Levelezési beállítások',
        'subheading' => 'Kezelje az e -mail konfigurációt.',
        'navigationLabel' => 'Felad',
        'sections' => [
            'config' => [
                'title' => 'Konfiguráció',
                'description' => 'leírás',
            ],
            'sender' => [
                'title' => '(Feladó)',
                'description' => 'leírás',
            ],
            'mail_to' => [
                'title' => 'Elküld',
                'description' => 'leírás',
            ],
        ],
        'fields' => [
            'placeholder' => [
                'receiver_email' => 'A fogadó e -maile ..',
            ],
            'driver' => 'Driver',
            'host' => 'Házigazda',
            'port' => 'Kikötő',
            'encryption' => 'Titkosítás',
            'timeout' => 'Időkorlát',
            'username' => 'Felhasználónév',
            'password' => 'Jelszó',
            'email' => 'Email',
            'name' => 'Név',
            'mail_to' => 'Elküld',
        ],
        'actions' => [
            'send_test_mail' => 'Teszt mail küldése',
        ],
    ]
    ];
