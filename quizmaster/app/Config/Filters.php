<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    // Liste des filtres disponibles
    public $aliases = [
        'csrf'     => \CodeIgniter\Filters\CSRF::class,
        'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot' => \CodeIgniter\Filters\Honeypot::class,
        'invalidchars' => \CodeIgniter\Filters\InvalidChars::class,
        'secureheaders' => \CodeIgniter\Filters\SecureHeaders::class,
        'admin' => \App\Filters\AdminFilter::class, // Notre filtre pour les admins
    ];

    // Filtres toujours actifs
    public $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf', // désactivé pour le moment car posait problème
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    // TODO: ajouter filtre pour vérifier si l'utilisateur est connecté
    // sur toutes les pages qui en ont besoin
} 