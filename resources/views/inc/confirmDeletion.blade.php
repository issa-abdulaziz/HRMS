<div class="modal fade" id="confirmDeletionModal" tabindex="-1" role="dialog"
  aria-labelledby="confirmDeletionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-capitalize" id="confirmDeletionModalLabel">Deleting {{ $title }} !!!
        </h5>
        <input type="hidden" id="modalTitle" value="{{ $title }}">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <form id="deletionForm" method="POST">
          @csrf
          @method('DELETE')
          <button id="delete" class="btn btn-danger" type="submit">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
