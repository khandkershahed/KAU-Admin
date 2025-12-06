<x-admin-app-layout :title="'Office Builder — ' . $office->title">



    <!-- SECTIONS -->
    <div class="card mb-10" id="office-sections">
        <div class="card-header align-items-center">
            <h3 class="card-title fw-bold">Sections</h3>
            <div class="card-toolbar">
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createSectionModal">
                    <i class="fa fa-plus me-2"></i> Add Section
                </button>
            </div>
        </div>

        <div class="card-body">

            @if ($sections->count() == 0)
                <div class="alert alert-info">
                    No sections found. Click <strong>Add Section</strong> to create one.
                </div>
            @else
                <div id="section-list">

                    @foreach ($sections as $section)
                        <div class="card mb-5 border" data-id="{{ $section->id }}">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between" style="width: 100%">

                                    <div class="d-flex align-items-center">
                                        <span class="cursor-move me-3">
                                            <i class="fa fa-arrows-alt-v fs-3 text-gray-500"></i>
                                        </span>
                                        <h4 class="fw-bold mb-0">{{ $section->title ?? 'Untitled Section' }}</h4>
                                        @if (!$section->status)
                                            <span class="badge badge-light-danger ms-3">Inactive</span>
                                        @endif
                                    </div>

                                    <div>
                                        <button class="btn btn-sm btn-light-warning editSectionBtn"
                                            data-id="{{ $section->id }}" data-title="{{ $section->title }}"
                                            data-position="{{ $section->position }}"
                                            data-status="{{ $section->status }}" data-bs-toggle="modal"
                                            data-bs-target="#editSectionModal">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <form class="d-inline"
                                            action="{{ route('admin.admin-sections.destroy', $section->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Delete this section and all members?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-light-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body">

                                <!-- MEMBERS HEADER -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0">Members</h5>

                                    <button class="btn btn-sm btn-primary addMemberBtn"
                                        data-section="{{ $section->id }}" data-bs-toggle="modal"
                                        data-bs-target="#createMemberModal">
                                        <i class="fa fa-plus me-2"></i> Add Member
                                    </button>
                                </div>

                                <!-- MEMBERS TABLE -->
                                @php
                                    $sectionMembers = $members->where('section_id', $section->id);
                                @endphp

                                @if ($sectionMembers->count() == 0)
                                    <div class="alert alert-secondary">No members in this section.</div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 gy-3">
                                            <thead>
                                                <tr class="fw-bold text-muted text-uppercase fs-7">
                                                    <th width="50" class="text-center">Sort</th>
                                                    <th width="60">Photo</th>
                                                    <th>Name</th>
                                                    <th>Designation</th>
                                                    <th width="150" class="text-end">Actions</th>
                                                </tr>
                                            </thead>

                                            <tbody class="member-sortable" data-section="{{ $section->id }}">
                                                @foreach ($sectionMembers as $member)
                                                    <tr data-id="{{ $member->id }}">

                                                        <td class="text-center cursor-move">
                                                            <i class="fa fa-arrows-alt-v fs-4 text-gray-500"></i>
                                                        </td>

                                                        <td>
                                                            @if ($member->image)
                                                                <img src="{{ asset('storage/' . $member->image) }}"
                                                                    class="img-thumbnail"
                                                                    style="height:40px;width:40px;object-fit:cover;">
                                                            @else
                                                                <span class="text-muted">—</span>
                                                            @endif
                                                        </td>

                                                        <td class="fw-bold">{{ $member->name }}</td>

                                                        <td>{{ $member->designation }}</td>

                                                        <td class="text-end">
                                                            <button class="btn btn-sm btn-light-warning editMemberBtn"
                                                                data-id="{{ $member->id }}"
                                                                data-name="{{ $member->name }}"
                                                                data-designation="{{ $member->designation }}"
                                                                data-email="{{ $member->email }}"
                                                                data-phone="{{ $member->phone }}"
                                                                data-image="{{ $member->image }}"
                                                                data-position="{{ $member->position }}"
                                                                data-section="{{ $member->section_id }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editMemberModal">
                                                                <i class="fa fa-edit"></i>
                                                            </button>

                                                            <form class="d-inline"
                                                                action="{{ route('admin.admin-members.destroy', $member->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Delete member?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-light-danger">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </form>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach

                </div>

            @endif

        </div>
    </div>

    <!-- ============================= -->
    <!-- CREATE SECTION MODAL -->
    <!-- ============================= -->
    <div class="modal fade" id="createSectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST" action="{{ route('admin.admin-sections.store') }}">
                    @csrf
                    <input type="hidden" name="office_id" value="{{ $office->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title">Add Section</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-5">
                            <label class="form-label required">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Position</label>
                            <input type="number" name="position" value="0" class="form-control">
                            <small class="text-muted">If duplicate → system will suggest available positions.</small>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" data-control="select2">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">
                            <i class="fa fa-save me-2"></i> Save Section
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- ============================= -->
    <!-- EDIT SECTION MODAL -->
    <!-- ============================= -->
    <div class="modal fade" id="editSectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="editSectionForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Section</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-5">
                            <label class="form-label required">Title</label>
                            <input type="text" name="title" id="editSectionTitle" class="form-control"
                                required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Position</label>
                            <input type="number" name="position" id="editSectionPosition" class="form-control">
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Status</label>
                            <select name="status" id="editSectionStatus" class="form-select"
                                data-control="select2">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">
                            <i class="fa fa-save me-2"></i> Update Section
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- ============================= -->
    <!-- CREATE MEMBER MODAL -->
    <!-- ============================= -->
    <div class="modal fade" id="createMemberModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form method="POST" action="{{ route('admin.admin-members.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="office_id" value="{{ $office->id }}">
                    <input type="hidden" name="section_id" id="createMemberSectionId">

                    <div class="modal-header">
                        <h5 class="modal-title">Add Member</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-6 mb-5">
                                <label class="form-label required">Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="col-lg-6 mb-5">
                                <label class="form-label">Designation</label>
                                <input type="text" name="designation" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-5">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-5">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Photo</label>
                            <input type="file" name="image" accept="image/*" class="form-control"
                                id="createMemberPhotoInput">

                            <div class="mt-3">
                                <img id="createMemberPhotoPreview"
                                    style="display:none;height:80px;width:80px;object-fit:cover;"
                                    class="img-thumbnail">
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Position</label>
                            <input type="number" name="position" value="0" class="form-control">
                            <small class="text-muted">If duplicate → system will suggest available positions.</small>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">
                            <i class="fa fa-save me-2"></i> Save Member
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- ============================= -->
    <!-- EDIT MEMBER MODAL -->
    <!-- ============================= -->
    <div class="modal fade" id="editMemberModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form id="editMemberForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="section_id" id="editMemberSectionId">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Member</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-6 mb-5">
                                <label class="form-label required">Name</label>
                                <input type="text" name="name" id="editMemberName" class="form-control"
                                    required>
                            </div>

                            <div class="col-lg-6 mb-5">
                                <label class="form-label">Designation</label>
                                <input type="text" name="designation" id="editMemberDesignation"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-5">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="editMemberEmail" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-5">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" id="editMemberPhone" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Existing Photo</label>
                            <img id="editMemberImageExisting" style="height:80px;width:80px;object-fit:cover;"
                                class="img-thumbnail">
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Change Photo</label>
                            <input type="file" name="image" id="editMemberPhotoInput" accept="image/*"
                                class="form-control">

                            <div class="mt-3">
                                <img id="editMemberPhotoPreview"
                                    style="display:none;height:80px;width:80px;object-fit:cover;"
                                    class="img-thumbnail">
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Position</label>
                            <input type="number" name="position" id="editMemberPosition" class="form-control">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">
                            <i class="fa fa-save me-2"></i> Update Member
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

        <script>
            /* ==========================================================
           SECTION SORTING
        ========================================================== */
            const sectionList = document.getElementById('section-list');

            if (sectionList) {
                Sortable.create(sectionList, {
                    handle: '.cursor-move',
                    animation: 150,
                    onEnd: function() {
                        const order = [];
                        sectionList.querySelectorAll("[data-id]").forEach(el => order.push(el.dataset.id));

                        fetch("{{ route('admin.admin-sections.sort') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content
                            },
                            body: JSON.stringify({
                                order
                            })
                        });
                    }
                });
            }

            /* ==========================================================
               MEMBER SORTING
            ========================================================== */
            document.querySelectorAll(".member-sortable").forEach(tbody => {
                Sortable.create(tbody, {
                    handle: ".cursor-move",
                    animation: 150,
                    onEnd: function() {
                        const order = [];
                        const sectionId = tbody.dataset.section;

                        tbody.querySelectorAll("tr[data-id]").forEach(row => order.push(row.dataset.id));

                        fetch("{{ route('admin.admin-members.sort') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]')
                                    .content
                            },
                            body: JSON.stringify({
                                order,
                                section_id: sectionId
                            })
                        });
                    }
                });
            });

            /* ==========================================================
               CREATE MEMBER modal — auto inject section id
            ========================================================== */
            document.querySelectorAll('.addMemberBtn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('createMemberSectionId').value = this.dataset.section;
                });
            });

            /* ==========================================================
               EDIT SECTION
            ========================================================== */
            document.querySelectorAll('.editSectionBtn').forEach(btn => {
                btn.addEventListener('click', function() {

                    const id = this.dataset.id;
                    document.getElementById('editSectionForm').action =
                        "{{ url('/admin-section') }}/" + id;

                    document.getElementById('editSectionTitle').value = this.dataset.title;
                    document.getElementById('editSectionPosition').value = this.dataset.position;
                    document.getElementById('editSectionStatus').value = this.dataset.status;
                    $("#editSectionStatus").trigger("change");
                });
            });

            /* ==========================================================
               CREATE MEMBER PHOTO PREVIEW
            ========================================================== */
            document.getElementById('createMemberPhotoInput')?.addEventListener('change', e => {
                const file = e.target.files[0];
                const preview = document.getElementById('createMemberPhotoPreview');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = ev => {
                        preview.src = ev.target.result;
                        preview.style.display = "block";
                    };
                    reader.readAsDataURL(file);
                }
            });

            /* ==========================================================
               EDIT MEMBER - Populate Modal
            ========================================================== */
            document.querySelectorAll('.editMemberBtn').forEach(btn => {
                btn.addEventListener('click', function() {

                    const id = this.dataset.id;

                    document.getElementById('editMemberForm').action =
                        "{{ url('/admin/admin-member') }}/" + id;

                    document.getElementById('editMemberName').value = this.dataset.name;
                    document.getElementById('editMemberDesignation').value = this.dataset.designation;
                    document.getElementById('editMemberEmail').value = this.dataset.email;
                    document.getElementById('editMemberPhone').value = this.dataset.phone;
                    document.getElementById('editMemberPosition').value = this.dataset.position;

                    document.getElementById('editMemberSectionId').value = this.dataset.section;

                    const image = this.dataset.image;
                    const existing = document.getElementById('editMemberImageExisting');

                    if (image) {
                        existing.src = "/storage/" + image;
                        existing.style.display = "block";
                    } else {
                        existing.style.display = "none";
                    }

                    document.getElementById('editMemberPhotoPreview').style.display = "none";
                });
            });

            /* ==========================================================
               EDIT MEMBER PHOTO PREVIEW
            ========================================================== */
            document.getElementById('editMemberPhotoInput')?.addEventListener('change', e => {
                const file = e.target.files[0];
                const preview = document.getElementById('editMemberPhotoPreview');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = ev => {
                        preview.src = ev.target.result;
                        preview.style.display = "block";
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>
    @endpush

</x-admin-app-layout>
