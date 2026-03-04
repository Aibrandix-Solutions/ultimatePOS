<!-- Edit Cash In Hand Modal -->
<div class="modal fade" id="editCashInHandModal" tabindex="-1" role="dialog" aria-labelledby="editCashInHandModalLabel" data-backdrop="false" style="z-index: 1060;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="editCashInHandModalLabel">Edit Cash in Hand</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="cash_in_hand_amount">New Cash in Hand Amount</label>
          <input type="number" step="0.01" min="0" class="form-control" id="cash_in_hand_amount" name="cash_in_hand_amount" value="{{ $register_details->cash_in_hand }}" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" 
          onclick="submitCashInHandUpdate('{{ route('cash_register.update_cash_in_hand', $register_details->id) }}', '{{ csrf_token() }}')">
          Update
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function submitCashInHandUpdate(url, token) {
    var amount = document.getElementById('cash_in_hand_amount').value;
    if (!amount) {
        alert('Please enter an amount.');
        return;
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: token,
            cash_in_hand_amount: amount
        },
        success: function(response) {
            if (response.success) {
                $('#editCashInHandModal').modal('hide');
                // Show success notification
                toastr.success(response.msg || 'Cash in hand updated successfully!');
                // Reload page after short delay so user sees notification
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            } else {
                alert(response.msg || 'Update failed.');
            }
        },
        error: function(xhr) {
            var msg = 'An error occurred.';
            if (xhr.responseJSON && xhr.responseJSON.msg) {
                msg = xhr.responseJSON.msg;
            }
            alert(msg);
        }
    });
}
</script>
