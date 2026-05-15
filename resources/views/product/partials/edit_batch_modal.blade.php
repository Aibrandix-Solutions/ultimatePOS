<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    {!! Form::open(['url' => action([\App\Http\Controllers\ProductBatchController::class, 'update'], [$purchase_line->id]), 'method' => 'put', 'id' => 'batch_edit_form' ]) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang('lang_v1.edit_batch'): {{ $purchase_line->batch_number }}</h4>
    </div>

    <div class="modal-body">
      <input type="hidden" id="tax_rate" value="{{ $tax_rate }}">
      <input type="hidden" id="tax_type" value="{{ $tax_calculation_type }}">
      
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered">
            <thead>
              <tr class="bg-green">
                <th colspan="2">@lang('lang_v1.product_cost')</th>
                <th>@lang('lang_v1.margin') (%)</th>
                <th>@lang('lang_v1.product_price')</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <label>@lang('lang_v1.exc_tax'):*</label>
                  {!! Form::text('purchase_price', @num_format($purchase_line->purchase_price), ['class' => 'form-control input_number', 'id' => 'purchase_price', 'required']) !!}
                </td>
                <td>
                  <label>@lang('lang_v1.inc_tax'):*</label>
                  {!! Form::text('purchase_price_inc_tax', @num_format($purchase_line->purchase_price_inc_tax), ['class' => 'form-control input_number', 'id' => 'purchase_price_inc_tax', 'required']) !!}
                </td>
                <td>
                   <label>&nbsp;</label>
                   {!! Form::text('batch_profit_margin', @num_format($purchase_line->batch_profit_margin), ['class' => 'form-control input_number', 'id' => 'batch_profit_margin', 'required']) !!}
                </td>
                <td>
                  <label>@lang('lang_v1.inc_tax')</label>
                  {!! Form::text('batch_selling_price_inc_tax', @num_format($purchase_line->batch_selling_price_inc_tax), ['class' => 'form-control input_number', 'id' => 'batch_selling_price_inc_tax', 'required']) !!}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    {!! Form::close() !!}
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    function calculate_prices() {
        var tax_rate = __read_number($('#tax_rate'));

        // Handle purchase price changes
        $(document).on('change', '#purchase_price', function() {
            var val = __read_number($(this));
            var tax_type = $('#tax_type').val();
            var inc_tax = (tax_type == 'fixed') ? (val + tax_rate) : (val + (val * tax_rate / 100));
            __write_number($('#purchase_price_inc_tax'), inc_tax);
            update_selling_price();
        });

        $(document).on('change', '#purchase_price_inc_tax', function() {
            var val = __read_number($(this));
            var tax_type = $('#tax_type').val();
            var exc_tax = (tax_type == 'fixed') ? (val - tax_rate) : (val / (1 + tax_rate / 100));
            __write_number($('#purchase_price'), exc_tax);
            update_selling_price();
        });

        $(document).on('change', '#batch_profit_margin', function() {
            update_selling_price();
        });

        $(document).on('change', '#batch_selling_price_inc_tax', function() {
            update_margin();
        });

        function update_selling_price() {
            var p_inc_tax = __read_number($('#purchase_price_inc_tax'));
            var m = __read_number($('#batch_profit_margin'));
            // Use __add_percent from functions.js if available, or manual math (consistent with purchase.js)
            var s_inc_tax = p_inc_tax + (p_inc_tax * m / 100);
            __write_number($('#batch_selling_price_inc_tax'), s_inc_tax);
        }

        function update_margin() {
            var p_inc_tax = __read_number($('#purchase_price_inc_tax'));
            var s_inc_tax = __read_number($('#batch_selling_price_inc_tax'));
            var m = 0;
            if (p_inc_tax != 0) {
                m = ((s_inc_tax - p_inc_tax) / p_inc_tax) * 100;
            }
            __write_number($('#batch_profit_margin'), m);
        }

        // Trigger initial calculation to resolve any inconsistencies on load
        // But only if we have cost and margin
        if (__read_number($('#purchase_price_inc_tax')) > 0 && __read_number($('#batch_profit_margin')) > 0) {
            update_selling_price();
        }
    }
    calculate_prices();
});
</script>
