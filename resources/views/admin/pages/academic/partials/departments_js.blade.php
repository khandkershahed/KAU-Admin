{{-- <script>
    /* =====================================================
       TOASTR OPTIONS (if not already set globally)
    ===================================================== */
    if (window.toastr) {
        toastr.options = {
            positionClass: "toast-top-right",
            closeButton: true,
            progressBar: true,
            newestOnTop: true,
        };
    }

    const CSRF = "{{ csrf_token() }}";

    /* =====================================================
       SLUGIFY
    ===================================================== */
    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .substring(0, 180);
    }

    function showToast(type, message) {
        if (window.toastr) {
            toastr[type](message);
        } else {
            alert(message);
        }
    }

    /* =====================================================
       1) AUTO SLUG LOGIC
    ===================================================== */
    $(document).on("keyup", ".slug-source", function() {
        const target = $(this).closest(".modal-body").find(".slug-target");
        target.val(slugify($(this).val()));
    });

    $(document).on("keyup", ".slug-source-edit", function() {
        const target = $(this).closest(".modal-body").find(".slug-target-edit");
        target.val(slugify($(this).val()));
    });

    /* =====================================================
       2) LOAD RIGHT PANEL VIA AJAX
    ===================================================== */
    function loadDepartmentPanel(deptId, push = true) {
        if (!deptId) return;

        $("#currentDepartmentId").val(deptId);
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

            initSortables();
            initDynamicButtons();

            if (push) {
                const url = new URL(window.location.href);
                url.searchParams.set("department_id", deptId);
                history.pushState({
                    dept_id: deptId
                }, "", url);
            }
        });
    }

    window.addEventListener("popstate", function(event) {
        if (event.state && event.state.dept_id) {
            loadDepartmentPanel(event.state.dept_id, false);
        }
    });

    /* =====================================================
       3) LEFT COLUMN — CLICK DEPARTMENT
    ===================================================== */
    $(document).on("click", ".dept-click-area", function() {
        const li = $(this).closest(".department-item");
        const deptId = li.data("id");

        $(".department-item").removeClass("active");
        li.addClass("active");

        loadDepartmentPanel(deptId, true);
    });

    /* =====================================================
       4) SWEETALERT DELETE (unified)
    ===================================================== */
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
                        Swal.fire({
                            title: "Deleted!",
                            text: json.message || "Deleted successfully.",
                            icon: "success",
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            // For now: full reload (delete changes structure)
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error", json.message || "Delete failed", "error");
                    }
                })
                .catch(() => Swal.fire("Error", "Delete failed", "error"));
        });
    });

    /* =====================================================
       5) EDIT DEPARTMENT MODAL POPULATION
    ===================================================== */
    $(document).on("click", ".editDepartmentBtn", function() {
        const id = $(this).data("id");
        const title = $(this).data("title");
        const shortCode = $(this).data("short-code");
        const slug = $(this).data("slug");
        const status = $(this).data("status");
        const position = $(this).data("position") ?? 0;
        const description = $(this).data("description") || "";

        const form = $("#editDepartmentForm");
        form.attr("action", "/admin/academic/departments/" + id);

        $("#deptEditTitle").val(title);
        $("#deptEditShortCode").val(shortCode);
        $("#deptEditSlug").val(slug);
        $("#deptEditStatus").val(status);
        $("#deptEditPosition").val(position);
        $("#deptEditDescription").val(description);

        $("#editDepartmentModal").modal("show");
    });

    /* =====================================================
       6) STATUS TOGGLE — DEPARTMENT
    ===================================================== */
    $(document).on("change", ".toggleDepartmentStatus", function() {
        const id = $(this).data("id");

        fetch("{{ route('admin.academic.departments.toggle-status', ':id') }}".replace(':id', id), {
                method: "PATCH",
                headers: {
                    "X-CSRF-TOKEN": CSRF,
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(json => {
                if (json.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: json.message || 'Department status updated.',
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false
                    });
                } else {
                    showToast("error", json.message || "Status update failed");
                }
            })
            .catch(() => showToast("error", "Status update failed"));
    });

    /* =====================================================
       7) STAFF GROUP & MEMBER MODALS (ACTIONS)
    ===================================================== */
    function initDynamicButtons() {
        // CREATE STAFF GROUP
        $(document).off("click", ".createStaffGroupBtn").on("click", ".createStaffGroupBtn", function() {
            const deptId = $(this).data("department-id");
            $("#createStaffGroupDepartmentId").val(deptId);
            $("#createStaffGroupForm").attr("action", "/admin/academic/departments/" + deptId + "/groups");
            $("#createStaffGroupModal").modal("show");
        });

        // CREATE STAFF MEMBER
        $(document).off("click", ".createStaffMemberBtn").on("click", ".createStaffMemberBtn", function() {
            const groupId = $(this).data("group-id");
            $("#createMemberGroupId").val(groupId);
            $("#createStaffMemberForm").attr("action", "/admin/academic/staff-groups/" + groupId + "/members");
            $("#createStaffMemberModal").modal("show");
        });

        // EDIT STAFF GROUP
        $(document).off("click", ".editStaffGroupBtn").on("click", ".editStaffGroupBtn", function() {
            const id = $(this).data("id");
            const title = $(this).data("title");
            const status = $(this).data("status");

            const form = $("#editStaffGroupForm");
            form.attr("action", "/admin/academic/staff-groups/" + id);

            $("#editStaffGroupTitle").val(title);
            $("#editStaffGroupStatus").val(status);

            $("#editStaffGroupModal").modal("show");
        });

        // EDIT STAFF MEMBER
        $(document).off("click", ".editStaffMemberBtn").on("click", ".editStaffMemberBtn", function() {
            const member = $(this).data("member");

            const form = $("#editStaffMemberForm");
            form.attr("action", "/admin/academic/staff-members/" + member.id);

            $("#editMemberName").val(member.name);
            $("#editMemberDesignation").val(member.designation);
            $("#editMemberEmail").val(member.email);
            $("#editMemberPhone").val(member.phone);
            $("#editMemberStatus").val(member.status);
            document.getElementById('editMemberMobile').value = (data.mobile || '');
            document.getElementById('editMemberAddress').value = (data.address || '');
            document.getElementById('editMemberResearchInterest').value = (data.research_interest || '');
            document.getElementById('editMemberBio').value = (data.bio || '');
            document.getElementById('editMemberEducation').value = (data.education || '');
            document.getElementById('editMemberExperience').value = (data.experience || '');
            document.getElementById('editMemberScholarship').value = (data.scholarship || '');
            document.getElementById('editMemberResearch').value = (data.research || '');
            document.getElementById('editMemberTeaching').value = (data.teaching || '');

            // Clear existing links
            $("#editStaffLinksRepeater").html("");
            editLinkIndex = 0;

            if (member.links && member.links.length) {
                member.links.forEach(function(link) {
                    addStaffLinkRow("#editStaffLinksRepeater", "#staffLinkTemplateEdit", true, link);
                });
            }

            $("#editStaffMemberModal").modal("show");
        });
    }

    /* =====================================================
       8) STAFF LINKS REPEATER (CREATE + EDIT)
       (uses hidden templates from member_modals)
    ===================================================== */
    let linkIndex = 1;
    let editLinkIndex = 0;

    function addStaffLinkRow(containerSelector, templateSelector, isEdit = false, linkData = null) {
        const index = isEdit ? editLinkIndex : linkIndex;
        const html = $(templateSelector).html().replace(/__INDEX__/g, index);
        const $row = $(html);

        $(containerSelector).append($row);

        if (typeof initIconPicker === "function") {
            initIconPicker($row[0]);
        }

        if (linkData) {
            $row.find("input[name='links[" + index + "][url]']").val(linkData.url || "");
            $row.find(".icon-picker-input").val(linkData.icon || "");
            $row.find(".icon-picker-toggle i").attr("class", linkData.icon || "fa fa-icons");
        }

        if (isEdit) {
            editLinkIndex++;
        } else {
            linkIndex++;
        }
    }

    $("#addStaffLinkBtn").on("click", function() {
        addStaffLinkRow("#staffLinksRepeater", "#staffLinkTemplate", false, null);
    });

    $("#addStaffLinkBtnEdit").on("click", function() {
        addStaffLinkRow("#editStaffLinksRepeater", "#staffLinkTemplateEdit", true, null);
    });

    $(document).on("click", ".removeLinkBtn", function() {
        $(this).closest(".link-row").remove();
    });

    /* =====================================================
       9) SORTABLES (USING SortableJS)
    ===================================================== */

    function initSortables() {
        /* ---- DEPARTMENTS (LEFT) ---- */
        const deptList = document.getElementById('departmentsSortable');
        if (deptList && typeof Sortable !== "undefined") {
            if (deptList.__sortableInstance) {
                deptList.__sortableInstance.destroy();
            }
            deptList.__sortableInstance = new Sortable(deptList, {
                handle: '.dept-sort-handle',
                animation: 150,
                onEnd: function() {
                    let order = [];
                    $("#departmentsSortable .department-item").each(function() {
                        order.push($(this).data("id"));
                    });

                    $.post("{{ route('admin.academic.departments.sort', ':site') }}"
                        .replace(':site', $("#activeSiteId").val()), {
                            order: order,
                            _token: CSRF
                        }).done(() => {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Department order updated.',
                            icon: 'success',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    }).fail(() => {
                        showToast("error", "Failed to update department order");
                    });
                }
            });
        }

        /* ---- STAFF GROUPS (ACCORDION) ---- */
        const groupContainer = document.getElementById('departmentStaffAccordion');
        if (groupContainer && typeof Sortable !== "undefined") {
            if (groupContainer.__sortableInstance) {
                groupContainer.__sortableInstance.destroy();
            }
            groupContainer.__sortableInstance = new Sortable(groupContainer, {
                handle: '.group-sort-handle',
                animation: 150,
                onEnd: function() {
                    let order = [];
                    $("#departmentStaffAccordion .staff-group-row").each(function() {
                        order.push($(this).data("id"));
                    });

                    const deptId = $("#currentDepartmentId").val();

                    $.post("/admin/academic/departments/" + deptId + "/groups/sort", {
                        order: order,
                        _token: CSRF
                    }).done(() => {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Staff group order updated.',
                            icon: 'success',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    }).fail(() => {
                        showToast("error", "Failed to update staff group order");
                    });
                }
            });
        }

        /* ---- STAFF MEMBERS (TABLE ROWS) ---- */
        $(".staff-members-sortable").each(function() {
            const tbody = this;
            if (typeof Sortable === "undefined") return;

            if (tbody.__sortableInstance) {
                tbody.__sortableInstance.destroy();
            }

            const groupId = $(tbody).data("group-id");

            tbody.__sortableInstance = new Sortable(tbody, {
                handle: ".member-sort", // cells with member-sort used as handle
                animation: 150,
                onEnd: function() {
                    let order = [];
                    $(tbody).find(".member-row").each(function() {
                        order.push($(this).data("id"));
                    });

                    $.post("/admin/academic/staff-groups/" + groupId + "/members/sort", {
                        order: order,
                        _token: CSRF
                    }).done(() => {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Staff member order updated.',
                            icon: 'success',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    }).fail(() => {
                        showToast("error", "Failed to update staff member order");
                    });
                }
            });
        });
    }

    /* =====================================================
       10) INITIALIZE ON PAGE LOAD
    ===================================================== */
    $(document).ready(function() {
        initSortables();
        initDynamicButtons();

        // Pre-load department from URL if present
        const urlParams = new URLSearchParams(window.location.search);
        const deptIdFromUrl = urlParams.get("department_id");
        if (deptIdFromUrl) {
            const li = $(".department-item[data-id='" + deptIdFromUrl + "']");
            if (li.length) {
                li.addClass("active");
                loadDepartmentPanel(deptIdFromUrl, false);
            }
        }

        // Fill site id when opening "Add Department"
        $("#openCreateDepartmentModalBtn").on("click", function() {
            $("#createDeptSiteId").val($("#activeSiteId").val());
        });
    });
</script> --}}


