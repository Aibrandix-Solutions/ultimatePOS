<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Find the product
$product = \App\Product::where('name', 'like', '%iphone%16%pro%')->first();

if (!$product) {
    echo "Product 'iPhone 16 Pro' not found in database\n";
    exit;
}

echo "Product Found:\n";
echo "  ID: {$product->id}\n";
echo "  Name: {$product->name}\n";
echo "  SKU: {$product->sku}\n";
echo "  Enable Stock: {$product->enable_stock}\n";
echo "\n";

// Get variations
$variations = \App\Variation::where('product_id', $product->id)->get();

if ($variations->isEmpty()) {
    echo "No variations found for this product\n";
    exit;
}

echo "Variations:\n";
foreach ($variations as $variation) {
    echo "  Variation ID: {$variation->id}\n";

    // Get stock details for each location
    $stocks = \App\VariationLocationDetails::where('variation_id', $variation->id)->get();

    if ($stocks->isEmpty()) {
        echo "    No stock records found\n";
    } else {
        foreach ($stocks as $stock) {
            echo "    Location {$stock->location_id}: qty_available = {$stock->qty_available}\n";
        }
    }
}
