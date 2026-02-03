<script>
    (function() {

        const csrf = "{{ csrf_token() }}";
        const sortUrl =
        "{{ route('admin.academic.pages.index') }}/sort"; // optional if implementing page sorting route

        function slugify(str) {
            return str.toString().trim().toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }

        // Auto-fill slug & key from title
        $(document).on('keyup change', '#pageTitle', function() {
            const v = $(this).val();
            const slug = slugify(v);

            const $slug = $('#pageSlug');
            const $key = $('#pageKey');

            if (!$slug.data('touched')) $slug.val(slug);
            if (!$key.data('touched')) $key.val(slug);
        });

        $(document).on('input', '#pageSlug', function() {
            $(this).data('touched', true);
        });
        $(document).on('input', '#pageKey', function() {
            $(this).data('touched', true);
        });

        // Change nav item => auto-sync slug + page_key
        $(document).on('change', '#pageNavItemId', function() {
            let id = $(this).val();
            if (!id) return;

            let selected = $(this).find('option:selected').text().trim();
            let text = selected.replace(/\(.*\)/, '').trim(); // clean label
            let s = slugify(text);

            $('#pageSlug').val(s).data('touched', true);
            $('#pageKey').val(s).data('touched', true);
        });

        // SORTABLE PAGE LIST
        if (typeof $.fn.sortable === 'function') {
            $('#pageSortableWrapper').sortable({
                handle: '.page-item',
                items: '.page-item',
                update: function() {
                    const order = [];
                    $('#pageSortableWrapper .page-item').each(function() {
                        order.push($(this).data('id'));
                    });

                    $.post("{{ route('admin.academic.pages.index') }}/sort", {
                        order: order,
                        site_id: "{{ optional($selectedSite)->id }}",
                        _token: csrf
                    }).done(res => {
                        Swal.fire('Updated', 'Page order updated.', 'success');
                    }).fail(() => {
                        Swal.fire('Error', 'Failed to update sorting.', 'error');
                    });
                }
            });
        }

        // DELETE — SweetAlert + AJAX
        $(document).on('click', 'a.delete', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');

            Swal.fire({
                title: "Delete this page?",
                text: "This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete",
                cancelButtonText: "Cancel"
            }).then(result => {
                if (!result.isConfirmed) return;

                fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json())
                    .then(json => {
                        if (json.success) {
                            Swal.fire('Deleted', json.message, 'success')
                                .then(() => window.location.reload());
                        } else {
                            Swal.fire('Error', json.message, 'error');
                        }
                    });
            });
        });

    })();
</script>
