<!-- Edit Order tax Modal -->
<div class="modal-dialog modal-lg" role="document">
	<div class="modal-content" style="border-radius:12px; overflow:hidden; box-shadow: 0 10px 30px rgba(22,17,96,0.25);">
		<div class="modal-header" style="background:linear-gradient(135deg,#161160 0%, #2a2480 100%); color:#ffffff; border-radius:12px 12px 0 0;">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#ffffff; opacity:0.9;"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" style="color:#ffffff; font-weight:600;">@lang('lang_v1.suspended_sales')</h4>
		</div>
		<div class="modal-body" style="background:#ffffff;">
			<div class="row">
				@php
					$c = 0;
					$subtype = '';
				@endphp
				@if(!empty($transaction_sub_type))
					@php
						$subtype = '?sub_type='.$transaction_sub_type;
					@endphp
				@endif
				@forelse($sales as $sale)
					@if($sale->is_suspend)
						<div class="col-xs-6 col-sm-3">
							<div class="small-box bg-yellow">
					            <div class="inner text-center">
						            @if(!empty($sale->additional_notes))
						            	<p><i class="fa fa-edit"></i> {{$sale->additional_notes}}</p>
						            @endif
					              <p>{{$sale->invoice_no}}<br>
					              {{@format_date($sale->transaction_date)}}<br>
					              <strong><i class="fa fa-user"></i> {{$sale->name}}</strong></p>
					              <p><i class="fa fa-cubes"></i>@lang('lang_v1.total_items'): {{count($sale->sell_lines)}}<br>
					              <i class="fas fa-money-bill-alt"></i> @lang('sale.total'): <span class="display_currency" data-currency_symbol=true>{{$sale->final_total}}</span>
					              </p>
					              @if($is_tables_enabled && !empty($sale->table->name))
					              	@lang('restaurant.table'): {{$sale->table->name}}
					              @endif
					              @if($is_service_staff_enabled && !empty($sale->service_staff))
					              	<br>@lang('restaurant.service_staff'): {{$sale->service_staff->user_full_name}}
					              @endif
					            </div>
								@if(auth()->user()->can('sell.update') || auth()->user()->can('direct_sell.update'))
									<a href="{{action([\App\Http\Controllers\SellPosController::class, 'edit'], ['po' => $sale->id]).$subtype}}" class="small-box-footer bg-blue p-10">
									@lang('sale.edit_sale') <i class="fa fa-arrow-circle-right"></i>
									</a>
								@endif
								@if(auth()->user()->can('sell.delete') || auth()->user()->can('direct_sell.delete'))
									<a href="{{action([\App\Http\Controllers\SellPosController::class, 'destroy'], ['po' => $sale->id])}}" class="small-box-footer delete-sale bg-red is_suspended">
										@lang('messages.delete') <i class="fas fa-trash"></i>
					            	</a>
								@endif
								@if(!auth()->user()->can('sell.update') && auth()->user()->can('edit_pos_payment'))
									<a href="{{route('edit-pos-payment', ['id' => $sale->id])}}" 
									class="small-box-footer bg-blue p-10">
									@lang('lang_v1.add_edit_payment') <i class="fas fa-money-bill-alt"></i>
									</a>
								@endif
					         </div>
				         </div>
				        @php
				         	$c++;
				        @endphp
					@endif

					@if($c%4==0)
						<div class="clearfix"></div>
					@endif
				@empty
					<p class="text-center">@lang('purchase.no_records_found')</p>
				@endforelse
			</div>
		</div>
		<div class="modal-footer" style="background:#f8f9fa; border-top:1px solid rgba(22,17,96,0.1);">
		    <button type="button" class="btn btn-default" data-dismiss="modal" style="background:#e8ebed; color:#374151; border:1px solid #e5e7eb; padding:8px 20px; border-radius:6px; font-weight:500;">@lang('messages.close')</button>
		</div>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->