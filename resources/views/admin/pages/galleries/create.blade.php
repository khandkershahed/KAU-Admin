<x-admin-app-layout :title="'Create Gallery'">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title fw-bold">Create Gallery</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.galleries.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold">Owner Type</label>
                    <select name="owner_type" class="form-select form-select-sm" required>
                        <option value="main">Main Site</option>
                        <option value="site">Faculty</option>
                        <option value="department">Department</option>
                    </select>
                    <small class="text-muted">Where this gallery will appear (About section).</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Owner ID (optional)</label>
                    <input type="number" name="owner_id" class="form-control form-control-sm">
                    <small class="text-muted">Leave empty for main site gallery.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Title</label>
                    <input type="text" name="title" class="form-control form-control-sm" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Slug</label>
                    <input type="text" name="slug" class="form-control form-control-sm" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Gallery Type</label>
                    <select name="type" class="form-select form-select-sm">
                        <option value="image">Image Gallery</option>
                        <option value="video">Video Gallery</option>
                        <option value="mixed">Mixed</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary">Save Gallery</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
