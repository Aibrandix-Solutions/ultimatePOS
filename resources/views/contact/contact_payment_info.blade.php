@if( $contact->type == 'supplier' || $contact->type == 'both')
    <strong>@lang('report.total_purchase')</strong>
    <p class="text-muted">
    <span class="display_currency" data-currency_symbol="true">
    {{ $contact->total_purchase }}</span>
    </p>
    <strong>@lang('contact.total_purchase_paid')</strong>
    <p class="text-muted">
    <span class="display_currency" data-currency_symbol="true">
    {{ $contact->purchase_paid }}</span>
    </p>
    <strong>@lang('contact.total_purchase_due')</strong>
    <p class="text-muted">
    <span class="display_currency" data-currency_symbol="true">
    {{ $contact->total_purchase - $contact->purchase_paid }}</span>
    </p>
@endif
@if( $contact->type == 'customer' || $contact->type == 'both')
    @php
        $gross_return_due = ($contact->total_sell_return ?? 0) - ($contact->sell_return_paid ?? 0);
        $opening_balance_due = ($contact->opening_balance ?? 0) - ($contact->opening_balance_paid ?? 0);
        $gross_sale_due = ($contact->total_invoice ?? 0) - ($contact->invoice_received ?? 0) + (in_array($contact->type, ['customer', 'both']) ? $opening_balance_due : 0);
        $net_sale_due = max(0, $gross_sale_due - $gross_return_due);
        $net_return_due = max(0, $gross_return_due - $gross_sale_due);
    @endphp
    <strong>@lang('report.total_sell')</strong>
    <p class="text-muted">
    <span class="display_currency" data-currency_symbol="true">
    {{ $contact->total_invoice }}</span>
    </p>
    <strong>@lang('contact.total_sale_paid')</strong>
    <p class="text-muted">
    <span class="display_currency" data-currency_symbol="true">
    {{ $contact->invoice_received }}</span>
    </p>
    <strong>@lang('contact.total_sale_due')</strong>
    <p class="text-muted">
    <span class="display_currency" data-currency_symbol="true">
    {{ $net_sale_due }}</span>
    </p>
    @if($net_return_due > 0)
        <strong>@lang('lang_v1.total_sell_return_due')</strong>
        <p class="text-muted">
        <span class="display_currency" data-currency_symbol="true">
        {{ $net_return_due }}</span>
        </p>
    @endif
@endif
@if(!empty($contact->opening_balance) && $contact->opening_balance != '0.00')
    <strong>@lang('lang_v1.opening_balance')</strong>
    <p class="text-muted">
    <span class="display_currency" data-currency_symbol="true">
    {{ $contact->opening_balance }}</span>
    </p>
    <strong>@lang('lang_v1.opening_balance_due')</strong>
    <p class="text-muted">
    <span class="display_currency" data-currency_symbol="true">
    {{ $contact->opening_balance - $contact->opening_balance_paid }}</span>
    </p>
@endif
<strong>@lang('lang_v1.advance_balance')</strong>
<p class="text-muted">
    <span class="display_currency" data-currency_symbol="true">
    {{ $contact->balance }}</span>
</p>