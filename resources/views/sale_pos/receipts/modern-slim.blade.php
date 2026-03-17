<!-- Modern Slim Receipt - Designed for 80mm thermal printers -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt-{{$receipt_details->invoice_no}}</title>
    <style type="text/css">
    /* ===== Base Reset ===== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    @page {
        margin: 5px;
    }
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        color: #000 !important;
        line-height: 1.2;
        background: #fff;
        margin: 5px;
        padding: 5px;
    }
    a, a:visited, a:hover, a:active {
        color: #000 !important;
        text-decoration: none !important;
    }

    /* ===== Receipt Container ===== */
    .receipt {
        width: calc(100% - 12px);
        max-width: calc(100% - 12px);
        margin: 0 auto;
        padding: 2px 0;
        color: #000 !important;
    }
    .receipt * {
        color: #000 !important;
    }

    /* ===== Text Alignment ===== */
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-left { text-align: left; }
    .v-top { vertical-align: top; }

    /* ===== Logo ===== */
    .receipt-logo {
        max-height: 80px;
        width: auto;
        margin: 0 auto 1px auto;
        display: block;
    }

    /* ===== Header ===== */
    .header-tagline {
        font-size: 10px;
        font-style: italic;
        margin-bottom: 1px;
    }
    .business-name {
        font-size: 15px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 1px;
        letter-spacing: 0.3px;
    }
    .business-info {
        font-size: 10px;
        line-height: 1.3;
        margin-bottom: 1px;
    }
    .sub-heading {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        margin: 2px 0;
    }

    /* ===== Separators ===== */
    .sep {
        border: none;
        border-bottom: 1px solid #000;
        margin: 2px 0;
    }
    .sep-thick {
        border: none;
        border-bottom: 1px solid #000;
        margin: 2px 0;
    }
    .sep-dashed {
        border: none;
        border-bottom: 1px dashed #999;
        margin: 2px 0;
    }

    /* ===== Info Rows (key-value on same line) ===== */
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.3;
        gap: 2px;
    }
    .info-row .r {
        text-align: right;
        flex-shrink: 0;
    }
    .info-line {
        font-size: 10px;
        line-height: 1.3;
        word-break: break-word;
    }

    /* ===== Product Table ===== */
    .ptable {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        table-layout: fixed;
    }
    .ptable thead {
        border-bottom: 1px solid #000;
    }
    .ptable th {
        font-weight: 700;
        padding: 1px 1px;
        font-size: 9px;
        text-align: left;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .ptable th.r { text-align: right; }
    .ptable td {
        padding: 2px 1px;
        vertical-align: top;
        font-size: 10px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    .ptable tbody tr {
        border-bottom: 1px dashed #ddd;
    }
    .ptable tbody tr:last-child {
        border-bottom: none;
    }

    .c-sno  { width: 5%;  text-align: left; vertical-align: top; }
    .c-type { width: 18%; text-align: left; }
    .c-qty  { width: 10%; text-align: right; }
    .c-uprc { width: 28%; text-align: right; }
    .c-disc { width: 15%; text-align: right; }
    .c-tot  { width: 24%; text-align: right; }

    .item-name {
        font-weight: normal;
        font-size: 10px;
        word-wrap: break-word;
    }
    .item-sub {
        font-size: 9px;
        color: #555 !important;
        margin-top: 0;
    }
    .detail-row td {
        font-size: 9px;
        padding: 1px 1px;
        border-bottom: 1px dashed #ccc;
    }
    .modifier-row td {
        font-size: 9px;
        color: #555 !important;
        padding: 1px 1px;
    }

    /* ===== Totals ===== */
    .tot-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        padding: 0;
        font-size: 11px;
        line-height: 1.4;
    }
    .tot-row .lbl {
        font-weight: 600;
    }
    .tot-row .val {
        text-align: right;
        white-space: nowrap;
        font-weight: 600;
    }

    /* ===== Grand Total ===== */
    .grand-total {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        padding: 2px 0;
        font-size: 15px;
        font-weight: 900;
        border-top: 2px solid #000;
        border-bottom: 2px solid #000;
        margin: 2px 0;
    }

    .total-due-box {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        padding: 2px 0;
        font-size: 14px;
        font-weight: 800;
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
        margin: 2px 0;
    }

    .receipt-balance {
        margin: 2px 0;
    }
    .receipt-balance .headline {
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        border-top: 2px solid #000;
        border-bottom: 1px solid #000;
        padding: 2px 0 1px 0;
        margin-bottom: 2px;
    }
    .receipt-balance .headline .val {
        font-size: 14px;
    }
    .receipt-balance .balance-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 11px;
        line-height: 1.4;
        padding: 0;
    }
    .receipt-balance .balance-row .lbl {
        font-weight: 600;
    }
    .receipt-balance .balance-row .val {
        font-weight: 600;
        text-align: right;
        white-space: nowrap;
    }
    .receipt-balance .final-due {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 15px;
        font-weight: 900;
        border-top: 2px solid #000;
        border-bottom: 1px solid #000;
        padding: 2px 0;
        margin-top: 3px;
    }

    .total-words {
        font-size: 9px;
        color: #555 !important;
        margin: 0;
        font-style: italic;
    }

    .due-note-block {
        margin: 2px 0;
        padding-top: 2px;
        border-top: 1px solid #000;
    }
    .due-note-title {
        font-size: 10px;
        font-weight: 700;
        margin-bottom: 1px;
    }
    .due-note-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 10px;
        line-height: 1.3;
        gap: 6px;
    }
    .due-note-row .lbl {
        font-weight: 600;
    }
    .due-note-row .val {
        text-align: right;
        white-space: nowrap;
        font-weight: 600;
    }

    /* ===== Notes ===== */
    .notes-section {
        font-size: 10px;
        line-height: 1.3;
        margin: 2px 0;
        padding: 1px 0;
    }

    /* ===== Footer ===== */
    .footer-text {
        text-align: center;
        font-size: 10px;
        margin: 2px 0;
        line-height: 1.3;
    }
    .thank-you {
        text-align: center;
        font-size: 10px;
        color: #666 !important;
        margin-top: 3px;
        padding-top: 2px;
    }

    /* ===== Print Styles ===== */
    @media print {
        body {
            font-size: 17px;
            font-family: 'Times New Roman', Times, serif;
        }
        .receipt {
            width: calc(100% - 12px);
            max-width: calc(100% - 12px);
            margin: 0 auto;
            padding: 0;
        }
        .receipt-logo {
            max-height: 100px;
        }
        .hidden-print,
        .hidden-print * {
            display: none !important;
        }
    }
    </style>
