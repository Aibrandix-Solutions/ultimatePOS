@extends('layouts.app')
@section('title', __('lang_v1.warranty_register'))

@section('content')

<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.warranty_register')</h1>
</section>

<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.warranty_register')])
        <table class="table table-bordered table-striped" id="warranty_register_table">
            <thead>
                <tr>
                    <th>@lang('sale.invoice_no')</th>
                    <th>@lang('lang_v1.date')</th>
                    <th>@lang('contact.customer')</th>
                    <th>@lang('product.product')</th>
                    <th>@lang('sale.qty')</th>
                    <th>@lang('lang_v1.warranty')</th>
                    <th>@lang('lang_v1.warranty_exp_date')</th>
                    <th>@lang('messages.action')</th>
                </tr>
            </thead>
        </table>
    @endcomponent

    <div class="modal fade" id="create_warranty_claim_modal" tabindex="-1" role="dialog" aria-labelledby="create_warranty_claim_modal_label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="create_warranty_claim_modal_label">@lang('lang_v1.add_claim')</h4>
                </div>
                <form id="warranty_claim_form" action="{{ route('warranties.claims.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="transaction_id" id="wc_transaction_id">
                        <input type="hidden" name="sell_line_id" id="wc_sell_line_id">

                        <div class="form-group">
                            <label>@lang('sale.invoice_no'):</label>
                            <div id="wc_invoice_no" class="help-block"></div>
                        </div>

                        <div class="form-group">
                            <label>@lang('contact.customer'):</label>
                            <div id="wc_customer" class="help-block"></div>
                        </div>

                        <div class="form-group">
                            <label>@lang('product.product'):</label>
                            <div id="wc_product" class="help-block"></div>
                        </div>

                        <div class="form-group">
                            <label for="wc_problem">@lang('lang_v1.problem')</label>
                            <textarea class="form-control" name="problem" id="wc_problem" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="wc_notes">@lang('lang_v1.notes')</label>
                            <textarea class="form-control" name="notes" id="wc_notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
                        <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
@stop

@section('javascript')
<script>
$(document).ready(function() {
    var warranty_register_table = $('#warranty_register_table').DataTable({
        processing: true,
        serverSide: true,
        fixedHeader: false,
        ajax: "{{ action([\App\Http\Controllers\WarrantyRegisterController::class, 'index']) }}",
        columnDefs: [{
            targets: [3, 6, 7],
            orderable: false,
            searchable: false
        }],
        columns: [
            { data: 'invoice_no', name: 'invoice_no' },
            { data: 'transaction_date', name: 'transaction_date' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'product', name: 'product' },
            { data: 'quantity', name: 'quantity' },
            { data: 'warranty_name', name: 'warranty_name' },
            { data: 'warranty_exp_date', name: 'warranty_exp_date' },
            { data: 'action', name: 'action' },
        ]
    });

    $(document).on('click', '.btn-create-warranty-claim', function() {
        $('#wc_transaction_id').val($(this).data('transaction-id'));
        $('#wc_sell_line_id').val($(this).data('sell-line-id'));

        $('#wc_invoice_no').text($(this).data('invoice-no') || '');
        $('#wc_customer').text($(this).data('customer-name') || '');
        $('#wc_product').text($(this).data('product-name') || '');

        $('#wc_problem').val('');
        $('#wc_notes').val('');

        $('#create_warranty_claim_modal').modal('show');
    });

    $(document).on('submit', 'form#warranty_claim_form', function(e) {
        e.preventDefault();
        $(this).find('button[type="submit"]').attr('disabled', true);

        $.ajax({
            method: $(this).attr('method'),
            url: $(this).attr('action'),
            dataType: 'json',
            data: $(this).serialize(),
            success: function(result) {
                if (result.success == true) {
                    $('#create_warranty_claim_modal').modal('hide');
                    toastr.success(result.msg);
                    warranty_register_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
                $('form#warranty_claim_form').find('button[type="submit"]').attr('disabled', false);
            },
            error: function() {
                toastr.error("@lang('messages.something_went_wrong')");
                $('form#warranty_claim_form').find('button[type="submit"]').attr('disabled', false);
            }
        });
    });
});
</script>
@endsection
