@if(empty($is_admin))
    <div class="form-section">
        <h3 class="section-title">@lang('business.business')</h3>
    </div>
@endif
{!! Form::hidden('language', request()->lang); !!}

<div class="form-section">
    <h3 class="section-title">@lang('business.business_details')</h3>
    
    <div class="form-group">
        <label class="form-label">@lang('business.business_name') *</label>
        <div class="input-group">
            <i class="fa fa-suitcase input-icon"></i>
            {!! Form::text('name', null, ['class' => 'form-input','placeholder' => __('business.business_name'), 'required']); !!}
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">@lang('business.start_date')</label>
            <div class="input-group">
                <i class="fa fa-calendar input-icon"></i>
                {!! Form::text('start_date', null, ['class' => 'form-input start-date-picker','placeholder' => __('business.start_date'), 'readonly']); !!}
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">@lang('business.currency') *</label>
            <div class="input-group">
                <i class="fas fa-money-bill-alt input-icon"></i>
                {!! Form::select('currency_id', $currencies, '', ['class' => 'form-select select2_register','placeholder' => __('business.currency_placeholder'), 'required']); !!}
            </div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">@lang('business.upload_logo')</label>
            {!! Form::file('business_logo', ['accept' => 'image/*', 'class' => 'form-input']); !!}
        </div>
        <div class="form-group">
            <label class="form-label">@lang('lang_v1.website')</label>
            <div class="input-group">
                <i class="fa fa-globe input-icon"></i>
                {!! Form::text('website', null, ['class' => 'form-input','placeholder' => __('lang_v1.website')]); !!}
            </div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">@lang('lang_v1.business_telephone')</label>
            <div class="input-group">
                <i class="fa fa-phone input-icon"></i>
                {!! Form::text('mobile', null, ['class' => 'form-input','placeholder' => __('lang_v1.business_telephone')]); !!}
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">@lang('business.alternate_number')</label>
            <div class="input-group">
                <i class="fa fa-phone input-icon"></i>
                {!! Form::text('alternate_number', null, ['class' => 'form-input','placeholder' => __('business.alternate_number')]); !!}
            </div>
        </div>
    </div>

    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">@lang('business.country') *</label>
            <div class="input-group">
                <i class="fa fa-globe input-icon"></i>
                {!! Form::text('country', null, ['class' => 'form-input','placeholder' => __('business.country'), 'required']); !!}
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">@lang('business.state') *</label>
            <div class="input-group">
                <i class="fa fa-map-marker input-icon"></i>
                {!! Form::text('state', null, ['class' => 'form-input','placeholder' => __('business.state'), 'required']); !!}
            </div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">@lang('business.city') *</label>
            <div class="input-group">
                <i class="fa fa-map-marker input-icon"></i>
                {!! Form::text('city', null, ['class' => 'form-input','placeholder' => __('business.city'), 'required']); !!}
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">@lang('business.zip_code') *</label>
            <div class="input-group">
                <i class="fa fa-map-marker input-icon"></i>
                {!! Form::text('zip_code', null, ['class' => 'form-input','placeholder' => __('business.zip_code_placeholder'), 'required']); !!}
            </div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">@lang('business.landmark') *</label>
            <div class="input-group">
                <i class="fa fa-map-marker input-icon"></i>
                {!! Form::text('landmark', null, ['class' => 'form-input','placeholder' => __('business.landmark'), 'required']); !!}
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">@lang('business.time_zone') *</label>
            <div class="input-group">
                <i class="fas fa-clock input-icon"></i>
                {!! Form::select('time_zone', $timezone_list, config('app.timezone'), ['class' => 'form-select select2_register','placeholder' => __('business.time_zone'), 'required']); !!}
            </div>
        </div>
    </div>
</div>

