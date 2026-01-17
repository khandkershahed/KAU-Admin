<x-admin-app-layout :title="'Create Office Page - ' . $office->title">

    <form method="POST" action="{{ route('admin.administration.office.cms.pages.store', $office->slug) }}" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-header align-items-center">
                <h3 class="card-title fw-bold">Create Office Page</h3>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.administration.office.cms.pages.index', $office->slug) }}" class="btn btn-light btn-sm">
                        <i class="fa-solid fa-arrow-left me-2"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-save me-2"></i> Save Page
                    </button>
                </div>
            </div>

            <div class="card-body">
                @include('admin.pages.administration.offices.pages.partials.form', ['office'=>$office,'navItems'=>$navItems,'page'=>null])
            </div>
        </div>
    </form>

</x-admin-app-layout>
