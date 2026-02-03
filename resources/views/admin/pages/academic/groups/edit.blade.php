<x-admin-app-layout :title="'Edit Academic Menu Group'">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold mb-0">Edit Menu Group</h3>
            <a href="{{ route('admin.academic.sites.index') }}" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.academic.groups.update', $group->id) }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="redirect_to" value="{{ route('admin.academic.sites.index') }}">

                <div class="row g-4">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm"
                            value="{{ old('title', $group->title) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="published" @selected(old('status',$group->status)==='published')>Published</option>
                            <option value="draft" @selected(old('status',$group->status)==='draft')>Draft</option>
                            <option value="archived" @selected(old('status',$group->status)==='archived')>Archived</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Update
                    </button>
                    <a href="{{ route('admin.academic.sites.index') }}" class="btn btn-light btn-sm ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
