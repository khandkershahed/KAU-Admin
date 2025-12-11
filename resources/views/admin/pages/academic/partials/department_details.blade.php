@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="fw-semibold mb-0">{{ $department->title }}</h4>

    @can('create academic staff')
        <button
            type="button"
            class="btn btn-light-primary btn-sm createStaffGroupBtn"
            data-department-id="{{ $department->id }}"
        >
            <i class="fa fa-plus me-2"></i> Add Staff Group
        </button>
    @endcan
</div>

@if($department->description)
    <div class="mb-4">
        {!! $department->description !!}
    </div>
@endif

<div class="staff-groups-sortable" id="staffGroupsSortable">
    @forelse($department->staffSections as $section)
        <div class="card mb-3 staff-group-row" data-id="{{ $section->id }}">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="me-3 group-sort-handle" style="cursor:grab;">
                        <i class="fa-solid fa-up-down text-muted"></i>
                    </span>
                    <div>
                        <div class="fw-semibold">{{ $section->title }}</div>
                        <small class="text-muted">Status: {{ $section->status }}</small>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    @can('create academic staff')
                        <button
                            type="button"
                            class="btn btn-light-primary btn-sm me-2 createStaffMemberBtn"
                            data-group-id="{{ $section->id }}"
                        >
                            <i class="fa fa-user-plus me-1"></i> Member
                        </button>
                    @endcan

                    @can('edit academic staff')
                        <button
                            type="button"
                            class="btn btn-light-success btn-sm me-2 editStaffGroupBtn"
                            data-id="{{ $section->id }}"
                            data-title="{{ $section->title }}"
                            data-status="{{ $section->status }}"
                        >
                            <i class="fa fa-pen"></i>
                        </button>
                    @endcan

                    @can('delete academic staff')
                        <a href="{{ route('admin.academic.staff-groups.destroy', $section->id) }}"
                           class="delete">
                            <i class="fa-solid fa-trash text-danger fs-4"></i>
                        </a>
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <ul class="list-group staff-members-sortable" data-group-id="{{ $section->id }}">
                    @forelse($section->members as $member)
                        @php
                            $memberData = [
                                'id'          => $member->id,
                                'name'        => $member->name,
                                'designation' => $member->designation,
                                'email'       => $member->email,
                                'phone'       => $member->phone,
                                'status'      => $member->status,
                                'position'    => $member->position,
                                'image_url'   => $member->image_path
                                    ? Storage::disk('public')->url($member->image_path)
                                    : asset('images/no_image.png'),
                                'links'       => $member->links ?? [],
                            ];
                        @endphp

                        <li class="list-group-item d-flex align-items-center justify-content-between member-row"
                            data-id="{{ $member->id }}">

                            <div class="d-flex align-items-center">
                                <span class="me-3 member-sort-handle" style="cursor:grab;">
                                    <i class="fa-solid fa-up-down text-muted"></i>
                                </span>

                                <div class="me-3">
                                    <div class="symbol symbol-50px">
                                        <img src="{{ $memberData['image_url'] }}" alt="{{ $member->name }}"
                                             class="rounded">
                                    </div>
                                </div>

                                <div>
                                    <div class="fw-semibold">{{ $member->name }}</div>
                                    @if($member->designation)
                                        <div class="text-muted small">{{ $member->designation }}</div>
                                    @endif
                                    <div class="text-muted small">
                                        @if($member->email)
                                            <i class="fa fa-envelope me-1"></i>{{ $member->email }}
                                        @endif
                                        @if($member->phone)
                                            &nbsp;|&nbsp;
                                            <i class="fa fa-phone me-1"></i>{{ $member->phone }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <span class="badge bg-light text-muted me-3">{{ $member->status }}</span>

                                @can('edit academic staff')
                                    <button
                                        type="button"
                                        class="btn btn-light-success btn-sm me-2 editStaffMemberBtn"
                                        data-member='@json($memberData)'
                                    >
                                        <i class="fa fa-pen"></i>
                                    </button>
                                @endcan

                                @can('delete academic staff')
                                    <a href="{{ route('admin.academic.staff-members.destroy', $member->id) }}"
                                       class="delete">
                                        <i class="fa-solid fa-trash text-danger fs-4"></i>
                                    </a>
                                @endcan
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small">No members in this group.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    @empty
        <div class="alert alert-light text-muted">
            No staff groups found for this department.
        </div>
    @endforelse
</div>
