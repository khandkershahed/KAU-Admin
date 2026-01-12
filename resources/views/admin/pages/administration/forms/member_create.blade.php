<x-admin-app-layout :title="'Add Member - ' . $office->title">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold mb-0">Add New Member</h3>
            <a href="{{ route('admin.administration.office.page', $office->slug) }}" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.administration.member.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <input type="hidden" name="office_id" value="{{ $office->id }}">
                <input type="hidden" name="section_id" value="{{ $section->id }}">

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Photo</label>
                        <input type="file" name="image" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Type</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">-- Select --</option>
                            <option value="head" @if (old('type') == 'head') selected @endif>Head</option>
                            <option value="officer" @if (old('type') == 'officer') selected @endif>Officer</option>
                            <option value="member" @if (old('type') == 'member') selected @endif>Member</option>
                            <option value="alumni" @if (old('type') == 'alumni') selected @endif>Alumni</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Label</label>
                        <input type="text" name="label" class="form-control form-control-sm"
                            value="{{ old('label') }}" placeholder="e.g. Batch: 2010">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm"
                            value="{{ old('name') }}" placeholder="Enter name..." required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Designation</label>
                        <input type="text" name="designation" class="form-control form-control-sm"
                            value="{{ old('designation') }}" placeholder="Enter designation...">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm"
                            value="{{ old('email') }}" placeholder="Enter email...">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control form-control-sm"
                            value="{{ old('phone') }}" placeholder="Enter phone...">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Save
                    </button>
                    <a href="{{ route('admin.administration.office.page', $office->slug) }}" class="btn btn-light btn-sm ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
