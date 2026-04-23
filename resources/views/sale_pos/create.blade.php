@extends('layouts.app')

@section('title', __('sale.pos_sale'))

@section('content')
    <section class="content no-print">
        <input type="hidden" id="amount_rounding_method" value="{{ $pos_settings['amount_rounding_method'] ?? '' }}">
        @if (!empty($pos_settings['allow_overselling']))
            <input type="hidden" id="is_overselling_allowed">
        @endif
        @if (session('business.enable_rp') == 1)
            <input type="hidden" id="reward_point_enabled">
        @endif
        @php
            $is_discount_enabled = $pos_settings['disable_discount'] != 1 ? true : false;
            $is_rp_enabled = session('business.enable_rp') == 1 ? true : false;
        @endphp
        {!! Form::open([
            'url' => action([\App\Http\Controllers\SellPosController::class, 'store']),
            'method' => 'post',
            'id' => 'add_pos_sell_form',
        ]) !!}
        <div class="row mb-12">
            <div class="col-md-12 tw-pt-0 tw-mb-14">
                <div class="row tw-flex lg:tw-flex-row md:tw-flex-col sm:tw-flex-col tw-flex-col tw-items-start md:tw-gap-4">
                    {{-- <div class="@if (empty($pos_settings['hide_product_suggestion'])) col-md-7 @else col-md-10 col-md-offset-1 @endif no-padding pr-12"> --}}
                    <div class="tw-px-3 tw-w-full  lg:tw-px-0 lg:tw-pr-0 @if(empty($pos_settings['hide_product_suggestion'])) lg:tw-w-[60%]  @else lg:tw-w-[100%] @endif">

                        <div class="tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-rounded-2xl tw-bg-white tw-mb-2 md:tw-mb-8 tw-p-2">

                            {{-- <div class="box box-solid mb-12 @if (!isMobile()) mb-40 @endif"> --}}
                                <div class="box-body pb-0">
                                    {!! Form::hidden('location_id', $default_location->id ?? null, [
                                        'id' => 'location_id',
                                        'data-receipt_printer_type' => !empty($default_location->receipt_printer_type)
                                            ? $default_location->receipt_printer_type
                                            : 'browser',
                                        'data-default_payment_accounts' => $default_location->default_payment_accounts ?? '',
                                    ]) !!}
                                    <!-- sub_type -->
                                    {!! Form::hidden('sub_type', isset($sub_type) ? $sub_type : null) !!}
                                    <input type="hidden" id="item_addition_method"
                                        value="{{ $business_details->item_addition_method }}">
                                    
                                    @if(!empty($exchange_data))
                                        <!-- Exchange Mode Banner -->
                                        <div class="alert alert-info" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); border: none; color: white; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 8px;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4 style="margin-top: 0; margin-bottom: 10px;">
                                                        <i class="fa fa-exchange-alt"></i> @lang('lang_v1.exchange') @lang('sale.mode')
                                                    </h4>
                                                    <p style="margin-bottom: 5px; font-size: 16px;">
                                                        <strong>@lang('lang_v1.return_credit'):</strong> 
                                                        <span class="text-white" style="font-size: 20px; font-weight: bold;">{{ @num_format($exchange_data['return_credit']) }}</span>
                                                    </p>
                                                    <p style="margin-bottom: 5px;">
                                                        <strong>@lang('sale.invoice_no'):</strong> {{ $exchange_data['return_invoice_no'] }}
                                                    </p>
                                                    <p style="margin-bottom: 0;">
														<strong>@lang('contact.customer'):</strong> {{ data_get($exchange_data, 'customer.name', '') }}
														@if(!empty(data_get($exchange_data, 'customer.mobile')))
															- {{ data_get($exchange_data, 'customer.mobile') }}
                                                        @endif
                                                    </p>
                                                    <input type="hidden" name="exchange_return_id" value="{{ $exchange_data['return_id'] }}">
                                                    <input type="hidden" name="exchange_parent_sale_id" value="{{ $exchange_data['parent_sale_id'] }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @include('sale_pos.partials.pos_form')

                                    @include('sale_pos.partials.pos_form_totals')

                                    @include('sale_pos.partials.payment_modal')

                                    @if (empty($pos_settings['disable_suspend']))
                                        @include('sale_pos.partials.suspend_note_modal')
                                    @endif

                                    @if (empty($pos_settings['disable_recurring_invoice']))
                                        @include('sale_pos.partials.recurring_invoice_modal')
                                    @endif
                                </div>
                            {{-- </div> --}}
                        </div>
                    </div>
                    @if (empty($pos_settings['hide_product_suggestion']) && !isMobile())
                        <div class="md:tw-no-padding tw-w-full lg:tw-w-[40%] tw-px-5">
                            @include('sale_pos.partials.pos_sidebar')
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @include('sale_pos.partials.pos_form_actions')
        {!! Form::close() !!}
    </section>

    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        @include('contact.create', ['quick_add' => true])
    </div>
    @if (empty($pos_settings['hide_product_suggestion']) && isMobile())
        @include('sale_pos.partials.mobile_product_suggestions')
    @endif
    <!-- /.content -->
    <div class="modal fade register_details_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade close_register_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <!-- quick product modal -->
    <div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>

    <div class="modal fade" id="expense_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    @include('sale_pos.partials.configure_search_modal')

    @include('sale_pos.partials.recent_transactions_modal')

    @include('sale_pos.partials.weighing_scale_modal')

    @if(session('business.enable_batch_pricing'))
        @include('sale_pos.partials.batch_select_modal')
    @endif

    <!-- Product Price Edit Modal - Single modal for all products -->
    <div class="modal fade row_edit_product_price_model" id="row_edit_product_price_modal" tabindex="-1" role="dialog" aria-labelledby="row_edit_product_price_modal_label" aria-hidden="true">
        @include('sale_pos.partials.row_edit_product_price_modal_single')
    </div>

@stop
@section('css')
    <!-- include module css -->
    @if (!empty($pos_module_data))
        @foreach ($pos_module_data as $key => $value)
            @if (!empty($value['module_css_path']))
                @includeIf($value['module_css_path'])
            @endif
        @endforeach
    @endif
@stop
@section('javascript')
    <script type="text/javascript">
        window.__enable_batch_pricing = {{ session('business.enable_batch_pricing') ? 'true' : 'false' }};
    </script>
    <script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/printer.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
    @include('sale_pos.partials.keyboard_shortcuts')

    <!-- Call restaurant module if defined -->
    @if (in_array('tables', $enabled_modules) ||
            in_array('modifiers', $enabled_modules) ||
            in_array('service_staff', $enabled_modules))
        <script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
    @endif
    <!-- include module js -->
    @if (!empty($pos_module_data))
        @foreach ($pos_module_data as $key => $value)
            @if (!empty($value['module_js_path']))
                @includeIf($value['module_js_path'], ['view_data' => $value['view_data']])
            @endif
        @endforeach
    @endif
    
    @if(!empty($exchange_data))
    <script>
        $(document).ready(function() {
            // Disable customer field in exchange mode to prevent changes
            $('#customer_id').prop('disabled', true);
            $('.add_new_customer').prop('disabled', true);
            
            // Add visual indicator that customer is locked
            $('#customer_id').closest('.input-group').css({
                'opacity': '0.7',
                'cursor': 'not-allowed'
            });
            
            // Add tooltip to inform user
            $('#customer_id').attr('title', 'Customer cannot be changed in exchange mode');
        });
    </script>
    @endif
@endsection
