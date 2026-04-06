<!-- business information here -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- <link rel="stylesheet" href="style.css"> -->
        <title>Receipt-{{$receipt_details->invoice_no}}</title>
    </head>
    <body>
        <div class="ticket">
        	@if(empty($receipt_details->letter_head))
				@if(!empty($receipt_details->logo))
					<div class="text-box centered">
						<img style="max-height: 100px; width: auto;" src="{{$receipt_details->logo}}" alt="Logo">
					</div>
				@endif
				<div class="text-box">
				<!-- Logo -->
				<p class="centered">
					<!-- Header text -->
					@if(!empty($receipt_details->header_text))
						<span class="headings">{!! $receipt_details->header_text !!}</span>
						<br/>
					@endif

					<!-- business information here -->
					@if(!empty($receipt_details->display_name))
						<span class="headings">
							{{$receipt_details->display_name}}
						</span>
						<br/>
					@endif
					
					@if(!empty($receipt_details->address))
						{!! $receipt_details->address !!}
						<br/>
					@endif

					@if(!empty($receipt_details->contact))
						{!! $receipt_details->contact !!}
					@endif
					@if(!empty($receipt_details->contact) && !empty($receipt_details->website))
						, 
					@endif
					@if(!empty($receipt_details->website))
						{{ $receipt_details->website }}
					@endif
					@if(!empty($receipt_details->location_custom_fields))
						<br>{{ $receipt_details->location_custom_fields }}
					@endif

	                @if(!empty($receipt_details->contact) || !empty($receipt_details->website) || !empty($receipt_details->location_custom_fields))
	                    <br/>
	                @endif

					@if(!empty($receipt_details->sub_heading_line1))
						{{ $receipt_details->sub_heading_line1 }}<br/>
					@endif
					@if(!empty($receipt_details->sub_heading_line2))
						{{ $receipt_details->sub_heading_line2 }}<br/>
					@endif
					@if(!empty($receipt_details->sub_heading_line3))
						{{ $receipt_details->sub_heading_line3 }}<br/>
					@endif
					@if(!empty($receipt_details->sub_heading_line4))
						{{ $receipt_details->sub_heading_line4 }}<br/>
					@endif		
					@if(!empty($receipt_details->sub_heading_line5))
						{{ $receipt_details->sub_heading_line5 }}<br/>
					@endif

					@if(!empty($receipt_details->tax_info1))
						<br><b>{{ $receipt_details->tax_label1 }}</b> {{ $receipt_details->tax_info1 }}
					@endif

					@if(!empty($receipt_details->tax_info2))
						<b>{{ $receipt_details->tax_label2 }}</b> {{ $receipt_details->tax_info2 }}
					@endif
				@endif
					<!-- Title of receipt -->
					@if(!empty($receipt_details->invoice_heading))
						<br/><span class="sub-headings">{!! $receipt_details->invoice_heading !!}</span>
					@endif
				</p>
				</div>
				@if(!empty($receipt_details->letter_head))
					<div class="text-box">
						<img style="width: 100%;margin-bottom: 10px;" src="{{$receipt_details->letter_head}}">
					</div>
				@endif
			<div class="border-top textbox-info">
				<p class="f-left"><strong>{!! $receipt_details->invoice_no_prefix !!}</strong></p>
				<p class="f-right">
					{{$receipt_details->invoice_no}}
				</p>
			</div>
			<div class="textbox-info">
				<p class="f-left"><strong>{!! $receipt_details->date_label !!}</strong></p>
				<p class="f-right">
					{{$receipt_details->invoice_date}}
				</p>
			</div>
			
			@if(!empty($receipt_details->due_date_label))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->due_date_label}}</strong></p>
					<p class="f-right">{{$receipt_details->due_date ?? ''}}</p>
				</div>
			@endif

			@if(!empty($receipt_details->sales_person_label))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->sales_person_label}}</strong></p>
				
					<p class="f-right">{{$receipt_details->sales_person}}</p>
				</div>
			@endif
			@if(!empty($receipt_details->commission_agent_label))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->commission_agent_label}}</strong></p>
				
					<p class="f-right">{{$receipt_details->commission_agent}}</p>
				</div>
			@endif

			@if(!empty($receipt_details->brand_label) || !empty($receipt_details->repair_brand))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->brand_label}}</strong></p>
				
					<p class="f-right">{{$receipt_details->repair_brand}}</p>
				</div>
			@endif

			@if(!empty($receipt_details->device_label) || !empty($receipt_details->repair_device))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->device_label}}</strong></p>
				
					<p class="f-right">{{$receipt_details->repair_device}}</p>
				</div>
			@endif
			
			@if(!empty($receipt_details->model_no_label) || !empty($receipt_details->repair_model_no))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->model_no_label}}</strong></p>
				
					<p class="f-right">{{$receipt_details->repair_model_no}}</p>
				</div>
			@endif
			
			@if(!empty($receipt_details->serial_no_label) || !empty($receipt_details->repair_serial_no))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->serial_no_label}}</strong></p>
				
					<p class="f-right">{{$receipt_details->repair_serial_no}}</p>
				</div>
			@endif

			@if(!empty($receipt_details->repair_status_label) || !empty($receipt_details->repair_status))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{!! $receipt_details->repair_status_label !!}
					</strong></p>
					<p class="f-right">
						{{$receipt_details->repair_status}}
					</p>
				</div>
        	@endif

        	@if(!empty($receipt_details->repair_warranty_label) || !empty($receipt_details->repair_warranty))
	        	<div class="textbox-info">
	        		<p class="f-left"><strong>
	        			{!! $receipt_details->repair_warranty_label !!}
	        		</strong></p>
	        		<p class="f-right">
	        			{{$receipt_details->repair_warranty}}
	        		</p>
	        	</div>
        	@endif

        	<!-- Waiter info -->
			@if(!empty($receipt_details->service_staff_label) || !empty($receipt_details->service_staff))
	        	<div class="textbox-info">
	        		<p class="f-left"><strong>
	        			{!! $receipt_details->service_staff_label !!}
	        		</strong></p>
	        		<p class="f-right">
	        			{{$receipt_details->service_staff}}
					</p>
	        	</div>
	        @endif

	        @if(!empty($receipt_details->table_label) || !empty($receipt_details->table))
	        	<div class="textbox-info">
	        		<p class="f-left"><strong>
	        			@if(!empty($receipt_details->table_label))
							<b>{!! $receipt_details->table_label !!}</b>
						@endif
	        		</strong></p>
	        		<p class="f-right">
	        			{{$receipt_details->table}}
	        		</p>
	        	</div>
	        @endif

			@if (!empty($receipt_details->sell_custom_field_1_value))
				<div class="textbox-info">
					<p class="f-left"><strong>{!! $receipt_details->sell_custom_field_1_label !!}</strong></p>
					<p class="f-right">
						{{$receipt_details->sell_custom_field_1_value}}
					</p>
				</div>
			@endif
			@if (!empty($receipt_details->sell_custom_field_2_value))
				<div class="textbox-info">
					<p class="f-left"><strong>{!! $receipt_details->sell_custom_field_2_label !!}</strong></p>
					<p class="f-right">
						{{$receipt_details->sell_custom_field_2_value}}
					</p>
				</div>
			@endif
			@if (!empty($receipt_details->sell_custom_field_3_value))
				<div class="textbox-info">
					<p class="f-left"><strong>{!! $receipt_details->sell_custom_field_3_label !!}</strong></p>
					<p class="f-right">
						{{$receipt_details->sell_custom_field_3_value}}
					</p>
				</div>
			@endif
			@if (!empty($receipt_details->sell_custom_field_4_value))
				<div class="textbox-info">
					<p class="f-left"><strong>{!! $receipt_details->sell_custom_field_4_label !!}</strong></p>
					<p class="f-right">
						{{$receipt_details->sell_custom_field_4_value}}
					</p>
				</div>
			@endif

	        <!-- customer info -->
	        <div class="textbox-info">
	        	<p style="vertical-align: top;"><strong>
	        		{{$receipt_details->customer_label ?? ''}}
	        	</strong></p>

	        	<p>
	        		@if(!empty($receipt_details->customer_info))
	        			<div class="bw">
						{!! $receipt_details->customer_info !!}
						</div>
					@endif
	        	</p>
	        </div>
			
			@if(!empty($receipt_details->client_id_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{{ $receipt_details->client_id_label }}
					</strong></p>
					<p class="f-right">
						{{ $receipt_details->client_id }}
					</p>
				</div>
			@endif
			
			@if(!empty($receipt_details->customer_tax_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{{ $receipt_details->customer_tax_label }}
					</strong></p>
					<p class="f-right">
						{{ $receipt_details->customer_tax_number }}
					</p>
				</div>
			@endif

			@if(!empty($receipt_details->customer_custom_fields))
				<div class="textbox-info">
					<p class="centered">
						{!! $receipt_details->customer_custom_fields !!}
					</p>
				</div>
			@endif
			
			@if(!empty($receipt_details->customer_rp_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{{ $receipt_details->customer_rp_label }}
					</strong></p>
					<p class="f-right">
						{{ $receipt_details->customer_total_rp }}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->shipping_custom_field_1_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{!!$receipt_details->shipping_custom_field_1_label!!} 
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->shipping_custom_field_1_value ?? ''!!}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->shipping_custom_field_2_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{!!$receipt_details->shipping_custom_field_2_label!!} 
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->shipping_custom_field_2_value ?? ''!!}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->shipping_custom_field_3_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{!!$receipt_details->shipping_custom_field_3_label!!} 
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->shipping_custom_field_3_value ?? ''!!}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->shipping_custom_field_4_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{!!$receipt_details->shipping_custom_field_4_label!!} 
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->shipping_custom_field_4_value ?? ''!!}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->shipping_custom_field_5_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{!!$receipt_details->shipping_custom_field_5_label!!} 
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->shipping_custom_field_5_value ?? ''!!}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->sale_orders_invoice_no))
				<div class="textbox-info">
					<p class="f-left"><strong>
						@lang('restaurant.order_no')
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->sale_orders_invoice_no ?? ''!!}
					</p>
				</div>
			@endif

			@if(!empty($receipt_details->sale_orders_invoice_date))
				<div class="textbox-info">
					<p class="f-left"><strong>
						@lang('lang_v1.order_dates')
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->sale_orders_invoice_date ?? ''!!}
					</p>
				</div>
			@endif
            <table style="margin-top: 10px !important" class="border-bottom width-100 table-f-12 mb-10">
                <thead class="border-bottom">
                    <tr>
                        <th style="width:5%; padding: 3px 2px; vertical-align: bottom;">#</th>
                        <th style="width:95%; padding: 3px 2px; text-align: left; vertical-align: bottom;" colspan="@if(empty($receipt_details->hide_price))@if(!empty($receipt_details->item_discount_label))4@else3@endif@else1@endif">
                        	{{$receipt_details->table_product_label}}
                        </th>
                    </tr>
                    @if(empty($receipt_details->hide_price))
                    <tr class="sub-header">
                        <th style="padding: 2px;"></th>
                        <th style="width:25%; padding: 2px; text-align: left; font-size: 9px;">{{$receipt_details->table_qty_label}}</th>
                        <th style="width:23%; padding: 2px; text-align: right; font-size: 9px;">{{$receipt_details->table_unit_price_label}}</th>
                        @if(!empty($receipt_details->item_discount_label))
                        <th style="width:23%; padding: 2px; text-align: right; font-size: 9px;">{{$receipt_details->item_discount_label}}</th>
                        @endif
                        <th style="width:24%; padding: 2px; text-align: right; font-size: 9px;">{{$receipt_details->table_subtotal_label}}</th>
                    </tr>
                    @endif
                </thead>
                <tbody>
                	@forelse($receipt_details->lines as $line)
	                    {{-- Row 1: Item Name --}}
	                    <tr class="item-row">
	                        <td style="vertical-align:top; width:5%; padding: 4px 2px 0px 2px;">{{$loop->iteration}}</td>
	                        <td style="vertical-align:top; width:95%; padding: 4px 2px 0px 2px;" colspan="@if(empty($receipt_details->hide_price))@if(!empty($receipt_details->item_discount_label))4@else3@endif@else1@endif">
	                        	<strong>{{$line['name']}} {{$line['product_variation']}} {{$line['variation']}}</strong>
	                        	@if(!empty($line['sub_sku']))<br><span class="f-8">SKU: {{$line['sub_sku']}}</span>@endif
	                        	@if(!empty($line['brand']))<span class="f-8">, {{$line['brand']}}</span>@endif
	                        	@if(!empty($line['product_description']))
	                            	<br><span class="f-8">{!!$line['product_description']!!}</span>
	                            @endif
	                        	@if(!empty($line['sell_line_note']))
	                        	<br><span class="f-8"><em>{!!$line['sell_line_note']!!}</em></span>
	                        	@endif
	                        	@if(!empty($line['lot_number']))<br><span class="f-8">{{$line['lot_number_label']}}: {{$line['lot_number']}}</span>@endif
	                        	@if(!empty($line['warranty_name']))<br><span class="f-8">{{$line['warranty_name']}}@if(!empty($line['warranty_exp_date'])) - {{@format_date($line['warranty_exp_date'])}}@endif</span>@endif
	                        </td>
	                    </tr>
	                    
	                    {{-- Row 2: Qty, Unit Price, Discount, Total --}}
	                    @if(empty($receipt_details->hide_price))
	                    <tr style="border-bottom: 1px dotted #ccc;">
	                        <td style="padding: 0px 2px 4px 2px;"></td>
	                        <td style="width:25%; padding: 0px 2px 4px 2px; text-align: left;">
	                        	<span class="f-8">{{$line['units']}}</span> {{$line['quantity']}}
	                        	@if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
	                        	<br><span class="f-8">{{$line['quantity']}} x {{$line['base_unit_multiplier']}} = {{$line['orig_quantity']}} {{$line['base_unit_name']}}</span>
	                        	@endif
	                        </td>
	                        <td style="width:23%; padding: 0px 2px 4px 2px; text-align: right;">{{$line['unit_price_before_discount']}}</td>
	                        @if(!empty($receipt_details->item_discount_label))
							<td style="width:23%; padding: 0px 2px 4px 2px; text-align: right; vertical-align: top;">
								@if(!empty($line['line_discount']) && $line['line_discount'] != '0.00')
									{!! $line['line_discount'] !!}
								@else
									-
								@endif
							</td>
							@endif
	                        <td style="width:24%; padding: 0px 2px 4px 2px; text-align: right; vertical-align: top;"><strong>{{$line['line_total']}}</strong></td>
	                    </tr>
	                    @endif
	                    @if(!empty($line['modifiers']))
							@foreach($line['modifiers'] as $modifier)
								<tr>
									<td>&nbsp;</td>
									<td colspan="@if(empty($receipt_details->hide_price))@if(!empty($receipt_details->item_discount_label))5@elseif(!empty($receipt_details->discounted_unit_price_label))4@else3@endif@else1@endif">
			                            {{$modifier['name']}} {{$modifier['variation']}}
			                            @if(!empty($modifier['sub_sku'])), {{$modifier['sub_sku']}} @endif @if(!empty($modifier['cat_code'])), {{$modifier['cat_code']}}@endif
			                            @if(!empty($modifier['sell_line_note']))({!!$modifier['sell_line_note']!!}) @endif
			                        </td>
								</tr>
								@if(empty($receipt_details->hide_price))
								<tr style="font-size:0.9em;">
									<td></td>
									<td>{{$modifier['units']}} {{$modifier['quantity']}}</td>
									<td class="text-right">{{$modifier['unit_price_inc_tax']}}</td>
									@if(!empty($receipt_details->discounted_unit_price_label))
										<td class="text-right">{{$modifier['unit_price_exc_tax']}}</td>
									@endif
									@if(!empty($receipt_details->item_discount_label))
										<td class="text-right">0.00</td>
									@endif
									<td class="text-right">{{$modifier['line_total']}}</td>
								</tr>
								@endif
							@endforeach
						@endif
                    @endforeach
                </tbody>
            </table>
			@if(!empty($receipt_details->total_quantity_label))
				<div class="flex-box">
					<p class="left text-right">
						{!! $receipt_details->total_quantity_label !!}
					</p>
					<p class="width-50 text-right">
						{{$receipt_details->total_quantity}}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->total_items_label))
				<div class="flex-box">
					<p class="left text-right">
						{!! $receipt_details->total_items_label !!}
					</p>
					<p class="width-50 text-right">
						{{$receipt_details->total_items}}
					</p>
				</div>
			@endif
			@if(empty($receipt_details->hide_price))
                <div class="border-top" style="margin-top: 8px; padding-top: 5px;">
	                <div class="flex-box" style="padding: 3px 0;">
	                    <p class="left text-right sub-headings" style="font-weight: bold;">
	                    	{!! $receipt_details->subtotal_label !!}
	                    </p>
	                    <p class="width-50 text-right sub-headings" style="font-weight: bold;">
	                    	{{$receipt_details->subtotal}}
	                    </p>
	                </div>
                </div>

                <!-- Shipping Charges -->
				@if(!empty($receipt_details->shipping_charges))
					<div class="flex-box" style="padding: 2px 0;">
						<p class="left text-right">
							{!! $receipt_details->shipping_charges_label !!}
						</p>
						<p class="width-50 text-right">
							{{$receipt_details->shipping_charges}}
						</p>
					</div>
				@endif

				@if(!empty($receipt_details->packing_charge))
					<div class="flex-box" style="padding: 2px 0;">
						<p class="left text-right">
							{!! $receipt_details->packing_charge_label !!}
						</p>
						<p class="width-50 text-right">
							{{$receipt_details->packing_charge}}
						</p>
					</div>
				@endif

			<!-- Line-level discounts -->
			@if( !empty($receipt_details->total_line_discount) )
				<div class="flex-box" style="padding: 2px 0;">
					<p class="width-50 text-right">
						{!! $receipt_details->line_discount_label !!}
					</p>
					<p class="width-50 text-right" style="color: #d9534f;">
						(-) {{$receipt_details->total_line_discount}}
					</p>
				</div>
			@endif

			<!-- Order-level discount (from POS Discount field) -->
			@if( !empty($receipt_details->order_discount_unformatted) && $receipt_details->order_discount_unformatted != 0 )
				<div class="flex-box" style="padding: 2px 0;">
					<p class="width-50 text-right">
						{!! $receipt_details->order_discount_label !!}
					</p>
					<p class="width-50 text-right" style="color: #d9534f;">
						(-) {{$receipt_details->order_discount}}
					</p>
				</div>
			@endif

				@if( !empty($receipt_details->additional_expenses) )
					@foreach($receipt_details->additional_expenses as $key => $val)
						<div class="flex-box" style="padding: 2px 0;">
							<p class="width-50 text-right">
								{{$key}}:
							</p>
							<p class="width-50 text-right">
								(+) {{$val}}
							</p>
						</div>
					@endforeach
				@endif

				@if(!empty($receipt_details->reward_point_label) )
					<div class="flex-box" style="padding: 2px 0;">
						<p class="width-50 text-right">
							{!! $receipt_details->reward_point_label !!}
						</p>
						<p class="width-50 text-right">
							(-) {{$receipt_details->reward_point_amount}}
						</p>
					</div>
				@endif

				@if( !empty($receipt_details->tax) )
					<div class="flex-box" style="padding: 2px 0;">
						<p class="width-50 text-right">
							{!! $receipt_details->tax_label !!}
						</p>
						<p class="width-50 text-right">
							(+) {{$receipt_details->tax}}
						</p>
					</div>
				@endif

				@if( $receipt_details->round_off_amount > 0)
					<div class="flex-box" style="padding: 2px 0;">
						<p class="width-50 text-right">
							{!! $receipt_details->round_off_label !!} 
						</p>
						<p class="width-50 text-right">
							{{$receipt_details->round_off}}
						</p>
					</div>
				@endif

				<div class="flex-box border-top" style="padding: 5px 0; margin-top: 5px;">
					<p class="width-50 text-right sub-headings" style="font-size: 16px !important; font-weight: bold;">
						{!! $receipt_details->total_label !!}
					</p>
					<p class="width-50 text-right sub-headings" style="font-size: 16px !important; font-weight: bold;">
						{{$receipt_details->total}}
					</p>
				</div>
				@if(!empty($receipt_details->total_in_words))
				<p class="text-right mb-0" style="padding: 2px 0;">
					<small><em>({{$receipt_details->total_in_words}})</em></small>
				</p>
				@endif

				<!-- Payments Section -->
				@if(!empty($receipt_details->payments))
					<div class="border-top" style="margin-top: 8px; padding-top: 5px;">
						@foreach($receipt_details->payments as $payment)
							<div class="flex-box" style="padding: 2px 0;">
								<p class="width-50 text-right">{{$payment['method']}} <span class="f-8">({{$payment['date']}})</span></p>
								<p class="width-50 text-right">{{$payment['amount']}}</p>
							</div>
						@endforeach
					</div>
				@endif

				<!-- Total Paid-->
				@if(!empty($receipt_details->total_paid))
					<div class="flex-box" style="padding: 3px 0; font-weight: bold;">
						<p class="width-50 text-right">
							{!! $receipt_details->total_paid_label !!}
						</p>
						<p class="width-50 text-right">
							{{$receipt_details->total_paid}}
						</p>
					</div>
				@endif

				<!-- Total Due-->
				@if(!empty($receipt_details->total_due) && !empty($receipt_details->total_due_label))
					<div class="flex-box" style="padding: 3px 0; font-weight: bold; color: #d9534f;">
						<p class="width-50 text-right">
							{!! $receipt_details->total_due_label !!}
						</p>
						<p class="width-50 text-right">
							{{$receipt_details->total_due}}
						</p>
					</div>
				@endif

				@if(!empty($receipt_details->all_due))
					<div class="flex-box" style="padding: 3px 0;">
						<p class="width-50 text-right">
							{!! $receipt_details->all_bal_label !!}
						</p>
						<p class="width-50 text-right">
							{{$receipt_details->all_due}}
						</p>
					</div>
				@endif
			@endif
            <div class="border-bottom width-100">&nbsp;</div>
            @if(empty($receipt_details->hide_price) && !empty($receipt_details->tax_summary_label) )
	            <!-- tax -->
	            @if(!empty($receipt_details->taxes))
	            	<table class="border-bottom width-100 table-f-12">
	            		<tr>
	            			<th colspan="2" class="text-center">{{$receipt_details->tax_summary_label}}</th>
	            		</tr>
	            		@foreach($receipt_details->taxes as $key => $val)
	            			<tr>
	            				<td class="left">{{$key}}</td>
	            				<td class="right">{{$val}}</td>
	            			</tr>
	            		@endforeach
	            	</table>
	            @endif
            @endif

            @if(!empty($receipt_details->additional_notes))
	            <p class="centered">
	            	{!! nl2br($receipt_details->additional_notes) !!}
	            </p>
            @endif

            {{-- Barcode --}}
			@if($receipt_details->show_barcode)
				<br/>
				<img class="center-block" src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 2,30,array(39, 48, 54), true)}}">
			@endif

			@if($receipt_details->show_qr_code && !empty($receipt_details->qr_code_text))
				<img class="center-block mt-5" src="data:image/png;base64,{{DNS2D::getBarcodePNG($receipt_details->qr_code_text, 'QRCODE')}}">
			@endif
			
			@if(!empty($receipt_details->footer_text))
				<p class="centered">
					{!! $receipt_details->footer_text !!}
				</p>
			@endif
			
        </div>
        <!-- <button id="btnPrint" class="hidden-print">Print</button>
        <script src="script.js"></script> -->
    </body>
