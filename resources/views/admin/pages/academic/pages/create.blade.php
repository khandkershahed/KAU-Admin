<x-admin-app-layout :title="'Create Academic Page'">

    <div class="card">
        <div class="card-header align-items-center">
            <h3 class="card-title fw-bold">Create Academic Page</h3>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.academic.pages.index', ['site_id' => optional($selectedSite)->id]) }}"
                    class="btn btn-light btn-sm d-flex align-items-center">
                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                </a>

                <select class="form-select form-select-sm" data-control="select2" style="min-width: 260px"
                    onchange="window.location='{{ route('admin.academic.pages.create') }}?site_id=' + this.value;">
                    @foreach ($sites as $site)
                        <option value="{{ $site->id }}"
                            {{ optional($selectedSite)->id == $site->id ? 'selected' : '' }}>
                            {{ $site->name }} ({{ $site->short_name }})
                        </option>
                    @endforeach
                </select>


            </div>
        </div>

        <div class="card-body">
            @if (!$selectedSite)
                <div class="alert alert-warning mb-0">
                    Please select a site first.
                </div>
            @else
                <div class="row mb-7">
                    <form id="academicPageCreateForm" method="POST" action="{{ route('admin.academic.pages.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="academic_site_id" value="{{ $selectedSite->id }}">

                        @include('admin.pages.academic.pages._form', [
                            'selectedSite' => $selectedSite,
                            'navItems' => $navItems,
                            'page' => null,
                        ])
                    </form>
                </div>

                <div class="row justify-content-end">
                    @if ($selectedSite)
                        <button type="submit" form="academicPageCreateForm" class="btn btn-primary btn-sm w-200px rounded-1">
                            <i class="fa-solid fa-save me-2"></i> Save Page
                        </button>
                    @endif
                </div>
            @endif

        </div>
    </div>

</x-admin-app-layout>
