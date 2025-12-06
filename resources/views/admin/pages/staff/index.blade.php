<x-admin-app-layout :title="'Staff List'">

    <!-- ===========================
         PAGE HEADER
    ============================ -->
    <div class="card shadow-sm mb-7">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-4">
            <div>
                <h3 class="fw-bold mb-1">Staffs ({{ $staffs->count() }})</h3>
                <div class="text-muted">Overview of all staffs and assigned roles.</div>
            </div>

            <a href="{{ route('admin.staff.create') }}"
               class="btn btn-light-info btn-sm d-flex align-items-center">
                <i class="fa fa-plus me-2"></i> Add Staff
            </a>
        </div>
    </div>


    <!-- ===========================
         STAFF TABLE CARD
    ============================ -->
    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h3 class="card-title fw-bold">Staff List</h3>

            <div class="d-flex align-items-center">
                <div class="input-group input-group-sm" style="width: 220px;">
                    <span class="input-group-text bg-light">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                    <input type="text"
                           class="form-control"
                           placeholder="Search users..."
                           id="staffSearchInput">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-row-bordered align-middle mb-0" id="staffTable">
                    <thead class="bg-light fw-bold text-muted">
                        <tr>
                            <th class="text-center" style="width:60px;">No.</th>
                            <th style="min-width:250px;">Member</th>
                            <th style="min-width:150px;">Designation</th>
                            <th style="min-width:150px;">Phone</th>
                            <th style="min-width:120px;">Status</th>
                            <th class="text-end" style="width:60px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staffs as $key => $staff)
                            <tr>
                                <td class="text-center fw-semibold">{{ $key + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ !empty($staff->photo) ? url('storage/' . $staff->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($staff->name) }}"
                                             width="40" height="40" class="rounded-circle">

                                        <div>
                                            <div class="fw-bold">{{ $staff->name }}</div>
                                            <div class="text-muted small">{{ $staff->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td><span class="fw-semibold">{{ $staff->designation }}</span></td>
                                <td><span class="fw-semibold">{{ $staff->phone }}</span></td>

                                <td>
                                    <span class="badge badge-light-{{ $staff->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($staff->status) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light btn-active-light-primary" data-bs-toggle="dropdown">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.staff.edit', $staff->id) }}" class="dropdown-item d-flex align-items-center">
                                                    <i class="fa fa-pencil-alt me-2"></i>Edit
                                                </a>
                                            </li>

                                            <li><hr class="dropdown-divider"></li>

                                            <li>
                                                <form action="{{ route('admin.staff.destroy', $staff->id) }}"
                                                      method="POST"
                                                      class="deleteForm">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="dropdown-item d-flex align-items-center text-danger">
                                                        <i class="fa fa-trash me-2"></i>Remove
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <!-- FOOTER PAGINATION -->
            <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                <div class="d-flex align-items-center gap-2 order-2 order-md-1">
                    Show
                    <select class="form-select form-select-sm w-auto" data-kt-datatable-size="true"></select>
                    per page
                </div>

                <div class="d-flex align-items-center gap-4 order-1 order-md-2">
                    <span data-kt-datatable-info="true"></span>
                    <div class="pagination" data-kt-datatable-pagination="true"></div>
                </div>

            </div>
        </div>

    </div>


    @push('scripts')
    <script>

        /* =====================================================
            REAL-TIME TABLE SEARCH (WORKING)
        ===================================================== */
        $("#staffSearchInput").on("keyup", function () {
            let value = $(this).val().toLowerCase();

            $("#staffTable tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().includes(value));
            });
        });


        /* =====================================================
            SWEETALERT DELETE
        ===================================================== */
        $(document).on("submit", ".deleteForm", function(e) {
            e.preventDefault();
            let form = this;

            Swal.fire({
                title: "Are you sure?",
                text: "This cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "Cancel",
                buttonsStyling: false,
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-secondary"
                }
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });

    </script>
    @endpush

</x-admin-app-layout>
