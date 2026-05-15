@if(isset($contact))
    @php
        $is_supplier = ($contact->type == 'supplier' || $contact->type == 'both');
        $is_customer = ($contact->type == 'customer' || $contact->type == 'both');

        $opening_balance_due = ($contact->opening_balance ?? 0) - ($contact->opening_balance_paid ?? 0);
        $ledger_discount = $contact->total_ledger_discount ?? 0;

        if ($is_supplier) {
            $total_due = (($contact->total_purchase ?? 0) - ($contact->purchase_paid ?? 0) - $ledger_discount - ($contact->total_purchase_return ?? 0) + ($contact->purchase_return_paid ?? 0)) + $opening_balance_due;
            $pending_cheques = $contact->purchase_pending_cheques ?? 0;
            $due_label = 'Total Supplier Due';
            $payable_label = 'Due Payable';
        } else {
            $total_due = (($contact->total_invoice ?? 0) - ($contact->invoice_received ?? 0) - $ledger_discount - ($contact->total_sell_return ?? 0) + ($contact->sell_return_paid ?? 0)) + $opening_balance_due;
            $pending_cheques = $contact->invoice_pending_cheques ?? 0;
            $due_label = 'Total Customer Due';
            $payable_label = 'Due Receivable';
        }

        // Customer invoice_received already counts pending cheques toward due.
        // Supplier purchase_paid uses cleared-only, so subtract pending cheques there.
        $due_payable_receivable = $is_supplier ? max(0, $total_due - $pending_cheques) : max(0, $total_due);
    @endphp

    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-12">
            <div class="box box-solid" style="border-top: 3px solid #3c8dbc;">
                <div class="box-body" style="padding: 15px;">
                    <h4 style="margin-top: 0; margin-bottom: 15px; color: #333; font-weight: 600; font-size: 16px;">
                        <i class="fa fa-chart-line"></i> Due Summary
                        @if($is_customer && $total_due > 0)
                            <button type="button" class="btn btn-success btn-sm pull-right" id="receive_cheque_btn"
                                data-contact-id="{{ $contact->id }}" style="margin-top: -3px;">
                                <i class="fa fa-plus"></i> Receive Cheque
                            </button>
                        @endif
                    </h4>
                    <div class="row">
                        <!-- Total Due Card -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 6px; padding: 15px; color: white; margin-bottom: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <div
                                    style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                    <span
                                        style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">{{ $due_label }}</span>
                                    <i class="fa fa-receipt" style="font-size: 18px; opacity: 100%;"></i>
                                </div>
                                <div style="font-size: 24px; font-weight: 700; margin-bottom: 3px;">
                                    <span class="display_currency"
                                        data-currency_symbol="true">{{ number_format($total_due, 2) }}</span>
                                </div>
                                <div style="font-size: 10px; opacity: 0.8;">Total outstanding amount</div>
                            </div>
                        </div>

                        <!-- Pending Cheques Card -->
                        @if($pending_cheques > 0)
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div
                                    style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 6px; padding: 15px; color: white; margin-bottom: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <div
                                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                        <span
                                            style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">Pending
                                            Cheques</span>
                                        <i class="fa fa-clock" style="font-size: 18px; opacity: 100%;"></i>
                                    </div>
                                    <div style="font-size: 24px; font-weight: 700; margin-bottom: 3px;">
                                        <span class="display_currency"
                                            data-currency_symbol="true">{{ number_format($pending_cheques, 2) }}</span>
                                    </div>
                                    <div style="font-size: 10px; opacity: 0.8;">Awaiting clearance</div>
                                </div>
                            </div>
                        @endif

                        <!-- Due Payable/Receivable Card -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div
                                style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 6px; padding: 15px; color: white; margin-bottom: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <div
                                    style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                    <span
                                        style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">{{ $payable_label }}</span>
                                    <i class="fa fa-hand-holding-usd" style="font-size: 18px; opacity: 100%;"></i>
                                </div>
                                <div style="font-size: 24px; font-weight: 700; margin-bottom: 3px;">
                                    <span class="display_currency"
                                        data-currency_symbol="true">{{ number_format($due_payable_receivable, 2) }}</span>
                                </div>
                                <div style="font-size: 11px; opacity: 0.8;">
                                    @if($is_supplier)
                                        Amount to pay now
                                    @else
                                        Amount to receive
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<table class="table table-bordered" id="contact_cheques_table">
    <thead>
        <tr>
            <th>@lang('lang_v1.paid_on')</th>
            <th>@lang('purchase.ref_no')</th>
            <th>@lang('sale.amount')</th>
            <th>@lang('lang_v1.cheque_no')</th>
            <th>@lang('lang_v1.cheque_issue_date')</th>
            <th>@lang('lang_v1.cheque_passing_date')</th>
            <th>@lang('lang_v1.cheque_status')</th>
            <th>@lang('lang_v1.cheque_bank_name')</th>
            <th>@lang('account.payment_for')</th>
            <th>@lang('messages.action')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($payments as $payment)
            <tr>
                <td>{{@format_datetime($payment->paid_on)}}</td>
                <td>{{$payment->payment_ref_no}}</td>
                <td>
                    <span class="display_currency" data-orig-value="{{$payment->amount}}"
                        data-currency_symbol="true">{{$payment->amount}}</span>
                </td>
                <td>{{$payment->cheque_number}}</td>
                <td>
                    @if(!empty($payment->cheque_issue_date))
                        {{@format_datetime($payment->cheque_issue_date)}}
                    @endif
                </td>
                <td>
                    @if(!empty($payment->cheque_passing_date))
                        {{@format_datetime($payment->cheque_passing_date)}}
                    @endif
                </td>
                <td>
                    @if(!empty($payment->cheque_status))
                        @lang('lang_v1.' . $payment->cheque_status)
                    @elseif($payment->method == 'cheque')
                        @lang('lang_v1.pending')
                    @endif
                </td>
                <td>{{$payment->cheque_bank_name}}</td>
                <td>
                    @php
                        $transaction_type = $payment->transaction_type;
                        $transaction_id = $payment->transaction_id;
                        $invoice_no = $payment->invoice_no;
                        $return_parent_id = $payment->return_parent_id;
                        $ref_no = $payment->ref_no;
                    @endphp
                    @if($transaction_type == 'sell')
                        <a data-href="{{action([\App\Http\Controllers\SellController::class, 'show'], [$transaction_id])}}"
                            href="#" data-container=".view_modal" class="btn-modal">{{$invoice_no}}</a>
                        <br><small>({{__('sale.sale')}})</small>
                    @elseif($transaction_type == 'sell_return')
                        <a data-href="{{action([\App\Http\Controllers\SellReturnController::class, 'show'], [$return_parent_id])}}"
                            href="#" data-container=".view_modal" class="btn-modal">{{$invoice_no}}</a>
                        <br><small>({{__('lang_v1.sell_return')}})</small>
                    @elseif($transaction_type == 'purchase_return')
                        <a data-href="{{action([\App\Http\Controllers\PurchaseReturnController::class, 'show'], [$return_parent_id])}}"
                            href="#" data-container=".view_modal" class="btn-modal">{{$ref_no}}</a>
                        <br><small>({{__('lang_v1.purchase_return')}})</small>
                    @elseif ($transaction_type == 'purchase')
                        <a data-href="{{action([\App\Http\Controllers\PurchaseController::class, 'show'], [$transaction_id])}}"
                            href="#" data-container=".view_modal" class="btn-modal">{{$ref_no}}</a>
                        <br><small>({{__('lang_v1.purchase')}})</small>
                    @else
                        @if(!empty($transaction_id))
                            {{$ref_no}}
                            <br><small>({{__('lang_v1.' . $transaction_type)}})</small>
                        @endif
                    @endif
                </td>
                <td>
                    <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary btn-modal"
                        data-href="{{action([\App\Http\Controllers\TransactionPaymentController::class, 'viewPayment'], [$payment->id])}}"
                        data-container=".view_modal"><i class="fas fa-eye"></i>{{__('messages.view')}}</button>
                    @php
                        $can_edit_purchase = auth()->user()->can('edit_purchase_payment');
                        $can_edit_sell = auth()->user()->can('edit_sell_payment');
                        $can_edit_this = false;

                        if (in_array($transaction_type, ['purchase', 'purchase_return'], true)) {
                            $can_edit_this = $can_edit_purchase;
                        } elseif (in_array($transaction_type, ['sell', 'sell_return'], true)) {
                            $can_edit_this = $can_edit_sell;
                        } else {
                            //Pay-due cheques might not have a transaction_id/type; allow if user can edit either.
                            $can_edit_this = ($can_edit_purchase || $can_edit_sell);
                        }
                    @endphp

                    @if($can_edit_this)
                        <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-info btn-modal"
                            data-href="{{action([\App\Http\Controllers\TransactionPaymentController::class, 'edit'], [$payment->id])}}"
                            data-container=".view_modal"><i class="fas fa-edit"></i> {{__('messages.edit')}}</button>

                        @if($payment->method == 'cheque')
                            @if($payment->cheque_status !== 'cleared')
                                <button type="button"
                                    class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-success js-update-cheque-status"
                                    data-payment-id="{{$payment->id}}" data-status="cleared"
                                    data-href="{{ url('/payments/update-cheque-status/' . $payment->id) }}">
                                    <i class="fas fa-check"></i> {{ __('lang_v1.mark_as_cleared') }}
                                </button>
                            @endif

                            @if($payment->cheque_status !== 'pending')
                                <button type="button"
                                    class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-warning js-update-cheque-status"
                                    data-payment-id="{{$payment->id}}" data-status="pending"
                                    data-href="{{ url('/payments/update-cheque-status/' . $payment->id) }}">
                                    <i class="fas fa-undo"></i> {{ __('lang_v1.mark_as_pending') }}
                                </button>
                            @endif

                            @if($payment->cheque_status !== 'bounced')
                                <button type="button"
                                    class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-danger js-update-cheque-status"
                                    data-payment-id="{{$payment->id}}" data-status="bounced"
                                    data-href="{{ url('/payments/update-cheque-status/' . $payment->id) }}">
                                    <i class="fas fa-times" style="font-size: 12px;"></i> {{ __('lang_v1.mark_as_bounced') }}
                                </button>
                            @endif
                        @endif
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center">@lang('purchase.no_records_found')</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="text-right" style="width: 100%;" id="contact_cheques_pagination">{{ $payments->links() }}</div>

