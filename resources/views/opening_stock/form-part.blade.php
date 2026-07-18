@php
	$enable_batch_pricing = !empty($enable_batch_pricing ?? false);
	$next_batch_labels = $next_batch_labels ?? [];
	$tax_percent = !empty($product->product_tax->amount) ? $product->product_tax->amount : 0;
	$tax_calc_type = !empty($product->product_tax->calculation_type) ? $product->product_tax->calculation_type : 'percentage';
	$selling_price_tax_type = !empty($product->tax_type) ? $product->tax_type : 'exclusive';
@endphp
<div class="row">
	<div class="col-sm-12">
		@forelse($locations as $key => $value)
		<div class="box box-solid">
			<div class="box-header">
	            <h3 class="box-title">@lang('sale.location'): {{$value}}</h3>
	        </div>
			<div class="box-body">
				<div class="row tw-overflow-scroll">
					<div class="col-sm-12">
						<table class="table table-condensed table-bordered text-center table-responsive table-striped add_opening_stock_table"
							data-tax-percent="{{ $tax_percent }}"
							data-tax-calc-type="{{ $tax_calc_type }}"
							data-selling-price-tax-type="{{ $selling_price_tax_type }}"
							data-enable-batch-pricing="{{ $enable_batch_pricing ? 1 : 0 }}">
								<thead>
								<tr class="bg-green">
									<th>@lang( 'product.product_name' )</th>
									<th>@lang( 'lang_v1.quantity_left' )</th>
									<th>@lang( 'purchase.unit_cost_before_tax' )</th>
									@if($enable_batch_pricing)
										<th>@lang( 'lang_v1.batch_number' )</th>
										<th>@lang( 'product.profit_percent' )</th>
										<th>@lang( 'lang_v1.selling_price' )</th>
									@endif
									@if($enable_expiry == 1 && $product->enable_stock == 1)
										<th>Exp. Date</th>
									@endif
									@if($enable_lot == 1)
										<th>@lang( 'lang_v1.lot_number' )</th>
									@endif
									<th>@lang( 'purchase.subtotal_before_tax' )</th>
									<th>@lang( 'lang_v1.date' )</th>
									<th>@lang( 'brand.note' )</th>
									<th>&nbsp;</th>
								</tr>
								</thead>
								<tbody>
@php
	$subtotal = 0;
@endphp
@foreach($product->variations as $variation)
	@php
		$next_batch_label = $next_batch_labels[$key][$variation->id] ?? 'Batch 1';
		$default_profit = !empty($variation->profit_percent) ? $variation->profit_percent : 0;
		$default_purchase = $variation->default_purchase_price;
		$default_item_tax = $tax_calc_type == 'fixed' ? $tax_percent : ($default_purchase * $tax_percent / 100);
		$default_purchase_inc = $default_purchase + $default_item_tax;
		$default_sell_inc = $default_purchase_inc + ($default_purchase_inc * $default_profit / 100);
		$default_sell_exc = $tax_calc_type == 'fixed'
			? max(0, $default_sell_inc - $tax_percent)
			: ($tax_percent != 0 ? ($default_sell_inc * 100) / (100 + $tax_percent) : $default_sell_inc);
		$default_sell_show = ($selling_price_tax_type == 'inclusive') ? $default_sell_inc : $default_sell_exc;
	@endphp
	@if(empty($purchases[$key][$variation->id]))
		@php
			$purchases[$key][$variation->id][] = [
				'quantity' => 0,
				'purchase_price' => $variation->default_purchase_price,
				'purchase_line_id' => null,
				'lot_number' => null,
				'transaction_date' => null,
				'purchase_line_note' => null,
				'secondary_unit_quantity' => 0,
				'batch_id' => null,
				'batch_number' => $enable_batch_pricing ? $next_batch_label : null,
				'batch_profit_margin' => $default_profit,
				'batch_selling_price' => $default_sell_exc,
				'batch_selling_price_inc_tax' => $default_sell_inc,
			];
			// Blank row consumed this label; "+" must start at the next one.
			if ($enable_batch_pricing && preg_match('/^Batch\s+(\d+)$/i', trim((string) $next_batch_label), $m)) {
				$next_batch_label = 'Batch '.((int) $m[1] + 1);
			}
		@endphp
	@endif

