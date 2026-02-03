{{-- Site Create / Edit Modals --}}

{{-- CREATE SITE MODAL --}}
<div class="modal fade" id="createSiteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.academic.sites.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Site</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Group</x-metronic.label>
                            <select name="academic_menu_group_id" id="createSiteGroup"
                                class="form-select form-select-sm" required>
                                <option value="">Select Group</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->title }} ({{ $group->slug }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Site Name</x-metronic.label>
                            <input type="text" name="name" id="createSiteName"
                                class="form-control form-control-sm" placeholder="Faculty of Veterinary Medicine"
                                required>
                        </div>

                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Short Name</x-metronic.label>
                            <input type="text" name="short_name" id="createSiteShortName"
                                class="form-control form-control-sm" placeholder="FST" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Slug</x-metronic.label>
                            <input type="text" name="slug" id="createSiteSlug"
                                class="form-control form-control-sm" placeholder="fst" required>
                            <small class="text-muted">Auto-filled from Short Name (can edit).</small>
                        </div>

                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Status</x-metronic.label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="published" selected>Published</option>
                                <option value="draft">Draft</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Logo</x-metronic.label>
                            <x-metronic.image-input name="logo" id="siteLogoCreate" :source="''" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Primary Color</x-metronic.label>
                            <x-metronic.color-picker id="sitePrimaryColorCreate" name="theme_primary_color"
                                :value="old('theme_primary_color')" class="form-control-sm" buttonClass="btn-sm" />
                        </div>
                        <div class="col-md-6">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Secondary Color</x-metronic.label>
                            <x-metronic.color-picker id="siteSecondaryColorCreate" name="theme_secondary_color"
                                :value="old('theme_secondary_color')" class="form-control-sm" buttonClass="btn-sm" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Short Description</x-metronic.label>
                        <textarea name="short_description" id="createSiteDescription" class="form-control form-control-sm" rows="3"
                            placeholder="Short intro about this site"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Site</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT SITE MODAL --}}
<div class="modal fade" id="editSiteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form id="editSiteForm" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Edit Site</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Group</x-metronic.label>
                            <select name="academic_menu_group_id" id="editSiteGroup"
                                class="form-select form-select-sm" required>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->title }} ({{ $group->slug }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Site Name</x-metronic.label>
                            <input type="text" name="name" id="editSiteName"
                                class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Short Name</x-metronic.label>
                            <input type="text" name="short_name" id="editSiteShortName"
                                class="form-control form-control-sm" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Slug</x-metronic.label>
                            <input type="text" name="slug" id="editSiteSlug"
                                class="form-control form-control-sm" required>
                            <small class="text-muted">Auto-filled from Short Name when editing (can override).</small>
                        </div>

                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Status</x-metronic.label>
                            <select name="status" id="editSiteStatus" class="form-select form-select-sm">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Logo</x-metronic.label>
                            <x-metronic.image-input name="logo" id="siteLogoEdit" :source="''" />
                            <small class="text-muted d-block mt-1">
                                Upload to replace existing logo.
                            </small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Primary Color</x-metronic.label>
                            <x-metronic.color-picker id="sitePrimaryColorEdit" name="theme_primary_color"
                                :value="old('theme_primary_color')" class="form-control-sm" buttonClass="btn-sm" />
                        </div>
                        <div class="col-md-6">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Secondary Color</x-metronic.label>
                            <x-metronic.color-picker id="siteSecondaryColorEdit" name="theme_secondary_color"
                                :value="old('theme_secondary_color')" class="form-control-sm" buttonClass="btn-sm" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Short Description</x-metronic.label>
                        <textarea name="short_description" id="editSiteDescription" class="form-control form-control-sm" rows="3"></textarea>
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
