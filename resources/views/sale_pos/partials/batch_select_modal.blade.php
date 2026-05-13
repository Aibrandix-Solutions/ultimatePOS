<div class="modal fade" tabindex="-1" role="dialog" id="pos_batch_select_modal" aria-labelledby="pos_batch_select_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="pos_batch_select_modal_label">
                    @lang('lang_v1.select_price_for_product')
                </h4>
                <small class="text-muted" id="pos_batch_select_product_name"></small>
            </div>
            <div class="modal-body">
                <div id="pos_batch_select_loading" class="text-center" style="display:none;">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="pos_batch_select_table" style="display:none;">
                        <thead>
                            <tr>
                                <th style="width:60px;">#</th>
                                <th>@lang('lang_v1.batch_number')</th>
                                <th>@lang('lang_v1.selling_price_inc_tax')</th>
                                <th>@lang('lang_v1.available_stock')</th>
                                <th>@lang('purchase.purchase_date')</th>
                                <th style="width:90px;"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div id="pos_batch_select_empty" class="text-center text-muted" style="display:none; padding:24px;">
                    @lang('lang_v1.no_records_found')
                </div>
            </div>
        </div>
    </div>
</div>
