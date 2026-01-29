<div class="payment_details_div @if( $payment_line['method'] !== 'card' ) {{ 'hide' }} @endif" data-type="card" >
	{{-- <div class="col-md-12">
		<div class="alert alert-info" role="alert">
			@lang('lang_v1.card_processing_disabled')
		</div>
	</div> --}}
</div>
<div class="payment_details_div @if( $payment_line['method'] !== 'cheque' ) {{ 'hide' }} @endif" data-type="cheque" >
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label("cheque_number_$row_index",__('lang_v1.cheque_no')) !!}
			{!! Form::text("payment[$row_index][cheque_number]", $payment_line['cheque_number'], ['class' => 'form-control', 'placeholder' => __('lang_v1.cheque_no'), 'id' => "cheque_number_$row_index"]) !!}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{!! Form::label("cheque_issue_date_$row_index", __('lang_v1.cheque_issue_date')) !!}
			{!! Form::text("payment[$row_index][cheque_issue_date]", !empty($payment_line['cheque_issue_date']) ? @format_datetime($payment_line['cheque_issue_date']) : null, ['class' => 'form-control datetimepicker', 'placeholder' => __('lang_v1.cheque_issue_date'), 'id' => "cheque_issue_date_$row_index", 'readonly']) !!}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{!! Form::label("cheque_passing_date_$row_index", __('lang_v1.cheque_passing_date')) !!}
			{!! Form::text("payment[$row_index][cheque_passing_date]", !empty($payment_line['cheque_passing_date']) ? @format_datetime($payment_line['cheque_passing_date']) : null, ['class' => 'form-control datetimepicker', 'placeholder' => __('lang_v1.cheque_passing_date'), 'id' => "cheque_passing_date_$row_index", 'readonly']) !!}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{!! Form::label("cheque_bank_name_$row_index", __('lang_v1.cheque_bank_name')) !!}
			{!! Form::text("payment[$row_index][cheque_bank_name]", $payment_line['cheque_bank_name'] ?? null, ['class' => 'form-control', 'placeholder' => __('lang_v1.cheque_bank_name'), 'id' => "cheque_bank_name_$row_index"]) !!}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{!! Form::label("cheque_status_$row_index", __('lang_v1.cheque_status')) !!}
			{!! Form::select("payment[$row_index][cheque_status]", ['pending' => __('lang_v1.pending'), 'cleared' => __('lang_v1.cleared'), 'bounced' => __('lang_v1.bounced')], $payment_line['cheque_status'] ?? 'pending', ['class' => 'form-control', 'id' => "cheque_status_$row_index"]) !!}
		</div>
	</div>
</div>
<div class="payment_details_div @if( $payment_line['method'] !== 'bank_transfer' ) {{ 'hide' }} @endif" data-type="bank_transfer" >
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label("bank_account_number_$row_index",__('lang_v1.bank_account_number')) !!}
			{!! Form::text( "payment[$row_index][bank_account_number]", $payment_line['bank_account_number'], ['class' => 'form-control', 'placeholder' => __('lang_v1.bank_account_number'), 'id' => "bank_account_number_$row_index"]) !!}
		</div>
	</div>
</div>

@for ($i = 1; $i < 8; $i++)
<div class="payment_details_div @if( $payment_line['method'] !== 'custom_pay_' . $i ) {{ 'hide' }} @endif" data-type="custom_pay_{{$i}}" >
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label("transaction_no_{$i}_{$row_index}", __('lang_v1.transaction_no')) !!}
			{!! Form::text("payment[$row_index][transaction_no_{$i}]", $payment_line['transaction_no'], ['class' => 'form-control', 'placeholder' => __('lang_v1.transaction_no'), 'id' => "transaction_no_{$i}_{$row_index}"]) !!}
		</div>
	</div>
</div>
@endfor