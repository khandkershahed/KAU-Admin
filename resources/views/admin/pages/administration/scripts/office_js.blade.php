<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>

    /* =====================================================
        INIT: SELECT2
    ===================================================== */
    function initSelect2() {
        $('[data-control="select2"]').select2({
            width: '100%',
            dropdownParent: $('.modal.show')
        });
    }

    $(document).on('shown.bs.modal', '.modal', function() {
        $(this).find('[data-control="select2"]').select2({
            dropdownParent: $(this)
        });
    });


    /* =====================================================
       INIT: TOASTR
    ===================================================== */
    toastr.options = {
        closeButton: true,
        progressBar: true,
        newestOnTop: true,
        positionClass: "toast-bottom-right",
    };


    /* =====================================================
       CSRF HEADER
    ===================================================== */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });


    /* =====================================================
       CREATE SECTION
    ===================================================== */
    $("#createSectionForm").on("submit", function(e) {
        e.preventDefault();

        $.post("{{ route('admin.administration.section.store') }}", $(this).serialize(), function(res) {
            toastr.success(res.message);
            location.reload();
        }).fail(function(err) {
            let errors = err.responseJSON.errors;
            $("#createSectionForm .invalid-feedback").empty();

            $.each(errors, function(key, val) {
                let input = $(`#createSectionForm [name="${key}"]`);
                input.addClass("is-invalid");
                input.next(".invalid-feedback").text(val[0]);
            });
        });
    });


    /* =====================================================
       OPEN EDIT SECTION MODAL
    ===================================================== */
    $(document).on("click", ".editSectionBtn", function() {
        $("#editSectionForm [name='id']").val($(this).data("id"));
        $("#editSectionForm [name='title']").val($(this).data("title"));
        $("#editSectionModal").modal("show");
    });


    /* =====================================================
       UPDATE SECTION
    ===================================================== */
    $("#editSectionForm").on("submit", function(e) {
        e.preventDefault();

        $.post("{{ route('admin.administration.section.update') }}", $(this).serialize(), function(res) {
            toastr.success(res.message);
            location.reload();
        }).fail(function(err) {
            let errors = err.responseJSON.errors;
            $("#editSectionForm .invalid-feedback").empty();

            $.each(errors, function(key, val) {
                let input = $(`#editSectionForm [name="${key}"]`);
                input.addClass("is-invalid");
                input.next(".invalid-feedback").text(val[0]);
            });
        });
    });


    /* =====================================================
       DELETE SECTION
    ===================================================== */
    $(document).on("click", ".deleteSectionBtn", function() {
        if (!confirm("Delete this section?")) return;

        let id = $(this).data("id");

        $.post("{{ route('admin.administration.section.delete') }}", {
                id: id
            },
            function(res) {
                toastr.success(res.message);
                location.reload();
            }
        );
    });


    /* =====================================================
       CREATE MEMBER MODAL
    ===================================================== */
    $(document).on("click", ".createMemberBtn", function() {
        $("#createMemberSectionId").val($(this).data("section-id"));
        $("#createMemberModal").modal("show");
    });


    /* =====================================================
       CREATE MEMBER (WITH IMAGE UPLOAD)
    ===================================================== */
    $("#createMemberForm").on("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.administration.member.store') }}",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function(res) {
                toastr.success(res.message);
                location.reload();
            },

            error: function(err) {
                let errors = err.responseJSON.errors;
                $("#createMemberForm .invalid-feedback").empty();

                $.each(errors, function(key, val) {
                    let input = $(`#createMemberForm [name="${key}"]`);
                    input.addClass("is-invalid");
                    input.next(".invalid-feedback").text(val[0]);
                });
            }
        });
    });


    /* =====================================================
       OPEN EDIT MEMBER MODAL
    ===================================================== */
    $(document).on("click", ".editMemberBtn", function() {

        $("#editMemberForm [name='id']").val($(this).data("id"));
        $("#editMemberForm [name='name']").val($(this).data("name"));
        $("#editMemberForm [name='designation']").val($(this).data("designation"));
        $("#editMemberForm [name='email']").val($(this).data("email"));
        $("#editMemberForm [name='phone']").val($(this).data("phone"));
        $("#editMemberSectionSelect").val($(this).data("section")).trigger("change");

        let image = $(this).data("image");
        if (image) {
            $("#editMemberImagePreview").css("background-image", `url('{{ asset('storage') }}/${image}')`);
        } else {
            $("#editMemberImagePreview").css("background-image",
                "url('{{ asset('images/default-user.png') }}')");
        }

        $("#editMemberModal").modal("show");
    });


    /* =====================================================
       UPDATE MEMBER (WITH IMAGE UPLOAD)
    ===================================================== */
    $("#editMemberForm").on("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.administration.member.update') }}",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function(res) {
                toastr.success(res.message);
                location.reload();
            },

            error: function(err) {
                let errors = err.responseJSON.errors;
                $("#editMemberForm .invalid-feedback").empty();

                $.each(errors, function(key, val) {
                    let input = $(`#editMemberForm [name="${key}"]`);
                    input.addClass("is-invalid");
                    input.next(".invalid-feedback").text(val[0]);
                });
            }
        });
    });


    /* =====================================================
       DELETE MEMBER
    ===================================================== */
    $(document).on("click", ".deleteMemberBtn", function() {
        if (!confirm("Delete this member?")) return;

        $.post(
            "{{ route('admin.administration.member.delete') }}", {
                id: $(this).data("id")
            },
            function(res) {
                toastr.success(res.message);
                location.reload();
            }
        );
    });


    /* =====================================================
       SORT SECTIONS
    ===================================================== */
    new Sortable(document.getElementById('officeSectionsAccordion'), {
        handle: '.section-sort',
        animation: 150,
        onEnd: function() {
            let order = [];

            $(".section-item").each(function() {
                order.push($(this).data("section-id"));
            });

            $.post("{{ route('admin.administration.section.sort') }}", {
                order: order
            });
        }
    });


    /* =====================================================
       SORT MEMBERS (FOR EACH SECTION)
    ===================================================== */
    $(".memberTable tbody").each(function() {

        new Sortable(this, {
            handle: ".member-sort",
            animation: 150,

            onEnd: function() {
                let order = [];
                $(this.el).find(".member-row").each(function() {
                    order.push($(this).data("id"));
                });

                $.post("{{ route('admin.administration.member.sort') }}", {
                    order: order
                });
            }
        });

    });


    /* =====================================================
       LIVE SEARCH
    ===================================================== */
    $("#officeSearchInput").on("keyup", function() {

        let value = $(this).val().toLowerCase();

        if (value) $("#clearOfficeSearchBtn").show();
        else $("#clearOfficeSearchBtn").hide();

        $(".section-item").each(function() {

            let section = $(this);
            let title = section.find(".accordion-button .fw-semibold").text().toLowerCase();
            let match = false;

            // Match section title
            if (title.includes(value)) match = true;

            // Match members
            section.find("tbody tr").each(function() {

                let row = $(this);
                let text = row.text().toLowerCase();

                if (text.includes(value)) {
                    match = true;
                    row.show();
                } else {
                    row.hide();
                }

            });

            // Show / hide section
            if (match) {
                section.show();
                section.find(".accordion-collapse").addClass("show");
            } else {
                section.hide();
            }

        });

    });


    /* =====================================================
       CLEAR SEARCH
    ===================================================== */
    $("#clearOfficeSearchBtn").on("click", function() {

        $("#officeSearchInput").val("");
        $(this).hide();

        $(".section-item").show();
        $(".member-row").show();
        $(".accordion-collapse").removeClass("show");
        $(".accordion-item:first .accordion-collapse").addClass("show");
    });
</script>
