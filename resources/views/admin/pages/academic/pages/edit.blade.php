<x-admin-app-layout :title="'Edit Academic Page'">

    <form id="academicPageEditForm" method="POST" action="{{ route('admin.academic.pages.update', $page->id) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" name="academic_site_id" value="{{ optional($selectedSite)->id }}">

        <div class="card">
            <div class="card-header align-items-center">
                <h3 class="card-title fw-bold">Edit Academic Page</h3>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.academic.pages.index', ['site_id' => optional($selectedSite)->id]) }}"
                        class="btn btn-light btn-sm">
                        <i class="fa-solid fa-arrow-left me-2"></i> Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-7">
                    @include('admin.pages.academic.pages._form', [
                        'selectedSite' => $selectedSite,
                        'navItems' => $navItems,
                        'page' => $page,
                    ])
                </div>
                <div class="row justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm w-200px rounded-1">
                        <i class="fa-solid fa-save me-2"></i> Update Page
                    </button>
                </div>
            </div>
        </div>
    </form>

</x-admin-app-layout>
