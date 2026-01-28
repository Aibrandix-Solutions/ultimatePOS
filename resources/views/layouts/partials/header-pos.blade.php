<!-- default value -->
@php
    $go_back_url = action([\App\Http\Controllers\SellPosController::class, 'index']);
    $transaction_sub_type = '';
    // Use relative URLs to avoid APP_URL / scheme mismatches in production (mixed-content/CORS issues).
    $view_suspended_sell_url = action([\App\Http\Controllers\SellController::class, 'index'], [], false) . '?suspended=1';
    $pos_redirect_url = action([\App\Http\Controllers\SellPosController::class, 'create']);
@endphp

@if (!empty($pos_module_data))
    @foreach ($pos_module_data as $key => $value)
        @php
            if (!empty($value['go_back_url'])) {
                $go_back_url = $value['go_back_url'];
            }

            if (!empty($value['transaction_sub_type'])) {
                $transaction_sub_type = $value['transaction_sub_type'];
                $view_suspended_sell_url .= '&transaction_sub_type=' . $transaction_sub_type;
                $pos_redirect_url .= '?sub_type=' . $transaction_sub_type;
            }
        @endphp
    @endforeach
@endif
<input type="hidden" name="transaction_sub_type" id="transaction_sub_type" value="{{ $transaction_sub_type }}">
@inject('request', 'Illuminate\Http\Request')
<div class="col-md-12 no-print pos-header">
    <input type="hidden" id="pos_redirect_url" value="{{ $pos_redirect_url }}">
    <div class="tw-flex tw-flex-col md:tw-flex-row tw-items-center tw-justify-between tw-shadow-lg tw-bg-white tw-rounded-xl tw-mx-0 tw-mt-1 tw-mb-0 md:tw-mb-0 tw-p-4">
        <div class="tw-w-full md:tw-w-1/3">
            <div class="tw-flex tw-items-center tw-gap-4">
                <div class="tw-flex tw-items-center tw-gap-2 tw-bg-gradient-to-r tw-from-blue-50 tw-to-indigo-50 tw-px-4 tw-py-2 tw-rounded-lg tw-border tw-border-blue-200 tw-shadow-sm" style="background: linear-gradient(135deg, rgba(22,17,96,0.1) 0%, rgba(42,36,128,0.1) 100%); border-color: rgba(22,17,96,0.3);">
                    <div class="tw-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-full tw-shadow-md" style="background: linear-gradient(135deg, #161160 0%, #2a2480 100%);">
                        <i class="fa fa-map-marker-alt tw-text-white tw-text-sm"></i>
                    </div>
                    <div class="tw-flex tw-flex-col">
                        <!-- <span class="tw-text-xs tw-font-medium tw-text-red-600 tw-uppercase tw-tracking-wide">@lang('sale.location')</span> -->
                        <div class="tw-flex tw-items-center tw-gap-2">
                            @if (empty($transaction->location_id))
                                @if (count($business_locations) > 1)
                                    <div class="tw-relative">
                                        {!! Form::select(
                                            'select_location_id',
                                            $business_locations,
                                            $default_location->id ?? null,
                                            ['class' => 'tw-appearance-none tw-bg-white tw-border tw-rounded-md tw-px-3 tw-py-1.5 tw-text-sm tw-font-medium tw-text-gray-700 tw-shadow-sm focus:tw-outline-none focus:tw-ring-2 tw-cursor-pointer', 'id' => 'select_location_id', 'required', 'autofocus', 'style' => 'border-color: rgba(22,17,96,0.3); focus:ring-color: rgba(22,17,96,0.4); focus:border-color: rgba(22,17,96,0.5);'],
                                            $bl_attributes,
                                        ) !!}
                                        <div class="tw-absolute tw-inset-y-0 tw-right-0 tw-flex tw-items-center tw-pr-2 tw-pointer-events-none">
                                            <i class="fa fa-chevron-down tw-text-xs" style="color: #161160;"></i>
                                        </div>
                                    </div>
                                @else
                                    <span class="tw-text-sm tw-font-semibold tw-text-gray-800 tw-bg-white tw-px-3 tw-py-1.5 tw-rounded-md tw-border tw-shadow-sm" style="border-color: rgba(22,17,96,0.3);">
                                        {{ $default_location->name }}
                                    </span>
                                @endif
                            @else
                                <span class="tw-text-sm tw-font-semibold tw-text-gray-800 tw-bg-white tw-px-3 tw-py-1.5 tw-rounded-md tw-border tw-shadow-sm" style="border-color: rgba(22,17,96,0.3);">
                                    {{ $transaction->location->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tw-hidden md:tw-block tw-py-1.5 tw-px-3 tw-rounded-md tw-transition-colors" style="background: linear-gradient(135deg, #161160 0%, #2a2480 100%);" onmouseover="this.style.background='linear-gradient(135deg, #2a2480 0%, #3d3580 100%)'" onmouseout="this.style.background='linear-gradient(135deg, #161160 0%, #2a2480 100%)'">
                     &nbsp; <span class="curr_datetime text-white tw-font-semibold">{{ @format_datetime('now') }}</span>
                    <i class="fa fa-keyboard hover-q text-white" aria-hidden="true" data-container="body"
                        data-toggle="popover" data-placement="bottom" data-content="@include('sale_pos.partials.keyboard_shortcuts_details')"
                        data-html="true" data-trigger="hover" data-original-title="" title=""></i>
                </div>

                @if (empty($pos_settings['hide_product_suggestion']))
                    <button type="button" title="{{ __('lang_v1.view_products') }}" data-placement="bottom"
                        class="tw-shadow-md tw-bg-white tw-cursor-pointer tw-border tw-flex tw-items-center tw-justify-center tw-rounded-md tw-w-8 tw-h-8 tw-text-gray-600 btn-modal pull-right tw-block md:tw-hidden tw-transition-colors"
                        style="border-color: rgba(22,17,96,0.3);" onmouseover="this.style.backgroundColor='rgba(22,17,96,0.1)'; this.style.borderColor='rgba(22,17,96,0.5)';" onmouseout="this.style.backgroundColor='white'; this.style.borderColor='rgba(22,17,96,0.3)';"
                        data-toggle="modal" data-target="#mobile_product_suggestion_modal">
                        <strong><i class="fa fa-cubes fa-lg !tw-text-sm" style="color: #161160;"></i></strong>
                    </button>
                @endif

                <span class="tw-block md:tw-hidden">
                    <i class="fas hamburger fa-bars tw-mx-5 tw-cursor-pointer tw-transition-colors" style="color: #161160;" onmouseover="this.style.color='#2a2480';" onmouseout="this.style.color='#161160';"
                        onclick="document.getElementById('pos_header_more_options').classList.toggle('tw-hidden')"></i>
                </span>

            </div>
        </div>

        <div class="tw-w-full md:tw-w-2/3 !tw-p-0 tw-flex tw-items-center tw-justify-between tw-gap-1 tw-flex-col md:tw-flex-row tw-hidden md:tw-flex"
            id="pos_header_more_options">
            
            <!-- Go Back Button -->
            <a href="{{ $go_back_url }}" title="{{ __('lang_v1.go_back') }}"
                class="tw-group tw-shadow-md tw-bg-white hover:tw-bg-red-50 tw-cursor-pointer tw-border tw-border-gray-200 hover:tw-border-red-300 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-3 tw-py-2 tw-transition-all tw-duration-200 tw-min-w-[120px]">
                <div class="tw-flex tw-items-center tw-gap-2">
                    <i class="fa fa-arrow-left tw-text-red-600 tw-text-sm group-hover:tw-text-red-700"></i>
                <span class="tw-text-xs tw-font-medium tw-text-gray-700 group-hover:tw-text-red-700">Back</span>
                </div>
            </a>

            @if (!isset($pos_settings['hide_recent_trans']) || $pos_settings['hide_recent_trans'] == 0)
                <!-- Recent Transactions Button -->
                <button type="button"
                    class="md:tw-hidden tw-group tw-shadow-md tw-bg-white hover:tw-bg-red-50 tw-cursor-pointer tw-border tw-border-gray-200 hover:tw-border-red-300 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-3 tw-py-2 tw-transition-all tw-duration-200 tw-min-w-[120px]"
                    data-toggle="modal" data-target="#recent_transactions_modal" id="recent-transactions">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fa fa-history tw-text-red-600 tw-text-sm group-hover:tw-text-red-700"></i>
                        <span class="tw-text-xs tw-font-medium tw-text-gray-700 group-hover:tw-text-red-700">History</span>
                    </div>
                </button>
            @endif

            @if (!empty($pos_settings['inline_service_staff']))
                <!-- Service Staff Availability Button -->
                <button type="button" id="show_service_staff_availability"
                    title="{{ __('lang_v1.service_staff_availability') }}"
                    class="tw-group tw-shadow-md tw-bg-white hover:tw-bg-red-50 tw-cursor-pointer tw-border tw-border-gray-200 hover:tw-border-red-300 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-3 tw-py-2 tw-transition-all tw-duration-200 tw-min-w-[120px]"
                    data-container=".view_modal"
                    data-href="{{ action([\App\Http\Controllers\SellPosController::class, 'showServiceStaffAvailibility']) }}">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fa fa-user-check tw-text-red-600 tw-text-sm group-hover:tw-text-red-700"></i>
                        <span class="tw-text-xs tw-font-medium tw-text-gray-700 group-hover:tw-text-red-700">Staff</span>
                    </div>
                </button>
            @endif

            @can('close_cash_register')
                <!-- Close Register Button -->
                <button type="button" id="close_register" title="{{ __('cash_register.close_register') }}"
                    class="tw-group tw-shadow-md tw-bg-white hover:tw-bg-red-50 tw-cursor-pointer tw-border tw-border-gray-200 hover:tw-border-red-300 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-3 tw-py-2 tw-transition-all tw-duration-200 tw-min-w-[120px] btn-modal"
                    data-container=".close_register_modal"
                    data-href="{{ action([\App\Http\Controllers\CashRegisterController::class, 'getCloseRegister']) }}">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fa fa-lock tw-text-red-600 tw-text-sm group-hover:tw-text-red-700"></i>
                        <span class="tw-text-xs tw-font-medium tw-text-gray-700 group-hover:tw-text-red-700">Close</span>
                    </div>
                </button>
            @endcan

            @if (
                !empty($pos_settings['inline_service_staff']) ||
                    (in_array('tables', $enabled_modules) || in_array('service_staff', $enabled_modules)))
                <!-- Service Staff Replacement Button -->
                <button type="button"
                    class="tw-group tw-shadow-md tw-bg-white hover:tw-bg-red-50 tw-cursor-pointer tw-border tw-border-gray-200 hover:tw-border-red-300 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-3 tw-py-2 tw-transition-all tw-duration-200 tw-min-w-[120px] popover-default"
                    id="service_staff_replacement" title="{{ __('restaurant.service_staff_replacement') }}"
                    data-toggle="popover" data-trigger="click"
                    data-content='<div class="m-8"><input type="text" class="form-control" placeholder="@lang('sale.invoice_no')" id="send_for_sell_service_staff_invoice_no"></div><div class="w-100 text-center"><button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-error" id="send_for_sercice_staff_replacement">@lang('lang_v1.send')</button></div>'
                    data-html="true" data-placement="bottom">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fa fa-user-exchange tw-text-red-600 tw-text-sm group-hover:tw-text-red-700"></i>
                        <span class="tw-text-xs tw-font-medium tw-text-gray-700 group-hover:tw-text-red-700">Replace</span>
                    </div>
                </button>
            @endif

            @can('view_cash_register')
                <!-- Register Details Button -->
                <button type="button" id="register_details" title="{{ __('cash_register.register_details') }}"
                    class="tw-group tw-shadow-md tw-bg-white hover:tw-bg-green-50 tw-cursor-pointer tw-border tw-border-gray-200 hover:tw-border-green-300 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-3 tw-py-2 tw-transition-all tw-duration-200 tw-min-w-[120px] btn-modal"
                    data-container=".register_details_modal"
                    data-href="{{ action([\App\Http\Controllers\CashRegisterController::class, 'getRegisterDetails']) }}">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fa fa-cash-register tw-text-green-600 tw-text-sm group-hover:tw-text-green-700"></i>
                        <span class="tw-text-xs tw-font-medium tw-text-gray-700 group-hover:tw-text-green-700">Register</span>
                    </div>
                </button>
            @endcan

            <!-- Calculator Button -->
            <button title="@lang('lang_v1.calculator')" id="btnCalculator" type="button"
                class="tw-group tw-shadow-md tw-bg-white hover:tw-bg-green-50 tw-cursor-pointer tw-border tw-border-gray-200 hover:tw-border-green-300 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-3 tw-py-2 tw-transition-all tw-duration-200 tw-min-w-[120px] popover-default"
                data-toggle="popover" data-trigger="click" data-content='@include('layouts.partials.calculator')' data-html="true"
                data-placement="bottom">
                <div class="tw-flex tw-items-center tw-gap-2">
                    <i class="fa fa-calculator tw-text-green-600 tw-text-sm group-hover:tw-text-green-700"></i>
                                            <span class="tw-text-xs tw-font-medium tw-text-gray-700 group-hover:tw-text-green-700">Calc</span>
                </div>
            </button>

            <!-- Return Sale Button -->
            <button type="button"
                class="tw-group tw-shadow-md tw-bg-white hover:tw-bg-red-50 tw-cursor-pointer tw-border tw-border-gray-200 hover:tw-border-red-300 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-3 tw-py-2 tw-transition-all tw-duration-200 tw-min-w-[120px] popover-default"
                id="return_sale" title="@lang('lang_v1.sell_return')" data-toggle="popover" data-trigger="click"
                data-content='<div class="m-8"><input type="text" class="form-control" placeholder="@lang('sale.invoice_no')" id="send_for_sell_return_invoice_no"></div><div class="w-100 text-center"><button type="button" class="tw-dw-btn tw-dw-btn-error tw-text-white tw-dw-btn-sm" id="send_for_sell_return">@lang('lang_v1.send')</button></div>'
                data-html="true" data-placement="bottom">
                <div class="tw-flex tw-items-center tw-gap-2">
                    <i class="fa fa-undo-alt tw-text-red-600 tw-text-sm group-hover:tw-text-red-700"></i>
                                            <span class="tw-text-xs tw-font-medium tw-text-gray-700 group-hover:tw-text-red-700">Return</span>
                </div>
            </button>

            <!-- Full Screen Button -->
            <button type="button" title="{{ __('lang_v1.full_screen') }}"
                class="tw-group tw-shadow-md tw-bg-white hover:tw-bg-red-50 tw-cursor-pointer tw-border tw-border-gray-200 hover:tw-border-red-300 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-3 tw-py-2 tw-transition-all tw-duration-200 tw-min-w-[120px]"
                id="full_screen">
                <div class="tw-flex tw-items-center tw-gap-2">
                    <i class="fa fa-expand-arrows-alt tw-text-red-600 tw-text-sm group-hover:tw-text-red-700"></i>
                                            <span class="tw-text-xs tw-font-medium tw-text-gray-700 group-hover:tw-text-red-700">Full</span>
                </div>
            </button>

            <!-- View Suspended Sales Button -->
            <button type="button" id="view_suspended_sales" title="{{ __('lang_v1.view_suspended_sales') }}"
                class="tw-group tw-shadow-md tw-bg-white hover:tw-bg-yellow-50 tw-cursor-pointer tw-border tw-border-gray-200 hover:tw-border-yellow-300 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-3 tw-py-2 tw-transition-all tw-duration-200 tw-min-w-[120px] btn-modal"
                data-container=".view_modal" data-href="{{ $view_suspended_sell_url }}">
                <div class="tw-flex tw-items-center tw-gap-2">
                    <i class="fa fa-pause-circle tw-text-yellow-600 tw-text-sm group-hover:tw-text-yellow-700"></i>
                                            <span class="tw-text-xs tw-font-medium tw-text-gray-700 group-hover:tw-text-yellow-700">Suspended</span>
                </div>
            </button>

            @if (!empty($pos_settings['customer_display_screen']))
                <!-- Customer Display Screen Button -->
                <a href="{{route('pos_display')}}" id="customer_display_screen"  onclick="window.open(this.href, 'customer_display', 'width='+screen.width+',height='+screen.height+',top=0,left=0'); return false;"   title="{{ __('lang_v1.customer_display_screen') }}"
                    class="tw-group tw-shadow-md tw-bg-white hover:tw-bg-red-50 tw-cursor-pointer tw-border tw-border-gray-200 hover:tw-border-red-300 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-3 tw-py-2 tw-transition-all tw-duration-200 tw-min-w-[120px]">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fa fa-desktop tw-text-red-600 tw-text-sm group-hover:tw-text-red-700"></i>
                        <span class="tw-text-xs tw-font-medium tw-text-gray-700 group-hover:tw-text-red-700">Display</span>
                    </div>
                </a>
            @endif

            @if (Module::has('Repair') && $transaction_sub_type != 'repair')
                @include('repair::layouts.partials.pos_header')
            @endif

            @if (in_array('pos_sale', $enabled_modules) && !empty($transaction_sub_type))
                @can('sell.create')
                    <!-- POS Sale Button -->
                    <a href="{{ action([\App\Http\Controllers\SellPosController::class, 'create']) }}"
                        title="@lang('sale.pos_sale')"
                        class="tw-group tw-shadow-md tw-bg-white hover:tw-bg-green-50 tw-cursor-pointer tw-border tw-border-gray-200 hover:tw-border-green-300 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-3 tw-py-2 tw-transition-all tw-duration-200 tw-min-w-[120px]">
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <i class="fa fa-shopping-cart tw-text-green-600 tw-text-sm group-hover:tw-text-green-700"></i>
                            <span class="tw-text-xs tw-font-medium tw-text-gray-700 group-hover:tw-text-green-700">POS</span>
                        </div>
                    </a>
                @endcan
            @endif

            @can('expense.add')
                <!-- Add Expense Button -->
                <button type="button" title="{{ __('expense.add_expense') }}" data-placement="bottom"
                    class="tw-group tw-shadow-md tw-bg-white hover:tw-bg-red-50 tw-cursor-pointer tw-border tw-border-gray-200 hover:tw-border-red-300 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-3 tw-py-2 tw-transition-all tw-duration-200 tw-min-w-[120px] btn-modal"
                    id="add_expense">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fa fa-minus-circle tw-text-red-600 tw-text-sm group-hover:tw-text-red-700"></i>
                        <span class="tw-text-xs tw-font-medium tw-text-gray-700 group-hover:tw-text-red-700">Expense</span>
                    </div>
                </button>
            @endcan

        </div>
    </div>
</div>

<div class="modal fade" id="service_staff_modal" tabindex="-1" role="dialog"
    aria-labelledby="gridSystemModalLabel">
</div>
