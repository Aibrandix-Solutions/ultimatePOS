@extends('layouts.app')
@section('title', __('lang_v1.bulk_edit_products'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.bulk_edit_products')</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-8 col-md-offset-2 col-xs-12">
			<div class="form-group">
              	{!! Form::text('search_product', null, ['class' => 'form-control',
              'placeholder' => __('lang_v1.search_product_to_edit'), 'id' => 'search_product']) !!}
			</div>
		</div>
	</div>
	<br>
	{!! Form::open(['url' => action([\App\Http\Controllers\ProductController::class, 'bulkUpdate']), 
			'method' => 'post', 'id' => 'bulk_edit_products_form' ]) !!}
	<div class="row">
		<div class="col-md-12">
			<table class="table text-center table-bordered" id="product_table">
				<thead id="product_table_head">
					<tr class="bg-gray">
						<th class="col-md-1">@lang('sale.product')</th>
						<th class="col-md-2">@lang('product.category')</th>
						<th class="col-md-2">@lang('product.sub_category')</th>
						<th class="col-md-2">@lang('product.brand')</th>
                		<th class="col-md-2">@lang('product.tax')</th>
                		<th class="col-md-3">@lang('business.business_locations')</th>
					</tr>
				</thead>
				@foreach($products as $product)
					@include('product.partials.bulk_edit_product_row')
				@endforeach
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white pull-right">@lang('messages.update')</button>
		</div>
	</div>
	{!! Form::close() !!}
</section>
@endsection

@section('javascript')
<script type="text/javascript">

	$(document).ready( function(){
		if ($('#search_product').length) {
		    $('#search_product').autocomplete({
	            source: function(request, response) {
	                $.getJSON(
	                    '/products/list-no-variation',
	                    {
	                        term: request.term,
	                    },
	                    response
	                );
	            },
	            minLength: 2,
	            response: function(event, ui) {
	                if (ui.content.length == 0) {
	                    toastr.error(LANG.no_products_found);
	                    $('input#search_product').select();
	                }
	            },
	            select: function(event, ui) {
	                addProductRow(ui.item.product_id);
	            },
	        }).autocomplete('instance')._renderItem = function(ul, item) {
		        var string = '<li>' + item.name + ' (' + item.sku + ')' + '</li>';
	            return $(string).appendTo(ul);
	        }
	    }
	});

	function get_row_tax_details(tbody) {
		var selected_tax = tbody.find('select.row_tax').find(':selected');
		var tax_rate = parseFloat(selected_tax.data('rate'));

		return {
			amount: isNaN(tax_rate) ? 0 : tax_rate,
			type: selected_tax.data('type') || 'percentage',
		};
	}

	function add_row_tax(amount, tax_details) {
		return amount + __calculate_amount(tax_details.type, tax_details.amount, amount);
	}

	function remove_row_tax(amount_inc_tax, tax_details, round) {
		if (tax_details.type == 'fixed') {
			var amount = amount_inc_tax - tax_details.amount;
			return amount < 0 ? 0 : amount;
		}

		return __get_principle(amount_inc_tax, tax_details.amount, round);
	}

	function calculateProductPrices(tr) {
		var tbody = tr.closest('tbody.product_rows')
		var tax_details = get_row_tax_details(tbody);
        var purchase_exc_tax = __read_number(tr.find('input.pp_exc_tax'));
	    var purchase_inc_tax = add_row_tax(purchase_exc_tax, tax_details);
        __write_number(tr.find('input.pp_inc_tax'), purchase_inc_tax);

        var profit_percent = __read_number(tr.find('input.profit_percent'));
        var selling_price = __add_percent(purchase_exc_tax, profit_percent);
        __write_number(tr.find('input.sp_exc_tax'), selling_price);

	    var selling_price_inc_tax = add_row_tax(selling_price, tax_details);
        __write_number(tr.find('input.sp_inc_tax'), selling_price_inc_tax);

	}
	$(document).on('change', 'input.pp_exc_tax, input.profit_percent', function(){
		var tr = $(this).closest('tr');
		calculateProductPrices(tr);
	});
	$(document).on('change', 'select.row_tax', function(){
		var tbody = $(this).closest('tbody.product_rows');
		tbody.find('tr.variation_row').each( function(){
			calculateProductPrices($(this));
		});
	});

	$(document).on('change', 'input.pp_inc_tax', function() {
		var pp_inc_tax = __read_number($(this));
		var tr = $(this).closest('tr');
		var tbody = tr.closest('tbody.product_rows');
		var tax_details = get_row_tax_details(tbody);

	    var pp_exc_tax = remove_row_tax(pp_inc_tax, tax_details, true);
        __write_number(tr.find('input.pp_exc_tax'), pp_exc_tax);
        tr.find('input.pp_exc_tax').change();
	});

	$(document).on('change', 'input.sp_exc_tax', function() {
		var tr = $(this).closest('tr');
		var tbody = tr.closest('tbody.product_rows');
		var tax_details = get_row_tax_details(tbody);

		var sp_exc_tax = __read_number($(this));
		var purchase_exc_tax = __read_number(tr.find('input.pp_exc_tax'));
		var profit_percent = __get_rate(purchase_exc_tax, sp_exc_tax);
		__write_number(tr.find('input.profit_percent'), profit_percent);
		var selling_price_inc_tax = add_row_tax(sp_exc_tax, tax_details);
        __write_number(tr.find('input.sp_inc_tax'), selling_price_inc_tax);
	});

	$(document).on('change', 'input.sp_inc_tax', function() {
		var tr = $(this).closest('tr');
		var tbody = tr.closest('tbody.product_rows');
		var tax_details = get_row_tax_details(tbody);

		var sp_inc_tax = __read_number($(this));
		var sp_exc_tax = remove_row_tax(sp_inc_tax, tax_details);
		__write_number(tr.find('input.sp_exc_tax'), sp_exc_tax);

		var purchase_exc_tax = __read_number(tr.find('input.pp_exc_tax'));
		var profit_percent = __get_rate(purchase_exc_tax, sp_exc_tax);
		__write_number(tr.find('input.profit_percent'), profit_percent);
	});

	$(document).on('change', 'select.category_id', function() {
		var cat = $(this).val();
		var tr = $(this).closest('tr');
	    $.ajax({
	        method: 'POST',
	        url: '/products/get_sub_categories',
	        dataType: 'html',
	        data: { cat_id: cat },
	        success: function(result) {
	            if (result) {
	                tr.find('select.sub_category_id').html(result);
	            }
	        },
	    });
	});

	function addProductRow(product_id) {
		if ($('#product_' + product_id).length == 0) {
			$.ajax({
		        url: '/products/get-product-to-edit/' + product_id,
		        dataType: 'html',
		        success: function(result) {
		            if (result) {
		                $(result).insertAfter('#product_table_head');
		                $('#product_' + product_id ).find('.select2').each( function() {
		                	$(this).select2();
		                });
		            }
		        },
		    });
		}
	}
</script>
@endsection