<!--
    {{-- CREATE STAFF MEMBER --}}
    <div class="modal fade" id="createStaffMemberModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <form class="modal-content" method="POST" enctype="multipart/form-data" id="createStaffMemberForm">
                @csrf
                <input type="hidden" name="staff_section_id" id="createMemberGroupId">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Staff Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-black">Image</label>
                            <x-metronic.image-input name="image" id="memberImage"></x-metronic.image-input>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-black">Name</label>
                            <input type="text"
                                class="form-control slug-source"
                                name="name"
                                id="memberCreateName"
                                placeholder="Enter name">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-black">Designation</label>
                            <input type="text"
                                class="form-control"
                                name="designation"
                                placeholder="Designation">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-black">Email</label>
                            <input type="email"
                                class="form-control"
                                name="email"
                                placeholder="Email">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-black">Phone</label>
                            <input type="text"
                                class="form-control"
                                name="phone"
                                placeholder="Phone">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-black">Status</label>
                            <select class="form-select" name="status">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <hr>
                            <h6 class="fw-bold mb-3">Links (Social / Scholar / Website)</h6>

                            <div id="staffLinksRepeater">
                                {{-- Initial row --}}
                                <div class="row g-3 link-row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold text-black">Icon</label>
                                        <x-metronic.icon-picker name="links[0][icon]" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-black">URL</label>
                                        <input type="text"
                                            name="links[0][url]"
                                            class="form-control"
                                            placeholder="https://example.com">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button"
                                                class="btn btn-danger btn-sm removeLinkBtn w-100">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="button"
                                    class="btn btn-light-primary btn-sm"
                                    id="addStaffLinkBtn">
                                <i class="fa fa-plus me-2"></i>Add Link
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT STAFF MEMBER --}}
    <div class="modal fade" id="editStaffMemberModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <form class="modal-content" method="POST" enctype="multipart/form-data" id="editStaffMemberForm">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Staff Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-black">Image</label>
                            <x-metronic.image-input name="image" id="editMemberImagePicker"></x-metronic.image-input>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-black">Name</label>
                            <input type="text"
                                class="form-control slug-source-edit"
                                name="name"
                                id="editMemberName">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-black">Designation</label>
                            <input type="text"
                                class="form-control"
                                name="designation"
                                id="editMemberDesignation">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-black">Email</label>
                            <input type="email"
                                class="form-control"
                                name="email"
                                id="editMemberEmail">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-black">Phone</label>
                            <input type="text"
                                class="form-control"
                                name="phone"
                                id="editMemberPhone">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-black">Status</label>
                            <select class="form-select"
                                    name="status"
                                    id="editMemberStatus">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <hr>
                            <h6 class="fw-bold mb-3">Links</h6>

                            <div id="editStaffLinksRepeater"></div>

                            <button type="button"
                                    class="btn btn-light-primary btn-sm"
                                    id="addStaffLinkBtnEdit">
                                <i class="fa fa-plus me-2"></i>Add Link
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- HIDDEN TEMPLATES FOR LINKS (CREATE + EDIT) --}}
    <div id="staffLinkTemplate" class="d-none">
        <div class="row g-3 link-row mb-2">
            <div class="col-md-4">
                <label class="form-label fw-semibold text-black">Icon</label>
                <x-metronic.icon-picker name="links[__INDEX__][icon]" />
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold text-black">URL</label>
                <input type="text"
                    name="links[__INDEX__][url]"
                    class="form-control"
                    placeholder="https://example.com">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button"
                        class="btn btn-danger btn-sm removeLinkBtn w-100">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="staffLinkTemplateEdit" class="d-none">
        <div class="row g-3 link-row mb-2">
            <div class="col-md-4">
                <label class="form-label fw-semibold text-black">Icon</label>
                <x-metronic.icon-picker name="links[__INDEX__][icon]" />
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold text-black">URL</label>
                <input type="text"
                    name="links[__INDEX__][url]"
                    class="form-control"
                    placeholder="https://example.com">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button"
                        class="btn btn-danger btn-sm removeLinkBtn w-100">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    </div>


