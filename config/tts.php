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

  /*
  |--------------------------------------------------------------------------
  | Use Browser's default SpeechSynthesis or not
  | 
  | May need change in code if set to true, because TTSService 
  | requires to a wav binary returned from TTSProvider, but browser does not.
  |
  | NOTE: even if set to true, if you try to TTS::generate, the output will
  |       not change, BECAUSE its just a flag for views to switch between 
  |       TTS Facade and JS's SpeechSynthesis
  | --------------------------------------------------------------------------
  */
  'browser' => env('TTS_BROWSER', false)
];
