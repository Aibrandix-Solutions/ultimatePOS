<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('lang_v1.batch_details'): {{ $product->name }}</h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>@lang('lang_v1.batch_number')</th>
                            <th>@lang('lang_v1.purchase_price_inc_tax')</th>
                            <th>@lang('lang_v1.selling_price_inc_tax')</th>
                            <th>@lang('lang_v1.qty_in')</th>
                            <th>@lang('lang_v1.qty_out')</th>
                            <th>@lang('lang_v1.remaining_stock')</th>
                            <th>@lang('purchase.purchase_date')</th>
                            <th>@lang('purchase.ref_no')</th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batches as $batch)
                            <tr>
                                <td data-label="@lang('lang_v1.batch_number')">{{ $batch->batch_number }}</td>
                                <td data-label="@lang('lang_v1.purchase_price_inc_tax')">
                                    <span class="display_currency" data-currency_symbol="true">
                                        {{ !empty($product->tax) ? $batch->purchase_price_inc_tax : $batch->purchase_price }}
                                    </span>
                                </td>
                                <td data-label="@lang('lang_v1.selling_price_inc_tax')"><span class="display_currency" data-currency_symbol="true">{{ $batch->batch_selling_price_inc_tax ?? 0 }}</span></td>
                                <td data-label="@lang('lang_v1.qty_in')">{{ @format_quantity($batch->qty_in) }}</td>
                                <td data-label="@lang('lang_v1.qty_out')">{{ @format_quantity($batch->qty_out) }}</td>
                                <td data-label="@lang('lang_v1.remaining_stock')">
                                    @php
                                        $qty = (float) $batch->qty_remaining;
                                        $cls = $qty <= 0 ? 'label-danger' : ($qty < 5 ? 'label-warning' : 'label-success');
                                    @endphp
                                    <span class="label {{ $cls }}">{{ @format_quantity($qty) }}</span>
                                </td>
                                <td data-label="@lang('purchase.purchase_date')">{{ @format_date($batch->transaction_date) }}</td>
                                <td data-label="@lang('purchase.ref_no')">{{ $batch->purchase_ref }}</td>
                                <td data-label="@lang('messages.action')">
                                    <button type="button" class="btn btn-xs btn-primary btn-modal" data-href="{{ action([\App\Http\Controllers\ProductBatchController::class, 'edit'], [$batch->id]) }}" data-container="#edit_batch_modal">
                                        <i class="fa fa-edit"></i> @lang('messages.edit')
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>
