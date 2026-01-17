<x-admin-app-layout :title="'Edit Office Menu Item - ' . $office->title">

    <form method="POST" action="{{ route('admin.administration.office.cms.menu.update', [$office->slug, $item->id]) }}">
        @csrf

        <div class="card">
            <div class="card-header align-items-center">
                <h3 class="card-title fw-bold">Edit Menu Item</h3>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.administration.office.cms.menu.index', $office->slug) }}" class="btn btn-light btn-sm">
                        <i class="fa-solid fa-arrow-left me-2"></i> Back
                    </a>
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fa-solid fa-save me-2"></i> Update
                    </button>
                </div>
            </div>

            <div class="card-body">
                @include('admin.pages.administration.offices.menu.partials.form', ['office'=>$office,'parents'=>$parents,'item'=>$item])
            </div>
        </div>
    </form>

</x-admin-app-layout>
