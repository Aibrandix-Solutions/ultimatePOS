<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = \App\Product::find(12);

if ($product) {
    echo "Product: {$product->name}\n";
    echo "Discount saved in DB: " . ($product->discount ?? 'NULL') . "\n";
    echo "\nIf discount is NULL, you need to edit the product and add a discount value.\n";
} else {
    echo "Product not found\n";
}