{{-- <script>
    /* =====================================================
       TOASTR OPTIONS (if not already set globally)
    ===================================================== */
    if (window.toastr) {
        toastr.options = {
            positionClass: "toast-top-right",
            closeButton: true,
            progressBar: true,
            newestOnTop: true,
        };
    }

    const CSRF = "{{ csrf_token() }}";

    /* =====================================================
       SLUGIFY
    ===================================================== */
    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .substring(0, 180);
    }

    function showToast(type, message) {
        if (window.toastr) {
            toastr[type](message);
        } else {
            alert(message);
        }
    }

    /* =====================================================
       1) AUTO SLUG LOGIC
    ===================================================== */
    $(document).on("keyup", ".slug-source", function() {
        const target = $(this).closest(".modal-body").find(".slug-target");
        target.val(slugify($(this).val()));
    });

    $(document).on("keyup", ".slug-source-edit", function() {
        const target = $(this).closest(".modal-body").find(".slug-target-edit");
        target.val(slugify($(this).val()));
    });

    /* =====================================================
       2) LOAD RIGHT PANEL VIA AJAX
    ===================================================== */
    function loadDepartmentPanel(deptId, push = true) {
        if (!deptId) return;

        $("#currentDepartmentId").val(deptId);
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

            initSortables();
            initDynamicButtons();

            if (push) {
                const url = new URL(window.location.href);
                url.searchParams.set("department_id", deptId);
                history.pushState({
                    dept_id: deptId
                }, "", url);
            }
        });
    }

    window.addEventListener("popstate", function(event) {
        if (event.state && event.state.dept_id) {
            loadDepartmentPanel(event.state.dept_id, false);
        }
    });

    /* =====================================================
       3) LEFT COLUMN — CLICK DEPARTMENT
    ===================================================== */
    $(document).on("click", ".dept-click-area", function() {
        const li = $(this).closest(".department-item");
        const deptId = li.data("id");

        $(".department-item").removeClass("active");
        li.addClass("active");

        loadDepartmentPanel(deptId, true);
    });

    /* =====================================================
       4) SWEETALERT DELETE (unified)
    ===================================================== */
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
                        Swal.fire({
                            title: "Deleted!",
                            text: json.message || "Deleted successfully.",
                            icon: "success",
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            // If Publications modal is open, refresh its list
                            const pubMemberId = $("#pubMemberId").val();
                            if (pubMemberId) {
                                openPublicationsModal(pubMemberId);
                                return;
                            }

                            // For now: full reload (delete changes structure)
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error", json.message || "Delete failed", "error");
                    }
                })
                .catch(() => Swal.fire("Error", "Delete failed", "error"));
        });
    });

    /* =====================================================
       5) EDIT DEPARTMENT MODAL POPULATION
    ===================================================== */
    $(document).on("click", ".editDepartmentBtn", function() {
        const id = $(this).data("id");
        const title = $(this).data("title");
        const shortCode = $(this).data("short-code");
        const slug = $(this).data("slug");
        const status = $(this).data("status");
        const position = $(this).data("position") ?? 0;
        const description = $(this).data("description") || "";

        const form = $("#editDepartmentForm");
        form.attr("action", "/admin/academic/departments/" + id);

        $("#deptEditTitle").val(title);
        $("#deptEditShortCode").val(shortCode);
        $("#deptEditSlug").val(slug);
        $("#deptEditStatus").val(status);
        $("#deptEditPosition").val(position);
        $("#deptEditDescription").val(description);

        $("#editDepartmentModal").modal("show");
    });

    /* =====================================================
       6) STATUS TOGGLE — DEPARTMENT
    ===================================================== */
    $(document).on("change", ".toggleDepartmentStatus", function() {
        const id = $(this).data("id");

        fetch("{{ route('admin.academic.departments.toggle-status', ':id') }}".replace(':id', id), {
                method: "PATCH",
                headers: {
                    "X-CSRF-TOKEN": CSRF,
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(json => {
                if (json.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: json.message || 'Department status updated.',
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false
                    });
                } else {
                    showToast("error", json.message || "Status update failed");
                }
            })
            .catch(() => showToast("error", "Status update failed"));
    });

    /* =====================================================
       7) STAFF GROUP & MEMBER MODALS (ACTIONS)
    ===================================================== */
    function initDynamicButtons() {

        // CREATE STAFF GROUP
        $(document).off("click", ".createStaffGroupBtn").on("click", ".createStaffGroupBtn", function() {
            const deptId = $(this).data("department-id");
            $("#createStaffGroupDepartmentId").val(deptId);
            $("#createStaffGroupForm").attr("action", "/admin/academic/departments/" + deptId + "/groups");
            $("#createStaffGroupModal").modal("show");
        });

        // CREATE STAFF MEMBER
        $(document).off("click", ".createStaffMemberBtn").on("click", ".createStaffMemberBtn", function() {
            const groupId = $(this).data("group-id");
            $("#createMemberGroupId").val(groupId);
            $("#createStaffMemberForm").attr("action", "/admin/academic/staff-groups/" + groupId + "/members");
            $("#createStaffMemberModal").modal("show");
        });

        // EDIT STAFF GROUP
        $(document).off("click", ".editStaffGroupBtn").on("click", ".editStaffGroupBtn", function() {
            const id = $(this).data("id");
            const title = $(this).data("title");
            const status = $(this).data("status");

            const form = $("#editStaffGroupForm");
            form.attr("action", "/admin/academic/staff-groups/" + id);

            $("#editStaffGroupTitle").val(title);
            $("#editStaffGroupStatus").val(status);

            $("#editStaffGroupModal").modal("show");
        });

        // EDIT STAFF MEMBER
        $(document).off("click", ".editStaffMemberBtn").on("click", ".editStaffMemberBtn", function() {
            const member = $(this).data("member");

            const form = $("#editStaffMemberForm");
            form.attr("action", "/admin/academic/staff-members/" + member.id);

            $("#editMemberName").val(member.name || '');
            $("#editMemberDesignation").val(member.designation || '');
            $("#editMemberEmail").val(member.email || '');
            $("#editMemberPhone").val(member.phone || '');
            $("#editMemberStatus").val(member.status || 'published');

            // EXTRA FIELDS (FIXED: use member, not data)
            if (document.getElementById('editMemberMobile')) document.getElementById('editMemberMobile').value =
                (member.mobile || '');
            if (document.getElementById('editMemberAddress')) document.getElementById('editMemberAddress')
                .value = (member.address || '');
            if (document.getElementById('editMemberResearchInterest')) document.getElementById(
                'editMemberResearchInterest').value = (member.research_interest || '');
            if (document.getElementById('editMemberBio')) document.getElementById('editMemberBio').value = (
                member.bio || '');
            if (document.getElementById('editMemberEducation')) document.getElementById('editMemberEducation')
                .value = (member.education || '');
            if (document.getElementById('editMemberExperience')) document.getElementById('editMemberExperience')
                .value = (member.experience || '');
            if (document.getElementById('editMemberScholarship')) document.getElementById(
                'editMemberScholarship').value = (member.scholarship || '');
            if (document.getElementById('editMemberResearch')) document.getElementById('editMemberResearch')
                .value = (member.research || '');
            if (document.getElementById('editMemberTeaching')) document.getElementById('editMemberTeaching')
                .value = (member.teaching || '');

            // Clear existing links
            $("#editStaffLinksRepeater").html("");
            editLinkIndex = 0;

            if (member.links && member.links.length) {
                member.links.forEach(function(link) {
                    addStaffLinkRow("#editStaffLinksRepeater", "#staffLinkTemplateEdit", true, link);
                });
            }

            $("#editStaffMemberModal").modal("show");
        });
    }

    /* =====================================================
       8) STAFF LINKS REPEATER (CREATE + EDIT)
       (uses hidden templates from member_modals)
    ===================================================== */
    let linkIndex = 1;
    let editLinkIndex = 0;

    function addStaffLinkRow(containerSelector, templateSelector, isEdit = false, linkData = null) {
        const index = isEdit ? editLinkIndex : linkIndex;
        const html = $(templateSelector).html().replace(/__INDEX__/g, index);
        const $row = $(html);

        $(containerSelector).append($row);

        if (typeof initIconPicker === "function") {
            initIconPicker($row[0]);
        }

        if (linkData) {
            $row.find("input[name='links[" + index + "][url]']").val(linkData.url || "");
            $row.find(".icon-picker-input").val(linkData.icon || "");
            $row.find(".icon-picker-toggle i").attr("class", linkData.icon || "fa fa-icons");
        }

        if (isEdit) {
            editLinkIndex++;
        } else {
            linkIndex++;
        }
    }

    $("#addStaffLinkBtn").on("click", function() {
        addStaffLinkRow("#staffLinksRepeater", "#staffLinkTemplate", false, null);
    });

    $("#addStaffLinkBtnEdit").on("click", function() {
        addStaffLinkRow("#editStaffLinksRepeater", "#staffLinkTemplateEdit", true, null);
    });

    $(document).on("click", ".removeLinkBtn", function() {
        $(this).closest(".link-row").remove();
    });

    /* =====================================================
       9) PUBLICATIONS MODAL (AJAX LOAD + CREATE + EDIT + SORT)
    ===================================================== */

    window.openPublicationsModal = function(memberId) {
        if (!memberId) return;

        $("#pubMemberId").val(memberId);

        const loader = document.getElementById('publicationsLoader');
        const content = document.getElementById('publicationsContent');
        if (loader) loader.classList.remove('d-none');
        if (content) content.innerHTML = '';

        // Set create form action
        const createForm = document.getElementById('createPublicationForm');
        if (createForm) {
            createForm.action = "/admin/academic/staff-members/" + memberId + "/publications";
            createForm.reset();
        }

        fetch("/admin/academic/staff-members/" + memberId + "/publications/list", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(json => {
                if (content) content.innerHTML = json.html || '';
                if (loader) loader.classList.add('d-none');

                // Hook edit buttons inside loaded HTML
                $("#publicationsContent").find(".editPublicationBtn").each(function() {
                    $(this).off("click").on("click", function() {
                        const pubId = $(this).data("id");

                        const editForm = document.getElementById('editPublicationForm');
                        if (editForm) {
                            editForm.action = "/admin/academic/publications/" + pubId;
                        }

                        $("#editPublicationId").val(pubId);
                        $("#editPubTitle").val($(this).data("title") || '');
                        $("#editPubType").val($(this).data("type") || '');
                        $("#editPubJournal").val($(this).data("journal") || '');
                        $("#editPubPublisher").val($(this).data("publisher") || '');
                        $("#editPubYear").val($(this).data("year") || '');
                        $("#editPubDoi").val($(this).data("doi") || '');
                        $("#editPubUrl").val($(this).data("url") || '');
                    });
                });

                // Sort publications (SortableJS)
                const pubList = document.getElementById('publicationsSortable');
                if (pubList && typeof Sortable !== "undefined") {
                    if (pubList.__sortableInstance) {
                        pubList.__sortableInstance.destroy();
                    }

                    pubList.__sortableInstance = new Sortable(pubList, {
                        handle: '.pub-sort-handle',
                        animation: 150,
                        onEnd: function() {
                            let order = [];
                            $("#publicationsSortable .publication-item").each(function() {
                                order.push($(this).data("id"));
                            });

                            fetch("/admin/academic/staff-members/" + memberId +
                                    "/publications/sort", {
                                        method: "POST",
                                        headers: {
                                            "X-CSRF-TOKEN": CSRF,
                                            "Accept": "application/json",
                                            "Content-Type": "application/json"
                                        },
                                        body: JSON.stringify({
                                            order: order
                                        })
                                    })
                                .then(res => res.json())
                                .then(json2 => {
                                    if (json2.success) {
                                        showToast("success", json2.message ||
                                            "Publication order updated.");
                                    } else {
                                        showToast("error", json2.message ||
                                            "Failed to update publication order.");
                                    }
                                })
                                .catch(() => showToast("error",
                                    "Failed to update publication order."));
                        }
                    });
                }

                $("#publicationsModal").modal("show");
            })
            .catch(() => {
                if (loader) loader.classList.add('d-none');
                if (content) content.innerHTML =
                    `<div class="alert alert-danger">Failed to load publications.</div>`;
                $("#publicationsModal").modal("show");
            });
    };

    /* =====================================================
       10) SORTABLES (USING SortableJS)
    ===================================================== */

    function initSortables() {
        /* ---- DEPARTMENTS (LEFT) ---- */
        const deptList = document.getElementById('departmentsSortable');
        if (deptList && typeof Sortable !== "undefined") {
            if (deptList.__sortableInstance) {
                deptList.__sortableInstance.destroy();
            }
            deptList.__sortableInstance = new Sortable(deptList, {
                handle: '.dept-sort-handle',
                animation: 150,
                onEnd: function() {
                    let order = [];
                    $("#departmentsSortable .department-item").each(function() {
                        order.push($(this).data("id"));
                    });

                    $.post("{{ route('admin.academic.departments.sort', ':site') }}"
                        .replace(':site', $("#activeSiteId").val()), {
                            order: order,
                            _token: CSRF
                        }).done(() => {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Department order updated.',
                            icon: 'success',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    }).fail(() => {
                        showToast("error", "Failed to update department order");
                    });
                }
            });
        }

        /* ---- STAFF GROUPS (ACCORDION) ---- */
        const groupContainer = document.getElementById('departmentStaffAccordion');
        if (groupContainer && typeof Sortable !== "undefined") {
            if (groupContainer.__sortableInstance) {
                groupContainer.__sortableInstance.destroy();
            }
            groupContainer.__sortableInstance = new Sortable(groupContainer, {
                handle: '.group-sort-handle',
                animation: 150,
                onEnd: function() {
                    let order = [];
                    $("#departmentStaffAccordion .staff-group-row").each(function() {
                        order.push($(this).data("id"));
                    });

                    const deptId = $("#currentDepartmentId").val();

                    $.post("/admin/academic/departments/" + deptId + "/groups/sort", {
                        order: order,
                        _token: CSRF
                    }).done(() => {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Staff group order updated.',
                            icon: 'success',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    }).fail(() => {
                        showToast("error", "Failed to update staff group order");
                    });
                }
            });
        }

        /* ---- STAFF MEMBERS (TABLE ROWS) ---- */
        $(".staff-members-sortable").each(function() {
            const tbody = this;
            if (typeof Sortable === "undefined") return;

            if (tbody.__sortableInstance) {
                tbody.__sortableInstance.destroy();
            }

            const groupId = $(tbody).data("group-id");

            tbody.__sortableInstance = new Sortable(tbody, {
                handle: ".member-sort",
                animation: 150,
                onEnd: function() {
                    let order = [];
                    $(tbody).find(".member-row").each(function() {
                        order.push($(this).data("id"));
                    });

                    $.post("/admin/academic/staff-groups/" + groupId + "/members/sort", {
                        order: order,
                        _token: CSRF
                    }).done(() => {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Staff member order updated.',
                            icon: 'success',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    }).fail(() => {
                        showToast("error", "Failed to update staff member order");
                    });
                }
            });
        });
    }

    /* =====================================================
       11) INITIALIZE ON PAGE LOAD
    ===================================================== */
    $(document).ready(function() {
        initSortables();
        initDynamicButtons();

        // Pre-load department from URL if present
        const urlParams = new URLSearchParams(window.location.search);
        const deptIdFromUrl = urlParams.get("department_id");
        if (deptIdFromUrl) {
            const li = $(".department-item[data-id='" + deptIdFromUrl + "']");
            if (li.length) {
                li.addClass("active");
                loadDepartmentPanel(deptIdFromUrl, false);
            }
        }

        // Fill site id when opening "Add Department"
        $("#openCreateDepartmentModalBtn").on("click", function() {
            $("#createDeptSiteId").val($("#activeSiteId").val());
        });
    });
</script> --}}

