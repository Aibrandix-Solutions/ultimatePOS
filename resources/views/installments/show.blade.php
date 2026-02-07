@extends('layouts.app')
@section('title', __('lang_v1.installment_plan'))

@section('content')

<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.installment_plan')</h1>
</section>

<section class="content">
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
            <div class="col-md-4">
                <strong>@lang('lang_v1.total_paying'):</strong>
                <div class="help-block"><span class="display_currency" data-currency_symbol="true">{{ $paid_amount ?? 0 }}</span></div>
            </div>
            <div class="col-md-4">
                <strong>@lang('lang_v1.balance'):</strong>
                <div class="help-block"><span class="display_currency" data-currency_symbol="true">{{ $balance_due ?? 0 }}</span></div>
            </div>
        </div>

        <hr>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>@lang('lang_v1.installment')</th>
                    <th>@lang('lang_v1.due_date')</th>
                    <th>@lang('sale.amount')</th>
                    <th>@lang('lang_v1.paid')</th>
                    <th>@lang('lang_v1.status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plan->lines as $line)
                    <tr>
                        <td>{{ $line->sequence }}</td>
                        <td>{{ \Carbon\Carbon::parse($line->due_date)->format(session('business.date_format')) }}</td>
                        <td><span class="display_currency" data-currency_symbol="true">{{ $line->amount }}</span></td>
                        <td><span class="display_currency" data-currency_symbol="true">{{ $line->paid_amount }}</span></td>
                        <td>{{ $line->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="tw-flex tw-items-center tw-gap-2">
            @if(!empty($transaction) && (auth()->user()->can('sell.payments') || auth()->user()->can('sell.create') || auth()->user()->can('direct_sell.access')))
                <a href="{{ action([\App\Http\Controllers\TransactionPaymentController::class, 'addPayment'], [$transaction->id]) }}" class="btn btn-primary add_payment_modal">
                    <i class="fas fa-money-bill-alt"></i> @lang('purchase.add_payment')
                </a>
            @endif
            <a href="{{ action([\App\Http\Controllers\InstallmentPlanController::class, 'index']) }}" class="btn btn-default">@lang('messages.go_back')</a>
        </div>
    @endcomponent
</section>

<div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@stop

@section('javascript')
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
