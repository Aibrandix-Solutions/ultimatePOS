<div class="modal fade" id="configure_search_modal" tabindex="-1" role="dialog" 
	aria-labelledby="gridSystemModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="border-radius:12px; box-shadow:0 8px 32px rgba(22,17,96,0.25);">
			<div class="modal-header" style="background:linear-gradient(135deg,#161160 0%, #2a2480 100%); color:#ffffff; border-radius:12px 12px 0 0;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#ffffff; opacity:0.9;"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" style="color:#ffffff; font-weight:600;">
					@lang('lang_v1.search_products_by')
				</h4>
			</div>
			<div class="modal-body" style="background:#ffffff;">
				<div class="row">
					<div class="col-md-6">
						<div class="checkbox">
							<label>
				              	{!! Form::checkbox('search_fields[]', 'name', true, ['class' => 'input-icheck search_fields']) !!} @lang('product.product_name')
				            </label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="checkbox">
							<label>
				              	{!! Form::checkbox('search_fields[]', 'sku', true, ['class' => 'input-icheck search_fields']) !!} @lang('product.sku')
				            </label>
						</div>
					</div>
					@if(request()->session()->get('business.enable_lot_number') == 1)
					<div class="col-md-6">
						<div class="checkbox">
							<label>
				              	{!! Form::checkbox('search_fields[]', 'lot', true, ['class' => 'input-icheck search_fields']) !!} @lang('lang_v1.lot_number')
				            </label>
						</div>
					</div>
					@endif

					@php
						$custom_labels = json_decode(session('business.custom_labels'), true);
						$product_custom_field1 = !empty($custom_labels['product']['custom_field_1']) ? $custom_labels['product']['custom_field_1'] : __('lang_v1.product_custom_field1');
						$product_custom_field2 = !empty($custom_labels['product']['custom_field_2']) ? $custom_labels['product']['custom_field_2'] : __('lang_v1.product_custom_field2');
						$product_custom_field3 = !empty($custom_labels['product']['custom_field_3']) ? $custom_labels['product']['custom_field_3'] : __('lang_v1.product_custom_field3');
						$product_custom_field4 = !empty($custom_labels['product']['custom_field_4']) ? $custom_labels['product']['custom_field_4'] : __('lang_v1.product_custom_field4');
			        @endphp
			        <div class="clearfix"></div>
			        <div class="col-md-6">
						<div class="checkbox">
							<label>
				              	{!! Form::checkbox('search_fields[]', 'product_custom_field1', false, ['class' => 'input-icheck search_fields']) !!} {{$product_custom_field1}}
				            </label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="checkbox">
							<label>
				              	{!! Form::checkbox('search_fields[]', 'product_custom_field2', false, ['class' => 'input-icheck search_fields']) !!} {{$product_custom_field2}}
				            </label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="checkbox">
							<label>
				              	{!! Form::checkbox('search_fields[]', 'product_custom_field3', false, ['class' => 'input-icheck search_fields']) !!} {{$product_custom_field3}}
				            </label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="checkbox">
							<label>
				              	{!! Form::checkbox('search_fields[]', 'product_custom_field4', false, ['class' => 'input-icheck search_fields']) !!} {{$product_custom_field4}}
				            </label>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer" style="background:#f8f9fa; border-radius:0 0 12px 12px; border-top:1px solid rgba(22,17,96,0.1);">
			    <button type="button" class="btn btn-default" data-dismiss="modal" style="background:#e8ebed; color:#374151; border:1px solid #e5e7eb; padding:8px 20px; border-radius:6px; font-weight:500;">@lang('messages.close')</button>
			</div>
		</div>
	</div>
</div>