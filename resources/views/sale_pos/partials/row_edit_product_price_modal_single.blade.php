<div class="modal-dialog" role="document">
	<div class="modal-content" style="border-radius: 12px; box-shadow: 0 8px 32px rgba(22,17,96,0.3);">
		<div class="modal-header" style="background: linear-gradient(135deg, #161160 0%, #2a2480 100%); color: white; border-radius: 12px 12px 0 0;">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="row_edit_product_price_modal_label" style="color: white; font-weight: 600;">Product Price Edit</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-xs-12" style="margin-bottom: 10px;">
					<div id="modal_product_image_wrapper" style="display:none; text-align:center;">
						<img id="modal_product_image" src="" alt="Product Image" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('/img/default.png') }}';" style="height: 90px; width: 90px; object-fit: cover; border-radius: 12px; border: 2px solid rgba(22,17,96,0.10); box-shadow: 0 4px 12px rgba(22,17,96,0.12);" />
					</div>
				</div>
				<div class="form-group col-xs-12">
					<label>@lang('sale.unit_price')</label>
					<input type="text" id="modal_unit_price" class="form-control pos_unit_price input_number mousetrap" value="">
				</div>
				<div class="form-group col-xs-12 col-sm-6">
					<label>@lang('sale.discount_type')</label>
					<select id="modal_discount_type" class="form-control row_discount_type">
						<option value="fixed">@lang('lang_v1.fixed')</option>
						<option value="percentage">@lang('lang_v1.percentage')</option>
					</select>
				</div>
				<div class="form-group col-xs-12 col-sm-6">
					<label>@lang('sale.discount_amount')</label>
					<input type="text" id="modal_discount_amount" class="form-control input_number row_discount_amount" value="">
				</div>
				<div class="form-group col-xs-12" id="modal_warranty_wrapper" style="display:none;">
					<label>@lang('lang_v1.warranty')</label>
					<select id="modal_warranty_id" class="form-control"></select>
				</div>
				<div class="form-group col-xs-12">
					<label>@lang('lang_v1.description')</label>
					<textarea id="modal_sell_line_note" class="form-control" rows="3" placeholder="@lang('lang_v1.sell_line_description_help')"></textarea>
				</div>
			</div>
		</div>
		<div class="modal-footer" style="background: #f8f9fa; border-radius: 0 0 12px 12px; border-top: 1px solid rgba(22,17,96,0.1);">
			<button type="button" class="btn btn-default" data-dismiss="modal" style="background: #e9ecef; color: #374151; border: 1px solid #e5e7eb; padding: 8px 20px; border-radius: 6px; font-weight: 500;">@lang('messages.close')</button>
			<button type="button" id="modal_save_changes" class="btn btn-primary" style="background: linear-gradient(135deg, #161160 0%, #2a2480 100%); color: #ffffff; border: none; padding: 8px 20px; border-radius: 6px; font-weight: 600;">@lang('messages.save')</button>
		</div>
	</div>
</div>
