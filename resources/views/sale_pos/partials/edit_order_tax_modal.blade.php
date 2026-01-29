<!-- Edit Order tax Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="posEditOrderTaxModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="border-radius:12px; box-shadow:0 8px 32px rgba(22,17,96,0.25);">
			<div class="modal-header" style="background:linear-gradient(135deg,#161160 0%, #2a2480 100%); color:#ffffff; border-radius:12px 12px 0 0;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#ffffff; opacity:0.9;"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" style="color:#ffffff; font-weight:600;">@lang('sale.edit_order_tax')</h4>
			</div>
			<div class="modal-body" style="background:#ffffff;">
				<div class="row">
					<div class="col-md-6">
				        <div class="form-group">
				            {!! Form::label('order_tax_modal', __('sale.order_tax') . ':*' ) !!}
				            <div class="input-group">
				                <span class="input-group-addon">
				                    <i class="fa fa-info"></i>
				                </span>
				                {!! Form::select('order_tax_modal', $taxes['tax_rates'], $selected_tax, ['placeholder' => __('messages.please_select'), 'class' => 'form-control'], $taxes['attributes']) !!}
				            </div>
				        </div>
				    </div>
				</div>
			</div>
			<div class="modal-footer" style="background:#f8f9fa; border-radius:0 0 12px 12px; border-top:1px solid rgba(22,17,96,0.1);">
				<button type="button" class="btn btn-default" data-dismiss="modal" style="background:#e8ebed; color:#374151; border:1px solid #e5e7eb; padding:8px 20px; border-radius:6px; font-weight:500;">@lang('messages.close')</button>
				<button type="button" class="btn btn-primary" id="posEditOrderTaxModalUpdate" style="background: linear-gradient(135deg, #161160 0%, #2a2480 100%); color: #ffffff; border: none; padding: 8px 20px; border-radius: 6px; font-weight: 600;">@lang('messages.update')</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->