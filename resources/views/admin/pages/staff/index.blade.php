<x-admin-app-layout :title="'Staff List'">
    <div class="kt-container-fixed">
        <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
            <div class="flex flex-col justify-center gap-2">
                <h1 class="text-xl font-medium leading-none text-mono">
                    Staffs ({{ $staffs->count() }})
                </h1>
                <div class="flex items-center gap-2 text-sm font-normal text-secondary-foreground">
                    Overview of all Staffs and roles.
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                {{-- <a class="kt-btn kt-btn-outline" href="#">
                Import Members
            </a> --}}

                {{-- DYNAMIC "ADD STAFF" BUTTON --}}
                {{-- @if (Auth::guard('admin')->user()->can('add.user')) --}}
                <a class="kt-btn btn-outline-info flex items-center gap-2 lg:rounded-none"
                    href="{{ route('admin.staff.create') }}">
                    <i class="ki-filled ki-plus text-lg"></i>
                    Add Staff
                </a>
                {{-- @endif --}}
            </div>
        </div>
    </div>
    <div class="kt-container-fixed">
        <div class="grid gap-5 lg:gap-7.5">
            <div class="kt-card kt-card-grid min-w-full">
                <div class="kt-card-header py-5 flex-wrap gap-2">
                    <h3 class="kt-card-title">
                        Staff List
                    </h3>
                    <div class="flex items-center gap-6">
                        <label class="kt-input">
                            <i class="ki-filled ki-magnifier">
                            </i>
                            <input data-kt-datatable-search="#team_members_table" placeholder="Search users"
                                type="text" value="" />
                        </label>
                        {{-- <label class="kt-label whitespace-nowrap">
                        Active Users
                        <input class="kt-switch kt-switch-sm" name="check" type="checkbox" value="1" />
                    </label> --}}
                    </div>
                </div>
                <div class="kt-card-content">
                    <div class="grid" data-kt-datatable="true" data-kt-datatable-page-size="10"
                        id="team_members_table">
                        <div class="kt-scrollable-x-auto">
                            <table class="kt-table kt-table-border dataTable" data-kt-datatable-table="true">
                                <thead>
                                    <tr>
                                        {{-- <th class="w-[60px] text-center">
                                        <input class="kt-checkbox kt-checkbox-sm" data-kt-datatable-check="true"
                                            type="checkbox">
                                        </input>
                                    </th> --}}
                                        <th class="w-[50px]">No.</th>
                                        <th class="min-w-[250px]">
                                            <span class="kt-table-col">
                                                <span class="kt-table-col-label">
                                                    Member
                                                </span>
                                            </span>
                                        </th>
                                        <th class="min-w-[150px]">
                                            Designation
                                        </th>
                                        <th class="min-w-[150px]">
                                            <span class="kt-table-col">
                                                <span class="kt-table-col-label">
                                                    Phone
                                                </span>
                                            </span>
                                        </th>
                                        <th class="min-w-[120px]">
                                            <span class="kt-table-col">
                                                <span class="kt-table-col-label">
                                                    Status
                                                </span>
                                            </span>
                                        </th>
                                        <th class="w-[60px] text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- DYNAMIC LOOP START --}}
                                    @foreach ($staffs as $key => $staff)
                                        <tr>
                                            {{-- <td class="text-center">
                                                <input class="kt-checkbox kt-checkbox-sm"
                                                    data-kt-datatable-row-check="true" type="checkbox" value="{{ $staff->id }}" />
                                            </td> --}}
                                            <td class="text-foreground font-medium">{{ $key + 1 }}</td>
                                            <td>
                                                <div class="flex items-center gap-2.5">
                                                    <div class="">
                                                        <img class="h-9 w-9 rounded-full object-cover"
                                                            src="{{ !empty($staff->photo) ? url('storage/' . $staff->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($staff->name) }}"
                                                            alt="{{ $staff->name }}" />
                                                    </div>
                                                    <div class="flex flex-col gap-0.5">
                                                        <span class="leading-none font-medium text-sm text-mono">
                                                            {{ $staff->name }}
                                                        </span>
                                                        <span class="text-xs text-secondary-foreground font-normal">
                                                            {{ $staff->email }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-foreground font-normal">{{ $staff->designation }}</span>
                                            </td>
                                            <td>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="leading-none text-foreground font-normal">
                                                        {{ $staff->phone }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="kt-badge kt-badge-outline {{ $staff->status == 'active' ? 'kt-badge-success' : 'kt-badge-destructive' }}">
                                                    {{ ucfirst($staff->status) }}
                                                </span>
                                            </td>
                                            <td class="w-[60px]">
                                                <div class="kt-menu" data-kt-menu="true">
                                                    <div class="kt-menu-item" data-kt-menu-item-offset="0, 10px"
                                                        data-kt-menu-item-placement="bottom-end"
                                                        data-kt-menu-item-placement-rtl="bottom-start"
                                                        data-kt-menu-item-toggle="dropdown"
                                                        data-kt-menu-item-trigger="click">
                                                        <button
                                                            class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                            <i class="ki-filled ki-dots-vertical text-lg">
                                                            </i>
                                                        </button>
                                                        <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]"
                                                            data-kt-menu-dismiss="true">

                                                            {{-- DYNAMIC EDIT BUTTON --}}
                                                            {{-- @if (Auth::guard('admin')->staff()->can('edit.staff')) --}}
                                                            <div class="kt-menu-item">
                                                                <a class="kt-menu-link"
                                                                    href="{{ route('admin.staff.edit', $staff->id) }}">
                                                                    <span class="kt-menu-icon">
                                                                        <i class="ki-filled ki-pencil"></i>
                                                                    </span>
                                                                    <span class="kt-menu-title">
                                                                        Edit
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            {{-- @endif --}}

                                                            {{-- DYNAMIC DELETE BUTTON --}}
                                                            {{-- @if (Auth::guard('admin')->staff()->can('delete.staff')) --}}
                                                            <div class="kt-menu-separator"></div>
                                                            <div class="kt-menu-item">
                                                                <form
                                                                    action="{{ route('admin.staff.destroy', $staff->id) }}"
                                                                    method="POST" class="w-full"
                                                                    onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="kt-menu-link w-full text-left">
                                                                        <span class="kt-menu-icon">
                                                                            <i class="ki-filled ki-trash"></i>
                                                                        </span>
                                                                        <span class="kt-menu-title">
                                                                            Remove
                                                                        </span>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            {{-- @endif --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    {{-- DYNAMIC LOOP END --}}

                                </tbody>
                            </table>
                        </div>
                        <div
                            class="kt-card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-secondary-foreground text-sm font-medium">
                            <div class="flex items-center gap-2 order-2 md:order-1">
                                Show
                                <select class="kt-select w-16" data-kt-datatable-size="true" data-kt-select=""
                                    name="perpage">
                                </select>
                                per page
                            </div>
                            <div class="flex items-center gap-4 order-1 md:order-2">
                                <span data-kt-datatable-info="true">
                                </span>
                                <div class="kt-datatable-pagination" data-kt-datatable-pagination="true">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')

    @endpush
</x-admin-app-layout>
