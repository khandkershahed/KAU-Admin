<script>
$(document).ready(function() {

    /* ============================================================
       TAB MEMORY — Restore Last Opened Tab
    ============================================================ */
    const savedTab = localStorage.getItem("settings_active_tab");

    if (savedTab) {
        $(`.nav-link[data-bs-target="#${savedTab}"]`).tab("show");
        $(".settings-nav-item").removeClass("active");
        $(`#${savedTab}-tab`).closest(".settings-nav-item").addClass("active");
    }

    $(".nav-link").on("shown.bs.tab", function() {
        const id = $(this).data("bs-target").replace("#", "");
        localStorage.setItem("settings_active_tab", id);

        $(".settings-nav-item").removeClass("active");
        $(this).closest(".settings-nav-item").addClass("active");
    });



    /* ============================================================
       UTILITY → REBUILD INDEXES (After Add/Delete/Sort)
    ============================================================ */
    function rebuildIndexes(wrapperSelector, fieldName) {
        $(wrapperSelector).children(".repeater-row").each(function(i) {
            $(this).find("input, textarea, select").each(function() {
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



    /* ============================================================
       GENERIC ADD REPEATER ROW
    ============================================================ */
    function addRepeater(wrapperSelector, template, fieldName) {
        $(wrapperSelector).append(template);
        rebuildIndexes(wrapperSelector, fieldName);
    }



    /* ============================================================
       DELETE REPEATER ROW
    ============================================================ */
    $(document).on("click", ".delete-row-btn", function() {
        const wrapper = $(this).closest(".repeater-wrapper");
        const field = wrapper.data("field");

        $(this).closest(".repeater-row").remove();
        rebuildIndexes(wrapper, field);
    });



    /* ============================================================
       SORTABLE (Footer Links + Social Links)
    ============================================================ */
    function initSortable() {
        $("#footerLinksRepeater").sortable({
            handle: ".sortable-handle",
            update: () => rebuildIndexes("#footerLinksRepeater", "footer_links")
        });

        $("#socialLinksRepeater").sortable({
            handle: ".sortable-handle",
            update: () => rebuildIndexes("#socialLinksRepeater", "social_links")
        });
    }
    initSortable();



    /* ============================================================
       REPEATER TEMPLATES
    ============================================================ */

    const footerLinkTemplate = `
        <div class="repeater-row d-flex gap-2 align-items-center mb-2">
            <span class="sortable-handle cursor-pointer fs-3">☰</span>
            <input type="text" name="footer_links[0][title]" class="form-control form-control-sm" placeholder="Title">
            <input type="text" name="footer_links[0][url]" class="form-control form-control-sm" placeholder="URL">
            <button type="button" class="btn btn-danger btn-sm delete-row-btn"><i class="fas fa-trash-alt"></i></button>
        </div>`;

    const emailTemplate = `
        <div class="repeater-row d-flex gap-2 align-items-center mb-2">
            <input type="text" name="emails[0][title]" class="form-control form-control-sm" placeholder="Title">
            <input type="email" name="emails[0][email]" class="form-control form-control-sm" placeholder="Email">
            <button type="button" class="btn btn-danger btn-sm delete-row-btn"><i class="fas fa-trash-alt"></i></button>
        </div>`;

    const phoneTemplate = `
        <div class="repeater-row d-flex gap-2 align-items-center mb-2">
            <input type="text" name="phone[0][title]" class="form-control form-control-sm" placeholder="Title">
            <input type="text" name="phone[0][phone]" class="form-control form-control-sm" placeholder="Phone">
            <button type="button" class="btn btn-danger btn-sm delete-row-btn"><i class="fas fa-trash-alt"></i></button>
        </div>`;

    const addressTemplate = `
        <div class="repeater-row d-flex gap-2 align-items-center mb-2">
            <input type="text" name="addresses[0][title]" class="form-control form-control-sm" placeholder="Title">
            <input type="text" name="addresses[0][address]" class="form-control form-control-sm" placeholder="Address">
            <button type="button" class="btn btn-danger btn-sm delete-row-btn"><i class="fas fa-trash-alt"></i></button>
        </div>`;



    /* ============================================================
       SOCIAL ICON TEMPLATE (Using Blade-rendered component)
    ============================================================ */
    const rawSocialIconTemplate = $("#socialIconTemplate").html().trim();

    function makeSocialTemplate(index) {

        const iconPicker = rawSocialIconTemplate
            .replace(/SOCIAL_ICON_ID/g, "socialIcon_" + index)
            .replace(/INDEX/g, index);

        return `
        <div class="repeater-row d-flex gap-2 align-items-center mb-2">

            <span class="sortable-handle cursor-pointer fs-3">☰</span>

            <div class="input-group w-lg-300px">
                ${iconPicker}
            </div>

            <input type="text"
                   name="social_links[${index}][url]"
                   class="form-control form-control-sm"
                   placeholder="URL">

            <button type="button" class="btn btn-danger btn-sm delete-row-btn">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>`;
    }



    /* ============================================================
       ADD BUTTON ACTIONS
    ============================================================ */
    $("#addFooterLinkBtn").click(() =>
        addRepeater("#footerLinksRepeater", footerLinkTemplate, "footer_links")
    );

    $("#addEmailBtn").click(() =>
        addRepeater("#emailRepeater", emailTemplate, "emails")
    );

    $("#addPhoneBtn").click(() =>
        addRepeater("#phoneRepeater", phoneTemplate, "phone")
    );

    $("#addAddressBtn").click(() =>
        addRepeater("#addressRepeater", addressTemplate, "addresses")
    );

    $("#addSocialBtn").click(function () {
        const wrapper = "#socialLinksRepeater";
        const count = $(wrapper).children().length;

        $(wrapper).append(makeSocialTemplate(count));
        rebuildIndexes(wrapper, "social_links");
        initSortable();
    });



    /* ============================================================
       AJAX SAVE SETTINGS
    ============================================================ */
    $("#saveSettingsBtn, button[type='submit']").on("click", function(e) {
        e.preventDefault();

        const form = $("form.form")[0];
        const formData = new FormData(form);

        $.ajax({
            url: $(form).attr("action"),
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function(res) {
                Swal.fire({
                    icon: res.success ? "success" : "error",
                    title: res.success ? "Saved Successfully" : "Error",
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                });
            },

            error: function() {
                Swal.fire("Error", "Server error occurred while saving.", "error");
            }
        });
    });



    /* ============================================================
       BUSINESS HOURS TOGGLE
    ============================================================ */
    $(document).on("change", ".toggle-closed", function() {
        const row = $(this).closest("tr");
        const closed = $(this).is(":checked");

        row.find("input[type='time']").prop("disabled", closed).val("");
    });

});
</script>