{{-- CREATE STAFF MEMBER --}}
<div class="modal fade" id="createStaffMemberModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="createStaffMemberForm">
            @csrf
            <input type="hidden" name="staff_section_id" id="createMemberGroupId">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Image</label>
                        <x-metronic.image-input name="image" id="memberImage"></x-metronic.image-input>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Name</label>
                        <input type="text" class="form-control slug-source" name="name" id="memberCreateName"
                            placeholder="Enter name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Designation</label>
                        <input type="text" class="form-control" name="designation" placeholder="Designation">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Phone</label>
                        <input type="text" class="form-control" name="phone" placeholder="Phone">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Mobile</label>
                        <input type="text" class="form-control" name="mobile"
                            placeholder="Mobile (preferred for UUID)">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-black">Address</label>
                        <textarea class="form-control" name="address" rows="2" placeholder="Address"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-black">Research Interest</label>
                        <textarea class="form-control" name="research_interest" rows="2" placeholder="Research interest"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-black">Bio</label>
                        <textarea class="form-control" name="bio" rows="3" placeholder="Bio"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-black">Education</label>
                        <textarea class="form-control" name="education" rows="4" placeholder="Education"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-black">Experience</label>
                        <textarea class="form-control" name="experience" rows="4" placeholder="Experience"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-black">Scholarship</label>
                        <textarea class="form-control" name="scholarship" rows="4" placeholder="Scholarship"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-black">Research</label>
                        <textarea class="form-control" name="research" rows="4" placeholder="Research"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-black">Teaching</label>
                        <textarea class="form-control" name="teaching" rows="4" placeholder="Teaching"></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Status</label>
                        <select class="form-select" name="status">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <hr>
                        <h6 class="fw-bold mb-3">Links (Social / Scholar / Website)</h6>

                        <div id="staffLinksRepeater">
                            <div class="row g-3 link-row mb-2">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-black">Icon</label>
                                    <x-metronic.icon-picker name="links[0][icon]" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-black">URL</label>
                                    <input type="text" name="links[0][url]" class="form-control"
                                        placeholder="https://example.com">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm removeLinkBtn w-100">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-light-primary btn-sm" id="addStaffLinkBtn">
                            <i class="fa fa-plus me-2"></i>Add Link
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>

{{-- EDIT STAFF MEMBER --}}
<div class="modal fade" id="editStaffMemberModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="editStaffMemberForm">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Image</label>
                        <x-metronic.image-input name="image" id="editMemberImagePicker"></x-metronic.image-input>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Name</label>
                        <input type="text" class="form-control slug-source-edit" name="name"
                            id="editMemberName">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Designation</label>
                        <input type="text" class="form-control" name="designation" id="editMemberDesignation">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Email</label>
                        <input type="email" class="form-control" name="email" id="editMemberEmail">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Phone</label>
                        <input type="text" class="form-control" name="phone" id="editMemberPhone">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Mobile</label>
                        <input type="text" class="form-control" name="mobile" id="editMemberMobile">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-black">Address</label>
                        <textarea class="form-control" name="address" id="editMemberAddress" rows="2"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-black">Research Interest</label>
                        <textarea class="form-control" name="research_interest" id="editMemberResearchInterest" rows="2"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-black">Bio</label>
                        <textarea class="form-control" name="bio" id="editMemberBio" rows="3"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-black">Education</label>
                        <textarea class="form-control" name="education" id="editMemberEducation" rows="4"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-black">Experience</label>
                        <textarea class="form-control" name="experience" id="editMemberExperience" rows="4"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-black">Scholarship</label>
                        <textarea class="form-control" name="scholarship" id="editMemberScholarship" rows="4"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-black">Research</label>
                        <textarea class="form-control" name="research" id="editMemberResearch" rows="4"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-black">Teaching</label>
                        <textarea class="form-control" name="teaching" id="editMemberTeaching" rows="4"></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-black">Status</label>
                        <select class="form-select" name="status" id="editMemberStatus">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <hr>
                        <h6 class="fw-bold mb-3">Links</h6>

                        <div id="editStaffLinksRepeater"></div>

                        <button type="button" class="btn btn-light-primary btn-sm" id="addStaffLinkBtnEdit">
                            <i class="fa fa-plus me-2"></i>Add Link
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-success">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- HIDDEN TEMPLATES FOR LINKS (CREATE + EDIT) --}}
<div id="staffLinkTemplate" class="d-none">
    <div class="row g-3 link-row mb-2">
        <div class="col-md-4">
            <label class="form-label fw-semibold text-black">Icon</label>
            <x-metronic.icon-picker name="links[__INDEX__][icon]" />
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold text-black">URL</label>
            <input type="text" name="links[__INDEX__][url]" class="form-control"
                placeholder="https://example.com">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm removeLinkBtn w-100">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
