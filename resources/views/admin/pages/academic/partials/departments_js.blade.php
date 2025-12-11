<script>
    (function ($) {
        "use strict";

        // ---------------------------------------------
        // Helpers
        // ---------------------------------------------
        function slugify(text) {
            return text.toString().toLowerCase()
                .replace(/[^a-z0-9]+/g, "-")
                .replace(/^-+|-+$/g, "")
                .substring(0, 180);
        }

        function showToast(type, message) {
            if (window.toastr) {
                toastr[type](message);
            } else {
                alert(message);
            }
        }

        var CSRF = "{{ csrf_token() }}";

        // ---------------------------------------------
        // 1) Auto slug
        // ---------------------------------------------
        $(document).on("keyup", ".slug-source", function () {
            var target = $(this).closest(".modal-body").find(".slug-target");
            target.val(slugify($(this).val()));
        });

        $(document).on("keyup", ".slug-source-edit", function () {
            var target = $(this).closest(".modal-body").find(".slug-target-edit");
            target.val(slugify($(this).val()));
        });

        // ---------------------------------------------
        // 2) Load right panel via AJAX
        // ---------------------------------------------
        function loadDepartmentPanel(deptId, push) {
            if (!deptId) return;

            if (typeof push === "undefined") {
                push = true;
            }

            $("#currentDepartmentId").val(deptId);
            $("#rightPanelLoader").removeClass("d-none");
            $("#rightPanelContent").addClass("d-none");

            $.get("{{ route('admin.academic.staff.index') }}", {
                site_id: $("#activeSiteId").val(),
                department_id: deptId,
                ajax: 1
            }, function (response) {
                $("#rightPanelContent").html(response.html);
                $("#rightPanelLoader").addClass("d-none");
                $("#rightPanelContent").removeClass("d-none");

                initSortables();
                initDynamicButtons();

                if (push) {
                    var url = new URL(window.location.href);
                    url.searchParams.set("department_id", deptId);
                    history.pushState({ dept_id: deptId }, "", url);
                }
            });
        }

        window.addEventListener("popstate", function (event) {
            if (event.state && event.state.dept_id) {
                loadDepartmentPanel(event.state.dept_id, false);
            }
        });

        // ---------------------------------------------
        // 3) Left column — click department
        // ---------------------------------------------
        $(document).on("click", ".dept-click-area", function () {
            var li = $(this).closest(".department-item");
            var deptId = li.data("id");

            $(".department-item").removeClass("active");
            li.addClass("active");

            loadDepartmentPanel(deptId, true);
        });

        // ---------------------------------------------
        // 4) SweetAlert delete (unified)
        // ---------------------------------------------
        $(document).on("click", ".delete", function (e) {
            e.preventDefault();
            var url = $(this).attr("href");

            Swal.fire({
                title: "Are you sure?",
                text: "This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it",
                cancelButtonText: "Cancel",
                reverseButtons: true
            }).then(function (result) {
                if (!result.isConfirmed) return;

                fetch(url, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": CSRF,
                        "Accept": "application/json"
                    }
                })
                    .then(function (res) { return res.json(); })
                    .then(function (json) {
                        if (json.success) {
                            Swal.fire("Deleted!", json.message, "success").then(function () {
                                location.reload();
                            });
                        } else {
                            Swal.fire("Error", json.message || "Delete failed", "error");
                        }
                    })
                    .catch(function () {
                        Swal.fire("Error", "Delete failed", "error");
                    });
            });
        });

        // ---------------------------------------------
        // 5) Edit department modal population
        // ---------------------------------------------
        $(document).on("click", ".editDepartmentBtn", function () {
            var id          = $(this).data("id");
            var title       = $(this).data("title");
            var shortCode   = $(this).data("short-code");
            var slug        = $(this).data("slug");
            var status      = $(this).data("status");
            var position    = $(this).data("position") || 0;
            var description = $(this).data("description") || "";

            var form = $("#editDepartmentForm");
            form.attr("action", "/admin/academic/departments/" + id);

            $("#deptEditTitle").val(title);
            $("#deptEditShortCode").val(shortCode);
            $("#deptEditSlug").val(slug);
            $("#deptEditStatus").val(status);
            $("#deptEditPosition").val(position);
            $("#deptEditDescription").val(description);

            $("#editDepartmentModal").modal("show");
        });

        // ---------------------------------------------
        // 6) Toggle department status
        // ---------------------------------------------
        $(document).on("change", ".toggleDepartmentStatus", function () {
            var id = $(this).data("id");

            fetch("/admin/academic/departments/" + id + "/toggle-status", {
                method: "PATCH",
                headers: {
                    "X-CSRF-TOKEN": CSRF,
                    "Accept": "application/json"
                }
            })
                .then(function (res) { return res.json(); })
                .then(function (json) {
                    if (json.success) {
                        showToast("success", json.message);
                    } else {
                        showToast("error", json.message || "Status update failed");
                    }
                })
                .catch(function () {
                    showToast("error", "Status update failed");
                });
        });

        // ---------------------------------------------
        // 7) Staff group & member modals (buttons)
        // ---------------------------------------------
        function initDynamicButtons() {
            // Create group
            $(document).off("click.createStaffGroup").on("click.createStaffGroup", ".createStaffGroupBtn", function () {
                var deptId = $(this).data("department-id");
                $("#createStaffGroupDepartmentId").val(deptId);
                $("#createStaffGroupForm").attr("action", "/admin/academic/departments/" + deptId + "/groups");
                $("#createStaffGroupModal").modal("show");
            });

            // Create member
            $(document).off("click.createStaffMember").on("click.createStaffMember", ".createStaffMemberBtn", function () {
                var groupId = $(this).data("group-id");
                $("#createMemberGroupId").val(groupId);
                $("#createStaffMemberForm").attr("action", "/admin/academic/staff-groups/" + groupId + "/members");
                $("#createStaffMemberModal").modal("show");
            });

            // Edit group
            $(document).off("click.editStaffGroup").on("click.editStaffGroup", ".editStaffGroupBtn", function () {
                var id     = $(this).data("id");
                var title  = $(this).data("title");
                var status = $(this).data("status");

                var form = $("#editStaffGroupForm");
                form.attr("action", "/admin/academic/staff-groups/" + id);

                $("#editStaffGroupTitle").val(title);
                $("#editStaffGroupStatus").val(status);

                $("#editStaffGroupModal").modal("show");
            });

            // Edit member
            $(document).off("click.editStaffMember").on("click.editStaffMember", ".editStaffMemberBtn", function () {
                var member = $(this).data("member"); 

                var form = $("#editStaffMemberForm");
                form.attr("action", "/admin/academic/staff-members/" + member.id);

                $("#editMemberName").val(member.name);
                $("#editMemberDesignation").val(member.designation);
                $("#editMemberEmail").val(member.email);
                $("#editMemberPhone").val(member.phone);
                $("#editMemberStatus").val(member.status);

                // Image preview (image-input component)
                if (member.image_url) {
                    var preview = document.getElementById("editMemberImagePicker-preview");
                    if (preview) {
                        preview.style.backgroundImage = "url('" + member.image_url + "')";
                    }
                }

                // Clear links in EDIT repeater
                $("#editStaffLinksRepeater").html("");
                editLinkIndex = 0;

                if (member.links && member.links.length) {
                    for (var i = 0; i < member.links.length; i++) {
                        addStaffLinkRow("#editStaffLinksRepeater", "#staffLinkTemplateEdit", true, member.links[i]);
                    }
                }

                $("#editStaffMemberModal").modal("show");
            });
        }

        // ---------------------------------------------
        // 8) Staff links repeater (create + edit)
        // ---------------------------------------------
        var linkIndex     = 1;
        var editLinkIndex = 0;

        function addStaffLinkRow(containerSelector, templateSelector, isEdit, linkData) {
            if (typeof isEdit === "undefined") isEdit = false;
            if (typeof linkData === "undefined") linkData = null;

            var index = isEdit ? editLinkIndex : linkIndex;
            var html  = $(templateSelector).html().replace(/__INDEX__/g, index);
            var $row  = $(html);

            $(containerSelector).append($row);

            // Re-init icon picker for new row
            if (typeof window.initIconPicker === "function") {
                window.initIconPicker($row[0]);
            }

            if (linkData) {
                $row.find("input[name='links[" + index + "][url]']").val(linkData.url || "");
                var iconInput = $row.find(".icon-picker-input");
                var iconBtn   = $row.find(".icon-picker-toggle i");

                if (iconInput.length) {
                    iconInput.val(linkData.icon || "");
                }
                if (iconBtn.length) {
                    iconBtn.attr("class", linkData.icon || "fa fa-icons");
                }
            }

            if (isEdit) {
                editLinkIndex++;
            } else {
                linkIndex++;
            }
        }

        $("#addStaffLinkBtn").on("click", function () {
            addStaffLinkRow("#staffLinksRepeater", "#staffLinkTemplate", false, null);
        });

        $("#addStaffLinkBtnEdit").on("click", function () {
            addStaffLinkRow("#editStaffLinksRepeater", "#staffLinkTemplateEdit", true, null);
        });

        $(document).on("click", ".removeLinkBtn", function () {
            $(this).closest(".link-row").remove();
        });

        // ---------------------------------------------
        // 9) Sortables
        // ---------------------------------------------
        function initSortables() {
            // Departments (left)
            if ($.fn.sortable) {
                $("#departmentsSortable").sortable({
                    handle: ".dept-sort-handle",
                    update: function () {
                        var order = [];
                        $("#departmentsSortable .department-item").each(function () {
                            order.push($(this).data("id"));
                        });

                        var siteId = $("#activeSiteId").val();
                        $.post("/admin/academic/sites/" + siteId + "/departments/sort", {
                            order: order,
                            _token: CSRF
                        }, function (res) {
                            showToast("success", res.message);
                        });
                    }
                });

                // Staff groups
                $("#staffGroupsSortable").sortable({
                    handle: ".group-sort-handle",
                    update: function () {
                        var deptId = $("#currentDepartmentId").val();
                        var order  = [];
                        $("#staffGroupsSortable .staff-group-row").each(function () {
                            order.push($(this).data("id"));
                        });

                        $.post("/admin/academic/departments/" + deptId + "/groups/sort", {
                            order: order,
                            _token: CSRF
                        }, function (res) {
                            showToast("success", res.message);
                        });
                    }
                });

                // Staff members
                $(".staff-members-sortable").each(function () {
                    var $list   = $(this);
                    var groupId = $list.data("group-id");

                    $list.sortable({
                        handle: ".member-sort-handle",
                        update: function () {
                            var order = [];
                            $list.find(".member-row").each(function () {
                                order.push($(this).data("id"));
                            });

                            $.post("/admin/academic/staff-groups/" + groupId + "/members/sort", {
                                order: order,
                                _token: CSRF
                            }, function (res) {
                                showToast("success", res.message);
                            });
                        }
                    });
                });
            }
        }

        // ---------------------------------------------
        // 10) Initial boot
        // ---------------------------------------------
        $(document).ready(function () {
            initSortables();
            initDynamicButtons();

            // Preselect department from URL if available
            var params = new URLSearchParams(window.location.search);
            var deptIdFromUrl = params.get("department_id");
            if (deptIdFromUrl) {
                var li = $(".department-item[data-id='" + deptIdFromUrl + "']");
                if (li.length) {
                    li.addClass("active");
                    loadDepartmentPanel(deptIdFromUrl, false);
                }
            }

            // Fill site id when opening "Add Department"
            $("#openCreateDepartmentModalBtn").on("click", function () {
                $("#createDeptSiteId").val($("#activeSiteId").val());
            });
        });

    })(jQuery);
</script>
