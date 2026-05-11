<div class="receipt">
    <div class="text-center">
        @if(!empty($business_name))
            <div class="business-name">{{ $business_name }}</div>
        @endif
        <div class="business-info">
            @if(!empty($location_name)) <div>{{ $location_name }}</div> @endif
            @if(!empty($location_address)) <div>{!! $location_address !!}</div> @endif
            @if(!empty($location_contact)) <div>{!! $location_contact !!}</div> @endif
        </div>
    </div>

    <div class="sub-heading centered">PAYMENT RECEIPT</div>

    <div class="info-row">
        <span><strong>@lang('messages.date'):</strong></span>
        <span>{{ $payment_date ?? '' }}</span>
    </div>
    <div class="info-row">
        <span><strong>@lang('purchase.ref_no'):</strong></span>
        <span>{{ $payment_ref_no ?? '' }}</span>
    </div>
    <div class="info-row">
        <span><strong>Customer:</strong></span>
        <span>{{ $contact_name ?? '' }}</span>
    </div>
    @if(!empty($contact_mobile))
        <div class="info-row">
            <span><strong>Mobile:</strong></span>
            <span>{{ $contact_mobile }}</span>
        </div>
    @endif

    <div class="sep-thick"></div>

    <div class="tot-row">
        <span>Previous Due:</span>
        <span class="val">{{ $previous_due ?? 0 }}</span>
    </div>
    <div class="tot-row" style="background: #f4f4f4; padding: 5px 0;">
        <span><strong>Amount Paid:</strong></span>
        <span class="val"><strong>{{ $amount_paid ?? 0 }}</strong></span>
    </div>

    <div class="grand-total">
        <span>TOTAL DUE:</span>
        <span class="val">{{ $total_due ?? 0 }}</span>
    </div>

    @if(!empty($next_due_date))
        <div class="info-row" style="margin-top: 5px;">
            <span>Next Due Date:</span>
            <span>{{ $next_due_date }}</span>
        </div>
    @endif

    <div class="footer-text">
        {{ $footer_text ?? '' }}
        <div style="margin-top: 10px;">Thank You!</div>
    </div>
</div>

<style type="text/css">
    body { font-family: Arial, Helvetica, sans-serif; color: #000; margin: 5px; padding: 5px; background: #fff; }
    .receipt { width: 100%; max-width: 100%; }
    .text-center { text-align: center; }
    .centered { text-align: center; }
    
    .business-name { font-size: 18px; font-weight: 900; text-transform: uppercase; margin-bottom: 2px; }
    .business-info { font-size: 12px; line-height: 1.3; margin-bottom: 5px; }
    
    .sub-heading { font-size: 15px; font-weight: 800; text-transform: uppercase; margin: 10px 0; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 3px 0; }
    
    .info-row { display: flex; justify-content: space-between; font-size: 12px; line-height: 1.4; }
    .tot-row { display: flex; justify-content: space-between; font-size: 13px; padding: 2px 0; }
    .grand-total { display: flex; justify-content: space-between; font-size: 16px; font-weight: 900; border-top: 1px solid #000; border-bottom: 2px solid #000; margin: 5px 0; padding: 5px 0; }
    
    .sep-thick { border-bottom: 2px solid #000; margin: 8px 0; }
    .footer-text { text-align: center; font-size: 12px; margin-top: 15px; }

    @media print {
        body { font-size: 12px; }
        .hidden-print { display: none !important; }
    }
</style>
