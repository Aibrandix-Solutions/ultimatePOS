<!--Purchase related settings -->
<div class="pos-tab-content">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('default_credit_limit',__('lang_v1.default_credit_limit') . ':') !!}
                {!! Form::text('common_settings[default_credit_limit]', $common_settings['default_credit_limit'] ?? '', ['class' => 'form-control input_number',
                'placeholder' => __('lang_v1.default_credit_limit'), 'id' => 'default_credit_limit']) !!}
                
                <div class="credit-limit-display" style="margin-top: 8px; padding: 8px 12px; background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 4px; font-size: 13px; color: #495057;">
                    <i class="fas fa-info-circle" style="color: #6c757d; margin-right: 5px;"></i>
                    <span class="credit-limit-text">
                        @if(!empty($common_settings['default_credit_limit']))
                            @lang('lang_v1.current_default_limit'): <strong>{{ @num_format($common_settings['default_credit_limit']) }}</strong>
                        @else
                            @lang('lang_v1.no_default_limit_set')
                        @endif
                    </span>
                </div>
                <p class="help-block">@lang('lang_v1.default_credit_limit_help')</p>
            </div>
        </div>
    </div>
</div>