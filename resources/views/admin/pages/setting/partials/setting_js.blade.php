<script>


$(document).ready(function () {

    /* ------------------------------------------------------------
       TAB MEMORY — Restore last opened tab on page load
    ------------------------------------------------------------ */
    const savedTab = localStorage.getItem("settings_active_tab");

    if (savedTab) {
        // Activate saved tab
        $(`.nav-link[data-bs-target="#${savedTab}"]`).tab("show");

        // Activate sidebar highlight
        $(".settings-nav-item").removeClass("active");
        $(`#${savedTab}-tab`).closest(".settings-nav-item").addClass("active");
    }

    // On tab click, save tab ID
    $(".nav-link").on("shown.bs.tab", function () {
        const tabId = $(this).data("bs-target").replace("#", "");
        localStorage.setItem("settings_active_tab", tabId);

        $(".settings-nav-item").removeClass("active");
        $(this).closest(".settings-nav-item").addClass("active");
    });


    /* ------------------------------------------------------------
       UTILITY FUNCTION — REBUILD INDEXES AFTER SORT/ADD/DELETE
    ------------------------------------------------------------ */
    function rebuildIndexes(wrapperSelector, fieldName) {
        $(wrapperSelector).children(".repeater-row").each(function (i) {
            $(this).find("input, textarea, select").each(function () {
                let name = $(this).attr("name");
                if (!name) return;
                name = name.replace(
                    new RegExp(fieldName + "\\[\\d+\\]", "g"),
                    `${fieldName}[${i}]`
                );
                $(this).attr("name", name);
            });
        });
    }


    /* ------------------------------------------------------------
       ADD REPEATER ROW FUNCTION
    ------------------------------------------------------------ */
    function addRepeater(wrapperSelector, template, fieldName) {
        $(wrapperSelector).append(template);
        rebuildIndexes(wrapperSelector, fieldName);
    }


    /* ------------------------------------------------------------
       DELETE REPEATER ROW
    ------------------------------------------------------------ */
    $(document).on("click", ".delete-row-btn", function () {
        const wrapper = $(this).closest(".repeater-wrapper");
        const fieldName = wrapper.data("field");

        $(this).closest(".repeater-row").remove();
        rebuildIndexes(wrapper, fieldName);
    });


    /* ------------------------------------------------------------
       SORTABLE REPEATERS (Footer Links + Social Links)
    ------------------------------------------------------------ */
    function initSortable() {
        $("#footerLinksRepeater").sortable({
            handle: ".sortable-handle",
            update: function () {
                rebuildIndexes("#footerLinksRepeater", "footer_links");
            }
        });

        $("#socialLinksRepeater").sortable({
            handle: ".sortable-handle",
            update: function () {
                rebuildIndexes("#socialLinksRepeater", "social_links");
            }
        });
    }

    initSortable();


    /* ------------------------------------------------------------
       REPEATER TEMPLATES (ROW STYLE)
    ------------------------------------------------------------ */

    const footerLinkTemplate = `
        <div class="repeater-row d-flex gap-2 align-items-center mb-2">
            <span class="sortable-handle cursor-pointer">☰</span>
            <input type="text" name="footer_links[0][title]" class="form-control form-control-sm w-lg-450px" placeholder="Title">
            <input type="text" name="footer_links[0][url]" class="form-control form-control-sm" placeholder="URL">
            <button type="button" class="btn btn-danger btn-sm delete-row-btn"><i class="fas fa-trash-alt"></i></button>
        </div>`;

    const contactPersonTemplate = `
        <div class="repeater-row d-flex gap-2 align-items-center mb-2">
            <input type="text" name="contact_person[0][name]" class="form-control" placeholder="Name">
            <input type="text" name="contact_person[0][designation]" class="form-control" placeholder="Designation">
            <input type="email" name="contact_person[0][email]" class="form-control" placeholder="Email">
            <input type="text" name="contact_person[0][phone]" class="form-control" placeholder="Phone">
            <button type="button" class="btn btn-danger btn-sm delete-row-btn"><i class="fas fa-trash-alt"></i></button>
        </div>`;

    const emailTemplate = `
        <div class="repeater-row d-flex gap-2 align-items-center mb-2">
            <input type="text" name="emails[0][title]" class="form-control form-control-sm w-lg-300px" placeholder="Title">
            <input type="email" name="emails[0][email]" class="form-control form-control-sm" placeholder="Email">
            <button type="button" class="btn btn-danger btn-sm delete-row-btn"><i class="fas fa-trash-alt"></i></button>
        </div>`;

    const phoneTemplate = `
        <div class="repeater-row d-flex gap-2 align-items-center mb-2">
            <input type="text" name="phone[0][title]" class="form-control form-control-sm w-lg-350px" placeholder="Title">
            <input type="text" name="phone[0][phone]" class="form-control form-control-sm" placeholder="Phone">
            <button type="button" class="btn btn-danger btn-sm delete-row-btn"><i class="fas fa-trash-alt"></i></button>
        </div>`;

    const addressTemplate = `
        <div class="repeater-row d-flex gap-2 align-items-center mb-2">
            <input type="text" name="addresses[0][title]" class="form-control form-control-sm w-lg-400px" placeholder="Title">
            <input type="text" name="addresses[0][address]" class="form-control form-control-sm" placeholder="Address">
            <button type="button" class="btn btn-danger btn-sm delete-row-btn"><i class="fas fa-trash-alt"></i></button>
        </div>`;

    const socialTemplate = `
        <div class="repeater-row d-flex gap-2 align-items-center mb-2">
            <span class="sortable-handle cursor-pointer">☰</span>

            <div class="input-group">
                <input type="text" name="social_links[0][icon_class]"
                       class="form-control form-control-sm w-lg-300px icon-picker-input"
                       placeholder="fa-brands fa-facebook">

                <button type="button" class="btn btn-outline-secondary icon-picker-toggle">
                    <i class="fa fa-icons"></i>
                </button>
            </div>

            <input type="text" name="social_links[0][url]"
                   class="form-control form-control-sm" placeholder="URL">

            <button type="button" class="btn btn-danger btn-sm delete-row-btn"><i class="fas fa-trash-alt"></i></button>
        </div>`;


    /* ------------------------------------------------------------
       ADD REPEATER EVENTS
    ------------------------------------------------------------ */
    $("#addFooterLinkBtn").click(() => { addRepeater("#footerLinksRepeater", footerLinkTemplate, "footer_links"); initSortable(); });
    $("#addContactPersonBtn").click(() => { addRepeater("#contactPersonRepeater", contactPersonTemplate, "contact_person"); });
    $("#addEmailBtn").click(() => { addRepeater("#emailRepeater", emailTemplate, "emails"); });
    $("#addPhoneBtn").click(() => { addRepeater("#phoneRepeater", phoneTemplate, "phone"); });
    $("#addAddressBtn").click(() => { addRepeater("#addressRepeater", addressTemplate, "addresses"); });
    $("#addSocialBtn").click(() => { addRepeater("#socialLinksRepeater", socialTemplate, "social_links"); initSortable(); });


    /* ------------------------------------------------------------
       ICON PICKER — INITIALIZE (Component logic handled inside component)
    ------------------------------------------------------------ */
    // Nothing required here — already self-contained in component


    /* ------------------------------------------------------------
       AJAX SAVE SETTINGS
    ------------------------------------------------------------ */
    $("#saveSettingsBtn, button[type='submit']").on("click", function (e) {
        e.preventDefault();

        let form = $("form.form")[0];
        let formData = new FormData(form);

        $.ajax({
            url: $(form).attr("action"),
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function (res) { 
                if (res.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Saved Successfully",
                        text: res.message || "Settings updated!",
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire("Error", res.message || "Unable to save settings.", "error");
                }
            },

            error: function (xhr) {
                Swal.fire("Error", "Server error occurred while saving.", "error");
            }
        });
    });


    /* ------------------------------------------------------------
       BUSINESS HOURS — Enable/Disable Start/End
    ------------------------------------------------------------ */
    $(document).on("change", ".toggle-closed", function () {
        const row = $(this).closest("tr");
        const isClosed = $(this).is(":checked");

        row.find("input[type='time']").prop("disabled", isClosed).val("");
    });


});

</script>
