<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    // import toastr from '@toastr/toastr';

    toastr.options = {
        positionClass: "top-right",
        closeButton: true,
        progressBar: true,
        newestOnTop: true,
    };

    /* =====================================================
       SELECT2 INIT
    ===================================================== */
    function initSelect2() {
        $('[data-control="select2"]').select2({
            width: "100%",
            dropdownParent: $(".modal.show")
        });
    }

    $(document).on("shown.bs.modal", ".modal", function() {
        $(this).find("[data-control='select2']").select2({
            dropdownParent: $(this)
        });
    });

    /* =====================================================
       CREATE OFFICE MODAL (Auto-select Group)
    ===================================================== */
    $(document).on("click", ".createOfficeBtn", function() {
        let groupId = $(this).data("group-id");
        $("#createOfficeGroupId").val(groupId);
        $("#officeGroupSelect").val(groupId).trigger("change");
        $("#createOfficeModal").modal("show");
    });

    /* =====================================================
       EDIT GROUP
    ===================================================== */
    $(document).on("click", ".editGroupBtn", function() {
        $("#editGroupForm [name='id']").val($(this).data("id"));
        $("#editGroupForm [name='name']").val($(this).data("name"));
        $("#editGroupModal").modal("show");
    });

    /* =====================================================
       EDIT OFFICE
    ===================================================== */
    $(document).on("click", ".editOfficeBtn", function() {

        $("#editOfficeForm [name='id']").val($(this).data("id"));
        $("#editOfficeForm [name='title']").val($(this).data("title"));
        $("#editOfficeForm [name='group_id']").val($(this).data("group")).trigger("change");
        $("#editOfficeForm [name='description']").val($(this).data("description"));

        $("#editOfficeForm [name='meta_title']").val($(this).data("meta_title"));
        $("#editOfficeForm [name='meta_tags']").val($(this).data("meta_tags"));
        $("#editOfficeForm [name='meta_description']").val($(this).data("meta_description"));

        $("#editOfficeModal").modal("show");
    });

    /* =====================================================
       DELETE (SweetAlert + AJAX)
    ===================================================== */
    // $(document).on("click", ".delete", function(e) {
    //     e.preventDefault();

    //     let deleteUrl = $(this).attr("href");
    //     let rowId = $(this).data("id");

    //     Swal.fire({
    //         title: "Are you sure?",
    //         text: "This cannot be undone!",
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonText: "Yes, delete it!",
    //         cancelButtonText: "Cancel",
    //         buttonsStyling: false,
    //         customClass: {
    //             confirmButton: "btn btn-danger",
    //             cancelButton: "btn btn-success"
    //         }
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 url: deleteUrl,
    //                 type: "DELETE",
    //                 data: {
    //                     id: rowId,
    //                     _token: $('meta[name="csrf-token"]').attr("content")
    //                 },
    //                 success: function() {
    //                     toastr.success("Record deleted successfully");
    //                     location.reload();
    //                 },
    //                 error: function(xhr, status, error) {
    //                     toastr.error("Error: " + error);
    //                 }
    //             });
    //         }
    //     });
    // });

    /* =====================================================
       GROUP SORTING
    ===================================================== */
    new Sortable(document.getElementById("adminGroupsAccordion"), {
        handle: ".group-sort",
        animation: 150,

        onEnd: function() {
            let order = [];
            $(".group-item").each(function() {
                order.push($(this).data("group-id"));
            });

            $.post("{{ route('admin.administration.group.sort') }}", {
                    order: order,
                    _token: "{{ csrf_token() }}"
                }).done(function() {

                    // SweetAlert Success
                    Swal.fire({
                        title: 'Success!',
                        text: 'Group order saved.',
                        icon: 'success',
                        timer: 1200,
                        showConfirmButton: false
                    });
                })
                .fail(function() {
                    toastr.error("Failed to save group order");
                });
        }
    });

    /* =====================================================
       OFFICE SORTING
    ===================================================== */
    $(".officesTable tbody").each(function() {
        const tableBody = this;

        new Sortable(tableBody, {
            handle: ".office-sort",
            animation: 150,

            onEnd: function() {
                let order = [];
                $(tableBody).find(".office-row").each(function() {
                    order.push($(this).data("id"));
                });

                $.post("{{ route('admin.administration.office.sort') }}", {
                    order: order,
                    _token: "{{ csrf_token() }}"
                }).done(function() {

                    // SweetAlert Success
                    Swal.fire({
                        title: 'Success!',
                        text: 'Office order saved.',
                        icon: 'success',
                        timer: 1200,
                        showConfirmButton: false
                    });
                })
                .fail(function() {
                    toastr.error("Failed to save Office order");
                });
            }
        });
    });

    /* =====================================================
       LIVE SEARCH WITH ACCORDION MATCHING
    ===================================================== */
    $("#adminSearchInput").on("input", function() {
        let value = $(this).val().toLowerCase();

        $("#clearAdminSearchBtn").toggle(value.length > 0);

        $(".group-item").each(function() {
            let group = $(this);
            let groupMatch = false;

            group.find("tbody tr").each(function() {
                let row = $(this);
                let officeTitle = row.find("td:nth-child(2)").text().toLowerCase();
                let officeSlug = row.find("td:nth-child(3)").text().toLowerCase();

                if (officeTitle.includes(value) || officeSlug.includes(value)) {
                    row.show();
                    groupMatch = true;
                } else {
                    row.hide();
                }
            });

            if (groupMatch) {
                group.show();
                group.find(".accordion-collapse").addClass("show");
            } else {
                group.hide();
            }
        });
    });

    /* =====================================================
       CLEAR SEARCH BUTTON
    ===================================================== */
    $("#clearAdminSearchBtn").on("click", function() {
        $("#adminSearchInput").val("");
        $(this).hide();

        $(".group-item").show();
        $(".accordion-collapse").removeClass("show");

        // Show FIRST accordion by default
        $(".accordion-item:first .accordion-collapse").addClass("show");

        $(".office-row").show();
    });
</script>
