<!-- ======================================================
     PAGE 2 MODALS: SECTIONS + MEMBERS
======================================================= -->

{{-- ======================================================
     CREATE SECTION MODAL
====================================================== --}}
<div class="modal fade" id="createSectionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="createSectionForm" action="{{ route('admin.administration.section.store') }}" method="POST"
                novalidate>
                @csrf

                <input type="hidden" name="office_id" value="{{ $office->id }}">

                <div class="modal-header">
                    <h5 class="modal-title">Add New Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Section Title <span class="text-danger">*</span></label>

                        <input type="text" name="title" class="form-control form-control-sm"
                            placeholder="Enter section title..." required>

                        <span class="text-danger error-text title_error small"></span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Section</button>
                </div>

            </form>

        </div>
    </div>
</div>



{{-- ======================================================
     EDIT SECTION MODAL
====================================================== --}}
<div class="modal fade" id="editSectionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="editSectionForm" action="{{ route('admin.administration.section.update') }}" method="POST"
                novalidate>
                @csrf
                @method('PUT')

                <input type="hidden" name="id">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Section Title <span class="text-danger">*</span></label>

                        <input type="text" name="title" class="form-control form-control-sm"
                            placeholder="Enter section title..." required>

                        <span class="text-danger error-text title_error small"></span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Section</button>
                </div>

            </form>

        </div>
    </div>
</div>



{{-- ======================================================
     CREATE MEMBER MODAL
====================================================== --}}
<div class="modal fade" id="createMemberModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form id="createMemberForm" action="{{ route('admin.administration.member.store') }}" method="POST"
                enctype="multipart/form-data" novalidate>
                @csrf

                <input type="hidden" name="office_id" value="{{ $office->id }}">
                <input type="hidden" name="section_id" id="createMemberSectionId">

                <div class="modal-header">
                    <h5 class="modal-title">Add New Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    {{-- MEMBER PHOTO --}}
                    <div class="col-md-4 mb-3 text-center">

                        <label class="form-label fw-semibold d-block">Photo</label>

                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <div class="image-input-wrapper w-125px h-125px"
                                style="background-image: url('{{ asset('images/no_image.png') }}')"></div>

                            <label class="btn btn-icon btn-circle btn-active-light-primary w-25px h-25px"
                                data-kt-image-input-action="change">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="image" class="member-image-input"
                                    accept=".png,.jpg,.jpeg">
                            </label>
                            <span class="text-danger image_error small"></span>

                            <span class="btn btn-icon btn-circle btn-active-light-primary w-25px h-25px"
                                data-kt-image-input-action="cancel">
                                <i class="bi bi-x fs-2"></i>
                            </span>

                            <span class="btn btn-icon btn-circle btn-active-light-primary w-25px h-25px"
                                data-kt-image-input-action="remove">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        </div>

                        <span class="text-danger error-text image_error small"></span>

                    </div>

                    {{-- MEMBER FIELDS --}}
                    <div class="col-md-8">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Member Name <span
                                    class="text-danger">*</span></label>

                            <input type="text" name="name" class="form-control form-control-sm"
                                placeholder="Enter member name..." required>

                            <span class="text-danger error-text name_error small"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Designation</label>

                            <input type="text" name="designation" class="form-control form-control-sm"
                                placeholder="Officer / Staff / etc..">

                            <span class="text-danger error-text designation_error small"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>

                            <input type="email" name="email" class="form-control form-control-sm"
                                placeholder="Email (optional)">

                            <span class="text-danger error-text email_error small"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phone</label>

                            <input type="text" name="phone" class="form-control form-control-sm"
                                placeholder="Phone (optional)">

                            <span class="text-danger error-text phone_error small"></span>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Member</button>
                </div>

            </form>

        </div>
    </div>
</div>



{{-- ======================================================
     EDIT MEMBER MODAL
====================================================== --}}
<div class="modal fade" id="editMemberModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form id="editMemberForm" action="{{ route('admin.administration.member.update') }}" method="POST"
                enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <input type="hidden" name="id">
                <input type="hidden" name="office_id" value="{{ $office->id }}">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    {{-- MEMBER PHOTO --}}
                    <div class="col-md-4 mb-3 text-center">

                        <label class="form-label fw-semibold d-block">Photo</label>

                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <div class="image-input-wrapper w-125px h-125px" id="editMemberImagePreview"
                                style="background-image: url('{{ asset('images/no_image.png') }}')"></div>

                            <label class="btn btn-icon btn-circle btn-active-light-primary w-25px h-25px"
                                data-kt-image-input-action="change">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="image" class="member-image-input"
                                    accept=".png,.jpg,.jpeg">
                            </label>
                            <span class="text-danger image_error small"></span>

                            <span class="btn btn-icon btn-circle btn-active-light-primary w-25px h-25px"
                                data-kt-image-input-action="cancel">
                                <i class="bi bi-x fs-2"></i>
                            </span>

                            <span class="btn btn-icon btn-circle btn-active-light-primary w-25px h-25px"
                                data-kt-image-input-action="remove">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        </div>

                        <span class="text-danger error-text image_error small"></span>

                    </div>

                    {{-- MEMBER FIELDS --}}
                    <div class="col-md-8">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Member Name <span
                                    class="text-danger">*</span></label>

                            <input type="text" name="name" class="form-control form-control-sm" required>

                            <span class="text-danger error-text name_error small"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Designation</label>

                            <input type="text" name="designation" class="form-control form-control-sm">

                            <span class="text-danger error-text designation_error small"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>

                            <input type="email" name="email" class="form-control form-control-sm">

                            <span class="text-danger error-text email_error small"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phone</label>

                            <input type="text" name="phone" class="form-control form-control-sm">

                            <span class="text-danger error-text phone_error small"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Section</label>

                            <select name="section_id" id="editMemberSectionSelect" class="form-select form-select-sm"
                                data-control="select2" data-placeholder="Select Section">
                                @foreach ($sections as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->title }}</option>
                                @endforeach
                            </select>

                            <span class="text-danger error-text section_id_error small"></span>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Member</button>
                </div>

            </form>

        </div>
    </div>
</div>
