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
                                    <th>@lang('purchase.location')</th>
                                    <th>@lang('lang_v1.total_remaining_stock')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade batch_details_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
    <div class="modal fade" id="edit_batch_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
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
            { data: 'location_name', name: 'bl.name' },
            { data: 'total_qty_remaining', name: 'total_qty_remaining', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'asc']],
        createdRow: function( row, data, dataIndex ) {
            $(row).find('td:eq(0)').attr('data-label', "@lang('product.product')");
            $(row).find('td:eq(1)').attr('data-label', "@lang('purchase.location')");
            $(row).find('td:eq(2)').attr('data-label', "@lang('lang_v1.total_remaining_stock')");
            $(row).find('td:eq(3)').attr('data-label', "@lang('messages.action')");
        },
        fnDrawCallback: function () { __currency_convert_recursively($('#batch_details_table')); },
    });


    $(document).on('click', '.view_batch_details', function() {
        $('.view_batch_details').removeClass('active');
        $(this).addClass('active');
        var product_id = $(this).data('product_id');
        var variation_id = $(this).data('variation_id');
        var location_id = $(this).data('location_id');

        $.ajax({
            url: "{{ action([\App\Http\Controllers\ProductBatchController::class, 'getBatchDetails']) }}",
            data: { 
                product_id: product_id,
                variation_id: variation_id,
                location_id: location_id
            },
            dataType: 'html',
            success: function(result) {
                $('.batch_details_modal').html(result).modal('show');
                __currency_convert_recursively($('.batch_details_modal'));
            }
        });
    });

    $(document).on('submit', 'form#batch_edit_form', function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function(result) {
                if (result.success === 1) {
                    $('#edit_batch_modal').modal('hide');
                    toastr.success(result.msg);
                    // Refresh the batch details modal if it's open
                    var $detailsModal = $('.batch_details_modal');
                    if ($detailsModal.hasClass('in') || $detailsModal.is(':visible')) {
                        var $activeBtn = $('.view_batch_details.active');
                        if ($activeBtn.length) {
                             $activeBtn.click();
                        } else {
                             $detailsModal.modal('hide');
                        }
                    }
                    batch_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $('#product_filter, #location_filter').on('change', function () { batch_table.ajax.reload(); });
});
</script>
@endsection