</div>

<div id="staffLinkTemplateEdit" class="d-none">
    <div class="row g-3 link-row mb-2">
        <div class="col-md-4">
            <label class="form-label fw-semibold text-black">Icon</label>
            <x-metronic.icon-picker name="links[__INDEX__][icon]" />
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold text-black">URL</label>
            <input type="text" name="links[__INDEX__][url]" class="form-control"
                placeholder="https://example.com">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm removeLinkBtn w-100">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
</div>
-->


{{-- CREATE STAFF MEMBER (FULL SCREEN + TABS) --}}
<div class="modal fade" id="createStaffMemberModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="createStaffMemberForm">
            @csrf
            <input type="hidden" name="staff_section_id" id="createMemberGroupId">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-7">
                {{-- TABS --}}
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active fw-semibold text-black" data-bs-toggle="tab"
                            href="#create_tab_basic" role="tab">
                            Basic Info
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#create_tab_bio"
                            role="tab">
                            Bio
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#create_tab_edu_exp"
                            role="tab">
                            Education + Experience
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab"
                            href="#create_tab_scholarship" role="tab">
                            Scholarship
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#create_tab_research"
                            role="tab">
                            Research
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#create_tab_teaching"
                            role="tab">
                            Teaching
                        </a>
                    </li>
                </ul>

                <div class="tab-content">

                    {{-- =======================
                        TAB: BASIC INFO
                    ======================== --}}
                    <div class="tab-pane fade show active" id="create_tab_basic" role="tabpanel">
                        <div class="row g-4">

                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-black">Image</label>
                                <x-metronic.image-input name="image" id="memberImage"></x-metronic.image-input>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Name</label>
                                        <input type="text" class="form-control form-control-sm slug-source"
                                            name="name" id="memberCreateName" placeholder="Enter name">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Designation</label>
                                        <input type="text" class="form-control form-control-sm" name="designation"
                                            placeholder="Designation">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Email</label>
                                        <input type="email" class="form-control form-control-sm" name="email"
                                            placeholder="Email">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Phone</label>
                                        <input type="text" class="form-control form-control-sm" name="phone"
                                            placeholder="Phone">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Mobile</label>
                                        <input type="text" class="form-control form-control-sm" name="mobile"
                                            placeholder="Mobile (preferred for UUID)">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Status</label>
                                        <select class="form-select form-select-sm" name="status"
                                            data-control="select2" data-allow-clear="true">
                                            <option value="published">Published</option>
                                            <option value="draft">Draft</option>
                                            <option value="archived">Archived</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-black">Address</label>
                                <textarea class="form-control form-control-sm" name="address" rows="2" placeholder="Address"></textarea>
                            </div>

                            <div class="col-md-12">
                                <hr>
                                <h6 class="fw-bold mb-3">Links (Social / Scholar / Website)</h6>

                                <div id="staffLinksRepeater">
                                    <div class="row g-3 link-row mb-2">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold text-black">Icon</label>
                                            <x-metronic.icon-picker name="links[0][icon]" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-black">URL</label>
                                            <input type="text" name="links[0][url]" class="form-control"
                                                placeholder="https://example.com">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <a href="javascript:void(0);" class="removeLinkBtn w-100">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-light-primary btn-sm" id="addStaffLinkBtn">
                                    <i class="fas fa-plus me-2"></i>Add Link
                                </button>
                            </div>

                        </div>
                    </div>

                    {{-- =======================
                        TAB: BIO
                    ======================== --}}
                    <div class="tab-pane fade" id="create_tab_bio" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <x-metronic.editor name="bio" label="Bio" :value="old('bio')" rows="7" />
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-black">Research Interest</label>
                                <textarea class="form-control" name="research_interest" rows="3" placeholder="Research interest"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: EDUCATION + EXPERIENCE
                    ======================== --}}
                    <div class="tab-pane fade" id="create_tab_edu_exp" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <x-metronic.editor name="education" label="Education" :value="old('education')"
                                    rows="7" />
                            </div>

                            <div class="col-md-12">
                                <x-metronic.editor name="experience" label="Experience" :value="old('experience')"
                                    rows="7" />
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: SCHOLARSHIP
                    ======================== --}}
                    <div class="tab-pane fade" id="create_tab_scholarship" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <x-metronic.editor name="scholarship" label="Scholarship" :value="old('scholarship')"
                                    rows="7" />
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: RESEARCH
                    ======================== --}}
                    <div class="tab-pane fade" id="create_tab_research" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <x-metronic.editor name="research" label="Research" :value="old('research')"
                                    rows="7" />
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: TEACHING
                    ======================== --}}
                    <div class="tab-pane fade" id="create_tab_teaching" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <x-metronic.editor name="teaching" label="Teaching" :value="old('teaching')"
                                    rows="7" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <div class="text-muted small">
                    Tip: Use tabs to fill all profile sections.
                </div>
                <div>
                    <button type="button" class="btn btn-outline btn-outline-danger me-7"
                        data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-outline btn-outline-primary">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>


