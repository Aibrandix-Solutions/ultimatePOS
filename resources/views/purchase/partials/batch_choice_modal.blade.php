{{-- Shown when adding a product that already has numbered batches: user can add a new batch OR a standard restock (variation default pricing, no batch label). --}}
<div class="modal fade" tabindex="-1" role="dialog" id="purchase_batch_choice_modal" aria-labelledby="purchase_batch_choice_modal_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="purchase_batch_choice_modal_label">@lang('lang_v1.product_already_has_batches')</h4>
            </div>
            <div class="modal-body">
                <p>@lang('lang_v1.purchase_batch_choice_intro')</p>
                <p class="text-muted small" id="purchase_batch_choice_next"></p>
                <div id="purchase_batch_choice_list_wrap" style="display:none;">
                    <strong>@lang('lang_v1.existing_batches')</strong>
                    <ul class="small" id="purchase_batch_choice_list"></ul>
                </div>
                <div id="purchase_batch_refill_wrap" style="display:none; margin-top:12px;">
                    <label for="purchase_batch_refill_select">@lang('lang_v1.refill_batch_select_label')</label>
                    <select id="purchase_batch_refill_select" class="form-control"></select>
                    <p class="help-block small text-muted">@lang('lang_v1.refill_batch_help')</p>
                </div>
            </div>
            <div class="modal-footer" style="display:flex; flex-wrap:wrap; gap:8px; justify-content:flex-end;">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="purchase_batch_choice_cancel">@lang('messages.cancel')</button>
                <button type="button" class="btn btn-info" id="purchase_batch_choice_refill" style="display:none;">@lang('lang_v1.refill_selected_batch')</button>
                <button type="button" class="btn btn-primary" id="purchase_batch_choice_standard">@lang('lang_v1.standard_restock_no_batch')</button>
                <button type="button" class="btn btn-success" id="purchase_batch_choice_new">@lang('lang_v1.add_as_new_batch')</button>
            </div>
        </div>
    </div>
</div>
