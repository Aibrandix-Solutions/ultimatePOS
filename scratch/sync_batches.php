<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Variation;
use App\PurchaseLine;
use App\ProductBatch;

$business_id = 1;

echo "Syncing product_batches with purchase_lines...\n";

// Get all purchase lines that have a batch_id and a selling price
$purchase_lines = PurchaseLine::whereNotNull('batch_id')
    ->whereNotNull('batch_selling_price_inc_tax')
    ->get();

foreach ($purchase_lines as $pl) {
    ProductBatch::syncSellPricesFromPurchaseLine($pl);
}

echo "Done.\n";
