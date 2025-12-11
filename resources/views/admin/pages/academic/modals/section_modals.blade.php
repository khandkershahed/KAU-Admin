{{-- CREATE STAFF GROUP --}}
<div class="modal fade" id="createStaffGroupModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <form class="modal-content" method="POST" id="createStaffGroupForm">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Staff Section / Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="department_id" id="createStaffGroupDepartmentId">

                <div class="row g-4">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text"
                               name="title"
                               class="form-control"
                               placeholder="e.g. Professors, Officers">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Position</label>
                        <input type="number"
                               class="form-control"
                               name="position"
                               value="0">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>

{{-- EDIT STAFF GROUP --}}
<div class="modal fade" id="editStaffGroupModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <form class="modal-content" method="POST" id="editStaffGroupForm">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Staff Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text"
                               name="title"
                               id="editStaffGroupTitle"
                               class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status"
                                id="editStaffGroupStatus"
                                class="form-select">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success">Update</button>
            </div>
        </form>
    </div>
</div>
