<div class="ticket">
    <div class="text-box centered">
        <div class="headings">
            {{ $business_name ?? '' }}
        </div>
        @if(!empty($location_name))
            <div class="sub-headings">{{ $location_name }}</div>
        @endif
        @if(!empty($location_address))
            <div class="f-12">{!! $location_address !!}</div>
        @endif
        @if(!empty($location_contact))
            <div class="f-12">{!! $location_contact !!}</div>
        @endif
        @if(!empty($location_email))
            <div class="f-12">{{ $location_email }}</div>
        @endif
    </div>

    <div class="border-top"></div>

    <div class="textbox-info">
        <p class="f-left"><strong>@lang('messages.date'):</strong></p>
        <p class="f-right">{{ $payment_date ?? '' }}</p>
    </div>
    <div class="textbox-info">
        <p class="f-left"><strong>@lang('purchase.ref_no'):</strong></p>
        <p class="f-right">{{ $payment_ref_no ?? '' }}</p>
    </div>
    <div class="textbox-info">
        <p class="f-left"><strong>@lang('contact.customer'):</strong></p>
        <p class="f-right">{{ $contact_name ?? '' }}</p>
    </div>
    @if(!empty($contact_mobile))
        <div class="textbox-info">
            <p class="f-left"><strong>@lang('contact.mobile'):</strong></p>
            <p class="f-right">{{ $contact_mobile }}</p>
        </div>
    @endif
    @if(!empty($cashier_name))
        <div class="textbox-info">
            <p class="f-left"><strong>Cashier:</strong></p>
            <p class="f-right">{{ $cashier_name }}</p>
        </div>
    @endif

    <div class="border-top"></div>

    <div class="centered sub-headings" style="margin: 6px 0;">PAYMENT RECEIPT</div>

    <table class="table-info">
        <tbody>
            <tr>
                <td class="text-left">Previous Due</td>
                <td class="text-right">
                    <span class="display_currency" data-currency_symbol="true">{{ $previous_due ?? 0 }}</span>
                </td>
            </tr>
            <tr>
                <td class="text-left">Amount Paid</td>
                <td class="text-right">
                    <span class="display_currency" data-currency_symbol="true">{{ $amount_paid ?? 0 }}</span>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="border-top"></div>

    <div class="textbox-info total">
        <p class="f-left"><strong>Total Due</strong></p>
        <p class="f-right"><strong><span class="display_currency" data-currency_symbol="true">{{ $total_due ?? 0 }}</span></strong></p>
    </div>

    @if(!empty($next_due_date))
        <div class="border-top"></div>
        <div class="textbox-info">
            <p class="f-left"><strong>Payment Due Date:</strong></p>
            <p class="f-right">{{ $next_due_date }}</p>
        </div>
    @endif

    <div class="border-top"></div>

    <div class="centered f-12" style="margin-top: 8px;">
        {{ $footer_text ?? '' }}
    </div>
</div>

<style type="text/css">
    body { color: #000; }

    .ticket { width: 100%; max-width: 100%; }
    .centered { text-align: center; }
    .text-left { text-align: left; }
    .text-right { text-align: right; }

    .headings { font-size: 16px; font-weight: 700; text-transform: uppercase; }
    .sub-headings { font-size: 15px; font-weight: 700; }

    .f-12 { font-size: 12px; }

    .border-top { border-top: 1px solid #242424; margin: 6px 0; }

    .textbox-info { width: 100%; clear: both; }
    .textbox-info:after { content: ""; display: table; clear: both; }
    .textbox-info .f-left { float: left; width: 50%; margin: 0; }
    .textbox-info .f-right { float: right; width: 50%; margin: 0; text-align: right; }

    .table-info { width: 100%; border-collapse: collapse; }
    .table-info td { padding: 2px 0; }

    @media print {
        * { font-size: 12px; font-family: 'Times New Roman'; word-break: break-word; }
        .hidden-print, .hidden-print * { display: none !important; }
    }
</style>