</html>

<style type="text/css">
* {
	margin: 0;
	padding: 0;
	box-sizing: border-box;
}

.f-8 {
	font-size: 8px !important;
	line-height: 1.2;
}

body {
	color: #000000;
	font-family: 'Times New Roman', Times, serif;
	margin: 0;
	padding: 5px;
}

@page {
	margin: 5px;
}

@media print {
	* {
	    	font-size: 15px;
    	font-family: 'Times New Roman';
    	word-break: break-all;
	}
	
	.f-8 {
		font-size: 8px !important;
	}
	
	.headings{
		font-size: 17px;
		font-weight: 700;
		text-transform: uppercase;
		white-space: nowrap;
	}

	.sub-headings{
		font-size: 15px !important;
		font-weight: 700 !important;
	}

	.border-top{
	    border-top: 1px solid #242424;
	    margin-top: 4px;
	    padding-top: 4px;
	}
	
	.border-bottom{
		border-bottom: 1px solid #242424;
	}

	.border-bottom-dotted{
		border-bottom: 1px dotted darkgray;
	}

	.centered {
	    text-align: center;
	    align-content: center;
	}

	.text-left {
		text-align: left !important;
	}

	.text-right {
		text-align: right !important;
	}

	.ticket {
	    width: calc(100% - 12px);
	    max-width: calc(100% - 12px);
		margin: 0 auto;
		padding: 0;
		box-sizing: border-box;
	}

	img {
	    max-width: inherit;
	    width: auto;
	}

    .hidden-print,
    .hidden-print * {
        display: none !important;
    }
}

