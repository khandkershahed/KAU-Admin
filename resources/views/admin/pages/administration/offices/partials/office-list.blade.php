@foreach ($groups as $index => $group)
    @php
        $groupOffices = $offices->where('group_id', $group->id);
        $expanded = true; // keep open for search
    @endphp

    <div class="accordion-item mb-5" data-group-id="{{ $group->id }}">
        <h2 class="accordion-header" id="heading-{{ $group->id }}">
            <button class="accordion-button {{ $expanded ? '' : 'collapsed' }}" type="button"
                data-bs-toggle="collapse" data-bs-target="#collapse-{{ $group->id }}">
                <strong>{{ $group->name }}</strong>
            </button>
        </h2>

        <div id="collapse-{{ $group->id }}" class="accordion-collapse collapse show">
            <div class="accordion-body">

                <div class="text-end mb-4">
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#createOfficeModal" data-group="{{ $group->id }}">
                        <i class="fa fa-plus me-2"></i> Add Office
                    </button>
                </div>

                @if ($groupOffices->count() == 0)
                    <div class="alert alert-info">No offices under {{ $group->name }}.</div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-3">
                            <thead>
                                <tr class="text-start fw-bold text-muted fs-7 text-uppercase gs-0">
                                    <th width="10%" class="text-center">Sort</th>
                                    <th width="45%">Office Title</th>
                                    <th width="15%" class="text-center">Status</th>
                                    <th width="30%" class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="sortable" data-group="{{ $group->id }}">
                                @foreach ($groupOffices as $office)
                                    <tr data-id="{{ $office->id }}">

                                        <td class="text-center cursor-move">
                                            <i class="fa fa-arrows-alt-v fs-4 text-gray-500"></i>
                                        </td>

                                        <td class="fw-semibold">{{ $office->title }}</td>

                                        <td class="text-center">
                                            @if ($office->status)
                                                <span class="badge badge-light-success">Active</span>
                                            @else
                                                <span class="badge badge-light-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td class="text-end">
                                            <a href="{{ route('admin.admin-offices.builder', $office->id) }}"
                                                class="btn btn-sm btn-light-primary me-2">
                                                <i class="fa fa-plus me-1"></i> Members
                                            </a>

                                            <button
                                                class="btn btn-sm btn-light-warning me-2 editOfficeBtn"
                                                data-id="{{ $office->id }}"
                                                data-title="{{ $office->title }}"
                                                data-status="{{ $office->status }}"
                                                data-position="{{ $office->position }}"
                                                data-group="{{ $office->group_id }}"
                                                data-banner="{{ $office->banner_image }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editOfficeModal">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <form class="d-inline" method="POST"
                                                action="{{ route('admin.admin-offices.destroy', $office->id) }}"
                                                onsubmit="return confirm('Delete this office?')">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-light-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endforeach
