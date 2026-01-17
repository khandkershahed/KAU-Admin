<x-admin-app-layout title="Edit Main Page">

    <form method="POST" action="{{ route('admin.cms.main.pages.update', $page->id) }}" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-header align-items-center">
                <h3 class="card-title fw-bold">Edit Main Page</h3>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.cms.main.pages.index') }}" class="btn btn-light btn-sm">
                        <i class="fa-solid fa-arrow-left me-2"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-save me-2"></i> Update
                    </button>
                </div>
            </div>

            <div class="card-body">
                @include('admin.pages.cms.main.pages.partials.form', ['navItems' => $navItems, 'page' => $page, 'blocks' => $blocks])
            </div>
        </div>
    </form>

</x-admin-app-layout>
