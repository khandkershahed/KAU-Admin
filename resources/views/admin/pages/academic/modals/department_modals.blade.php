{{-- CREATE DEPARTMENT --}}
<div class="modal fade" id="createDepartmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" action="{{ route('admin.academic.departments.store', 0) }}">
            @csrf
            <input type="hidden" name="academic_site_id" id="createDeptSiteId">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row g-4">

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="title" id="deptCreateTitle" class="form-control slug-source"
                            placeholder="Enter department title">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Short Code</label>
                        <input type="text" name="short_code" class="form-control" placeholder="e.g. AGB">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" name="slug" id="deptCreateSlug" class="form-control slug-target"
                            placeholder="Auto-filled slug">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Position</label>
                        <input type="number" name="position" class="form-control" value="0">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control editor" placeholder="Department description"></textarea>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>

        </form>
    </div>
</div>


{{-- EDIT DEPARTMENT --}}
<div class="modal fade" id="editDepartmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" id="editDepartmentForm">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row g-4">

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="title" id="deptEditTitle" class="form-control slug-source-edit">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Short Code</label>
                        <input type="text" name="short_code" id="deptEditShortCode" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" name="slug" id="deptEditSlug" class="form-control slug-target-edit">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" id="deptEditStatus" class="form-select">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Position</label>
                        <input type="number" name="position" id="deptEditPosition" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="deptEditDescription" class="form-control editor"></textarea>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Update</button>
            </div>

        </form>
    </div>
</div>
