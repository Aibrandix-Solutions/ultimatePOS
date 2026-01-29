<!-- Edit discount Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="recurringInvoiceModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="border-radius: 12px; box-shadow: 0 8px 32px rgba(22,17,96,0.25);">
			<div class="modal-header" style="background: linear-gradient(135deg, #161160 0%, #2a2480 100%); color: #ffffff; border-radius: 12px 12px 0 0;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.9;"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" style="color: #ffffff; font-weight: 600;">@lang('lang_v1.subscribe') @if(!empty($transaction->subscription_no)) - {{$transaction->subscription_no}} @endif</h4>
			</div>
			<div class="modal-body" style="background: #ffffff;">
				<div class="row">
					<div class="col-md-6">
				        <div class="form-group">
				        	{!! Form::label('recur_interval', __('lang_v1.subscription_interval') . ':*' ) !!}
				        	<div class="input-group">
				               {!! Form::number('recur_interval', !empty($transaction->recur_interval) ? $transaction->recur_interval : null, ['class' => 'form-control', 'required', 'style' => 'width: 50%;']) !!}
				               
				                {!! Form::select('recur_interval_type', ['days' => __('lang_v1.days'), 'months' => __('lang_v1.months'), 'years' => __('lang_v1.years')], !empty($transaction->recur_interval_type) ? $transaction->recur_interval_type : 'days', ['class' => 'form-control', 'required', 'style' => 'width: 50%;', 'id' => 'recur_interval_type']) !!}
				                
				            </div>
				        </div>
				    </div>

				    <div class="col-md-6">
				        <div class="form-group">
				        	{!! Form::label('recur_repetitions', __('lang_v1.no_of_repetitions') . ':' ) !!}
				        	{!! Form::number('recur_repetitions', !empty($transaction->recur_repetitions) ? $transaction->recur_repetitions : null, ['class' => 'form-control']) !!}
					        <p class="help-block">@lang('lang_v1.recur_repetition_help')</p>
				        </div>
				    </div>
				    @php
				    	$repetitions = [];
				    	for ($i=1; $i <= 30; $i++) { 
				    		$repetitions[$i] = str_ordinal($i);
				        }
				    @endphp
				    <div class="subscription_repeat_on_div col-md-6 @if(empty($transaction->recur_interval_type)) hide @elseif(!empty($transaction->recur_interval_type) && $transaction->recur_interval_type != 'months') hide @endif">
				        <div class="form-group">
				        	{!! Form::label('subscription_repeat_on', __('lang_v1.repeat_on') . ':' ) !!}
				        	{!! Form::select('subscription_repeat_on', $repetitions, !empty($transaction->subscription_repeat_on) ? $transaction->subscription_repeat_on : null, ['class' => 'form-control', 'placeholder' => __('messages.please_select')]) !!}
				        </div>
				    </div>

				</div>
			</div>
			<div class="modal-footer" style="background: #f8f9fa; border-radius: 0 0 12px 12px; border-top: 1px solid rgba(22,17,96,0.1);">
			    <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #e8ebed; color: #374151; border: 1px solid #e5e7eb; padding: 8px 20px; border-radius: 6px; font-weight: 500;">@lang('messages.close')</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->