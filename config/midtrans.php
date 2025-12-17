<?php

return [
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'server_key' => env('MIDTRANS_SERVER_KEY'),

    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanbox' => env('MIDTRANS_IS_SANDBOX', true),

    'snap_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? env('MIDTRANS_PRODUCTION_BASE_URL', 'https://app.midtrans.com')
        : env('MIDTRANS_SANDBOX_BASE_URL', 'https://app.sandbox.midtrans.com'),
];
