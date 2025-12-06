<x-admin-app-layout :title="'Roles List'">

    <div class="card shadow-sm">

        {{-- Header --}}
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold">Roles List</h3>

            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus me-2"></i> Add New Role
            </a>
        </div>

        {{-- Body --}}
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-row-bordered table-striped align-middle gy-4">

                    <thead style="background: aliceblue;">
                        <tr class="fw-bold text-gray-700">
                            <th style="width: 30px">#</th>
                            <th>Role Name</th>
                            <th style="width: 160px">Users</th>
                            <th>Permissions</th>
                            <th style="width: 120px">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($roles as $key => $role)
                            <tr>

                                {{-- Index --}}
                                <td>{{ $key + 1 }}</td>

                                {{-- Role Name --}}
                                <td class="fw-semibold fs-6">{{ $role->name }}</td>

                                {{-- User Count --}}
                                <td>
                                    <span class="badge bg-primary">
                                        {{ count($role->users) }}
                                    </span>
                                </td>

                                {{-- Permissions List --}}
                                <td>
                                    @if ($role->permissions->count())
                                        <div class="d-flex flex-wrap">

                                            @foreach ($role->permissions as $permission)
                                                <span class="badge badge-light-primary text-primary fw-semibold me-2 mb-2">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach

                                        </div>
                                    @else
                                        <span class="text-muted">No permissions</span>
                                    @endif
                                </td>

                                {{-- Edit --}}
                                <td>
                                    <a href="{{ route('admin.roles.edit', $role->id) }}"
                                        class="btn btn-light btn-sm btn-active-light-primary">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>

    </div>

</x-admin-app-layout>
