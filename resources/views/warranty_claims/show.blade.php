@extends('layouts.app')
@section('title', __('lang_v1.warranty_claim'))

@section('content')
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.warranty_claim') #{{ $warranty_claim->id }}</h1>
</section>

<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.details')])
        <div class="row">
            <div class="col-sm-4">
                <strong>@lang('sale.invoice_no'):</strong>
                <div>{{ optional($warranty_claim->transaction)->invoice_no }}</div>
            </div>
            <div class="col-sm-4">
                <strong>@lang('lang_v1.date'):</strong>
                <div>{{ !empty(optional($warranty_claim->transaction)->transaction_date) ? \Carbon\Carbon::parse($warranty_claim->transaction->transaction_date)->format('Y-m-d H:i') : '' }}</div>
            </div>
            <div class="col-sm-4">
                <strong>@lang('contact.customer'):</strong>
                <div>{{ optional($warranty_claim->customer)->name }}</div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <strong>@lang('product.product'):</strong>
                <div>
                    {{ optional(optional($warranty_claim->sell_line)->product)->name }}
                    @if(!empty(optional(optional($warranty_claim->sell_line)->variations)->name) && optional(optional($warranty_claim->sell_line)->variations)->name !== 'DUMMY')
                        - {{ $warranty_claim->sell_line->variations->name }}
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <strong>@lang('lang_v1.warranty'):</strong>
                <div>{{ optional(optional($warranty_claim->sell_line)->warranties->first())->name }}</div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <strong>@lang('lang_v1.status'):</strong>
                <div>{{ $warranty_claim->status }}</div>
            </div>
            <div class="col-sm-6">
                <strong>@lang('report.supplier'):</strong>
                <div>{{ optional($warranty_claim->supplier)->name }}</div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <strong>@lang('lang_v1.problem'):</strong>
                <div>{{ $warranty_claim->problem }}</div>
            </div>
            <div class="col-sm-6">
                <strong>@lang('lang_v1.notes'):</strong>
                <div>{{ $warranty_claim->notes }}</div>
            </div>
        </div>
    @endcomponent

    @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.update_status')])
        @if(!empty($next_statuses))
            <form id="warranty_claim_status_form" action="{{ route('warranties.claims.status', [$warranty_claim->id]) }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="to_status">@lang('lang_v1.status')</label>
                            <select name="to_status" id="to_status" class="form-control">
                                @foreach($next_statuses as $st)
                                    <option value="{{ $st }}">{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4" id="supplier_wrap" style="display:none;">
                        <div class="form-group">
                            <label for="supplier_id">@lang('report.supplier')</label>
                            <select name="supplier_id" id="supplier_id" class="form-control" style="width:100%"></select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="note">@lang('lang_v1.note')</label>
                            <input type="text" name="note" id="note" class="form-control" />
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
            </form>
        @else
            <p>@lang('lang_v1.no_further_actions')</p>
        @endif
    @endcomponent

    @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.timeline')])
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>@lang('lang_v1.date')</th>
                    <th>@lang('lang_v1.status')</th>
                    <th>@lang('lang_v1.note')</th>
                    <th>@lang('lang_v1.user')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($warranty_claim->status_logs as $log)
                    <tr>
                        <td>{{ !empty($log->created_at) ? \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i') : '' }}</td>
                        <td>{{ $log->to_status }}</td>
                        <td>{{ $log->note }}</td>
                        <td>{{ optional($log->created_by_user)->first_name }} {{ optional($log->created_by_user)->last_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endcomponent
</section>
@stop

@section('javascript')
<script>
$(document).ready(function() {
    function toggleSupplier() {
        var val = $('#to_status').val();
        if (val === '{{ \App\WarrantyClaim::STATUS_SENT_TO_SUPPLIER }}') {
            $('#supplier_wrap').show();
        } else {
            $('#supplier_wrap').hide();
        }
    }

    $('#to_status').on('change', toggleSupplier);
    toggleSupplier();

    $('#supplier_id').select2({
        ajax: {
            url: '{{ action([\App\Http\Controllers\PurchaseController::class, 'getSuppliers']) }}',
            dataType: 'json',
            delay: 250,
            data: function(params) { return { q: params.term }; },
            processResults: function(data) { return { results: data }; },
        },
        minimumInputLength: 1,
        width: '100%'
    });

    $(document).on('submit', 'form#warranty_claim_status_form', function(e) {
        e.preventDefault();
        var $form = $(this);
        $form.find('button[type="submit"]').attr('disabled', true);

        $.ajax({
            method: $form.attr('method'),
            url: $form.attr('action'),
            dataType: 'json',
            data: $form.serialize(),
            success: function(result) {
                if (result.success == true) {
                    toastr.success(result.msg);
                    setTimeout(function(){ location.reload(); }, 400);
                } else {
                    toastr.error(result.msg);
                    $form.find('button[type="submit"]').attr('disabled', false);
                }
            },
            error: function() {
                toastr.error("@lang('messages.something_went_wrong')");
                $form.find('button[type="submit"]').attr('disabled', false);
            }
        });
    });
});
</script>
@endsection
