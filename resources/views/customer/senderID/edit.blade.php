<form method="post" role="form" id="updateForm" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="form-group">
        <label for="sender_id">@lang('customer.sender_id') *</label>
        <input type="text" name="sender_id"
               class="form-control" id="sender_id_edit"
               placeholder="@lang('customer.sender_id')">
    </div>
    <div class="modal-footer p-2">
        <button id="modal-confirm-btn" type="submit" class="btn btn-primary btn-sm">@lang('customer.submit')</button>
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">@lang('customer.close')</button>
    </div>
</form>
