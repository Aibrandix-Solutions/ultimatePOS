<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header no-print">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">
        @lang( 'lang_v1.view_payment' )
        @if(!empty($single_payment_line->payment_ref_no))
          ( @lang('purchase.ref_no'): {{ $single_payment_line->payment_ref_no }} )
        @endif
      </h4>
    </div>
    <div class="modal-body">
      <div class="payment-receipt-view">

        {{-- Business Header --}}
        @if(!empty($transaction))
          <div class="text-center receipt-header">
            @if(!empty($transaction->business->name))
              <div class="receipt-business-name">{{ $transaction->business->name }}</div>
            @endif
            @if(!empty($transaction->location))
              <div class="receipt-business-info">
                @if(!empty($transaction->location->name))
                  <div>{{ $transaction->location->name }}</div>
                @endif
                @if(!empty($transaction->location->landmark))
                  <div>{{ $transaction->location->landmark }}</div>
                @endif
                @if(!empty($transaction->location->city) || !empty($transaction->location->state) || !empty($transaction->location->country))
                  <div>{{ implode(', ', array_filter([$transaction->location->city, $transaction->location->state, $transaction->location->country])) }}</div>
                @endif
                @if(!empty($transaction->location->mobile))
                  <div>{{ $transaction->location->mobile }}</div>
                @endif
              </div>
            @endif
          </div>
        @endif

        {{-- Receipt Title --}}
        <div class="receipt-title text-center">PAYMENT RECEIPT</div>

        {{-- Payment Info --}}
        <div class="receipt-info-section">
          <div class="receipt-info-row">
            <span class="receipt-label">@lang('messages.date'):</span>
            <span class="receipt-value">{{ @format_datetime($single_payment_line->paid_on) }}</span>
          </div>
          <div class="receipt-info-row">
            <span class="receipt-label">@lang('purchase.ref_no'):</span>
            <span class="receipt-value">{{ $single_payment_line->payment_ref_no ?? '--' }}</span>
          </div>
        </div>

        {{-- Contact Info --}}
        @if(!empty($transaction))
          <div class="receipt-info-section">
            @if(in_array($transaction->type, ['purchase', 'purchase_return']))
              <div class="receipt-info-row">
                <span class="receipt-label">@lang('purchase.supplier'):</span>
                <span class="receipt-value">{{ $transaction->contact->name ?? '' }}</span>
              </div>
              @if(!empty($transaction->contact->supplier_business_name))
                <div class="receipt-info-row">
                  <span class="receipt-label">@lang('business.business_name'):</span>
                  <span class="receipt-value">{{ $transaction->contact->supplier_business_name }}</span>
                </div>
              @endif
            @else
              @if($transaction->type != 'payroll' && !empty($transaction->contact))
                <div class="receipt-info-row">
                  <span class="receipt-label">@lang('contact.customer'):</span>
                  <span class="receipt-value">{{ $transaction->contact->name ?? '' }}</span>
                </div>
              @elseif(!empty($transaction->transaction_for))
                <div class="receipt-info-row">
                  <span class="receipt-label">@lang('essentials::lang.payroll_for'):</span>
                  <span class="receipt-value">{{ $transaction->transaction_for->user_full_name ?? '' }}</span>
                </div>
              @endif
            @endif
            @if(!empty($transaction->contact->mobile))
              <div class="receipt-info-row">
                <span class="receipt-label">@lang('contact.mobile'):</span>
                <span class="receipt-value">{{ $transaction->contact->mobile }}</span>
              </div>
            @endif
          </div>
        @endif

        {{-- Separator --}}
        <div class="receipt-separator"></div>

        {{-- Amount --}}
        <div class="receipt-amount-section">
          <div class="receipt-info-row">
            <span class="receipt-label"><strong>@lang('purchase.amount'):</strong></span>
            <span class="receipt-value"><strong>@format_currency($single_payment_line->amount)</strong></span>
          </div>
        </div>

        {{-- Separator --}}
        <div class="receipt-separator"></div>

        {{-- Payment Details --}}
        <div class="receipt-info-section">
          <div class="receipt-info-row">
            <span class="receipt-label">@lang('lang_v1.payment_method'):</span>
            <span class="receipt-value">{{ $payment_types[$single_payment_line->method] ?? '' }}</span>
          </div>

          @if($single_payment_line->method == "card")
            @if(!empty($single_payment_line->card_holder_name))
              <div class="receipt-info-row">
                <span class="receipt-label">@lang('lang_v1.card_holder_name'):</span>
                <span class="receipt-value">{{ $single_payment_line->card_holder_name }}</span>
              </div>
            @endif
            @if(!empty($single_payment_line->card_number))
              <div class="receipt-info-row">
                <span class="receipt-label">@lang('lang_v1.card_number'):</span>
                <span class="receipt-value">{{ $single_payment_line->card_number }}</span>
              </div>
            @endif
            @if(!empty($single_payment_line->card_transaction_number))
              <div class="receipt-info-row">
                <span class="receipt-label">@lang('lang_v1.card_transaction_number'):</span>
                <span class="receipt-value">{{ $single_payment_line->card_transaction_number }}</span>
              </div>
            @endif
          @elseif($single_payment_line->method == "cheque")
            @if(!empty($single_payment_line->cheque_number))
              <div class="receipt-info-row">
                <span class="receipt-label">@lang('lang_v1.cheque_number'):</span>
                <span class="receipt-value">{{ $single_payment_line->cheque_number }}</span>
              </div>
            @endif
            @if(!empty($single_payment_line->cheque_issue_date))
              <div class="receipt-info-row">
                <span class="receipt-label">@lang('lang_v1.cheque_issue_date'):</span>
                <span class="receipt-value">{{ @format_datetime($single_payment_line->cheque_issue_date) }}</span>
              </div>
            @endif
            @if(!empty($single_payment_line->cheque_passing_date))
              <div class="receipt-info-row">
                <span class="receipt-label">@lang('lang_v1.cheque_passing_date'):</span>
                <span class="receipt-value">{{ @format_datetime($single_payment_line->cheque_passing_date) }}</span>
              </div>
            @endif
            @if(!empty($single_payment_line->cheque_bank_name))
              <div class="receipt-info-row">
                <span class="receipt-label">@lang('lang_v1.cheque_bank_name'):</span>
                <span class="receipt-value">{{ $single_payment_line->cheque_bank_name }}</span>
              </div>
            @endif
            @if(!empty($single_payment_line->cheque_status))
              <div class="receipt-info-row">
                <span class="receipt-label">@lang('lang_v1.cheque_status'):</span>
                <span class="receipt-value">@lang('lang_v1.' . $single_payment_line->cheque_status)</span>
              </div>
            @endif
          @elseif(in_array($single_payment_line->method, ['custom_pay_1', 'custom_pay_2', 'custom_pay_3']))
            @if(!empty($single_payment_line->transaction_no))
              <div class="receipt-info-row">
                <span class="receipt-label">@lang('lang_v1.transaction_number'):</span>
                <span class="receipt-value">{{ $single_payment_line->transaction_no }}</span>
              </div>
            @endif
          @endif

          @if(!empty($single_payment_line->note))
            <div class="receipt-info-row">
              <span class="receipt-label">@lang('purchase.payment_note'):</span>
              <span class="receipt-value">{{ $single_payment_line->note }}</span>
            </div>
          @endif
        </div>

        {{-- Document Download --}}
        @if(!empty($single_payment_line->document_path))
          <div class="text-center no-print" style="margin-top: 10px;">
            <a href="{{$single_payment_line->document_path}}" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-accent" download="{{$single_payment_line->document_name}}">
              <i class="fa fa-download"></i> {{__('purchase.download_document')}}
            </a>
          </div>
        @endif

      </div>
    </div>
    <div class="modal-footer no-print">
      <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white" 
        aria-label="Print" 
          onclick="$(this).closest('div.modal').printThis();">
        <i class="fa fa-print"></i> @lang( 'messages.print' )
      </button>
      <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">@lang( 'messages.close' )
      </button>
    </div>
  </div>
