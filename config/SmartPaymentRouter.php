<?php

return [
  'processors' => [
    'paystack' => [
      'api_key' => env('PAYSTACK_API_KEY'),
      'secret_key' => env('PAYSTACK_SECRET_KEY'),
      'public_key' => env('PAYSTACK_PULIC_KEY')
    ],
    'flutterwave' => [
      'api_key' => env('FLUTTERWAVE_API_KEY'),
      'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
      'public_key' => env('FLUTTERWAVE_PULIC_KEY')
    ],
    'moniepoint' => [
      'api_key' => env('MONIEPOINT_API_KEY'),
      'secret_key' => env('MONIEPOINT_SECRET_KEY'),
      'public_key' => env('MONIEPOINT_PULIC_KEY')
    ],
  ],
];
