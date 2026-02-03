<x-admin-app-layout :title="'Add Academic Department'">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold mb-0">Add Department</h3>
            <a href="{{ route('admin.academic.staff.index', ['site_id' => $siteId]) }}" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.academic.departments.store') }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="redirect_to" value="{{ route('admin.academic.staff.index', ['site_id' => $siteId]) }}">

                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Site <span class="text-danger">*</span></label>
                        <select name="academic_site_id" class="form-select form-select-sm" required>
                            @foreach($sites as $s)
                                <option value="{{ $s->id }}" @selected(old('academic_site_id', $siteId)==$s->id)>{{ $s->name }} ({{ $s->short_name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Department Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm slug-source" value="{{ old('title') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Short Code</label>
                        <input type="text" name="short_code" class="form-control form-control-sm" value="{{ old('short_code') }}" placeholder="CSE">
                        <div class="small text-muted mt-1">If blank, it will be auto-generated.</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" name="slug" class="form-control form-control-sm slug-target" value="{{ old('slug') }}" placeholder="computer-science-and-engineering">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="published" @selected(old('status','published')==='published')>Published</option>
                            <option value="draft" @selected(old('status')==='draft')>Draft</option>
                            <option value="archived" @selected(old('status')==='archived')>Archived</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Position</label>
                        <input type="number" name="position" class="form-control form-control-sm" value="{{ old('position', 0) }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control form-control-sm" rows="3">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk me-2"></i> Save</button>
                    <a href="{{ route('admin.academic.staff.index', ['site_id' => $siteId]) }}" class="btn btn-light btn-sm ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function slugify(text) {
                return text.toString().toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '')
                    .substring(0, 180);
            }
            document.addEventListener('keyup', function(e){
                if(!e.target.classList.contains('slug-source')) return;
                const tgt = document.querySelector('.slug-target');
                if(tgt && !tgt.value) tgt.value = slugify(e.target.value);
            });
        </script>
    @endpush
</x-admin-app-layout>
