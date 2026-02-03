<x-admin-app-layout :title="'Edit Academic Department'">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold mb-0">Edit Department</h3>
            <a href="{{ route('admin.academic.staff.index', ['site_id' => $department->academic_site_id, 'department_id' => $department->id]) }}" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.academic.departments.update', $department->id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_to" value="{{ route('admin.academic.staff.index', ['site_id' => $department->academic_site_id, 'department_id' => $department->id]) }}">

                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Site</label>
                        <select class="form-select form-select-sm" disabled>
                            @foreach($sites as $s)
                                <option value="{{ $s->id }}" @selected($department->academic_site_id==$s->id)>{{ $s->name }} ({{ $s->short_name }})</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="academic_site_id" value="{{ $department->academic_site_id }}">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Department Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm" value="{{ old('title', $department->title) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Short Code</label>
                        <input type="text" name="short_code" class="form-control form-control-sm" value="{{ old('short_code', $department->short_code) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" name="slug" class="form-control form-control-sm" value="{{ old('slug', $department->slug) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="published" @selected(old('status',$department->status)==='published')>Published</option>
                            <option value="draft" @selected(old('status',$department->status)==='draft')>Draft</option>
                            <option value="archived" @selected(old('status',$department->status)==='archived')>Archived</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Position</label>
                        <input type="number" name="position" class="form-control form-control-sm" value="{{ old('position', $department->position) }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control form-control-sm" rows="3">{{ old('description', $department->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk me-2"></i> Update</button>
                    <a href="{{ route('admin.academic.staff.index', ['site_id' => $department->academic_site_id, 'department_id' => $department->id]) }}" class="btn btn-light btn-sm ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
