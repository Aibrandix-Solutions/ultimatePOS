@extends('layouts.app')
@section('title', __('lang_v1.warranty_claims'))

@section('content')

<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.warranty_claims')</h1>
</section>

<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.warranty_claims')])
        <table class="table table-bordered table-striped" id="warranty_claims_table">
            <thead>
                <tr>
                    <th>@lang('lang_v1.date')</th>
                    <th>@lang('sale.invoice_no')</th>
                    <th>@lang('contact.customer')</th>
                    <th>@lang('product.product')</th>
                    <th>@lang('lang_v1.warranty')</th>
                    <th>@lang('lang_v1.status')</th>
                    <th>@lang('messages.action')</th>
                </tr>
            </thead>
        </table>
    @endcomponent
</section>
@stop

@section('javascript')
<script>
$(document).ready(function() {
    $('#warranty_claims_table').DataTable({
        processing: true,
        serverSide: true,
        fixedHeader: false,
        ajax: "{{ action([\App\Http\Controllers\WarrantyClaimController::class, 'index']) }}",
        columnDefs: [{
            targets: [3, 6],
            orderable: false,
            searchable: false
        }],
        columns: [
            { data: 'created_at', name: 'created_at' },
            { data: 'invoice_no', name: 'invoice_no' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'product', name: 'product' },
            { data: 'warranty_name', name: 'warranty_name' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' },
        ]
    });
});
</script>
@endsection
