<x-admin-app-layout :title="'Edit Academic Site'">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold mb-0">Edit Site</h3>
            <a href="{{ route('admin.academic.sites.index', ['site_id' => $site->id]) }}" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.academic.sites.update', $site->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="redirect_to" value="{{ route('admin.academic.sites.index', ['site_id' => $site->id]) }}">

                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Menu Group <span class="text-danger">*</span></label>
                        <select name="academic_menu_group_id" class="form-select form-select-sm" required>
                            @foreach($groups as $g)
                                <option value="{{ $g->id }}" @selected(old('academic_menu_group_id', $site->academic_menu_group_id)==$g->id)>{{ $g->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Site Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm"
                            value="{{ old('name', $site->name) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Short Name</label>
                        <input type="text" name="short_name" class="form-control form-control-sm"
                            value="{{ old('short_name', $site->short_name) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control form-control-sm"
                            value="{{ old('slug', $site->slug) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="published" @selected(old('status',$site->status)==='published')>Published</option>
                            <option value="draft" @selected(old('status',$site->status)==='draft')>Draft</option>
                            <option value="archived" @selected(old('status',$site->status)==='archived')>Archived</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Short Description</label>
                        <textarea name="short_description" class="form-control form-control-sm" rows="2">{{ old('short_description', $site->short_description) }}</textarea>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Primary Color</label>
                        <input type="text" name="theme_primary_color" class="form-control form-control-sm"
                            value="{{ old('theme_primary_color', $site->theme_primary_color) }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Secondary Color</label>
                        <input type="text" name="theme_secondary_color" class="form-control form-control-sm"
                            value="{{ old('theme_secondary_color', $site->theme_secondary_color) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Logo</label>
                        <input type="file" name="logo" class="form-control form-control-sm">
                        @if($site->logo_path)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$site->logo_path) }}" alt="logo" style="max-height:60px;">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Update
                    </button>
                    <a href="{{ route('admin.academic.sites.index', ['site_id' => $site->id]) }}" class="btn btn-light btn-sm ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
