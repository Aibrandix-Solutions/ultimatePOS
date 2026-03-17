<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(Illuminate\Http\Request::capture());

Auth::loginUsingId(1);
$business_id = 1;
$location_id = 1;

// Find a product with enable_stock = 1
$product = App\Product::where('enable_stock', 1)->where('business_id', $business_id)->first();
$variation = $product->variations->first();

// Fake inputs for SellPosController::store
$input = [
    'location_id' => $location_id,
    'contact_id' => 1,
    'transaction_date' => date('Y-m-d H:i:s'),
    'status' => 'draft',
    'is_suspend' => 1,
    'products' => [
        [
            'product_id' => $product->id,
            'variation_id' => $variation->id,
            'quantity' => 1,
            'unit_price_inc_tax' => 10,
            'unit_price' => 10,
            'line_discount_amount' => 0,
            'item_tax' => 0,
            'tax_id' => null,
            'sell_line_note' => '',
            'product_type' => 'single',
            'enable_stock' => 1
        ]
    ],
    'discount_type' => 'fixed',
    'discount_amount' => 0,
    'shipping_charges' => 0,
    'final_total' => 10
];

echo "Simulating creating a NEW Suspended Sale...\n";

try {
    $request = new \Illuminate\Http\Request();
    $request->merge($input);
    $request->setMethod('POST');

    // Call store
    $controller = app('App\Http\Controllers\SellPosController');
    $result = $controller->store($request);

    echo "Result:\n";
    print_r(json_decode($result->getContent(), true) ?? $result);
} catch (\Exception $e) {
    echo "Exception Caught: " . $e->getMessage() . "\n";
}
