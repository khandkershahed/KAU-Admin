<script>
    (function() {
        const csrfToken = "{{ csrf_token() }}";
        const groupSortUrl = "{{ route('admin.academic.groups.sort') }}";
        const siteSortUrl = "{{ route('admin.academic.sites.sort') }}";
        @if ($selectedSite)
            const navSortUrl = "{{ route('admin.academic.nav.sort', $selectedSite->id) }}";
        @else
            const navSortUrl = null;
        @endif

        // -----------------
        // Helper: slugify
        // -----------------
        function slugify(text) {
            return text
                .toString()
                .trim()
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }

        // -----------------
        // Auto Slugs
        // -----------------

        // Group create: title -> slug
        $(document).on('keyup change', '#createGroupTitle', function() {
            const val = $(this).val();
            const $slug = $('#createGroupSlug');
            if (!$slug.data('touched')) {
                $slug.val(slugify(val));
            }
        });
        $(document).on('input', '#createGroupSlug', function() {
            $(this).data('touched', true);
        });

        // Group edit: title -> slug (only if user hasn't touched)
        $(document).on('keyup change', '#editGroupTitle', function() {
            const val = $(this).val();
            const $slug = $('#editGroupSlug');
            if (!$slug.data('touched')) {
                $slug.val(slugify(val));
            }
        });
        $(document).on('input', '#editGroupSlug', function() {
            $(this).data('touched', true);
        });

        // Site create: short_name -> slug
        $(document).on('keyup change', '#createSiteShortName', function() {
            const val = $(this).val();
            const $slug = $('#createSiteSlug');
            if (!$slug.data('touched')) {
                $slug.val(slugify(val));
            }
        });
        $(document).on('input', '#createSiteSlug', function() {
            $(this).data('touched', true);
        });

        // Site edit: short_name -> slug
        $(document).on('keyup change', '#editSiteShortName', function() {
            const val = $(this).val();
            const $slug = $('#editSiteSlug');
            if (!$slug.data('touched')) {
                $slug.val(slugify(val));
            }
        });
        $(document).on('input', '#editSiteSlug', function() {
            $(this).data('touched', true);
        });

        // Nav create: label -> slug + menu_key
        $(document).on('keyup change', '#createNavLabel', function() {
            const val = $(this).val();
            const slug = slugify(val);
            const $slug = $('#createNavSlug');
            const $key = $('#createNavMenuKey');

            if (!$slug.data('touched')) {
                $slug.val(slug);
            }
            if (!$key.data('touched')) {
                $key.val(slug);
            }
        });
        $(document).on('input', '#createNavSlug', function() {
            $(this).data('touched', true);
        });
        $(document).on('input', '#createNavMenuKey', function() {
            $(this).data('touched', true);
        });

        // Nav edit: label -> slug + menu_key (only if untouched)
        $(document).on('keyup change', '#editNavLabel', function() {
            const val = $(this).val();
            const slug = slugify(val);
            const $slug = $('#editNavSlug');
            const $key = $('#editNavMenuKey');

            if (!$slug.data('touched')) {
                $slug.val(slug);
            }
            if (!$key.data('touched')) {
                $key.val(slug);
            }
        });
        $(document).on('input', '#editNavSlug', function() {
            $(this).data('touched', true);
        });
        $(document).on('input', '#editNavMenuKey', function() {
            $(this).data('touched', true);
        });

        // -----------------
        // Modals: fill edit data
        // -----------------

        // Edit group button
        $(document).on('click', '.editGroupBtn', function() {
            const btn = $(this);
            const id = btn.data('id');
            const title = btn.data('title');
            const slug = btn.data('slug');
            const status = btn.data('status');

            const actionUrl = "{{ url('admin/academic/groups') }}/" + id;

            $('#editGroupForm').attr('action', actionUrl);
            $('#editGroupTitle').val(title);
            $('#editGroupSlug').val(slug).data('touched', true);
            $('#editGroupStatus').val(status);

            $('#editGroupModal').modal('show');
        });

        // Create site from group "Add Site" button
        $(document).on('click', '.createSiteBtn', function() {
            const groupId = $(this).data('group-id');
            $('#createSiteGroup').val(groupId);
        });

        // Edit site button
        $(document).on('click', '.editSiteBtn', function() {
            const btn = $(this);
            const id = btn.data('id');
            const groupId = btn.data('group-id');
            const name = btn.data('name');
            const shortName = btn.data('short_name');
            const slug = btn.data('slug');
            const description = btn.data('description');
            const primary = btn.data('primary');
            const secondary = btn.data('secondary');
            const status = btn.data('status');

            const actionUrl = "{{ url('admin/academic/sites') }}/" + id;

            $('#editSiteForm').attr('action', actionUrl);
            $('#editSiteGroup').val(groupId);
            $('#editSiteName').val(name);
            $('#editSiteShortName').val(shortName);
            $('#editSiteSlug').val(slug).data('touched', true);
            $('#editSiteDescription').val(description || '');
            $('#sitePrimaryColorEdit').val(primary || '');
            $('#siteSecondaryColorEdit').val(secondary || '');
            $('#editSiteStatus').val(status || 'published');

            $('#editSiteModal').modal('show');
        });

        // Edit nav item button
        $(document).on('click', '.editNavItemBtn', function() {
            const btn = $(this);
            const id = btn.data('id');

            const label = btn.data('label');
            const slug = btn.data('slug');
            const menuKey = btn.data('menu_key');
            const type = btn.data('type');
            const externalUrl = btn.data('external_url');
            const icon = btn.data('icon');
            const status = btn.data('status');
            const parentId = btn.data('parent_id');

            const actionUrl = "{{ url('admin/academic/nav') }}/" + id;

            $('#editNavForm').attr('action', actionUrl);
            $('#editNavLabel').val(label);
            $('#editNavSlug').val(slug).data('touched', true);
            $('#editNavMenuKey').val(menuKey).data('touched', true);
            $('#editNavType').val(type);
            $('#editNavExternalUrl').val(externalUrl || '');
            $('#editNavParentId').val(parentId || '');
            $('#editNavStatus').val(status || 'published');

            // Show/hide external url wrapper
            if (type === 'external') {
                $('#editNavExternalUrlWrapper').show();
            } else {
                $('#editNavExternalUrlWrapper').hide();
            }

            // icon-picker gets its value normally via its own JS; simplest is to set a data attribute
            // if your component exposes an input, you may set it here as well
            $('#editNavIcon').attr('data-initial-icon', icon || '');

            $('#editNavModal').modal('show');
        });

        // Change type => toggle external url field (create)
        $(document).on('change', '#createNavType', function() {
            const type = $(this).val();
            if (type === 'external') {
                $('#createNavExternalUrlWrapper').show();
            } else {
                $('#createNavExternalUrlWrapper').hide();
                $('#createNavExternalUrl').val('');
            }
        });

        // Change type => toggle external url field (edit)
        $(document).on('change', '#editNavType', function() {
            const type = $(this).val();
            if (type === 'external') {
                $('#editNavExternalUrlWrapper').show();
            } else {
                $('#editNavExternalUrlWrapper').hide();
                $('#editNavExternalUrl').val('');
            }
        });

        // -----------------
        // Sortable: groups
        // -----------------
        if (typeof $.fn.sortable === 'function') {
            $('#academicGroupsAccordion').sortable({
                handle: '.group-sort-handle',
                items: '.group-item',
                update: function() {
                    const order = [];
                    $('#academicGroupsAccordion .group-item').each(function() {
                        order.push($(this).data('id'));
                    });

                    $.post(groupSortUrl, {
                        order: order,
                        _token: csrfToken
                    }).done(function(res) {
                        Swal.fire('Updated', res.message || 'Group order updated.', 'success');
                    }).fail(function() {
                        Swal.fire('Error', 'Failed to update group order.', 'error');
                    });
                }
            });

            // Sortable: sites inside each group
            $('.site-list').each(function() {
                const $list = $(this);
                const groupId = $list.data('group-id');

                $list.sortable({
                    handle: '.site-sort-handle',
                    items: '.site-item',
                    update: function() {
                        const order = [];
                        $list.find('.site-item').each(function() {
                            order.push($(this).data('id'));
                        });

                        $.post(siteSortUrl, {
                            order: order,
                            group_id: groupId,
                            _token: csrfToken
                        }).done(function(res) {
                            Swal.fire('Updated', res.message || 'Site order updated.',
                                'success');
                        }).fail(function() {
                            Swal.fire('Error', 'Failed to update site order.', 'error');
                        });
                    }
                });
            });

            // Sortable: root nav items
            @if ($selectedSite)
                $('#navRootWrapper').sortable({
                    handle: '.nav-handle',
                    items: '.nav-item-row',
                    update: function() {
                        if (!navSortUrl) return;

                        const order = [];
                        $('#navRootWrapper .nav-item-row').each(function() {
                            order.push($(this).data('id'));
                        });

                        $.post(navSortUrl, {
                            order: order,
                            parent_id: null,
                            _token: csrfToken
                        }).done(function(res) {
                            Swal.fire('Updated', res.message || 'Navigation order updated.',
                                'success');
                        }).fail(function() {
                            Swal.fire('Error', 'Failed to update navigation order.', 'error');
                        });
                    }
                });
            @endif
        }

        // -----------------
        // Global delete (SweetAlert + AJAX)
        // -----------------
        $(document).on('click', 'a.delete', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (!result.isConfirmed) return;

                fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(json => {
                        if (json.success) {
                            Swal.fire('Deleted!', json.message || 'Item removed successfully.',
                                    'success')
                                .then(() => {
                                    window.location.reload();
                                });
                        } else {
                            Swal.fire('Error', json.message || 'Delete failed.', 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Delete failed.', 'error');
                    });
            });
        });

    })();
</script>
