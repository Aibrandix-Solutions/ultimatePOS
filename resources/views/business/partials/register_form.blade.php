@if(empty($is_admin))
    <div class="mb-4">
        <h3 class="h5 fw-bold text-primary border-bottom pb-2">@lang('business.business')</h3>
    </div>
@endif
{!! Form::hidden('language', request()->lang) !!}

<div class="mb-4">
    <h3 class="h5 fw-bold text-primary border-bottom pb-2">@lang('business.business_details')</h3>
    
    <div class="mb-3">
        <label class="form-label fw-semibold">@lang('business.business_name') *</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-building"></i>
            </span>
            {!! Form::text('name', null, ['class' => 'form-control','placeholder' => __('business.business_name'), 'required']) !!}
        </div>
    </div>
    
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.start_date')</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-calendar"></i>
                </span>
                {!! Form::text('start_date', null, ['class' => 'form-control start-date-picker','placeholder' => __('business.start_date'), 'autocomplete' => 'off']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.currency') *</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-currency-dollar"></i>
                </span>
                {!! Form::select('currency_id', $currencies, '', ['class' => 'form-select select2_register','placeholder' => __('business.currency_placeholder'), 'required']) !!}
            </div>
        </div>
    </div>
    
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.upload_logo')</label>
            {!! Form::file('business_logo', ['accept' => 'image/*', 'class' => 'form-control']) !!}
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('lang_v1.website')</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-globe"></i>
                </span>
                {!! Form::text('website', null, ['class' => 'form-control','placeholder' => __('lang_v1.website')]) !!}
            </div>
        </div>
    </div>
    
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('lang_v1.business_telephone')</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-telephone"></i>
                </span>
                {!! Form::text('mobile', null, ['class' => 'form-control','placeholder' => __('lang_v1.business_telephone')]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.alternate_number')</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-telephone"></i>
                </span>
                {!! Form::text('alternate_number', null, ['class' => 'form-control','placeholder' => __('business.alternate_number')]) !!}
            </div>
        </div>
    </div>

    
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.country')</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-globe"></i>
                </span>
                {!! Form::text('country', null, ['class' => 'form-control','placeholder' => __('business.country')]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.state')</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-geo-alt"></i>
                </span>
                {!! Form::text('state', null, ['class' => 'form-control','placeholder' => __('business.state')]) !!}
            </div>
        </div>
    </div>
    
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.city')</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-geo-alt"></i>
                </span>
                {!! Form::text('city', null, ['class' => 'form-control','placeholder' => __('business.city')]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.zip_code')</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-mailbox"></i>
                </span>
                {!! Form::text('zip_code', null, ['class' => 'form-control','placeholder' => __('business.zip_code_placeholder')]) !!}
            </div>
        </div>
    </div>
    
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.landmark')</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-geo-alt"></i>
                </span>
                {!! Form::text('landmark', null, ['class' => 'form-control','placeholder' => __('business.landmark')]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.time_zone') *</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-clock"></i>
                </span>
                {!! Form::select('time_zone', $timezone_list, config('app.timezone'), ['class' => 'form-select select2_register','placeholder' => __('business.time_zone'), 'required']) !!}
            </div>
        </div>
    </div>
</div>

<!-- Business Settings -->
@if(empty($is_admin))
    <div class="mb-4">
        <h3 class="h5 fw-bold text-primary border-bottom pb-2">@lang('business.business_settings')</h3>
        
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">@lang('business.tax_1_name')</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-info-circle"></i>
                    </span>
                    {!! Form::text('tax_label_1', null, ['class' => 'form-control','placeholder' => __('business.tax_1_placeholder')]) !!}
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">@lang('business.tax_1_no')</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-info-circle"></i>
                    </span>
                    {!! Form::text('tax_number_1', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">@lang('business.tax_2_name')</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-info-circle"></i>
                    </span>
                    {!! Form::text('tax_label_2', null, ['class' => 'form-control','placeholder' => __('business.tax_1_placeholder')]) !!}
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">@lang('business.tax_2_no')</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-info-circle"></i>
                    </span>
                    {!! Form::text('tax_number_2', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">@lang('business.fy_start_month') * @show_tooltip(__('tooltip.fy_start_month'))</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-calendar"></i>
                    </span>
                    {!! Form::select('fy_start_month', $months, null, ['class' => 'form-select select2_register', 'required']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">@lang('business.accounting_method') *</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-calculator"></i>
                    </span>
                    {!! Form::select('accounting_method', $accounting_methods, null, ['class' => 'form-select select2_register', 'required']) !!}
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Owner Information -->
@if(empty($is_admin))
    <div class="mb-4">
        <h3 class="h5 fw-bold text-primary border-bottom pb-2">@lang('business.owner')</h3>
    </div>
@endif

<div class="mb-4">
    <h3 class="h5 fw-bold text-primary border-bottom pb-2">@lang('business.owner_info')</h3>
    
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold">@lang('business.prefix')</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person"></i>
                </span>
                {!! Form::text('surname', null, ['class' => 'form-control','placeholder' => __('business.prefix_placeholder')]) !!}
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">@lang('business.first_name') *</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person"></i>
                </span>
                {!! Form::text('first_name', null, ['class' => 'form-control','placeholder' => __('business.first_name'), 'required']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">@lang('business.last_name')</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person"></i>
                </span>
                {!! Form::text('last_name', null, ['class' => 'form-control','placeholder' =>  __('business.last_name')]) !!}
            </div>
        </div>
    </div>
    
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.username') *</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person-circle"></i>
                </span>
                {!! Form::text('username', null, ['class' => 'form-control','placeholder' => __('business.username'), 'required']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.email')</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                </span>
                {!! Form::text('email', null, ['class' => 'form-control','placeholder' => __('business.email')]) !!}
            </div>
        </div>
    </div>
    
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.password') *</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                </span>
                {!! Form::password('password', ['class' => 'form-control','placeholder' => __('business.password'), 'required']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">@lang('business.confirm_password') *</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                </span>
                {!! Form::password('confirm_password', ['class' => 'form-control','placeholder' => __('business.confirm_password'), 'required']) !!}
            </div>
        </div>
    </div>
    
    @if(!empty($system_settings['superadmin_enable_register_tc']) && !empty($is_register))
        <div class="form-check mb-3">
            {!! Form::checkbox('accept_tc', 0, false, ['required', 'class' => 'form-check-input', 'id' => 'accept_tc']) !!}
            <label class="form-check-label" for="accept_tc">
                <a class="terms_condition cursor-pointer" data-bs-toggle="modal" data-bs-target="#tc_modal">
                    @lang('lang_v1.accept_terms_and_conditions')
                </a>
            </label>
        </div>
        @include('business.partials.terms_conditions')
    @endif

    @if(config('constants.enable_recaptcha') && !empty($is_register))
        <div class="mb-3">
            <div id="recaptcha-container"></div>
            @if ($errors->has('g-recaptcha-response'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('g-recaptcha-response') }}
                </div>
            @endif
        </div>
    @endif
</div>

@if(config('constants.enable_recaptcha') && !empty($is_register))
    <script>
        window.RECAPTCHA_SITE_KEY = "{{ config('constants.google_recaptcha_key') }}";
    </script>
@endif
