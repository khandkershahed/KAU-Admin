<x-admin-app-layout :title="'Home Popups'">
    <style>
        .table-loading {
            pointer-events: none;
            opacity: 0.45;
        }
    </style>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h4 class="mb-0">Manage Home Popups</h4>
            <div class="d-flex align-items-center">
                <div class="input-group input-group-sm me-5" style="width: 220px;">
                    <span class="input-group-text bg-light">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search Home Popup..." id="homePopupSearch">
                </div>
                @can('manage homepage')
                    <div>
                        <a href="javascript:void(0)" class="btn btn-sm btn-outline btn-outline-info rounded-0"
                            data-bs-toggle="modal" data-bs-target="#AddPopupModal">
                            <i class="fa fa-plus me-2"></i> Add Popup
                        </a>
                    </div>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive position-relative">

                {{-- TABLE LOADER --}}
                <div id="homePopupTableLoader" class="position-absolute top-50 start-50 translate-middle d-none"
                    style="z-index: 10;">
                    <div class="d-flex flex-column align-items-center">
                        <span class="spinner-border text-primary mb-2" role="status"></span>
                        <span class="text-muted small">Updating status...</span>
                    </div>
                </div>

                {{-- TABLE --}}
                <table class="table border table-striped table-row-bordered align-middle mb-0 gy-5 gs-7"
                    id="homePopupTable">

                    <thead style="background: aliceblue;">
                        <tr class="fw-semibold fs-6 text-gray-900">
                            <th width="5%">SL.</th>
                            <th width="30%">Title</th>
                            <th width="25%">Button</th>
                            <th width="20%">Status</th>
                            <th class="text-center" width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($popups as $popup)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $popup->title }}</td>

                                <td>
                                    <div>{{ $popup->button_name }}</div>
                                    <small>
                                        <a href="{{ $popup->button_link }}">{{ $popup->button_link }}</a>
                                    </small>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center" data-row="{{ $popup->id }}">
                                        <div class="me-3">
                                            <span
                                                class="badge js-status-badge {{ $popup->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                                {{ ucfirst($popup->status) }}
                                            </span>
                                        </div>
                                        <div class="form-check form-switch ms-5">
                                            <input type="checkbox" class="form-check-input js-status-toggle"
                                                data-id="{{ $popup->id }}"
                                                data-url="{{ route('admin.home_popups.toggle_status', $popup->id) }}"
                                                @checked($popup->status === 'active')>
                                        </div>
                                    </div>
                                </td>


                                <td class="text-center">
                                    <a href="javascript:void(0)" class="me-5" data-bs-toggle="modal"
                                        data-bs-target="#ShowPopupModal-{{ $popup->id }}">
                                        <i class="fa-solid fa-eye text-info"></i>
                                    </a>

                                    @can('manage homepage')
                                        <a href="javascript:void(0)" class="me-5" data-bs-toggle="modal"
                                            data-bs-target="#EditPopupModal-{{ $popup->id }}">
                                            <i class="fa-solid fa-pen text-primary"></i>
                                        </a>

                                        <a href="{{ route('admin.home_popups.destroy', $popup->id) }}" class="delete">
                                            <i class="fa-solid fa-trash-alt text-danger"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-700 py-5">
                                    No home popups found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= CREATE MODAL ================= --}}
    <div class="modal fade" tabindex="-1" id="AddPopupModal">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header py-3" style="background: beige;">
                    <h3 class="modal-title text-black">Create Home Popup</h3>
                    <button type="button" class="btn btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark fs-2"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('admin.home_popups.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4 mb-5">
                            <div class="col-md-8">
                                <x-metronic.label class="fw-bold">Title</x-metronic.label>
                                <x-metronic.input class="form-control form-control-sm" name="title"
                                    :value="old('title')" />
                            </div>

                            <div class="col-md-4">
                                <x-metronic.label class="fw-bold">Status</x-metronic.label>
                                <x-metronic.select-option class="form-select form-select-sm" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </x-metronic.select-option>
                            </div>
                        </div>

                        <div class="row g-4 mb-5">
                            {{-- <div class="col-md-4">
                                <x-metronic.label class="fw-bold">Badge</x-metronic.label>
                                <x-metronic.input name="badge" />
                            </div> --}}

                            <div class="col-md-4">
                                <x-metronic.label class="fw-bold">Button Name</x-metronic.label>
                                <x-metronic.input class="form-control form-control-sm" name="button_name" />
                            </div>

                            <div class="col-md-8">
                                <x-metronic.label class="fw-bold">Button Link</x-metronic.label>
                                <input type="url" name="button_link" id="button_link"
                                    class="form-control form-control-sm" value="{{ old('button_link') }}" />
                            </div>
                        </div>

                        {{-- <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <x-metronic.label class="fw-bold">Popup Image</x-metronic.label>
                                <x-metronic.image-input name="image" />
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <x-metronic.label class="fw-bold">Image Redirect URL</x-metronic.label>
                                        <x-metronic.input name="image_url" />
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <x-metronic.editor name="content" label="Popup Content" :value="old('content', '')" rows="10" />

                        <div class="d-flex justify-content-end mt-5">
                            <button type="submit"
                                class="btn btn-sm rounded-0 btn-outline btn-outline-primary">Submit</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= EDIT MODALS ================= --}}
    @foreach ($popups as $popup)
        <div class="modal fade" tabindex="-1" id="ShowPopupModal-{{ $popup->id }}">
            <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header py-3" style="background: beige;">
                        <h3 class="modal-title text-black">Popup Details</h3>
                        <button type="button" class="btn btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark fs-2"></i>
                        </button>
                    </div>

                    <div class="modal-body">

                        {{-- ROW 1 --}}
                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <label class="fw-bold text-muted">Title</label>
                                <div class="form-control form-control-solid">
                                    {{ $popup->title }}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="fw-bold text-muted">Slug</label>
                                <div class="form-control form-control-solid">
                                    {{ $popup->slug }}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="fw-bold text-muted">Status</label>
                                <div>
                                    <span
                                        class="badge {{ $popup->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($popup->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- ROW 2 --}}
                        <div class="row g-4 mb-5">
                            {{-- <div class="col-md-4">
                                <label class="fw-bold text-muted">Badge</label>
                                <div class="form-control form-control-solid">
                                    {{ $popup->badge ?: '-' }}
                                </div>
                            </div> --}}

                            <div class="col-md-4">
                                <label class="fw-bold text-muted">Button Name</label>
                                <div class="form-control form-control-solid">
                                    {{ $popup->button_name ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label class="fw-bold text-muted">Button Link</label>
                                <div class="form-control form-control-solid text-truncate">
                                    {{ $popup->button_link ?: '-' }}
                                </div>
                            </div>
                        </div>

                        {{-- ROW 3 --}}
                        {{-- <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label class="fw-bold text-muted">Popup Image</label>
                                <div class="border rounded p-3 text-center">
                                    @if ($popup->image)
                                        <img src="{{ asset('storage/' . $popup->image) }}" class="img-fluid rounded"
                                            style="max-height: 200px;">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="fw-bold text-muted">Image Redirect URL</label>
                                <div class="form-control form-control-solid">
                                    {{ $popup->image_url ?: '-' }}
                                </div>
                            </div>
                        </div> --}}

                        {{-- CONTENT --}}
                        <div class="mb-4">
                            <label class="fw-bold text-muted mb-2">Popup Content</label>
                            <div class="border rounded p-4 bg-light">
                                {!! $popup->content !!}
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm rounded-0 btn-outline btn-outline-danger"
                            data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" id="EditPopupModal-{{ $popup->id }}">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header py-3" style="background: beige;">
                        <h3 class="modal-title">Update Home Popup</h3>
                        <button type="button" class="btn btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark fs-2"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('admin.home_popups.update', $popup->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-4 mb-5">
                                <div class="col-md-8">
                                    <x-metronic.label class="fw-bold">Title</x-metronic.label>
                                    <x-metronic.input class="form-control form-control-sm" name="title"
                                        :value="old('title', $popup->title)" />
                                </div>

                                <div class="col-md-4">
                                    <x-metronic.label class="fw-bold">Status</x-metronic.label>
                                    <x-metronic.select-option class="form-select form-select-sm" name="status">
                                        <option value="active" @selected($popup->status === 'active')>Active</option>
                                        <option value="inactive" @selected($popup->status === 'inactive')>Inactive</option>
                                    </x-metronic.select-option>
                                </div>
                            </div>

                            <div class="row g-4 mb-5">
                                {{-- <div class="col-md-4">
                                    <x-metronic.label class="fw-bold">Badge</x-metronic.label>
                                    <x-metronic.input name="badge" :value="old('badge', $popup->badge)" />
                                </div> --}}

                                <div class="col-md-4">
                                    <x-metronic.label class="fw-bold">Button Name</x-metronic.label>
                                    <x-metronic.input class="form-control form-control-sm" name="button_name"
                                        :value="old('button_name', $popup->button_name)" />
                                </div>

                                <div class="col-md-8">
                                    <x-metronic.label class="fw-bold">Button Link</x-metronic.label>
                                    <input type="url" name="button_link"
                                        id="button_link_{{ $popup->button_link }}"
                                        class="form-control form-control-sm"
                                        value="{{ old('button_link', $popup->button_link) }}" />

                                </div>
                            </div>

                            {{-- <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <x-metronic.label class="fw-bold">Popup Image</x-metronic.label>
                                    <x-metronic.image-input name="image" :source="$popup->image ? asset('storage/' . $popup->image) : null" />
                                </div>

                                <div class="col-md-6">
                                    <x-metronic.label class="fw-bold">Image Redirect URL</x-metronic.label>
                                    <x-metronic.input name="image_url" :value="old('image_url', $popup->image_url)" />
                                </div>
                            </div> --}}

                            <x-metronic.editor name="content" label="Popup Content" :value="old('content', $popup->content)"
                                rows="10" />

                            <div class="d-flex justify-content-end mt-5">
                                <button type="submit"
                                    class="btn btn-sm rounded-0 btn-outline btn-outline-primary">Submit</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

    @push('scripts')
        <script>
            $("#homePopupSearch").on("keyup", function() {
                let value = $(this).val().toLowerCase();

                $("#homePopupTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().includes(value));
                });
            });

            $(document).on('change', '.js-status-toggle', function() {

                const checkbox = $(this);
                const url = checkbox.data('url');
                const currentId = checkbox.data('id');

                const table = $('#homePopupTable');
                const loader = $('#homePopupTableLoader');

                // 🔄 SHOW TABLE LOADER
                loader.removeClass('d-none');
                table.addClass('table-loading');

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {

                        // 1️⃣ Reset ALL rows
                        $('.js-status-toggle').prop('checked', false);
                        $('.js-status-badge')
                            .removeClass('badge-success')
                            .addClass('badge-danger')
                            .text('Inactive');

                        // 2️⃣ Activate current row if backend says active
                        if (res.new_status === 'active') {

                            const activeRow = $('[data-row="' + currentId + '"]');

                            activeRow.find('.js-status-toggle').prop('checked', true);

                            activeRow.find('.js-status-badge')
                                .removeClass('badge-danger')
                                .addClass('badge-success')
                                .text('Active');
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Updated',
                            text: res.message,
                            timer: 1000,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'Status update failed', 'error');
                        checkbox.prop('checked', !checkbox.prop('checked'));
                    },
                    complete: function() {
                        // ✅ HIDE TABLE LOADER
                        loader.addClass('d-none');
                        table.removeClass('table-loading');
                    }
                });
            });
        </script>
    @endpush
</x-admin-app-layout>
