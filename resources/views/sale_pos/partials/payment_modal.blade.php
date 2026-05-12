<div class="modal fade" tabindex="-1" role="dialog" id="modal_payment">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 12px; box-shadow: 0 8px 32px rgba(22,17,96,0.25);">
            <div class="modal-header" style="background: linear-gradient(135deg, #161160 0%, #2a2480 100%); color: #ffffff; border-radius: 12px 12px 0 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.9;"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color: #ffffff; font-weight: 600;">@lang('lang_v1.payment')</h4>
            </div>
            <div class="modal-body" style="background: #ffffff;">
                <div class="row">
                    <div class="col-md-12 mb-12">
                        <strong>@lang('lang_v1.advance_balance'):</strong> <span id="advance_balance_text"></span>
                        {!! Form::hidden('advance_balance', null, [
                            'id' => 'advance_balance',
                            'data-error-msg' => __('lang_v1.required_advance_balance_not_available'),
                        ]) !!}
                    </div>
                    <div class="col-md-12 mb-12 hide" id="advance_deduct_checkbox_wrapper" style="margin-top:4px;">
                        <label style="font-weight: 600; cursor: pointer; color: #161160;">
                            {!! Form::checkbox('deduct_from_advance', 1, false, ['id' => 'deduct_from_advance']) !!}
                            Use Advance Balance to pay this bill
                            <span id="advance_auto_deduct_text" style="color:#d97706; font-weight:700; margin-left:8px;"></span>
                        </label>
                    </div>

                    <div class="col-md-12 mb-12 apply_to_old_dues_wrapper" style="margin-top:6px;">
                        {!! Form::hidden('apply_payment_to_old_dues', 0) !!}
                        <label style="font-weight: 600; cursor: pointer;">
                            {!! Form::checkbox('apply_payment_to_old_dues', 1, false, ['id' => 'apply_payment_to_old_dues']) !!}
                            Keep this payment on the current invoice
                        </label>
                        <small class="help-block" style="margin: 2px 0 0;">
                            If unchecked, payment reduces previous dues first (oldest first) and the current invoice will remain due.
                        </small>
                    </div>

                    <div class="col-md-12 mb-12 hide" id="pos_receipt_due_preview" style="margin-top:8px;">
                        <div style="border: 1px dashed rgba(22,17,96,0.25); border-radius: 8px; background: #f8fafc; padding: 10px 12px;">
                            <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                                <span style="font-weight:600; color:#111827;">Previous Due:</span>
                                <span id="pos_receipt_previous_due" style="font-weight:700; color:#111827;">0</span>
                            </div>
                            <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; margin-top:6px;">
                                <span style="font-weight:700; color:#161160;">Amount Payable:</span>
                                <span id="pos_receipt_amount_payable" style="font-weight:700; color:#161160;">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div id="payment_rows_div">
                                @php
                                    $pos_settings = !empty(session()->get('business.pos_settings')) ? json_decode(session()->get('business.pos_settings'), true) : [];
                                    $show_in_pos = '';


                                    if (isset($pos_settings['enable_cash_denomination_on']) && ($pos_settings['enable_cash_denomination_on'] == 'all_screens' || $pos_settings['enable_cash_denomination_on'] == 'pos_screen')) {
                                        $show_in_pos = true;
                                    }
                                    
                                @endphp
                                @foreach ($payment_lines as $payment_line)
                                    @if ($payment_line['is_return'] == 1)
                                        @php
                                            $change_return = $payment_line;
                                        @endphp

                                        @continue
                                    @endif

                                    @include('sale_pos.partials.payment_row', [
                                        'removable' => !$loop->first,
                                        'row_index' => $loop->index,
                                        'payment_line' => $payment_line,
                                        'show_denomination' => true,
                                        'show_in_pos' => $show_in_pos,
                                    ])
                                @endforeach
                            </div>
                            <input type="hidden" id="payment_row_index" value="{{ count($payment_lines) }}">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white tw-dw-btn-sm tw-w-full"
                                    id="add-payment-row">@lang('sale.add_payment_row')</button>
                            </div>
                        </div>
                        <br>
                        {{-- <div class="row @if ($change_return['amount'] == 0) hide @endif payment_row"
                            id="change_return_payment_data">
                            <div class="col-md-12">
                                <div class="box box-solid payment_row bg-lightgray">
                                    <div class="box-body">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('change_return_method', __('lang_v1.change_return_payment_method') . ':*') !!}
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fas fa-money-bill-alt"></i>
                                                    </span>
                                                    @php
                                                        $_payment_method = empty($change_return['method']) && array_key_exists('cash', $payment_types) ? 'cash' : $change_return['method'];

                                                        $_payment_types = $payment_types;
                                                        if (isset($_payment_types['advance'])) {
                                                            unset($_payment_types['advance']);
                                                        }
                                                    @endphp
                                                    {!! Form::select('payment[change_return][method]', $_payment_types, $_payment_method, [
                                                        'class' => 'form-control col-md-12 payment_types_dropdown',
                                                        'id' => 'change_return_method',
                                                        'style' => 'width:100%;',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @if (!empty($accounts))
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    {!! Form::label('change_return_account', __('lang_v1.change_return_payment_account') . ':') !!}
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fas fa-money-bill-alt"></i>
                                                        </span>
                                                        {!! Form::select(
                                                            'payment[change_return][account_id]',
                                                            $accounts,
                                                            !empty($change_return['account_id']) ? $change_return['account_id'] : '',
                                                            ['class' => 'form-control select2', 'id' => 'change_return_account', 'style' => 'width:100%;'],
                                                        ) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="clearfix"></div>
                                        @include('sale_pos.partials.payment_type_details', [
                                            'payment_line' => $change_return,
                                            'row_index' => 'change_return',
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('sale_note', __('sale.sell_note') . ':') !!}
                                    {!! Form::textarea('sale_note', !empty($transaction) ? $transaction->additional_notes : null, [
                                        'class' => 'form-control',
                                        'rows' => 3,
                                        'placeholder' => __('sale.sell_note'),
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('staff_note', __('sale.staff_note') . ':') !!}
                                    {!! Form::textarea('staff_note', !empty($transaction) ? $transaction->staff_note : null, [
                                        'class' => 'form-control',
                                        'rows' => 3,
                                        'placeholder' => __('sale.staff_note'),
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="box box-solid" style="border-radius:10px; border: 1px solid rgba(22,17,96,0.1); background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);">
                            <div class="box-body" style="padding: 14px 14px;">
                                <div class="col-md-12">
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                                        <span style="font-weight:600; color:#111827;">@lang('lang_v1.total_items'):</span>
                                        <span class="text-bold total_quantity" style="color:#111827;font-size:1.5rem">0</span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <hr style="border-color: rgba(22,17,96,0.1);">
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                                        <span style="font-weight:700; color:#161160;">@lang('sale.total_payable'):</span>
                                        <span class="text-bold total_payable_span" style="color:#161160;font-size:1.2rem">0</span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <hr style="border-color: rgba(22,17,96,0.1);">
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                                        <span style="font-weight:600; color:#111827;">@lang('lang_v1.total_paying'):</span>
                                        <span class="text-bold total_paying" style="color:#111827;font-size:1.2rem">0</span>
                                    </div>
                                    <input type="hidden" id="total_paying_input">
                                </div>

                                <div class="col-md-12 change_return_row">
                                    <hr style="border-color: rgba(22,17,96,0.1);">
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                                        <span style="font-weight:600; color:#111827;">@lang('lang_v1.change_return'):</span>
                                        <span class="text-bold change_return_span" style="color:#111827;font-size:1.5rem">0</span>
                                    </div>
                                    {!! Form::hidden('change_return', $change_return['amount'], [
                                        'class' => 'form-control change_return input_number',
                                        'required',
                                        'id' => 'change_return',
                                    ]) !!}
                                    <!-- <span class="text-bold total_quantity">0</span> -->
                                    @if (!empty($change_return['id']))
                                        <input type="hidden" name="change_return_id"
                                            value="{{ $change_return['id'] }}">
                                    @endif
                                </div>

                                <div class="col-md-12 balance_due_row">
                                    <hr style="border-color: rgba(22,17,96,0.1);">
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                                        <span style="font-weight:700; color:#dc2626;">@lang('lang_v1.balance'):</span>
                                        <span class="text-bold balance_due" style="color:#dc2626;font-size:1.5rem">0</span>
                                    </div>
                                    <input type="hidden" id="in_balance_due" value=0>
                                </div>



                                <div class="col-md-12 hide" id="pos_due_date_wrapper">
                                    <hr style="border-color: rgba(22,17,96,0.1);">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        {!! Form::label('due_date_dropdown', __('lang_v1.due_date') . ':') !!}
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            {!! Form::select('due_date_dropdown', ['0' => __('home.today'), '30' => '30 Days', '60' => '60 Days', '90' => '90 Days', 'custom' => 'Customize'], '60', ['class' => 'form-control', 'id' => 'pos_due_date_dropdown']) !!}
                                        </div>
                                        
                                        <div id="custom_due_days_wrapper" class="input-group hide" style="margin-top: 5px;">
                                            <span class="input-group-addon">
                                                <i class="fa fa-pencil-alt"></i>
                                            </span>
                                            {!! Form::number('custom_due_days', null, ['class' => 'form-control', 'id' => 'custom_due_days', 'placeholder' => 'Enter exact days']) !!}
                                        </div>

                                        <!-- Hidden original input to keep backend submission working -->
                                        {!! Form::text('due_date', null, ['class' => 'form-control pos_due_date hide', 'id' => 'pos_due_date', 'autocomplete' => 'off', 'style' => 'display:none;']) !!}

                                        <small class="help-block" style="margin: 4px 0 0;">
                                            @lang('lang_v1.due_date')
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-12" style="margin-top:10px;">
                                    <hr style="border-color: rgba(22,17,96,0.1);">
                                    <label style="font-weight: 600; cursor: pointer;">
                                        {!! Form::checkbox('enable_installment_plan', 1, false, ['id' => 'enable_installment_plan']) !!}
                                        @lang('lang_v1.installment_plan')
                                    </label>
                                    <small class="help-block" style="margin: 4px 0 0;">
                                        @lang('lang_v1.installment_plan_help')
                                    </small>
                                </div>

                                <div class="col-md-12 hide" id="installment_plan_fields_wrapper">
                                    <div class="row" style="margin-top:6px;">
                                        <div class="col-md-4">
                                            <div class="form-group" style="margin-bottom: 8px;">
                                                {!! Form::label('installment_count', __('lang_v1.installment_count') . ':*') !!}
                                                {!! Form::number('installment_count', 3, ['class' => 'form-control', 'id' => 'installment_count', 'min' => 1]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="margin-bottom: 8px;">
                                                {!! Form::label('installment_interval', __('lang_v1.installment_interval') . ':*') !!}
                                                {!! Form::number('installment_interval', 1, ['class' => 'form-control', 'id' => 'installment_interval', 'min' => 1]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="margin-bottom: 8px;">
                                                {!! Form::label('installment_interval_type', __('lang_v1.installment_interval_type') . ':*') !!}
                                                {!! Form::select('installment_interval_type', ['days' => __('lang_v1.days'), 'weeks' => __('lang_v1.weeks'), 'months' => __('lang_v1.months')], 'months', ['class' => 'form-control', 'id' => 'installment_interval_type']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group" style="margin-bottom: 8px;">
                                                {!! Form::label('installment_first_due_date', __('lang_v1.first_installment_due_date') . ':*') !!}
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    {!! Form::text('installment_first_due_date', null, ['class' => 'form-control', 'id' => 'installment_first_due_date', 'autocomplete' => 'off']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="help-block" style="margin: 0;">
                                        @lang('lang_v1.installment_first_due_help')
                                    </small>
                                </div>



                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8f9fa; border-radius: 0 0 12px 12px; border-top: 1px solid rgba(22,17,96,0.1);">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #b0b0b0; color: #374151; border: 1px solid #e5e7eb; padding: 8px 20px; border-radius: 6px; font-weight: 500;">@lang('messages.close')</button>
                <button type="submit" class="btn btn-primary" id="pos-save" style="background: linear-gradient(135deg, #28b77b 0%, #20a16b 100%); border: 1px solid rgba(40,183,123,0.2); color: #ffffff; border: none; padding: 8px 20px; border-radius: 6px; font-weight: 600;">@lang('sale.complete_payment')</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Used for express checkout card transaction -->
<div class="modal fade" tabindex="-1" role="dialog" id="card_details_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px; box-shadow: 0 8px 32px rgba(22,17,96,0.25);">
            <div class="modal-header" style="background: linear-gradient(135deg, #161160 0%, #2a2480 100%); color: #ffffff; border-radius: 12px 12px 0 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.9;"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color: #ffffff; font-weight: 600;">@lang('lang_v1.card_transaction_details')</h4>
            </div>
            <div class="modal-body" style="background: #ffffff;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info" role="alert">
                            @lang('lang_v1.card_processing_disabled')
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8f9fa; border-radius: 0 0 12px 12px; border-top: 1px solid rgba(22,17,96,0.1);">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #e8ebed; color: #374151; border: 1px solid #e5e7eb; padding: 8px 20px; border-radius: 6px; font-weight: 500;">@lang('messages.close')</button>
            </div>
        </div>
    </div>
</div>
