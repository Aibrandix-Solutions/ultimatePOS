<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$contact = \App\Contact::where('contact_id', 'CO0031')->first();
if ($contact) {
    echo "Full Contact Data:\n";
    print_r($contact->toArray());
} else {
    echo "Contact not found.\n";
}
