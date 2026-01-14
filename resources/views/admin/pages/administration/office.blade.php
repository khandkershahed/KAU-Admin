<x-admin-app-layout :title="'Office: ' . $office->title">
<style>
    .member-sort{
        cursor: grab;
    }
</style>
    <div class="card shadow-sm">
        <!-- =========================
             CARD HEADER
        ========================== -->
        <div class="card-header d-flex align-items-center justify-content-between">

            <h3 class="card-title fw-bold">
               <a href="{{ route('admin.administration.index') }}" class="me-4"><i class="fa-solid fa-circle-arrow-left text-info fs-2"></i></a> Office: {{ $office->title }}
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
                    <a href="{{ route('admin.administration.section.create', $office->slug) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i> Add Section
                    </a>
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

                            <!-- LEFT SIDE -->
                            <div class="d-flex align-items-center flex-grow-1 me-2 section-sort" style="cursor: grab;">

                                <span class="section-sort me-3" style="cursor: grab;">
                                    <i class="fa-solid fa-up-down text-muted"></i>
                                </span>

                                <button
                                    class="accordion-button collapsed py-2 px-2 shadow-none bg-transparent flex-grow-1"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#sectionCollapse{{ $section->id }}"
                                    aria-expanded="false"
                                    aria-controls="sectionCollapse{{ $section->id }}">

                                    <span class="fw-semibold d-flex align-items-center">
                                        {{ $section->title }}

                                        @if ($section->section_type === 'officer_cards' || $section->section_type === 'alumni_cards') 
                                            <span class="badge badge-info ms-2">
                                                Members: {{ $section->members->count() }}
                                            </span>
                                            @else
                                            <span class="badge badge-secondary ms-2">
                                                Page Type: {{ ucwords(str_replace('_', ' ', $section->section_type)) }}
                                            </span>
                                        @endif
                                    </span>
                                </button>
                            </div>

                            <!-- RIGHT SIDE ACTION BUTTONS -->
                            <div class="d-flex align-items-center ms-3">

                                @can('edit admin section')
                                    <a href="{{ route('admin.administration.section.edit', [$office->slug, $section->id]) }}" class="btn btn-light-success btn-sm me-2">
                                            <i class="fa-solid fa-pen-to-square fs-6"></i>
                                        </a>
                                @endcan

                                @can('delete admin section')
                                    <a href="{{ route('admin.administration.section.delete', $section->id) }}"
                                       class="btn btn-light-danger btn-sm delete"
                                       data-id="{{ $section->id }}">
                                        <i class="fa-solid fa-trash fs-6"></i>
                                    </a>
                                @endcan

                            </div>
                        </div>

                        <!-- ======================
                            SECTION BODY
                        ======================= -->
                        @if ($section->section_type === 'officer_cards' || $section->section_type === 'alumni_cards') 
                            <div id="sectionCollapse{{ $section->id }}" class="accordion-collapse collapse"
                                data-bs-parent="#officeSectionsAccordion">
    
                                <div class="accordion-body">
    
                                    <!-- CREATE MEMBER -->
                                    @can('create admin member')
                                        <a href="{{ route('admin.administration.member.create', [$office->slug, $section->id]) }}" class="btn btn-outline-info btn-active-info float-end btn-sm mb-3">
                                            <i class="fa fa-plus me-2"></i> Add Member
                                        </a>
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
    
                                                        <!-- SORT -->
                                                        <td class=" member-sort">
                                                            <i class="fas fa-up-down-left-right text-gray-600 fs-6 sort-handle member-sort"></i>
                                                        </td>
    
                                                        <!-- PHOTO -->
                                                        <td class=" member-sort">
                                                            @if ($member->image)
                                                                <img src="{{ asset('storage/' . $member->image) }}"
                                                                    class="rounded-circle" width="45" height="45">
                                                            @else
                                                                <img src="{{ asset('images/default-user.png') }}"
                                                                    class="rounded-circle" width="45" height="45">
                                                            @endif
                                                        </td>
    
                                                        <!-- NAME -->
                                                        <td class="member-sort">{{ $member->name }}</td>
    
                                                        <!-- DESIGNATION -->
                                                        <td class="member-sort">{{ $member->designation }}</td>
    
                                                        <!-- EMAIL -->
                                                        <td class="member-sort">{{ $member->email }}</td>
    
                                                        <!-- PHONE -->
                                                        <td>{{ $member->phone }}</td>
    
                                                        <td>
                                                            <!-- EDIT -->
                                                            @can('edit admin member')
                                                                <a href="{{ route('admin.administration.member.edit', [$office->slug, $member->id]) }}" class="btn btn-light-success btn-sm me-2">
                                                                        <i class="fa-solid fa-pen fs-6"></i>
                                                                    </a>
                                                            @endcan
    
                                                            <!-- DELETE -->
                                                            @can('delete admin member')
                                                                <a href="{{ route('admin.administration.member.delete', $member->id) }}"
                                                                    class="btn btn-light-danger btn-sm delete"
                                                                    data-id="{{ $member->id }}">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            @endcan
                                                        </td>
    
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center">No data found</td>
                                                    </tr>
                                                @endforelse
    
                                            </tbody>
    
                                        </table>
                                    </div>
    
                                </div>
    
                            </div>
                        @endif

                    </div>
                @endforeach

            </div>

        </div>

    </div>

    <!-- =====================
         PAGE-2 MODALS
    ====================== -->
    

    <!-- =====================
         PAGE-2 JAVASCRIPT
    ====================== -->
    @push('scripts')
        @include('admin.pages.administration.scripts.office_js')
    @endpush

</x-admin-app-layout>
