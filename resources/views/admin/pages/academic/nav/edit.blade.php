<x-admin-app-layout :title="'Edit Navigation Item'">

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h3 class="card-title fw-bold mb-0">Edit Navigation Item</h3>
                <div class="text-muted small mt-1">
                    Site: <span class="fw-semibold">{{ $site->name }} ({{ $site->short_name }})</span>
                </div>
            </div>

            <a href="{{ route('admin.academic.sites.index', ['site_id' => $site->id]) }}" class="btn btn-light btn-sm">
                <i class="fa fa-arrow-left me-2"></i>Back
            </a>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.academic.nav.update', $item->id) }}" method="POST">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Label</x-metronic.label>
                        <input type="text" name="label" id="navLabel" class="form-control form-control-sm"
                               value="{{ $item->label }}" required>
                    </div>

                    <div class="col-md-3">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Slug</x-metronic.label>
                        <input type="text" name="slug" id="navSlug" class="form-control form-control-sm"
                               value="{{ $item->slug }}" required>
                    </div>

                    <div class="col-md-3">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Menu Key</x-metronic.label>
                        <input type="text" name="menu_key" id="navMenuKey" class="form-control form-control-sm"
                               value="{{ $item->menu_key }}">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Parent</x-metronic.label>
                        <select name="parent_id" class="form-select form-select-sm" disabled>
                            <option value="">(Root)</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}" {{ (string)$item->parent_id === (string)$p->id ? 'selected' : '' }}>
                                    {{ $p->label }} ({{ $p->slug }})
                                </option>
                            @endforeach
                        </select>
                        <div class="text-muted small mt-1">
                            Parent is controlled by drag/drop nesting in the Navigation tree.
                        </div>
                    </div>

                    <div class="col-md-4">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Type</x-metronic.label>
                        <select name="type" id="navType" class="form-select form-select-sm">
                            <option value="page" {{ $item->type === 'page' ? 'selected' : '' }}>Page</option>
                            <option value="route" {{ $item->type === 'route' ? 'selected' : '' }}>Route</option>
                            <option value="external" {{ $item->type === 'external' ? 'selected' : '' }}>External URL</option>
                            <option value="group" {{ $item->type === 'group' ? 'selected' : '' }}>Group (non-clickable)</option>
                        </select>
                    </div>

                    <div class="col-md-4" id="navExternalUrlWrapper" style="{{ $item->type === 'external' ? '' : 'display:none;' }}">
                        <x-metronic.label class="col-form-label fw-bold fs-6">External URL</x-metronic.label>
                        <input type="text" name="external_url" id="navExternalUrl" class="form-control form-control-sm"
                               value="{{ $item->external_url }}">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Icon</x-metronic.label>
                        <x-metronic.icon-picker id="navIcon" name="icon" :value="$item->icon ?? ''"
                            class="form-control-sm" buttonClass="btn-sm btn-outline-info btn-active-info" />
                    </div>

                    <div class="col-md-4">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Status</x-metronic.label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="published" {{ $item->status === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ $item->status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="archived" {{ $item->status === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.academic.sites.index', ['site_id' => $site->id]) }}" class="btn btn-light btn-sm">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-save me-2"></i>Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const type = document.getElementById('navType');
                const externalWrap = document.getElementById('navExternalUrlWrapper');
                const external = document.getElementById('navExternalUrl');

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
