<x-admin-app-layout :title="'Departments & Staff Management'">
    <style>
        .list-group-item.active {
            z-index: 2;
            color: #000000;
            background-color: mintcream;
            border-color: #7700f7;
        }
    </style>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold">Departments & Staff</h3>
            {{-- SITE SELECTOR --}}
            <form method="GET" action="{{ route('admin.academic.staff.index') }}" class="d-flex">
                <select name="site_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach ($sites as $site)
                        <option value="{{ $site->id }}"
                            {{ optional($selectedSite)->id == $site->id ? 'selected' : '' }}>
                            {{ $site->name }} ({{ $site->short_name }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="card-body pt-0">
            <input type="hidden" id="activeSiteId" value="{{ optional($selectedSite)->id }}">

            <div class="row">
                {{-- LEFT COLUMN — DEPARTMENTS --}}
                <div class="col-lg-3 border-end">
                    <div class="d-flex align-items-center justify-content-between my-3">
                        <h4 class="fw-semibold mb-0">Departments</h4>

                        @can('create academic departments')
                            <button class="btn btn-outline btn-outline-info btn-sm text-hover-white" data-bs-toggle="modal"
                                data-bs-target="#createDepartmentModal" data-site-id="{{ optional($selectedSite)->id }}"
                                id="openCreateDepartmentModalBtn">
                                <i class="fa fa-plus me-2"></i>Add
                            </button>
                        @endcan
                    </div>

                    <ul id="departmentsSortable" class="list-group departments-sortable">
                        @forelse($departments as $dept)
                            <li class="list-group-item d-flex align-items-center justify-content-between department-item"
                                data-id="{{ $dept->id }}" data-position="{{ $dept->position }}">

                                <div class="d-flex align-items-center flex-grow-1 dept-click-area"
                                    style="cursor:pointer;">
                                    {{-- SORT HANDLE --}}
                                    <span class="me-3 dept-sort-handle" style="cursor:grab;">
                                        <i class="fa-solid fa-up-down text-primary"></i>
                                    </span>

                                    {{-- LABEL --}}
                                    <div>
                                        <div class="fw-semibold">{{ $dept->title }}</div>
                                        <div>
                                            <small class="badge badge-light text-gray-700">{{ $dept->status }}</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- RIGHT SIDE BUTTONS --}}
                                <div class="d-flex align-items-center">

                                    {{-- STATUS TOGGLE --}}
                                    @can('edit academic departments')
                                        <label class="form-check form-switch form-switch-sm me-3">
                                            <input type="checkbox" class="form-check-input toggleDepartmentStatus"
                                                data-id="{{ $dept->id }}"
                                                {{ $dept->status === 'published' ? 'checked' : '' }}>
                                        </label>
                                    @endcan

                                    {{-- EDIT --}}
                                    @can('edit academic departments')
                                        <a href="javascript:void(0);" class="me-4 editDepartmentBtn"
                                            data-id="{{ $dept->id }}" data-title="{{ $dept->title }}"
                                            data-short-code="{{ $dept->short_code }}" data-slug="{{ $dept->slug }}"
                                            data-description="{{ $dept->description }}" data-status="{{ $dept->status }}"
                                            data-position="{{ $dept->position }}">
                                            <i class="fa-solid fa-pen-to-square fs-2 text-info"></i>
                                        </a>
                                    @endcan

                                    {{-- DELETE --}}
                                    @can('delete academic departments')
                                        <a href="{{ route('admin.academic.departments.destroy', $dept->id) }}"
                                            class="delete">
                                            <i class="fa-solid fa-trash text-danger fs-4"></i>
                                        </a>
                                    @endcan
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted small">No departments found.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- RIGHT COLUMN — AJAX LOADED CONTENT --}}
                <div class="col-lg-9">
                    <input type="hidden" id="currentDepartmentId" value="">
                    <div id="rightPanelLoader" class="py-5 text-center d-none">
                        <i class="fa fa-spinner fa-spin me-2"></i> Loading department details...
                    </div>

                    <div id="rightPanelContent" class="py-3 text-muted text-center">
                        Select a department to load its staff groups &amp; members.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODALS --}}
    @include('admin.pages.academic.modals.department_modals')
    @include('admin.pages.academic.modals.section_modals')
    @include('admin.pages.academic.modals.member_modals')
    @include('admin.pages.academic.modals.publication_modals')


    @push('scripts')
    @include('admin.pages.academic.partials.departments_js')
        @include('admin.pages.academic.partials.publications_js')
    @endpush

</x-admin-app-layout>
