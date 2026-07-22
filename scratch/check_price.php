<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Product;
use App\Variation;
use App\ProductBatch;
use App\PurchaseLine;

$products = Product::limit(10)->get();

foreach ($products as $p) {
    echo "Product ID: {$p->id}, Name: {$p->name}, SKU: {$p->sku}\n";
    foreach ($p->variations as $v) {
        echo "  Variation ID: {$v->id}, Sub SKU: {$v->sub_sku}\n";
        echo "  Sell Price Inc Tax: {$v->sell_price_inc_tax}\n";
        $batches = ProductBatch::where('variation_id', $v->id)->get();
        foreach ($batches as $b) {
            echo "    [Batch ID: {$b->id}] Label: {$b->batch_label}, Sell Inc Tax: {$b->sell_price_inc_tax}\n";
        }
    }
}
