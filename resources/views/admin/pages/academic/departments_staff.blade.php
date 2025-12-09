<x-admin-app-layout :title="'Academic Departments & Staff'">

    <div class="card">
        <div class="card-header align-items-center">
            <h3 class="card-title fw-bold">Academic Departments & Staff</h3>

            <div class="d-flex">

                {{-- SITE FILTER --}}
                <form method="GET" action="{{ route('admin.academic.staff.index') }}" class="me-3">
                    <select name="site_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach ($sites as $site)
                            <option value="{{ $site->id }}"
                                {{ optional($selectedSite)->id == $site->id ? 'selected' : '' }}>
                                {{ $site->name }} ({{ $site->short_name }})
                            </option>
                        @endforeach
                    </select>
                </form>

                @can('create academic departments')
                    @if ($selectedSite)
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#createDepartmentModal">
                            <i class="fa fa-plus me-2"></i> Add Department
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        {{-- =======================
             CARD BODY
        ======================== --}}
        <div class="card-body">

            @if (!$selectedSite)
                <p class="text-muted mb-0">
                    Please create/select an Academic Site in Module 1 to manage departments & staff.
                </p>
            @else
                <div class="accordion" id="departmentsAccordion">

                    @forelse($departments as $department)
                        <div class="accordion-item department-item mb-5" data-id="{{ $department->id }}"
                            data-sort-url="{{ route('admin.academic.departments.sort', $selectedSite->id) }}">

                            {{-- =======================
                                 DEPARTMENT HEADER
                            ======================== --}}
                            <div class="accordion-header d-flex align-items-center justify-content-between px-3 py-2"
                                style="background: aliceblue;">

                                {{-- LEFT SIDE (SORT + TITLE) --}}
                                <div class="d-flex align-items-center flex-grow-1 me-2 department-sort"
                                    style="cursor: grab;">

                                    {{-- SORT HANDLE --}}
                                    <span class="me-3">
                                        <i class="fa-solid fa-up-down text-muted"></i>
                                    </span>

                                    {{-- TITLE --}}
                                    <button
                                        class="accordion-button collapsed py-2 px-2 shadow-none bg-transparent flex-grow-1"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#department-{{ $department->id }}">
                                        <span class="fw-semibold">
                                            {{ $department->title }}
                                        </span>
                                        @if ($department->short_code)
                                            <span class="badge bg-light ms-2">{{ $department->short_code }}</span>
                                        @endif
                                        @if ($department->slug)
                                            <small class="text-muted ms-2">/ {{ $department->slug }}</small>
                                        @endif
                                    </button>
                                </div>

                                {{-- RIGHT SIDE ACTIONS --}}
                                <div class="d-flex align-items-center ms-3">

                                    {{-- ACTIVE TOGGLE (simple select) --}}
                                    @can('edit academic departments')
                                        <select class="form-select form-select-sm me-2 departmentStatusSelect"
                                            data-url="{{ route('admin.academic.departments.update', $department->id) }}">
                                            <option value="1" {{ $department->is_active ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ !$department->is_active ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    @endcan

                                    {{-- EDIT DEPARTMENT BTN --}}
                                    @can('edit academic departments')
                                        <button class="btn btn-light-success btn-sm me-2 editDepartmentBtn"
                                            data-id="{{ $department->id }}" data-title="{{ $department->title }}"
                                            data-short_code="{{ $department->short_code }}"
                                            data-slug="{{ $department->slug }}"
                                            data-description="{{ $department->description }}"
                                            data-active="{{ $department->is_active ? 1 : 0 }}">
                                            <i class="fa-solid fa-pen-to-square fs-6"></i>
                                        </button>
                                    @endcan

                                    {{-- DELETE DEPARTMENT --}}
                                    @can('delete academic departments')
                                        <a href="{{ route('admin.academic.departments.destroy', $department->id) }}"
                                            class="delete">
                                            <i class="fa-solid fa-trash text-danger fs-4"></i>
                                        </a>
                                    @endcan
                                </div>
                            </div>

                            {{-- =======================
                                 DEPARTMENT BODY
                            ======================== --}}
                            <div id="department-{{ $department->id }}"
                                class="accordion-collapse collapse @if ($loop->first) show @endif">

                                <div class="accordion-body">

                                    {{-- ADD STAFF GROUP BUTTON --}}
                                    @can('create academic staff')
                                        <button class="btn btn-sm btn-primary mb-4 float-end createStaffGroupBtn"
                                            data-department-id="{{ $department->id }}">
                                            <i class="fa fa-plus me-2"></i> Add Staff Group
                                        </button>
                                    @endcan

                                    <div class="clearfix"></div>

                                    {{-- STAFF GROUPS & MEMBERS --}}
                                    <div class="staff-groups-wrapper" data-department-id="{{ $department->id }}"
                                        data-sort-url="{{ route('admin.academic.groups.staff.sort', $department->id) }}">

                                        @forelse($department->staffSections->sortBy('position') as $section)
                                            <div class="card mb-4 staff-group-card" data-id="{{ $section->id }}">

                                                {{-- STAFF GROUP HEADER --}}
                                                <div class="card-header d-flex align-items-center justify-content-between py-2"
                                                    style="background: #f9f9f9;">

                                                    <div class="d-flex align-items-center flex-grow-1 group-sort"
                                                        style="cursor: grab;">
                                                        <span class="me-3">
                                                            <i class="fa-solid fa-up-down text-muted"></i>
                                                        </span>
                                                        <span class="fw-semibold">
                                                            {{ $section->title }}
                                                        </span>
                                                        <span class="badge bg-light ms-3">
                                                            {{ $section->members->count() }} Members
                                                        </span>
                                                    </div>

                                                    <div class="d-flex align-items-center ms-3">

                                                        @can('edit academic staff')
                                                            <button
                                                                class="btn btn-light-success btn-sm me-2 editStaffGroupBtn"
                                                                data-id="{{ $section->id }}"
                                                                data-title="{{ $section->title }}">
                                                                <i class="fa-solid fa-pen-to-square fs-6"></i>
                                                            </button>
                                                        @endcan

                                                        @can('delete academic staff')
                                                            <a href="{{ route('admin.academic.groups.staff.destroy', $section->id) }}"
                                                                class="delete">
                                                                <i class="fa-solid fa-trash text-danger fs-4"></i>
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </div>

                                                {{-- STAFF GROUP BODY (MEMBERS TABLE) --}}
                                                <div class="card-body">

                                                    @can('create academic staff')
                                                        <button
                                                            class="btn btn-sm btn-primary mb-3 createStaffMemberBtn float-end"
                                                            data-group-id="{{ $section->id }}">
                                                            <i class="fa fa-plus me-2"></i> Add Member
                                                        </button>
                                                    @endcan

                                                    <div class="clearfix"></div>

                                                    <div class="table-responsive w-100">
                                                        <table
                                                            class="table px-2 border table-row-bordered table-hover membersTable">
                                                            <thead style="background: beige;">
                                                                <tr>
                                                                    <th style="width:50px;">Sort</th>
                                                                    <th>Name</th>
                                                                    <th>Designation</th>
                                                                    <th>Email</th>
                                                                    <th>Phone</th>
                                                                    <th class="text-end">Actions</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody class="member-sortable"
                                                                data-group-id="{{ $section->id }}"
                                                                data-sort-url="{{ route('admin.academic.members.sort', $section->id) }}">

                                                                @forelse($section->members->sortBy('position') as $member)
                                                                    <tr class="member-row"
                                                                        data-id="{{ $member->id }}">

                                                                        <td style="cursor: grab;">
                                                                            <i
                                                                                class="fa-solid fa-up-down text-muted"></i>
                                                                        </td>

                                                                        <td style="cursor: grab;">
                                                                            {{ $member->name }}
                                                                        </td>

                                                                        <td style="cursor: grab;">
                                                                            {{ $member->designation }}
                                                                        </td>

                                                                        <td style="cursor: grab;">
                                                                            {{ $member->email }}
                                                                        </td>

                                                                        <td style="cursor: grab;">
                                                                            {{ $member->phone }}
                                                                        </td>

                                                                        <td class="text-end">

                                                                            @can('edit academic staff')
                                                                                <button
                                                                                    class="btn btn-light-success btn-sm editStaffMemberBtn me-2"
                                                                                    data-id="{{ $member->id }}"
                                                                                    data-group-id="{{ $section->id }}"
                                                                                    data-name="{{ $member->name }}"
                                                                                    data-designation="{{ $member->designation }}"
                                                                                    data-email="{{ $member->email }}"
                                                                                    data-phone="{{ $member->phone }}"
                                                                                    data-links='@json($member->links ?? [])'>
                                                                                    <i class="fa-solid fa-pen fs-6"></i>
                                                                                </button>
                                                                            @endcan

                                                                            @can('delete academic staff')
                                                                                <a href="{{ route('admin.academic.members.destroy', $member->id) }}"
                                                                                    class="delete">
                                                                                    <i
                                                                                        class="fa-solid fa-trash text-danger fs-4"></i>
                                                                                </a>
                                                                            @endcan

                                                                        </td>

                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="6"
                                                                            class="text-center text-muted">
                                                                            No members added yet.
                                                                        </td>
                                                                    </tr>
                                                                @endforelse

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted">No staff groups in this department.</p>
                                        @endforelse

                                    </div>
                                </div>
                            </div>

                        </div>
                    @empty
                        <p class="text-muted mb-0">No departments found for this site.</p>
                    @endforelse

                </div>
            @endif

        </div>
    </div>

    {{-- =======================
         MODALS
    ======================== --}}

    {{-- Create Department --}}
    @if ($selectedSite)
        <div class="modal fade" id="createDepartmentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('admin.academic.departments.store', $selectedSite->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add Department ({{ $selectedSite->short_name }})</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Department
                                        Title</x-metronic.label>
                                    <input type="text" name="title" class="form-control form-control-sm"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Short Code</x-metronic.label>
                                    <input type="text" name="short_code" class="form-control form-control-sm"
                                        placeholder="VAH, AGM, FBG...">
                                </div>
                                <div class="col-md-3">
                                    <x-metronic.label class="col-form-label fw-bold fs-6">Slug</x-metronic.label>
                                    <input type="text" name="slug" class="form-control form-control-sm"
                                        placeholder="anatomy-histology">
                                </div>
                            </div>

                            <div class="mb-3">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Description</x-metronic.label>
                                <textarea name="description" class="form-control form-control-sm" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light btn-sm"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm">Save Department</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Department --}}
    <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="editDepartmentForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Department
                                    Title</x-metronic.label>
                                <input type="text" name="title" id="editDeptTitle"
                                    class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-3">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Short Code</x-metronic.label>
                                <input type="text" name="short_code" id="editDeptShortCode"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Slug</x-metronic.label>
                                <input type="text" name="slug" id="editDeptSlug"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="mb-3">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Description</x-metronic.label>
                            <textarea name="description" id="editDeptDescription" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" value="1"
                                id="editDeptActive">
                            <label for="editDeptActive" class="form-check-label">Active</label>
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

    {{-- Create Staff Group --}}
    <div class="modal fade" id="createStaffGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form id="createStaffGroupForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Staff Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Group Title</x-metronic.label>
                        <input type="text" name="title" class="form-control form-control-sm" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Staff Group --}}
    <div class="modal fade" id="editStaffGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form id="editStaffGroupForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Staff Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <x-metronic.label class="col-form-label fw-bold fs-6">Group Title</x-metronic.label>
                        <input type="text" name="title" id="editGroupTitle"
                            class="form-control form-control-sm" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Create Staff Member --}}
    <div class="modal fade" id="createStaffMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="createStaffMemberForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Staff Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Name</x-metronic.label>
                                <input type="text" name="name" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Designation</x-metronic.label>
                                <input type="text" name="designation" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Email</x-metronic.label>
                                <input type="email" name="email" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Phone</x-metronic.label>
                                <input type="text" name="phone" class="form-control form-control-sm">
                            </div>
                        </div>

                        {{-- Links as JSON for now (flexible) --}}
                        <div class="mb-3">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Links (JSON)</x-metronic.label>
                            <textarea name="links_json" class="form-control form-control-sm" rows="3"
                                placeholder='[{"icon":"fa-solid fa-google-scholar","url":"https://..."}, ...]'></textarea>
                            <small class="text-muted">Optional: keep empty or paste valid JSON array of
                                icon+url.</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Staff Member --}}
    <div class="modal fade" id="editStaffMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="editStaffMemberForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Staff Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Name</x-metronic.label>
                                <input type="text" name="name" id="editMemberName"
                                    class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Designation</x-metronic.label>
                                <input type="text" name="designation" id="editMemberDesignation"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Email</x-metronic.label>
                                <input type="email" name="email" id="editMemberEmail"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <x-metronic.label class="col-form-label fw-bold fs-6">Phone</x-metronic.label>
                                <input type="text" name="phone" id="editMemberPhone"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="mb-3">
                            <x-metronic.label class="col-form-label fw-bold fs-6">Links (JSON)</x-metronic.label>
                            <textarea name="links_json" id="editMemberLinks" class="form-control form-control-sm" rows="3"></textarea>
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

    {{-- =======================
         PAGE JS
    ======================== --}}
    @push('scripts')
        <script>
            const csrfToken = "{{ csrf_token() }}";

            // ========= GLOBAL DELETE (SweetAlert) =========
            $(document).on('click', 'a.delete', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This item will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(json => {
                            if (json.success) {
                                Swal.fire('Deleted!', 'Item deleted successfully.', 'success')
                                    .then(() => window.location.reload());
                            } else {
                                Swal.fire('Error', json.message || 'Failed to delete.', 'error');
                            }
                        })
                        .catch(() => Swal.fire('Error', 'Failed to delete.', 'error'));
                });
            });

            // ========= DEPARTMENT STATUS QUICK UPDATE =========
            $(document).on('change', '.departmentStatusSelect', function() {
                const select = this;
                const url = select.dataset.url;
                const isActive = select.value === '1';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        title: '', // will be overwritten in controller if you want; or ignore
                        is_active: isActive ? 1 : 0
                    })
                }).then(() => {
                    // Not perfect, but we already have edit forms; you can enhance this
                });
            });

            // ========= SORT DEPARTMENTS =========
            $('#departmentsAccordion').sortable({
                handle: '.department-sort',
                update: function() {
                    const order = [];
                    $('#departmentsAccordion .department-item').each(function() {
                        order.push($(this).data('id'));
                    });

                    const sortUrl = $('#departmentsAccordion .department-item:first').data('sort-url');
                    if (!sortUrl) return;

                    $.post(sortUrl, {
                        order,
                        _token: csrfToken
                    }, function(res) {
                        Swal.fire('Updated', res.message, 'success');
                    }).fail(function() {
                        Swal.fire('Error', 'Failed to update order.', 'error');
                    });
                }
            });

            // ========= SORT STAFF GROUPS (Sections) =========
            $('.staff-groups-wrapper').each(function() {
                const wrapper = $(this);
                const sortUrl = wrapper.data('sort-url');

                wrapper.sortable({
                    handle: '.group-sort',
                    items: '.staff-group-card',
                    update: function() {
                        const order = [];
                        wrapper.find('.staff-group-card').each(function() {
                            order.push($(this).data('id'));
                        });

                        $.post(sortUrl, {
                            order,
                            _token: csrfToken
                        }, function(res) {
                            Swal.fire('Updated', res.message, 'success');
                        }).fail(function() {
                            Swal.fire('Error', 'Failed to update staff group order.', 'error');
                        });
                    }
                });
            });

            // ========= SORT MEMBERS =========
            $('.member-sortable').each(function() {
                const tbody = $(this);
                const sortUrl = tbody.data('sort-url');

                tbody.sortable({
                    handle: '.member-row td:first-child',
                    items: '.member-row',
                    update: function() {
                        const order = [];
                        tbody.find('.member-row').each(function() {
                            order.push($(this).data('id'));
                        });

                        $.post(sortUrl, {
                            order,
                            _token: csrfToken
                        }, function(res) {
                            Swal.fire('Updated', res.message, 'success');
                        }).fail(function() {
                            Swal.fire('Error', 'Failed to update members order.', 'error');
                        });
                    }
                });
            });

            // ========= EDIT DEPARTMENT MODAL FILL =========
            $(document).on('click', '.editDepartmentBtn', function() {
                const btn = $(this);
                const id = btn.data('id');
                const title = btn.data('title');
                const shortCode = btn.data('short_code');
                const slug = btn.data('slug');
                const description = btn.data('description');
                const active = btn.data('active') == 1;

                const form = $('#editDepartmentForm');
                form.attr('action', "{{ url('admin/academic/departments') }}/" + id);

                $('#editDeptTitle').val(title);
                $('#editDeptShortCode').val(shortCode || '');
                $('#editDeptSlug').val(slug || '');
                $('#editDeptDescription').val(description || '');
                $('#editDeptActive').prop('checked', active);

                $('#editDepartmentModal').modal('show');
            });

            // ========= CREATE STAFF GROUP (SECTION) =========
            $(document).on('click', '.createStaffGroupBtn', function() {
                const deptId = $(this).data('department-id');
                const form = $('#createStaffGroupForm');
                form.attr('action', "{{ url('admin/academic/departments') }}/" + deptId + "/groups");
                form.trigger('reset');
                $('#createStaffGroupModal').modal('show');
            });

            // ========= EDIT STAFF GROUP =========
            $(document).on('click', '.editStaffGroupBtn', function() {
                const id = $(this).data('id');
                const title = $(this).data('title');
                const form = $('#editStaffGroupForm');

                form.attr('action', "{{ url('admin/academic/staff-groups') }}/" + id);
                $('#editGroupTitle').val(title);

                $('#editStaffGroupModal').modal('show');
            });

            // ========= CREATE STAFF MEMBER =========
            $(document).on('click', '.createStaffMemberBtn', function() {
                const groupId = $(this).data('group-id');
                const form = $('#createStaffMemberForm');
                form.attr('action', "{{ url('admin/academic/staff-groups') }}/" + groupId + "/members");
                form.trigger('reset');
                $('#createStaffMemberModal').modal('show');
            });

            // ========= EDIT STAFF MEMBER =========
            $(document).on('click', '.editStaffMemberBtn', function() {
                const btn = $(this);
                const id = btn.data('id');
                const name = btn.data('name');
                const designation = btn.data('designation');
                const email = btn.data('email');
                const phone = btn.data('phone');
                const links = btn.data('links') || [];

                const form = $('#editStaffMemberForm');
                form.attr('action', "{{ url('admin/academic/staff-members') }}/" + id);

                $('#editMemberName').val(name);
                $('#editMemberDesignation').val(designation || '');
                $('#editMemberEmail').val(email || '');
                $('#editMemberPhone').val(phone || '');
                $('#editMemberLinks').val(JSON.stringify(links));

                $('#editStaffMemberModal').modal('show');
            });

            // ========= HANDLE LINKS JSON IN CREATE / EDIT FORMS =========
            $('#createStaffMemberForm, #editStaffMemberForm').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const $form = $(form);
                const textarea = $form.find('textarea[name="links_json"]');
                let links = [];

                if (textarea.length && textarea.val().trim() !== '') {
                    try {
                        links = JSON.parse(textarea.val());
                    } catch (e) {
                        Swal.fire('Invalid JSON', 'Please provide valid JSON in Links field.', 'error');
                        return false;
                    }
                }

                // convert to proper fields for controller
                // we send links[] array via hidden input
                $form.find('input[name^="links["]').remove();
                if (Array.isArray(links)) {
                    links.forEach(function(item, index) {
                        $form.append(
                            `<input type="hidden" name="links[${index}][icon]" value="${item.icon || ''}">`);
                        $form.append(
                            `<input type="hidden" name="links[${index}][url]" value="${item.url || ''}">`);
                    });
                }

                form.submit();
            });
        </script>
    @endpush

</x-admin-app-layout>
