@foreach($featured_products as $variation)
	<div class="col-md-3 col-xs-4 product_list no-print">
		<div class="product_box hover:tw-shadow-lg hover:tw-animate-pulse" data-toggle="tooltip" data-placement="bottom" data-variation_id="{{$variation->id}}" @if($variation->product->enable_stock) data-enable_stock="1" data-orig_qty_available="{{$variation->qty_available}}" data-stock_unit="{{$variation->product->unit}}" @else data-enable_stock="0" @endif title="{{$variation->full_name}}">

		<div class="image-container" 
			style="background-image: url(@if(count($variation->media) > 0)
						{{$variation->media->first()->display_url}}
					@elseif(!empty($variation->product->image_url))
						{{$variation->product->image_url}}
					@else
						{{asset('/img/default.png')}}
					@endif), url({{asset('/img/default.png')}});
			background-repeat: no-repeat; background-position: center;
			background-size: contain;">
			
		</div>

		<div class="text_div">
			<small class="text text-muted">{{$variation->product->name}} 
			@if($variation->product->type == 'variable')
				- {{$variation->name}}
			@endif
			</small>

			<small class="text-muted">
				({{$variation->sub_sku}})
			</small>
			<br>
			<small class="text-muted" style="font-size: 60%;">
				@if($variation->product->enable_stock)
					<span class="js_pos_product_stock_qty">{{ @num_format($variation->qty_available) }}</span> <span class="js_pos_product_stock_unit">{{$variation->product->unit}}</span> @lang('lang_v1.in_stock')
				@else
					--
				@endif
			</small>
		</div>
			
		</div>
	</div>
@endforeach