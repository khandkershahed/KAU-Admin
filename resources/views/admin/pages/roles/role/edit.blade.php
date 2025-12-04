<x-admin-app-layout :title="'Role Create'">

    <div class="kt-container-fixed">
        <div class="grid gap-5 lg:gap-7.5">
            <div class="kt-card kt-card-grid min-w-full">
                <div class="kt-card-header py-2 flex-wrap gap-2">
                    <h3 class="kt-card-title">
                        Role Create
                    </h3>
                    <div class="flex items-center gap-6">

                        <a class="kt-btn btn-outline-info flex items-center gap-2 lg:rounded-none"
                            href="{{ route('admin.roles.index') }}">
                            <i class="ki-filled ki-arrow-left text-lg"></i>
                            Back to the list
                        </a>
                    </div>
                </div>
                <div class="kt-card-content p-5 lg:p-7.5">
                    <form class="kt-form" action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="flex gap-3 justify-center">
                            <div class="kt-form-item gap-3 w-[450px]">
                                <x-metronic.label for="name"
                                    class="kt-form-label font-semibold text-mono">{{ __('Role Name') }}</x-metronic.label>

                                <x-metronic.input id="name" type="name" name="name"
                                    class="kt-input required:true" placeholder="Enter Role name" :value="old('name', $role->name)"
                                    autocomplete="off" maxlength="150" input_description="Role name is required" />
                            </div>
                        </div>

                        <div class="grid space-y-5 kt-scrollable-x-auto">
                            <div class="kt-card">
                                <div class="kt-card-header min-h-16 lg:w-[400px]">
                                    <input type="text" placeholder="Search..." class="kt-input sm:w-48" />
                                </div>
                                <div class="kt-card-table">
                                    <div class="kt-table-wrapper kt-scrollable">
                                        <table class="kt-table kt-table-border" data-kt-datatable-table="true">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="w-[30%]">
                                                        <span class="kt-table-col">
                                                            <span class="kt-table-col-label sm:w-20">
                                                                Administrator Access
                                                                <i class="ki-filled ki-information ms-2 fs-3"
                                                                    data-bs-toggle="tooltip"
                                                                    title="Allows a full access to the system"></i>
                                                            </span>
                                                        </span>
                                                    </th>
                                                    <th scope="col" class="w-[70%] sm:w-48">
                                                        <span class="kt-table-col">
                                                            <span class="kt-table-col-label">
                                                                <div class="flex items-center gap-2">
                                                                    <input type="checkbox"
                                                                        class="kt-checkbox kt-checkbox-sm"
                                                                        id="kt_roles_select_all" value="" />
                                                                    <label class="kt-label text-gray-600"
                                                                        for="kt_roles_select_all">Select
                                                                        all</label>
                                                                </div>
                                                            </span>
                                                        </span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($group_permissions as $key => $group)
                                                    <tr>
                                                        <td>
                                                            <div class="flex items-center gap-2">
                                                                <input type="checkbox"
                                                                    class="kt-checkbox kt-checkbox-sm kt_roles_select_row"
                                                                    id="kt_roles_select_row-{{ $key }}"
                                                                    value="" />
                                                                <label class="kt-label text-gray-600"
                                                                    for="kt_roles_select_row-{{ $key }}">{{ $group->group_name }}</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="grid lg:grid-cols-3 gap-2">
                                                                @foreach (app\models\Admin::getpermissionByGroupName($group->group_name) as $permission)
                                                                    <div class="flex items-center gap-2">
                                                                        <input type="checkbox"
                                                                            class="kt-checkbox kt-checkbox-sm"
                                                                            name="permissions[]"
                                                                            @checked($role->permissions->contains('id', $permission->id))
                                                                            id="check_{{ $permission->id }}"
                                                                            value="{{ $permission->name }}" />
                                                                        <label class="kt-label text-gray-600"
                                                                            for="check_{{ $permission->id }}">{{ Str::title($permission->name) }}</label>
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
                        </div>

                        <div class="kt-form-actions">
                            <button type="reset" class="kt-btn kt-btn-outline">Reset</button>
                            <button type="submit" class="kt-btn btn-outline-success lg:rounded-none">Submit</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>



    @push('scripts')
        <script>
            $(document).ready(function() {

                /** ============================
                 *  SELECT ALL PERMISSIONS
                 *  ============================ */
                $("#kt_roles_select_all").on("change", function() {
                    let isChecked = $(this).is(":checked");
                    $(".kt_roles_select_row").prop("checked", isChecked);
                    $("input[name='permissions[]']").prop("checked", isChecked);
                });

                /** ============================
                 *  SELECT PERMISSION BY GROUP ROW
                 *  ============================ */
                $(document).on('change', ".kt_roles_select_row", function() {
                    let isChecked = $(this).is(":checked");
                    let row = $(this).closest("tr");
                    row.find("input[name='permissions[]']").prop("checked", isChecked);
                    updateSelectAllStatus();
                });

                /** ============================
                 *  UPDATE "SELECT ALL" STATUS
                 *  ============================ */
                $("input[name='permissions[]']").on("change", function() {
                    updateSelectAllStatus();
                });

                function updateSelectAllStatus() {
                    let totalPermissions = $("input[name='permissions[]']").length;
                    let checkedPermissions = $("input[name='permissions[]']:checked").length;
                    $("#kt_roles_select_all").prop("checked", totalPermissions === checkedPermissions);
                }

            });
        </script>
    @endpush
</x-admin-app-layout>
