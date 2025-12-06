<x-admin-app-layout :title="'Administration Offices'">

    <div class="card">
        {{-- ========================================== --}}
        {{-- HEADER --}}
        {{-- ========================================== --}}
        <div class="card-header align-items-center">
            <h3 class="card-title fw-bold">Administration Offices</h3>

            <div class="card-toolbar justify-content-between">
                <form class="me-3" method="GET" action="{{ route('admin.admin-offices.index') }}">
                    <div class="input-group position-relative">

                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Search offices..." value="{{ request('search') }}" id="officeSearchInput"
                            style="height: 36px; padding-right: 35px;" />

                        <!-- CROSS BUTTON -->


                        <button type="button" class="btn btn-danger" id="clearSearchBtn"
                            style="height: 36px; display: none; line-height: 1;">
                            <i class="fas fa-x"></i>
                        </button>

                    </div>


                </form>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createOfficeModal">
                    <i class="fa fa-plus me-2"></i> Add Office
                </button>
            </div>
        </div>


        <div class="card-body">
            <div id="officeListContainer">
                @include('admin.pages.administration.offices.partials.office-list')
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- CREATE OFFICE MODAL --}}
    {{-- ======================================================== --}}
    <div class="modal fade" id="createOfficeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST" action="{{ route('admin.admin-offices.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Create Office</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        {{-- GROUP --}}
                        <div class="mb-5">
                            <label class="form-label required">Group</label>
                            <select name="group_id" class="form-select" id="createGroupSelect" data-placeholder="Select Group" data-allow-clear="true" data-control="select2"
                                required>
                                <option value="">Select Group</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TITLE --}}
                        <div class="mb-5">
                            <label class="form-label required">Office Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        {{-- POSITION --}}
                        <div class="mb-5">
                            <label class="form-label">Position</label>
                            <input type="number" name="position" value="0" class="form-control">
                        </div>

                        {{-- STATUS --}}
                        <div class="mb-5">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" data-control="select2">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        {{-- BANNER IMAGE --}}
                        <div class="mb-5">
                            <label class="form-label">Banner Image</label>
                            <input type="file" name="banner_image" id="createBannerInput" accept="image/*"
                                class="form-control">

                            <div class="mt-3">
                                <img id="createBannerPreview"
                                    style="display:none;height:80px;width:80px;object-fit:cover;" class="img-thumbnail">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Save Office
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- EDIT OFFICE MODAL --}}
    {{-- ======================================================== --}}
    <div class="modal fade" id="editOfficeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form id="editOfficeForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Office</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        {{-- GROUP --}}
                        <div class="mb-5">
                            <label class="form-label required">Group</label>
                            <select name="group_id" id="editGroupSelect" class="form-select" data-control="select2"
                                required>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TITLE --}}
                        <div class="mb-5">
                            <label class="form-label required">Office Title</label>
                            <input type="text" name="title" id="editOfficeTitle" class="form-control" required>
                        </div>

                        {{-- POSITION --}}
                        <div class="mb-5">
                            <label class="form-label">Position</label>
                            <input type="number" name="position" id="editOfficePosition" class="form-control">
                        </div>

                        {{-- STATUS --}}
                        <div class="mb-5">
                            <label class="form-label">Status</label>
                            <select name="status" id="editOfficeStatus" class="form-select" data-control="select2">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        {{-- EXISTING BANNER --}}
                        <div class="mb-3">
                            <label class="form-label">Existing Banner</label>
                            <img id="editBannerExisting" style="height:80px;width:80px;object-fit:cover;"
                                class="img-thumbnail">
                        </div>

                        {{-- UPLOAD NEW BANNER --}}
                        <div class="mb-5">
                            <label class="form-label">Change Banner</label>
                            <input type="file" name="banner_image" id="editBannerInput" accept="image/*"
                                class="form-control">

                            <div class="mt-3">
                                <img id="editBannerPreview"
                                    style="display:none;height:80px;width:80px;object-fit:cover;"
                                    class="img-thumbnail">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Update Office
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

        <script>
            // ===============================================
            // LIVE SEARCH WITHOUT PAGE RELOAD
            // ===============================================
            document.querySelector("input[name='search']").addEventListener("keyup", function() {

                let search = this.value;

                fetch("{{ route('admin.admin-offices.index') }}?search=" + search, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(res => res.json())
                    .then(data => {

                        document.getElementById("officeListContainer").innerHTML = data.html;

                        // Reinitialize sortable after reload
                        initSortable();
                    });
            });

            function initSortable() {
                document.querySelectorAll(".sortable").forEach(tableBody => {
                    Sortable.create(tableBody, {
                        handle: ".cursor-move",
                        animation: 150,
                        onEnd: function() {
                            const order = [];
                            tableBody.querySelectorAll("tr[data-id]").forEach(row =>
                                order.push(row.dataset.id)
                            );

                            fetch("{{ route('admin.admin-offices.sort') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]')
                                        .content
                                },
                                body: JSON.stringify({
                                    order
                                })
                            });
                        }
                    });
                });
            }

            // First run
            initSortable();

            // =====================================================
            // GROUP-SPECIFIC SORTING
            // =====================================================
            document.querySelectorAll(".sortable").forEach(tableBody => {
                Sortable.create(tableBody, {
                    handle: ".cursor-move",
                    animation: 150,
                    onEnd: function() {
                        const order = [];
                        tableBody.querySelectorAll("tr[data-id]").forEach(row =>
                            order.push(row.dataset.id)
                        );

                        fetch("{{ route('admin.admin-offices.sort') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]')
                                    .content
                            },
                            body: JSON.stringify({
                                order
                            })
                        });
                    }
                });
            });

            // =====================================================
            // AUTO SELECT GROUP WHEN ADDING
            // =====================================================
            const createModal = document.getElementById('createOfficeModal');
            createModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const groupId = button?.getAttribute('data-group');

                if (groupId) {
                    document.getElementById('createGroupSelect').value = groupId;
                    $("#createGroupSelect").trigger("change");
                }
            });

            // =====================================================
            // CREATE PREVIEW
            // =====================================================
            document.getElementById('createBannerInput')?.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById('createBannerPreview');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = ev => {
                        preview.src = ev.target.result;
                        preview.style.display = "block";
                    };
                    reader.readAsDataURL(file);
                }
            });

            // =====================================================
            // EDIT MODAL POPULATOR
            // =====================================================
            document.querySelectorAll('.editOfficeBtn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;

                    // UPDATE FORM ACTION
                    document.getElementById('editOfficeForm').action =
                        "{{ url('/admin/admin-offices') }}/" + id;

                    // FILL INPUTS
                    document.getElementById('editOfficeTitle').value = this.dataset.title;
                    document.getElementById('editOfficeStatus').value = this.dataset.status;
                    document.getElementById('editOfficePosition').value = this.dataset.position;

                    // GROUP SELECT
                    document.getElementById('editGroupSelect').value = this.dataset.group;
                    $("#editGroupSelect").trigger("change");

                    // EXISTING BANNER
                    const banner = this.dataset.banner;
                    const existing = document.getElementById('editBannerExisting');

                    if (banner) {
                        existing.src = "/storage/" + banner;
                        existing.style.display = "block";
                    } else {
                        existing.style.display = "none";
                    }

                    // RESET PREVIEW
                    document.getElementById('editBannerPreview').style.display = "none";
                });
            });

            // =====================================================
            // EDIT PREVIEW
            // =====================================================
            document.getElementById('editBannerInput')?.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById('editBannerPreview');

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
    @endpush --}}

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const searchInput = document.getElementById("officeSearchInput");
                const clearBtn = document.getElementById("clearSearchBtn");

                //----------------------------------------------------
                // LIVE SEARCH + ACCORDION TOGGLE FIXED
                //----------------------------------------------------
                function performSearch(searchText) {

                    const searchLower = searchText.trim().toLowerCase();
                    const groupIdFromURL = new URLSearchParams(window.location.search).get("group_id");

                    document.querySelectorAll(".accordion-item").forEach((item, index) => {

                        const rows = item.querySelectorAll("table tbody tr");
                        let hasMatch = false;

                        rows.forEach(row => {
                            if (row.textContent.toLowerCase().includes(searchLower)) {
                                hasMatch = true;
                            }
                        });

                        const collapse = item.querySelector(".accordion-collapse");
                        const thisGroup = item.getAttribute("data-group-id");

                        // CASE 1: URL contains group_id → FORCE this open only
                        if (groupIdFromURL) {
                            if (String(thisGroup) === String(groupIdFromURL)) {
                                collapse.classList.add("show");
                            } else {
                                collapse.classList.remove("show");
                            }
                            return;
                        }

                        // CASE 2: Empty search → default behavior (first open)
                        if (searchLower === "") {
                            if (index === 0) collapse.classList.add("show");
                            else collapse.classList.remove("show");
                            return;
                        }

                        // CASE 3: Search active → only matching will open
                        if (hasMatch) collapse.classList.add("show");
                        else collapse.classList.remove("show");
                    });
                }

                //----------------------------------------------------
                // INITIAL ACCORDION STATE ON PAGE LOAD
                //----------------------------------------------------
                const urlGroupId = new URLSearchParams(window.location.search).get("group_id");

                if (urlGroupId) {
                    document.querySelectorAll(".accordion-item").forEach(item => {
                        const collapse = item.querySelector(".accordion-collapse");
                        const thisGroup = item.getAttribute("data-group-id");

                        if (String(thisGroup) === String(urlGroupId)) {
                            collapse.classList.add("show");
                        } else {
                            collapse.classList.remove("show");
                        }
                    });
                } else {
                    // default open first only
                    const first = document.querySelector(".accordion-item .accordion-collapse");
                    if (first) first.classList.add("show");
                }

                //----------------------------------------------------
                // SEARCH INPUT EVENTS
                //----------------------------------------------------
                ["input", "keyup"].forEach(eventType => {
                    searchInput.addEventListener(eventType, function() {
                        clearBtn.style.display = this.value.trim().length > 0 ? "block" : "none";
                        performSearch(this.value);
                    });
                });

                clearBtn.addEventListener("click", function() {
                    searchInput.value = "";
                    clearBtn.style.display = "none";
                    performSearch("");
                });

                //----------------------------------------------------
                // SORTABLE
                //----------------------------------------------------
                function initSortable() {
                    document.querySelectorAll(".sortable").forEach(tableBody => {
                        Sortable.create(tableBody, {
                            handle: ".cursor-move",
                            animation: 150,
                            onEnd: function() {
                                const order = [];
                                tableBody.querySelectorAll("tr[data-id]").forEach(row =>
                                    order.push(row.dataset.id)
                                );

                                fetch("{{ route('admin.admin-offices.sort') }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": document.querySelector(
                                            'meta[name=csrf-token]').content
                                    },
                                    body: JSON.stringify({
                                        order
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        toastr.success('Group order updated.');
                                    }else{
                                        toastr.error('Group order not updated.');
                                    }
                                });
                            }
                        });
                    });
                }
                initSortable();

                //----------------------------------------------------
                // EDIT BUTTONS
                //----------------------------------------------------
                function initEditButtons() {
                    document.querySelectorAll('.editOfficeBtn').forEach(btn => {

                        btn.addEventListener('click', function() {

                            const id = this.dataset.id;

                            document.getElementById('editOfficeForm').action =
                                "{{ url('/admin/admin-offices') }}/" + id;

                            document.getElementById('editOfficeTitle').value = this.dataset.title;
                            document.getElementById('editOfficeStatus').value = this.dataset.status;
                            document.getElementById('editOfficePosition').value = this.dataset.position;

                            document.getElementById('editGroupSelect').value = this.dataset.group;
                            $("#editGroupSelect").trigger("change");

                            const banner = this.dataset.banner;
                            const existing = document.getElementById('editBannerExisting');

                            if (banner) {
                                existing.src = "/storage/" + banner;
                                existing.style.display = "block";
                            } else {
                                existing.style.display = "none";
                            }

                            document.getElementById('editBannerPreview').style.display = "none";
                        });
                    });
                }
                initEditButtons();

                //----------------------------------------------------
                // IMAGE PREVIEW - CREATE
                //----------------------------------------------------
                document.getElementById('createBannerInput')?.addEventListener('change', e => {
                    const file = e.target.files[0];
                    const preview = document.getElementById('createBannerPreview');
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = ev => {
                            preview.src = ev.target.result;
                            preview.style.display = "block";
                        };
                        reader.readAsDataURL(file);
                    }
                });

                //----------------------------------------------------
                // IMAGE PREVIEW - EDIT
                //----------------------------------------------------
                document.getElementById('editBannerInput')?.addEventListener('change', e => {
                    const file = e.target.files[0];
                    const preview = document.getElementById('editBannerPreview');
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = ev => {
                            preview.src = ev.target.result;
                            preview.style.display = "block";
                        };
                        reader.readAsDataURL(file);
                    }
                });

                //----------------------------------------------------
                // CREATE MODAL → AUTO SELECT GROUP
                //----------------------------------------------------
                document.getElementById('createOfficeModal').addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const groupId = button?.getAttribute('data-group');
                    if (groupId) {
                        document.getElementById('createGroupSelect').value = groupId;
                        $("#createGroupSelect").trigger("change");
                    }
                });

                // run initial search-based behavior
                performSearch("");
            });
        </script>
    @endpush





</x-admin-app-layout>
