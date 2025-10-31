@php
    $is_mobile = isMobile();
@endphp
<div class="row">
    <div
        class="pos-form-actions tw-rounded-tr-xl tw-rounded-tl-xl tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-bg-white tw-cursor-pointer">
        <div
            class="tw-flex tw-items-center tw-justify-between tw-flex-col sm:tw-flex-row md:tw-flex-row lg:tw-flex-row xl:tw-flex-row tw-gap-2 tw-px-4 tw-py-0 tw-overflow-x-auto tw-w-full">

            <div class="md:!tw-w-none !tw-flex md:!tw-hidden !tw-flex-row !tw-items-center !tw-gap-3">
                <div class="tw-pos-total tw-flex tw-items-center tw-gap-4 tw-bg-gradient-to-r tw-from-slate-50 tw-to-gray-100 tw-px-6 tw-py-4 tw-rounded-xl tw-shadow-lg tw-border tw-border-gray-200">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-10 tw-h-10 tw-bg-gradient-to-br tw-from-indigo-600 tw-to-indigo-800 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-shadow-md" style="background: linear-gradient(135deg, #161160 0%, #2a2480 100%);">
                            <i class="fas fa-calculator tw-text-white tw-text-lg"></i>
                        </div>
                        <div class="tw-text-gray-700 tw-font-semibold tw-text-sm tw-flex tw-flex-col">
                            <div class="tw-text-xs tw-text-gray-500 tw-uppercase tw-tracking-wide">@lang('sale.total_payable')</div>
                            <div class="tw-text-xs tw-text-gray-400">Amount Due</div>
                        </div>
                    </div>
                    <input type="hidden" name="final_total" id="final_total_input" value="0.00">
                    <div class="tw-text-right">
                        <span id="total_payable" class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-tracking-tight number" style="text-shadow: 0 1px 2px rgba(0,0,0,0.1);">0.00</span>
                    </div>
                </div>
            </div>

            <div class="!tw-w-full md:!tw-w-none !tw-flex md:!tw-hidden !tw-flex-row !tw-items-center !tw-gap-3">
                @if (empty($edit))
                    <button type="button" class="tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-rounded-lg tw-p-3 tw-w-[5.5rem] tw-flex tw-flex-row tw-items-center tw-justify-center tw-gap-2 tw-shadow-lg tw-transition-all tw-duration-300 tw-hover:tw-scale-105 tw-hover:tw-shadow-xl" 
                        style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border: 1px solid rgba(220,38,38,0.2);"
                        id="pos-cancel"> <i class="fas fa-window-close tw-mr-2"></i> @lang('sale.cancel')</button>
                @else
                    <button type="button" class="btn-danger tw-dw-btn hide tw-dw-btn-xs" id="pos-delete"
                        @if (!empty($only_payment)) disabled @endif> <i class="fas fa-trash-alt"></i>
                        @lang('messages.delete')</button>
                @endif
                @if (!Gate::check('disable_pay_checkout') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    <button type="button"
                        class="tw-flex tw-flex-row tw-items-center tw-justify-center tw-gap-4 tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-rounded-lg tw-p-3 tw-w-[8.5rem] tw-shadow-lg tw-transition-all tw-duration-300 tw-hover:tw-scale-105 tw-hover:tw-shadow-xl @if (!$is_mobile)  @endif no-print @if ($pos_settings['disable_pay_checkout'] != 0) hide @endif"
                        style="background: linear-gradient(135deg, #28b77b 0%, #20a16b 100%); border: 1px solid rgba(40,183,123,0.2);"
                        id="pos-finalize" title="@lang('lang_v1.tooltip_checkout_multi_pay')"><i class="fas fa-money-check-alt"
                            aria-hidden="true"></i> @lang('lang_v1.checkout_multi_pay') </button>
                @endif
            </div>
            <div class="tw-flex tw-items-center tw-gap-4 tw-flex-row tw-overflow-x-auto">

                @if (!Gate::check('disable_draft') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    <button type="button"
                        class="tw-font-bold tw-text-gray-700 tw-text-xs md:tw-text-sm tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-1 tw-p-2 tw-rounded-lg tw-shadow-md tw-transition-all tw-duration-300 tw-hover:tw-scale-105 tw-hover:tw-shadow-lg tw-border-2 tw-border-transparent tw-hover:tw-border-blue-200 @if ($pos_settings['disable_draft'] != 0) hide @endif"
                        style=" border: 1px solid rgba(22,17,96,0.2);"
                        id="pos-draft" @if (!empty($only_payment)) disabled @endif><i
                            class="fas fa-edit tw-text-[#009ce4] tw-text-lg"></i> @lang('sale.draft')</button>
                @endif

                @if (!Gate::check('disable_quotation') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    <button type="button"
                        class="tw-font-bold tw-text-gray-700 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-1 tw-p-2 tw-rounded-lg tw-shadow-md tw-transition-all tw-duration-300 tw-hover:tw-scale-105 tw-hover:tw-shadow-lg tw-border-2 tw-border-transparent tw-hover:tw-border-orange-200 @if ($is_mobile) col-xs-6 @endif"
                        style=" border: 1px solid rgba(22,17,96,0.2);"
                        id="pos-quotation" @if (!empty($only_payment)) disabled @endif><i
                            class="fas fa-edit tw-text-[#E7A500] tw-text-lg"></i> @lang('lang_v1.quotation')</button>
                @endif

                @if (!Gate::check('disable_suspend_sale') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    @if (empty($pos_settings['disable_suspend']))
                        <button type="button"
                            class="tw-font-bold tw-text-gray-700 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-1 tw-p-2 tw-rounded-lg tw-shadow-md tw-transition-all tw-duration-300 tw-hover:tw-scale-105 tw-hover:tw-shadow-lg tw-border-2 tw-border-transparent tw-hover:tw-border-red-200 no-print pos-express-finalize"
                            style="border: 1px solid rgba(22,17,96,0.2);"
                            data-pay_method="suspend" title="@lang('lang_v1.tooltip_suspend')"
                            @if (!empty($only_payment)) disabled @endif>
                            <i class="fas fa-clock tw-text-[#EF4B51] tw-text-lg" aria-hidden="true"></i>
                            @lang('lang_v1.suspend')
                        </button>
                    @endif
                @endif

                @if (!Gate::check('disable_credit_sale') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    @if (empty($pos_settings['disable_credit_sale_button']))
                        <input type="hidden" name="is_credit_sale" value="0" id="is_credit_sale">
                        <button type="button"
                            class="tw-font-bold tw-text-gray-700 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-1 tw-p-2 tw-rounded-lg tw-shadow-md tw-transition-all tw-duration-300 tw-hover:tw-scale-105 tw-hover:tw-shadow-lg tw-border-2 tw-border-transparent tw-hover:tw-border-purple-200 no-print pos-express-finalize @if ($is_mobile) col-xs-6 @endif"
                            style=" border: 1px solid rgba(22,17,96,0.2);"
                            data-pay_method="credit_sale" title="@lang('lang_v1.tooltip_credit_sale')"
                            @if (!empty($only_payment)) disabled @endif>
                            <i class="fas fa-check tw-text-[#5E5CA8] tw-text-lg" aria-hidden="true"></i> @lang('lang_v1.credit_sale')
                        </button>
                    @endif
                @endif
                @if (!Gate::check('disable_card') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    {{-- <button type="button"
                        class="tw-font-bold tw-text-gray-700 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-1 tw-p-2 tw-rounded-lg tw-shadow-md tw-transition-all tw-duration-300 tw-hover:tw-scale-105 tw-hover:tw-shadow-lg tw-border-2 tw-border-transparent tw-hover:tw-border-pink-200 no-print @if (!empty($pos_settings['disable_suspend']))  @endif pos-express-finalize @if (!array_key_exists('card', $payment_types)) hide @endif @if ($is_mobile) col-xs-6 @endif"
                        style=" border: 1px solid rgba(22,17,96,0.2);"
                        data-pay_method="card" title="@lang('lang_v1.tooltip_express_checkout_card')">
                        <i class="fas fa-credit-card tw-text-[#D61B60] tw-text-lg" aria-hidden="true"></i> @lang('lang_v1.express_checkout_card')
                    </button> --}}
                @endif
                @if (empty($edit))
                    <button type="button"
                        class="tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-rounded-lg tw-p-3 tw-w-[8.5rem] tw-hidden md:tw-flex lg:tw-flex lg:tw-flex-row lg:tw-items-center lg:tw-justify-center lg:tw-gap-1 tw-shadow-lg tw-transition-all tw-duration-300 tw-hover:tw-scale-105 tw-hover:tw-shadow-xl"
                        style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border: 1px solid rgba(220,38,38,0.2);"
                        id="pos-cancel"> <i class="fas fa-window-close tw-mr-2"></i> @lang('sale.cancel')</button>
                @else
                    <button type="button"
                        class="tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-bg-red-600 tw-p-2 tw-rounded-md tw-w-[8.5rem] tw-hidden md:tw-flex lg:tw-flex lg:tw-flex-row lg:tw-items-center lg:tw-justify-center lg:tw-gap-1 hide"
                        id="pos-delete" @if (!empty($only_payment)) disabled @endif> <i
                            class="fas fa-trash-alt"></i> @lang('messages.delete')</button>
                @endif
                @if (!Gate::check('disable_pay_checkout') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    <button type="button"
                        class="tw-hidden md:tw-flex md:tw-flex-row md:tw-items-center md:tw-justify-center md:tw-gap-1 tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-rounded-lg tw-p-3 tw-w-[8.5rem] tw-shadow-lg tw-transition-all tw-duration-300 tw-hover:tw-scale-105 tw-hover:tw-shadow-xl @if (!$is_mobile)  @endif no-print @if ($pos_settings['disable_pay_checkout'] != 0) hide @endif"
                        style="background: linear-gradient(135deg, #28b77b 0%, #20a16b 100%); border: 1px solid rgba(40,183,123,0.2);"
                        id="pos-finalize" title="@lang('lang_v1.tooltip_checkout_multi_pay')"><i class="fas fa-money-check-alt"
                            aria-hidden="true"></i> @lang('lang_v1.pay') </button>
                @endif

                @if (!$is_mobile)
                    {{-- <div class="bg-navy pos-total text-white ">
					<span class="text">@lang('sale.total_payable')</span>
					<input type="hidden" name="final_total" 
												id="final_total_input" value=0>
					<span id="total_payable" class="number">0</span>
					</div> --}}
                    <div class="pos-total md:tw-flex md:tw-items-center md:tw-gap-4 tw-hidden tw-bg-gradient-to-r tw-from-slate-50 tw-to-gray-100 tw-px-8 tw-py-6 tw-rounded-2xl tw-shadow-xl tw-border tw-border-gray-200">
                        <div class="tw-flex tw-items-center tw-gap-4">
                            <div class="tw-w-12 tw-h-12 tw-bg-gradient-to-br tw-from-indigo-600 tw-to-indigo-800 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-shadow-lg" style="background: linear-gradient(135deg, #161160 0%, #2a2480 100%);">
                                <i class="fas fa-calculator tw-text-white tw-text-xl"></i>
                            </div>
                            <div class="tw-text-gray-700 tw-font-semibold tw-text-sm tw-flex tw-flex-col">
                                <div class="tw-text-sm tw-text-gray-600 tw-uppercase tw-tracking-wide tw-font-bold">@lang('sale.total_payable')</div>
                                <div class="tw-text-xs tw-text-gray-500">Amount Due</div>
                            </div>
                        </div>
                        <input type="hidden" name="final_total" id="final_total_input" value="0.00">
                        <div class="tw-text-right">
                            <span id="total_payable" class="tw-text-3xl tw-font-bold tw-text-gray-800 tw-tracking-tight number" style="text-shadow: 0 2px 4px rgba(0,0,0,0.1);">0.00</span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="tw-w-full md:tw-w-fit tw-flex tw-flex-col tw-items-end tw-gap-3 tw-hidden md:tw-block">
                @if (!isset($pos_settings['hide_recent_trans']) || $pos_settings['hide_recent_trans'] == 0)
                    <button type="button"
                        class="tw-font-bold tw-rounded-full tw-text-white tw-w-full md:tw-w-fit tw-px-6 tw-p-3 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-shadow-lg tw-transition-all tw-duration-300 tw-hover:tw-scale-105 tw-hover:tw-shadow-xl tw-border-2 tw-border-transparent tw-hover:tw-border-indigo-300"
                        style="background: linear-gradient(135deg, #161160 0%, #3d3580 100%); border: 1px solid rgba(22,17,96,0.3);"
                        data-toggle="modal" data-target="#recent_transactions_modal" id="recent-transactions"> <i
                            class="fas fa-clock tw-mr-2"></i> @lang('lang_v1.recent_transactions')</button>
                @endif
            </div>
        </div>
    </div>
</div>
@if (isset($transaction))
    @include('sale_pos.partials.edit_discount_modal', [
        'sales_discount' => $transaction->discount_amount,
        'discount_type' => $transaction->discount_type,
        'rp_redeemed' => $transaction->rp_redeemed,
        'rp_redeemed_amount' => $transaction->rp_redeemed_amount,
        'max_available' => !empty($redeem_details['points']) ? $redeem_details['points'] : 0,
    ])
@else
    @include('sale_pos.partials.edit_discount_modal', [
        'sales_discount' => $business_details->default_sales_discount,
        'discount_type' => 'percentage',
        'rp_redeemed' => 0,
        'rp_redeemed_amount' => 0,
        'max_available' => 0,
    ])
@endif

@if (isset($transaction))
    @include('sale_pos.partials.edit_order_tax_modal', ['selected_tax' => $transaction->tax_id])
@else
    @include('sale_pos.partials.edit_order_tax_modal', [
        'selected_tax' => $business_details->default_sales_tax,
    ])
@endif

@include('sale_pos.partials.edit_shipping_modal')
