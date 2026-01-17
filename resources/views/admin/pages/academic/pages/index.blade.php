<x-admin-app-layout :title="'Academic Pages'">
    <style>
        .list-group-item.active {
            z-index: 2;
            color: #000000;
            background-color: mintcream;
            border-color: #7700f7;
        }
        .list-group-item + .list-group-item { border-top-width: 1px; }
    </style>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold">Academic Pages</h3>

            {{-- SITE SELECTOR --}}
            <form method="GET" action="{{ route('admin.academic.pages.index') }}" class="d-flex">
                <select name="site_id" data-control="select2" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach ($sites as $site)
                        <option value="{{ $site->id }}" {{ optional($selectedSite)->id == $site->id ? 'selected' : '' }}>
                            {{ $site->name }} ({{ $site->short_name }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="card-body pt-0">
            @if (!$selectedSite)
                <p class="text-muted my-3">Please select a site first.</p>
            @else
                <div class="d-flex align-items-center justify-content-between my-4">
                    <div>
                        <div class="fw-semibold fs-5">Pages List</div>
                        <div class="text-muted small">Manage pages for: <span class="fw-semibold">{{ $selectedSite->name }}</span></div>
                    </div>

                    <div class="d-flex gap-2">
                        @can('create academic pages')
                            <a href="{{ route('admin.academic.pages.create', ['site_id' => $selectedSite->id]) }}"
                               class="btn btn-outline btn-outline-info btn-sm text-hover-white">
                                <i class="fa fa-plus me-2"></i>Add New Page
                            </a>
                        @endcan
                    </div>
                </div>

                <ul class="list-group" id="pageSortableWrapper">
                    @forelse($pages as $page)
                        <li class="list-group-item py-5 my-2 d-flex align-items-center justify-content-between"
                            data-id="{{ $page->id }}">

                            <div class="d-flex align-items-center flex-grow-1">
                                <div>
                                    <div class="fw-semibold">
                                        {{ $page->title }}

                                        @if ($page->is_home)
                                            <span class="badge badge-success mx-4 mb-2">Home</span>
                                        @endif
                                        @if ($page->is_department_boxes)
                                            <span class="badge badge-info mx-4 mb-2">Departments</span>
                                        @endif
                                        @if ($page->is_faculty_members)
                                            <span class="badge badge-warning mx-4 mb-2">Faculty</span>
                                        @endif
                                    </div>
                                    <div class="text-muted small mt-1">
                                        Slug: <span class="fw-semibold">{{ $page->slug }}</span> &nbsp; | &nbsp;
                                        Status: <span class="fw-semibold">{{ $page->status }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                @can('edit academic pages')
                                    <a href="{{ route('admin.academic.pages.edit', $page->id) }}" class="me-4 text-decoration-none">
                                        <i class="fas fa-pen-square fs-2 text-info"></i>
                                    </a>
                                @endcan

                                @can('delete academic pages')
                                    <a href="{{ route('admin.academic.pages.destroy', $page->id) }}" class="delete">
                                        <i class="fas fa-trash-alt text-danger fs-4"></i>
                                    </a>
                                @endcan
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small">No pages found.</li>
                    @endforelse
                </ul>
            @endif
        </div>
    </div>

    @push('scripts')
        @include('admin.pages.academic.partials.pages_js')
    @endpush
</x-admin-app-layout>
