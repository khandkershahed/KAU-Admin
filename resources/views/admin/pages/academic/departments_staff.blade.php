<x-admin-app-layout :title="'Departments & Staff Management'">

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold">Departments & Staff</h3>

            {{-- SITE SELECTOR --}}
            <form method="GET" action="{{ route('admin.academic.staff.index') }}" class="d-flex">
                <select name="site_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($sites as $site)
                        <option value="{{ $site->id }}" {{ optional($selectedSite)->id == $site->id ? 'selected' : '' }}>
                            {{ $site->name }} ({{ $site->short_name }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="card-body">
            <div class="row">

                {{-- LEFT COLUMN — DEPARTMENTS --}}
                <div class="col-lg-4 border-end">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="fw-semibold">Departments</h4>

                        @can('manage academic staff')
                            <button
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#createDepartmentModal"
                                data-site-id="{{ optional($selectedSite)->id }}"
                            >
                                <i class="fa fa-plus me-2"></i>Add
                            </button>
                        @endcan
                    </div>

                    <ul id="departmentsSortable" class="list-group">

                        @foreach($departments as $dept)
                            <li class="list-group-item d-flex align-items-center justify-content-between department-item"
                                data-id="{{ $dept->id }}"
                                data-load-url="{{ route('admin.academic.staff.index', ['site_id'=> $selectedSite->id, 'dept_id'=> $dept->id]) }}"
                            >
                                <div class="d-flex align-items-center flex-grow-1 dept-click-area" style="cursor:pointer;">
                                    {{-- SORT HANDLE --}}
                                    <span class="me-3 dept-sort-handle" style="cursor:grab;">
                                        <i class="fa-solid fa-up-down text-muted"></i>
                                    </span>

                                    {{-- LABEL --}}
                                    <div>
                                        <div class="fw-semibold">{{ $dept->title }}</div>
                                        <small class="text-muted">{{ $dept->short_code }}</small>
                                    </div>
                                </div>

                                {{-- RIGHT SIDE BUTTONS --}}
                                <div class="d-flex align-items-center">

                                    {{-- STATUS TOGGLE --}}
                                    @can('manage academic staff')
                                        <label class="form-check form-switch form-switch-sm me-3">
                                            <input type="checkbox"
                                                   class="form-check-input toggleDepartmentStatus"
                                                   data-id="{{ $dept->id }}"
                                                   {{ $dept->status === 'published' ? 'checked' : '' }}>
                                        </label>
                                    @endcan

                                    {{-- EDIT --}}
                                    <button
                                        class="btn btn-light-success btn-sm me-2 editDepartmentBtn"
                                        data-id="{{ $dept->id }}"
                                        data-title="{{ $dept->title }}"
                                        data-short-code="{{ $dept->short_code }}"
                                        data-slug="{{ $dept->slug }}"
                                        data-description="{{ $dept->description }}"
                                        data-status="{{ $dept->status }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editDepartmentModal"
                                    >
                                        <i class="fa-solid fa-pen fs-6"></i>
                                    </button>

                                    {{-- DELETE --}}
                                    <a href="{{ route('admin.academic.departments.destroy', $dept->id) }}"
                                       class="delete">
                                        <i class="fa-solid fa-trash text-danger fs-4"></i>
                                    </a>

                                </div>

                            </li>
                        @endforeach

                        @if($departments->isEmpty())
                            <li class="list-group-item text-muted small">No departments found.</li>
                        @endif

                    </ul>
                </div>

                {{-- RIGHT COLUMN — AJAX LOADED CONTENT --}}
                <div class="col-lg-8">
                    <div id="departmentDetailsContainer" class="py-3 text-muted text-center">
                        Select a department to load its staff groups & members.
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODALS (sent in Message 2) --}}
    @include('admin.pages.academic.modals.department_modals')
    @include('admin.pages.academic.modals.section_modals')
    @include('admin.pages.academic.modals.member_modals')

    @push('scripts')
        @include('admin.pages.academic.partials.departments_js')
    @endpush

</x-admin-app-layout>

