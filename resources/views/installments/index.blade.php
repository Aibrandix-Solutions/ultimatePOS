@extends('layouts.app')
@section('title', __('lang_v1.installment_plans'))

@section('content')

<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.installment_plans')</h1>
</section>

<section class="content">
    @component('components.filters', ['title' => __('report.filters')])
        <div class="col-md-3">
            <div class="form-group">
                <label for="installment_filter_start_date">Start Date:</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input type="text" class="form-control" id="installment_filter_start_date" placeholder="Start date" autocomplete="off">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="installment_filter_end_date">End Date:</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input type="text" class="form-control" id="installment_filter_end_date" placeholder="End date" autocomplete="off">
                </div>
            </div>
        </div>
    @endcomponent

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
    var dt_table = $('#installment_plans_table').DataTable({
        processing: true,
        serverSide: true,
        fixedHeader: false,
        ajax: {
            url: "{{ action([\App\Http\Controllers\InstallmentPlanController::class, 'index']) }}",
            data: function (d) {
                d.start_date = $('#installment_filter_start_date').val();
                d.end_date = $('#installment_filter_end_date').val();
            }
        },
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
            { data: 'next_due_date', name: 'next_due_date' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' },
        ]
    });

    // Initialize datepickers for date range filters
    $('#installment_filter_start_date').datepicker({ autoclose: true, format: datepicker_date_format });
    $('#installment_filter_end_date').datepicker({ autoclose: true, format: datepicker_date_format });

    // Trigger filter on date change
    $('#installment_filter_start_date, #installment_filter_end_date').on('changeDate', function() {
        dt_table.draw();
    });

    // Optional: Allow Enter key to trigger filter
    $('#installment_filter_start_date, #installment_filter_end_date').keypress(function(e) {
        if (e.which == 13) {
            dt_table.draw();
            return false;
        }
    });
});
</script>
@endsection
