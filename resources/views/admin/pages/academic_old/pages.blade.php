<x-admin-app-layout :title="'Academic Pages'">
    <style>
        .list-group-item.active {
            z-index: 2;
            color: #000000;
            background-color: mintcream;
            border-color: #7700f7;
        }

        .list-group-item+.list-group-item {
            border-top-width: 1px;
        }
    </style>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold">Academic Pages</h3>

            {{-- SITE SELECTOR --}}
            <form method="GET" action="{{ route('admin.academic.pages.index') }}" class="d-flex">
                <select name="site_id" data-control="select2" class="form-select form-select-sm"
                    onchange="this.form.submit()">
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
            @if (!$selectedSite)
                <p class="text-muted my-3">Please select a site first.</p>
            @else
                <div class="row">
                    {{-- LEFT SIDE: PAGE LIST --}}
                    <div class="col-lg-4 border-end">
                        <div class="d-flex align-items-center justify-content-between my-3">
                            <h4 class="fw-semibold mb-0">Pages</h4>

                            @can('create academic pages')
                                <a href="{{ route('admin.academic.pages.index', [
                                    'site_id' => $selectedSite->id,
                                    'mode' => 'create',
                                ]) }}"
                                    class="btn btn-outline btn-outline-info btn-sm text-hover-white">
                                    <i class="fa fa-plus me-2"></i>Add
                                </a>
                            @endcan
                        </div>

                        <ul class="list-group page-sortable" id="pageSortableWrapper">
                            @forelse($pages as $page)
                                @php
                                    $isActive = $mode === 'edit' && $editingPage && $editingPage->id === $page->id;
                                @endphp

                                <li class="list-group-item py-5 my-2 d-flex align-items-center justify-content-between pages-item {{ $isActive ? 'active' : '' }}"
                                    data-id="{{ $page->id }}"
                                    data-edit-url="{{ route('admin.academic.pages.index', [
                                        'site_id' => $selectedSite->id,
                                        'mode' => 'edit',
                                        'page_id' => $page->id,
                                    ]) }}">

                                    {{-- CLICKABLE AREA (anywhere except delete) --}}
                                    <div class="d-flex align-items-center flex-grow-1 page-click-area"
                                        style="cursor:pointer;">

                                        {{-- SORT HANDLE (if you use SortableJS for pages) --}}
                                        {{-- <span class="me-3 page-sort-handle" style="cursor:grab;">
                                            <i class="fa-solid fa-up-down text-primary"></i>
                                        </span> --}}

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
                                        </div>
                                    </div>

                                    {{-- RIGHT SIDE ACTIONS --}}
                                    <div class="d-flex align-items-center">

                                        {{-- OPTIONAL explicit edit icon (still opens same edit page) --}}
                                        <a href="{{ route('admin.academic.pages.index', [
                                            'site_id' => $selectedSite->id,
                                            'mode' => 'edit',
                                            'page_id' => $page->id,
                                        ]) }}"
                                            class="me-4 text-decoration-none">
                                            <i class="fas fa-pen-square fs-2 text-info"></i>
                                        </a>

                                        {{-- DELETE --}}
                                        @can('delete academic pages')
                                            <a href="{{ route('admin.academic.pages.destroy', $page->id) }}"
                                                class="delete">
                                                <i class="fas fa-trash-alt text-danger fs-4"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-muted small">No pages found.</li>
                            @endforelse
                        </ul>
                    </div>

                    {{-- RIGHT SIDE: CREATE / EDIT FORM --}}
                    {{-- <div class="col-lg-8">
                        <div class="border rounded-3 p-4 my-3">
                            @if ($mode === 'create')
                                <h5 class="fw-semibold mb-3">Create New Page</h5>
                                @include('admin.pages.academic.partials.page_form', [
                                    'formAction' => route('admin.academic.pages.store'),
                                    'method' => 'POST',
                                    'page' => null,
                                    'navItems' => $navItems,
                                    'selectedSite' => $selectedSite,
                                ])
                            @elseif($mode === 'edit' && $editingPage)
                                <h5 class="fw-semibold mb-3">Edit Page: {{ $editingPage->title }}</h5>
                                @include('admin.pages.academic.partials.page_form', [
                                    'formAction' => route('admin.academic.pages.update', $editingPage->id),
                                    'method' => 'POST',
                                    'page' => $editingPage,
                                    'navItems' => $navItems,
                                    'selectedSite' => $selectedSite,
                                ])
                            @else
                                <p class="text-muted mb-0">
                                    Select a page from the left to edit, or click “Add” to create a new page.
                                </p>
                            @endif
                        </div>
                    </div> --}}
                    {{-- RIGHT SIDE: CREATE / EDIT FORM --}}
                    <div class="col-lg-8">
                        <div class="border rounded-3 p-4 my-3">

                            {{-- title wrapper --}}
                            <h5 id="pageFormTitle" class="fw-semibold mb-3">
                                @if ($mode === 'create')
                                    Create New Page
                                @elseif($mode === 'edit' && $editingPage)
                                    Edit Page: {{ $editingPage->title }}
                                @else
                                    Select a page from the left to edit, or click “Add” to create a new page.
                                @endif
                            </h5>

                            {{-- form wrapper --}}
                            <div id="pageFormWrapper">
                                @if ($mode === 'create')
                                    @include('admin.pages.academic.partials.page_form', [
                                        'formAction' => route('admin.academic.pages.store'),
                                        'method' => 'POST',
                                        'page' => null,
                                        'navItems' => $navItems,
                                        'selectedSite' => $selectedSite,
                                    ])
                                @elseif($mode === 'edit' && $editingPage)
                                    @include('admin.pages.academic.partials.page_form', [
                                        'formAction' => route('admin.academic.pages.update', $editingPage->id),
                                        'method' => 'POST',
                                        'page' => $editingPage,
                                        'navItems' => $navItems,
                                        'selectedSite' => $selectedSite,
                                    ])
                                @else
                                    <p class="text-muted mb-0">
                                        Select a page from the left to edit, or click “Add” to create a new page.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        @include('admin.pages.academic.partials.pages_js')


        <script>
            $(document).on("click", ".page-click-area", function(e) {
                const li = $(this).closest(".pages-item");
                const url = li.data("edit-url");
                if (!url) return;

                // When clicked, mark as active visually (before navigation, nice UX)
                $(".pages-item").removeClass("active");
                li.addClass("active");

                window.location.href = url;
            });
        </script>
    @endpush

</x-admin-app-layout>
