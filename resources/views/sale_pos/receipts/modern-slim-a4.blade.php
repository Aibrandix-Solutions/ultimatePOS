<!-- Modern Slim A4 - Designed for A4 PDF / Browser Print -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice-{{$receipt_details->invoice_no}}</title>
    <style type="text/css">
    /* ===== Base Reset ===== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    @page {
        size: A4;
        margin: 12mm 12mm 12mm 12mm;
    }
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
        color: #000 !important;
        line-height: 1.4;
        background: #fff;
        margin: 0;
        padding: 0;
    }
    a, a:visited, a:hover, a:active {
        color: #000 !important;
        text-decoration: none !important;
    }

    /* ===== Page Container ===== */
    .receipt {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
        padding: 0;
        color: #000 !important;
    }
    .receipt * {
        color: #000 !important;
    }

    /* ===== Text Alignment ===== */
    .text-center { text-align: center; }
    .text-right  { text-align: right; }
    .text-left   { text-align: left; }
    .v-top       { vertical-align: top; }

    /* ===== Logo ===== */
    .receipt-logo {
        max-height: 90px;
        width: auto;
        margin: 0 auto 4px auto;
        display: block;
    }

    /* ===== Header ===== */
    .header-tagline {
        font-size: 12px;
        font-style: italic;
        margin-bottom: 2px;
    }
    .business-name {
        font-size: 20px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 2px;
        letter-spacing: 0.5px;
    }
    .business-info {
        font-size: 12px;
        line-height: 1.4;
        margin-bottom: 2px;
    }
    .sub-heading {
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase;
        margin: 4px 0 2px 0;
        letter-spacing: 1px;
    }

    /* ===== Separators ===== */
    .sep {
        border: none;
        border-bottom: 1px solid #000;
        margin: 4px 0;
    }
    .sep-thick {
        border: none;
        border-bottom: 2px solid #000;
        margin: 4px 0;
    }
    .sep-dashed {
        border: none;
        border-bottom: 1px dashed #999;
        margin: 3px 0;
    }

    /* ===== Invoice Info Header ===== */
    .inv-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        font-size: 12px;
        line-height: 1.5;
        margin-bottom: 4px;
    }
    .inv-header .left { flex: 1; padding-right: 8px; }
    .inv-header .right { text-align: right; flex-shrink: 0; }
    .inv-header strong { font-weight: 700; }

    /* ===== Product Table ===== */
    .ptable {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        table-layout: auto;
    }
    .ptable thead {
        border-bottom: 2px solid #000;
    }
    .ptable th {
        font-weight: 700;
        padding: 4px 6px;
        font-size: 12px;
        text-align: left;
        border-bottom: 2px solid #000;
    }
    .ptable th.r { text-align: right; }
    .ptable td {
        padding: 4px 6px;
        vertical-align: top;
        font-size: 12px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    .ptable tbody tr {
        border-bottom: none;
    }
    .detail-row td {
        font-size: 11px;
        padding: 2px 6px;
        border-bottom: 1px dashed #ccc;
        color: #222 !important;
    }
    .ptable tbody tr:last-child .detail-row td,
    .detail-row:last-of-type td {
        border-bottom: none;
    }
    .modifier-row td {
        font-size: 11px;
        color: #555 !important;
        padding: 2px 6px;
    }
    .item-name {
        font-weight: 600;
        font-size: 12px;
        word-wrap: break-word;
    }
    .item-sub {
        font-size: 11px;
        color: #555 !important;
        margin-top: 1px;
    }

    .c-sno  { width: 4%;  text-align: left; vertical-align: top; }
    .c-name { width: 40%; text-align: left; }
    .c-type { width: 12%; text-align: left; }
    .c-qty  { width: 8%;  text-align: left; white-space: nowrap; }
    .c-uprc { width: 16%; text-align: left; white-space: nowrap; }
    .c-disc { width: 10%; text-align: left; white-space: nowrap; }
    .c-tot  { width: 14%; text-align: left; white-space: nowrap; font-weight: 700; }

    .ptable th.c-qty,
    .ptable th.c-uprc,
    .ptable th.c-disc,
    .ptable th.c-tot,
    .ptable td.c-qty,
    .ptable td.c-uprc,
    .ptable td.c-disc,
    .ptable td.c-tot {
        word-wrap: normal;
        overflow-wrap: normal;
        word-break: keep-all;
    }

    /* ===== Two-column bottom layout ===== */
    .bottom-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-top: 4px;
    }

    /* LEFT: Installment / Due date panel */
    .installment-panel {
        flex: 1;
        min-width: 0;
        padding-right: 8px;
        font-size: 12px;
        line-height: 1.5;
    }
    .installment-panel .panel-title {
        font-size: 12px;
        font-weight: 700;
        border-bottom: 1px solid #000;
        padding-bottom: 2px;
        margin-bottom: 4px;
    }
    .inst-row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        line-height: 1.5;
        gap: 8px;
    }
    .inst-row .lbl { font-weight: 600; }
    .inst-row .val { text-align: right; white-space: nowrap; }

    /* RIGHT: Financial summary panel */
    .financial-panel {
        flex: 0 0 42%;
        font-size: 12px;
        line-height: 1.5;
    }
    .fin-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 13px;
        line-height: 1.5;
        padding: 1px 0;
    }
    .fin-row .lbl { font-weight: 600; }
    .fin-row .val { text-align: right; white-space: nowrap; font-weight: 600; }

    .fin-headline {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 14px;
        font-weight: 900;
        text-transform: uppercase;
        border-bottom: 1px solid #000;
        padding-bottom: 2px;
        margin-bottom: 4px;
    }
    .fin-headline .val {
        font-size: 14px;
        text-align: right;
        white-space: nowrap;
    }

    .total-due-final {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 17px;
        font-weight: 900;
        border-top: 2px solid #000;
        border-bottom: 2px solid #000;
        padding: 4px 0;
        margin-top: 4px;
    }

    .grand-total {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 17px;
        font-weight: 900;
        border-top: 2px solid #000;
        border-bottom: 2px solid #000;
        padding: 4px 0;
        margin-top: 4px;
    }

    .total-due-box {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 16px;
        font-weight: 800;
        border-top: 2px solid #000;
        border-bottom: 2px solid #000;
        padding: 4px 0;
        margin-top: 4px;
    }

    /* ===== Totals above two-column (shipping/discount/tax) ===== */
    .tot-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 13px;
        line-height: 1.5;
    }
    .tot-row .lbl { font-weight: 600; }
    .tot-row .val { text-align: right; white-space: nowrap; font-weight: 600; }

    /* ===== Notes / Footer ===== */
    .notes-section {
        font-size: 12px;
        line-height: 1.4;
        margin: 6px 0;
        padding: 4px 0;
        border-top: 1px solid #ccc;
    }
    .footer-text {
        text-align: center;
        font-size: 12px;
        margin: 6px 0;
        line-height: 1.4;
    }
    .thank-you {
        text-align: center;
        font-size: 12px;
        color: #666 !important;
        margin-top: 6px;
        padding-top: 4px;
        border-top: 1px solid #ccc;
    }

    /* ===== Print ===== */
    @media print {
        body { margin: 0; padding: 0; }
        .receipt { width: 100%; }
        .hidden-print,
        .hidden-print * { display: none !important; }
    }
    </style>
