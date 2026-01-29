@extends('layouts.app')
@section('title', __('lang_v1.damages_list'))

@section('content')

    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.damages_list')</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('damage_location_id', __('purchase.business_location') . ':') !!}
                            {!! Form::select('damage_location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('damage_date_range', __('report.date_range') . ':') !!}
                            {!! Form::text('damage_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('damage_product', __('product.product') . ':') !!}
                            {!! Form::select('damage_product', $products, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]) !!}
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title">@lang('lang_v1.all_damages')</h3>
                        <div class="box-tools">
                            <a href="{{ action([App\Http\Controllers\DamageController::class, 'index']) }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> @lang('lang_v1.add_damage')
                            </a>
                        </div>
                    </div>
            <div class="box-body">
                <table class="table table-bordered table-striped" id="damages_table">
                    <thead>
                        <tr>
                            <th>@lang('messages.date')</th>
                            <th>@lang('product.product')</th>
                            <th>@lang('product.variation')</th>
                            <th>@lang('business.location')</th>
                            <th>@lang('sale.qty')</th>
                            <th>@lang('product.default_purchase_price')</th>
                            <th>@lang('sale.total')</th>
                            <th>@lang('lang_v1.reason')</th>
                            <th>@lang('lang_v1.added_by')</th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        </div>
    </section>

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            // Initialize date range picker
            $('#damage_date_range').daterangepicker(
                dateRangeSettings,
                function (start, end) {
                    $('#damage_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                }
            );

            $('#damage_date_range').on('cancel.daterangepicker', function(ev, picker) {
                $('#damage_date_range').val('');
                damages_table.ajax.reload();
            });

            var damages_table = $('#damages_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/damages/list-data',
                    data: function(d) {
                        if ($('#damage_location_id').length > 0) {
                            d.location_id = $('#damage_location_id').val();
                        }
                        if ($('#damage_product').length > 0) {
                            d.product_id = $('#damage_product').val();
                        }
                        if ($('#damage_date_range').length > 0) {
                            var daterange = $('#damage_date_range').data('daterangepicker');
                            if (daterange && daterange.startDate) {
                                d.start_date = daterange.startDate.format('YYYY-MM-DD');
                                d.end_date = daterange.endDate.format('YYYY-MM-DD');
                            }
                        }
                    }
                },
                columns: [
                    { data: 'created_at', name: 'created_at' },
                    { data: 'product_name', name: 'product_name' },
                    { data: 'variation_name', name: 'variation_name' },
                    { data: 'location_name', name: 'location_name' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'unit_cost', name: 'unit_cost' },
                    { data: 'total_cost', name: 'total_cost' },
                    { data: 'reason', name: 'reason' },
                    { data: 'added_by', name: 'added_by' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']]
            });

            // Reload table when filters change
            $('#damage_location_id, #damage_product').on('change', function() {
                damages_table.ajax.reload();
            });

            $('#damage_date_range').on('apply.daterangepicker', function() {
                damages_table.ajax.reload();
            });

            // Delete damage handler
            $(document).on('click', '.delete-damage', function(e) {
                e.preventDefault();
                var url = $(this).data('href');

                swal({
                    title: LANG.sure,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((confirmed) => {
                    if (!confirmed) return;

                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(result) {
                            if (result.success) {
                                toastr.success(result.msg || LANG.deleted_success);
                                damages_table.ajax.reload();
                            } else {
                                toastr.error(result.msg || 'Could not delete damage');
                            }
                        },
                        error: function() {
                            toastr.error('Error deleting damage');
                        }
                    });
                });
            });
        });
    </script>
@endsection