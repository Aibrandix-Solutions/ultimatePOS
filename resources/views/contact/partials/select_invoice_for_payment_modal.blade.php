<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">
                <i class="fa fa-money-check-alt"></i> Select Invoice for Cheque Payment
            </h4>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="well well-sm">
                        <strong>Customer:</strong> {{ $contact->name }}<br>
                        <strong>Total Due:</strong> <span class="display_currency"
                            data-currency_symbol="true">{{ ($contact->total_invoice ?? 0) - ($contact->invoice_received ?? 0) }}</span>
                    </div>
                </div>
            </div>

            @if($unpaid_invoices->count() > 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Remaining</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unpaid_invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->invoice_no }}</td>
                                            <td>{{ @format_date($invoice->transaction_date) }}</td>
                                            <td>
                                                <span class="display_currency"
                                                    data-currency_symbol="true">{{ $invoice->final_total }}</span>
                                            </td>
                                            <td>
                                                <span class="display_currency"
                                                    data-currency_symbol="true">{{ $invoice->remaining_amount }}</span>
                                            </td>
                                            <td>
                                                <button
                                                    data-href="{{ action([\App\Http\Controllers\TransactionPaymentController::class, 'addPayment'], [$invoice->id]) }}"
                                                    class="btn btn-xs btn-success add_payment_btn"
                                                    data-invoice-id="{{ $invoice->id }}">
                                                    <i class="fa fa-plus"></i> Add Payment
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p class="text-muted">
                            <i class="fa fa-check-circle fa-3x"></i><br><br>
                            No unpaid invoices found for this customer.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <div class="modal-footer">
            <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>