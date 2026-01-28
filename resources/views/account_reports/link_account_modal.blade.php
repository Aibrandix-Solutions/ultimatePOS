<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ action([\App\Http\Controllers\AccountReportsController::class, 'postLinkAccount']) }}" method="POST" id="link_account_form">
      @csrf

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'account.link_account' ) - @lang( 'account.payment_ref_no' ): - {{$payment->payment_ref_no}}</h4>
      </div>

      <div class="modal-body">
          <div class="form-group">
              <input type="hidden" name="transaction_payment_id" value="{{ $payment->id }}">
              <label for="account_id">{{ __( 'account.account' ) .":" }}</label>
              <select name="account_id" id="account_id" class="form-control" required>
                @foreach($accounts as $id => $name)
                  <option value="{{ $id }}" @selected((string)old('account_id', $payment->account_id) === (string)$id)>{{ $name }}</option>
                @endforeach
              </select>
          </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white">@lang( 'messages.save' )</button>
        <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->