.table-info {
	width: 100%;
}

.table-info tr:first-child td, 
.table-info tr:first-child th {
	padding-top: 8px;
}

.table-info th {
	text-align: left;
}

.table-info td {
	text-align: right;
}

.logo {
	float: left;
	width:35%;
	padding: 10px;
}

.text-with-image {
	float: left;
	width:65%;
}

.text-box {
	width: 100%;
	height: auto;
}

.ticket {
	width: calc(100% - 12px);
	max-width: calc(100% - 12px);
	margin: 0 auto;
	padding: 0;
	box-sizing: border-box;
}

.textbox-info {
	clear: both;
	line-height: 1.4;
}

.textbox-info p {
	margin-bottom: 2px;
}

.flex-box {
	display: flex;
	width: 100%;
	justify-content: space-between;
	align-items: flex-start;
}

.flex-box p {
	width: 50%;
	margin-bottom: 2px;
	white-space: normal;
}

.flex-box .left {
	text-align: left;
}

.flex-box .width-50 {
	width: 50%;
}

.table-f-12 {
	width: 100%;
	border-collapse: collapse;
}

.table-f-12 th {
	font-size: 11px;
	font-weight: bold;
	padding: 4px 2px;
}

.table-f-12 thead {
	border-bottom: 2px solid #242424;
}

.table-f-12 .sub-header th {
	font-size: 9px !important;
	font-weight: normal;
	padding: 2px;
	border-bottom: none;
}

.table-f-12 td {
	font-size: 11px;
	word-break: break-word;
	padding: 3px 2px;
	vertical-align: top;
}

.f-left {
	float: left;
	width: 50%;
	text-align: left;
}

.f-right {
	float: right;
	width: 50%;
	text-align: right;
}

.width-100 {
	width: 100%;
	clear: both;
}

.mb-10 {
	margin-bottom: 10px;
}

.bw {
	word-break: break-word;
}
</style>