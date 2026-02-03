<div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
    <h4 class="fw-semibold mb-0">{{ $department->title }}</h4>

    @can('create academic staff')
        <button type="button" class="btn btn-light-primary btn-sm createStaffGroupBtn"
            data-department-id="{{ $department->id }}">
            <i class="fa fa-plus me-2"></i> Add Staff Group
        </button>
    @endcan
</div>



{{-- ACCORDION OF STAFF GROUPS --}}
<div class="accordion staff-groups-sortable" id="departmentStaffAccordion">

    @forelse($department->staffSections as $section)
        <div class="accordion-item mb-3 staff-group-row" data-id="{{ $section->id }}">

            <div class="accordion-header d-flex align-items-center justify-content-between px-3 py-2"
                style="background: aliceblue;" id="staffSectionHeading{{ $section->id }}">

                {{-- LEFT SIDE: sort handle + accordion toggle --}}
                <div class="d-flex align-items-center flex-grow-1 me-2">

                    {{-- SORT HANDLE (groups) --}}
                    <span class="group-sort-handle me-3" style="cursor: grab;">
                        <i class="fa-solid fa-up-down text-muted"></i>
                    </span>

                    <button class="accordion-button collapsed py-2 px-2 shadow-none bg-transparent flex-grow-1"
                        type="button" data-bs-toggle="collapse"
                        data-bs-target="#staffSectionCollapse{{ $section->id }}" aria-expanded="false"
                        aria-controls="staffSectionCollapse{{ $section->id }}">
                        <span class="fw-semibold d-flex align-items-center">
                            {{ $section->title }}

                            <span class="badge badge-info ms-2">
                                Members: {{ $section->members->count() }}
                            </span>

                            <span class="badge badge-light text-muted ms-2">
                                {{ ucfirst($section->status) }}
                            </span>
                        </span>
                    </button>
                </div>

                {{-- RIGHT SIDE ACTION BUTTONS --}}
                <div class="d-flex align-items-center ms-3">

                    @can('edit academic staff')
                        <button type="button"
                            class="btn btn-outline btn-outline-primary text-hover-white btn-sm me-2 editStaffGroupBtn"
                            data-id="{{ $section->id }}" data-title="{{ $section->title }}"
                            data-status="{{ $section->status }}">
                            <i class="fa-solid fa-pen-to-square fs-6 text-primary"></i>
                        </button>
                    @endcan

                    @can('delete academic staff')
                        <a href="{{ route('admin.academic.staff-groups.destroy', $section->id) }}"
                            class="btn btn-outline btn-outline-danger text-hover-white btn-sm delete">
                            <i class="fa-solid fa-trash fs-6 text-danger"></i>
                        </a>
                    @endcan

                </div>
            </div>

            {{-- =======================
                 GROUP BODY (COLLAPSE)
            ======================== --}}
            <div id="staffSectionCollapse{{ $section->id }}" class="accordion-collapse collapse"
                data-bs-parent="#departmentStaffAccordion">

                <div class="accordion-body">

                    {{-- ADD MEMBER BUTTON --}}
                    @can('create academic staff')
                        <button type="button"
                            class="btn btn-outline btn-outline-info btn-active-info float-end btn-sm mb-3 createStaffMemberBtn"
                            data-group-id="{{ $section->id }}">
                            <i class="fas fa-plus me-2"></i> Add Member
                        </button>
                    @endcan

                    <div class="clearfix mb-3"></div>

                    {{-- MEMBERS TABLE --}}
                    <div class="table-responsive w-100">
                        <table class="table border table-row-bordered align-middle gy-3">
                            <thead style="background: beige;">
                                <tr class="fw-bold">
                                    <th style="width: 40px;">Sort</th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th style="width: 180px;">Actions</th>
                                </tr>
                            </thead>

                            {{-- tbody MUST keep these classes/attrs for JS sortable --}}
                            <tbody class="staff-members-sortable" data-group-id="{{ $section->id }}">

                                @forelse($section->members as $member)
                                    @php
                                        $memberData = [
                                            'id' => $member->id,
                                            'name' => $member->name,
                                            'designation' => $member->designation,
                                            'email' => $member->email,
                                            'phone' => $member->phone,
                                            'status' => $member->status,
                                            'position' => $member->position,
                                            'mobile' => $member->mobile,
                                            'address' => $member->address,
                                            'research_interest' => $member->research_interest,
                                            'bio' => $member->bio,
                                            'education' => $member->education,
                                            'experience' => $member->experience,
                                            'employment_history' => $member->employment_history,
                                            'institutional_member' => $member->institutional_member,
                                            'consultancy' => $member->consultancy,
                                            'scholarship' => $member->scholarship,
                                            'research' => $member->research,
                                            'teaching' => $member->teaching,

                                            'image_url' => $member->image_path
                                                ? asset('storage/' . $member->image_path)
                                                : asset('images/no_image.png'),
                                            'links' => $member->links ?? [],
                                        ];
                                    @endphp

                                    <tr class="member-row" data-id="{{ $member->id }}">

                                        {{-- SORT HANDLE --}}
                                        <td class="member-sort">
                                            <i class="fas fa-up-down-left-right text-gray-600 fs-6 member-sort-handle"
                                                style="cursor: grab;"></i>
                                        </td>

                                        {{-- PHOTO --}}
                                        <td class="member-sort">
                                            <img src="{{ $memberData['image_url'] }}" class="rounded-circle"
                                                width="45" height="45" alt="{{ $member->name }}">
                                        </td>

                                        {{-- NAME --}}
                                        <td class="member-sort">
                                            <div class="fw-semibold">{{ $member->name }}</div>
                                        </td>

                                        {{-- DESIGNATION --}}
                                        <td class="member-sort">
                                            {{ $member->designation }}
                                        </td>

                                        {{-- EMAIL --}}
                                        <td class="member-sort">
                                            {{ $member->email }}
                                        </td>

                                        {{-- PHONE --}}
                                        <td>
                                            {{ $member->phone }}
                                        </td>

                                        {{-- ACTIONS --}}
                                        <td>
                                            @can('edit academic staff')
                                                <button type="button"
                                                    class="btn btn-light-success btn-sm me-2 editStaffMemberBtn"
                                                    data-member='@json($memberData)'>
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endcan

                                            @can('delete academic staff')
                                                <a href="{{ route('admin.academic.staff-members.destroy', $member->id) }}"
                                                    class="btn btn-light-danger btn-sm delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endcan
                                            <button type="button" class="btn btn-sm btn-light-primary"
                                                onclick="openPublicationsModal({{ $member->id }})">
                                                <i class="fa fa-book me-2"></i>Publications
                                            </button>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            No members in this group.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    @empty
        <div class="alert alert-light text-muted">
            No staff groups found for this department.
        </div>
    @endforelse

</div>
