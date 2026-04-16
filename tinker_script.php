<?php
$u = App\User::first();
auth()->login($u);
session()->put('user', $u->toArray());
session()->put('business', App\Business::first()->toArray());
$req = new Illuminate\Http\Request();
$req->merge(['product_row' => 1, 'variation_id' => 28, 'location_id' => 1]);
$row = app('App\Http\Controllers\SellPosController')->getProductRow(28, 1);
file_put_contents('row_output.html', $row);
