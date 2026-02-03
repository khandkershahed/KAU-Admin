<x-admin-app-layout :title="'Add Navigation Item'">

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h3 class="card-title fw-bold mb-0">Add Navigation Item</h3>
                <div class="text-muted small mt-1">
                    Site: <span class="fw-semibold">{{ $site->name }} ({{ $site->short_name }})</span>
                </div>
            </div>

            <a href="{{ route('admin.academic.sites.index', ['site_id' => $site->id]) }}" class="btn btn-light btn-sm">
                <i class="fa fa-arrow-left me-2"></i>Back
            </a>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.academic.nav.store', $site->id) }}" method="POST">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Label</x-metronic.label>
                        <input type="text" name="label" id="navLabel" class="form-control form-control-sm"
                               placeholder="About, Departments, Academic Result..." required>
                        <div class="text-muted small mt-1">Frontend menu label.</div>
                    </div>

                    <div class="col-md-3">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Slug</x-metronic.label>
                        <input type="text" name="slug" id="navSlug" class="form-control form-control-sm"
                               placeholder="about" required>
                        <div class="text-muted small mt-1">Used for URL mapping.</div>
                    </div>

                    <div class="col-md-3">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Menu Key</x-metronic.label>
                        <input type="text" name="menu_key" id="navMenuKey" class="form-control form-control-sm"
                               placeholder="about">
                        <div class="text-muted small mt-1">Optional key to map with page_key.</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Parent</x-metronic.label>
                        <select name="parent_id" class="form-select form-select-sm">
                            <option value="">(Root)</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}" {{ (string)$parentId === (string)$p->id ? 'selected' : '' }}>
                                    {{ $p->label }} ({{ $p->slug }})
                                </option>
                            @endforeach
                        </select>
                        <div class="text-muted small mt-1">Choose parent for dropdown nesting.</div>
                    </div>

                    <div class="col-md-4">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Type</x-metronic.label>
                        <select name="type" id="navType" class="form-select form-select-sm">
                            <option value="page" selected>Page</option>
                            <option value="route">Route</option>
                            <option value="external">External URL</option>
                            <option value="group">Group (non-clickable)</option>
                        </select>
                        <div class="text-muted small mt-1">Controls link behavior.</div>
                    </div>

                    <div class="col-md-4" id="navExternalUrlWrapper" style="display:none;">
                        <x-metronic.label class="col-form-label fw-bold fs-6">External URL</x-metronic.label>
                        <input type="text" name="external_url" id="navExternalUrl" class="form-control form-control-sm"
                               placeholder="https://example.com">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Icon</x-metronic.label>
                        <x-metronic.icon-picker id="navIcon" name="icon" :value="''"
                            class="form-control-sm" buttonClass="btn-sm btn-outline-info btn-active-info" />
                        <div class="text-muted small mt-1">Optional menu icon.</div>
                    </div>

                    <div class="col-md-4">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Status</x-metronic.label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="published" selected>Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                        <div class="text-muted small mt-1">Draft items can be hidden from frontend.</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.academic.sites.index', ['site_id' => $site->id]) }}" class="btn btn-light btn-sm">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-save me-2"></i>Save
                    </button>
                </div>

            </form>

        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const label = document.getElementById('navLabel');
                const slug = document.getElementById('navSlug');
                const key = document.getElementById('navMenuKey');
                const type = document.getElementById('navType');
                const externalWrap = document.getElementById('navExternalUrlWrapper');
                const external = document.getElementById('navExternalUrl');

                function slugify(text) {
                    return (text || '')
                        .toString()
                        .trim()
                        .toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                }

                label.addEventListener('input', function () {
                    if (!slug.dataset.touched) slug.value = slugify(label.value);
                    if (!key.dataset.touched) key.value = slugify(label.value);
                });

                slug.addEventListener('input', function () { slug.dataset.touched = '1'; });
                key.addEventListener('input', function () { key.dataset.touched = '1'; });

                function toggleExternal() {
                    if (type.value === 'external') {
                        externalWrap.style.display = '';
                    } else {
                        externalWrap.style.display = 'none';
                        if (external) external.value = '';
                    }
                }

                type.addEventListener('change', toggleExternal);
                toggleExternal();
            })();
        </script>
    @endpush

</x-admin-app-layout>
