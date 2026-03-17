@extends('layouts.app')
@section('title', __('lang_v1.add_damage'))

@section('content')

<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.add_damage')</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-exclamation-triangle"></i> @lang('messages.add')
                        @lang('lang_v1.damages')</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ action([\App\Http\Controllers\DamageController::class, 'list']) }}"
                            class="btn btn-sm btn-default">
                            <i class="fa fa-list"></i> @lang('lang_v1.damages_list')
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <form id="damage_add_form" method="POST"
                        action="{{ action([\App\Http\Controllers\DamageController::class, 'store']) }}">
                        @csrf

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fa fa-cube text-primary"></i> @lang('product.product') <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="product_search" name="product_search"
                                        class="form-control input-lg" autocomplete="off" required
                                        placeholder="{{ __('product.product') }} (type to search)">
                                    <input type="hidden" id="product_id" name="product_id" value="">
                                    <div id="product_suggestions" class="panel panel-default"
                                        style="position: absolute; z-index: 9999; display:none; max-height:250px; overflow:auto;">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><i class="fa fa-sliders text-info"></i> @lang('product.variation') <small
                                            class="text-muted">(@lang('lang_v1.optional'))</small></label>
                                    <input type="text" name="variation_id" class="form-control"
                                        placeholder="{{ __('product.variation') }} (ID)">
                                </div>

                                <div class="form-group">
                                    <label><i class="fa fa-map-marker text-success"></i> @lang('business.location')
                                        <small class="text-muted">(@lang('lang_v1.optional'))</small></label>
                                    <input type="text" name="location_id" class="form-control"
                                        placeholder="{{ __('business.location') }} (ID)">
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fa fa-calculator text-warning"></i> @lang('sale.qty') <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="quantity" class="form-control input-lg" required
                                        placeholder="{{ __('sale.qty') }}">
                                </div>

                                <div class="form-group">
                                    <label><i class="fa fa-money text-success"></i>
                                        @lang('product.default_purchase_price') <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="unit_cost" class="form-control" required
                                        placeholder="{{ __('product.default_purchase_price') }}">
                                </div>

                                <div class="form-group">
                                    <label><i class="fa fa-comment text-muted"></i> @lang('expense.expense_note') <small
                                            class="text-muted">(@lang('lang_v1.optional'))</small></label>
                                    <textarea name="reason" class="form-control" rows="3"
                                        placeholder="{{ __('lang_v1.reason') }}"></textarea>
                                </div>

                                <div class="form-group">
                                    <label><i class="fa fa-money text-primary"></i> Payment Action <span class="text-danger">*</span></label>
                                    <select name="payment_method" id="damage_payment_method" class="form-control input-lg" required>
                                        <option value="due">Due</option>
                                        @foreach($payment_types as $payment_type_key => $payment_type_label)
                                            <option value="{{ $payment_type_key }}">{{ $payment_type_label }}</option>
                                        @endforeach
                                    </select>
                                    <p class="help-block">Choose <strong>Due</strong> to record only the damage expense. Choose a payment method to affect register immediately.</p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary btn-lg" type="submit">
                                    <i class="fa fa-save"></i> @lang('messages.add')
                                </button>
                                <a href="{{ action([\App\Http\Controllers\DamageController::class, 'list']) }}"
                                    class="btn btn-default btn-lg">
                                    <i class="fa fa-times"></i> @lang('messages.cancel')
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            // Helper: debounce
            function debounce(fn, delay) {
                var timer = null;
                return function () {
                    var context = this, args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        fn.apply(context, args);
                    }, delay);
                };
            }

            // Product autocomplete
            var $search = $('#product_search');
            var $hiddenId = $('#product_id');
            var $suggest = $('#product_suggestions');

            $search.on('input', debounce(function () {
                var q = $(this).val();
                $hiddenId.val('');
                if (!q || q.length < 1) {
                    $suggest.hide();
                    return;
                }

                $.getJSON('/damages/products/search', { q: q }, function (data) {
                    if (!data || data.length === 0) {
                        $suggest.hide();
                        return;
                    }

                    var html = '';
                    data.forEach(function (item) {
                        html += '<div class="suggest-item" data-id="' + item.id + '" style="padding:6px;cursor:pointer;border-bottom:1px solid #eee;">' + item.text + '</div>';
                    });
                    $suggest.html(html).show();

                    $('.suggest-item').on('click', function () {
                        var id = $(this).data('id');
                        var text = $(this).text();
                        $hiddenId.val(id);
                        $search.val(text);
                        $suggest.hide();

                        // Fetch product details and prefill other fields
                        $.getJSON('/damages/products/' + id + '/details', function (info) {
                            if (!info) return;
                            if (info.default_variation_id) {
                                $('input[name="variation_id"]').val(info.default_variation_id);
                            }
                            if (info.default_location_id) {
                                $('input[name="location_id"]').val(info.default_location_id);
                            }
                            if (info.unit_cost !== null && info.unit_cost !== undefined) {
                                $('input[name="unit_cost"]').val(parseFloat(info.unit_cost));
                            }
                            // set default qty to 1
                            if (!$('input[name="quantity"]').val()) {
                                $('input[name="quantity"]').val(1);
                            }
                        }).fail(function () {
                            // ignore
                        });
                    });
                });
            }, 250));

            // Hide suggestions on click outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#product_suggestions, #product_search').length) {
                    $suggest.hide();
                }
            });

            $('#damage_add_form').on('submit', function (e) {
                e.preventDefault();
                var $form = $(this);
                var url = $form.attr('action');
                var data = $form.serialize();

                $.ajax({
                    method: 'POST',
                    url: url,
                    data: data,
                    success: function (res) {
                        if (res.success) {
                            toastr.success(res.msg || '@lang('messages.added_success')');
                            // Redirect to damages list
                            window.location.href = '{{ action([\App\Http\Controllers\DamageController::class, "list"]) }}';
                        } else {
                            toastr.error('Could not add damage.');
                        }
                    },
                    error: function (xhr) {
                        var msg = 'Error saving damage';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errs = xhr.responseJSON.errors;
                            msg = Object.keys(errs).map(function (k) { return errs[k].join(', '); }).join('\n');
                        }
                        toastr.error(msg);
                    }
                });
            });
        });
    </script>
@endsection