</head>
<body>
    <div class="receipt">

        @php
            $isWalkInCustomer = !empty($receipt_details->is_walk_in_customer);
            $showDueBreakdown = !empty($receipt_details->receipt_show_due_breakdown) && !$isWalkInCustomer;
            $showDueBox       = !$isWalkInCustomer && !empty($receipt_details->total_due) && !empty($receipt_details->total_due_label);
            $hasInstallments  = !empty($receipt_details->receipt_due_date) || !empty($receipt_details->installment_due_dates);
        @endphp

        {{-- ========== HEADER ========== --}}
        @if(empty($receipt_details->letter_head))
            @if(!empty($receipt_details->logo))
                <div class="text-center">
                    <img class="receipt-logo" src="{{$receipt_details->logo}}" alt="Logo">
                </div>
            @endif

            @if(!empty($receipt_details->header_text))
                <div class="text-center header-tagline">
                    {!! $receipt_details->header_text !!}
                </div>
            @endif

            <div class="text-center business-info">
                @if(!empty($receipt_details->display_name))
                    <div class="business-name">{{$receipt_details->display_name}}</div>
                @endif
                @if(!empty($receipt_details->address))
                    <div>{!! $receipt_details->address !!}</div>
                @endif
                @if(!empty($receipt_details->contact))
                    <div>{!! $receipt_details->contact !!}</div>
                @endif
                @if(!empty($receipt_details->website))
                    <div>{{ $receipt_details->website }}</div>
                @endif
                @if(!empty($receipt_details->location_custom_fields))
                    <div>{{ $receipt_details->location_custom_fields }}</div>
                @endif
                @if(!empty($receipt_details->sub_heading_line1)) <div>{{ $receipt_details->sub_heading_line1 }}</div> @endif
                @if(!empty($receipt_details->sub_heading_line2)) <div>{{ $receipt_details->sub_heading_line2 }}</div> @endif
                @if(!empty($receipt_details->sub_heading_line3)) <div>{{ $receipt_details->sub_heading_line3 }}</div> @endif
                @if(!empty($receipt_details->sub_heading_line4)) <div>{{ $receipt_details->sub_heading_line4 }}</div> @endif
                @if(!empty($receipt_details->sub_heading_line5)) <div>{{ $receipt_details->sub_heading_line5 }}</div> @endif
            </div>
        @else
            <div class="text-center">
                <img style="width: 100%; margin-bottom: 5px;" src="{{$receipt_details->letter_head}}">
            </div>
        @endif

        {{-- Tax Info --}}
        @if(!empty($receipt_details->tax_info1))
            <div class="text-center" style="font-size:13px;">
                <strong>{{ $receipt_details->tax_label1 }}</strong> {{ $receipt_details->tax_info1 }}
            </div>
        @endif
        @if(!empty($receipt_details->tax_info2))
            <div class="text-center" style="font-size:13px;">
                <strong>{{ $receipt_details->tax_label2 }}</strong> {{ $receipt_details->tax_info2 }}
            </div>
        @endif

        {{-- Invoice Heading --}}
        @if(!empty($receipt_details->invoice_heading))
            <div class="text-center sub-heading">{!! $receipt_details->invoice_heading !!}</div>
        @endif

        <div class="sep"></div>

        {{-- ========== INVOICE DETAILS: 2-column ========== --}}
        <div class="inv-header">
            {{-- LEFT: Customer --}}
            <div class="left">
                @if(!$isWalkInCustomer && (!empty($receipt_details->customer_label) || !empty($receipt_details->customer_info)))
                    <div>
                        @if(!empty($receipt_details->customer_label))
                            <strong>{{$receipt_details->customer_label}}</strong>
                        @endif
                        @if(!empty($receipt_details->customer_info))
                            {!! $receipt_details->customer_info !!}
                        @endif
                    </div>
                @endif
                @if(!$isWalkInCustomer && !empty($receipt_details->client_id_label))
                    <div><strong>{{ $receipt_details->client_id_label }}</strong> {{ $receipt_details->client_id }}</div>
                @endif
                @if(!$isWalkInCustomer && !empty($receipt_details->customer_tax_label))
                    <div><strong>{{ $receipt_details->customer_tax_label }}</strong> {{ $receipt_details->customer_tax_number }}</div>
                @endif
                @if(!$isWalkInCustomer && !empty($receipt_details->customer_custom_fields))
                    <div>{!! $receipt_details->customer_custom_fields !!}</div>
                @endif
            </div>
            {{-- RIGHT: Cashier + Invoice No + Date --}}
            <div class="right">
                @if(!empty($receipt_details->added_by))
                    <div>Cashier: {{$receipt_details->added_by}}</div>
                @elseif(!$isWalkInCustomer && !empty($receipt_details->sales_person_label))
                    <div>{{$receipt_details->sales_person_label}} {{$receipt_details->sales_person}}</div>
                @endif
                <div>{!! $receipt_details->invoice_no_prefix !!} {{$receipt_details->invoice_no}}</div>
                <div>{{$receipt_details->invoice_date}}</div>
            </div>
        </div>

        {{-- Extra info rows --}}
        @if(!$isWalkInCustomer && !empty($receipt_details->customer_rp_label))
            <div class="tot-row"><span class="lbl">{{ $receipt_details->customer_rp_label }}</span><span class="val">{{ $receipt_details->customer_total_rp }}</span></div>
        @endif
        @if(!$isWalkInCustomer && !empty($receipt_details->commission_agent_label))
            <div class="tot-row"><span class="lbl">{{$receipt_details->commission_agent_label}}</span><span class="val">{{$receipt_details->commission_agent}}</span></div>
        @endif
        @if(!$isWalkInCustomer && (!empty($receipt_details->service_staff_label) || !empty($receipt_details->service_staff)))
            <div class="tot-row"><span class="lbl">{!! $receipt_details->service_staff_label !!}</span><span class="val">{{$receipt_details->service_staff}}</span></div>
        @endif
        @if(!$isWalkInCustomer && (!empty($receipt_details->table_label) || !empty($receipt_details->table)))
            <div class="tot-row"><span class="lbl">{!! $receipt_details->table_label !!}</span><span class="val">{{$receipt_details->table}}</span></div>
        @endif

        {{-- ========== PRODUCT TABLE ========== --}}
        <div class="sep-thick"></div>

        @php
            $hasDisc = !empty($receipt_details->item_discount_label) || !empty($receipt_details->discounted_unit_price_label);
            $hidePrice = !empty($receipt_details->hide_price);
            $nameColspan = $hidePrice ? 1 : ($hasDisc ? 5 : 4);
        @endphp

        <table class="ptable">
            <thead>
                <tr>
                    <th class="c-sno">#</th>
                    <th style="text-align:left;" colspan="{{$nameColspan}}">{{$receipt_details->table_product_label}}</th>
                </tr>
                @if(empty($receipt_details->hide_price))
                <tr style="font-size:11px; border-bottom: 2px solid #000;">
                    <th class="c-sno"></th>
                    <th class="c-type" style="text-align:left;">Type</th>
                    <th class="c-qty">Qty</th>
                    <th class="c-uprc">Price</th>
                    @if(!empty($receipt_details->item_discount_label))
                        <th class="c-disc">Disc</th>
                    @endif
                    @if(!empty($receipt_details->discounted_unit_price_label))
                        <th class="c-disc">Disc Price</th>
                    @endif
                    <th class="c-tot">Subtotal</th>
                </tr>
                @endif
            </thead>
            <tbody>
                @forelse($receipt_details->lines as $line)
                    <tr>
                        <td class="c-sno">{{$loop->iteration}}</td>
                        <td colspan="{{$nameColspan}}" style="padding-bottom:0;">
                            <span class="item-name">{{$line['name']}} {{$line['product_variation']}} {{$line['variation']}}</span>
                            @if(!empty($line['sub_sku']))       <div class="item-sub">{{$line['sub_sku']}}</div>@endif
                            @if(!empty($line['brand']))         <div class="item-sub">{{$line['brand']}}</div>@endif
                            @if(!empty($line['cat_code']))      <div class="item-sub">{{$line['cat_code']}}</div>@endif
                            @if(!empty($line['product_custom_fields'])) <div class="item-sub">{{$line['product_custom_fields']}}</div>@endif
                            @if(!empty($line['product_description'])) <div class="item-sub">{!!$line['product_description']!!}</div>@endif
                            @if(!empty($line['sell_line_note'])) <div class="item-sub">{!!$line['sell_line_note']!!}</div>@endif
                            @if(!empty($line['lot_number']))
                                <div class="item-sub">{{$line['lot_number_label']}}: {{$line['lot_number']}}
                                    @if(!empty($line['product_expiry'])), {{$line['product_expiry_label']}}: {{$line['product_expiry']}}@endif
                                </div>
                            @endif
                            @if(!empty($line['warranty_name']))
                                <div class="item-sub">{{$line['warranty_name']}}
                                    @if(!empty($line['warranty_exp_date'])) - {{@format_date($line['warranty_exp_date'])}}@endif
                                    @if(!empty($line['warranty_description'])) {{$line['warranty_description']}}@endif
                                </div>
                            @endif
                        </td>
                    </tr>
                    @if(empty($receipt_details->hide_price))
                    <tr class="detail-row">
                        <td class="c-sno"></td>
                        <td class="c-type">{{$line['units']}}</td>
                        <td class="c-qty">{{$line['quantity']}}</td>
                        <td class="c-uprc">{{$line['unit_price_before_discount']}}</td>
                        @if(!empty($receipt_details->item_discount_label))
                            <td class="c-disc">{{$line['line_discount'] ?? '0.00'}}</td>
                        @endif
                        @if(!empty($receipt_details->discounted_unit_price_label))
                            <td class="c-disc">{{$line['unit_price_inc_tax']}}</td>
                        @endif
                        <td class="c-tot">{{$line['line_total']}}</td>
                    </tr>
                    @endif

                    @if(!empty($line['modifiers']))
                        @foreach($line['modifiers'] as $modifier)
                            <tr class="modifier-row">
                                <td></td>
                                <td colspan="{{$nameColspan}}" class="item-sub">
                                    {{$modifier['name']}} {{$modifier['variation']}}
                                    @if(!empty($modifier['sub_sku'])) ({{$modifier['sub_sku']}})@endif
                                    @if(!empty($modifier['sell_line_note'])) ({!!$modifier['sell_line_note']!!})@endif
                                </td>
                            </tr>
                            @if(empty($receipt_details->hide_price))
                            <tr class="modifier-row">
                                <td></td>
                                <td class="c-type">{{$modifier['units']}}</td>
                                <td class="c-qty">{{$modifier['quantity']}}</td>
                                <td class="c-uprc">{{$modifier['unit_price_inc_tax']}}</td>
                                @if(!empty($receipt_details->discounted_unit_price_label))
                                    <td class="c-disc">{{$modifier['unit_price_exc_tax']}}</td>
                                @endif
                                @if(!empty($receipt_details->item_discount_label))
                                    <td class="c-disc">0.00</td>
                                @endif
                                <td class="c-tot">{{$modifier['line_total']}}</td>
                            </tr>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="sep-thick"></div>

        {{-- ========== Pre-total rows (discount/tax/shipping) ========== --}}
        @if(empty($receipt_details->hide_price))
            @if(!$isWalkInCustomer && !empty($receipt_details->total_quantity_label))
                <div class="tot-row"><span class="lbl">{!! $receipt_details->total_quantity_label !!}</span><span class="val">{{$receipt_details->total_quantity}}</span></div>
            @endif
            @if(!$isWalkInCustomer && !empty($receipt_details->total_items_label))
                <div class="tot-row"><span class="lbl">{!! $receipt_details->total_items_label !!}</span><span class="val">{{$receipt_details->total_items}}</span></div>
            @endif
            @if(!empty($receipt_details->shipping_charges))
                <div class="tot-row"><span class="lbl">{!! $receipt_details->shipping_charges_label !!}</span><span class="val">{{$receipt_details->shipping_charges}}</span></div>
            @endif
            @if(!empty($receipt_details->packing_charge))
                <div class="tot-row"><span class="lbl">{!! $receipt_details->packing_charge_label !!}</span><span class="val">{{$receipt_details->packing_charge}}</span></div>
            @endif
            @if(!empty($receipt_details->total_line_discount))
                <div class="tot-row"><span class="lbl">{!! $receipt_details->line_discount_label !!}</span><span class="val">(-) {{$receipt_details->total_line_discount}}</span></div>
            @endif
            @if(!empty($receipt_details->order_discount_unformatted) && $receipt_details->order_discount_unformatted != 0)
                <div class="tot-row"><span class="lbl">{!! $receipt_details->order_discount_label !!}</span><span class="val">(-) {{$receipt_details->order_discount}}</span></div>
            @endif
            @if(!empty($receipt_details->additional_expenses))
                @foreach($receipt_details->additional_expenses as $key => $val)
                    <div class="tot-row"><span class="lbl">{{$key}}</span><span class="val">(+) {{$val}}</span></div>
                @endforeach
            @endif
            @if(!$isWalkInCustomer && !empty($receipt_details->reward_point_label))
                <div class="tot-row"><span class="lbl">{!! $receipt_details->reward_point_label !!}</span><span class="val">(-) {{$receipt_details->reward_point_amount}}</span></div>
            @endif
            @if(!empty($receipt_details->tax))
                <div class="tot-row"><span class="lbl">{!! $receipt_details->tax_label !!}</span><span class="val">(+) {{$receipt_details->tax}}</span></div>
            @endif
            @if($receipt_details->round_off_amount > 0)
                <div class="tot-row"><span class="lbl">{!! $receipt_details->round_off_label !!}</span><span class="val">{{$receipt_details->round_off}}</span></div>
            @endif
        @endif

        {{-- ========== TWO-COLUMN BOTTOM: Installments (left) | Financials (right) ========== --}}
        @if(empty($receipt_details->hide_price))
        <div class="bottom-section" style="margin-top:6px;">

            {{-- LEFT COLUMN: Installment / Due Date info --}}
            <div class="installment-panel">
                @if($hasInstallments)
                    <div class="panel-title">Payment Schedule</div>
                    @if(!empty($receipt_details->receipt_due_date))
                        <div class="inst-row">
                            <span class="lbl">{{$receipt_details->receipt_due_date_label}}</span>
                            <span class="val">{{$receipt_details->receipt_due_date}}</span>
                        </div>
                    @endif
                    @if(!empty($receipt_details->installment_due_dates))
                        <div style="font-weight:700; font-size:12px; margin-top:4px; margin-bottom:2px;">
                            {{$receipt_details->installment_due_dates_label}}
                        </div>
                        @foreach($receipt_details->installment_due_dates as $installment_due)
                            <div class="inst-row">
                                <span class="lbl">{{$installment_due['title']}}</span>
                                <span class="val">{{$installment_due['value']}}</span>
                            </div>
                        @endforeach
                    @endif
                @endif
            </div>

            {{-- RIGHT COLUMN: Financial Summary --}}
            <div class="financial-panel">
                @if($showDueBreakdown)
                    <div class="fin-headline">
                        <span>{!! $receipt_details->receipt_current_bill_label !!}</span>
                        <span class="val">{{$receipt_details->receipt_current_bill}}</span>
                    </div>
                    <div class="fin-row">
                        <span class="lbl">{!! $receipt_details->receipt_previous_due_label !!}</span>
                        <span class="val">{{$receipt_details->receipt_previous_due}}</span>
                    </div>
                    <div class="fin-row">
                        <span class="lbl">{!! $receipt_details->receipt_amount_payable_label !!}</span>
                        <span class="val">{{$receipt_details->receipt_amount_payable}}</span>
                    </div>
                    <div class="fin-row">
                        <span class="lbl">{!! $receipt_details->receipt_amount_paid_label !!}</span>
                        <span class="val">-{{$receipt_details->receipt_amount_paid}}</span>
                    </div>
                    <div class="total-due-final">
                        <span>{!! $receipt_details->receipt_total_due_label !!}</span>
                        <span>{{$receipt_details->receipt_total_due}}</span>
                    </div>
                @elseif($showDueBox)
                    <div class="total-due-box">
                        <span>{!! $receipt_details->total_due_label !!}</span>
                        <span>{{$receipt_details->total_due}}</span>
                    </div>
                @elseif(!empty($receipt_details->total) && !empty($receipt_details->total_label))
                    <div class="grand-total">
                        <span>{!! $receipt_details->total_label !!}</span>
                        <span>{{$receipt_details->total}}</span>
                    </div>
                @endif
            </div>

        </div>
        @endif

        {{-- ========== TAX SUMMARY ========== --}}
        @if(empty($receipt_details->hide_price) && !empty($receipt_details->tax_summary_label))
            @if(!empty($receipt_details->taxes))
                <div class="sep"></div>
                <div class="text-center" style="font-size:13px;font-weight:700;">{{$receipt_details->tax_summary_label}}</div>
                @foreach($receipt_details->taxes as $key => $val)
                    <div class="tot-row"><span class="lbl">{{$key}}</span><span class="val">{{$val}}</span></div>
                @endforeach
            @endif
        @endif

        {{-- ========== NOTES ========== --}}
        @if(!empty($receipt_details->additional_notes))
            <div class="notes-section">
                {!! nl2br($receipt_details->additional_notes) !!}
            </div>
        @endif

        {{-- Barcode --}}
        @if($receipt_details->show_barcode)
            <div class="text-center" style="margin: 10px 0;">
                <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 3, 50, array(39, 48, 54), true)}}">
            </div>
        @endif

        @if($receipt_details->show_qr_code && !empty($receipt_details->qr_code_text))
            <div class="text-center" style="margin: 10px 0;">
                <img src="data:image/png;base64,{{DNS2D::getBarcodePNG($receipt_details->qr_code_text, 'QRCODE')}}">
            </div>
        @endif

        {{-- Footer --}}
        @if(!empty($receipt_details->footer_text))
            <div class="footer-text">
                {!! $receipt_details->footer_text !!}
            </div>
        @endif

        <div class="thank-you">
            ★ Thank you for your business! ★
        </div>

        @php
            $cs = !empty($receipt_details->common_settings) ? $receipt_details->common_settings : [];
            $show_website = !empty($cs['show_digipartner_website']);
            $show_phone   = !empty($cs['show_digipartner_phone']);
        @endphp
        <div style="text-align:center; font-size:11px; color:#888 !important; margin-top:6px; padding-top:4px; border-top:1px dashed #ccc;">
            Powered by : <strong>DigiPartner</strong>
            @if($show_website || $show_phone)
                <br>
                @if($show_website && $show_phone)
                    digipartner.lk / 074 410 3531
                @elseif($show_website)
                    digipartner.lk
                @elseif($show_phone)
                    074 410 3531
                @endif
            @endif
        </div>

    </div>
</body>
</html>
