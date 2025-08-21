<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Default TTS provider
  |--------------------------------------------------------------------------
  */
  'default' => env('TTS_DRIVER', 'local'),

  /*
  |--------------------------------------------------------------------------
  | Providers
  |--------------------------------------------------------------------------
  | Each provider has its own config and voices
  */
  'providers' => [
    'local' => [
      'url' => env('TTS_LOCAL_URL', 'http://localhost:5000'),
      'voices' => [
        'fa' => [
          'default' => 'fa_IR-amir-medium',
          'options' => ['fa_IR-amir-medium'],
        ],
        'en' => [
          'default' => 'en_US-lessac-medium',
          'options' => ['en_US-lessac-medium'],
        ],
        'ar' => [
          'default' => "ar_JO-kareem-medium",
          'options' => ["ar_JO-kareem-medium"]
        ]
      ],
      'provider' => App\TTS\Providers\LocalTTSProvider::class
    ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Storage
  |--------------------------------------------------------------------------
  */
  'disk' => 'public',
  'path' => 'tts',
];
