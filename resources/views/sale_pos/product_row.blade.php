@php
	$common_settings = session()->get('business.common_settings');
	$multiplier = 1;

	$action = !empty($action) ? $action : '';
@endphp

@foreach($sub_units as $key => $value)
	@if(!empty($product->sub_unit_id) && $product->sub_unit_id == $key)
		@php
			$multiplier = $value['multiplier'];
		@endphp
	@endif
@endforeach

<tr class="product_row modern-table-row" data-row_index="{{$row_count}}" @if(!empty($so_line)) data-so_id="{{$so_line->transaction_id}}" @endif style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 12px; margin: 6px 0; box-shadow: 0 4px 12px rgba(22,17,96,0.08), 0 2px 4px rgba(22,17,96,0.04); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(22,17,96,0.06); position: relative; overflow: hidden;">
	@if(!empty($is_serial_no))
		<td class="serial_no" ></td>
	@endif
	<td>
		@if(!empty($so_line))
			<input type="hidden" 
			name="products[{{$row_count}}][so_line_id]" 
			value="{{$so_line->id}}">
		@endif
		@php
			$product_name = e($product->product_name) . '<br/>' . $product->sub_sku ;
			if(!empty($product->brand)){ $product_name .= ' ' . $product->brand ;}
			$warranty_id = !empty($action) && $action == 'edit' && !empty($product->warranties->first())  ? $product->warranties->first()->id : $product->warranty_id;
			$product_image_url = '';
			if(count($product->media) > 0) {
				$product_image_url = $product->media->first()->display_url;
			} elseif(!empty($product->product_image)) {
				$product_image_url = asset('/uploads/img/' . rawurlencode($product->product_image));
			} else {
				$product_image_url = asset('/img/default.png');
			}
		@endphp

		<input type="hidden" class="pos_product_image_url" value="{{$product_image_url}}">

		@if( ($edit_price || $edit_discount) && empty($is_direct_sell) )
		<div title="@lang('lang_v1.pos_edit_product_price_help')" style="display: inline">
		<span class="text-link text-info cursor-pointer" data-toggle="modal" data-target="#row_edit_product_price_modal" data-row-index="{{$row_count}}" style="color: #161160; text-decoration: none; cursor: pointer; font-weight: 600; font-size: 14px; letter-spacing: 0.2px; transition: all 0.3s ease; display: inline-block; padding: 1px 0;">
			{!! $product_name !!}
			&nbsp;<i class="fa fa-info-circle" style="color: #161160; font-size: 13px; opacity: 0.8; transition: all 0.3s ease;"></i>
		</span>
		</div>
		@else
			{!! $product_name !!}
		@endif

		@if(!empty($common_settings['enable_product_warranty']))
			<div style="display:none;">
				{!! Form::select("products[$row_count][warranty_id]", $warranties, $warranty_id ?? ($product->warranty_id ?? null), ['placeholder' => __('messages.please_select'), 'class' => 'row_warranty_id']) !!}
			</div>
		@endif

		@php
			$show_eye = auth()->user()->can('view_purchase_price') || !empty($product->product_description);
		@endphp
		@if($show_eye)
			<button type="button"
				class="btn btn-xs btn-default toggle-cost-profit"
				title="@if(auth()->user()->can('view_purchase_price')) @lang('lang_v1.view_purchase_price') / @lang('lang_v1.gross_profit') @endif @if(!empty($product->product_description)) @lang('lang_v1.product_description') @endif"
				style="margin-left: 6px; border-radius: 8px; border: 1px solid rgba(22,17,96,0.15); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); color: #161160; padding: 2px 8px; font-weight: 600;">
				<i class="fa fa-eye"></i>
			</button>
		@endif
		@if(empty($pos_settings['hide_selected_product_image']))
			<img onerror="this.onerror=null;this.src='{{ asset('/img/default.png') }}';" src="{{$product_image_url}}" alt="product-img" loading="lazy" style="height: 40px; display: inline; margin-left: 6px; border: 2px solid rgba(22,17,96,0.1); border-radius: 8px; margin-top: 2px; width: 40px; object-fit: cover; box-shadow: 0 2px 6px rgba(22,17,96,0.08); transition: all 0.3s ease; cursor: pointer;">
		@endif


		<input type="hidden" class="enable_sr_no" value="{{$product->enable_sr_no}}">
		<input type="hidden" 
			class="product_type" 
			name="products[{{$row_count}}][product_type]" 
			value="{{$product->product_type}}">

		@php
			$hide_tax = 'hide';
	        if(session()->get('business.enable_inline_tax') == 1){
	            $hide_tax = '';
	        }
	        
			$tax_id = $product->tax_id;
			$item_tax = !empty($product->item_tax) ? $product->item_tax : 0;
			$unit_price_inc_tax = $product->sell_price_inc_tax;

			if($hide_tax == 'hide'){
				$tax_id = null;
				$unit_price_inc_tax = $product->default_sell_price;
			}

			if(!empty($so_line) && $action !== 'edit') {
				$tax_id = $so_line->tax_id;
				$item_tax = $so_line->item_tax;
				$unit_price_inc_tax = $so_line->unit_price_inc_tax;
			}

			$discount_type = !empty($product->line_discount_type) ? $product->line_discount_type : 'fixed';
			$discount_amount = !empty($product->line_discount_amount) ? $product->line_discount_amount : 0;
			
			if(!empty($discount)) {
				$discount_type = $discount->discount_type;
				$discount_amount = $discount->discount_amount;
			}

			if(!empty($so_line) && $action !== 'edit') {
				$discount_type = $so_line->line_discount_type;
				$discount_amount = $so_line->line_discount_amount;
			}

  			$sell_line_note = '';
  			if(!empty($product->sell_line_note)){
  				$sell_line_note = $product->sell_line_note;
  			}
			  if(!empty($so_line)){
  				$sell_line_note = $so_line->sell_line_note;
  			}
  		@endphp

		@if(!empty($discount))
			{!! Form::hidden("products[$row_count][discount_id]", $discount->id) !!}
		@endif

		@php
			if($discount_type == 'fixed') {
				$discount_amount = $discount_amount * $multiplier;
			}

			// Calculate discounted unit price for initial subtotal display
			$discounted_unit_price_inc_tax = $unit_price_inc_tax;
			
			// Get base price without tax
			$tax_rate = 0;
			$tax_type = 'percentage';
			if(!empty($tax_id) && isset($tax_dropdown['tax_rates'][$tax_id])) {
				$tax_attributes = $tax_dropdown['attributes'][$tax_id] ?? [];
				$tax_rate = $tax_attributes['data-rate'] ?? 0;
				$tax_type = $tax_attributes['data-type'] ?? 'percentage';
			}
			
			// Calculate base price before discount
			$base_price = $unit_price_inc_tax;
			if($tax_type == 'fixed') {
				$base_price = $unit_price_inc_tax - $tax_rate;
			} elseif($tax_rate > 0) {
				$base_price = $unit_price_inc_tax / (1 + ($tax_rate / 100));
			}
			
			// Apply discount to base price
			if($discount_amount > 0) {
				if($discount_type == 'fixed') {
					$base_price = $base_price - $discount_amount;
				} else {
					// percentage discount
					$base_price = $base_price - ($base_price * ($discount_amount / 100));
				}
			}
			
			// Add tax back to get discounted price inc tax
			if($tax_type == 'fixed') {
				$discounted_unit_price_inc_tax = $base_price + $tax_rate;
			} elseif($tax_rate > 0) {
				$discounted_unit_price_inc_tax = $base_price * (1 + ($tax_rate / 100));
			} else {
				$discounted_unit_price_inc_tax = $base_price;
			}
		@endphp
		<small class="text-muted p-1" style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); padding: 3px 6px !important; border-radius: 6px; font-size: 11px; font-weight: 500; color: #64748b; border: 1px solid rgba(22,17,96,0.08); display: inline-block; margin-top: 2px;">
			@if($product->enable_stock)
			<i class="fa fa-cube" style="margin-right: 4px; color: #161160;"></i>{{ @num_format($product->qty_available) }} {{$product->unit}} @lang('lang_v1.in_stock')
			@else
				<i class="fa fa-infinity" style="margin-right: 4px; color: #161160;"></i>Unlimited
			@endif
		</small>

		{{-- Persist per-line description so it gets saved & printed on receipt --}}
		@if(empty($is_direct_sell))
			<textarea name="products[{{$row_count}}][sell_line_note]" class="tw-hidden" rows="1" style="display:none;">{{$sell_line_note}}</textarea>
		@endif

		@php
			$base_purchase_price = (!empty($product->default_purchase_price)) ? ($product->default_purchase_price / $multiplier) : 0;
		@endphp
		@if($show_eye)
			<input type="hidden" class="pos_purchase_price_base" value="{{ @num_format($base_purchase_price) }}">
			<div class="pos_cost_profit_panel" style="display:none; margin-top: 8px; padding: 8px 10px; border-radius: 10px; border: 1px dashed rgba(22,17,96,0.20); background: rgba(22,17,96,0.03); max-width: 420px;">
				@can('view_purchase_price')
				<small class="text-muted" style="display:block; margin-bottom: 4px;">
					<strong>@lang('product.default_purchase_price')</strong>
					<span class="pos_unit_cost display_currency" data-currency_symbol="true">0</span>
					&nbsp;|&nbsp;
					<strong>@lang('lang_v1.gross_profit')</strong>
					<span class="pos_unit_profit display_currency" data-currency_symbol="true">0</span>
					&nbsp;|&nbsp;
					<strong>@lang('sale.subtotal')</strong>
					<span class="pos_total_profit display_currency" data-currency_symbol="true">0</span>
				</small>
				@endcan
				@if(!empty($product->product_description))
				@can('view_purchase_price')
					<hr style="margin: 4px 0; border-top: 1px solid rgba(22,17,96,0.1);">
				@endcan
				<div style="font-size: 13px; color: #475569; margin-top: 4px;">
					{!! $product->product_description !!}
				</div>
				@endif
			</div>
		@endif

		<!-- Description modal end -->
		@if(in_array('modifiers' , $enabled_modules))
			<div class="modifiers_html">
				@if(!empty($product->product_ms))
					@include('restaurant.product_modifier_set.modifier_for_product', array('edit_modifiers' => true, 'row_count' => $loop->index, 'product_ms' => $product->product_ms ) )
				@endif
			</div>
		@endif

		@php
			$max_quantity = $product->qty_available;
			$formatted_max_quantity = $product->formatted_qty_available;

			if(!empty($action) && $action == 'edit') {
				if(!empty($so_line)) {
					$qty_available = $so_line->quantity - $so_line->so_quantity_invoiced + $product->quantity_ordered;
					$max_quantity = $qty_available;
					$formatted_max_quantity = number_format($qty_available, session('business.quantity_precision', 2), session('currency')['decimal_separator'], session('currency')['thousand_separator']);
				}
			} else {
				if(!empty($so_line) && $so_line->qty_available <= $max_quantity) {
					$max_quantity = $so_line->qty_available;
					$formatted_max_quantity = $so_line->formatted_qty_available;
				}
			}
			

			$max_qty_rule = $max_quantity;
			$max_qty_msg = __('validation.custom-messages.quantity_not_available', ['qty'=> $formatted_max_quantity, 'unit' => $product->unit  ]);
		@endphp

		@php
			$row_batch_id = (!empty($product->batch_id) && session()->get('business.enable_batch_pricing'))
				? (int) $product->batch_id : null;
			$batch_purchase_line_id = (session()->get('business.enable_batch_pricing') && !empty($purchase_line_id))
				? (int) $purchase_line_id : null;
			$show_lot_expiry_dropdown = (session()->get('business.enable_lot_number') == 1 || session()->get('business.enable_product_expiry') == 1)
				&& !empty($product->lot_numbers)
				&& empty($is_sales_order);
			$purchase_line_in_lot_list = false;
			if ($show_lot_expiry_dropdown && $batch_purchase_line_id) {
				foreach ($product->lot_numbers as $__ln) {
					if ((int) $__ln->purchase_line_id === $batch_purchase_line_id) {
						$purchase_line_in_lot_list = true;
						break;
					}
				}
			}
			// mapPurchaseSell: lot_no_line_id and/or batch_id pin stock to a bucket. Hidden lot is only for legacy purchase_line_id mapping.
			$need_hidden_lot_for_batch_map = !empty($batch_purchase_line_id) && empty($row_batch_id) && empty($is_sales_order)
				&& (!$show_lot_expiry_dropdown || !$purchase_line_in_lot_list);

			if (!empty($row_batch_id)) {
				$max_qty_msg = __('lang_v1.batch_quantity_not_available', [
					'max' => trim($formatted_max_quantity.' '.$product->unit),
				]);
			}
		@endphp
		@if( session()->get('business.enable_lot_number') == 1 || session()->get('business.enable_product_expiry') == 1)
		@php
			$lot_enabled = session()->get('business.enable_lot_number');
			$exp_enabled = session()->get('business.enable_product_expiry');
			$lot_no_line_id = '';
			if(!empty($product->lot_no_line_id)){
				$lot_no_line_id = $product->lot_no_line_id;
			}
		@endphp
		@if($show_lot_expiry_dropdown && !($batch_purchase_line_id && !$purchase_line_in_lot_list))
			<select class="form-control lot_number input-sm" name="products[{{$row_count}}][lot_no_line_id]" @if(!empty($product->transaction_sell_lines_id)) disabled @endif>
				<option value="">@lang('lang_v1.lot_n_expiry')</option>
				@foreach($product->lot_numbers as $lot_number)
					@php
						$selected = "";
						if($lot_number->purchase_line_id == $lot_no_line_id){
							$selected = "selected";

							$max_qty_rule = $lot_number->qty_available;
							$max_qty_msg = __('lang_v1.quantity_error_msg_in_lot', ['qty'=> $lot_number->qty_formated, 'unit' => $product->unit  ]);
						}

						$expiry_text = '';
						if($exp_enabled == 1 && !empty($lot_number->exp_date)){
							if( \Carbon::now()->gt(\Carbon::createFromFormat('Y-m-d', $lot_number->exp_date)) ){
								$expiry_text = '(' . __('report.expired') . ')';
							}
						}

						//preselected lot number if product searched by lot number
						if(!empty($purchase_line_id) && $purchase_line_id == $lot_number->purchase_line_id) {
							$selected = "selected";

							$max_qty_rule = $lot_number->qty_available;
							$max_qty_msg = __('lang_v1.quantity_error_msg_in_lot', ['qty'=> $lot_number->qty_formated, 'unit' => $product->unit  ]);
						}
					@endphp
					<option value="{{$lot_number->purchase_line_id}}" data-qty_available="{{$lot_number->qty_available}}" data-msg-max="@lang('lang_v1.quantity_error_msg_in_lot', ['qty'=> $lot_number->qty_formated, 'unit' => $product->unit  ])" {{$selected}}>@if(!empty($lot_number->lot_number) && $lot_enabled == 1){{$lot_number->lot_number}} @endif @if($lot_enabled == 1 && $exp_enabled == 1) - @endif @if($exp_enabled == 1 && !empty($lot_number->exp_date)) @lang('product.exp_date'): {{@format_date($lot_number->exp_date)}} @endif {{$expiry_text}}</option>
				@endforeach
			</select>
		@endif
	@endif
	@if($need_hidden_lot_for_batch_map)
		<input type="hidden" name="products[{{$row_count}}][lot_no_line_id]" value="{{ $batch_purchase_line_id }}" class="lot_no_line_id_batch_purchase_line">
	@endif
	@if(session()->get('business.enable_batch_pricing'))
		<input type="hidden" class="row_batch_id" name="products[{{$row_count}}][batch_id]" value="{{ $row_batch_id ?? '' }}">
	@endif
	@if(!empty($is_direct_sell))
  		<br>
  		<textarea class="form-control" name="products[{{$row_count}}][sell_line_note]" rows="2">{{$sell_line_note}}</textarea>
  		<p class="help-block"><small>@lang('lang_v1.sell_line_description_help')</small></p>
	@endif
	</td>

	<td>
		{{-- If edit then transaction sell lines will be present --}}
		@if(!empty($product->transaction_sell_lines_id))
			<input type="hidden" name="products[{{$row_count}}][transaction_sell_lines_id]" class="form-control" value="{{$product->transaction_sell_lines_id}}">
		@endif

		<input type="hidden" name="products[{{$row_count}}][product_id]" class="form-control product_id" value="{{$product->product_id}}">

		<input type="hidden" value="{{$product->variation_id}}" 
			name="products[{{$row_count}}][variation_id]" class="row_variation_id">

		<input type="hidden" value="{{$product->enable_stock}}" 
			name="products[{{$row_count}}][enable_stock]">
		
		@if(empty($product->quantity_ordered))
			@php
				$product->quantity_ordered = 1;
			@endphp
		@endif

		@php
			$allow_decimal = true;
			if($product->unit_allow_decimal != 1) {
				$allow_decimal = false;
			}
		@endphp
		@foreach($sub_units as $key => $value)
        	@if(!empty($product->sub_unit_id) && $product->sub_unit_id == $key)
        		@php
        			$max_qty_rule = $max_qty_rule / $multiplier;
        			$unit_name = $value['name'];
        			$max_qty_msg = __('validation.custom-messages.quantity_not_available', ['qty'=> $max_qty_rule, 'unit' => $unit_name  ]);

        			if(!empty($product->lot_no_line_id)){
        				$max_qty_msg = __('lang_v1.quantity_error_msg_in_lot', ['qty'=> $max_qty_rule, 'unit' => $unit_name  ]);
        			} elseif(!empty($row_batch_id)){
        				$max_qty_msg = __('lang_v1.batch_quantity_not_available', [
        					'max' => trim(number_format($max_qty_rule, session('business.quantity_precision', 2), session('currency')['decimal_separator'], session('currency')['thousand_separator']).' '.$unit_name),
        				]);
        			}

        			if($value['allow_decimal']) {
        				$allow_decimal = true;
        			}
        		@endphp
        	@endif
        @endforeach
		<div class="input-group input-number" style="border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(22,17,96,0.12), 0 2px 4px rgba(22,17,96,0.08); border: 1px solid rgba(22,17,96,0.15); background: #ffffff; position: relative;">
			<span class="input-group-btn"><button type="button" class="btn btn-default btn-flat quantity-down" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); border: none; color: white; padding: 6px 10px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-weight: 600; box-shadow: inset 0 1px 0 rgba(255,255,255,0.2);"><i class="fa fa-minus" style="font-size: 11px;"></i></button></span>
		<input type="text" data-min="1" style="width: auto; border: none; padding: 8px 10px; font-size: 13px; font-weight: 600; text-align: center; background: #ffffff; color: #1e293b; letter-spacing: 0.3px;"
			class="form-control pos_quantity input_number mousetrap input_quantity" 
			value="{{@format_quantity($product->quantity_ordered)}}" name="products[{{$row_count}}][quantity]" data-allow-overselling="@if(empty($pos_settings['allow_overselling'])){{'false'}}@else{{'true'}}@endif" 
			data-qty_available="{{$product->qty_available}}" 
			@if($allow_decimal) 
				data-decimal=1 
			@else 
				data-decimal=0 
				data-rule-abs_digit="true" 
				data-msg-abs_digit="@lang('lang_v1.decimal_value_not_allowed')" 
			@endif
			data-rule-required="true" 
			data-msg-required="@lang('validation.custom-messages.this_field_is_required')" 
			@if($product->enable_stock && empty($pos_settings['allow_overselling']) && empty($is_sales_order) )
				data-rule-max-value="{{$max_qty_rule}}" data-msg-max-value="{{$max_qty_msg}}" 
				data-msg_max_default="@lang('validation.custom-messages.quantity_not_available', ['qty'=> $product->formatted_qty_available, 'unit' => $product->unit  ])" 
			@endif 
		>
		<span class="input-group-btn"><button type="button" class="btn btn-default btn-flat quantity-up" style="background: linear-gradient(135deg, #51cf66 0%, #40c057 100%); border: none; color: white; padding: 6px 10px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-weight: 600; box-shadow: inset 0 1px 0 rgba(255,255,255,0.2);"><i class="fa fa-plus" style="font-size: 11px;"></i></button></span>
		</div>
		
		<input type="hidden" name="products[{{$row_count}}][product_unit_id]" value="{{$product->unit_id}}">
		@if(count($sub_units) > 0)
			<br>
			<select name="products[{{$row_count}}][sub_unit_id]" class="form-control input-sm sub_unit" style="border-radius: 8px; border: 1px solid rgba(22,17,96,0.15); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); font-size: 11px; font-weight: 500; color: #475569; padding: 4px 8px; box-shadow: 0 2px 4px rgba(22,17,96,0.06); transition: all 0.3s ease;">
                @foreach($sub_units as $key => $value)
                    <option value="{{$key}}" data-multiplier="{{$value['multiplier']}}" data-unit_name="{{$value['name']}}" data-allow_decimal="{{$value['allow_decimal']}}" @if(!empty($product->sub_unit_id) && $product->sub_unit_id == $key) selected @endif>
                        {{$value['name']}}
                    </option>
                @endforeach
           </select>
		@else
			{{$product->unit}}
		@endif

		@if(!empty($product->second_unit))
            <br>
            <span style="white-space: nowrap;">
            @lang('lang_v1.quantity_in_second_unit', ['unit' => $product->second_unit])*:</span><br>
            <input type="text" 
            name="products[{{$row_count}}][secondary_unit_quantity]" 
            value="{{@format_quantity($product->secondary_unit_quantity)}}"
            class="form-control input-sm input_number"
            required>
        @endif

		<input type="hidden" class="base_unit_multiplier" name="products[{{$row_count}}][base_unit_multiplier]" value="{{$multiplier}}">

		<input type="hidden" class="hidden_base_unit_sell_price" value="{{$product->default_sell_price / $multiplier}}">
		
		{{-- Hidden fields for combo products --}}
		@if($product->product_type == 'combo'&& !empty($product->combo_products))

			@foreach($product->combo_products as $k => $combo_product)

				@if(isset($action) && $action == 'edit')
					@php
						$combo_product['qty_required'] = $combo_product['quantity'] / $product->quantity_ordered;

						$qty_total = $combo_product['quantity'];
					@endphp
				@else
					@php
						$qty_total = $combo_product['qty_required'];
					@endphp
				@endif

				<input type="hidden" 
					name="products[{{$row_count}}][combo][{{$k}}][product_id]"
					value="{{$combo_product['product_id']}}">

					<input type="hidden" 
					name="products[{{$row_count}}][combo][{{$k}}][variation_id]"
					value="{{$combo_product['variation_id']}}">

					<input type="hidden"
					class="combo_product_qty" 
					name="products[{{$row_count}}][combo][{{$k}}][quantity]"
					data-unit_quantity="{{$combo_product['qty_required']}}"
					value="{{$qty_total}}">

					@if(isset($action) && $action == 'edit')
						<input type="hidden" 
							name="products[{{$row_count}}][combo][{{$k}}][transaction_sell_lines_id]"
							value="{{$combo_product['id']}}">
					@endif

			@endforeach
		@endif
	</td>
	@if(!empty($is_direct_sell))
		@if(!empty($pos_settings['inline_service_staff']))
			<td>
				<div class="form-group">
					<div class="input-group">
						{!! Form::select("products[" . $row_count . "][res_service_staff_id]", $waiters, !empty($product->res_service_staff_id) ? $product->res_service_staff_id : null, ['class' => 'form-control select2 order_line_service_staff', 'placeholder' => __('restaurant.select_service_staff'), 'required' => (!empty($pos_settings['is_service_staff_required']) && $pos_settings['is_service_staff_required'] == 1) ? true : false ]) !!}
					</div>
				</div>
			</td>
		@endif
		@php
			$pos_unit_price = !empty($product->unit_price_before_discount) ? $product->unit_price_before_discount : $product->default_sell_price;

			if(!empty($so_line) && $action !== 'edit') {
				$pos_unit_price = $so_line->unit_price_before_discount;
			}
		@endphp
		<td class="@if(!auth()->user()->can('edit_product_price_from_sale_screen')) hide @endif">
			<input type="text" name="products[{{$row_count}}][unit_price]" class="form-control pos_unit_price input_number mousetrap" value="{{@num_format($pos_unit_price)}}" @if(!empty($pos_settings['enable_msp']) && empty($bypass_msp)) data-rule-min-value="{{$pos_unit_price}}" data-msg-min-value="{{__('lang_v1.minimum_selling_price_error_msg', ['price' => @num_format($pos_unit_price)])}}" @endif> 

			@if(!empty($last_sell_line))
				<br>
				<small class="text-muted">@lang('lang_v1.prev_unit_price'): @format_currency($last_sell_line->unit_price_before_discount)</small>
			@endif
		</td>
		<td @if(!$edit_discount) class="hide" @endif>
			{!! Form::text("products[$row_count][line_discount_amount]", @num_format($discount_amount), ['class' => 'form-control input_number row_discount_amount']) !!}<br>
			{!! Form::select("products[$row_count][line_discount_type]", ['fixed' => __('lang_v1.fixed'), 'percentage' => __('lang_v1.percentage')], $discount_type , ['class' => 'form-control row_discount_type']) !!}
			@if(!empty($discount))
				<p class="help-block">{!! __('lang_v1.applied_discount_text', ['discount_name' => $discount->name, 'starts_at' => $discount->formated_starts_at, 'ends_at' => $discount->formated_ends_at]) !!}</p>
			@endif

			@if(!empty($last_sell_line))
				<br>
				<small class="text-muted">
					@lang('lang_v1.prev_discount'): 
					@if($last_sell_line->line_discount_type == 'percentage')
						{{@num_format($last_sell_line->line_discount_amount)}}%
					@else
						@format_currency($last_sell_line->line_discount_amount)
					@endif
				</small>
			@endif
		</td>
		<td class="text-center {{$hide_tax}}">
			{!! Form::hidden("products[$row_count][item_tax]", @num_format($item_tax), ['class' => 'item_tax']) !!}
		
			{!! Form::select("products[$row_count][tax_id]", $tax_dropdown['tax_rates'], $tax_id, ['placeholder' => 'Select', 'class' => 'form-control tax_id'], $tax_dropdown['attributes']) !!}
		</td>

	@else
		@if(!empty($pos_settings['inline_service_staff']))
			<td>
				<div class="form-group">
					<div class="input-group">
						{!! Form::select("products[" . $row_count . "][res_service_staff_id]", $waiters, !empty($product->res_service_staff_id) ? $product->res_service_staff_id : null, ['class' => 'form-control select2 order_line_service_staff', 'placeholder' => __('restaurant.select_service_staff'), 'required' => (!empty($pos_settings['is_service_staff_required']) && $pos_settings['is_service_staff_required'] == 1) ? true : false ]) !!}
					</div>
				</div>
			</td>
		@endif

	@endif
	<td class="{{$hide_tax}}">
			<!-- Hidden fields for discount so auto-discounts apply in POS JS calculator -->
			@if(empty($is_direct_sell))
				<input type="hidden" name="products[{{$row_count}}][line_discount_amount]" class="row_discount_amount" value="{{@num_format($discount_amount)}}">
				<input type="hidden" name="products[{{$row_count}}][line_discount_type]" class="row_discount_type" value="{{$discount_type}}">
			@endif

			@php
				$msp_unit_price_inc_tax = !empty($product->min_sell_price_inc_tax) ? $product->min_sell_price_inc_tax : $discounted_unit_price_inc_tax;
			@endphp
			<input type="text" style="width: auto" name="products[{{$row_count}}][unit_price_inc_tax]" class="form-control pos_unit_price_inc_tax input_number" value="{{@num_format($discounted_unit_price_inc_tax)}}" @if(!$edit_price) readonly @endif @if(!empty($pos_settings['enable_msp']) && empty($bypass_msp)) data-rule-min-value="{{$msp_unit_price_inc_tax}}" data-msg-min-value="{{__('lang_v1.minimum_selling_price_error')}}" @endif>
	</td>
	<td class="text-center" style="vertical-align: middle;">
		@php
			$subtotal_type = !empty($pos_settings['is_pos_subtotal_editable']) ? 'text' : 'hidden';

		@endphp
		<input style="width: auto; border: none; background: transparent; font-size: 16px; font-weight: 700; color: #1e293b; text-align: center; letter-spacing: 0.5px;" type="{{$subtotal_type}}" class="form-control pos_line_total @if(!empty($pos_settings['is_pos_subtotal_editable'])) input_number @endif" value="{{@num_format($product->quantity_ordered*$discounted_unit_price_inc_tax )}}">
		<span class="display_currency pos_line_total_text @if(!empty($pos_settings['is_pos_subtotal_editable'])) hide @endif" data-currency_symbol="true" style="font-size: 16px; font-weight: 700; color: #1e293b; letter-spacing: 0.5px; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">{{$product->quantity_ordered*$discounted_unit_price_inc_tax}}</span>
	</td>
	<td class="text-center v-center" style="vertical-align: middle;">
		<i class="fa fa-times text-danger pos_remove_row cursor-pointer" aria-hidden="true" style="font-size: 18px; padding: 8px; border-radius: 50%; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #dc2626; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 4px rgba(220,38,38,0.2);"></i>
	</td>
</tr>