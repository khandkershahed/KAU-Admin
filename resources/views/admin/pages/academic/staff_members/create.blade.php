<x-admin-app-layout :title="'Add Staff Member'">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h3 class="card-title fw-bold mb-0">Add Staff Member</h3>
                <div class="small text-muted">
                    Department: <b>{{ $department?->title }}</b> | Group: <b>{{ $group->title }}</b>
                </div>
            </div>
            <a href="{{ route('admin.academic.staff.index', ['site_id' => $group->academic_site_id, 'department_id' => $group->academic_department_id]) }}"
               class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <form action="{{ route('admin.academic.staff-members.store', $group->id) }}"
              method="POST"
              enctype="multipart/form-data"
              novalidate>
            @csrf

            <input type="hidden" name="redirect_to"
                   value="{{ route('admin.academic.staff.index', ['site_id' => $group->academic_site_id, 'department_id' => $group->academic_department_id]) }}">

            {{-- optional safety: keeps modal-style hidden field too --}}
            <input type="hidden" name="staff_section_id" value="{{ $group->id }}">

            <div class="card-body px-7">

                {{-- TABS --}}
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active fw-semibold text-black" data-bs-toggle="tab"
                           href="#create_tab_basic" role="tab">
                            | Basic Info |
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab"
                           href="#create_tab_bio" role="tab">
                            | Bio |
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab"
                           href="#create_tab_edu_exp" role="tab">
                            | Education + Experience |
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab"
                           href="#create_tab_employment_institutional" role="tab">
                            | Employment History + Institutional Member |
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab"
                           href="#create_tab_research" role="tab">
                            | Consultancy + Research |
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
                                        <label class="form-label fw-semibold text-black">
                                            Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               name="name"
                                               value="{{ old('name') }}"
                                               placeholder="Enter name"
                                               required>
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Designation</label>
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               name="designation"
                                               value="{{ old('designation') }}"
                                               placeholder="Designation">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Email</label>
                                        <input type="email"
                                               class="form-control form-control-sm"
                                               name="email"
                                               value="{{ old('email') }}"
                                               placeholder="Email">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Phone</label>
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               name="phone"
                                               value="{{ old('phone') }}"
                                               placeholder="Phone">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Mobile</label>
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               name="mobile"
                                               value="{{ old('mobile') }}"
                                               placeholder="Mobile (preferred for UUID)">
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Status</label>
                                        <select class="form-select form-select-sm"
                                                name="status"
                                                data-control="select2"
                                                data-allow-clear="true">
                                            <option value="published" @selected(old('status','published')==='published')>Published</option>
                                            <option value="draft" @selected(old('status')==='draft')>Draft</option>
                                            <option value="archived" @selected(old('status')==='archived')>Archived</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label class="form-label fw-semibold text-black">Position</label>
                                        <input type="number"
                                               class="form-control form-control-sm"
                                               name="position"
                                               value="{{ old('position', 0) }}">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold text-black">Address</label>
                                        <textarea class="form-control form-control-sm"
                                                  name="address"
                                                  rows="2"
                                                  placeholder="Address">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr>
                                <h6 class="fw-bold mb-3">Links (Social / Scholar / Website)</h6>

                                <div id="staffLinksRepeater">
                                    @php($oldLinks = old('links', []))
                                    @if(!empty($oldLinks))
                                        @foreach($oldLinks as $i => $l)
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
                                <textarea class="form-control"
                                          name="research_interest"
                                          rows="3"
                                          placeholder="Research interest">{{ old('research_interest') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: EDUCATION + EXPERIENCE
                    ======================== --}}
                    <div class="tab-pane fade" id="create_tab_edu_exp" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <x-metronic.editor name="education" label="Education" :value="old('education')" rows="3" />
                            </div>

                            <div class="col-md-12">
                                <x-metronic.editor name="experience" label="Experience" :value="old('experience')" rows="3" />
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: EMPLOYMENT + INSTITUTIONAL
                    ======================== --}}
                    <div class="tab-pane fade" id="create_tab_employment_institutional" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <x-metronic.editor name="employment_history"
                                                  label="Employment History"
                                                  :value="old('employment_history')"
                                                  rows="3" />
                            </div>
                            <div class="col-md-12">
                                <x-metronic.editor name="institutional_member"
                                                  label="Institutional Member"
                                                  :value="old('institutional_member')"
                                                  rows="3" />
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        TAB: RESEARCH
                    ======================== --}}
                    <div class="tab-pane fade" id="create_tab_research" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <x-metronic.editor name="consultancy"
                                                  label="Consultancy"
                                                  :value="old('consultancy')"
                                                  rows="3" />
                            </div>

                            <div class="col-md-12">
                                <x-metronic.editor name="research"
                                                  label="Founded Research Project"
                                                  :value="old('research')"
                                                  rows="3" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <div class="text-muted small">
                    Tip: Use tabs to fill all profile sections.
                </div>
                <div>
                    <a href="{{ route('admin.academic.staff.index', ['site_id' => $group->academic_site_id, 'department_id' => $group->academic_department_id]) }}"
                       class="btn btn-outline btn-outline-danger me-7 rounded-0">
                        Close
                    </a>
                    <button class="btn btn-outline btn-outline-primary rounded-0">
                        Create
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- HIDDEN TEMPLATE FOR LINKS --}}
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

    @push('scripts')
        <script>
            (function () {
                const repeater = document.getElementById('staffLinksRepeater');
                const addBtn = document.getElementById('addStaffLinkBtn');
                const tpl = document.getElementById('staffLinkTemplate');

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
