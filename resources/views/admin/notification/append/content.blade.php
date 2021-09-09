<div class="modal-header">
    <h4 class="modal-title">{{ $notification->data['title'] }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <p>{{ $notification->data['content'] }}</p>
</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default btn-sm btn-flat" data-dismiss="modal">Close</button>
</div>
