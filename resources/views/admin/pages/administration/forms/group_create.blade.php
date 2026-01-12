<x-admin-app-layout :title="'Add Administration Group'">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold mb-0">Add New Group</h3>
            <a href="{{ route('admin.administration.index') }}" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.administration.group.store') }}" method="POST" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Group Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name') }}"
                        placeholder="Enter group name..." required>
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
