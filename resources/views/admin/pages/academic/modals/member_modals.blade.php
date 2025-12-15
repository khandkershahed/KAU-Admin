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
                            <label class="form-label fw-semibold">Image</label>
                            <x-metronic.image-input name="image" id="memberImage"></x-metronic.image-input>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Name</label>
                            <input type="text"
                                class="form-control slug-source"
                                name="name"
                                id="memberCreateName"
                                placeholder="Enter name">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Designation</label>
                            <input type="text"
                                class="form-control"
                                name="designation"
                                placeholder="Designation">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email"
                                class="form-control"
                                name="email"
                                placeholder="Email">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text"
                                class="form-control"
                                name="phone"
                                placeholder="Phone">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
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
                                        <label class="form-label fw-semibold">Icon</label>
                                        <x-metronic.icon-picker name="links[0][icon]" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">URL</label>
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
                            <label class="form-label fw-semibold">Image</label>
                            <x-metronic.image-input name="image" id="editMemberImagePicker"></x-metronic.image-input>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Name</label>
                            <input type="text"
                                class="form-control slug-source-edit"
                                name="name"
                                id="editMemberName">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Designation</label>
                            <input type="text"
                                class="form-control"
                                name="designation"
                                id="editMemberDesignation">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email"
                                class="form-control"
                                name="email"
                                id="editMemberEmail">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text"
                                class="form-control"
                                name="phone"
                                id="editMemberPhone">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
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
                <label class="form-label fw-semibold">Icon</label>
                <x-metronic.icon-picker name="links[__INDEX__][icon]" />
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">URL</label>
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
                <label class="form-label fw-semibold">Icon</label>
                <x-metronic.icon-picker name="links[__INDEX__][icon]" />
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">URL</label>
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
-->

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
                        <label class="form-label fw-semibold">Image</label>
                        <x-metronic.image-input name="image" id="memberImage"></x-metronic.image-input>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" class="form-control slug-source" name="name" id="memberCreateName"
                            placeholder="Enter name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Designation</label>
                        <input type="text" class="form-control" name="designation" placeholder="Designation">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" class="form-control" name="phone" placeholder="Phone">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Mobile</label>
                        <input type="text" class="form-control" name="mobile"
                            placeholder="Mobile (preferred for UUID)">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea class="form-control" name="address" rows="2" placeholder="Address"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Research Interest</label>
                        <textarea class="form-control" name="research_interest" rows="2" placeholder="Research interest"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Bio</label>
                        <textarea class="form-control" name="bio" rows="3" placeholder="Bio"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Education</label>
                        <textarea class="form-control" name="education" rows="4" placeholder="Education"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Experience</label>
                        <textarea class="form-control" name="experience" rows="4" placeholder="Experience"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Scholarship</label>
                        <textarea class="form-control" name="scholarship" rows="4" placeholder="Scholarship"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Research</label>
                        <textarea class="form-control" name="research" rows="4" placeholder="Research"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Teaching</label>
                        <textarea class="form-control" name="teaching" rows="4" placeholder="Teaching"></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
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
                                    <label class="form-label fw-semibold">Icon</label>
                                    <x-metronic.icon-picker name="links[0][icon]" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">URL</label>
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
                        <label class="form-label fw-semibold">Image</label>
                        <x-metronic.image-input name="image" id="editMemberImagePicker"></x-metronic.image-input>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" class="form-control slug-source-edit" name="name"
                            id="editMemberName">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Designation</label>
                        <input type="text" class="form-control" name="designation" id="editMemberDesignation">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" name="email" id="editMemberEmail">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" class="form-control" name="phone" id="editMemberPhone">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Mobile</label>
                        <input type="text" class="form-control" name="mobile" id="editMemberMobile">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea class="form-control" name="address" id="editMemberAddress" rows="2"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Research Interest</label>
                        <textarea class="form-control" name="research_interest" id="editMemberResearchInterest" rows="2"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Bio</label>
                        <textarea class="form-control" name="bio" id="editMemberBio" rows="3"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Education</label>
                        <textarea class="form-control" name="education" id="editMemberEducation" rows="4"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Experience</label>
                        <textarea class="form-control" name="experience" id="editMemberExperience" rows="4"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Scholarship</label>
                        <textarea class="form-control" name="scholarship" id="editMemberScholarship" rows="4"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Research</label>
                        <textarea class="form-control" name="research" id="editMemberResearch" rows="4"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Teaching</label>
                        <textarea class="form-control" name="teaching" id="editMemberTeaching" rows="4"></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
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
            <label class="form-label fw-semibold">Icon</label>
            <x-metronic.icon-picker name="links[__INDEX__][icon]" />
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">URL</label>
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
            <label class="form-label fw-semibold">Icon</label>
            <x-metronic.icon-picker name="links[__INDEX__][icon]" />
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">URL</label>
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
