<script src="https://cdn.jsdelivr.net/npm/jquery.repeater@1.2.1/jquery.repeater.min.js"></script>

<script>
    const homepagePreviewUrl = "{{ route('admin.homepage.builder.preview') }}";
    const sectionSortUrl = "{{ route('admin.homepage.sections.sort') }}";
    const sectionToggleUrl = "{{ route('admin.homepage.sections.toggle') }}";
    const csrfToken = "{{ csrf_token() }}";

    /**
     * Rebuild hidden section_order[] inputs so if the form is submitted
     * normally, the order is still correct.
     */
    function rebuildSectionOrder() {
        const list = document.getElementById('sections-sortable');
        const container = document.getElementById('section-order-container');
        if (!list || !container) return;

        container.innerHTML = '';

        list.querySelectorAll('.js-section-row').forEach(function(li) {
            const id = li.getAttribute('data-id');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'section_order[]';
            input.value = id;
            container.appendChild(input);
        });
    }

    /**
     * Show the right-hand form for a given section key.
     */
    function showSectionForm(key) {
        // highlight row
        document.querySelectorAll('.js-section-row').forEach(function(row) {
            row.classList.remove('border-primary', 'border', 'shadow-sm');
        });

        const activeRow = document.querySelector('.js-section-row[data-key="' + key + '"]');
        if (activeRow) {
            activeRow.classList.add('border', 'border-primary', 'shadow-sm');
        }

        // show matching form
        document.querySelectorAll('.js-section-form').forEach(function(box) {
            box.style.display = (box.getAttribute('data-section-key') === key) ? 'block' : 'none';
        });

        // update title
        const title = activeRow ?
            activeRow.querySelector('.section-title').innerText.trim() :
            'Section';
        document.getElementById('currentSectionTitle').innerText = title;
    }

    /**
     * Load fullscreen live preview (if you want to keep this).
     */
    function loadHomepagePreview() {
        const overlay = $('#homepagePreviewOverlay');
        const content = $('#homepagePreviewContent');
        const form = $('#homepageBuilderForm');

        overlay.show();

        $.ajax({
            url: homepagePreviewUrl,
            type: 'POST',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(html) {
                content.html(html);
            },
            error: function(xhr) {
                const txt = xhr.responseText ||
                    '<div class="p-10 text-danger text-center">Failed to load preview.</div>';
                content.html(txt);
                console.error('Preview error', xhr.status, xhr.statusText);
            },
            complete: function() {
                overlay.hide();
            }
        });
    }

    $(document).ready(function() {

        /*
         * =========================
         * 1) SORTABLE + AUTOSAVE
         * =========================
         */
        const list = document.getElementById('sections-sortable');
        if (list) {
            new Sortable(list, {
                animation: 150,
                handle: '.section-row-handle',

                onEnd: function() {
                    // rebuild hidden inputs for normal form submit
                    rebuildSectionOrder();

                    // collect order for AJAX
                    let order = [];
                    $('#sections-sortable .js-section-row').each(function() {
                        order.push($(this).data('id'));
                    });

                    // AJAX POST to save order
                    $.ajax({
                        url: sectionSortUrl,
                        type: "POST",
                        data: {
                            order: order,
                            _token: csrfToken
                        },
                        success: function(res) {

                            Swal.fire({
                                title: 'Saved!',
                                text: res.message ||
                                    'Section order has been updated.',
                                icon: 'success',
                                timer: 1200,
                                showConfirmButton: false
                            });
                        },
                        error: function() {

                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to save section order.',
                                icon: 'error',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }

        /*
         * =========================
         * 2) REPEATERS
         * =========================
         */
        $('#banner_repeater').repeater({
            show: function() {
                $(this).slideDown();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        $('#explore_repeater').repeater({
            show: function() {
                $(this).slideDown();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        $('#glance_repeater').repeater({
            show: function() {
                $(this).slideDown();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        /*
         * =========================
         * 3) SECTION SELECT / SEARCH
         * =========================
         */

        // click on row (except switch)
        $(document).on('click', '.js-section-row', function(e) {
            if ($(e.target).closest('.form-check').length) return;
            const key = $(this).data('key');
            showSectionForm(key);
        });

        // click on edit icon
        $(document).on('click', '.js-edit-section', function(e) {
            e.stopPropagation();
            const key = $(this).data('key');
            showSectionForm(key);
        });

        // search filter
        $('#sectionSearch').on('input', function() {
            const term = $(this).val().toLowerCase();
            let firstVisibleKey = null;

            $('.js-section-row').each(function() {
                const title = $(this).find('.section-title').text().toLowerCase();
                const match = !term || title.indexOf(term) !== -1;
                $(this).toggle(match);
                if (match && !firstVisibleKey) {
                    firstVisibleKey = $(this).data('key');
                }
            });

            if (firstVisibleKey) {
                showSectionForm(firstVisibleKey);
            }
        });

        // default: open first section
        const firstRow = document.querySelector('.js-section-row');
        if (firstRow) {
            showSectionForm(firstRow.getAttribute('data-key'));
        }

        /*
         * =========================
         * 4) FULLSCREEN PREVIEW (optional)
         * =========================
         */
        $('#btnHomepagePreview').on('click', function() {
            const modalEl = document.getElementById('homepagePreviewModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
            loadHomepagePreview();
        });

        /*
         * =========================
         * 5) TOGGLE SECTION ON/OFF (AUTOSAVE)
         * =========================
         */
        $(document).on('change', '.js-section-toggle', function() {
            const $row = $(this).closest('.js-section-row');
            const sectionId = $row.data('id');
            const isActive = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: sectionToggleUrl,
                type: 'POST',
                data: {
                    id: sectionId,
                    is_active: isActive,
                    _token: csrfToken
                },
                success: function(res) {
                    Swal.fire({
                        title: 'Saved!',
                        text: res.message || 'Section visibility has been updated.',
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false
                    });
                },
                error: function() {
                    // revert UI if failed
                    $('.js-section-toggle[data-id="' + sectionId + '"]').prop('checked', !
                        isActive);

                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to update section visibility.',
                        icon: 'error',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });

    });
</script>
