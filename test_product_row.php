<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = App\User::first();
auth()->login($user);
request()->session()->put('user', $user->toArray());
request()->session()->put('business', App\Business::first()->toArray());

$response = $kernel->handle(
    $request = Illuminate\Http\Request::create(
        '/sells/pos/get_product_row/28/1',
        'GET',
        [
            'product_row' => 1,
            'variation_id' => 28,
            'location_id' => 1,
            'customer_id' => 1
        ]
    )
);
echo $response->getContent();
