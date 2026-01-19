@extends('layouts.app')
@section('title', 'Cash Flow')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Cash Flow</h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('date_filter', __('report.date_range') . ':') !!}
                        {!! Form::text('date_range', null, [
                            'placeholder' => __('lang_v1.select_a_date_range'),
                            'class' => 'form-control',
                            'id' => 'date_filter',
                            'readonly',
                        ]) !!}
                    </div>
                </div>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @component('components.widget')
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="cash_flow_report">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('lang_v1.description')</th>
                                    <th>@lang('sale.amount')</th>
                                    <th>@lang('lang_v1.type')</th>
                                    <th>@lang('account.account')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                @endcomponent
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection

@section('javascript')

    <script type="text/javascript">
        $(document).ready(function() {

            if ($('#date_filter').length == 1) {

                $('#date_filter').daterangepicker(
                    dateRangeSettings,
                    function(start, end) {
                        $('#date_filter').val(start.format(moment_date_format) + ' ~ ' + end.format(
                            moment_date_format));
                        cash_flow_report.ajax.reload();
                    }
                );

                $('#date_filter').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    cash_flow_report.ajax.reload();
                });
            }

            cash_flow_report = $('#cash_flow_report').DataTable({
                processing: true,
                serverSide: true,
                    "ajax": {
                        "url": "<?php echo e(url('/account/cash-flow'), false); ?>",
                    "data": function(d) {
                        var start_date = '';
                        var endDate = '';
                        if ($('#date_filter').val()) {
                            var start_date = $('#date_filter').data('daterangepicker').startDate.format('YYYY-MM-DD');
                            var endDate = $('#date_filter').data('daterangepicker').endDate.format('YYYY-MM-DD');
                        }
                        d.start_date = start_date;
                        d.end_date = endDate;
                    }
                },
                columns: [
                    { data: 'date', name: 'date' },
                    { data: 'description', name: 'description' },
                    { data: 'amount', name: 'amount' },
                    { data: 'type', name: 'type' },
                    { data: 'account', name: 'account' },
                    { data: 'action', name: 'action' }
                ],
                "fnDrawCallback": function(oSettings) {
                    __currency_convert_recursively($('#cash_flow_report'));
                }
            });

            $('#date_filter').change(function() {
                cash_flow_report.ajax.reload();
            });
        })
    </script>
@endsection