<script type="text/javascript">
    $(document).ready(function () {
        function init_contact_cheque_payment_modal($modal) {
            if (!$modal || !$modal.length) {
                return;
            }

            // Re-init plugins for dynamically injected modal content
            if (typeof __currency_convert_recursively === 'function') {
                __currency_convert_recursively($modal);
            }

            // Paid on
            if ($modal.find('#paid_on').length) {
                $modal.find('#paid_on').datetimepicker({
                    format: moment_date_format + ' ' + moment_time_format,
                    ignoreReadonly: true,
                });
            }

            // Cheque issue/passing dates (and any other datetime fields)
            if ($modal.find('.datetimepicker').length) {
                $modal.find('.datetimepicker').datetimepicker({
                    format: moment_date_format + ' ' + moment_time_format,
                    ignoreReadonly: true,
                });
            }

            if (typeof set_default_payment_account === 'function') {
                set_default_payment_account();
            }

            if ($modal.find('form#transaction_payment_add_form').length && $.fn.validate) {
                $modal.find('form#transaction_payment_add_form').validate();
            }
        }

        // Handle Receive Cheque button click
        $(document).on('click', '#receive_cheque_btn', function (e) {
            e.preventDefault();
            var contact_id = $(this).data('contact-id');

            $.ajax({
                url: '/contacts/unpaid-invoices/' + contact_id,
                method: 'GET',
                dataType: 'html',
                success: function (result) {
                    $('.view_modal')
                        .html(result)
                        .modal('show');

                    init_contact_cheque_payment_modal($('.view_modal'));
                }
            });
        });

        // Handle Add Payment button click from invoice selection modal
        $(document).on('click', '.add_payment_btn', function (e) {
            e.preventDefault();
            var url = $(this).data('href');

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function (result) {
                    if (result.status == 'due' && result.view) {
                        // Replace the current modal content with payment form
                        $('.view_modal')
                            .html(result.view)
                            .modal('show');

                        init_contact_cheque_payment_modal($('.view_modal'));
                    } else if (result.status == 'paid') {
                        toastr.error(result.msg);
                        $('.view_modal').modal('hide');
                    }
                }
            });
        });

        // Refresh cheques tab after adding payment
        $(document).on('submit', '#transaction_payment_add_form', function (e) {
            var form = $(this);
            setTimeout(function () {
                // Close both modals
                $('.modal').modal('hide');
                // Reload cheques tab
                var contact_id = $('#receive_cheque_btn').data('contact-id');
                if (contact_id) {
                    $('#contact_cheques_tab').load('/contacts/cheques/' + contact_id);
                }
            }, 1500);
        });
    });
</script>