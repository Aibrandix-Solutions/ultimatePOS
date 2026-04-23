@extends('layouts.app')
@section('title', __('lang_v1.batch_details'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('lang_v1.batch_details') }}</h1>
    <p class="text-muted tw-mb-0"><strong>{{ __('lang_v1.batch_details_multi_batch_only') }}</strong></p>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i>
                {{ __('lang_v1.batch_details_multi_batch_only_help') }}
            </div>
            @component('components.filters', ['title' => __('report.filters')])
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('product_filter', __('product.product') . ':') !!}
                        {!! Form::select('product_filter', [], null, [
                            'class' => 'form-control',
                            'style' => 'width:100%',
                            'id' => 'product_filter',
                            'placeholder' => __('lang_v1.all'),
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('location_filter', __('purchase.business_location') . ':') !!}
                        {!! Form::select('location_filter', $business_locations, null, [
                            'class' => 'form-control select2',
                            'style' => 'width:100%',
                            'id' => 'location_filter',
                            'placeholder' => __('lang_v1.all'),
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('batch_date_range', __('report.date_range') . ':') !!}
                        <input type="text" class="form-control" id="batch_date_range"
                            readonly placeholder="@lang('report.date_range')" style="background-color: #fff;">
                    </div>
                </div>
            @endcomponent

            <div class="box box-solid">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="batch_details_table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>@lang('product.product')</th>
                                    <th>@lang('lang_v1.batch_number')</th>
                                    <th>@lang('purchase.location')</th>
                                    <th>@lang('lang_v1.purchase_price')</th>
                                    <th>@lang('lang_v1.selling_price_inc_tax')</th>
                                    <th>@lang('lang_v1.qty_in')</th>
                                    <th>@lang('lang_v1.qty_out')</th>
                                    <th>@lang('lang_v1.remaining_stock')</th>
                                    <th>@lang('purchase.purchase_date')</th>
                                    <th>@lang('purchase.ref_no')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop

@section('javascript')
<script type="text/javascript">
$(document).ready(function () {
    var dateRangeSettings = {
        autoUpdateInput: false,
        locale: { cancelLabel: 'Clear' },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        },
    };

    $('#batch_date_range').daterangepicker(dateRangeSettings, function (start, end) {
        $('#batch_date_range').val(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        batch_table.ajax.reload();
    });
    $('#batch_date_range').on('cancel.daterangepicker', function () {
        $(this).val('');
        batch_table.ajax.reload();
    });

    $('#product_filter').select2({
        ajax: {
            url: '/products/list',
            dataType: 'json',
            delay: 250,
            data: function (params) { return { q: params.term, page: params.page }; },
            processResults: function (data) {
                return {
                    results: data.map(function (p) {
                        return { id: p.product_id, text: p.name + ' (' + p.sub_sku + ')' };
                    }),
                };
            },
        },
        minimumInputLength: 1,
        allowClear: true,
        placeholder: "{{ __('lang_v1.all') }}",
    });

    var batch_table = $('#batch_details_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ action([\App\Http\Controllers\ProductBatchController::class, 'index']) }}",
            data: function (d) {
                d.product_id = $('#product_filter').val();
                d.location_id = $('#location_filter').val();
                var range = $('#batch_date_range').val();
                if (range) {
                    var parts = range.split(' to ');
                    d.start_date = parts[0];
                    d.end_date = parts[1];
                }
            },
        },
        columns: [
            { data: 'product_name', name: 'p.name' },
            { data: 'batch_number', name: 'purchase_lines.batch_number' },
            { data: 'location_name', name: 'bl.name' },
            { data: 'purchase_price', name: 'purchase_lines.purchase_price' },
            { data: 'batch_selling_price_inc_tax', name: 'purchase_lines.batch_selling_price_inc_tax' },
            { data: 'qty_in', name: 'purchase_lines.quantity' },
            { data: 'qty_out', name: 'qty_out', orderable: false, searchable: false },
            { data: 'qty_remaining', name: 'qty_remaining', orderable: false, searchable: false },
            { data: 'transaction_date', name: 't.transaction_date' },
            { data: 'purchase_ref', name: 't.ref_no' },
        ],
        order: [[8, 'desc']],
        fnDrawCallback: function () { __currency_convert_recursively($('#batch_details_table')); },
    });

    $('#product_filter, #location_filter').on('change', function () { batch_table.ajax.reload(); });
});
</script>
@endsection