<!-- Business Settings -->
@if(empty($is_admin))
    <div class="form-section">
        <h3 class="section-title">@lang('business.business_settings')</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">@lang('business.tax_1_name')</label>
                <div class="input-group">
                    <i class="fa fa-info input-icon"></i>
                    {!! Form::text('tax_label_1', null, ['class' => 'form-input','placeholder' => __('business.tax_1_placeholder')]); !!}
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">@lang('business.tax_1_no')</label>
                <div class="input-group">
                    <i class="fa fa-info input-icon"></i>
                    {!! Form::text('tax_number_1', null, ['class' => 'form-input']); !!}
                </div>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">@lang('business.tax_2_name')</label>
                <div class="input-group">
                    <i class="fa fa-info input-icon"></i>
                    {!! Form::text('tax_label_2', null, ['class' => 'form-input','placeholder' => __('business.tax_1_placeholder')]); !!}
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">@lang('business.tax_2_no')</label>
                <div class="input-group">
                    <i class="fa fa-info input-icon"></i>
                    {!! Form::text('tax_number_2', null, ['class' => 'form-input']); !!}
                </div>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">@lang('business.fy_start_month') * @show_tooltip(__('tooltip.fy_start_month'))</label>
                <div class="input-group">
                    <i class="fa fa-calendar input-icon"></i>
                    {!! Form::select('fy_start_month', $months, null, ['class' => 'form-select select2_register', 'required']); !!}
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">@lang('business.accounting_method') *</label>
                <div class="input-group">
                    <i class="fa fa-calculator input-icon"></i>
                    {!! Form::select('accounting_method', $accounting_methods, null, ['class' => 'form-select select2_register', 'required']); !!}
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Owner Information -->
@if(empty($is_admin))
    <div class="form-section">
        <h3 class="section-title">@lang('business.owner')</h3>
    </div>
@endif

<div class="form-section">
    <h3 class="section-title">@lang('business.owner_info')</h3>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">@lang('business.prefix')</label>
            <div class="input-group">
                <i class="fa fa-info input-icon"></i>
                {!! Form::text('surname', null, ['class' => 'form-input','placeholder' => __('business.prefix_placeholder')]); !!}
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">@lang('business.first_name') *</label>
            <div class="input-group">
                <i class="fa fa-info input-icon"></i>
                {!! Form::text('first_name', null, ['class' => 'form-input','placeholder' => __('business.first_name'), 'required']); !!}
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">@lang('business.last_name')</label>
            <div class="input-group">
                <i class="fa fa-info input-icon"></i>
                {!! Form::text('last_name', null, ['class' => 'form-input','placeholder' =>  __('business.last_name')]); !!}
            </div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">@lang('business.username') *</label>
            <div class="input-group">
                <i class="fa fa-user input-icon"></i>
                {!! Form::text('username', null, ['class' => 'form-input','placeholder' => __('business.username'), 'required']); !!}
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">@lang('business.email') *</label>
            <div class="input-group">
                <i class="fa fa-envelope input-icon"></i>
                {!! Form::text('email', null, ['class' => 'form-input','placeholder' => __('business.email'), 'required']); !!}
            </div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">@lang('business.password') *</label>
            <div class="input-group">
                <i class="fa fa-lock input-icon"></i>
                {!! Form::password('password', ['class' => 'form-input','placeholder' => __('business.password'), 'required']); !!}
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">@lang('business.confirm_password') *</label>
            <div class="input-group">
                <i class="fa fa-lock input-icon"></i>
                {!! Form::password('confirm_password', ['class' => 'form-input','placeholder' => __('business.confirm_password'), 'required']); !!}
            </div>
        </div>
    </div>
    
    @if(!empty($system_settings['superadmin_enable_register_tc']) && !empty($is_register))
        <div class="checkbox-container">
            {!! Form::checkbox('accept_tc', 0, false, ['required', 'class' => 'checkbox-input']); !!}
            <label class="checkbox-label">
                <a class="terms_condition cursor-pointer" data-toggle="modal" data-target="#tc_modal">
                    @lang('lang_v1.accept_terms_and_conditions')
                </a>
            </label>
        </div>
        @include('business.partials.terms_conditions')
    @endif

    @if(config('constants.enable_recaptcha') && !empty($is_register))
        <div class="form-group">
            <div id="recaptcha-container"></div>
            @if ($errors->has('g-recaptcha-response'))
                <span style="color:#dc3545; font-size:.8rem; margin-top:.25rem; display:block;">
                    {{ $errors->first('g-recaptcha-response') }}
                </span>
            @endif
        </div>
    @endif
</div>

@if(config('constants.enable_recaptcha') && !empty($is_register))
    <script>
        window.RECAPTCHA_SITE_KEY = "{{ config('constants.google_recaptcha_key') }}";
    </script>
@endif
