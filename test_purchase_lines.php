<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(Illuminate\Http\Request::capture());

$product = App\Product::where('sku', '4792090100635')->first();
$variation = $product->variations->first();

$pls = App\PurchaseLine::where('product_id', $product->id)->get();
echo "Total Purchase Lines: \n";
foreach ($pls as $pl) {
    echo "ID: {$pl->id}, Transaction: {$pl->transaction_id}, Qty: {$pl->quantity}, Qty Sold: {$pl->quantity_sold}\n";
}

$vlds = App\VariationLocationDetails::where('variation_id', $variation->id)->get();
echo "Total VLDs: \n";
foreach ($vlds as $vld) {
    echo "Location: {$vld->location_id}, Qty Available: {$vld->qty_available}\n";
}
