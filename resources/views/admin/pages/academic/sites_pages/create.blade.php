<x-admin-app-layout :title="'Add Academic Site'">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold mb-0">Add Site</h3>
            <a href="{{ route('admin.academic.sites.index') }}" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.academic.sites.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="redirect_to" value="{{ route('admin.academic.sites.index') }}">

                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Menu Group <span class="text-danger">*</span></label>
                        <select name="academic_menu_group_id" class="form-select form-select-sm" required>
                            <option value="">Select Group</option>
                            @foreach($groups as $g)
                                <option value="{{ $g->id }}" @selected(old('academic_menu_group_id', $selectedGroupId)==$g->id)>{{ $g->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Site Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm slug-source"
                            value="{{ old('name') }}" placeholder="Faculty of ..." required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Short Name</label>
                        <input type="text" name="short_name" class="form-control form-control-sm"
                            value="{{ old('short_name') }}" placeholder="FST">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control form-control-sm slug-target"
                            value="{{ old('slug') }}" placeholder="faculty-of-science-and-technology" required>
                        <div class="small text-muted mt-1">Used as frontend base path: <code>/facultySlug</code></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="published" @selected(old('status','published')==='published')>Published</option>
                            <option value="draft" @selected(old('status')==='draft')>Draft</option>
                            <option value="archived" @selected(old('status')==='archived')>Archived</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Short Description</label>
                        <textarea name="short_description" class="form-control form-control-sm" rows="2"
                            placeholder="Optional">{{ old('short_description') }}</textarea>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Primary Color</label>
                        <input type="text" name="theme_primary_color" class="form-control form-control-sm"
                            value="{{ old('theme_primary_color') }}" placeholder="#0d6efd">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Secondary Color</label>
                        <input type="text" name="theme_secondary_color" class="form-control form-control-sm"
                            value="{{ old('theme_secondary_color') }}" placeholder="#6610f2">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Logo</label>
                        <input type="file" name="logo" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Save
                    </button>
                    <a href="{{ route('admin.academic.sites.index') }}" class="btn btn-light btn-sm ms-2">Cancel</a>
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
                const slugTarget = document.querySelector('.slug-target');
                if(slugTarget && !slugTarget.value) slugTarget.value = slugify(e.target.value);
            });
        </script>
    @endpush
</x-admin-app-layout>
