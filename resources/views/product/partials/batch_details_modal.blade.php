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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batches as $batch)
                            <tr>
                                <td>{{ $batch->batch_number }}</td>
                                <td><span class="display_currency" data-currency_symbol="true">{{ $batch->purchase_price_inc_tax }}</span></td>
                                <td><span class="display_currency" data-currency_symbol="true">{{ $batch->batch_selling_price_inc_tax ?? 0 }}</span></td>
                                <td>{{ @format_quantity($batch->qty_in) }}</td>
                                <td>{{ @format_quantity($batch->qty_out) }}</td>
                                <td>
                                    @php
                                        $qty = (float) $batch->qty_remaining;
                                        $cls = $qty <= 0 ? 'label-danger' : ($qty < 5 ? 'label-warning' : 'label-success');
                                    @endphp
                                    <span class="label {{ $cls }}">{{ @format_quantity($qty) }}</span>
                                </td>
                                <td>{{ @format_date($batch->transaction_date) }}</td>
                                <td>{{ $batch->purchase_ref }}</td>
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
