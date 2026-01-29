<div class="payment_details_div @if( $payment_line->method !== 'card' ) {{ 'hide' }} @endif" data-type="card" >
	{{-- <div class="col-md-12">
		<div class="alert alert-info" role="alert">
			@lang('lang_v1.card_processing_disabled')
		</div>
	</div> --}}
</div>
<div class="payment_details_div @if( $payment_line->method !== 'cheque' ) {{ 'hide' }} @endif" data-type="cheque" >
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label("cheque_number",__('lang_v1.cheque_no')) !!}
			{!! Form::text("cheque_number", $payment_line->cheque_number, ['class' => 'form-control', 'placeholder' => __('lang_v1.cheque_no')]) !!}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{!! Form::label("cheque_issue_date", __('lang_v1.cheque_issue_date')) !!}
			{!! Form::text("cheque_issue_date", !empty($payment_line->cheque_issue_date) ? @format_datetime($payment_line->cheque_issue_date) : null, ['class' => 'form-control datetimepicker', 'placeholder' => __('lang_v1.cheque_issue_date'), 'readonly']) !!}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{!! Form::label("cheque_passing_date", __('lang_v1.cheque_passing_date')) !!}
			{!! Form::text("cheque_passing_date", !empty($payment_line->cheque_passing_date) ? @format_datetime($payment_line->cheque_passing_date) : null, ['class' => 'form-control datetimepicker', 'placeholder' => __('lang_v1.cheque_passing_date'), 'readonly']) !!}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{!! Form::label("cheque_bank_name", __('lang_v1.cheque_bank_name')) !!}
			{!! Form::text("cheque_bank_name", $payment_line->cheque_bank_name, ['class' => 'form-control', 'placeholder' => __('lang_v1.cheque_bank_name')]) !!}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{!! Form::label("cheque_status", __('lang_v1.cheque_status')) !!}
			{!! Form::select("cheque_status", ['pending' => __('lang_v1.pending'), 'cleared' => __('lang_v1.cleared'), 'bounced' => __('lang_v1.bounced')], !empty($payment_line->cheque_status) ? $payment_line->cheque_status : 'pending', ['class' => 'form-control']) !!}
		</div>
	</div>
</div>
<div class="payment_details_div @if( $payment_line->method !== 'bank_transfer' ) {{ 'hide' }} @endif" data-type="bank_transfer" >
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label("bank_account_number",__('lang_v1.bank_account_number')) !!}
			{!! Form::text( "bank_account_number", $payment_line->bank_account_number, ['class' => 'form-control', 'placeholder' => __('lang_v1.bank_account_number')]) !!}
		</div>
	</div>
</div>
<div class="payment_details_div @if( $payment_line->method !== 'custom_pay_1' ) {{ 'hide' }} @endif" data-type="custom_pay_1" >
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label("transaction_no_1", __('lang_v1.transaction_no')) !!}
			{!! Form::text("transaction_no_1", $payment_line->transaction_no, ['class' => 'form-control', 'placeholder' => __('lang_v1.transaction_no')]) !!}
		</div>
	</div>
</div>
<div class="payment_details_div @if( $payment_line->method !== 'custom_pay_2' ) {{ 'hide' }} @endif" data-type="custom_pay_2" >
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label("transaction_no_2", __('lang_v1.transaction_no')) !!}
			{!! Form::text("transaction_no_2", $payment_line->transaction_no, ['class' => 'form-control', 'placeholder' => __('lang_v1.transaction_no')]) !!}
		</div>
	</div>
</div>
<div class="payment_details_div @if( $payment_line->method !== 'custom_pay_3' ) {{ 'hide' }} @endif" data-type="custom_pay_3" >
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label("transaction_no_3", __('lang_v1.transaction_no')) !!}
			{!! Form::text("transaction_no_3", $payment_line->transaction_no, ['class' => 'form-control', 'placeholder' => __('lang_v1.transaction_no')]) !!}
		</div>
	</div>
</div>