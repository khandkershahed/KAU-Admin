{{-- Group Create / Edit Modals --}}

{{-- CREATE GROUP MODAL --}}
<div class="modal fade" id="createGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.academic.groups.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Menu Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row mb-3">
                        <div class="col-md-7">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Title</x-metronic.label>
                            <input type="text" name="title" id="createGroupTitle"
                                class="form-control form-control-sm" placeholder="Faculty of Veterinary Medicine"
                                required>
                        </div>
                        <div class="col-md-5">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Slug</x-metronic.label>
                            <input type="text" name="slug" id="createGroupSlug"
                                class="form-control form-control-sm" placeholder="faculty-of-veterinary-medicine"
                                required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Status</x-metronic.label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="published" selected>Published</option>
                                <option value="draft">Draft</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Position (optional)</x-metronic.label>
                            <input type="number" name="position" class="form-control form-control-sm" placeholder="0">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT GROUP MODAL --}}
<div class="modal fade" id="editGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form id="editGroupForm" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Edit Menu Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row mb-3">
                        <div class="col-md-7">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Title</x-metronic.label>
                            <input type="text" name="title" id="editGroupTitle"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-5">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Slug</x-metronic.label>
                            <input type="text" name="slug" id="editGroupSlug" class="form-control form-control-sm"
                                required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Status</x-metronic.label>
                        <select name="status" id="editGroupStatus" class="form-select form-select-sm">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
