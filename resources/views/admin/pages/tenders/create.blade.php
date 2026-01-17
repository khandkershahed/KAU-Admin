<x-admin-app-layout :title="'Create Tender'">

    <div class="card card-flush shadow-sm">
        <div class="card-header align-items-center py-5">
            <div class="d-flex flex-column flex-md-row justify-content-between w-100 align-items-md-center gap-3">
                <div>
                    <h3 class="card-title fw-bold mb-1">Create Tender</h3>
                    <span class="text-muted fs-7">Add a new tender with attachments and SEO.</span>
                </div>

                <div class="card-toolbar">
                    <a href="{{ route('admin.tenders.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to the list
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            @if ($errors->any())
                <div class="alert alert-danger mb-6">
                    <div class="d-flex flex-column">
                        <h5 class="mb-1">Please fix the following errors:</h5>
                        <ul class="mb-0 ps-4">
                            @foreach ($errors->all() as $error)
                                <li class="small">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.tenders.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.pages.tenders._form')
            </form>
        </div>
    </div>

</x-admin-app-layout>
