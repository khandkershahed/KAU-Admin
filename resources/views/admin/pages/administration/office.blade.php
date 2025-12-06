<x-admin-app-layout :title="'Office: ' . $office->title">

    <div class="card shadow-sm">

        <!-- =========================
             CARD HEADER
        ========================== -->
        <div class="card-header d-flex align-items-center justify-content-between">

            <h3 class="card-title fw-bold">
                Office: {{ $office->title }}
            </h3>

            <div class="d-flex align-items-center">

                <!-- SEARCH -->
                <div class="input-group me-3" style="width: 280px;">
                    <input type="text" id="officeSearchInput" class="form-control form-control-sm"
                        placeholder="Search sections or members..." style="height: 36px; padding-right: 35px;">
                    <button type="button" id="clearOfficeSearchBtn" class="btn btn-danger"
                        style="height: 36px; display:none;">
                        <i class="fas fa-x"></i>
                    </button>
                </div>

                <!-- ADD SECTION -->
                @can('create admin section')
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createSectionModal">
                        <i class="fa fa-plus me-2"></i> Add Section
                    </button>
                @endcan

            </div>
        </div>

        <!-- =========================
             CARD BODY
        ========================== -->
        <div class="card-body">

            <div class="accordion" id="officeSectionsAccordion">

                @foreach ($sections as $section)
                    <div class="accordion-item mb-3 section-item" data-section-id="{{ $section->id }}">

                        <!-- ======================
                         SECTION HEADER
                    ======================= -->
                        <div class="accordion-header d-flex align-items-center justify-content-between px-3 py-2"
                            style="background: aliceblue;" id="sectionHeading{{ $section->id }}">

                            <!-- LEFT SIDE: Sort Handle + Title -->
                            <div class="d-flex align-items-center flex-grow-1 me-2 section-sort" style="cursor: grab;">

                                <!-- SORT HANDLE ICON -->
                                <span class="section-sort me-3" style="cursor: grab;">
                                    <i class="fa-solid fa-up-down text-muted"></i>
                                </span>

                                <!-- COLLAPSE TOGGLE BUTTON -->
                                <button
                                    class="accordion-button collapsed py-2 px-2 shadow-none bg-transparent flex-grow-1"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#sectionCollapse{{ $section->id }}" aria-expanded="false"
                                    aria-controls="sectionCollapse{{ $section->id }}">

                                    <span class="fw-semibold d-flex align-items-center">
                                        {{ $section->title }}

                                        <span class="badge bg-secondary ms-2">
                                            Members: {{ $section->members->count() }}
                                        </span>
                                    </span>
                                </button>
                            </div>

                            <!-- RIGHT SIDE ACTION BUTTONS -->
                            <div class="d-flex align-items-center ms-3">

                                @can('edit admin section')
                                    <button class="btn btn-light-success btn-sm me-2 editSectionBtn"
                                        data-id="{{ $section->id }}" data-title="{{ $section->title }}">
                                        <i class="fa-solid fa-pen-to-square fs-6"></i>
                                    </button>
                                @endcan

                                @can('delete admin section')
                                    <button class="btn btn-light-danger btn-sm deleteSectionBtn"
                                        data-id="{{ $section->id }}">
                                        <i class="fa-solid fa-trash fs-6"></i>
                                    </button>
                                @endcan

                            </div>
                        </div>

                        <!-- ======================
                         SECTION CONTENT
                    ======================= -->
                        <div id="sectionCollapse{{ $section->id }}" class="accordion-collapse collapse"
                            data-bs-parent="#officeSectionsAccordion">

                            <div class="accordion-body">

                                <!-- ADD MEMBER BUTTON -->
                                @can('create admin member')
                                    <button class="btn btn-outline btn-outline-info btn-active-info float-end btn-sm mb-3 createMemberBtn"
                                        data-section-id="{{ $section->id }}" data-bs-toggle="modal"
                                        data-bs-target="#createMemberModal">
                                        <i class="fas fa-plus me-2"></i> Add Member
                                    </button>
                                @endcan

                                <!-- MEMBERS TABLE -->
                                <div class="table-responsive w-100">
                                    <table class="table border table-row-bordered align-middle gy-3 memberTable"
                                        data-section="{{ $section->id }}">

                                        <thead style="background: beige;">
                                            <tr class="fw-bold">
                                                <th style="width: 40px;">Sort</th>
                                                <th>Photo</th>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th style="width: 160px;">Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody id="memberSortSection{{ $section->id }}">

                                            @forelse ($section->members as $member)
                                                <tr class="member-row" data-id="{{ $member->id }}">

                                                    <td>
                                                        <i
                                                            class="fas fa-up-down-left-right text-gray-600 fs-6 sort-handle member-sort"></i>
                                                    </td>

                                                    <td>
                                                        @if ($member->image)
                                                            <img src="{{ asset('storage/' . $member->image) }}"
                                                                class="rounded-circle" width="45" height="45">
                                                        @else
                                                            <img src="{{ asset('images/default-user.png') }}"
                                                                class="rounded-circle" width="45" height="45">
                                                        @endif
                                                    </td>

                                                    <td>{{ $member->name }}</td>

                                                    <td>{{ $member->designation }}</td>

                                                    <td>{{ $member->email }}</td>

                                                    <td>{{ $member->phone }}</td>

                                                    <td>

                                                        <!-- EDIT MEMBER -->
                                                        @can('edit admin member')
                                                            <button class="btn btn-light-success btn-sm me-2 editMemberBtn"
                                                                data-id="{{ $member->id }}"
                                                                data-name="{{ $member->name }}"
                                                                data-designation="{{ $member->designation }}"
                                                                data-email="{{ $member->email }}"
                                                                data-phone="{{ $member->phone }}"
                                                                data-section="{{ $member->section_id }}"
                                                                data-image="{{ $member->image }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                        @endcan

                                                        <!-- DELETE MEMBER -->
                                                        @can('delete admin member')
                                                            <button class="btn btn-light-danger btn-sm deleteMemberBtn"
                                                                data-id="{{ $member->id }}">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        @endcan

                                                    </td>

                                                </tr>
                                                @empty
                                                <tr>
                                                    <td class="text-center" colspan="8">No Data Found</td>
                                                </tr>
                                            @endforelse

                                        </tbody>

                                    </table>
                                </div>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>

    <!-- ========================================
         INCLUDE ALL PAGE-2 MODALS IN ONE FILE
    ========================================= -->
    @include('admin.pages.administration.modals.office_modals')


    <!-- ========================================
         INCLUDE PAGE-2 JS SCRIPTS
    ========================================= -->
    @push('scripts')
        @include('admin.pages.administration.scripts.office_js')
    @endpush

</x-admin-app-layout>
