<x-admin-app-layout :title="'Administration Module'">

    <div class="card">

        <!-- =======================
             CARD HEADER
        ======================== -->
        <div class="card-header align-items-center">
            <h3 class="card-title fw-bold">Administration Module</h3>

            <div class="d-flex">

                <!-- SEARCH -->
                <div class="me-3 position-relative">
                    <input type="text" id="adminSearchInput" class="form-control form-control-sm"
                        placeholder="Search groups or offices..." style="width: 230px; height: 36px;" />

                    <button type="button" id="clearAdminSearchBtn" class="btn btn-danger btn-sm position-absolute"
                        style="right: 0; top: 0; height: 36px; display:none;">
                        <i class="fas fa-x"></i>
                    </button>
                </div>

                @can('create admin group')
                    <a href="{{ route('admin.administration.group.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i> Add Group
                    </a>
                @endcan

            </div>
        </div>

        <!-- =======================
             CARD BODY
        ======================== -->
        <div class="card-body">

            <div class="accordion" id="adminGroupsAccordion">

                @foreach ($groups as $group)
                    <div class="accordion-item group-item mb-5" data-group-id="{{ $group->id }}">

                        <!-- =======================
                             ACCORDION HEADER
                        ======================== -->
                        <div class="accordion-header d-flex align-items-center justify-content-between px-3 py-2"
                            style="background: aliceblue;">

                            <!-- LEFT SIDE -->
                            <div class="d-flex align-items-center flex-grow-1 me-2 group-sort" style="cursor: grab;">

                                <!-- SORT HANDLE -->
                                <span class="group-sort me-3" style="cursor: grab;">
                                    <i class="fa-solid fa-up-down text-muted"></i>
                                </span>

                                <!-- TITLE -->
                                <button
                                    class="accordion-button collapsed py-2 px-2 shadow-none bg-transparent flex-grow-1"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#group-{{ $group->id }}">
                                    <span class="fw-semibold">{{ $group->name }}</span>
                                </button>
                            </div>

                            <!-- RIGHT SIDE ACTIONS -->
                            <div class="d-flex align-items-center ms-3">

                                @can('edit admin group')
                                    <a href="{{ route('admin.administration.group.edit', $group->id) }}" class="btn btn-light-success btn-sm me-2">
                                        <i class="fa-solid fa-pen-to-square fs-6"></i>
                                    </a>
                                @endcan

                                @can('delete admin group')
                                    <a href="{{ route('admin.administration.group.delete', $group->id) }}"
                                        data-id="{{ $group->id }}" class="btn btn-light-danger btn-sm delete">
                                        <i class="fa-solid fa-trash fs-6"></i>
                                    </a>
                                @endcan

                            </div>
                        </div>

                        <!-- =======================
                             ACCORDION BODY
                        ======================== -->
                        <div id="group-{{ $group->id }}"
                            class="accordion-collapse collapse @if ($loop->first) show @endif">

                            <div class="accordion-body">

                                <!-- ADD OFFICE BUTTON -->
                                @can('create admin office')
                                    <a href="{{ route('admin.administration.office.create', ['group_id' => $group->id]) }}" class="btn btn-sm btn-primary mb-4 float-end">
                                        <i class="fa fa-plus me-2"></i> Add Office
                                    </a>
                                @endcan

                                <!-- OFFICE TABLE -->
                                <div class="table-responsive w-100">
                                    <table class="table px-2 border table-row-bordered table-hover officesTable">
                                        <thead style="background: beige;">
                                            <tr>
                                                <th style="width:50px;">Sort</th>
                                                <th>Office Title</th>
                                                <th>Slug</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse ($group->offices as $office)
                                                <tr class="office-row office-sort" data-id="{{ $office->id }}">

                                                    <td class="office-sort" style="cursor: grab;">
                                                        <i class="fa-solid fa-up-down text-muted"></i>
                                                    </td>

                                                    <td style="cursor: grab;">{{ $office->title }}</td>
                                                    <td style="cursor: grab;">{{ $office->slug }}</td>

                                                    <td class="text-end">

                                                        <!-- EDIT -->
                                                        @can('edit admin office')
                                                            <a href="{{ route('admin.administration.office.edit', $office->id) }}" class="btn btn-light-success btn-sm me-2">
                                                                <i class="fa-solid fa-pen fs-6"></i>
                                                            </a>
                                                        @endcan

                                                        <!-- DELETE (custom SweetAlert delete pattern) -->
                                                        @can('delete admin office')
                                                            <a href="{{ route('admin.administration.office.delete', $office->id) }}"
                                                                data-id="{{ $office->id }}"
                                                                class="btn btn-light-danger btn-sm delete">
                                                                <i class="fa-solid fa-trash fs-6"></i>
                                                            </a>
                                                        @endcan

                                                        <!-- GO TO OFFICE PAGE -->
                                                        <a href="{{ route('admin.office.cms.menu.index', $office->slug) }}"
                                                            class="btn btn-sm btn-secondary ms-2">
                                                            <i class="fas fa-users me-2"></i> More
                                                        </a>
                                                        {{-- <a href="{{ route('admin.administration.office.page', $office->slug) }}"
                                                            class="btn btn-sm btn-secondary ms-2">
                                                            <i class="fas fa-users me-2"></i> Staff
                                                        </a> --}}

                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No data Found</td>
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

    <!-- =======================
         INCLUDE ALL MODALS
    ======================== -->


    <!-- =======================
         PAGE JS
    ======================== -->
    @push('scripts')
        @include('admin.pages.administration.scripts.index_js')
    @endpush

</x-admin-app-layout>
