<x-admin-app-layout :title="'Edit Staff Member'">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h3 class="card-title fw-bold mb-0">Edit Staff Member</h3>
                <div class="small text-muted">
                    Department: <b>{{ $department?->title }}</b> | Group: <b>{{ $group?->title }}</b>
                </div>
            </div>

            <a href="{{ route('admin.academic.staff.index', ['site_id' => $member->academic_site_id, 'department_id' => $member->academic_department_id]) }}"
               class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <form action="{{ route('admin.academic.staff-members.update', $member->id) }}"
              method="POST"
              enctype="multipart/form-data"
              novalidate>
            @csrf
            @method('PUT')

            <input type="hidden" name="redirect_to"
                   value="{{ route('admin.academic.staff.index', ['site_id' => $member->academic_site_id, 'department_id' => $member->academic_department_id]) }}">

            <div class="card-body px-7">

                {{-- TABS --}}
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active fw-semibold text-black" data-bs-toggle="tab" href="#edit_tab_basic"
                           role="tab">
                            | Basic Info |
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#edit_tab_bio"
                           role="tab">
                            | Bio |
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#edit_tab_edu_exp"
                           role="tab">
                            | Education + Experience |
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab"
                           href="#edit_tab_employment_institutional" role="tab">
                            | Employment History + Institutional Member |
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#edit_tab_research"
                           role="tab">
                            | Consultancy + Research |
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
                                <x-metronic.image-input name="image" id="editMemberImagePicker"></x-metronic.image-input>

                                @if($member->image_path)
                                    <div class="mt-3">
                                        <div class="text-muted small mb-1">Current image</div>
                                        <img src="{{ asset('storage/'.$member->image_path) }}"
                                             class="rounded-circle"
                                             width="60"
                                             height="60"
                                             alt="image">
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-9">
                                <div class="row">

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               name="name"
                                               id="editMemberName"
                                               value="{{ old('name', $member->name) }}"
                                               required>
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Designation</label>
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               name="designation"
                                               id="editMemberDesignation"
                                               value="{{ old('designation', $member->designation) }}">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Email</label>
                                        <input type="email"
                                               class="form-control form-control-sm"
                                               name="email"
                                               id="editMemberEmail"
                                               value="{{ old('email', $member->email) }}">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Phone</label>
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               name="phone"
                                               id="editMemberPhone"
                                               value="{{ old('phone', $member->phone) }}">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Mobile</label>
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               name="mobile"
                                               id="editMemberMobile"
                                               value="{{ old('mobile', $member->mobile) }}">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Status</label>
                                        <select class="form-select form-select-sm"
                                                name="status"
                                                id="editMemberStatus"
                                                data-control="select2"
                                                data-allow-clear="true">
                                            <option value="published" @selected(old('status',$member->status)==='published')>Published</option>
                                            <option value="draft" @selected(old('status',$member->status)==='draft')>Draft</option>
                                            <option value="archived" @selected(old('status',$member->status)==='archived')>Archived</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Position</label>
                                        <input type="number"
                                               class="form-control form-control-sm"
                                               name="position"
                                               value="{{ old('position', $member->position) }}">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold text-black">Address</label>
                                        <textarea class="form-control"
                                                  name="address"
                                                  id="editMemberAddress"
                                                  rows="2">{{ old('address', $member->address) }}</textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <hr>
                                        <h6 class="fw-bold mb-3">Links</h6>

                                        <div id="editStaffLinksRepeater">
                                            @php($links = old('links', $member->links ?? []))
                                            @if(!empty($links))
                                                @foreach($links as $i => $l)
                                                    <div class="row g-3 link-row mb-2">
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold text-black">Icon</label>
                                                            <x-metronic.icon-picker name="links[{{ $i }}][icon]" />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold text-black">URL</label>
                                                            <input type="text"
                                                                   name="links[{{ $i }}][url]"
                                                                   class="form-control"
                                                                   value="{{ $l['url'] ?? '' }}"
                                                                   placeholder="https://example.com">
                                                        </div>
                                                        <div class="col-md-2 d-flex align-items-end">
                                                            <a href="javascript:void(0);" class="removeLinkBtn w-100">
                                                                <i class="fas fa-trash-alt text-danger"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
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
                                                        <a href="javascript:void(0);" class="removeLinkBtn w-100">
                                                            <i class="fas fa-trash-alt text-danger"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <button type="button" class="btn btn-light-primary btn-sm" id="addStaffLinkBtnEdit">
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
                                <x-metronic.editor id="edit_member_bio"
                                                  name="bio"
                                                  label="Bio"
                                                  :value="old('bio', $member->bio)"
                                                  rows="7" />
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-black">Research Interest</label>
                                <textarea class="form-control"
                                          name="research_interest"
                                          id="editMemberResearchInterest"
                                          rows="3">{{ old('research_interest', $member->research_interest) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: EDUCATION + EXPERIENCE
                    ======================== --}}
                    <div class="tab-pane fade" id="edit_tab_edu_exp" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <x-metronic.editor id="edit_member_education"
                                                  name="education"
                                                  label="Education"
                                                  :value="old('education', $member->education)"
                                                  rows="7" />
                            </div>

                            <div class="col-md-12">
                                <x-metronic.editor id="edit_member_experience"
                                                  name="experience"
                                                  label="Experience"
                                                  :value="old('experience', $member->experience)"
                                                  rows="7" />
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: EMPLOYMENT + INSTITUTIONAL
                    ======================== --}}
                    <div class="tab-pane fade" id="edit_tab_employment_institutional" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <x-metronic.editor id="edit_member_employment_history"
                                                  name="employment_history"
                                                  label="Employment History"
                                                  :value="old('employment_history', $member->employment_history)"
                                                  rows="3" />
                            </div>
                            <div class="col-md-12">
                                <x-metronic.editor id="edit_member_institutional_member"
                                                  name="institutional_member"
                                                  label="Institutional Member"
                                                  :value="old('institutional_member', $member->institutional_member)"
                                                  rows="3" />
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: RESEARCH
                    ======================== --}}
                    <div class="tab-pane fade" id="edit_tab_research" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <x-metronic.editor id="edit_member_consultancy"
                                                  name="consultancy"
                                                  label="Consultancy"
                                                  :value="old('consultancy', $member->consultancy)"
                                                  rows="3" />
                            </div>
                            <div class="col-md-12">
                                <x-metronic.editor id="edit_member_research"
                                                  name="research"
                                                  label="Founded Research Project"
                                                  :value="old('research', $member->research)"
                                                  rows="3" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer d-flex justify-content-end">
                <a href="{{ route('admin.academic.staff.index', ['site_id' => $member->academic_site_id, 'department_id' => $member->academic_department_id]) }}"
                   class="btn btn-outline btn-outline-danger rounded-0 me-7">
                    Close
                </a>

                <a href="{{ route('admin.academic.publications.create', $member->id) }}"
                   class="btn btn-outline btn-outline-primary rounded-0 me-7">
                    <i class="fa fa-book me-2"></i>Manage Publications
                </a>

                <button class="btn btn-outline btn-outline-success rounded-0">
                    Update
                </button>
            </div>
        </form>
    </div>

    {{-- HIDDEN TEMPLATE FOR LINKS (EDIT) --}}
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
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const repeater = document.getElementById('editStaffLinksRepeater');
                const addBtn = document.getElementById('addStaffLinkBtnEdit');
                const tpl = document.getElementById('staffLinkTemplateEdit');

                function getNextIndex() {
                    const rows = repeater.querySelectorAll('.link-row');
                    return rows.length;
                }

                addBtn.addEventListener('click', function () {
                    const idx = getNextIndex();
                    const html = tpl.innerHTML.replaceAll('__INDEX__', idx);
                    const temp = document.createElement('div');
                    temp.innerHTML = html.trim();
                    repeater.appendChild(temp.firstElementChild);
                });

                repeater.addEventListener('click', function (e) {
                    const btn = e.target.closest('.removeLinkBtn');
                    if (!btn) return;
                    const rows = repeater.querySelectorAll('.link-row');
                    if (rows.length <= 1) return;
                    btn.closest('.link-row').remove();
                });
            })();
        </script>
    @endpush
</x-admin-app-layout>
