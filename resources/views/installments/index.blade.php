@extends('layouts.app')
@section('title', __('lang_v1.installment_plans'))

@section('content')

<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.installment_plans')</h1>
</section>

<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.installment_plans')])
        <table class="table table-bordered table-striped" id="installment_plans_table">
            <thead>
                <tr>
                    <th>@lang('sale.invoice_no')</th>
                    <th>@lang('lang_v1.date')</th>
                    <th>@lang('contact.customer')</th>
                    <th>@lang('sale.total_payable')</th>
                    <th>@lang('lang_v1.down_payment')</th>
                    <th>@lang('lang_v1.balance')</th>
                    <th>@lang('lang_v1.installment_count')</th>
                    <th>@lang('lang_v1.installment_interval')</th>
                    <th>@lang('lang_v1.due_date')</th>
                    <th>@lang('lang_v1.status')</th>
                    <th>@lang('messages.action')</th>
                </tr>
            </thead>
        </table>
    @endcomponent
</section>

@stop

@section('javascript')
<script>
$(document).ready(function() {
    $('#installment_plans_table').DataTable({
        processing: true,
        serverSide: true,
        fixedHeader: false,
        ajax: "{{ action([\App\Http\Controllers\InstallmentPlanController::class, 'index']) }}",
        columnDefs: [{
            targets: [10],
            orderable: false,
            searchable: false
        }],
        columns: [
            { data: 'invoice_no', name: 'invoice_no' },
            { data: 'transaction_date', name: 'transaction_date' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'final_total', name: 'final_total' },
            { data: 'down_payment', name: 'down_payment' },
            { data: 'balance_due', name: 'balance_due', searchable: false, orderable: false },
            { data: 'installment_count', name: 'installment_count' },
            { data: 'interval_label', name: 'interval_label', searchable: false, orderable: false },
            { data: 'first_due_date', name: 'first_due_date' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' },
        ]
    });
});
</script>
@endsection
