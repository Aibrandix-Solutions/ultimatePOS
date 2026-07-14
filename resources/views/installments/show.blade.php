@extends('layouts.app')
@section('title', __('lang_v1.installment_plan'))

@section('content')

<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.installment_plan')</h1>
</section>

<section class="content">
    @if(!empty($customer_credit) && (float) $customer_credit > 0)
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="fa fa-info-circle"></i>
            {!! __('lang_v1.installment_customer_credit', ['amount' => '<span class="display_currency" data-currency_symbol="true">' . $customer_credit . '</span>']) !!}
        </div>
    @endif

    @if(!empty($show_adjust_alert))
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i> @lang('lang_v1.update_installment_plan')</h4>
            @if((float) ($sell_return_total ?? 0) > 0)
                @lang('lang_v1.installment_plan_adjust_after_return')
            @else
                @lang('lang_v1.installment_plan_adjust_alert')
            @endif
        </div>
    @endif

    @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.installment_plan')])
        <div class="row">
            <div class="col-md-4">
                <strong>@lang('sale.invoice_no'):</strong>
                <div class="help-block">{{ $transaction->invoice_no ?? '' }}</div>
            </div>
            <div class="col-md-4">
                <strong>@lang('contact.customer'):</strong>
                <div class="help-block">{{ $plan->contact->name ?? '' }}</div>
            </div>
            <div class="col-md-4">
                <strong>@lang('lang_v1.status'):</strong>
                <div class="help-block">{{ $plan->status }}</div>
            </div>

            <div class="col-md-4">
                <strong>@lang('sale.total_payable'):</strong>
                <div class="help-block"><span class="display_currency" data-currency_symbol="true">{{ $transaction->final_total ?? 0 }}</span></div>
            </div>
            @if((float) ($sell_return_total ?? 0) > 0)
                <div class="col-md-4">
                    <strong>@lang('lang_v1.total_returns'):</strong>
                    <div class="help-block"><span class="display_currency" data-currency_symbol="true">{{ $sell_return_total }}</span></div>
                </div>
                <div class="col-md-4">
                    <strong>@lang('lang_v1.balance_after_returns'):</strong>
                    <div class="help-block"><span class="display_currency" data-currency_symbol="true">{{ $effective_total ?? 0 }}</span></div>
                </div>
            @endif
            <div class="col-md-4">
                <strong>@lang('lang_v1.total_paying'):</strong>
                <div class="help-block"><span class="display_currency" data-currency_symbol="true">{{ $paid_amount ?? 0 }}</span></div>
            </div>
            <div class="col-md-4">
                <strong>@lang('lang_v1.balance'):</strong>
                <div class="help-block"><span class="display_currency" data-currency_symbol="true">{{ $balance_due ?? 0 }}</span></div>
            </div>
            @if($plan->status === 'active')
                <div class="col-md-4">
                    <strong>@lang('lang_v1.remaining_installment_balance'):</strong>
                    <div class="help-block">
                        <span class="display_currency" data-currency_symbol="true" id="remaining_installment_balance">{{ $remaining_installment_balance ?? 0 }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <strong>@lang('lang_v1.pending_installments_total'):</strong>
                    <div class="help-block">
                        <span class="display_currency" data-currency_symbol="true" id="pending_installments_total">{{ $pending_lines_total ?? 0 }}</span>
                    </div>
                </div>
            @endif
        </div>

        <hr>

        @if($can_edit && $plan->status === 'active' && $plan->lines->where('status', '!=', 'paid')->count() > 0)
            {!! Form::open(['url' => action([\App\Http\Controllers\InstallmentPlanController::class, 'updateLines'], [$plan->id]), 'method' => 'put', 'id' => 'installment_lines_form']) !!}
        @endif

        <table class="table table-bordered table-striped" id="installment_lines_table">
            <thead>
                <tr>
                    <th>@lang('lang_v1.installment')</th>
                    <th>@lang('lang_v1.due_date')</th>
                    <th>@lang('sale.amount')</th>
                    @if($can_edit && $plan->status === 'active')
                        <th>@lang('lang_v1.suggested_amount')</th>
                    @endif
                    <th>@lang('lang_v1.paid')</th>
                    <th>@lang('lang_v1.installment_remaining')</th>
                    <th>@lang('lang_v1.status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plan->lines as $line)
                    @php
                        $line_remaining = max(0, round((float) $line->amount - (float) $line->paid_amount, 4));
                        $is_partial = $line->status !== 'paid'
                            && (float) $line->paid_amount > 0.0001
                            && $line_remaining > 0.0001;
                        $is_next_due = ! empty($next_pending_installment)
                            && (int) $line->sequence === (int) $next_pending_installment['sequence'];
                    @endphp
                    <tr @if($is_next_due) class="info" @endif>
                        <td>{{ $line->sequence }}</td>
                        <td>{{ \Carbon\Carbon::parse($line->due_date)->format(session('business.date_format')) }}</td>
                        <td>
                            @if($can_edit && $plan->status === 'active' && $line->status !== 'paid')
                                <input type="text"
                                    name="line_amounts[{{ $line->id }}]"
                                    class="form-control input-sm input_number installment_line_amount"
                                    value="{{ @num_format($line->amount) }}"
                                    data-initial-amount="{{ $line->amount }}">
                            @else
                                <span class="display_currency" data-currency_symbol="true">{{ $line->amount }}</span>
                            @endif
                        </td>
                        @if($can_edit && $plan->status === 'active')
                            <td>
                                @if($line->status !== 'paid' && isset($suggested_amounts[$line->id]))
                                    <span class="display_currency suggested_amount_value" data-currency_symbol="true" data-amount="{{ $suggested_amounts[$line->id] }}">{{ $suggested_amounts[$line->id] }}</span>
                                @else
                                    —
                                @endif
                            </td>
                        @endif
                        <td><span class="display_currency" data-currency_symbol="true">{{ $line->paid_amount }}</span></td>
                        <td>
                            @if($line->status === 'paid')
                                <span class="text-muted">—</span>
                            @else
                                <span class="display_currency" data-currency_symbol="true">{{ $line_remaining }}</span>
                            @endif
                        </td>
                        <td>
                            @if($is_partial)
                                <span class="label label-warning">@lang('lang_v1.installment_status_partial')</span>
                            @elseif($line->status === 'paid')
                                <span class="label label-success">@lang('lang_v1.paid')</span>
                            @else
                                <span class="label label-default">{{ $line->status }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="tw-flex tw-items-center tw-gap-2 tw-flex-wrap">
            @if($can_edit && $plan->status === 'active' && $plan->lines->where('status', '!=', 'paid')->count() > 0)
                <button type="button" class="btn btn-info" id="apply_suggested_amounts_btn">
                    <i class="fas fa-magic"></i> @lang('lang_v1.apply_suggested_amounts')
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> @lang('lang_v1.update_installment_amounts')
                </button>
            @endif
            @if(!empty($transaction) && (auth()->user()->can('sell.payments') || auth()->user()->can('sell.create') || auth()->user()->can('direct_sell.access')))
                <a href="{{ action([\App\Http\Controllers\TransactionPaymentController::class, 'addPayment'], [$transaction->id]) }}" class="btn btn-primary add_payment_modal">
                    <i class="fas fa-money-bill-alt"></i> @lang('purchase.add_payment')
                </a>
            @endif
            <a href="{{ action([\App\Http\Controllers\InstallmentPlanController::class, 'index']) }}" class="btn btn-default">@lang('messages.go_back')</a>
        </div>

        @if($can_edit && $plan->status === 'active' && $plan->lines->where('status', '!=', 'paid')->count() > 0)
            {!! Form::close() !!}
        @endif
    @endcomponent
</section>

<div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@stop

@section('javascript')
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#apply_suggested_amounts_btn').on('click', function() {
                $('#installment_lines_table .suggested_amount_value').each(function() {
                    var amount = $(this).data('amount');
                    var $row = $(this).closest('tr');
                    $row.find('.installment_line_amount').val(__number_f(amount));
                });
            });
        });
    </script>
@endsection
