<div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius:12px; overflow:hidden; box-shadow: 0 10px 30px rgba(22,17,96,0.25);">
        {!! Form::open(['url' => action([\App\Http\Controllers\ExpenseController::class, 'store']), 'method' => 'post', 'id' => 'add_expense_modal_form', 'files' => true ]) !!}
        <div class="modal-header" style="background:linear-gradient(135deg,#161160 0%, #2a2480 100%); color:#ffffff; border-radius:12px 12px 0 0;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#ffffff; opacity:0.9;"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="color:#ffffff; font-weight:600;">@lang( 'expense.add_expense' )</h4>
        </div>
        <div class="modal-body" style="background:#ffffff;">
            <div class="row">
                @if(count($business_locations) == 1)
                    @php 
                        $default_location = current(array_keys($business_locations->toArray())) 
                    @endphp
                @else
                    @php $default_location = request()->input('location_id'); @endphp
                @endif
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('expense_location_id', __('purchase.business_location').':*') !!}
                        {!! Form::select('location_id', $business_locations, $default_location, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required', 'id' => 'expense_location_id'], $bl_attributes) !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('expense_category_id', __('expense.expense_category').':') !!}
                        {!! Form::select('expense_category_id', $expense_categories, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]) !!}
                    </div>
                </div>
                <div class="col-md-6">
					<div class="form-group">
			            {!! Form::label('expense_sub_category_id', __('product.sub_category') . ':') !!}
			              {!! Form::select('expense_sub_category_id', [],  null, ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2']) !!}
			          </div>
				</div>
                <div class="clearfix"></div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('expense_ref_no', __('purchase.ref_no').':') !!}
                        {!! Form::text('ref_no', null, ['class' => 'form-control', 'id' => 'expense_ref_no']) !!}
                        <p class="help-block">
                            @lang('lang_v1.leave_empty_to_autogenerate')
                        </p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('expense_transaction_date', __('messages.date') . ':*') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            {!! Form::text('transaction_date', @format_datetime('now'), ['class' => 'form-control', 'readonly', 'required', 'id' => 'expense_transaction_date']) !!}
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('expense_for', __('expense.expense_for').':') !!} @show_tooltip(__('tooltip.expense_for'))
                        {!! Form::select('expense_for', $users, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]) !!}
                    </div>
                </div>                
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('expense_tax_id', __('product.applicable_tax') . ':' ) !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-info"></i>
                            </span>
                            {!! Form::select('tax_id', $taxes['tax_rates'], null, ['class' => 'form-control', 'id'=>'expense_tax_id'], $taxes['attributes']) !!}

                            <input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" 
                            value="0">
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('expense_final_total', __('sale.total_amount') . ':*') !!}
                        {!! Form::text('final_total', null, ['class' => 'form-control input_number', 'placeholder' => __('sale.total_amount'), 'required', 'id' => 'expense_final_total']) !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('expense_additional_notes', __('expense.expense_note') . ':') !!}
                                {!! Form::textarea('additional_notes', null, ['class' => 'form-control', 'rows' => 3, 'id' => 'expense_additional_notes']) !!}
                    </div>
                </div>
            </div>

            <div class="payment_row">
                <h4>@lang('purchase.add_payment'):</h4>
                @include('sale_pos.partials.payment_row_form', ['row_index' => 0, 'show_date' => true])
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right">
                            <strong>@lang('purchase.payment_due'):</strong>
                            <span id="expense_payment_due">{{@num_format(0)}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="background:#f8f9fa; border-top:1px solid rgba(22,17,96,0.1);">
            <button type="button" class="btn btn-default" data-dismiss="modal" style="background:#e8ebed; color:#374151; border:1px solid #e5e7eb; padding:8px 20px; border-radius:6px; font-weight:500;">@lang( 'messages.close' )</button>
            <button type="submit" class="btn btn-primary" style="background:linear-gradient(135deg,#161160 0%, #2a2480 100%); color:#ffffff; border:none; padding:8px 20px; border-radius:6px; font-weight:600;">@lang( 'messages.save' )</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
