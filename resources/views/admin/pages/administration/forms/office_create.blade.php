<x-admin-app-layout :title="'Add Administration Office'">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold mb-0">Add New Office</h3>
            <a href="{{ route('admin.administration.index') }}" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.administration.office.store') }}" method="POST" novalidate>
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Office Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm"
                            value="{{ old('title') }}" placeholder="Enter office title..." required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Group <span class="text-danger">*</span></label>
                        <select name="group_id" class="form-select form-select-sm" required>
                            <option value="">-- Select Group --</option>
                            @foreach ($groups as $g)
                                <option value="{{ $g->id }}"
                                    @if (old('group_id', $selectedGroupId) == $g->id) selected @endif>
                                    {{ $g->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" rows="4" class="form-control form-control-sm"
                            placeholder="Office description...">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control form-control-sm"
                            value="{{ old('meta_title') }}" placeholder="Meta title...">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Meta Tags</label>
                        <input type="text" name="meta_tags" class="form-control form-control-sm"
                            value="{{ old('meta_tags') }}" placeholder="Meta tags...">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Meta Description</label>
                        <input type="text" name="meta_description" class="form-control form-control-sm"
                            value="{{ old('meta_description') }}" placeholder="Meta description...">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Save
                    </button>
                    <a href="{{ route('admin.administration.index') }}" class="btn btn-light btn-sm ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
