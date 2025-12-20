<x-admin-app-layout :title="'Manage Terms & Conditions'">

    <style>
        .table-loading {
            pointer-events: none;
            opacity: 0.45;
        }
    </style>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h4 class="mb-0">Manage Terms & Conditions</h4>

            <div class="d-flex align-items-center">
                <div class="input-group input-group-sm me-5" style="width: 220px;">
                    <span class="input-group-text bg-light">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search Terms..." id="termsSearch">
                </div>

                <a href="javascript:void(0)" class="btn btn-sm btn-outline btn-outline-info rounded-0"
                    data-bs-toggle="modal" data-bs-target="#AddTermModal">
                    <i class="fa fa-plus me-2"></i> Add Terms
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive position-relative">

                {{-- TABLE LOADER --}}
                <div id="termsTableLoader" class="position-absolute top-50 start-50 translate-middle d-none"
                    style="z-index: 10;">
                    <div class="d-flex flex-column align-items-center">
                        <span class="spinner-border text-primary mb-2"></span>
                        <span class="text-muted small">Updating status...</span>
                    </div>
                </div>

                <table class="table border table-striped table-row-bordered align-middle mb-0 gy-5 gs-7"
                    id="termsTable">

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
                        @forelse ($terms as $term)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>

                                <td>{{ $term->title }}</td>

                                <td>{{ $term->version }}</td>

                                <td>
                                    {{ $term->effective_date }}
                                    @if ($term->expiration_date)
                                        <br>
                                        <small class="text-muted">
                                            Exp: {{ $term->expiration_date }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex align-items-center" data-row="{{ $term->id }}">
                                        <div class="me-3">
                                            <span
                                                class="badge js-status-badge {{ $term->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                                {{ ucfirst($term->status) }}
                                            </span>
                                        </div>

                                        <div class="form-check form-switch ms-5">
                                            <input type="checkbox" class="form-check-input js-status-toggle"
                                                data-id="{{ $term->id }}"
                                                data-url="{{ route('admin.terms.toggle-status', $term->id) }}"
                                                @checked($term->status === 'active')>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <a href="javascript:void(0)" class="me-5" data-bs-toggle="modal"
                                        data-bs-target="#ShowTermModal-{{ $term->id }}">
                                        <i class="fa-solid fa-eye text-info"></i>
                                    </a>

                                    <a href="javascript:void(0)" class="me-5" data-bs-toggle="modal"
                                        data-bs-target="#EditTermModal-{{ $term->id }}">
                                        <i class="fa-solid fa-pen text-primary"></i>
                                    </a>

                                    <a href="{{ route('admin.terms.destroy', $term->id) }}" class="delete">
                                        <i class="fa-solid fa-trash-alt text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    No terms found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    {{-- ================= CREATE MODAL ================= --}}
    <div class="modal fade" tabindex="-1" id="AddTermModal">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header py-3" style="background: beige;">
                    <h3 class="modal-title">Create Terms</h3>
                    <button type="button" class="btn btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark fs-2"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('admin.terms.store') }}" method="POST">
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

                        <x-metronic.editor name="content" label="Terms Content" rows="10" />

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

    {{-- ================= SHOW & EDIT MODALS ================= --}}
    @foreach ($terms as $term)
        {{-- SHOW --}}
        <div class="modal fade" tabindex="-1" id="ShowTermModal-{{ $term->id }}">
            <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header py-3" style="background: beige;">
                        <h3 class="modal-title">Terms Details</h3>
                        <button type="button" class="btn btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark fs-2"></i>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <label class="fw-bold text-muted">Title</label>
                                <div class="form-control form-control-solid">{{ $term->title }}</div>
                            </div>

                            <div class="col-md-4">
                                <label class="fw-bold text-muted">Version</label>
                                <div class="form-control form-control-solid">{{ $term->version }}</div>
                            </div>

                            <div class="col-md-4">
                                <label class="fw-bold text-muted">Status</label>
                                <span class="badge {{ $term->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($term->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold text-muted">Content</label>
                            <div class="border rounded p-4 bg-light">
                                {!! $term->content !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- EDIT --}}
        <div class="modal fade" tabindex="-1" id="EditTermModal-{{ $term->id }}">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header py-3" style="background: beige;">
                        <h3 class="modal-title">Update Terms</h3>
                        <button type="button" class="btn btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark fs-2"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('admin.terms.update', $term->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-4 mb-5">
                                <div class="col-md-8">
                                    <x-metronic.label class="fw-bold">Title</x-metronic.label>
                                    <x-metronic.input name="title" :value="$term->title" class="form-control-sm" />
                                </div>

                                <div class="col-md-4">
                                    <x-metronic.label class="fw-bold">Version</x-metronic.label>
                                    <x-metronic.input name="version" :value="$term->version" class="form-control-sm" />
                                </div>
                            </div>

                            <div class="row g-4 mb-5">
                                <div class="col-md-4">
                                    <x-metronic.label class="fw-bold">Effective Date</x-metronic.label>
                                    <input type="date" name="effective_date" class="form-control form-control-sm"
                                        value="{{ $term->effective_date }}">
                                </div>

                                <div class="col-md-4">
                                    <x-metronic.label class="fw-bold">Expiration Date</x-metronic.label>
                                    <input type="date" name="expiration_date" class="form-control form-control-sm"
                                        value="{{ $term->expiration_date }}">
                                </div>

                                <div class="col-md-4">
                                    <x-metronic.label class="fw-bold">Status</x-metronic.label>
                                    <x-metronic.select-option name="status" class="form-select-sm">
                                        <option value="active" @selected($term->status === 'active')>Active</option>
                                        <option value="inactive" @selected($term->status === 'inactive')>Inactive</option>
                                    </x-metronic.select-option>
                                </div>
                            </div>

                            <x-metronic.editor name="content" label="Terms Content" :value="$term->content"
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
    @endforeach

    @push('scripts')
        <script>
            $('#termsSearch').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $('#termsTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().includes(value));
                });
            });

            $(document).on('change', '.js-status-toggle', function() {

                const checkbox = $(this);
                const table = $('#termsTable');
                const loader = $('#termsTableLoader');
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
