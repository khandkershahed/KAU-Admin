<x-admin-app-layout :title="'Office CMS - ' . $office->title">

    <div class="card mb-6">
        <div class="card-header align-items-center">
            <div>
                <h3 class="card-title fw-bold mb-1">Office CMS</h3>
                <div class="text-muted">
                    Office: <strong>{{ $office->title }}</strong>
                    <span class="mx-2">•</span>
                    Frontend: <code>/offices/{{ $office->slug }}</code>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.administration.office.page', $office->slug) }}" class="btn btn-light-primary btn-sm">
                    <i class="fa-solid fa-users me-2"></i> Sections & Staff
                </a>

                <a href="{{ route('admin.administration.office.cms.pages.index', $office->slug) }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-file-lines me-2"></i> Pages
                </a>

                <a href="{{ route('admin.administration.office.cms.menu.index', $office->slug) }}" class="btn btn-dark btn-sm">
                    <i class="fa-solid fa-sitemap me-2"></i> Menu
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-5">
                <div class="col-md-4">
                    <div class="border rounded p-5 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="fw-bold">Office Pages</div>
                            <span class="badge badge-light-primary">{{ $pagesCount }}</span>
                        </div>
                        <div class="text-muted mb-4">
                            Create Office homepage + other office pages. Each page can be built with blocks (rich text, hero, gallery etc).
                        </div>
                        <a href="{{ route('admin.administration.office.cms.pages.index', $office->slug) }}" class="btn btn-sm btn-primary">
                            Manage Pages
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border rounded p-5 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="fw-bold">Office Menu</div>
                            <span class="badge badge-light-dark">{{ $menuCount }}</span>
                        </div>
                        <div class="text-muted mb-4">
                            Build the left/right menus for office pages (internal pages, external links, groups). Drag to reorder.
                        </div>
                        <a href="{{ route('admin.administration.office.cms.menu.index', $office->slug) }}" class="btn btn-sm btn-dark">
                            Manage Menu
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border rounded p-5 h-100">
                        <div class="fw-bold mb-3">Sections & Staff</div>
                        <div class="text-muted mb-4">
                            Manage office sections (tabs) and add staff members under each section. This controls the staff listing UI.
                        </div>
                        <a href="{{ route('admin.administration.office.page', $office->slug) }}" class="btn btn-sm btn-light-primary">
                            Manage Sections & Staff
                        </a>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mt-6 mb-0">
                <strong>Tip:</strong>
                Use <em>Menu</em> to create the page slugs first (type = <code>page</code>),
                then create the matching <em>Pages</em> and set one page as <code>Office Home</code>.
            </div>
        </div>
    </div>

</x-admin-app-layout>
