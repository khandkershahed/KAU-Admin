
<script>
    /* =====================================================
   TOASTR OPTIONS
===================================================== */
    toastr.options = {
        positionClass: "toast-top-right",
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
    DISABLE BROWSER NATIVE MIMETYPE VALIDATION
    ===================================================== */
    $(document).ready(function() {

        // override accept list to avoid HTML5 mime mismatch warning
        $(".member-image-input").attr("accept", "image/*");

        // prevent Chrome "valid mimetype" native error
        $(".member-image-input").on("change", function() {
            this.setCustomValidity("");
        });

    });

    /* =====================================================
       jQuery VALIDATION — GLOBAL RULES
    ===================================================== */
    function applyValidation(formId, rulesObj) {

        $(formId).validate({
            rules: rulesObj,

            errorPlacement: function(error, element) {

                /* ===== IMAGE INPUT (METRONIC) ===== */
                if (element.hasClass("member-image-input")) {
                    element.closest(".col-md-4").find(".image_error").html(error.text());
                }

                /* ===== OTHER INPUTS ===== */
                else {
                    let container = element.closest('div').find('.error-text');
                    if (container.length) container.html(error.text());
                    else error.insertAfter(element);
                }
            },

            highlight: function(element) {
                $(element).addClass("is-invalid");
            },

            unhighlight: function(element) {
                $(element).removeClass("is-invalid");
                $(element).closest("div").find(".error-text").html("");
                $(element).closest(".col-md-4").find(".image_error").html("");
            },

            submitHandler: function(form) {
                form.submit(); // direct submit (refresh)
            }
        });
    }


    /* =====================================================
       CUSTOM FILE VALIDATION FOR IMAGE INPUT
    ===================================================== */
    jQuery.validator.addMethod("validImage", function(value, element) {

        if (element.files && element.files.length > 0) {
            let file = element.files[0];
            let type = file.type;

            return (
                type === "image/jpeg" ||
                type === "image/jpg" ||
                type === "image/png" ||
                type === "image/webp"
            );
        }

        return true; // no file selected = OK
    }, "Please upload a valid image (jpg, jpeg, png, webp).");


    /* =====================================================
       VALIDATION RULES
    ===================================================== */
    applyValidation("#createSectionForm", {
        title: {
            required: true,
            maxlength: 255
        }
    });

    applyValidation("#editSectionForm", {
        title: {
            required: true,
            maxlength: 255
        }
    });

    applyValidation("#createMemberForm", {
        name: {
            required: true,
            maxlength: 255
        },
        email: {
            email: true
        },
        image: {
            validImage: true
        }
    });

    applyValidation("#editMemberForm", {
        name: {
            required: true,
            maxlength: 255
        },
        email: {
            email: true
        },
        image: {
            validImage: true
        }
    });


    $("select[data-control='select2']").on("change", function() {
        $(this).valid();
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
       OPEN CREATE MEMBER MODAL
    ===================================================== */
    $(document).on("click", ".createMemberBtn", function() {
        $("#createMemberSectionId").val($(this).data("section-id"));
        $("#createMemberModal").modal("show");
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
        let preview = $("#editMemberImagePreview");

        if (image) {
            preview.css("background-image", "url('{{ asset('storage') }}/" + image + "')");
        } else {
            preview.css("background-image", "url('{{ asset('images/default-user.png') }}')");
        }

        $("#editMemberModal").modal("show");
    });


    /* ======================================================
       SWEETALERT DELETE — EXACT USER REQUESTED CODE
    ===================================================== */
    // $(document).on('click', '.delete', function(e) {
    //     e.preventDefault();

    //     var deleteUrl = $(this).attr('href');

    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "You won't be able to revert this!",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonText: 'Yes, delete it!',
    //         cancelButtonText: 'No, cancel!',
    //         buttonsStyling: false,
    //         customClass: {
    //             confirmButton: 'btn btn-danger',
    //             cancelButton: 'btn btn-success'
    //         }
    //     }).then(function(result) {
    //         if (result.isConfirmed) {

    //             $.ajax({
    //                 url: deleteUrl,
    //                 type: 'DELETE',
    //                 headers: {
    //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //                 },

    //                 success: function(data) {
    //                     Swal.fire('Deleted!', 'Your file has been deleted.', 'success')
    //                         .then(() => location.reload());
    //                 },

    //                 error: function(xhr, status, error) {
    //                     Swal.fire('Error Occurred!', error, 'error');
    //                 }
    //             });
    //         } else if (result.dismiss === swal.DismissReason.cancel) {
    //             Swal.fire('Cancelled', 'Your imaginary file is safe :)', 'error');
    //         }
    //     });
    // });


    /* =====================================================
       DRAG & DROP SORT — SECTIONS
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
                order: order,
                _token: "{{ csrf_token() }}"
            }).done(() => {
                Swal.fire({
                    title: 'Success!',
                    text: 'Office Section order Updated.',
                    icon: 'success',
                    timer: 1200,
                    showConfirmButton: false
                });
            }).fail(() => {
                toastr.error("Failed to update Office Section order");
            });
        }
    });


    /* =====================================================
       DRAG & DROP SORT — MEMBERS
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
                    order: order,
                    _token: "{{ csrf_token() }}"
                }).done(() => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Office Members order Updated.',
                        icon: 'success',
                        timer: 1200,
                        showConfirmButton: false
                    });
                }).fail(() => {
                    toastr.error("Failed to update Office Members order");
                });
            }
        });
    });


    /* =====================================================
       LIVE SEARCH (Sections + Members)
    ===================================================== */
    $("#officeSearchInput").on("input", function() {

        let value = $(this).val().toLowerCase();
        $("#clearOfficeSearchBtn").toggle(value.length > 0);

        $(".section-item").each(function() {

            let sec = $(this);
            let secTitle = sec.find(".accordion-button .fw-semibold").text().toLowerCase();
            let match = false;

            if (secTitle.includes(value)) match = true;

            sec.find("tbody tr").each(function() {
                let row = $(this);
                let text = row.text().toLowerCase();

                if (text.includes(value)) {
                    match = true;
                    row.show();
                } else {
                    row.hide();
                }
            });

            if (match) {
                sec.show();
                sec.find(".accordion-collapse").addClass("show");
            } else {
                sec.hide();
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
