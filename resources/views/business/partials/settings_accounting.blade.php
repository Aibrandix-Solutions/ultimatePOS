<!-- Accounting related settings -->
<div class="pos-tab-content">
    <div class="row">
        <?php
            $accounts_raw = \App\Account::where('business_id', $business->id)->where('is_closed', 0)->get();
            $accounts_dropdown = ['' => __('lang_v1.none')];
            foreach ($accounts_raw as $acct) {
                $accounts_dropdown[$acct->id] = $acct->name;
            }
        ?>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('damage_writeoff_account_id', __('Damages (write-off) Account') . ':') !!}
                {!! Form::select('common_settings[damage_writeoff_account_id]', $accounts_dropdown, !empty($common_settings['damage_writeoff_account_id']) ? $common_settings['damage_writeoff_account_id'] : null, ['class' => 'form-control select2', 'style' => 'width:100%;']) !!}
                <p class="help-block">Select the account to record inventory write-offs for damaged goods.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('inventory_account_id', __('Inventory Account') . ':') !!}
                {!! Form::select('common_settings[inventory_account_id]', $accounts_dropdown, !empty($common_settings['inventory_account_id']) ? $common_settings['inventory_account_id'] : null, ['class' => 'form-control select2', 'style' => 'width:100%;']) !!}
                <p class="help-block">Select the inventory asset account. Used to credit when writing off damaged stock.</p>
            </div>
        </div>
    </div>
</div>

@section('javascript')
@parent
<script>
    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2').select2({ width: '100%' });
        }
    });
</script>
@endsection
