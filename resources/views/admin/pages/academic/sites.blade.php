<x-admin-app-layout :title="'Academic Sites & Navigation'">

    <div class="card">
        <div class="card-header align-items-center">
            <h3 class="card-title fw-bold">Academic Sites & Navigation</h3>

            <div class="d-flex">

                <form method="GET" action="{{ route('admin.academic.sites.index') }}" class="me-3">
                    <select name="site_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach ($groups as $group)
                            @foreach ($group->sites as $site)
                                <option value="{{ $site->id }}"
                                    {{ optional($selectedSite)->id == $site->id ? 'selected' : '' }}>
                                    {{ $group->title }} — {{ $site->name }} ({{ $site->short_name }})
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </form>

                @can('manage academic sites')
                    <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                        <i class="fa fa-plus me-2"></i> Add Group
                    </button>

                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createSiteModal">
                        <i class="fa fa-plus me-2"></i> Add Site
                    </button>
                @endcan

            </div>
        </div>

        <div class="card-body">

            <div class="row">
                {{-- LEFT SIDE: GROUPS + SITES --}}
                <div class="col-lg-6 border-end">
                    <h5 class="mb-3 fw-semibold">Groups & Sites</h5>

                    <div class="accordion" id="academicGroupsAccordion">

                        @foreach ($groups as $group)
                            <div class="accordion-item group-item mb-4" data-id="{{ $group->id }}">

                                <div class="accordion-header d-flex align-items-center justify-content-between px-3 py-2"
                                    style="background:#f4f7ff; border-radius:4px;">

                                    <div class="d-flex align-items-center flex-grow-1">
                                        <span class="group-sort-handle me-3" style="cursor:grab;">
                                            <i class="fa-solid fa-up-down text-muted"></i>
                                        </span>

                                        <button
                                            class="accordion-button collapsed py-2 px-2 bg-transparent shadow-none flex-grow-1"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#group-{{ $group->id }}">
                                            <span class="fw-semibold">{{ $group->title }}</span>
                                            <small class="ms-2 text-muted">{{ $group->slug }}</small>
                                        </button>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-light-success btn-sm me-2 editGroupBtn"
                                            data-id="{{ $group->id }}" data-title="{{ $group->title }}"
                                            data-slug="{{ $group->slug }}" data-status="{{ $group->status }}">
                                            <i class="fa-solid fa-pen fs-6"></i>
                                        </button>

                                        <a href="{{ route('admin.academic.groups.destroy', $group->id) }}"
                                            class="delete">
                                            <i class="fa-solid fa-trash text-danger fs-4"></i>
                                        </a>
                                    </div>

                                </div>

                                <div id="group-{{ $group->id }}"
                                    class="accordion-collapse collapse @if ($loop->first) show @endif">
                                    <div class="accordion-body">

                                        <button class="btn btn-sm btn-primary mb-3 createSiteBtn"
                                            data-group-id="{{ $group->id }}" data-bs-toggle="modal"
                                            data-bs-target="#createSiteModal">
                                            <i class="fa fa-plus me-2"></i> Add Site
                                        </button>

                                        <ul class="list-group site-list" data-group-id="{{ $group->id }}">
                                            @foreach ($group->sites as $site)
                                                <li class="list-group-item d-flex align-items-center justify-content-between site-item mb-2"
                                                    data-id="{{ $site->id }}" style="cursor:grab;">

                                                    <div class="d-flex align-items-center">
                                                        <span class="site-sort-handle me-3">
                                                            <i class="fa-solid fa-up-down text-muted"></i>
                                                        </span>

                                                        <div class="fw-semibold">
                                                            {{ $site->name }} ({{ $site->short_name }})
                                                            @if ($site->status !== 'published')
                                                                <span
                                                                    class="badge bg-secondary ms-2">{{ ucfirst($site->status) }}</span>
                                                            @endif
                                                            {{-- <div class="small text-muted">
                                                                Slug: <code>{{ $site->slug }}</code>
                                                            </div> --}}
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ route('admin.academic.sites.index', ['site_id' => $site->id]) }}"
                                                            class="btn btn-sm btn-outline-primary me-2">Nav</a>

                                                        <button class="btn btn-light-success btn-sm me-2 editSiteBtn"
                                                            data-id="{{ $site->id }}"
                                                            data-group-id="{{ $group->id }}"
                                                            data-name="{{ $site->name }}"
                                                            data-short_name="{{ $site->short_name }}"
                                                            data-slug="{{ $site->slug }}"
                                                            data-description="{{ $site->short_description }}"
                                                            data-primary="{{ $site->theme_primary_color }}"
                                                            data-secondary="{{ $site->theme_secondary_color }}"
                                                            data-status="{{ $site->status }}"
                                                            data-logo="{{ $site->logo_path ? asset('storage/' . $site->logo_path) : '' }}"
                                                            data-bs-toggle="modal" data-bs-target="#editSiteModal">
                                                            <i class="fa-solid fa-pen fs-6"></i>
                                                        </button>

                                                        <a href="{{ route('admin.academic.sites.destroy', $site->id) }}"
                                                            class="delete">
                                                            <i class="fa-solid fa-trash text-danger fs-4"></i>
                                                        </a>
                                                    </div>

                                                </li>
                                            @endforeach
                                        </ul>

                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

                {{-- RIGHT SIDE — NAV TREE --}}
                <div class="col-lg-6">
                    <h5 class="mb-3 fw-semibold">
                        Navigation
                        @if ($selectedSite)
                            <span class="text-muted"> — {{ $selectedSite->name }}</span>
                        @endif
                    </h5>

                    @if (!$selectedSite)
                        <p class="text-muted">Select a site to manage its navigation.</p>
                    @else
                        <button class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal"
                            data-bs-target="#createNavModal" data-site-id="{{ $selectedSite->id }}">
                            <i class="fa fa-plus me-2"></i> Add Root Nav Item
                        </button>
                        {{-- @php
                            $rootItems = $navItemsTree; // already root nodes
                        @endphp --}}
                        <div id="navRootWrapper">
                            @foreach ($navItemsTree as $item)
                                @include('admin.pages.academic.partials.nav_item', [
                                    'item' => $item,
                                    'site' => $selectedSite,
                                ])
                            @endforeach
                        </div>

                    @endif

                </div>

            </div>

        </div>
    </div>

    {{-- MODALS --}}
    @include('admin.pages.academic.modals.group_modals')
    @include('admin.pages.academic.modals.site_modals')
    @include('admin.pages.academic.modals.nav_modals')

    @push('scripts')
        @include('admin.pages.academic.partials.sites_js')
    @endpush

</x-admin-app-layout>
