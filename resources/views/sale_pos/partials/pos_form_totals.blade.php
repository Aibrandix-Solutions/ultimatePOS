<div class="row pos_form_totals">
	<div class="col-md-12">
		<table class="table table-condensed" style="border-radius:12px; overflow:hidden; border:1px solid rgba(22,17,96,0.12); box-shadow:0 4px 12px rgba(22,17,96,0.08); background:#e8ebed;">
			<tr>
				<td style="padding:12px 16px; vertical-align:middle;">
					<b class="tw-text-base md:tw-text-lg tw-font-bold" style="color:#161160; letter-spacing:0.2px;">@lang('sale.item'):</b>&nbsp;
					<span class="total_quantity tw-text-base md:tw-text-lg tw-font-semibold" style="color:#1f2937;">0</span></td>
				<td style="padding:12px 16px; vertical-align:middle;">
					<b class="tw-text-base md:tw-text-lg tw-font-bold" style="color:#161160; letter-spacing:0.2px;">@lang('sale.total'):</b> &nbsp;
					<span class="price_total tw-text-base md:tw-text-lg tw-font-semibold" style="color:#1f2937;">0</span>
				</td>
			</tr>
			<tr>
				
					<td @if(!Gate::check('disable_discount') || auth()->user()->can('superadmin') || auth()->user()->can('admin')) class="" @else class="hide" @endif style="padding:12px 16px; vertical-align:middle; border-top:1px solid rgba(22,17,96,0.08);">
						<b class="tw-text-base md:tw-text-lg tw-font-bold" style="color:#161160; letter-spacing:0.2px;">
							@if($is_discount_enabled)
								@lang('sale.discount')
								@show_tooltip(__('tooltip.sale_discount'))
							@endif
							@if($is_rp_enabled)
								{{session('business.rp_name')}}
							@endif
							@if($is_discount_enabled)
								(-):
								@if($edit_discount)
								<i class="fas fa-edit cursor-pointer" id="pos-edit-discount" title="@lang('sale.edit_discount')" aria-hidden="true" data-toggle="modal" data-target="#posEditDiscountModal" style="color:#161160; margin-left:6px; opacity:0.9;"></i>
								@endif
							
								<span class="tw-text-base md:tw-text-lg tw-font-semibold" id="total_discount" style="color:#1f2937;">0</span>
							@endif
								<input type="hidden" name="discount_type" id="discount_type" value="@if(empty($edit)){{'percentage'}}@else{{$transaction->discount_type}}@endif" data-default="percentage">

								<input type="hidden" name="discount_amount" id="discount_amount" value="@if(empty($edit)) {{@num_format($business_details->default_sales_discount)}} @else {{@num_format($transaction->discount_amount)}} @endif" data-default="{{$business_details->default_sales_discount}}">

								<input type="hidden" name="rp_redeemed" id="rp_redeemed" value="@if(empty($edit)){{'0'}}@else{{$transaction->rp_redeemed}}@endif">

								<input type="hidden" name="rp_redeemed_amount" id="rp_redeemed_amount" value="@if(empty($edit)){{'0'}}@else {{$transaction->rp_redeemed_amount}} @endif">

								</span>
						</b> 
					</td>
				
				<td class="@if($pos_settings['disable_order_tax'] != 0) hide @endif" style="padding:12px 16px; vertical-align:middle; border-top:1px solid rgba(22,17,96,0.08);">
					<span class="tw-text-base md:tw-text-lg tw-font-semibold">
						<b class="tw-text-base md:tw-text-lg tw-font-bold" style="color:#161160; letter-spacing:0.2px;">@lang('sale.order_tax')(+): @show_tooltip(__('tooltip.sale_tax'))</b>
						<i class="fas fa-edit cursor-pointer" title="@lang('sale.edit_order_tax')" aria-hidden="true" data-toggle="modal" data-target="#posEditOrderTaxModal" id="pos-edit-tax" style="color:#161160; margin-left:6px; opacity:0.9;"></i> 
						<span class="tw-text-base md:tw-text-lg tw-font-semibold" id="order_tax" style="color:#1f2937;">
							@if(empty($edit))
								0
							@else
								{{$transaction->tax_amount}}
							@endif
						</span>

						<input type="hidden" name="tax_rate_id" 
							id="tax_rate_id" 
							value="@if(empty($edit)) {{$business_details->default_sales_tax}} @else {{$transaction->tax_id}} @endif" 
							data-default="{{$business_details->default_sales_tax}}">

						<input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" 
							value="@if(empty($edit)) {{@num_format($business_details->tax_calculation_amount)}} @else {{@num_format($transaction->tax?->amount)}} @endif" data-default="{{$business_details->tax_calculation_amount}}">

						<input type="hidden" name="tax_calculation_type" id="tax_calculation_type"
							value="@if(empty($edit)) percentage @else {{$transaction->tax->calculation_type ?? 'percentage'}} @endif" data-default="percentage">

					</span>
				</td>
				<td style="padding:12px 16px; vertical-align:middle; border-top:1px solid rgba(22,17,96,0.08);">
					<span class="tw-text-base md:tw-text-lg tw-font-semibold">

						<b class="tw-text-base md:tw-text-lg tw-font-bold" style="color:#161160; letter-spacing:0.2px;">@lang('sale.shipping')(+): @show_tooltip(__('tooltip.shipping'))</b> 
						<i class="fas fa-edit cursor-pointer"  title="@lang('sale.shipping')" aria-hidden="true" data-toggle="modal" data-target="#posShippingModal" style="color:#161160; margin-left:6px; opacity:0.9;"></i>
						<span id="shipping_charges_amount" style="color:#1f2937;">0</span>
						<input type="hidden" name="shipping_details" id="shipping_details" value="@if(empty($edit)){{''}}@else{{$transaction->shipping_details}}@endif" data-default="">

						<input type="hidden" name="shipping_address" id="shipping_address" value="@if(empty($edit)){{''}}@else{{$transaction->shipping_address}}@endif">

						<input type="hidden" name="shipping_status" id="shipping_status" value="@if(empty($edit)){{''}}@else{{$transaction->shipping_status}}@endif">

						<input type="hidden" name="delivered_to" id="delivered_to" value="@if(empty($edit)){{''}}@else{{$transaction->delivered_to}}@endif">

						<input type="hidden" name="delivery_person" id="delivery_person" value="@if(empty($edit)){{''}}@else{{$transaction->delivery_person}}@endif">

						<input type="hidden" name="shipping_charges" id="shipping_charges" value="@if(empty($edit)){{@num_format(0.00)}} @else{{@num_format($transaction->shipping_charges)}} @endif" data-default="0.00">
					</span>
				</td>
				@if(in_array('types_of_service', $enabled_modules))
					<td class="col-sm-3 col-xs-6 d-inline-table">
						<b class="tw-text-base md:tw-text-lg tw-font-bold">@lang('lang_v1.packing_charge')(+):</b>
						<i class="fas fa-edit cursor-pointer service_modal_btn"></i> 
						<span  class="tw-text-base md:tw-text-lg tw-font-semibold" id="packing_charge_text">
							0
						</span>
					</td>
				@endif
				@if(!empty($pos_settings['amount_rounding_method']) && $pos_settings['amount_rounding_method'] > 0)
				<td>
					<b class="tw-text-base md:tw-text-lg tw-font-bold" id="round_off">@lang('lang_v1.round_off'):</b> <span id="round_off_text">0</span>								
					<input type="hidden" name="round_off_amount" id="round_off_amount" value=0>
				</td>
				@endif
			</tr>
			@if(!empty($exchange_data))
			<tr style="background: rgba(23, 162, 184, 0.1);">
				<td colspan="4" style="padding:12px 16px; vertical-align:middle; border-top:2px solid #17a2b8;">
					<b class="tw-text-base md:tw-text-lg tw-font-bold" style="color:#17a2b8; letter-spacing:0.2px;">
						@lang('lang_v1.exchange') @lang('lang_v1.return_credit')(-):
					</b>
					<span class="tw-text-base md:tw-text-lg tw-font-semibold" id="exchange_credit_display" style="color:#17a2b8;">
						{{ @num_format($exchange_data['return_credit']) }}
					</span>
					<input type="hidden" name="exchange_credit" id="exchange_credit" value="{{ $exchange_data['return_credit'] }}">
				</td>
			</tr>
			@endif
		</table>
	</div>
</div>