{{-- resources/views/admin/pages/academic/partials/departments_js.blade.php --}}

<script>
    // Blade injections MUST be outside @verbatim
    window.__ACADEMIC = window.__ACADEMIC || {};
    window.__ACADEMIC.CSRF = @json(csrf_token());
    window.__ACADEMIC.STAFF_INDEX_URL = @json(route('admin.academic.staff.index'));
    window.__ACADEMIC.DEPARTMENT_SORT_URL = @json(route('admin.academic.departments.sort', ':site'));
    window.__ACADEMIC.DEPARTMENT_TOGGLE_URL = @json(route('admin.academic.departments.toggle-status', ':id'));
</script>

@verbatim
    <script>
        /* =====================================================
                           TOASTR OPTIONS (if not already set globally)
                        ===================================================== */
        if (window.toastr) {
            toastr.options = {
                positionClass: "toast-top-right",
                closeButton: true,
                progressBar: true,
                newestOnTop: true,
            };
        }

        const CSRF = (window.__ACADEMIC && window.__ACADEMIC.CSRF) ? window.__ACADEMIC.CSRF : '';

        /* =====================================================
           SLUGIFY
        ===================================================== */
        function slugify(text) {
            return text.toString().toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '')
                .substring(0, 180);
        }

        function showToast(type, message) {
            if (window.toastr) {
                toastr[type](message);
            } else {
                alert(message);
            }
        }

        /* =====================================================
           TinyMCE: set content helper (IMPORTANT for Edit modal)
           - TinyMCE UI doesn't update when textarea value changes
        ===================================================== */
        function setTinyContent(editorId, html, tryCount = 0) {
            html = html || '';

            if (window.tinymce && typeof tinymce.get === 'function') {
                const ed = tinymce.get(editorId);
                if (ed) {
                    ed.setContent(html);
                    ed.save(); // sync textarea
                    return true;
                }
            }

            const el = document.getElementById(editorId);
            if (el) {
                el.value = html;
            }

            if (tryCount < 10) {
                setTimeout(function() {
                    setTinyContent(editorId, html, tryCount + 1);
                }, 150);
            }

            return false;
        }

        /* =====================================================
           1) AUTO SLUG LOGIC
        ===================================================== */
        $(document).on("keyup", ".slug-source", function() {
            const target = $(this).closest(".modal-body").find(".slug-target");
            target.val(slugify($(this).val()));
        });

        $(document).on("keyup", ".slug-source-edit", function() {
            const target = $(this).closest(".modal-body").find(".slug-target-edit");
            target.val(slugify($(this).val()));
        });

        /* =====================================================
           2) LOAD RIGHT PANEL VIA AJAX
        ===================================================== */
        function loadDepartmentPanel(deptId, push = true) {
            if (!deptId) return;

            $("#currentDepartmentId").val(deptId);
            $("#rightPanelLoader").removeClass("d-none");
            $("#rightPanelContent").addClass("d-none");

            $.get((window.__ACADEMIC && window.__ACADEMIC.STAFF_INDEX_URL) ? window.__ACADEMIC.STAFF_INDEX_URL : '', {
                site_id: $("#activeSiteId").val(),
                department_id: deptId,
                ajax: 1
            }, function(response) {
                $("#rightPanelContent").html(response.html);
                $("#rightPanelLoader").addClass("d-none");
                $("#rightPanelContent").removeClass("d-none");

                initSortables();
                initDynamicButtons();

                if (push) {
                    const url = new URL(window.location.href);
                    url.searchParams.set("department_id", deptId);
                    history.pushState({
                        dept_id: deptId
                    }, "", url);
                }
            });
        }

        window.addEventListener("popstate", function(event) {
            if (event.state && event.state.dept_id) {
                loadDepartmentPanel(event.state.dept_id, false);
            }
        });

        /* =====================================================
           3) LEFT COLUMN — CLICK DEPARTMENT
        ===================================================== */
        $(document).on("click", ".dept-click-area", function() {
            const li = $(this).closest(".department-item");
            const deptId = li.data("id");

            $(".department-item").removeClass("active");
            li.addClass("active");

            loadDepartmentPanel(deptId, true);
        });

        /* =====================================================
           4) SWEETALERT DELETE (unified)
        ===================================================== */
        // $.ajaxSetup({
        //     headers: {
        //         "X-CSRF-TOKEN": "{{ csrf_token() }}"
        //     }
        // });
        // $(document).on("click", ".delete", function(e) {
        //     e.preventDefault();
        //     const url = $(this).attr("href");
        //     const CSRF = "{{ csrf_token() }}";
        //     Swal.fire({
        //         title: "Are you sure?",
        //         text: "This action cannot be undone.",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonText: "Yes, delete it",
        //         cancelButtonText: "Cancel",
        //         reverseButtons: true
        //     }).then((result) => {
        //         if (!result.isConfirmed) return;

        //         fetch(url, {
        //                 method: "DELETE",
        //                 headers: {
        //                     "X-CSRF-TOKEN": CSRF,
        //                     "Accept": "application/json"
        //                 }
        //             })
        //             .then(res => res.json())
        //             .then(json => {
        //                 if (json.success) {
        //                     Swal.fire({
        //                         title: "Deleted!",
        //                         text: json.message || "Deleted successfully.",
        //                         icon: "success",
        //                         timer: 1200,
        //                         showConfirmButton: false
        //                     }).then(() => {
        //                         const pubMemberId = $("#pubMemberId").val();
        //                         if (pubMemberId) {
        //                             openPublicationsModal(pubMemberId);
        //                             return;
        //                         }
        //                         location.reload();
        //                     });
        //                 } else {
        //                     Swal.fire("Error", json.message || "Delete failed", "error");
        //                 }
        //             })
        //             .catch(() => Swal.fire("Error", "Delete failed", "error"));
        //     });
        // });

        /* =====================================================
           5) EDIT DEPARTMENT MODAL POPULATION
        ===================================================== */
        $(document).on("click", ".editDepartmentBtn", function() {
            const id = $(this).data("id");
            const title = $(this).data("title");
            const shortCode = $(this).data("short-code");
            const slug = $(this).data("slug");
            const status = $(this).data("status");
            const position = $(this).data("position") ?? 0;
            const description = $(this).data("description") || "";

            const form = $("#editDepartmentForm");
            form.attr("action", "/admin/academic/departments/" + id);

            $("#deptEditTitle").val(title);
            $("#deptEditShortCode").val(shortCode);
            $("#deptEditSlug").val(slug);
            $("#deptEditStatus").val(status);
            $("#deptEditPosition").val(position);
            $("#deptEditDescription").val(description);

            $("#editDepartmentModal").modal("show");
        });

        /* =====================================================
           6) STATUS TOGGLE — DEPARTMENT
        ===================================================== */
        $(document).on("change", ".toggleDepartmentStatus", function() {
            const id = $(this).data("id");

            const urlTpl = (window.__ACADEMIC && window.__ACADEMIC.DEPARTMENT_TOGGLE_URL) ? window.__ACADEMIC
                .DEPARTMENT_TOGGLE_URL : '';
            const url = urlTpl.replace(':id', id);

            fetch(url, {
                    method: "PATCH",
                    headers: {
                        "X-CSRF-TOKEN": CSRF,
                        "Accept": "application/json"
                    }
                })
                .then(res => res.json())
                .then(json => {
                    if (json.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: json.message || 'Department status updated.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    } else {
                        showToast("error", json.message || "Status update failed");
                    }
                })
                .catch(() => showToast("error", "Status update failed"));
        });

        /* =====================================================
           7) STAFF GROUP & MEMBER MODALS (ACTIONS)
        ===================================================== */
        function setDragImagePickerPreview(uid, imageUrl) {
            const preview = document.getElementById(uid + "-preview");
            const removeFlag = document.getElementById(uid + "-remove-flag");
            const fileInput = document.getElementById(uid);

            if (preview && imageUrl) {
                preview.style.backgroundImage = "url('" + imageUrl + "')";
            }

            // clear file input (so old file not stuck)
            if (fileInput) {
                fileInput.value = "";
            }

            // ensure remove flag is 0 (meaning keep existing image unless user clicks remove)
            if (removeFlag) {
                removeFlag.value = 0;
            }
        }

        function initDynamicButtons() {

            $(document).off("click", ".createStaffGroupBtn").on("click", ".createStaffGroupBtn", function() {
                const deptId = $(this).data("department-id");
                $("#createStaffGroupDepartmentId").val(deptId);
                $("#createStaffGroupForm").attr("action", "/admin/academic/departments/" + deptId + "/groups");
                $("#createStaffGroupModal").modal("show");
            });

            $(document).off("click", ".createStaffMemberBtn").on("click", ".createStaffMemberBtn", function() {
                const groupId = $(this).data("group-id");
                $("#createMemberGroupId").val(groupId);
                $("#createStaffMemberForm").attr("action", "/admin/academic/staff-groups/" + groupId + "/members");
                $("#createStaffMemberModal").modal("show");
            });

            $(document).off("click", ".editStaffGroupBtn").on("click", ".editStaffGroupBtn", function() {
                const id = $(this).data("id");
                const title = $(this).data("title");
                const status = $(this).data("status");

                const form = $("#editStaffGroupForm");
                form.attr("action", "/admin/academic/staff-groups/" + id);

                $("#editStaffGroupTitle").val(title);
                $("#editStaffGroupStatus").val(status);

                $("#editStaffGroupModal").modal("show");
            });

            $(document).off("click", ".editStaffMemberBtn").on("click", ".editStaffMemberBtn", function() {
                const member = $(this).data("member");

                window.__editingMember = member;

                const form = $("#editStaffMemberForm");
                form.attr("action", "/admin/academic/staff-members/" + member.id);

                $("#editMemberName").val(member.name || '');
                $("#editMemberDesignation").val(member.designation || '');
                $("#editMemberEmail").val(member.email || '');
                $("#editMemberPhone").val(member.phone || '');
                $("#editMemberStatus").val(member.status || 'published');

                if (document.getElementById('editMemberMobile')) document.getElementById('editMemberMobile').value =
                    (member.mobile || '');
                if (document.getElementById('editMemberAddress')) document.getElementById('editMemberAddress')
                    .value = (member.address || '');
                if (document.getElementById('editMemberResearchInterest')) document.getElementById(
                    'editMemberResearchInterest').value = (member.research_interest || '');

                setDragImagePickerPreview('editMemberImagePicker', member.image_url || '');
                $("#editStaffLinksRepeater").html("");
                editLinkIndex = 0;

                if (member.links && member.links.length) {
                    member.links.forEach(function(link) {
                        addStaffLinkRow("#editStaffLinksRepeater", "#staffLinkTemplateEdit", true, link);
                    });
                }

                $("#editStaffMemberModal").modal("show");
            });
        }

        $('#editStaffMemberModal').off('shown.bs.modal').on('shown.bs.modal', function() {
            const member = window.__editingMember || {};

            setTinyContent('edit_member_bio', member.bio || '');
            setTinyContent('edit_member_education', member.education || '');
            setTinyContent('edit_member_experience', member.experience || '');
            setTinyContent('edit_member_employment_history', member.employment_history || '');
            setTinyContent('edit_member_institutional_member', member.institutional_member || '');
            setTinyContent('edit_member_research', member.research || '');
            setTinyContent('edit_member_consultancy', member.consultancy || '');
            setTinyContent('edit_member_teaching', member.teaching || '');
        });

        /* =====================================================
           8) STAFF LINKS REPEATER (CREATE + EDIT)
        ===================================================== */
        let linkIndex = 1;
        let editLinkIndex = 0;

        function addStaffLinkRow(containerSelector, templateSelector, isEdit = false, linkData = null) {
            const index = isEdit ? editLinkIndex : linkIndex;
            const html = $(templateSelector).html().replace(/__INDEX__/g, index);
            const $row = $(html);

            $(containerSelector).append($row);

            if (typeof initIconPicker === "function") {
                initIconPicker($row[0]);
            }

            if (linkData) {
                $row.find("input[name='links[" + index + "][url]']").val(linkData.url || "");
                $row.find(".icon-picker-input").val(linkData.icon || "");
                $row.find(".icon-picker-toggle i").attr("class", linkData.icon || "fa fa-icons");
            }

            if (isEdit) {
                editLinkIndex++;
            } else {
                linkIndex++;
            }
        }

        $("#addStaffLinkBtn").on("click", function() {
            addStaffLinkRow("#staffLinksRepeater", "#staffLinkTemplate", false, null);
        });

        $("#addStaffLinkBtnEdit").on("click", function() {
            addStaffLinkRow("#editStaffLinksRepeater", "#staffLinkTemplateEdit", true, null);
        });

        $(document).on("click", ".removeLinkBtn", function() {
            $(this).closest(".link-row").remove();
        });

        /* =====================================================
           10) SORTABLES (USING SortableJS)
        ===================================================== */
        function initSortables() {

            const deptList = document.getElementById('departmentsSortable');
            if (deptList && typeof Sortable !== "undefined") {
                if (deptList.__sortableInstance) {
                    deptList.__sortableInstance.destroy();
                }
                deptList.__sortableInstance = new Sortable(deptList, {
                    handle: '.dept-sort-handle',
                    animation: 150,
                    onEnd: function() {
                        let order = [];
                        $("#departmentsSortable .department-item").each(function() {
                            order.push($(this).data("id"));
                        });

                        const urlTpl = (window.__ACADEMIC && window.__ACADEMIC.DEPARTMENT_SORT_URL) ? window
                            .__ACADEMIC.DEPARTMENT_SORT_URL : '';
                        const url = urlTpl.replace(':site', $("#activeSiteId").val());

                        $.post(url, {
                            order: order,
                            _token: CSRF
                        }).done(() => {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Department order updated.',
                                icon: 'success',
                                timer: 1200,
                                showConfirmButton: false
                            });
                        }).fail(() => {
                            showToast("error", "Failed to update department order");
                        });
                    }
                });
            }

            const groupContainer = document.getElementById('departmentStaffAccordion');
            if (groupContainer && typeof Sortable !== "undefined") {
                if (groupContainer.__sortableInstance) {
                    groupContainer.__sortableInstance.destroy();
                }
                groupContainer.__sortableInstance = new Sortable(groupContainer, {
                    handle: '.group-sort-handle',
                    animation: 150,
                    onEnd: function() {
                        let order = [];
                        $("#departmentStaffAccordion .staff-group-row").each(function() {
                            order.push($(this).data("id"));
                        });

                        const deptId = $("#currentDepartmentId").val();

                        $.post("/admin/academic/departments/" + deptId + "/groups/sort", {
                            order: order,
                            _token: CSRF
                        }).done(() => {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Staff group order updated.',
                                icon: 'success',
                                timer: 1200,
                                showConfirmButton: false
                            });
                        }).fail(() => {
                            showToast("error", "Failed to update staff group order");
                        });
                    }
                });
            }

            $(".staff-members-sortable").each(function() {
                const tbody = this;
                if (typeof Sortable === "undefined") return;

                if (tbody.__sortableInstance) {
                    tbody.__sortableInstance.destroy();
                }

                const groupId = $(tbody).data("group-id");

                tbody.__sortableInstance = new Sortable(tbody, {
                    handle: ".member-sort",
                    animation: 150,
                    onEnd: function() {
                        let order = [];
                        $(tbody).find(".member-row").each(function() {
                            order.push($(this).data("id"));
                        });

                        $.post("/admin/academic/staff-groups/" + groupId + "/members/sort", {
                            order: order,
                            _token: CSRF
                        }).done(() => {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Staff member order updated.',
                                icon: 'success',
                                timer: 1200,
                                showConfirmButton: false
                            });
                        }).fail(() => {
                            showToast("error", "Failed to update staff member order");
                        });
                    }
                });
            });
        }

        /* =====================================================
           11) INITIALIZE ON PAGE LOAD
        ===================================================== */
        $(document).ready(function() {
            initSortables();
            initDynamicButtons();

            const urlParams = new URLSearchParams(window.location.search);
            const deptIdFromUrl = urlParams.get("department_id");
            if (deptIdFromUrl) {
                const li = $(".department-item[data-id='" + deptIdFromUrl + "']");
                if (li.length) {
                    li.addClass("active");
                    loadDepartmentPanel(deptIdFromUrl, false);
                }
            }

            $("#openCreateDepartmentModalBtn").on("click", function() {
                $("#createDeptSiteId").val($("#activeSiteId").val());
            });
        });
    </script>
@endverbatim
