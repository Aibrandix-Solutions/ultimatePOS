<div class="row" id="featured_products_box" style="display: none;">
    @if (!empty($featured_products))
        @include('sale_pos.partials.featured_products')
    @endif
</div>
<div class="row tw-mb-1">
    @if (!empty($categories))
        <div class="col-md-6 !tw-px-2" id="product_category_div">
            <div class="tw-dw-drawer tw-dw-drawer-end">
                <input id="my-drawer-4" type="checkbox" class="tw-dw-drawer-toggle">
                <div class="tw-dw-drawer-content">
                    <!-- Page content here -->
                    <label for="my-drawer-4"
                        class="tw-bg-gradient-to-r tw-from-[#161160] tw-to-[#2a2480] hover:tw-from-[#161160] hover:tw-to-[#2a2480] focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#161160] focus:tw-ring-offset-2 active:tw-from-[#2a2480] active:tw-to-[#2a2480] lg:tw-w-[98%] tw-w-full tw-flex tw-items-center tw-justify-center tw-gap-1 tw-text-base md:tw-text-lg tw-text-white tw-font-semibold tw-rounded-xl tw-h-12 tw-cursor-pointer" style="background: linear-gradient(135deg,#161160,#2a2480) !important;">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="tw-w-5 icon icon-tabler icon-tabler-category-plus" width="44" height="44"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 4h6v6h-6zm10 0h6v6h-6zm-10 10h6v6h-6zm10 3h6m-3 -3v6" />
                        </svg>
                        @lang('category.category')
                    </label>
                </div>
                <div class="tw-dw-drawer-side" style="z-index: 4000">
                    <label for="my-drawer-4" aria-label="close sidebar"
                        class="tw-dw-drawer-overlay overlay-category"></label>
                    <div class="tw-dw-menu tw-w-2/4 tw-min-h-full tw-bg-white tw-p-6">
                        <div class="tw-flex tw-items-center tw-mb-16" style="background:linear-gradient(135deg,#161160,#2a2480); border-radius:12px; padding:10px 12px; color:#ffffff;">
                            <button type="button"
                                class="tw-dw-btn category-back tw-bg-transparent tw-border-2"
                                style="display: none; border-color:#ffffff;color:#ffffff;">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="tw-w-5 icon icon-tabler icon-tabler-chevron-left" width="44"
                                    height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M15 6l-6 6l6 6" />
                                </svg>
                            </button>

                            <h3 class="tw-text-center tw-flex-grow mx-auto category_heading tw-font-bold tw-text-base md:tw-text-2xl" style="margin-bottom:0;margin-top:5px;color:#ffffff;">@lang('category.category')</h3>

                            <button type="button" class="tw-dw-btn close-side-bar-category" style="color:#ffffff; border-color:#ffffff; background:transparent;">
                                <i class="fa fa-times-circle" aria-hidden="true"></i>
                            </button>

                        </div>
                        <div class="row tw-mr-5">
                            <div class="col-md-3 col-xs-12 tw-mb-7 tw-w-auto  tw-h-auto tw-cursor-pointer  main-category-div main-category no-print"
                                data-value="all" data-parent="0">
                                <div class="tw-dw-card tw-w-25 tw-bg-base-100 tw-shadow-sm tw-h-auto tw-shadow-[rgba(22,_17,_96,_0.12)_0px_8px_24px] tw-bg-white hover:tw-bg-white/60 tw-cursor-pointer !tw-text-xs md:!tw-text-sm tw-font-semibold tw-text-center tw-border-2 tw-border-white/30">
                                    <div class="tw-dw-card-body">
                                        <h4 class="tw-flex tw-items-center tw-justify-center" style="margin-bottom: 0px; margin-top:0px; font-size: inherit; font-weight: inherit;">@lang('lang_v1.all_category')</h4>
                                    </div>
                                </div>
                            </div>
                            @foreach ($categories as $category)
                                    <div class="col-md-3 col-xs-12 tw-mb-7 tw-w-auto  tw-h-28  tw-cursor-pointer main-category-div  no-print"
                                        data-value="{{ $category['id'] }}" data-name="{{ $category['name'] }}" data-parent="1">
                                        <div
                                            class="tw-dw-card tw-w-25 tw-bg-base-100 tw-shadow-sm tw-h-auto tw-shadow-[rgba(22,_17,_96,_0.12)_0px_8px_24px] tw-bg-white hover:tw-bg-white/60 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-font-semibold !tw-text-center tw-border-2 tw-border-white/30">
                                            <div class="tw-dw-card-body" style="margin-bottom: -20px">
                                                <h4 class="tw-flex tw-items-center tw-justify-center"
                                                    style="margin-bottom: 0px; margin-top:0px; font-size: inherit; font-weight: inherit;">
                                                    {{ $category['name'] }}</h4>
                                            </div>
                                            <div class="tw-dw-card-actions tw-justify-center">
                                                <button type="button" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-sm main-category tw-mb-2" style="border-color:#ffffff;color:#ffffff;background:transparent;" data-value="{{ $category['id'] }}" data-parent="0">{{ __('lang_v1.all') }}</button>
                                                @if (!empty($category['sub_categories']))
                                                <button type="button" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-sm main-category tw-mb-2" style="background:linear-gradient(135deg,#161160,#2a2480);color:#fff;border-color:#2a2480;" data-parent="1" data-value="{{ $category['id'] }}" data-name="{{ $category['name'] }}">@lang('pagination.next')</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                            @foreach ($categories as $category)
                                @if (!empty($category['sub_categories']))
                                    <div class="{{ $category['id'] }} all-sub-category" style="display: none">
                                        @foreach ($category['sub_categories'] as $sc)
                                            @if ($sc['parent_id'] != 0)
                                                <div class="col-md-3 col-xs-12 tw-mb-5 tw-w-auto tw-h-auto tw-cursor-pointer product_category no-print"
                                                    data-value="{{ $sc['id'] }}">
                                                    <div
                                                        class="tw-dw-card tw-w-25 tw-bg-base-100 tw-shadow-sm tw-h-auto tw-shadow-[rgba(22,_17,_96,_0.12)_0px_8px_24px] tw-bg-white hover:tw-bg-white/60 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-font-semibold tw-text-center tw-border-2 tw-border-white/30">
                                                        <div class="tw-dw-card-body">
                                                            <h4 class="tw-flex tw-items-center tw-justify-center"
                                                                style="margin-bottom: 0px; margin-top:0px; font-size: inherit; font-weight: inherit;">
                                                                {{ $sc['name'] }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (!empty($brands))
        <div class="col-sm-6 !tw-px-2" id="product_brand_div">
            <div class="tw-dw-drawer tw-dw-drawer-end">
                <input id="my-drawer-brand" type="checkbox" class="tw-dw-drawer-toggle">
                <div class="tw-dw-drawer-content">
                    <!-- Page content here -->
                    <label for="my-drawer-brand"
                        class="tw-bg-gradient-to-r tw-from-[#161160] tw-to-[#2a2480] hover:tw-from-[#161160] hover:tw-to-[#2a2480] focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#161160] focus:tw-ring-offset-2 active:tw-from-[#2a2480] active:tw-to-[#2a2480] lg:tw-w-[98%] tw-w-full tw-flex tw-items-center tw-justify-center tw-gap-1 tw-text-base md:tw-text-lg tw-text-white tw-font-semibold tw-rounded-xl tw-h-12 tw-cursor-pointer" style="background: linear-gradient(135deg,#161160,#2a2480) !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-5 icon icon-tabler icon-tabler-brand-beats"
                            width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12.5 12.5m-3.5 0a3.5 3.5 0 1 0 7 0a3.5 3.5 0 1 0 -7 0" />
                            <path d="M9 12v-8" />
                        </svg>
                        @lang('brand.brands')
                    </label>

                </div>
                <div class="tw-dw-drawer-side" style="z-index: 4000">
                    <label for="my-drawer-brand" aria-label="close sidebar"
                        class="tw-dw-drawer-overlay overlay-brand"></label>
                    <div class="tw-dw-menu tw-w-2/4 tw-min-h-full tw-bg-white tw-p-6">

                        <div class="tw-flex tw-items-center tw-mb-16" style="background:linear-gradient(135deg,#161160,#2a2480); border-radius:12px; padding:10px 12px; color:#ffffff;">
                            <h3 class="tw-text-center tw-mx-auto tw-font-bold tw-text-base md:tw-text-2xl tw-mb-16"
                                style="margin-bottom:0;margin-top:5px;color:#ffffff;">@lang('brand.brands')</h3>
                            <button type="button" class="tw-dw-btn close-side-bar-brand" style="color:#ffffff; border-color:#ffffff; background:transparent;">
                                <i class="fa fa-times-circle" aria-hidden="true"></i>
                            </button>
                        </div>

                        <div class="row tw-mr-5">
                            @foreach ($brands as $key => $brand)
                                <div class="col-md-4 col-xs-12 tw-mb-5 tw-w-auto tw-h-auto tw-cursor-pointer product_brand no-print"
                                    data-value="{{ $key }}">
                                    <div
                                        class="tw-dw-card tw-w-25 tw-bg-base-100 tw-shadow-sm tw-h-auto tw-shadow-[rgba(22,_17,_96,_0.12)_0px_8px_24px] tw-bg-white hover:tw-bg-white/60 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-font-semibold tw-text-center tw-border-2 tw-border-white/30">
                                        <div class="tw-dw-card-body">
                                            <h4 class="tw-flex tw-items-center tw-justify-center"
                                                style="margin-bottom: 0px; margin-top:0px; font-size: inherit; font-weight: inherit;">
                                                {{ $brand }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- used in repair : filter for service/product -->
    <div class="col-md-6 hide" id="product_service_div">
        {!! Form::select(
            'is_enabled_stock',
            ['' => __('messages.all'), 'product' => __('sale.product'), 'service' => __('lang_v1.service')],
            null,
            ['id' => 'is_enabled_stock', 'class' => 'select2', 'name' => null, 'style' => 'width:100% !important'],
        ) !!}
    </div>

    <div class="col-sm-4 @if (empty($featured_products)) hide @endif" id="feature_product_div">
        <button type="button" class="btn btn-primary btn-flat"
            id="show_featured_products">@lang('lang_v1.featured_products')</button>
    </div>
</div>
<div class="row">
    <input type="hidden" id="suggestion_page" value="1">
    <div class="col-md-12">
        <div class="eq-height-row" id="product_list_body"></div>
    </div>
    <div class="col-md-12 text-center" id="suggestion_page_loader" style="display: none;">
        <i class="fa fa-spinner fa-spin fa-2x"></i>
    </div>
</div>