{{-- EDIT STAFF MEMBER (FULL SCREEN + TABS) --}}
<div class="modal fade" id="editStaffMemberModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="editStaffMemberForm">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-7">
                {{-- TABS --}}
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active fw-semibold text-black" data-bs-toggle="tab" href="#edit_tab_basic"
                            role="tab">
                            Basic Info
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#edit_tab_bio"
                            role="tab">
                            Bio
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#edit_tab_edu_exp"
                            role="tab">
                            Education + Experience
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#edit_tab_scholarship"
                            role="tab">
                            Scholarship
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#edit_tab_research"
                            role="tab">
                            Research
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#edit_tab_teaching"
                            role="tab">
                            Teaching
                        </a>
                    </li>
                </ul>

                <div class="tab-content">

                    {{-- =======================
                        TAB: BASIC INFO
                    ======================== --}}
                    <div class="tab-pane fade show active" id="edit_tab_basic" role="tabpanel">
                        <div class="row g-4">

                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-black">Image</label>
                                <x-metronic.image-input name="image"
                                    id="editMemberImagePicker"></x-metronic.image-input>
                            </div>

                            <div class="col-md-9">
                                <div class="row">

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Name</label>
                                        <input type="text" class="form-control form-control-sm slug-source-edit"
                                            name="name" id="editMemberName">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Designation</label>
                                        <input type="text" class="form-control form-control-sm" name="designation"
                                            id="editMemberDesignation">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Email</label>
                                        <input type="email" class="form-control form-control-sm" name="email"
                                            id="editMemberEmail">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Phone</label>
                                        <input type="text" class="form-control form-control-sm" name="phone"
                                            id="editMemberPhone">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Mobile</label>
                                        <input type="text" class="form-control form-control-sm" name="mobile"
                                            id="editMemberMobile">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Status</label>
                                        <select class="form-select form-select-sm" name="status"
                                            id="editMemberStatus" data-control="select2" data-allow-clear="true">
                                            <option value="published">Published</option>
                                            <option value="draft">Draft</option>
                                            <option value="archived">Archived</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold text-black">Address</label>
                                        <textarea class="form-control" name="address" id="editMemberAddress" rows="2"></textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <hr>
                                        <h6 class="fw-bold mb-3">Links</h6>

                                        <div id="editStaffLinksRepeater"></div>

                                        <button type="button" class="btn btn-light-primary btn-sm"
                                            id="addStaffLinkBtnEdit">
                                            <i class="fa fa-plus me-2"></i>Add Link
                                        </button>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- =======================
                        TAB: BIO
                    ======================== --}}
                    <div class="tab-pane fade" id="edit_tab_bio" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                {{-- IMPORTANT: UNIQUE ID for TinyMCE --}}
                                <x-metronic.editor id="edit_member_bio" name="bio" label="Bio"
                                    :value="old('bio')" rows="7" />
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-black">Research Interest</label>
                                <textarea class="form-control" name="research_interest" id="editMemberResearchInterest" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: EDUCATION + EXPERIENCE
                    ======================== --}}
                    <div class="tab-pane fade" id="edit_tab_edu_exp" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                {{-- UNIQUE ID --}}
                                <x-metronic.editor id="edit_member_education" name="education" label="Education"
                                    :value="old('education')" rows="7" />
                            </div>

                            <div class="col-md-12">
                                {{-- UNIQUE ID --}}
                                <x-metronic.editor id="edit_member_experience" name="experience" label="Experience"
                                    :value="old('experience')" rows="7" />
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: SCHOLARSHIP
                    ======================== --}}
                    <div class="tab-pane fade" id="edit_tab_scholarship" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                {{-- UNIQUE ID --}}
                                <x-metronic.editor id="edit_member_scholarship" name="scholarship"
                                    label="Scholarship" :value="old('scholarship')" rows="7" />
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: RESEARCH
                    ======================== --}}
                    <div class="tab-pane fade" id="edit_tab_research" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                {{-- UNIQUE ID --}}
                                <x-metronic.editor id="edit_member_research" name="research" label="Research"
                                    :value="old('research')" rows="7" />
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: TEACHING
                    ======================== --}}
                    <div class="tab-pane fade" id="edit_tab_teaching" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                {{-- UNIQUE ID --}}
                                <x-metronic.editor id="edit_member_teaching" name="teaching" label="Teaching"
                                    :value="old('teaching')" rows="7" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer d-flex justify-content-end">
                <button type="button" class="btn btn-outline btn-outline-danger rounded-1 me-7"
                    data-bs-dismiss="modal">
                    Close
                </button>
                <button class="btn btn-outline btn-outline-success rounded-1">
                    Update
                </button>
            </div>

        </form>
    </div>
