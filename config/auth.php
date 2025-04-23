<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Valores por defecto
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard'     => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'usuarios'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Guards
    |--------------------------------------------------------------------------
    */
   'guards' => [

    // Usuarios normales (alias web)
    'web' => [
        'driver'   => 'session',
        'provider' => 'usuarios',
    ],

    // Alias explícito que usan tus controladores
    'usuario' => [          // ← NUEVO
        'driver'   => 'session',
        'provider' => 'usuarios',
    ],

    // Administradores
    'administrador' => [
        'driver'   => 'session',
        'provider' => 'administradores',
    ],
],

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [
        'usuarios' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Usuario::class,
        ],

        'administradores' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Administrador::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Restablecimiento de contraseñas
    |--------------------------------------------------------------------------
    */
    'passwords' => [
        'usuarios' => [
            'provider' => 'usuarios',
            'table'    => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire'   => 60,
            'throttle' => 60,
        ],
        // puedes añadir 'administradores' si después implementas ese flujo
    ],

    /*
    |--------------------------------------------------------------------------
    | Tiempo de expiración de confirmación de contraseña
    |--------------------------------------------------------------------------
    */
    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),
];
