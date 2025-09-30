<?php

// config for Threls/ThrelsCheckEnv
return [
    /*
      |--------------------------------------------------------------------------
      | Temporary Environment Suffix
      |--------------------------------------------------------------------------
      |
      | This suffix is used to create temporary environment files for validation.
      | example: .env.staging.test.encrypted
      |
      */
    'temp-env-suffix' => 'test',

    /*
      |--------------------------------------------------------------------------
      | Environment Definitions
      |--------------------------------------------------------------------------
      |
      | Define your environments here. The key should be the environment name,
      | and the encryption-key value should be the env key which will store the encryption key.
      |
      */

    'environments' => [
        'development' => [
            'encryption-key' => 'ENV_ENCRYPTION_KEY',
        ],
        'staging' => [
            'encryption-key' => 'ENV_ENCRYPTION_KEY',
        ],
        'production' => [
            'encryption-key' => 'ENV_ENCRYPTION_KEY',
        ],
    ],

    'files' => [
        '.env',
        '.env.example',
        '.env.staging',
        '.env.development',
        '.env.production',
        // Users can add as many env files as they want:
    ],

    /* Show values on env diff table */
    'show_values' => false,
];