</div>


{{-- HIDDEN TEMPLATES FOR LINKS (CREATE + EDIT) --}}
<div id="staffLinkTemplate" class="d-none">
    <div class="row g-3 link-row mb-2">
        <div class="col-md-4">
            <label class="form-label fw-semibold text-black">Icon</label>
            <x-metronic.icon-picker name="links[__INDEX__][icon]" />
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold text-black">URL</label>
            <input type="text" name="links[__INDEX__][url]" class="form-control"
                placeholder="https://example.com">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="javascript:void(0);" class="removeLinkBtn w-100">
                <i class="fas fa-trash-alt text-danger"></i>
            </a>
        </div>
    </div>
</div>

<div id="staffLinkTemplateEdit" class="d-none">
    <div class="row g-3 link-row mb-2">
        <div class="col-md-4">
            <label class="form-label fw-semibold text-black">Icon</label>
            <x-metronic.icon-picker name="links[__INDEX__][icon]" />
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold text-black">URL</label>
            <input type="text" name="links[__INDEX__][url]" class="form-control"
                placeholder="https://example.com">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="javascript:void(0);" class="removeLinkBtn w-100">
                <i class="fas fa-trash-alt text-danger"></i>
            </a>
            {{-- <button type="button" class="btn btn-danger btn-sm removeLinkBtn w-100">
                <i class="fa fa-trash"></i>
            </button> --}}
        </div>
    </div>
</div>
