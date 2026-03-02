<div class="row mini_print">
  <div class="col-sm-12">
    <table class="table table-condensed">
      <tr>
        <th>@lang('lang_v1.payment_method')</th>
        <th>@lang('sale.sale')</th>
        <th>@lang('lang_v1.expense')</th>
      </tr>
      <tr>
        <td>
          @lang('cash_register.cash_in_hand'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->cash_in_hand }}</span>
            <button type="button" class="btn btn-xs btn-warning edit-cash-in-hand-btn" style="margin-left:8px;" data-toggle="modal" data-target="#editCashInHandModal">
              <i class="fa fa-edit"></i> Edit
            </button>
        </td>
        <td>--</td>
      </tr>
        <!-- Edit Cash In Hand Modal -->
        <div class="modal fade" id="editCashInHandModal" tabindex="-1" role="dialog" aria-labelledby="editCashInHandModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="editCashInHandModalLabel">Edit Cash in Hand</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
                <form id="editCashInHandForm" method="POST" action="{{ route('cash_register.update_cash_in_hand', $register_details->id) }}">
                  @csrf
                  <div class="modal-body">
                    <div class="form-group">
                      <label for="cash_in_hand_amount">New Cash in Hand Amount</label>
                      <input type="number" step="0.01" min="0" class="form-control" id="cash_in_hand_amount" name="cash_in_hand_amount" value="{{ $register_details->cash_in_hand }}" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                </form>
                <script>
                  $(document).ready(function() {
                    $('#editCashInHandForm').on('submit', function(e) {
                      e.preventDefault();
                      var form = $(this);
                      var url = form.attr('action');
                      var data = form.serialize();
                      $.post(url, data, function(response) {
                        $('#editCashInHandModal').modal('hide');
                        // Reload the register details modal content
                        $.get(window.location.href, function(page) {
                          var newContent = $(page).find('.register_details_modal').html();
                          $('.register_details_modal').html(newContent);
                        });
                      });
                    });
                  });
                </script>
            </div>
          </div>
        </div>
      <tr>
        <td>
          @lang('cash_register.cash_payment'):
        </th>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cash }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cash_expense }}</span>
        </td>
      </tr>
      <tr>
        <td>
          @lang('cash_register.checque_payment'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cheque }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cheque_expense }}</span>
        </td>
      </tr>
      <tr>
        <td>
          @lang('cash_register.card_payment'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_card }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_card_expense }}</span>
        </td>
      </tr>
      <tr>
        <td>
          @lang('cash_register.bank_transfer'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_bank_transfer }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_bank_transfer_expense }}</span>
        </td>
      </tr>
      <tr>
        <td>
          @lang('lang_v1.advance_payment'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_advance }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_advance_expense }}</span>
        </td>
      </tr>
      @if(array_key_exists('custom_pay_1', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_1']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_1 }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_1_expense }}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_2', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_2']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_2 }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_2_expense }}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_3', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_3']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_3 }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_3_expense }}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_4', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_4']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_4 }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_4_expense }}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_5', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_5']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_5 }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_5_expense }}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_6', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_6']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_6 }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_6_expense }}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_7', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_7']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_7 }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_7_expense }}</span>
          </td>
        </tr>
      @endif
      <tr>
        <td>
          @lang('cash_register.other_payments'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_other }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_other_expense }}</span>
        </td>
      </tr>
    </table>
    <hr>
    <table class="table table-condensed">
      <tr>
        <td>
          @lang('cash_register.total_sales'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_sale }}</span>
        </td>
      </tr>
      <tr class="danger">
        <th>
          @lang('cash_register.total_refund')
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_refund }}</span></b><br>
          <small>
          @if($register_details->total_cash_refund != 0)
            Cash: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cash_refund }}</span><br>
          @endif
          @if($register_details->total_cheque_refund != 0) 
            Cheque: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cheque_refund }}</span><br>
          @endif
          @if($register_details->total_card_refund != 0) 
            Card: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_card_refund }}</span><br> 
          @endif
          @if($register_details->total_bank_transfer_refund != 0)
            Bank Transfer: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_bank_transfer_refund }}</span><br>
          @endif
          @if(array_key_exists('custom_pay_1', $payment_types) && $register_details->total_custom_pay_1_refund != 0)
              {{$payment_types['custom_pay_1']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_1_refund }}</span>
          @endif
          @if(array_key_exists('custom_pay_2', $payment_types) && $register_details->total_custom_pay_2_refund != 0)
              {{$payment_types['custom_pay_2']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_2_refund }}</span>
          @endif
          @if(array_key_exists('custom_pay_3', $payment_types) && $register_details->total_custom_pay_3_refund != 0)
              {{$payment_types['custom_pay_3']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_3_refund }}</span>
          @endif
          @if($register_details->total_other_refund != 0)
            Other: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_other_refund }}</span>
          @endif
          </small>
        </td>
      </tr>
      <tr class="success">
        <th>
          @lang('lang_v1.total_payment')
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $register_details->cash_in_hand + $register_details->total_cash - $register_details->total_cash_refund }}</span></b>
        </td>
      </tr>
      <tr class="success">
        <th>
          @lang('lang_v1.credit_sales'):
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $details['transaction_details']->total_sales - $register_details->total_sale }}</span></b>
        </td>
      </tr>
      <tr class="success">
        <th>
          @lang('cash_register.total_sales'):
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $details['transaction_details']->total_sales }}</span></b>
        </td>
      </tr>
      <tr class="danger">
        <th>
          @lang('report.total_expense'):
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_expense }}</span></b>
        </td>
      </tr>
    </table>
    <hr>
    <span>
        @lang('sale.total') = 
        @format_currency($register_details->cash_in_hand) (@lang('messages.opening')) + 
        @format_currency($register_details->total_sale + $register_details->total_refund) (@lang('business.sale')) - 
        @format_currency($register_details->total_refund) (@lang('lang_v1.refund')) - 
        @format_currency($register_details->total_expense) (@lang('lang_v1.expense')) 
        = @format_currency($register_details->cash_in_hand + $register_details->total_sale - $register_details->total_expense)
    </span>
  </div>
</div>

@include('cash_register.register_product_details')