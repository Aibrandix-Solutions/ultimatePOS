<?php
/**
 * Fix Invoice 0103 (ID 187) for Mr Yoosuf:
 * - Rs 250 is stuck in advance balance (should have gone to current invoice)
 * - Move Rs 250 from advance to Invoice 0103 payment
 */
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$contact = App\Contact::where('name', 'like', '%Yoosuf%')->first();
if (!$contact) {
    echo "Contact not found\n";
    exit(1);
}

$transaction = App\Transaction::find(187); // Invoice 0103
if (!$transaction || $transaction->contact_id != $contact->id) {
    echo "Transaction 187 not found or doesn't belong to this contact\n";
    exit(1);
}

echo "BEFORE FIX:\n";
echo "  Contact advance balance: {$contact->balance}\n";
echo "  Invoice 0103 total: {$transaction->final_total}\n";
$paid = App\TransactionPayment::where('transaction_id', 187)->where('is_return', 0)->sum('amount');
echo "  Invoice 0103 paid: {$paid}\n";
echo "  Invoice 0103 status: {$transaction->payment_status}\n";

// Amount to transfer from advance balance to invoice 0103
$transfer_amount = 250;

if ($contact->balance < $transfer_amount) {
    echo "ERROR: Advance balance ({$contact->balance}) is less than transfer amount ({$transfer_amount})\n";
    exit(1);
}

DB::beginTransaction();
try {
    $transactionUtil = app(App\Utils\TransactionUtil::class);

    // Create a payment on Invoice 0103
    $ref_count = $transactionUtil->setAndGetReferenceCount('sell_payment', $transaction->business_id);
    $payment_ref_no = $transactionUtil->generateReferenceNumber('sell_payment', $ref_count, $transaction->business_id);

    $payment = App\TransactionPayment::create([
        'transaction_id' => $transaction->id,
        'business_id' => $transaction->business_id,
        'amount' => $transfer_amount,
        'method' => 'cash',
        'is_return' => 0,
        'paid_on' => $transaction->transaction_date,
        'created_by' => 1,
        'payment_for' => $contact->id,
        'payment_ref_no' => $payment_ref_no,
        'note' => 'Corrected: overflow from old dues allocation (was incorrectly added to advance balance)',
    ]);

    // Deduct from advance balance
    $contact->balance -= $transfer_amount;
    $contact->save();

    // Update payment status
    $transactionUtil->updatePaymentStatus($transaction->id, $transaction->final_total);

    // Fire event for accounting
    event(new App\Events\TransactionPaymentAdded($payment, [
        'amount' => $transfer_amount,
        'transaction_type' => $transaction->type,
        'account_id' => null,
    ]));

    DB::commit();

    // Reload
    $contact->refresh();
    $transaction->refresh();
    $paid = App\TransactionPayment::where('transaction_id', 187)->where('is_return', 0)->sum('amount');

    echo "\nAFTER FIX:\n";
    echo "  Contact advance balance: {$contact->balance}\n";
    echo "  Invoice 0103 paid: {$paid}\n";
    echo "  Invoice 0103 status: {$transaction->payment_status}\n";
    $due = app(App\Utils\Util::class)->getContactDue($contact->id, $contact->business_id);
    echo "  Total customer due: {$due}\n";
    echo "\nDONE!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine() . "\n";
}
