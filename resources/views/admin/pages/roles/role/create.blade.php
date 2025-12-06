<x-admin-app-layout :title="'Role Create'">

    <div class="card shadow-sm">

        {{-- =========================
            HEADER
        ========================== --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold">Role Create</h3>

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

        {{-- =========================
            BODY
        ========================== --}}
        <div class="card-body">

            <form action="{{ route('admin.roles.store') }}" method="POST" class="mb-10">
                @csrf

                {{-- =========================
                    ROLE NAME
                ========================== --}}


                {{-- =========================
                    PERMISSIONS FILTER + TABLE
                ========================== --}}
                <div class="card border mb-8">

                    {{-- SEARCH BAR --}}
                    <div class="card-header justify-content-center py-3">
                        <div class="">
                            <label class="form-label fw-semibold">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm w-lg-475px" name="name"
                                placeholder="Enter role name" value="{{ old('name') }}" required>
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
                                                <input type="checkbox" id="kt_roles_select_all"
                                                    class="form-check-input">
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

                                            {{-- GROUP NAME + ROW SELECT --}}
                                            <td class="fw-semibold">
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="checkbox" class="form-check-input kt_roles_select_row"
                                                        id="row_{{ $key }}">
                                                    <label for="row_{{ $key }}" class="cursor-pointer">
                                                        {{ $group->group_name }}
                                                    </label>
                                                </div>
                                            </td>

                                            {{-- PERMISSIONS LIST --}}
                                            <td>
                                                <div class="d-flex flex-wrap gap-7">

                                                    @foreach (app\models\Admin::getpermissionByGroupName($group->group_name) as $permission)
                                                        <div
                                                            class="d-flex align-items-center gap-2 permission-item me-5">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="permissions[]" id="perm_{{ $permission->id }}"
                                                                value="{{ $permission->name }}">

                                                            <label class="cursor-pointer"
                                                                for="perm_{{ $permission->id }}">
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

                {{-- SUBMIT BUTTONS --}}
                <div class="d-flex gap-3 float-end">
                    <button type="reset" class="btn btn-light">Reset</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check me-2"></i> Submit
                    </button>
                </div>

            </form>

        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

                /* ============================================
                    SEARCH IN PERMISSIONS
                ============================================ */
                $("#permissionSearch").on("keyup", function() {
                    let value = $(this).val().toLowerCase();

                    $(".permission-row").each(function() {
                        let row = $(this);
                        let text = row.text().toLowerCase();
                        row.toggle(text.includes(value));
                    });
                });


                /* ============================================
                    SELECT ALL PERMISSIONS
                ============================================ */
                $("#kt_roles_select_all").on("change", function() {
                    let checked = $(this).is(":checked");

                    $(".kt_roles_select_row").prop("checked", checked);
                    $("input[name='permissions[]']").prop("checked", checked);
                });


                /* ============================================
                    SELECT BY ROW GROUP
                ============================================ */
                $(".kt_roles_select_row").on("change", function() {
                    let row = $(this).closest("tr");
                    let state = $(this).is(":checked");

                    row.find("input[name='permissions[]']").prop("checked", state);

                    updateSelectAll();
                });


                /* ============================================
                    UPDATE SELECT ALL STATUS
                ============================================ */
                $("input[name='permissions[]']").on("change", function() {
                    updateSelectAll();
                });

                function updateSelectAll() {
                    let total = $("input[name='permissions[]']").length;
                    let selected = $("input[name='permissions[]']:checked").length;

                    $("#kt_roles_select_all").prop("checked", total > 0 && total === selected);
                }

            });
        </script>
    @endpush

</x-admin-app-layout>