</head>
<body>
    <div class="receipt">

        @php
            $isWalkInCustomer = !empty($receipt_details->is_walk_in_customer);
            $showDueBreakdown = !empty($receipt_details->receipt_show_due_breakdown) && !$isWalkInCustomer;
            $showDueBox = !$isWalkInCustomer && !empty($receipt_details->total_due) && !empty($receipt_details->total_due_label);
            $showSubtotalRow = $showDueBreakdown
                || !empty($receipt_details->shipping_charges)
                || !empty($receipt_details->packing_charge)
                || !empty($receipt_details->total_line_discount)
                || (!empty($receipt_details->order_discount_unformatted) && $receipt_details->order_discount_unformatted != 0)
                || !empty($receipt_details->additional_expenses)
                || (!$isWalkInCustomer && !empty($receipt_details->reward_point_label))
                || !empty($receipt_details->tax)
                || (!empty($receipt_details->round_off_amount) && $receipt_details->round_off_amount > 0)
                || trim((string) ($receipt_details->subtotal ?? '')) !== trim((string) ($receipt_details->total ?? ''));
        @endphp

        {{-- ========== HEADER: Logo & Business Info ========== --}}
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

            <div class="sep"></div>

            {{-- Address & Contact --}}
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
                @if(!empty($receipt_details->sub_heading_line1))
                    <div>{{ $receipt_details->sub_heading_line1 }}</div>
                @endif
                @if(!empty($receipt_details->sub_heading_line2))
                    <div>{{ $receipt_details->sub_heading_line2 }}</div>
                @endif
                @if(!empty($receipt_details->sub_heading_line3))
                    <div>{{ $receipt_details->sub_heading_line3 }}</div>
                @endif
                @if(!empty($receipt_details->sub_heading_line4))
                    <div>{{ $receipt_details->sub_heading_line4 }}</div>
                @endif
                @if(!empty($receipt_details->sub_heading_line5))
                    <div>{{ $receipt_details->sub_heading_line5 }}</div>
                @endif
            </div>
        @else
            <div class="text-center">
                <img style="width: 100%; margin-bottom: 3px;" src="{{$receipt_details->letter_head}}">
            </div>
        @endif

        {{-- Tax Info --}}
        @if(!empty($receipt_details->tax_info1))
            <div class="text-center" style="font-size:12px;">
                <strong>{{ $receipt_details->tax_label1 }}</strong> {{ $receipt_details->tax_info1 }}
            </div>
        @endif
        @if(!empty($receipt_details->tax_info2))
            <div class="text-center" style="font-size:12px;">
                <strong>{{ $receipt_details->tax_label2 }}</strong> {{ $receipt_details->tax_info2 }}
            </div>
        @endif

        {{-- Invoice Heading --}}
        @if(!empty($receipt_details->invoice_heading))
            <div class="text-center sub-heading">{!! $receipt_details->invoice_heading !!}</div>
        @endif

        <div class="sep"></div>

        {{-- ========== INVOICE DETAILS ========== --}}
        <div class="info-row">
            <span>{!! $receipt_details->invoice_no_prefix !!} {{$receipt_details->invoice_no}}</span>
            @if(!empty($receipt_details->added_by))
                <span class="r">Cashier: {{$receipt_details->added_by}}</span>
            @elseif(!$isWalkInCustomer && !empty($receipt_details->sales_person_label))
                <span class="r">{{$receipt_details->sales_person_label}} {{$receipt_details->sales_person}}</span>
            @endif
        </div>

        <div class="info-row">
            <span>{{$receipt_details->invoice_date}}</span>
        </div>

        @if(!$isWalkInCustomer && !empty($receipt_details->due_date_label))
            <div class="info-line">
                <strong>{{$receipt_details->due_date_label}}</strong> {{$receipt_details->due_date ?? ''}}
            </div>
        @endif

        {{-- Customer --}}
        @if(!$isWalkInCustomer && (!empty($receipt_details->customer_label) || !empty($receipt_details->customer_info)))
            <div class="info-line">
                @if(!empty($receipt_details->customer_label))
                    <strong>{{$receipt_details->customer_label}}</strong>
                @endif
                @if(!empty($receipt_details->customer_info))
                    {!! preg_replace('/<br\s*\/?\>?\s*Mobile\s*:.*$/i', '', $receipt_details->customer_info) !!}
                @endif
            </div>
        @endif

        @if(!$isWalkInCustomer && !empty($receipt_details->client_id_label))
            <div class="info-line">
                <strong>{{ $receipt_details->client_id_label }}</strong> {{ $receipt_details->client_id }}
            </div>
        @endif

        @if(!$isWalkInCustomer && !empty($receipt_details->customer_tax_label))
            <div class="info-line">
                <strong>{{ $receipt_details->customer_tax_label }}</strong> {{ $receipt_details->customer_tax_number }}
            </div>
        @endif

        @if(!$isWalkInCustomer && !empty($receipt_details->customer_custom_fields))
            <div class="info-line">{!! $receipt_details->customer_custom_fields !!}</div>
        @endif

        @if(!$isWalkInCustomer && !empty($receipt_details->customer_rp_label))
            <div class="info-row">
                <span><strong>{{ $receipt_details->customer_rp_label }}</strong></span>
                <span class="r">{{ $receipt_details->customer_total_rp }}</span>
            </div>
        @endif

        {{-- Custom sell fields --}}
        @if(!$isWalkInCustomer && !empty($receipt_details->sell_custom_field_1_value))
            <div class="info-row">
                <span>{!! $receipt_details->sell_custom_field_1_label !!}</span>
                <span class="r">{{$receipt_details->sell_custom_field_1_value}}</span>
            </div>
        @endif
        @if(!$isWalkInCustomer && !empty($receipt_details->sell_custom_field_2_value))
            <div class="info-row">
                <span>{!! $receipt_details->sell_custom_field_2_label !!}</span>
                <span class="r">{{$receipt_details->sell_custom_field_2_value}}</span>
            </div>
        @endif
        @if(!$isWalkInCustomer && !empty($receipt_details->sell_custom_field_3_value))
            <div class="info-row">
                <span>{!! $receipt_details->sell_custom_field_3_label !!}</span>
                <span class="r">{{$receipt_details->sell_custom_field_3_value}}</span>
            </div>
        @endif
        @if(!$isWalkInCustomer && !empty($receipt_details->sell_custom_field_4_value))
            <div class="info-row">
                <span>{!! $receipt_details->sell_custom_field_4_label !!}</span>
                <span class="r">{{$receipt_details->sell_custom_field_4_value}}</span>
            </div>
        @endif

        {{-- Shipping custom fields --}}
        @if(!$isWalkInCustomer && !empty($receipt_details->shipping_custom_field_1_label))
            <div class="info-row">
                <span>{!!$receipt_details->shipping_custom_field_1_label!!}</span>
                <span class="r">{!!$receipt_details->shipping_custom_field_1_value ?? ''!!}</span>
            </div>
        @endif
        @if(!$isWalkInCustomer && !empty($receipt_details->shipping_custom_field_2_label))
            <div class="info-row">
                <span>{!!$receipt_details->shipping_custom_field_2_label!!}</span>
                <span class="r">{!!$receipt_details->shipping_custom_field_2_value ?? ''!!}</span>
            </div>
        @endif
        @if(!$isWalkInCustomer && !empty($receipt_details->shipping_custom_field_3_label))
            <div class="info-row">
                <span>{!!$receipt_details->shipping_custom_field_3_label!!}</span>
                <span class="r">{!!$receipt_details->shipping_custom_field_3_value ?? ''!!}</span>
            </div>
        @endif
        @if(!$isWalkInCustomer && !empty($receipt_details->shipping_custom_field_4_label))
            <div class="info-row">
                <span>{!!$receipt_details->shipping_custom_field_4_label!!}</span>
                <span class="r">{!!$receipt_details->shipping_custom_field_4_value ?? ''!!}</span>
            </div>
        @endif
        @if(!$isWalkInCustomer && !empty($receipt_details->shipping_custom_field_5_label))
            <div class="info-row">
                <span>{!!$receipt_details->shipping_custom_field_5_label!!}</span>
                <span class="r">{!!$receipt_details->shipping_custom_field_5_value ?? ''!!}</span>
            </div>
        @endif

        {{-- Sale order info --}}
        @if(!$isWalkInCustomer && !empty($receipt_details->sale_orders_invoice_no))
            <div class="info-row">
                <span><strong>@lang('restaurant.order_no')</strong></span>
                <span class="r">{!!$receipt_details->sale_orders_invoice_no!!}</span>
            </div>
        @endif
        @if(!$isWalkInCustomer && !empty($receipt_details->sale_orders_invoice_date))
            <div class="info-row">
                <span><strong>@lang('lang_v1.order_dates')</strong></span>
                <span class="r">{!!$receipt_details->sale_orders_invoice_date!!}</span>
            </div>
        @endif

        {{-- Commission agent / Repair fields --}}
        @if(!$isWalkInCustomer && !empty($receipt_details->commission_agent_label))
            <div class="info-row">
                <span><strong>{{$receipt_details->commission_agent_label}}</strong></span>
                <span class="r">{{$receipt_details->commission_agent}}</span>
            </div>
        @endif
        @if(!$isWalkInCustomer && (!empty($receipt_details->brand_label) || !empty($receipt_details->repair_brand)))
            <div class="info-row">
                <span><strong>{{$receipt_details->brand_label}}</strong></span>
                <span class="r">{{$receipt_details->repair_brand}}</span>
            </div>
        @endif
        @if(!$isWalkInCustomer && (!empty($receipt_details->device_label) || !empty($receipt_details->repair_device)))
            <div class="info-row">
                <span><strong>{{$receipt_details->device_label}}</strong></span>
                <span class="r">{{$receipt_details->repair_device}}</span>
            </div>
        @endif
        @if(!$isWalkInCustomer && (!empty($receipt_details->service_staff_label) || !empty($receipt_details->service_staff)))
            <div class="info-row">
                <span><strong>{!! $receipt_details->service_staff_label !!}</strong></span>
                <span class="r">{{$receipt_details->service_staff}}</span>
            </div>
        @endif
        @if(!$isWalkInCustomer && (!empty($receipt_details->table_label) || !empty($receipt_details->table)))
            <div class="info-row">
                <span><strong>{!! $receipt_details->table_label !!}</strong></span>
                <span class="r">{{$receipt_details->table}}</span>
            </div>
        @endif

        {{-- ========== PRODUCT TABLE ========== --}}
        <div class="sep-thick"></div>

        @php
            $hasDisc = !empty($receipt_details->item_discount_label) || !empty($receipt_details->discounted_unit_price_label);
            $hidePrice = !empty($receipt_details->hide_price);
            // 5-col normal: #, type, qty, price, total
            // 6-col with disc: #, type, qty, price, disc, total
            // 2-col hide price: #, type
            $nameColspan = $hidePrice ? 1 : ($hasDisc ? 5 : 4);
        @endphp

        <table class="ptable">
            <thead>
                {{-- Header row 1: # | Item (full span) --}}
                <tr>
                    <th class="c-sno">#</th>
                    <th style="text-align:left;" colspan="{{$nameColspan}}">{{$receipt_details->table_product_label}}</th>
                </tr>
                {{-- Header row 2: blank | Type | Qty | Price | [Disc] | Total --}}
                @if(empty($receipt_details->hide_price))
                <tr style="font-size:9px; border-bottom: 1px solid #000;">
                    <th class="c-sno"></th>
                    <th class="c-type" style="text-align:left;">Type</th>
                    <th class="c-qty r">Qty</th>
                    <th class="c-uprc r">Price</th>
                    @if(!empty($receipt_details->item_discount_label))
                        <th class="c-disc r">Disc</th>
                    @endif
                    @if(!empty($receipt_details->discounted_unit_price_label))
                        <th class="c-disc r">Disc</th>
                    @endif
                    <th class="c-tot r">Subtotal</th>
                </tr>
                @endif
            </thead>
            <tbody>
                @forelse($receipt_details->lines as $line)
                    {{-- Row 1: # + Item name spanning all columns --}}
                    <tr>
                        <td class="c-sno">{{$loop->iteration}}</td>
                        <td colspan="{{$nameColspan}}" style="padding-bottom:0;">
                            {{$line['name']}} {{$line['product_variation']}} {{$line['variation']}}
                            @if(!empty($line['sub_sku']))
                                <div class="item-sub">{{$line['sub_sku']}}</div>
                            @endif
                            @if(!empty($line['brand']))
                                <div class="item-sub">{{$line['brand']}}</div>
                            @endif
                            @if(!empty($line['cat_code']))
                                <div class="item-sub">{{$line['cat_code']}}</div>
                            @endif
                            @if(!empty($line['product_custom_fields']))
                                <div class="item-sub">{{$line['product_custom_fields']}}</div>
                            @endif
                            @if(!empty($line['product_description']))
                                <div class="item-sub">{!!$line['product_description']!!}</div>
                            @endif
                            @if(!empty($line['sell_line_note']))
                                <div class="item-sub">{!!$line['sell_line_note']!!}</div>
                            @endif
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
                    {{-- Row 2: blank | Type | Qty | Price | [Disc] | Total --}}
                    @if(empty($receipt_details->hide_price))
                    <tr class="detail-row">
                        <td class="c-sno"></td>
                        <td class="c-type">{{$line['units']}}</td>
                        <td class="c-qty" style="text-align:right;">{{$line['quantity']}}</td>
                        <td class="c-uprc" style="text-align:right;">{{$line['unit_price_before_discount']}}</td>
                        @if(!empty($receipt_details->item_discount_label))
                            <td class="c-disc" style="text-align:right;">{{$line['line_discount'] ?? '0.00'}}</td>
                        @endif
                        @if(!empty($receipt_details->discounted_unit_price_label))
                            <td class="c-disc" style="text-align:right;">{{$line['unit_price_inc_tax']}}</td>
                        @endif
                        <td class="c-tot" style="text-align:right; font-weight:700;">{{$line['line_total']}}</td>
                    </tr>
                    @endif

                    {{-- Modifiers --}}
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
                                <td style="text-align:right;">{{$modifier['quantity']}}</td>
                                <td style="text-align:right;">{{$modifier['unit_price_inc_tax']}}</td>
                                @if(!empty($receipt_details->discounted_unit_price_label))
                                    <td style="text-align:right;">{{$modifier['unit_price_exc_tax']}}</td>
                                @endif
                                @if(!empty($receipt_details->item_discount_label))
                                    <td style="text-align:right;">0.00</td>
                                @endif
                                <td style="text-align:right;">{{$modifier['line_total']}}</td>
                            </tr>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="sep-thick"></div>

        {{-- ========== TOTALS SECTION ========== --}}
        @if(!$isWalkInCustomer && !empty($receipt_details->total_quantity_label))
            <div class="tot-row">
                <span class="lbl">{!! $receipt_details->total_quantity_label !!}</span>
                <span class="val">{{$receipt_details->total_quantity}}</span>
            </div>
        @endif

        @if(!$isWalkInCustomer && !empty($receipt_details->total_items_label))
            <div class="tot-row">
                <span class="lbl">{!! $receipt_details->total_items_label !!}</span>
                <span class="val">{{$receipt_details->total_items}}</span>
            </div>
        @endif

        @if(empty($receipt_details->hide_price))
            {{-- Subtotal --}}
            @if($showSubtotalRow)
                <div class="tot-row">
                    <span class="lbl">{!! $receipt_details->subtotal_label !!}</span>
                    <span class="val">{{$receipt_details->subtotal}}</span>
                </div>
            @endif

            {{-- Shipping --}}
            @if(!empty($receipt_details->shipping_charges))
                <div class="tot-row">
                    <span class="lbl">{!! $receipt_details->shipping_charges_label !!}</span>
                    <span class="val">{{$receipt_details->shipping_charges}}</span>
                </div>
            @endif

            {{-- Packing --}}
            @if(!empty($receipt_details->packing_charge))
                <div class="tot-row">
                    <span class="lbl">{!! $receipt_details->packing_charge_label !!}</span>
                    <span class="val">{{$receipt_details->packing_charge}}</span>
                </div>
            @endif

            {{-- Line-level discounts --}}
            @if(!empty($receipt_details->total_line_discount))
                <div class="tot-row">
                    <span class="lbl">{!! $receipt_details->line_discount_label !!}</span>
                    <span class="val">(-) {{$receipt_details->total_line_discount}}</span>
                </div>
            @endif

            {{-- Order-level discount (from POS Discount field) --}}
            @if(!empty($receipt_details->order_discount_unformatted) && $receipt_details->order_discount_unformatted != 0)
                <div class="tot-row">
                    <span class="lbl">{!! $receipt_details->order_discount_label !!}</span>
                    <span class="val">(-) {{$receipt_details->order_discount}}</span>
                </div>
            @endif

            {{-- Additional expenses --}}
            @if(!empty($receipt_details->additional_expenses))
                @foreach($receipt_details->additional_expenses as $key => $val)
                    <div class="tot-row">
                        <span class="lbl">{{$key}}</span>
                        <span class="val">(+) {{$val}}</span>
                    </div>
                @endforeach
            @endif

            {{-- Reward points --}}
            @if(!$isWalkInCustomer && !empty($receipt_details->reward_point_label))
                <div class="tot-row">
                    <span class="lbl">{!! $receipt_details->reward_point_label !!}</span>
                    <span class="val">(-) {{$receipt_details->reward_point_amount}}</span>
                </div>
            @endif

            {{-- Tax --}}
            @if(!empty($receipt_details->tax))
                <div class="tot-row">
                    <span class="lbl">{!! $receipt_details->tax_label !!}</span>
                    <span class="val">(+) {{$receipt_details->tax}}</span>
                </div>
            @endif

            {{-- Round off --}}
            @if($receipt_details->round_off_amount > 0)
                <div class="tot-row">
                    <span class="lbl">{!! $receipt_details->round_off_label !!}</span>
                    <span class="val">{{$receipt_details->round_off}}</span>
                </div>
            @endif

            @if($showDueBreakdown)
                <div class="receipt-balance">
                    <div class="headline">
                        <span>{!! $receipt_details->receipt_current_bill_label !!}</span>
                        <span class="val">{{$receipt_details->receipt_current_bill}}</span>
                    </div>

                    <div class="balance-row">
                        <span class="lbl">{!! $receipt_details->receipt_previous_due_label !!}</span>
                        <span class="val">{{$receipt_details->receipt_previous_due}}</span>
                    </div>

                    <div class="balance-row">
                        <span class="lbl">{!! $receipt_details->receipt_amount_payable_label !!}</span>
                        <span class="val">{{$receipt_details->receipt_amount_payable}}</span>
                    </div>

                    <div class="balance-row">
                        <span class="lbl">{!! $receipt_details->receipt_amount_paid_label !!}</span>
                        <span class="val">-{{$receipt_details->receipt_amount_paid}}</span>
                    </div>

                    <div class="final-due">
                        <span>{!! $receipt_details->receipt_total_due_label !!}</span>
                        <span>{{$receipt_details->receipt_total_due}}</span>
                    </div>
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

            @if(!empty($receipt_details->receipt_due_date) || !empty($receipt_details->installment_due_dates))
                <div class="due-note-block">
                    @if(!empty($receipt_details->receipt_due_date))
                        <div class="due-note-row">
                            <span class="lbl">{{$receipt_details->receipt_due_date_label}}</span>
                            <span class="val">{{$receipt_details->receipt_due_date}}</span>
                        </div>
                    @endif

                    @if(!empty($receipt_details->installment_due_dates))
                        <div class="due-note-title">{{$receipt_details->installment_due_dates_label}}</div>
                        @foreach($receipt_details->installment_due_dates as $installment_due)
                            <div class="due-note-row">
                                <span class="lbl">{{$installment_due['title']}}</span>
                                <span class="val">{{$installment_due['value']}}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endif
        @endif

        {{-- ========== TAX SUMMARY ========== --}}
        @if(empty($receipt_details->hide_price) && !empty($receipt_details->tax_summary_label))
            @if(!empty($receipt_details->taxes))
                <div class="sep"></div>
                <div class="text-center" style="font-size:12px;font-weight:700;">{{$receipt_details->tax_summary_label}}</div>
                @foreach($receipt_details->taxes as $key => $val)
                    <div class="tot-row">
                        <span class="lbl">{{$key}}</span>
                        <span class="val">{{$val}}</span>
                    </div>
                @endforeach
            @endif
        @endif

        <div class="sep"></div>

        {{-- ========== NOTES ========== --}}
        @if(!empty($receipt_details->additional_notes))
            <div class="notes-section">
                {!! nl2br($receipt_details->additional_notes) !!}
            </div>
        @endif

        {{-- Barcode --}}
        @if($receipt_details->show_barcode)
            <div class="text-center" style="margin: 6px 0;">
                <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 2, 30, array(39, 48, 54), true)}}">
            </div>
        @endif

        @if($receipt_details->show_qr_code && !empty($receipt_details->qr_code_text))
            <div class="text-center" style="margin: 6px 0;">
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
            $show_phone = !empty($cs['show_digipartner_phone']);
        @endphp
        <div style="text-align:center; font-size:10px; color:#888 !important; margin-top:5px; padding-top:3px; border-top:1px dashed #ccc;">
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
