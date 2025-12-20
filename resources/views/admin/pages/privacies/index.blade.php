<x-admin-app-layout :title="'Manage Privacy Policy'">

    <style>
        .table-loading {
            pointer-events: none;
            opacity: 0.45;
        }
    </style>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h4 class="mb-0">Manage Privacy Policy</h4>

            <div class="d-flex align-items-center">
                <div class="input-group input-group-sm me-5" style="width: 220px;">
                    <span class="input-group-text bg-light">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search Privacy..." id="privacySearch">
                </div>

                @can('create privacy')
                    <a href="javascript:void(0)" class="btn btn-sm btn-outline btn-outline-info rounded-0"
                        data-bs-toggle="modal" data-bs-target="#AddPrivacyModal">
                        <i class="fa fa-plus me-2"></i> Add Privacy
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive position-relative">

                {{-- TABLE LOADER --}}
                <div id="privacyTableLoader" class="position-absolute top-50 start-50 translate-middle d-none"
                    style="z-index: 10;">
                    <div class="d-flex flex-column align-items-center">
                        <span class="spinner-border text-primary mb-2"></span>
                        <span class="text-muted small">Updating status...</span>
                    </div>
                </div>

                <table class="table border table-striped table-row-bordered align-middle mb-0 gy-5 gs-7"
                    id="privacyTable">

                    <thead style="background: aliceblue;">
                        <tr class="fw-semibold fs-6 text-gray-900">
                            <th width="5%">SL.</th>
                            <th width="30%">Title</th>
                            <th width="15%">Version</th>
                            <th width="20%">Effective</th>
                            <th width="15%">Status</th>
                            <th class="text-center" width="15%">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($privacies as $privacy)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>

                                <td>{{ $privacy->title }}</td>

                                <td>{{ $privacy->version }}</td>

                                <td>
                                    {{ $privacy->effective_date }}
                                    @if ($privacy->expiration_date)
                                        <br>
                                        <small class="text-muted">
                                            Exp: {{ $privacy->expiration_date }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex align-items-center" data-row="{{ $privacy->id }}">
                                        <div class="me-3">
                                            <span
                                                class="badge js-status-badge {{ $privacy->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                                {{ ucfirst($privacy->status) }}
                                            </span>
                                        </div>

                                        @can('edit privacy')
                                            <div class="form-check form-switch ms-5">
                                                <input type="checkbox" class="form-check-input js-status-toggle"
                                                    data-id="{{ $privacy->id }}"
                                                    data-url="{{ route('admin.privacy.toggle-status', $privacy->id) }}"
                                                    @checked($privacy->status === 'active')>
                                            </div>
                                        @endcan
                                    </div>
                                </td>

                                <td class="text-center">

                                    @can('view privacy')
                                        <a href="javascript:void(0)" class="me-5" data-bs-toggle="modal"
                                            data-bs-target="#ShowPrivacyModal-{{ $privacy->id }}">
                                            <i class="fa-solid fa-eye text-info"></i>
                                        </a>
                                    @endcan

                                    @can('edit privacy')
                                        <a href="javascript:void(0)" class="me-5" data-bs-toggle="modal"
                                            data-bs-target="#EditPrivacyModal-{{ $privacy->id }}">
                                            <i class="fa-solid fa-pen text-primary"></i>
                                        </a>
                                    @endcan

                                    @can('delete privacy')
                                        <form action="{{ route('admin.privacy.destroy', $privacy->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-danger"
                                                onclick="return confirm('Delete this privacy policy?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    No privacy policy found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    {{-- ================= CREATE MODAL ================= --}}
    @can('create privacy')
        <div class="modal fade" tabindex="-1" id="AddPrivacyModal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header py-3" style="background: beige;">
                        <h3 class="modal-title">Create Privacy Policy</h3>
                        <button type="button" class="btn btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark fs-2"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('admin.privacy.store') }}" method="POST">
                            @csrf

                            <div class="row g-4 mb-5">
                                <div class="col-md-8">
                                    <x-metronic.label class="fw-bold">Title</x-metronic.label>
                                    <x-metronic.input name="title" class="form-control-sm" />
                                </div>

                                <div class="col-md-4">
                                    <x-metronic.label class="fw-bold">Version</x-metronic.label>
                                    <x-metronic.input name="version" class="form-control-sm" />
                                </div>
                            </div>

                            <div class="row g-4 mb-5">
                                <div class="col-md-4">
                                    <x-metronic.label class="fw-bold">Effective Date</x-metronic.label>
                                    <input type="date" name="effective_date" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-4">
                                    <x-metronic.label class="fw-bold">Expiration Date</x-metronic.label>
                                    <input type="date" name="expiration_date" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-4">
                                    <x-metronic.label class="fw-bold">Status</x-metronic.label>
                                    <x-metronic.select-option name="status" class="form-select-sm">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </x-metronic.select-option>
                                </div>
                            </div>

                            <x-metronic.editor name="content" label="Privacy Content" rows="10" />

                            <div class="d-flex justify-content-end mt-5">
                                <button type="submit" class="btn btn-sm rounded-0 btn-outline btn-outline-primary">
                                    Submit
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endcan

    {{-- ================= SHOW & EDIT MODALS ================= --}}
    @foreach ($privacies as $privacy)
        {{-- SHOW MODAL --}}
        <div class="modal fade" tabindex="-1" id="ShowPrivacyModal-{{ $privacy->id }}">
            <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header py-3" style="background: beige;">
                        <h3 class="modal-title">Privacy Details</h3>
                        <button type="button" class="btn btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark fs-2"></i>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <label class="fw-bold text-muted">Title</label>
                                <div class="form-control form-control-solid">{{ $privacy->title }}</div>
                            </div>

                            <div class="col-md-4">
                                <label class="fw-bold text-muted">Version</label>
                                <div class="form-control form-control-solid">{{ $privacy->version }}</div>
                            </div>

                            <div class="col-md-4">
                                <label class="fw-bold text-muted">Status</label>
                                <span class="badge {{ $privacy->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($privacy->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold text-muted">Content</label>
                            <div class="border rounded p-4 bg-light">
                                {!! $privacy->content !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- EDIT MODAL --}}
        @can('edit privacy')
            <div class="modal fade" tabindex="-1" id="EditPrivacyModal-{{ $privacy->id }}">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">

                        <div class="modal-header py-3" style="background: beige;">
                            <h3 class="modal-title">Update Privacy Policy</h3>
                            <button type="button" class="btn btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                                <i class="fa-solid fa-xmark fs-2"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form action="{{ route('admin.privacy.update', $privacy->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row g-4 mb-5">
                                    <div class="col-md-8">
                                        <x-metronic.label class="fw-bold">Title</x-metronic.label>
                                        <x-metronic.input name="title" :value="$privacy->title" class="form-control-sm" />
                                    </div>

                                    <div class="col-md-4">
                                        <x-metronic.label class="fw-bold">Version</x-metronic.label>
                                        <x-metronic.input name="version" :value="$privacy->version" class="form-control-sm" />
                                    </div>
                                </div>

                                <div class="row g-4 mb-5">
                                    <div class="col-md-4">
                                        <x-metronic.label class="fw-bold">Effective Date</x-metronic.label>
                                        <input type="date" name="effective_date" class="form-control form-control-sm"
                                            value="{{ $privacy->effective_date }}">
                                    </div>

                                    <div class="col-md-4">
                                        <x-metronic.label class="fw-bold">Expiration Date</x-metronic.label>
                                        <input type="date" name="expiration_date" class="form-control form-control-sm"
                                            value="{{ $privacy->expiration_date }}">
                                    </div>

                                    <div class="col-md-4">
                                        <x-metronic.label class="fw-bold">Status</x-metronic.label>
                                        <x-metronic.select-option name="status" class="form-select-sm">
                                            <option value="active" @selected($privacy->status === 'active')>Active</option>
                                            <option value="inactive" @selected($privacy->status === 'inactive')>Inactive</option>
                                        </x-metronic.select-option>
                                    </div>
                                </div>

                                <x-metronic.editor name="content" label="Privacy Content" :value="$privacy->content"
                                    rows="10" />

                                <div class="d-flex justify-content-end mt-5">
                                    <button type="submit" class="btn btn-sm rounded-0 btn-outline btn-outline-primary">
                                        Submit
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        @endcan
    @endforeach

    @push('scripts')
        <script>
            $('#privacySearch').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $('#privacyTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().includes(value));
                });
            });

            $(document).on('change', '.js-status-toggle', function() {

                const checkbox = $(this);
                const table = $('#privacyTable');
                const loader = $('#privacyTableLoader');
                const url = checkbox.data('url');
                const id = checkbox.data('id');

                loader.removeClass('d-none');
                table.addClass('table-loading');

                $.post(url, {
                        _token: '{{ csrf_token() }}'
                    })
                    .done(res => {

                        $('.js-status-toggle').prop('checked', false);
                        $('.js-status-badge')
                            .removeClass('badge-success')
                            .addClass('badge-danger')
                            .text('Inactive');

                        if (res.new_status === 'active') {
                            const row = $('[data-row="' + id + '"]');
                            row.find('.js-status-toggle').prop('checked', true);
                            row.find('.js-status-badge')
                                .removeClass('badge-danger')
                                .addClass('badge-success')
                                .text('Active');
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Updated',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    })
                    .fail(() => {
                        Swal.fire('Error', 'Status update failed', 'error');
                        checkbox.prop('checked', !checkbox.prop('checked'));
                    })
                    .always(() => {
                        loader.addClass('d-none');
                        table.removeClass('table-loading');
                    });
            });
        </script>
    @endpush

</x-admin-app-layout>