@foreach($purchases[$key][$variation->id] as $sub_key => $var)
	@php

	$purchase_line_id = $var['purchase_line_id'];

	// For the first existing row per variation+location, display the TOTAL
	// qty_available so the user sets the total stock (not just the opening-stock slice).
	// A hidden original_qty field carries the old value so save() can compute the
	// correct absolute delta: new_qty_available = user_input.
	$original_qty = null;
	if ($sub_key === 0 && !empty($purchase_line_id) && isset($qty_available_map[$key][$variation->id])) {
		$qty = $qty_available_map[$key][$variation->id];
		$original_qty = $qty;
	} else {
		$qty = $var['quantity'];
	}

	$purcahse_price = $var['purchase_price'];

	$row_total = $qty * $purcahse_price;

	$subtotal += $row_total;
	$lot_number = $var['lot_number'];
	$transaction_date = $var['transaction_date'];
	$purchase_line_note = $var['purchase_line_note'];

	$row_profit = array_key_exists('batch_profit_margin', $var) && $var['batch_profit_margin'] !== null
		? $var['batch_profit_margin']
		: $default_profit;

	$row_purchase_inc = $purcahse_price + ($tax_calc_type == 'fixed' ? $tax_percent : ($purcahse_price * $tax_percent / 100));
	$row_sell_inc = array_key_exists('batch_selling_price_inc_tax', $var) && $var['batch_selling_price_inc_tax'] !== null
		? $var['batch_selling_price_inc_tax']
		: ($row_purchase_inc + ($row_purchase_inc * $row_profit / 100));
	$row_sell_exc = array_key_exists('batch_selling_price', $var) && $var['batch_selling_price'] !== null
		? $var['batch_selling_price']
		: ($tax_calc_type == 'fixed'
			? max(0, $row_sell_inc - $tax_percent)
			: ($tax_percent != 0 ? ($row_sell_inc * 100) / (100 + $tax_percent) : $row_sell_inc));
	$row_sell_show = ($selling_price_tax_type == 'inclusive') ? $row_sell_inc : $row_sell_exc;

	$row_batch_number = !empty($var['batch_number'])
		? $var['batch_number']
		: ($enable_batch_pricing ? $next_batch_label : null);
	@endphp

<tr data-variation-id="{{ $variation->id }}" data-location-id="{{ $key }}">
	<td>
		{{ $product->name }} @if( $product->type == 'variable' ) (<b>{{ $variation->product_variation->name }}</b> : {{ $variation->name }}) @endif

		@if(!empty($purchase_line_id))
			{!! Form::hidden('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][purchase_line_id]', $purchase_line_id) !!}
		@endif
		@if(!is_null($original_qty))
			{!! Form::hidden('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][original_qty]', $original_qty) !!}
		@endif
		@if($enable_batch_pricing)
			{!! Form::hidden('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][selling_price_tax_type]', $selling_price_tax_type) !!}
		@endif
	</td>
	<td>
		<div class="input-group">
		  {!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][quantity]', @format_quantity($qty) , ['class' => 'form-control input-sm input_number purchase_quantity input_quantity', 'required']) !!}
		  <span class="input-group-addon">
		    {{ $product->unit->short_name }}
		  </span>
		</div>
		@if(!empty($product->second_unit))
			<br>
            <span>
            @lang('lang_v1.quantity_in_second_unit', ['unit' => $product->second_unit->short_name])*:</span><br>
            {!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][secondary_unit_quantity]', @format_quantity($var['secondary_unit_quantity']) , ['class' => 'form-control input-sm input_number input_quantity', 'required']) !!}
		@endif
	</td>
<td>
	{!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][purchase_price]', @num_format($purcahse_price) , ['class' => 'form-control input-sm input_number unit_price', 'required']) !!}
</td>

@if($enable_batch_pricing)
	<td>
		{!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][batch_number]', $row_batch_number, [
			'class' => 'form-control input-sm batch_number_input',
			'readonly' => true,
			'title' => __('lang_v1.batch_number'),
		]) !!}
		@if(empty($purchase_line_id) || empty($var['batch_id']))
			<small class="text-muted">@lang('lang_v1.new_batch')</small>
		@endif
	</td>
	<td>
		{!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][profit_percent]', @num_format($row_profit), ['class' => 'form-control input-sm input_number profit_percent', 'required']) !!}
	</td>
	<td>
		{!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][default_sell_price]', @num_format($row_sell_show), ['class' => 'form-control input-sm input_number default_sell_price', 'required']) !!}
	</td>
@endif

@if($enable_expiry == 1 && $product->enable_stock == 1)
	<td>
		{!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][exp_date]', !empty($var['exp_date']) ? @format_date($var['exp_date']) : null , ['class' => 'form-control input-sm os_exp_date', 'readonly']) !!}
	</td>
