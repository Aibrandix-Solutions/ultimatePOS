<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/sells', 'GET', [
    'only_pending_shipments' => 'true',
    'columns' => [
        ['data' => 'action', 'name' => 'action'],
        ['data' => 'transaction_date', 'name' => 'transaction_date'],
        ['data' => 'invoice_no', 'name' => 'invoice_no'],
        ['data' => 'conatct_name', 'name' => 'conatct_name'],
        ['data' => 'mobile', 'name' => 'contacts.mobile'],
        ['data' => 'business_location', 'name' => 'bl.name'],
        ['data' => 'shipping_status', 'name' => 'shipping_status'],
        ['data' => 'payment_status', 'name' => 'payment_status'],
        ['data' => 'waiter', 'name' => 'ss.first_name'],
    ],
    'order' => [
        ['column' => 1, 'dir' => 'desc']
    ]
]);
$request->headers->set('X-Requested-With', 'XMLHttpRequest');

// Login a user
\Illuminate\Support\Facades\Auth::loginUsingId(1); // Usually admin ID is 1

$response = $kernel->handle($request);
if ($response->exception) {
    echo "EXCEPTION OCCURRED: \n";
    echo $response->exception->getMessage() . "\n";
    echo $response->exception->getTraceAsString();
} else {
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Response Content: \n" . substr($response->getContent(), 0, 1000) . "\n...";
}
