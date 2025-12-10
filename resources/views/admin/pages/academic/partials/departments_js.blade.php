<script>
    /* ------------------------------------------------------------
    UTILITIES
------------------------------------------------------------ */
    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .substring(0, 180);
    }

    function showToast(type, message) {
        toastr[type](message);
    }

    const CSRF = "{{ csrf_token() }}";

    /* ------------------------------------------------------------
        1) AUTO SLUG LOGIC
    ------------------------------------------------------------ */

    // CREATE Department
    $(document).on("keyup", ".slug-source", function() {
        const target = $(this).closest(".modal-body").find(".slug-target");
        target.val(slugify($(this).val()));
    });

    // EDIT Department
    $(document).on("keyup", ".slug-source-edit", function() {
        const target = $(this).closest(".modal-body").find(".slug-target-edit");
        target.val(slugify($(this).val()));
    });


    /* ------------------------------------------------------------
        2) LOAD RIGHT PANEL VIA AJAX
    ------------------------------------------------------------ */
    function loadDepartmentPanel(deptId, push = true) {
        $("#rightPanelLoader").removeClass("d-none");
        $("#rightPanelContent").addClass("d-none");

        $.get("{{ route('admin.academic.staff.index') }}", {
            site_id: $("#activeSiteId").val(),
            department_id: deptId,
            ajax: 1
        }, function(response) {

            $("#rightPanelContent").html(response.html);
            $("#rightPanelLoader").addClass("d-none");
            $("#rightPanelContent").removeClass("d-none");

            initSortables(); // reinitialize after AJAX load

            if (push) {
                const url = new URL(window.location.href);
                url.searchParams.set("department_id", deptId);
                history.pushState({
                    dept_id: deptId
                }, "", url);
            }
        });
    }

    // Handle browser back/forward
    window.addEventListener("popstate", function(event) {
        if (event.state?.dept_id) {
            loadDepartmentPanel(event.state.dept_id, false);
        }
    });


    /* ------------------------------------------------------------
        3) LEFT COLUMN — CLICK DEPARTMENT
    ------------------------------------------------------------ */
    $(document).on("click", ".dept-row", function() {
        const deptId = $(this).data("id");

        $(".dept-row").removeClass("active");
        $(this).addClass("active");

        loadDepartmentPanel(deptId, true);
    });


    /* ------------------------------------------------------------
        4) SWEETALERT DELETE (unified)
    ------------------------------------------------------------ */
    $(document).on("click", ".delete", function(e) {
        e.preventDefault();
        const url = $(this).attr("href");

        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it",
            cancelButtonText: "Cancel",
            reverseButtons: true
        }).then((result) => {
            if (!result.isConfirmed) return;

            fetch(url, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": CSRF,
                        "Accept": "application/json"
                    }
                })
                .then(res => res.json())
                .then(json => {
                    if (json.success) {
                        Swal.fire("Deleted!", json.message, "success").then(() => {
                            location.reload(); // safe for now; can also re-AJAX
                        });
                    } else {
                        Swal.fire("Error", json.message || "Delete failed", "error");
                    }
                })
                .catch(() => Swal.fire("Error", "Delete failed", "error"));
        });
    });


    /* ------------------------------------------------------------
        5) MODAL POPULATION — EDIT DEPARTMENT
    ------------------------------------------------------------ */
    $(document).on("click", ".editDeptBtn", function() {
        const id = $(this).data("id");
        const title = $(this).data("title");
        const slug = $(this).data("slug");
        const status = $(this).data("status");
        const position = $(this).data("position");
        const description = $(this).data("description");

        const form = $("#editDepartmentForm");
        form.attr("action", "/admin/academic/departments/" + id);

        $("#deptEditTitle").val(title);
        $("#deptEditSlug").val(slug);
        $("#deptEditStatus").val(status);
        $("#deptEditPosition").val(position ?? 0);
        $("#deptEditDescription").val(description ?? "");

        $("#editDepartmentModal").modal("show");
    });


    /* ------------------------------------------------------------
        6) MODAL POPULATION — EDIT STAFF GROUP
    ------------------------------------------------------------ */
    $(document).on("click", ".editStaffGroupBtn", function() {
        const id = $(this).data("id");
        const title = $(this).data("title");
        const status = $(this).data("status");

        const form = $("#editStaffGroupForm");
        form.attr("action", "/admin/academic/staff-groups/" + id);

        $("#editStaffGroupTitle").val(title);
        $("#editStaffGroupStatus").val(status);

        $("#editStaffGroupModal").modal("show");
    });


    /* ------------------------------------------------------------
        7) MODAL POPULATION — EDIT STAFF MEMBER
    ------------------------------------------------------------ */
    $(document).on("click", ".editStaffMemberBtn", function() {

        const member = $(this).data("json");
        const form = $("#editStaffMemberForm");
        form.attr("action", "/admin/academic/staff-members/" + member.id);

        $("#editMemberName").val(member.name);
        $("#editMemberDesignation").val(member.designation);
        $("#editMemberEmail").val(member.email);
        $("#editMemberPhone").val(member.phone);
        $("#editMemberStatus").val(member.status);

        // Load existing image
        if (member.image_url) {
            $("#editMemberImagePicker").attr("data-image", member.image_url);
        }

        // Load dynamic links
        $("#editStaffLinksRepeater").html("");
        if (member.links?.length) {
            member.links.forEach((lnk, index) => {
                $("#editStaffLinksRepeater").append(`
                <div class="row g-3 link-row mb-2">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Icon</label>
                        <x-icon-picker name="links[${index}][icon]" :value="${lnk.icon}" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">URL</label>
                        <input type="text" class="form-control" name="links[${index}][url]"
                               value="${lnk.url}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm removeLinkBtn w-100">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            `);
            });
        }

        $("#editStaffMemberModal").modal("show");
    });


    /* ------------------------------------------------------------
        8) STAFF LINKS REPEATER (CREATE + EDIT)
    ------------------------------------------------------------ */

    let linkIndex = 1;

    // ADD link (create modal)
    $("#addStaffLinkBtn").on("click", function() {
        $("#staffLinksRepeater").append(`
        <div class="row g-3 link-row mb-2">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Icon</label>
                <x-icon-picker name="links[${linkIndex}][icon]" />
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">URL</label>
                <input type="text" name="links[${linkIndex}][url]" class="form-control"
                       placeholder="https://example.com">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm removeLinkBtn w-100">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    `);
        linkIndex++;
    });

    // REMOVE link row
    $(document).on("click", ".removeLinkBtn", function() {
        $(this).closest(".link-row").remove();
    });


    /* ------------------------------------------------------------
        9) SORTABLE (Departments, Groups, Members)
    ------------------------------------------------------------ */

    function initSortables() {

        // DEPARTMENTS
        $(".departments-sortable").sortable({
            handle: ".dept-sort-handle",
            update: function() {
                const order = [];
                $(".dept-row").each(function() {
                    order.push($(this).data("id"));
                });

                $.post("{{ route('admin.academic.departments.sort', ':site') }}"
                    .replace(":site", $("#activeSiteId").val()), {
                        order,
                        _token: CSRF
                    },
                    function(res) {
                        showToast("success", res.message);
                    });
            }
        });

        // STAFF GROUPS
        $(".staff-groups-sortable").sortable({
            handle: ".group-sort-handle",
            update: function() {
                const deptId = $("#currentDepartmentId").val();
                const order = [];
                $(".staff-group-row").each(function() {
                    order.push($(this).data("id"));
                });

                $.post(`/admin/academic/departments/${deptId}/groups/sort`, {
                    order,
                    _token: CSRF
                }, function(res) {
                    showToast("success", res.message);
                });
            }
        });

        // STAFF MEMBERS
        $(".staff-members-sortable").sortable({
            handle: ".member-sort-handle",
            update: function() {
                const groupId = $("#currentStaffGroupId").val();
                const order = [];
                $(".member-row").each(function() {
                    order.push($(this).data("id"));
                });

                $.post(`/admin/academic/staff-groups/${groupId}/members/sort`, {
                    order,
                    _token: CSRF
                }, function(res) {
                    showToast("success", res.message);
                });
            }
        });
    }

    initSortables();


    /* ------------------------------------------------------------
        END OF SCRIPT
    ------------------------------------------------------------ */
</script>
