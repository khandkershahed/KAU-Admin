{{-- Navigation Create / Edit Modals --}}

@php
    /** @var \App\Models\AcademicSite|null $selectedSite */
@endphp

@if ($selectedSite)
    {{-- CREATE NAV ITEM MODAL --}}
    <div class="modal fade" id="createNavModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.academic.nav.store', $selectedSite->id) }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Add Navigation Item ({{ $selectedSite->short_name }})</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="parent_id" id="createNavParentId" value="">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Label</x-metronic.label>
                                <input type="text" name="label" id="createNavLabel"
                                    class="form-control form-control-sm"
                                    placeholder="About, Departments, Academic Result..." required>
                            </div>
                            <div class="col-md-3">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Slug</x-metronic.label>
                                <input type="text" name="slug" id="createNavSlug"
                                    class="form-control form-control-sm" placeholder="about" required>
                            </div>
                            <div class="col-md-3">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Menu Key</x-metronic.label>
                                <input type="text" name="menu_key" id="createNavMenuKey"
                                    class="form-control form-control-sm" placeholder="about" required>
                                <small class="text-muted d-block">Will sync with page_key.</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Type</x-metronic.label>
                                <select name="type" id="createNavType" class="form-select form-select-sm">
                                    <option value="page" selected>Page</option>
                                    <option value="route">Route</option>
                                    <option value="external">External URL</option>
                                    <option value="group">Group (non-clickable)</option>
                                </select>
                            </div>

                            <div class="col-md-4" id="createNavExternalUrlWrapper" style="display:none;">
                                <x-metronic.label class="col-form-label fw-bold fs-6">External URL</x-metronic.label>
                                <input type="text" name="external_url" id="createNavExternalUrl"
                                    class="form-control form-control-sm" placeholder="https://example.com">
                            </div>

                            <div class="col-md-4">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Icon</x-metronic.label>
                                <x-metronic.icon-picker id="createNavIcon" name="icon" :value="''"
                                    class="form-control-sm" buttonClass="btn-sm btn-outline-info btn-active-info" />
                                <small class="text-muted d-block mt-1">Optional.</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Status</x-metronic.label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="published" selected>Published</option>
                                <option value="draft">Draft</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT NAV ITEM MODAL --}}
    <div class="modal fade" id="editNavModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="editNavForm" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Navigation Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="parent_id" id="editNavParentId" value="">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Label</x-metronic.label>
                                <input type="text" name="label" id="editNavLabel"
                                    class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-3">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Slug</x-metronic.label>
                                <input type="text" name="slug" id="editNavSlug"
                                    class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-3">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Menu Key</x-metronic.label>
                                <input type="text" name="menu_key" id="editNavMenuKey"
                                    class="form-control form-control-sm" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Type</x-metronic.label>
                                <select name="type" id="editNavType" class="form-select form-select-sm">
                                    <option value="page">Page</option>
                                    <option value="route">Route</option>
                                    <option value="external">External URL</option>
                                    <option value="group">Group (non-clickable)</option>
                                </select>
                            </div>

                            <div class="col-md-4" id="editNavExternalUrlWrapper" style="display:none;">
                                <x-metronic.label class="col-form-label fw-bold fs-6">External URL</x-metronic.label>
                                <input type="text" name="external_url" id="editNavExternalUrl"
                                    class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Icon</x-metronic.label>
                                <x-metronic.icon-picker id="editNavIcon" name="icon" :value="''"
                                    class="form-control-sm" buttonClass="btn-sm btn-outline-info btn-active-info" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Status</x-metronic.label>
                            <select name="status" id="editNavStatus" class="form-select form-select-sm">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
