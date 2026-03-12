<!-- business information here -->

<div class="row" style="color: #000000 !important;">
	<!-- Logo -->
	@if(empty($receipt_details->letter_head))
		@if(!empty($receipt_details->logo))
			<img style="max-height: 120px; width: auto;" src="{{$receipt_details->logo}}" class="img img-responsive center-block">
		@endif

		<!-- Header text -->
		@if(!empty($receipt_details->header_text))
			<div class="col-xs-12">
				{!! $receipt_details->header_text !!}
			</div>
		@endif

		<!-- business information here -->
		<div class="col-xs-12 text-center">
			<h2 class="text-center">
				<!-- Shop & Location Name  -->
				@if(!empty($receipt_details->display_name))
					{{$receipt_details->display_name}}
				@endif
			</h2>

			<!-- Address -->
			<p>
			@if(!empty($receipt_details->address))
					<small class="text-center">
					{!! $receipt_details->address !!}
					</small>
			@endif
			@if(!empty($receipt_details->contact))
				<br/>{!! $receipt_details->contact !!}
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
			</p>
			<p>
			@if(!empty($receipt_details->sub_heading_line1))
				{{ $receipt_details->sub_heading_line1 }}
			@endif
			@if(!empty($receipt_details->sub_heading_line2))
				<br>{{ $receipt_details->sub_heading_line2 }}
			@endif
			@if(!empty($receipt_details->sub_heading_line3))
				<br>{{ $receipt_details->sub_heading_line3 }}
			@endif
			@if(!empty($receipt_details->sub_heading_line4))
				<br>{{ $receipt_details->sub_heading_line4 }}
			@endif		
			@if(!empty($receipt_details->sub_heading_line5))
				<br>{{ $receipt_details->sub_heading_line5 }}
			@endif
			</p>
			<p>
			@if(!empty($receipt_details->tax_info1))
				<b>{{ $receipt_details->tax_label1 }}</b> {{ $receipt_details->tax_info1 }}
			@endif

			@if(!empty($receipt_details->tax_info2))
				<b>{{ $receipt_details->tax_label2 }}</b> {{ $receipt_details->tax_info2 }}
			@endif
			</p>
		@endif

		<!-- Title of receipt -->
		@if(!empty($receipt_details->invoice_heading))
			<h3 class="text-center">
				{!! $receipt_details->invoice_heading !!}
			</h3>
		@endif
	</div>
	@if(!empty($receipt_details->letter_head))
		<div class="col-xs-12 text-center">
			<img style="width: 100%;margin-bottom: 10px;" src="{{$receipt_details->letter_head}}">
		</div>
	@endif
<div class="col-xs-12 text-center">
	<!-- Invoice  number, Date  -->
	<p style="width: 100% !important" class="word-wrap">
		<span class="pull-left text-left word-wrap">
			@if(!empty($receipt_details->invoice_no_prefix))
				<b>{!! $receipt_details->invoice_no_prefix !!}</b>
			@endif
			{{$receipt_details->invoice_no}}

			@if(!empty($receipt_details->parent_invoice_no))
				<br/>
				<span class="pull-left text-left">
					<strong>Original Invoice:</strong>
					@if(!empty($receipt_details->parent_invoice_no_prefix))
						{!! $receipt_details->parent_invoice_no_prefix !!}
					@endif
					{{$receipt_details->parent_invoice_no}}
				</span>
			@endif

			<!-- Table information-->
	        @if(!empty($receipt_details->table_label) || !empty($receipt_details->table))
	        	<br/>
				<span class="pull-left text-left">
					@if(!empty($receipt_details->table_label))
						<b>{!! $receipt_details->table_label !!}</b>
					@endif
					{{$receipt_details->table}}
				</span>
	        @endif

			<!-- customer info -->
			@if(!empty($receipt_details->customer_info))
				<br/>
				<b>{{ $receipt_details->customer_label }}</b> <br> {!! $receipt_details->customer_info !!} <br>
			@endif
			@if(!empty($receipt_details->client_id_label))
				<br/>
				<b>{{ $receipt_details->client_id_label }}</b> {{ $receipt_details->client_id }}
			@endif
			@if(!empty($receipt_details->customer_tax_label))
				<br/>
				<b>{{ $receipt_details->customer_tax_label }}</b> {{ $receipt_details->customer_tax_number }}
			@endif
			@if(!empty($receipt_details->customer_custom_fields))
				<br/>{!! $receipt_details->customer_custom_fields !!}
			@endif
		</span>

		<span class="pull-right text-left">
			<b>{{$receipt_details->date_label}}</b> {{$receipt_details->invoice_date}}

			@if(!empty($receipt_details->due_date_label))
			<br><b>{{$receipt_details->due_date_label}}</b> {{$receipt_details->due_date ?? ''}}
			@endif
		</span>
	</p>
