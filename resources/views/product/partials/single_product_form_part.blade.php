@if(!session('business.enable_price_tax')) 
  @php
    $default = 0;
    $class = 'hide';
  @endphp
@else
  @php
    $default = null;
    $class = '';
  @endphp
@endif

<div class="table-responsive">
    <table class="table table-bordered add-product-price-table table-condensed {{$class}}">
        <tr>
          <th>@lang('product.default_purchase_price') @show_tooltip(__('tooltip.product_cost_help'))</th>
          <th>@lang('product.profit_percent') @show_tooltip(__('tooltip.profit_percent'))</th>
          <th>@lang('product.default_selling_price') @show_tooltip(__('tooltip.product_price_help'))</th>
          
        </tr>
        <tr>
          <td>
            <div class="col-sm-6">
              {!! Form::label('single_dpp', trans('product.exc_of_tax') . ':*') !!}
              <small class="help-block text-muted">@lang('tooltip.cost_exc_tax_help')</small>
              {!! Form::text('single_dpp', $default, ['class' => 'form-control input-sm dpp input_number', 'placeholder' => __('product.exc_of_tax'), 'required']) !!}
            </div>

            <div class="col-sm-6">
              {!! Form::label('single_dpp_inc_tax', trans('product.inc_of_tax') . ':*') !!}
              <small class="help-block text-muted">@lang('tooltip.cost_inc_tax_help')</small>
              {!! Form::text('single_dpp_inc_tax', $default, ['class' => 'form-control input-sm dpp_inc_tax input_number', 'placeholder' => __('product.inc_of_tax'), 'required']) !!}
            </div>
          </td>

          <td>
            <br/>
            {!! Form::text('profit_percent', @num_format($profit_percent), ['class' => 'form-control input-sm input_number', 'id' => 'profit_percent', 'required']) !!}
          </td>

          <td>
            <label><span class="dsp_label">@lang('product.exc_of_tax')</span></label>
            <small class="help-block text-muted dsp_help_text">@lang('tooltip.price_exc_tax_help')</small>
            {!! Form::text('single_dsp', $default, ['class' => 'form-control input-sm dsp input_number', 'placeholder' => __('product.exc_of_tax'), 'id' => 'single_dsp', 'required']) !!}

            <small class="help-block text-muted dsp_inc_tax_help_text hide">@lang('tooltip.price_inc_tax_help')</small>
            {!! Form::text('single_dsp_inc_tax', $default, ['class' => 'form-control input-sm hide input_number', 'placeholder' => __('product.inc_of_tax'), 'id' => 'single_dsp_inc_tax', 'required']) !!}

            <small class="help-block text-muted min_sell_price_help_text">@lang('lang_v1.minimum_sale_price_help')</small>
            {!! Form::text('single_min_sell_price_inc_tax', $default, ['class' => 'form-control input-sm input_number', 'placeholder' => 'Minimum selling price (inc tax)', 'id' => 'single_min_sell_price_inc_tax']) !!}
          </td>
          
        </tr>
    </table>
</div>