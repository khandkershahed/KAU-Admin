<x-admin-app-layout :title="'Administration Groups'">

    <div class="card card-flush">
        <div class="card-header align-items-center">
            <div class="card-title">
                <h3 class="fw-bold">Administration Groups</h3>
            </div>
            <div class="card-toolbar">
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                    <i class="fa fa-plus me-2"></i> Add Group
                </button>
            </div>
        </div>

        <div class="card-body">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="alert alert-success mb-5">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-5">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($groups->count() === 0)
                <div class="alert alert-info mb-0">
                    No groups found. Click <strong>"Add Group"</strong> to create a new one.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-3">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-50px text-center">Sort</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th class="w-125px text-center">Status</th>
                                <th class="w-150px text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="group-sortable">
                            @foreach ($groups as $group)
                                <tr data-id="{{ $group->id }}">
                                    <td class="text-center">
                                        <span class="cursor-move text-gray-500">
                                            <i class="fa fa-arrows-alt-v fs-4"></i>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-gray-800">{{ $group->name }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $group->slug }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($group->status)
                                            <span class="badge badge-light-success">Active</span>
                                        @else
                                            <span class="badge badge-light-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.admin-offices.index') }}?group_id={{ $group->id }}"
                                            class="btn btn-sm btn-light-primary me-2 mb-2">
                                            <i class="fa fa-sitemap me-1"></i> Offices
                                        </a>

                                        <button class="btn btn-sm btn-light-warning me-2 mb-2 editGroupBtn"
                                            data-id="{{ $group->id }}" data-name="{{ $group->name }}"
                                            data-status="{{ $group->status }}" data-bs-toggle="modal"
                                            data-bs-target="#editGroupModal">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <form action="{{ route('admin.admin-groups.destroy', $group->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this group? This will also delete all related offices.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-muted fs-7 mt-4">
                    <i class="fa fa-info-circle me-1"></i>
                    Drag the <strong>Sort</strong> icon to reorder groups. Order is saved automatically.
                </div>
            @endif

        </div>
    </div>


    {{-- ----------------------------------------------------- --}}
    {{-- CREATE GROUP MODAL --}}
    {{-- ----------------------------------------------------- --}}
    <div class="modal fade" id="createGroupModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST" action="{{ route('admin.admin-groups.store') }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Add Administration Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-5">
                            <label class="form-label required">Group Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" id="addStatus" data-control="select2"
                                data-allow-clear="true" data-placeholder="Select Status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i> Save Group
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    {{-- ----------------------------------------------------- --}}
    {{-- EDIT GROUP MODAL --}}
    {{-- ----------------------------------------------------- --}}
    <div class="modal fade" id="editGroupModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST" id="editGroupForm">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Administration Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-5">
                            <label class="form-label required">Group Name</label>
                            <input type="text" id="editGroupName" class="form-control" name="name" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Status</label>
                            <select id="editGroupStatus" name="status" class="form-select" data-control="select2"
                                data-allow-clear="true" data-placeholder="Select Status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i> Update Group
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    @push('scripts')
        {{-- SortableJS CDN --}}
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

        <script>
            // ----- SORTABLE -----
            const tbody = document.getElementById('group-sortable');
            if (tbody) {
                Sortable.create(tbody, {
                    handle: '.cursor-move',
                    animation: 150,
                    onEnd: function() {
                        const order = [];
                        tbody.querySelectorAll('tr[data-id]').forEach(row => {
                            order.push(row.getAttribute('data-id'));
                        });

                        fetch("{{ route('admin.admin-groups.sort') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    order
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    toastr.success('Group order updated.');
                                }
                            });
                    }
                });
            }

            // ----- EDIT MODAL FILL -----
            document.querySelectorAll('.editGroupBtn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const status = this.dataset.status;

                    document.getElementById('editGroupName').value = name;
                    document.getElementById('editGroupStatus').value = status;

                    document.getElementById('editGroupForm').action =
                        "{{ url('/admin/admin-groups') }}/" + id;
                });
            });
        </script>
    @endpush

</x-admin-app-layout>
