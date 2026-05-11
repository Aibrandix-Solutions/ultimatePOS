<span id="view_contact_page"></span>
@php
    $is_supplier = in_array($contact->type, ['supplier', 'both']);
    $is_customer = in_array($contact->type, ['customer', 'both']);

    $opening_balance_due = ($contact->opening_balance ?? 0) - ($contact->opening_balance_paid ?? 0);
    $purchase_due = (($contact->total_purchase ?? 0) - ($contact->purchase_paid ?? 0)) + ($is_supplier ? $opening_balance_due : 0);
    
    $gross_return_due = ($contact->total_sell_return ?? 0) - ($contact->sell_return_paid ?? 0);
    $gross_sell_due = (($contact->total_invoice ?? 0) - ($contact->invoice_received ?? 0)) + ($is_customer ? $opening_balance_due : 0);
    
    $sell_due = max(0, $gross_sell_due - $gross_return_due);
    $sell_return_due = max(0, $gross_return_due - $gross_sell_due);
    
    $has_supplier_due = $is_supplier && $purchase_due > 0;
    $has_customer_due = $is_customer && $sell_due > 0;
    $has_return_due = $is_customer && $sell_return_due > 0;
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="col-sm-3">
            @include('contact.contact_basic_info')
        </div>
        <div class="col-sm-3 mt-56">
            @include('contact.contact_more_info')
        </div>
        @if( $contact->type != 'customer')
            <div class="col-sm-3 mt-56">
                @include('contact.contact_tax_info')
            </div>
        @endif
        {{--
        <div class="col-sm-3 mt-56">
            @include('contact.contact_payment_info') 
        </div>
        @if( $contact->type == 'customer' || $contact->type == 'both')
            <div class="col-sm-3 @if($contact->type != 'both') mt-56 @endif">
                <strong>@lang('lang_v1.total_sell_return')</strong>
                <p class="text-muted">
                    <span class="display_currency" data-currency_symbol="true">
                    {{ $contact->total_sell_return }}</span>
                </p>
                <strong>@lang('lang_v1.total_sell_return_due')</strong>
                <p class="text-muted">
                    <span class="display_currency" data-currency_symbol="true">
                    {{ $contact->total_sell_return -  $contact->total_sell_return_paid }}</span>
                </p>
            </div>
        @endif
        --}}

        <div class="col-sm-12">
            <div class="pull-right tw-m-2">
                @if($has_supplier_due || $has_customer_due)
                    @if($has_supplier_due && $has_customer_due)
                        <div class="btn-group">
                            <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white tw-dw-btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-money-bill-alt" aria-hidden="true"></i> @lang('contact.pay_due_amount') <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                <li>
                                    <a href="{{ action([\App\Http\Controllers\TransactionPaymentController::class, 'getPayContactDue'], [$contact->id]) }}?type=purchase" class="pay_purchase_due">
                                        <i class="fas fa-arrow-circle-down" aria-hidden="true"></i> @lang('lang_v1.pay_to_supplier')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ action([\App\Http\Controllers\TransactionPaymentController::class, 'getPayContactDue'], [$contact->id]) }}?type=sell" class="pay_sale_due">
                                        <i class="fas fa-arrow-circle-up" aria-hidden="true"></i> @lang('lang_v1.receive_from_customer')
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @elseif($has_supplier_due)
                        <a href="{{ action([\App\Http\Controllers\TransactionPaymentController::class, 'getPayContactDue'], [$contact->id]) }}?type=purchase" class="pay_purchase_due tw-dw-btn tw-dw-btn-primary tw-text-white tw-dw-btn-sm">
                            <i class="fas fa-money-bill-alt" aria-hidden="true"></i> @lang('contact.pay_due_amount')
                        </a>
                    @elseif($has_customer_due)
                        <a href="{{ action([\App\Http\Controllers\TransactionPaymentController::class, 'getPayContactDue'], [$contact->id]) }}?type=sell" class="pay_sale_due tw-dw-btn tw-dw-btn-primary tw-text-white tw-dw-btn-sm">
                            <i class="fas fa-money-bill-alt" aria-hidden="true"></i> @lang('contact.pay_due_amount')
                        </a>
                    @elseif($has_return_due && ($sell_return_due > $gross_sell_due))
                        <a href="{{ action([\App\Http\Controllers\TransactionPaymentController::class, 'getPayContactDue'], [$contact->id]) }}?type=sell_return" class="pay_purchase_due tw-dw-btn tw-dw-btn-primary tw-text-white tw-dw-btn-sm">
                            <i class="fas fa-money-bill-alt" aria-hidden="true"></i> @lang('lang_v1.pay_sell_return_due')
                        </a>
                    @endif
                @endif

                <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white tw-dw-btn-sm" data-toggle="modal" data-target="#add_discount_modal">@lang('lang_v1.add_discount')</button>
            </div>
        </div>
    </div>
</div>