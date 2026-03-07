<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(Illuminate\Http\Request::capture());

$txns = App\Transaction::where('type', 'sell')
    ->orderBy('id', 'desc')
    ->limit(5)
    ->get(['id', 'status', 'is_suspend', 'created_at']);
foreach ($txns as $t) {
    echo "Txn {$t->id}: status={$t->status}, is_suspend={$t->is_suspend}, created_at={$t->created_at}\n";
    $lines = $t->sell_lines()->get();
    foreach ($lines as $l) {
        echo "  - Line {$l->id}: product_id={$l->product_id}, qty={$l->quantity}\n";
    }
}
