<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Variation;
use App\Product;
use App\TaxRate;

$business_id = 1; // Change if needed

$variations = Variation::join('products as p', 'variations.product_id', '=', 'p.id')
    ->join('tax_rates as t', 'p.tax', '=', 't.id')
    ->where('t.calculation_type', 'fixed')
    ->where('p.business_id', $business_id)
    ->select('variations.*', 't.amount as tax_amount')
    ->get();

echo "Found " . count($variations) . " variations with fixed taxes.\n";

foreach ($variations as $v) {
    $expected_dpp_inc_tax = $v->default_purchase_price + $v->tax_amount;
    if (abs($v->dpp_inc_tax - $expected_dpp_inc_tax) > 0.01) {
        echo "Updating Variation ID: {$v->id} (Product: {$v->product_id})\n";
        echo "Old DPP Inc Tax: {$v->dpp_inc_tax}, New: {$expected_dpp_inc_tax}\n";
        
        $v->dpp_inc_tax = $expected_dpp_inc_tax;
        
        // Also fix selling price if needed
        if ($v->product_variation_id) {
            $product = Product::find($v->product_id);
            if ($product->tax_type == 'inclusive') {
                // If it was calculated as percentage, default_sell_price is wrong
                $v->default_sell_price = max($v->sell_price_inc_tax - $v->tax_amount, 0);
            } else {
                $v->sell_price_inc_tax = $v->default_sell_price + $v->tax_amount;
            }
        }
        
        $v->save();
    }
}

echo "Done.\n";
