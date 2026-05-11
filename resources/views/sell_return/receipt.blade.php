<!-- Modern Slim Receipt for Returns -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Receipt-{{$receipt_details->invoice_no}}</title>
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
        font-size: 13px;
        color: #000 !important;
        line-height: 1.2;
        background: #fff;
        margin: 5px;
        padding: 5px;
    }

    /* ===== Receipt Container ===== */
    .receipt {
        width: calc(100% - 12px);
        max-width: calc(100% - 12px);
        margin: 0 auto;
        padding: 2px 0;
    }

    /* ===== Text Alignment ===== */
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-left { text-align: left; }

    /* ===== Logo ===== */
    .receipt-logo {
        max-height: 80px;
        width: auto;
        margin: 0 auto 5px auto;
        display: block;
    }

    /* ===== Header ===== */
    .business-name {
        font-size: 20px;
        font-weight: 900;
        text-transform: uppercase;
        margin-bottom: 2px;
        letter-spacing: 1px;
    }
    .customer-name-top {
        font-size: 18px;
        font-weight: 700;
        margin: 5px 0;
        color: #000;
    }
    .business-info {
        font-size: 12px;
        line-height: 1.3;
        margin-bottom: 8px;
    }
    .sub-heading {
        font-size: 16px;
        font-weight: 800;
        text-transform: uppercase;
        margin: 8px 0;
        border-top: 2px solid #000;
        border-bottom: 2px solid #000;
        padding: 4px 0;
    }

    /* ===== Separators ===== */
    .sep-thick {
        border-bottom: 2px solid #000;
        margin: 8px 0;
    }
    .sep-thin {
        border-bottom: 1px solid #000;
        margin: 5px 0;
    }

    /* ===== Info Rows ===== */
    .info-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        line-height: 1.5;
        margin-bottom: 2px;
    }
    .info-row strong {
        font-weight: 800;
    }

    /* ===== Product Table ===== */
    .ptable {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin: 10px 0;
    }
    .ptable th {
        font-weight: 800;
        border-bottom: 2px solid #000;
        padding: 5px 0;
        text-align: left;
        text-transform: uppercase;
        font-size: 11px;
    }
    .ptable td {
        padding: 6px 0;
        vertical-align: top;
        border-bottom: 1px dashed #ccc;
    }
    .item-sub {
        font-size: 11px;
        color: #333;
        font-style: italic;
    }

    /* ===== Totals ===== */
    .tot-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        padding: 3px 0;
    }
    .grand-total {
        display: flex;
        justify-content: space-between;
        font-size: 19px;
        font-weight: 900;
        border-top: 2px solid #000;
        border-bottom: 3px double #000;
        margin: 10px 0;
        padding: 8px 0;
    }

    /* ===== Footer ===== */
    .footer-text {
        text-align: center;
        font-size: 12px;
        margin-top: 15px;
        border-top: 1px solid #000;
        padding-top: 10px;
    }
    </style>
</head>
<body>
    <div class="receipt">
        {{-- Header: Logo & Business Info --}}
        @if(!empty($receipt_details->logo))
            <img class="receipt-logo" src="{{$receipt_details->logo}}" alt="Logo">
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
        </div>

        {{-- Customer Name Prominent (as in user image) --}}
        @if(!empty($receipt_details->customer_name))
            <div class="text-center customer-name-top">
                {{$receipt_details->customer_name}}
            </div>
        @endif

        <div class="text-center sub-heading">
            {{$receipt_details->invoice_heading ?? 'SELL RETURN'}}
        </div>

        {{-- Invoice Details --}}
        <div class="info-row">
            <span><strong>Return No:</strong></span>
            <span>{{$receipt_details->invoice_no}}</span>
        </div>
        
        @if(!empty($receipt_details->parent_invoice_no))
            <div class="info-row">
                <span><strong>Original Invoice:</strong></span>
                <span>{{$receipt_details->parent_invoice_no}}</span>
            </div>
        @endif

        <div class="info-row">
            <span><strong>Date:</strong></span>
            <span>{{$receipt_details->invoice_date}}</span>
        </div>

        {{-- Product Table --}}
        <table class="ptable">
            <thead>
                <tr>
                    <th style="width: 45%;">PRODUCT</th>
                    <th class="text-right" style="width: 15%;">QTY</th>
                    <th class="text-right" style="width: 20%;">PRICE</th>
                    <th class="text-right" style="width: 20%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipt_details->lines as $line)
                    <tr>
                        <td>
                            <strong>{{$line['name']}}</strong>
                            @if(!empty($line['sub_sku'])) <div class="item-sub">SKU: {{$line['sub_sku']}}</div> @endif
                        </td>
                        <td class="text-right">{{$line['quantity']}}</td>
                        <td class="text-right">{{$line['unit_price_exc_tax']}}</td>
                        <td class="text-right"><strong>{{$line['line_total']}}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="sep-thick"></div>

        {{-- Totals --}}
        <div class="tot-row">
            <span>Subtotal:</span>
            <span>{{$receipt_details->subtotal}}</span>
        </div>

        @if(!empty($receipt_details->tax))
            <div class="tot-row">
                <span>Tax:</span>
                <span>(+) {{$receipt_details->tax}}</span>
            </div>
        @endif

        <div class="grand-total">
            <span>TOTAL RETURN:</span>
            <span>{{$receipt_details->total}}</span>
        </div>

        @if(!empty($receipt_details->total_paid))
            <div class="info-row">
                <span>Total Paid:</span>
                <span>{{$receipt_details->total_paid}}</span>
            </div>
        @endif

        @if(!empty($receipt_details->total_due))
            <div class="info-row" style="color: red; font-weight: bold;">
                <span>Total Due:</span>
                <span>{{$receipt_details->total_due}}</span>
            </div>
        @endif

        {{-- Footer --}}
        @if(!empty($receipt_details->footer_text))
            <div class="footer-text">
                {!! $receipt_details->footer_text !!}
            </div>
        @endif

        <div class="text-center" style="margin-top: 10px;">
            <p style="font-size: 11px;">Thank You!</p>
        </div>
    </div>
</body>
</html>