@endif

@if($enable_lot == 1)
	<td>
		{!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][lot_number]', $lot_number , ['class' => 'form-control input-sm']) !!}
	</td>
@endif
	<td>
		<span class="row_subtotal_before_tax">{{@num_format($row_total)}}</span>
	</td>
	<td>
		<div class="input-group date">
		{!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][transaction_date]', $transaction_date , ['class' => 'form-control input-sm os_date', 'readonly']) !!}
		</div>
	</td>
	<td>
		{!! Form::textarea('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][purchase_line_note]', $purchase_line_note , ['class' => 'form-control input-sm', 'rows' => 3 ]) !!}
	</td>
	<td>
		@if($loop->index == 0)
			<button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline  tw-dw-btn-primary add_stock_row"
				data-sub-key="{{ count($purchases[$key][$variation->id])}}"
				data-next-batch="{{ $next_batch_label }}"
				data-row-html='<tr data-variation-id="{{ $variation->id }}" data-location-id="{{ $key }}">
					<td>
						{{ $product->name }} @if( $product->type == "variable" ) (<b>{{ $variation->product_variation->name }}</b> : {{ $variation->name }}) @endif
					</td>
					<td>
					<div class="input-group">
	              		<input class="form-control input-sm input_number purchase_quantity" required="" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][quantity]" type="text" value="0">
			              <span class="input-group-addon">
			                {{ $product->unit->short_name }}
			              </span>
	        			</div>
					</td>
	<td>
		<input class="form-control input-sm input_number unit_price" required="" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][purchase_price]" type="text" value="{{@num_format($purcahse_price)}}">
	</td>
	@if($enable_batch_pricing)
	<td>
		<input class="form-control input-sm batch_number_input" readonly name="stocks[{{$key}}][{{$variation->id}}][__subkey__][batch_number]" type="text" value="__batch_number__">
		<small class="text-muted">@lang("lang_v1.new_batch")</small>
		<input type="hidden" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][selling_price_tax_type]" value="{{ $selling_price_tax_type }}">
	</td>
	<td>
		<input class="form-control input-sm input_number profit_percent" required name="stocks[{{$key}}][{{$variation->id}}][__subkey__][profit_percent]" type="text" value="{{@num_format($default_profit)}}">
	</td>
	<td>
		<input class="form-control input-sm input_number default_sell_price" required name="stocks[{{$key}}][{{$variation->id}}][__subkey__][default_sell_price]" type="text" value="{{@num_format($default_sell_show)}}">
	</td>
	@endif
	@if($enable_expiry == 1 && $product->enable_stock == 1)
	<td>
		<input class="form-control input-sm os_exp_date" required="" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][exp_date]" type="text" readonly>
	</td>
	@endif

	@if($enable_lot == 1)
	<td>
		<input class="form-control input-sm" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][lot_number]" type="text">
	</td>
	@endif
	<td>
		<span class="row_subtotal_before_tax">
			0.00
		</span>
	</td>
	<td>
		<div class="input-group date">
			<input class="form-control input-sm os_date" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][transaction_date]" type="text" readonly>
		</div>
	</td>
	<td>
		<textarea rows="3" class="form-control input-sm" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][purchase_line_note]"></textarea>
	</td>
	<td>&nbsp;</td></tr>'
	><i class="fa fa-plus"></i></button>
	@else
		&nbsp;
	@endif
			</td>
			</tr>
		@endforeach
	@endforeach
								</tbody>
								<tfoot>
								@php
									$colspan_before_total = 3; // name, qty, cost
									if ($enable_batch_pricing) {
										$colspan_before_total += 3; // batch, margin, sell
									}
									if ($enable_expiry == 1 && $product->enable_stock == 1) {
										$colspan_before_total += 1;
									}
									if ($enable_lot == 1) {
										$colspan_before_total += 1;
									}
								@endphp
								<tr>
									<td colspan="{{ $colspan_before_total }}"></td>
									<td><strong>@lang( 'lang_v1.total_amount_exc_tax' ): </strong> <span id="total_subtotal">{{@num_format($subtotal)}}</span>
									<input type="hidden" id="total_subtotal_hidden" value=0>
									</td>
								</tr>
								</tfoot>
						</table>
						
					</div>
				</div>
			</div>
		</div> <!--box end-->
		@empty
    		<h3>@lang( 'lang_v1.product_not_assigned_to_any_location' )</h3>
		@endforelse
	</div>
</div>