</div>

<style type="text/css">
  .payment-receipt-view {
    max-width: 400px;
    margin: 0 auto;
    font-family: Arial, Helvetica, sans-serif;
    color: #000;
    font-size: 13px;
  }
  .receipt-header {
    margin-bottom: 10px;
  }
  .receipt-business-name {
    font-size: 18px;
    font-weight: 900;
    text-transform: uppercase;
    margin-bottom: 2px;
  }
  .receipt-business-info {
    font-size: 12px;
    line-height: 1.4;
  }
  .receipt-title {
    font-size: 15px;
    font-weight: 800;
    text-transform: uppercase;
    margin: 10px 0;
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
    padding: 3px 0;
  }
  .receipt-info-section {
    margin-bottom: 8px;
  }
  .receipt-info-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    line-height: 1.6;
    font-size: 13px;
  }
  .receipt-label {
    flex-shrink: 0;
    margin-right: 10px;
  }
  .receipt-value {
    text-align: right;
    word-break: break-word;
  }
  .receipt-separator {
    border-bottom: 2px solid #000;
    margin: 8px 0;
  }
  .receipt-amount-section .receipt-info-row {
    font-size: 15px;
    padding: 4px 0;
  }

  @media print {
    .payment-receipt-view {
      max-width: 100%;
    }
  }
</style>