</div>
</div>

<div class="row" style="color: #000000 !important;">
	<div class="col-xs-12">
		<br/>
		@php
			$p_width = 40;
		@endphp
		<table class="table table-responsive table-slim" style="width: 100%;">
			<thead>
				<tr>
					<th width="{{$p_width}}%">{{$receipt_details->table_product_label}}</th>
					<th class="text-right" width="15%">Qty</th>
					<th class="text-right" width="20%">{{$receipt_details->table_unit_price_label}}</th>
					<th class="text-right" width="20%">{{$receipt_details->table_subtotal_label}}</th>
				</tr>
			</thead>
			<tbody>
				@forelse($receipt_details->lines as $line)
					<tr>
						<td>
							@if(!empty($line['image']))
								<img src="{{$line['image']}}" alt="Image" width="50" style="float: left; margin-right: 8px;">
							@endif
                            {{$line['name']}} @if(!empty($line['product_variation'])){{$line['product_variation']}}@endif {{$line['variation']}} 
                            @if(!empty($line['sub_sku'])), {{$line['sub_sku']}} @endif @if(!empty($line['brand'])), {{$line['brand']}} @endif @if(!empty($line['cat_code'])), {{$line['cat_code']}}@endif
                            @if(!empty($line['product_custom_fields'])), {{$line['product_custom_fields']}} @endif
                            @if(!empty($line['product_description']))
                            	<small>
                            		{!!$line['product_description']!!}
                            	</small>
                            @endif 
                            @if(!empty($line['sell_line_note']))
                            <br>
                            <small>
                            	{!!$line['sell_line_note']!!}
                            </small>
                            @endif 
                            @if(!empty($line['lot_number']))<br> {{$line['lot_number_label']}}:  {{$line['lot_number']}} @endif 
                            @if(!empty($line['product_expiry'])), {{$line['product_expiry_label']}}:  {{$line['product_expiry']}} @endif

                            @if(!empty($line['warranty_name'])) <br><small>{{$line['warranty_name']}} </small>@endif @if(!empty($line['warranty_exp_date'])) <small>- {{@format_date($line['warranty_exp_date'])}} </small>@endif
                            @if(!empty($line['warranty_description'])) <small> {{$line['warranty_description'] ?? ''}}</small>@endif

                            @if(!empty($receipt_details->show_base_unit_details) && !empty($line['quantity']) && !empty($line['base_unit_multiplier']) && $line['base_unit_multiplier'] !== 1)
                            <br><small>
                            	1 {{$line['units']}} = {{$line['base_unit_multiplier']}} {{$line['base_unit_name'] ?? ''}} <br>
                            	{{$line['base_unit_price'] ?? ''}} x {{$line['orig_quantity'] ?? ''}} = {{$line['line_total']}}
                            </small>
                            @endif
                        </td>
						<td class="text-right">
							{{$line['quantity']}} {{$line['units']}} 

							@if(!empty($receipt_details->show_base_unit_details) && !empty($line['quantity']) && !empty($line['base_unit_multiplier']) && $line['base_unit_multiplier'] !== 1)
                            <br><small>
                            	{{$line['quantity']}} x {{$line['base_unit_multiplier']}} = {{$line['orig_quantity'] ?? ''}} {{$line['base_unit_name'] ?? ''}}
                            </small>
                            @endif
						</td>
						<td class="text-right">{{$line['unit_price_exc_tax']}}</td>
						<td class="text-right">{{$line['line_total']}}</td>
					</tr>
				@empty
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
				@endforelse
			</tbody>
		</table>

		<div style="margin-top: 20px;">
			<table class="table table-slim" style="width: 100%; border-top: 2px solid #000;">
				<tbody>
					<tr>
						<th style="width:70%; text-align: right; padding: 5px;">
							{!! $receipt_details->subtotal_label !!}
						</th>
						<td class="text-right" style="width:30%; padding: 5px;">
							{{$receipt_details->subtotal}}
						</td>
					</tr>

					<!-- Discount -->
					@if( !empty($receipt_details->discount) )
						<tr>
							<th style="text-align: right; padding: 5px;">
								{!! $receipt_details->discount_label !!}
							</th>
							<td class="text-right" style="padding: 5px;">
								(-) {{$receipt_details->discount}}
							</td>
						</tr>
					@endif

					@if( !empty($receipt_details->total_line_discount) )
						<tr>
							<th style="text-align: right; padding: 5px;">
								{!! $receipt_details->line_discount_label !!}
							</th>
							<td class="text-right" style="padding: 5px;">
								(-) {{$receipt_details->total_line_discount}}
							</td>
						</tr>
					@endif

					<!-- Tax -->
					@if( !empty($receipt_details->tax) )
						<tr>
							<th style="text-align: right; padding: 5px;">
								{!! $receipt_details->tax_label !!}
							</th>
							<td class="text-right" style="padding: 5px;">
								(+) {{$receipt_details->tax}}
							</td>
						</tr>
					@endif

					@if( $receipt_details->round_off_amount > 0)
						<tr>
							<th style="text-align: right; padding: 5px;">
								{!! $receipt_details->round_off_label !!}
							</th>
							<td class="text-right" style="padding: 5px;">
								{{$receipt_details->round_off}}
							</td>
						</tr>
					@endif

					<!-- Total -->
					<tr style="border-top: 2px solid #000;">
						<th style="text-align: right; padding: 8px; font-weight: bold;">
							{!! $receipt_details->total_label !!}
						</th>
						<td class="text-right" style="padding: 8px; font-weight: bold;">
							{{$receipt_details->total}}
							@if(!empty($receipt_details->total_in_words))
								<br>
								<small>({{$receipt_details->total_in_words}})</small>
							@endif
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="row" style="color: #000000 !important;">
	<div class="col-xs-12">
		@if(!empty($receipt_details->payments))
			<table class="table table-slim">
				<tbody>
				@foreach($receipt_details->payments as $payment)
					<tr>
						<td>{{$payment['method']}}</td>
						<td class="text-right">{{$payment['amount']}}</td>
						<td class="text-right">{{$payment['date']}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		@endif

		<!-- Total Paid-->
		@if(!empty($receipt_details->total_paid))
			<p>
				<b>{!! $receipt_details->total_paid_label !!}:</b>
				<span class="pull-right">{{$receipt_details->total_paid}}</span>
			</p>
		@endif

		<!-- Total Due-->
		@if(!empty($receipt_details->total_due) && !empty($receipt_details->total_due_label))
			<p>
				<b>{!! $receipt_details->total_due_label !!}:</b>
				<span class="pull-right">{{$receipt_details->total_due}}</span>
			</p>
		@endif
	</div>

    <div class="border-bottom col-md-12">
	    @if(empty($receipt_details->hide_price) && !empty($receipt_details->tax_summary_label) )
	        <!-- tax -->
	        @if(!empty($receipt_details->taxes))
	        	<table class="table table-slim table-bordered">
	        		<tr>
	        			<th colspan="2" class="text-center">{{$receipt_details->tax_summary_label}}</th>
	        		</tr>
	        		@foreach($receipt_details->taxes as $key => $val)
	        			<tr>
	        				<td class="text-center"><b>{{$key}}</b></td>
	        				<td class="text-center">{{$val}}</td>
	        			</tr>
	        		@endforeach
	        	</table>
	        @endif
	    @endif
	</div>

	@if(!empty($receipt_details->additional_notes))
	    <div class="col-xs-12">
	    	<p>{!! nl2br($receipt_details->additional_notes) !!}</p>
	    </div>
    @endif
    
</div>
<div class="row" style="color: #000000 !important;">
	@if(!empty($receipt_details->footer_text))
	<div class="@if($receipt_details->show_barcode || $receipt_details->show_qr_code) col-xs-8 @else col-xs-12 @endif">
		{!! $receipt_details->footer_text !!}
	</div>
	@endif
	@if($receipt_details->show_barcode || $receipt_details->show_qr_code)
		<div class="@if(!empty($receipt_details->footer_text)) col-xs-4 @else col-xs-12 @endif text-center">
			@if($receipt_details->show_barcode)
				{{-- Barcode --}}
				<img class="center-block" src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 2,30,array(39, 48, 54), true)}}">
			@endif
			
			@if($receipt_details->show_qr_code && !empty($receipt_details->qr_code_text))
				<img class="center-block mt-5" src="data:image/png;base64,{{DNS2D::getBarcodePNG($receipt_details->qr_code_text, 'QRCODE', 3, 3, [39, 48, 54])}}">
			@endif
		</div>
	@endif
</div>