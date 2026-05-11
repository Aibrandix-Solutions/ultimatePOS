<?php
include 'vendor/autoload.php';
$app = include_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$id = 29;
$contact = DB::table('contacts')
    ->leftJoin('transactions as t', 'contacts.id', '=', 't.contact_id')
    ->where('contacts.id', $id)
    ->select(
        'contacts.name',
        DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
        DB::raw("SUM(IF(t.type = 'sell_return', final_total, 0)) as total_sell_return")
    )
    ->groupBy('contacts.id')
    ->first();

print_r($contact);

$transactions = DB::table('transactions')
    ->where('contact_id', $id)
    ->select('id', 'type', 'final_total', 'return_parent_id')
    ->get();

print_r($transactions);
