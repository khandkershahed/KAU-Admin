<script>
    (function() {
        const CSRF = "{{ csrf_token() }}";
        const createUrl = "{{ route('admin.academic.pages.blocks.store', $page->id) }}";
        const sortUrl = "{{ route('admin.academic.pages.blocks.sort', $page->id) }}";

        function jsonFetch(url, options) {
            const opts = options || {};
            opts.headers = Object.assign({}, opts.headers || {}, {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            });
            return fetch(url, opts).then(r => r.json());
        }

        // Toggle block body
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.toggle-block');
            if (!btn) return;
            const target = btn.getAttribute('data-target');
            const el = document.querySelector(target);
            if (!el) return;
            el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
        });

        // Add new block
        const addBtn = document.getElementById('btnAddBlock');
        if (addBtn) {
            addBtn.addEventListener('click', function() {
                const type = (document.getElementById('blockTypeSelect') || {}).value || 'rich_text';

                jsonFetch(createUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ block_type: type, status: 'published', data: {} })
                }).then(json => {
                    if (json && json.success) {
                        Swal.fire('Added', json.message || 'Block added.', 'success')
                            .then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', (json && json.message) ? json.message : 'Failed to add block.', 'error');
                    }
                }).catch(() => {
                    Swal.fire('Error', 'Failed to add block.', 'error');
                });
            });
        }

        // Update block (inline form)
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (!form.classList || !form.classList.contains('block-update-form')) return;
            e.preventDefault();

            const url = form.getAttribute('action');
            const fd = new FormData(form);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                },
                body: fd
            }).then(r => r.json())
                .then(json => {
                    if (json && json.success) {
                        toastr && toastr.success(json.message || 'Block saved.');
                    } else {
                        toastr && toastr.error((json && json.message) ? json.message : 'Failed to save block.');
                    }
                })
                .catch(() => {
                    toastr && toastr.error('Failed to save block.');
                });
        });

        // Delete block
        document.addEventListener('click', function(e) {
            const a = e.target.closest('a.delete-block');
            if (!a) return;
            e.preventDefault();

            const url = a.getAttribute('href');
            const li = a.closest('.block-item');

            Swal.fire({
                title: 'Delete this block?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (!result.isConfirmed) return;

                jsonFetch(url, { method: 'DELETE' }).then(json => {
                    if (json && json.success) {
                        if (li) li.remove();
                        Swal.fire('Deleted', json.message || 'Block deleted.', 'success');
                    } else {
                        Swal.fire('Error', (json && json.message) ? json.message : 'Failed to delete block.', 'error');
                    }
                }).catch(() => {
                    Swal.fire('Error', 'Failed to delete block.', 'error');
                });
            });
        });

        // Sort blocks (SortableJS)
        function initBlocksSortable() {
            const list = document.getElementById('blocksSortable');
            if (!list || typeof Sortable === 'undefined') return;

            if (list.__sortableInstance) {
                list.__sortableInstance.destroy();
            }

            list.__sortableInstance = new Sortable(list, {
                animation: 150,
                handle: '.sort-handle',
                draggable: '.block-item',
                onEnd: function() {
                    const order = [];
                    list.querySelectorAll('.block-item').forEach(function(item) {
                        order.push(parseInt(item.getAttribute('data-id'), 10));
                    });

                    jsonFetch(sortUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ order: order })
                    }).then(json => {
                        if (json && json.success) {
                            toastr && toastr.success(json.message || 'Block order updated.');
                        } else {
                            toastr && toastr.error((json && json.message) ? json.message : 'Failed to update block order.');
                        }
                    }).catch(() => {
                        toastr && toastr.error('Failed to update block order.');
                    });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initBlocksSortable);
    })();
</script>
