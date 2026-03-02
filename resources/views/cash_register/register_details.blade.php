<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content" style="border-radius:12px; overflow:hidden; box-shadow: 0 10px 30px rgba(22,17,96,0.25);">
    <div class="modal-header mini_print" style="background:linear-gradient(135deg,#161160 0%, #2a2480 100%); color:#ffffff; border-radius:12px 12px 0 0;">
      <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close" style="color:#ffffff; opacity:0.9;"><span aria-hidden="true">&times;</span></button>
      <h3 class="modal-title" style="color:#ffffff; font-weight:600;">@lang( 'cash_register.register_details' ) ( {{ \Carbon::createFromFormat('Y-m-d H:i:s', $register_details->open_time)->format('jS M, Y h:i A') }} -  {{\Carbon::createFromFormat('Y-m-d H:i:s', $close_time)->format('jS M, Y h:i A')}} )</h3>
    </div>

    <div class="modal-body" style="background:#ffffff;">
      @include('cash_register.payment_details')
      <hr>
      @if(!empty($register_details->denominations))
        @php
          $total = 0;
        @endphp
        <div class="row">
          <div class="col-md-8 col-sm-12">
            <h3>@lang( 'lang_v1.cash_denominations' )</h3>
            <table class="table table-slim">
              <thead>
                <tr>
                  <th width="20%" class="text-right">@lang('lang_v1.denomination')</th>
                  <th width="20%">&nbsp;</th>
                  <th width="20%" class="text-center">@lang('lang_v1.count')</th>
                  <th width="20%">&nbsp;</th>
                  <th width="20%" class="text-left">@lang('sale.subtotal')</th>
                </tr>
              </thead>
              <tbody>
                @foreach($register_details->denominations as $key => $value)
                <tr>
                  <td class="text-right">{{$key}}</td>
                  <td class="text-center">X</td>
                  <td class="text-center">{{$value ?? 0}}</td>
                  <td class="text-center">=</td>
                  <td class="text-left">
                    @format_currency($key * $value)
                  </td>
                </tr>
                @php
                  $total += ($key * $value);
                @endphp
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="4" class="text-center">@lang('sale.total')</th>
                  <td>@format_currency($total)</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      @endif
      
      <div class="row">
        <div class="col-xs-6">
          <b>@lang('report.user'):</b> {{ $register_details->user_name}}<br>
          <b>@lang('business.email'):</b> {{ $register_details->email}}<br>
          <b>@lang('business.business_location'):</b> {{ $register_details->location_name}}<br>
        </div>
        @if(!empty($register_details->closing_note))
          <div class="col-xs-6">
            <strong>@lang('cash_register.closing_note'):</strong><br>
            {{$register_details->closing_note}}
          </div>
        @endif
      </div>
    </div>

    <div class="modal-footer" style="background:#f8f9fa; border-top:1px solid rgba(22,17,96,0.1);">
      <button type="button" class="btn btn-default no-print" data-dismiss="modal" style="background:#e8ebed; color:#374151; border:1px solid #e5e7eb; padding:8px 20px; border-radius:6px; font-weight:500;">@lang('messages.close')</button>
      <button type="button" class="btn btn-primary no-print print-mini-button" aria-label="Print" style="background:linear-gradient(135deg,#161160 0%, #2a2480 100%); color:#ffffff; border:none; padding:8px 20px; border-radius:6px; font-weight:600;">
        <i class="fa fa-print"></i> @lang('messages.print_mini')
      </button>
      <button type="button" class="btn btn-primary no-print" aria-label="Print" onclick="$(this).closest('div.modal').printThis();" style="background:linear-gradient(135deg,#161160 0%, #2a2480 100%); color:#ffffff; border:none; padding:8px 20px; border-radius:6px; font-weight:600;">
        <i class="fa fa-print"></i> @lang( 'messages.print_detailed' )
      </button>
    </div>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<style type="text/css">
  @media print {
    .modal {
        position: absolute;
        left: 0;
        top: 0;
        margin: 0;
        padding: 0;
        overflow: visible!important;
    }
}
</style>
<script>
  $(document).ready(function () {
      $(document).on('click', '.print-mini-button', function () {
          $('.mini_print').printThis();
      });
  });
</script>
@include('cash_register.edit_cash_in_hand_modal')