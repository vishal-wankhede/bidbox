{{-- Add Filter Modal --}}
<div class="modal fade" id="addFilterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('utilities.filters.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                        <input type="hidden" name="parent_id" id="addparent_id" >

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <!-- Hidden input to ensure 0 is sent if checkbox is unchecked -->
                        <input type="hidden" name="isFix" value="0">

                        <!-- Checkbox that overrides the hidden input if checked -->
                        <input type="checkbox" name="isFix" class="form-check-input" value="1" id="isFix">
                        <label class="form-check-label" for="isFix">Is Fixed</label>
                    </div>
                    <div class="mb-3 form-check">
                        <!-- Hidden input to ensure 0 is sent if checkbox is unchecked -->
                        <input type="hidden" name="child" value="0">

                        <!-- Checkbox that overrides the hidden input if checked -->
                        <input type="checkbox" name="child" class="form-check-input" value="1" id="child">
                        <label class="form-check-label" for="child">Do you wants to add Child?</label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Edit Filter Modal --}}
<div class="modal fade" id="editFilterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="editFilterForm">
            @csrf
            <input type="hidden" name="id" id="editFilterId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" id="editTitle" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <!-- Hidden input to submit '0' when checkbox is unchecked -->
                        <input type="hidden" name="isFix" value="0">

                        <!-- Checkbox, overrides hidden input if checked -->
                        <input type="checkbox" name="isFix" id="editisFix" class="form-check-input" value="1">
                        <label class="form-check-label" for="editisFix">Is Fixed</label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>
