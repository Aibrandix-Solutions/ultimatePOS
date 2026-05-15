<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Variation;
use App\PurchaseLine;
use App\TaxRate;

$business_id = 1;

// 1. Fix Variations
$variations = Variation::join('products as p', 'variations.product_id', '=', 'p.id')
    ->join('tax_rates as t', 'p.tax', '=', 't.id')
    ->where('t.calculation_type', 'fixed')
    ->where('p.business_id', $business_id)
    ->select('variations.*', 't.amount as tax_amount')
    ->get();

echo "Checking variations...\n";
foreach ($variations as $v) {
    $expected_dpp_inc_tax = $v->default_purchase_price + $v->tax_amount;
    if (abs($v->dpp_inc_tax - $expected_dpp_inc_tax) > 0.01) {
        echo "Updating Variation ID: {$v->id} (Old: {$v->dpp_inc_tax}, New: {$expected_dpp_inc_tax})\n";
        $v->dpp_inc_tax = $expected_dpp_inc_tax;
        $v->save();
    }
}

// 2. Fix Purchase Lines (Purchase Price & Selling Price)
$purchase_lines = PurchaseLine::join('variations as v', 'purchase_lines.variation_id', '=', 'v.id')
    ->join('tax_rates as t', 'purchase_lines.tax_id', '=', 't.id')
    ->where('t.calculation_type', 'fixed')
    ->select('purchase_lines.*', 't.amount as tax_amount', 't.calculation_type as tax_type', 'v.profit_percent')
    ->get();

echo "Checking purchase lines...\n";
foreach ($purchase_lines as $pl) {
    $needs_save = false;
    
    // Fix Purchase Price
    $expected_inc_tax = $pl->purchase_price + $pl->tax_amount;
    if (abs($pl->purchase_price_inc_tax - $expected_inc_tax) > 0.01) {
        echo "Updating Purchase Price for PL ID: {$pl->id} (Old: {$pl->purchase_price_inc_tax}, New: {$expected_inc_tax})\n";
        $pl->purchase_price_inc_tax = $expected_inc_tax;
        $pl->item_tax = $pl->tax_amount;
        $needs_save = true;
    }
    
    // Fix Selling Price if it is missing or clearly wrong (Batch 1 usually misses it)
    if (empty($pl->batch_selling_price_inc_tax)) {
        echo "Setting missing Batch Selling Price for PL ID: {$pl->id}\n";
        $pl->batch_profit_margin = $pl->profit_percent;
        $pl->batch_selling_price = $pl->purchase_price * (1 + $pl->profit_percent / 100);
        $pl->batch_selling_price_inc_tax = $pl->batch_selling_price + $pl->tax_amount;
        $needs_save = true;
    }
    
    if ($needs_save) {
        $pl->save();
    }
}

echo "Done.\n";
