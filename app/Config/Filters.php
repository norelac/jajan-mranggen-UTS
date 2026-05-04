<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'      => \CodeIgniter\Filters\CSRF::class,
        'toolbar'   => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot'  => \CodeIgniter\Filters\Honeypot::class,
        'invalidchars' => \CodeIgniter\Filters\InvalidChars::class,
        'secureheaders' => \CodeIgniter\Filters\SecureHeaders::class,
        // Custom Filters
        'auth'     => \App\Filters\AuthFilter::class,
        'admin'    => \App\Filters\AdminFilter::class,
        'penjual'  => \App\Filters\PenjualFilter::class,
        'pembeli'  => \App\Filters\PembeliFilter::class,
    ];

    public array $globals = [
        'before' => [],
        'after'  => [
            'toolbar',
        ],
    ];

    public array $methods = [];

    public array $filters = [];
}
