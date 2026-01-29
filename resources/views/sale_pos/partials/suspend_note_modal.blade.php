<div class="modal fade" tabindex="-1" role="dialog" id="confirmSuspendModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="border-radius: 12px; box-shadow: 0 8px 32px rgba(22,17,96,0.25);">
			<div class="modal-header" style="background: linear-gradient(135deg, #161160 0%, #2a2480 100%); color: #ffffff; border-radius: 12px 12px 0 0;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.9;"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" style="color: #ffffff; font-weight: 600;">@lang('lang_v1.suspend_sale')</h4>
			</div>
			<div class="modal-body" style="background: #ffffff;">
				<div class="row">
					<div class="col-xs-12">
				        <div class="form-group">
				            {!! Form::label('additional_notes', __('lang_v1.suspend_note') . ':' ) !!}
				            {!! Form::textarea('additional_notes', !empty($transaction->additional_notes) ? $transaction->additional_notes : null, ['class' => 'form-control','rows' => '4']) !!}
				            {!! Form::hidden('is_suspend', 0, ['id' => 'is_suspend']) !!}
				        </div>
				    </div>
				</div>
			</div>
			<div class="modal-footer" style="background: #f8f9fa; border-radius: 0 0 12px 12px; border-top: 1px solid rgba(22,17,96,0.1);">
				<button type="button" class="btn btn-default" data-dismiss="modal" style="background: #e8ebed; color: #374151; border: 1px solid #e5e7eb; padding: 8px 20px; border-radius: 6px; font-weight: 500;">@lang('messages.close')</button>
				<button type="button" class="btn btn-primary" id="pos-suspend" style="background: linear-gradient(135deg, #161160 0%, #2a2480 100%); color: #ffffff; border: none; padding: 8px 20px; border-radius: 6px; font-weight: 600;">@lang('messages.save')</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->