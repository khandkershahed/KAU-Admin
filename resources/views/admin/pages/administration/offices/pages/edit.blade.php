<x-admin-app-layout :title="'Edit Office Page - ' . $office->title">

    <form method="POST" action="{{ route('admin.administration.office.cms.pages.update', [$office->slug, $page->id]) }}" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-header align-items-center">
                <h3 class="card-title fw-bold">Edit Office Page</h3>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.administration.office.cms.pages.index', $office->slug) }}" class="btn btn-light btn-sm">
                        <i class="fa-solid fa-arrow-left me-2"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-save me-2"></i> Update Page
                    </button>
                </div>
            </div>

            <div class="card-body">
                @include('admin.pages.administration.offices.pages.partials.form', ['office'=>$office,'navItems'=>$navItems,'page'=>$page,'blocks'=>$blocks])
            </div>
        </div>
    </form>

</x-admin-app-layout>
