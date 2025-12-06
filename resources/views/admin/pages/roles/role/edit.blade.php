<x-admin-app-layout :title="'Edit Role'">

    <div class="card shadow-sm">

        {{-- =========================
            HEADER
        ========================== --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold">Edit Role</h3>

            <div class="d-flex align-items-center justify-content-end">
                <div class="me-5">
                    <input type="text" id="permissionSearch" class="form-control form-control-sm w-lg-350px"
                        placeholder="Search permissions...">
                </div>
                @can('view role')
                    <div>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light btn-active-light-info btn-sm">
                            <i class="fa fa-arrow-left me-2"></i> Back to List
                        </a>
                    </div>
                @endcan
            </div>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="card border mb-8">

                    <div class="card-header justify-content-center py-3">
                        <div class="">
                            <label class="form-label fw-semibold">Role Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $role->name) }}"
                        class="form-control form-control-sm w-lg-450px mx-auto" placeholder="Enter role name" required>
                        </div>
                    </div>
                    {{-- TABLE --}}
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-row-bordered align-middle mb-0">
                                <thead class="bg-light">
                                    <tr class="fw-bold text-gray-700">
                                        <th style="width:20%">Permission Group</th>
                                        <th style="width:80%">
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="checkbox" class="form-check-input"
                                                    id="kt_roles_select_all">
                                                <label for="kt_roles_select_all" class="fw-semibold cursor-pointer">
                                                    Select All
                                                </label>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody id="permissionsTable">
                                    @foreach ($group_permissions as $key => $group)
                                        <tr class="permission-row">

                                            {{-- GROUP NAME + SELECT ROW --}}
                                            <td class="fw-semibold">
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="checkbox" class="form-check-input kt_roles_select_row"
                                                        id="group_{{ $key }}">
                                                    <label for="group_{{ $key }}" class="cursor-pointer">
                                                        {{ $group->group_name }}
                                                    </label>
                                                </div>
                                            </td>

                                            {{-- PERMISSIONS --}}
                                            <td>
                                                <div class="d-flex flex-wrap gap-10">

                                                    @foreach (app\models\Admin::getpermissionByGroupName($group->group_name) as $permission)
                                                        <div class="d-flex align-items-center gap-2 permission-item me-5">

                                                            <input type="checkbox"
                                                                class="form-check-input perm-checkbox"
                                                                name="permissions[]" id="perm_{{ $permission->id }}"
                                                                value="{{ $permission->name }}"
                                                                @checked($role->permissions->contains('id', $permission->id))>

                                                            <label for="perm_{{ $permission->id }}"
                                                                class="cursor-pointer">
                                                                {{ Str::title($permission->name) }}
                                                            </label>

                                                        </div>
                                                    @endforeach

                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>

                {{-- =========================
                    SUBMIT BUTTONS
                ========================== --}}
                <div class="d-flex gap-3 float-end">
                    <button type="reset" class="btn btn-light">Reset</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check me-2"></i> Update Role
                    </button>
                </div>

            </form>

        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

                /* ===============================
                   SEARCH PERMISSIONS
                =============================== */
                $("#permissionSearch").on("keyup", function() {
                    let value = $(this).val().toLowerCase();

                    $(".permission-row").each(function() {
                        let rowText = $(this).text().toLowerCase();
                        $(this).toggle(rowText.includes(value));
                    });
                });


                /* ===============================
                   SELECT ALL PERMISSIONS
                =============================== */
                $("#kt_roles_select_all").on("change", function() {
                    let checked = $(this).is(":checked");

                    $(".kt_roles_select_row").prop("checked", checked);
                    $("input[name='permissions[]']").prop("checked", checked);
                });


                /* ===============================
                   SELECT BY GROUP ROW
                =============================== */
                $(document).on("change", ".kt_roles_select_row", function() {
                    let row = $(this).closest("tr");
                    let state = $(this).is(":checked");

                    row.find("input[name='permissions[]']").prop("checked", state);

                    updateSelectAll();
                });


                /* ===============================
                   INDIVIDUAL PERMISSION CLICK
                =============================== */
                $(document).on("change", "input[name='permissions[]']", function() {
                    updateSelectAll();
                    updateGroupCheckboxes();
                });


                /* ===============================
                   UPDATE GROUP ROW STATUS
                =============================== */
                function updateGroupCheckboxes() {
                    $(".permission-row").each(function() {
                        let row = $(this);
                        let total = row.find("input[name='permissions[]']").length;
                        let checked = row.find("input[name='permissions[]']:checked").length;

                        row.find(".kt_roles_select_row").prop("checked", total === checked);
                    });
                }


                /* ===============================
                   UPDATE SELECT ALL STATUS
                =============================== */
                function updateSelectAll() {
                    let total = $("input[name='permissions[]']").length;
                    let checked = $("input[name='permissions[]']:checked").length;

                    $("#kt_roles_select_all").prop("checked", total > 0 && total === checked);
                }


                /* ===============================
                   INITIALIZE CHECK STATES
                =============================== */
                updateGroupCheckboxes();
                updateSelectAll();

            });
        </script>
    @endpush

</x-admin-app-layout>
