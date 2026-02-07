@forelse($products as $product)
	<div class="col-md-3 col-xs-4 product_list no-print">
		<div class="product_box hover:tw-shadow-lg hover:tw-animate-pulse" data-variation_id="{{$product->id}}" @if($product->enable_stock) data-enable_stock="1" data-orig_qty_available="{{$product->qty_available}}" data-stock_unit="{{$product->unit}}" @else data-enable_stock="0" @endif title="{{$product->name}} @if($product->type == 'variable')- {{$product->variation}} @endif {{ '(' . $product->sub_sku . ')'}} @if(!empty($show_prices)) @lang('lang_v1.default') - @format_currency($product->selling_price) @foreach($product->group_prices as $group_price) @if(array_key_exists($group_price->price_group_id, $allowed_group_prices)) {{$allowed_group_prices[$group_price->price_group_id]}} - @format_currency($group_price->price_inc_tax) @endif @endforeach @endif">

		<div class="image-container" 
			style="background-image: url(@if(count($product->media) > 0)
						{{$product->media->first()->display_url}}
					@elseif(!empty($product->product_image))
						{{asset('/uploads/img/' . rawurlencode($product->product_image))}}
					@else
						{{asset('/img/default.png')}}
					@endif), url({{asset('/img/default.png')}});
			background-repeat: no-repeat; background-position: center;
			background-size: contain;">
			
		</div>

		<div class="text_div">
			<small class="text text-muted">{{$product->name}} 
			@if($product->type == 'variable')
				- {{$product->variation}}
			@endif
			</small>

			<small class="text-muted">
				({{$product->sub_sku}})
			</small><br>
			<small class="text-muted" style="font-size: 60%;">
				@if($product->enable_stock)
				<span class="js_pos_product_stock_qty">{{ @num_format($product->qty_available) }}</span> <span class="js_pos_product_stock_unit">{{$product->unit}}</span> @lang('lang_v1.in_stock')
				@else
					--
				@endif
			</small>
		</div>
			
		</div>
	</div>
@empty
	<input type="hidden" id="no_products_found">
	<div class="col-md-12">
		<h4 class="text-center">
			@lang('lang_v1.no_products_to_display')
		</h4>
	</div>
@endforelse