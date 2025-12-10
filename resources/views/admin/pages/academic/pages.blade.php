<x-admin-app-layout :title="'Academic Pages'">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold">Academic Pages</h3>

            {{-- SITE SELECTOR --}}
            <form method="GET" action="{{ route('admin.academic.pages.index') }}">
                <select name="site_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($sites as $site)
                        <option value="{{ $site->id }}"
                            {{ optional($selectedSite)->id == $site->id ? 'selected' : '' }}>
                            {{ $site->name }} ({{ $site->short_name }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="card-body">
            @if(!$selectedSite)
                <p class="text-muted">Please select a site first.</p>
            @else
                <div class="row">
                    {{-- LEFT SIDE: PAGE LIST --}}
                    <div class="col-lg-4 border-end">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-semibold mb-0">Pages</h5>

                            @can('create academic pages')
                                <a href="{{ route('admin.academic.pages.index', [
                                    'site_id' => $selectedSite->id,
                                    'mode' => 'create'
                                ]) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-plus me-2"></i> Add Page
                                </a>
                            @endcan
                        </div>

                        <ul class="list-group page-sortable" id="pageSortableWrapper">
                            @forelse($pages as $page)
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 page-item"
                                    data-id="{{ $page->id }}"
                                    style="cursor: grab;">

                                    <div>
                                        <div class="fw-semibold">
                                            {{ $page->title }}

                                            @if($page->is_home)
                                                <span class="badge bg-success ms-2">Home</span>
                                            @endif
                                            @if($page->is_department_boxes)
                                                <span class="badge bg-info ms-2">Departments</span>
                                            @endif
                                            @if($page->is_faculty_members)
                                                <span class="badge bg-warning ms-2">Faculty</span>
                                            @endif
                                        </div>

                                        <div class="small text-muted">
                                            slug: <code>{{ $page->slug }}</code>
                                            @if($page->page_key)
                                                | key: <code>{{ $page->page_key }}</code>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('admin.academic.pages.index', [
                                            'site_id' => $selectedSite->id,
                                            'mode' => 'edit',
                                            'page_id' => $page->id
                                        ]) }}" class="btn btn-light-success btn-sm me-2">
                                            <i class="fa fa-pen"></i>
                                        </a>

                                        @can('delete academic pages')
                                            <a href="{{ route('admin.academic.pages.destroy', $page->id) }}"
                                               class="delete">
                                                <i class="fa fa-trash text-danger fs-4"></i>
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
                    <div class="col-lg-8">

                        @if($mode === 'create')
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
                            <p class="text-muted">Select a page to edit or create a new one.</p>
                        @endif

                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        @include('admin.pages.academic.partials.pages_js')
    @endpush

</x-admin-app-layout>
