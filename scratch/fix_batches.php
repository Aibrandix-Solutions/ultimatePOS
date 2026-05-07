<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\ProductBatch;
use App\PurchaseLine;

$b2s = ProductBatch::where('batch_label', 'Batch 2')->get();
foreach($b2s as $row) {
    $b1 = ProductBatch::where('variation_id', $row->variation_id)
        ->where('location_id', $row->location_id)
        ->where('batch_label', 'Batch 1')
        ->first();
        
    // If there is no Batch 1, or if it has 0 stock, we can rename Batch 2 to Batch 1.
    if (!$b1 || ProductBatch::remainingStock($b1->id) <= 0) {
        if ($b1) {
            $b1->delete();
        }
        $row->batch_label = 'Batch 1';
        $row->save();
        
        // Also update the purchase lines to match
        PurchaseLine::where('batch_id', $row->id)->update(['batch_number' => 'Batch 1']);
        echo "Fixed Variation ID: {$row->variation_id}\n";
    }
}
echo "Cleanup complete.\n";
