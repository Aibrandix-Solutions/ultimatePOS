<div class="modal fade no-print" id="recent_transactions_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" style="border-radius: 12px; box-shadow: 0 8px 32px rgba(22,17,96,0.25);">
			<div class="modal-header" style="background: linear-gradient(135deg, #161160 0%, #2a2480 100%); color: #ffffff; border-radius: 12px 12px 0 0;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.9;"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" style="color: #ffffff; font-weight: 600;">@lang('lang_v1.recent_transactions')</h4>
			</div>
			<div class="modal-body" style="background: #ffffff;">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_final" data-toggle="tab" aria-expanded="true"><b><i class="fa fa-check"></i> @lang('sale.final')</b></a></li>

						<li class=""><a href="#tab_quotation" data-toggle="tab" aria-expanded="false"><b><i class="fa fa-terminal"></i> @lang('lang_v1.quotation')</b></a></li>
						
						<li class=""><a href="#tab_draft" data-toggle="tab" aria-expanded="false"><b><i class="fa fa-terminal"></i> @lang('sale.draft')</b></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_final">
						</div>
						<div class="tab-pane" id="tab_quotation">
						</div>
						<div class="tab-pane" id="tab_draft">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer" style="background: #f8f9fa; border-radius: 0 0 12px 12px; border-top: 1px solid rgba(22,17,96,0.1);">
			    <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #e8ebed; color: #374151; border: 1px solid #e5e7eb; padding: 8px 20px; border-radius: 6px; font-weight: 500;">@lang('messages.close')</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>