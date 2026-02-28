<!-- Edit Cash In Hand Modal -->
<div class="modal fade" id="editCashInHandModal" tabindex="-1" role="dialog" aria-labelledby="editCashInHandModalLabel" style="z-index: 1060;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="editCashInHandModalLabel">Edit Cash in Hand</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
        <form id="editCashInHandForm" method="POST" action="{{ route('cash_register.update_cash_in_hand', $register_details->id) }}">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label for="cash_in_hand_amount">New Cash in Hand Amount</label>
              <input type="number" step="0.01" min="0" class="form-control" id="cash_in_hand_amount" name="cash_in_hand_amount" value="{{ $register_details->cash_in_hand }}" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
        <script>
          $(document).ready(function() {
            $('#editCashInHandForm').on('submit', function(e) {
              e.preventDefault();
              var form = $(this);
              var url = form.attr('action');
              var data = form.serialize();
              $.post(url, data, function(response) {
                $('#editCashInHandModal').modal('hide');
                // Reload the modal that triggered it (Register details or close register)
                $.get(window.location.href, function(page) {
                  if ($('.register_details_modal').length) {
                      var newContent = $(page).find('.register_details_modal').html();
                      $('.register_details_modal').html(newContent);
                  }
                  if ($('.close_register_modal').length) {
                      var newContent = $(page).find('.close_register_modal').html();
                      $('.close_register_modal').html(newContent);
                  }
                });
              });
            });
          });
        </script>
    </div>
  </div>
</div>
