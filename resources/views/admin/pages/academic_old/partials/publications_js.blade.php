<script>
    document.addEventListener('DOMContentLoaded', function() {

        function jsonParseSafe(v, fallback) {
            try {
                return JSON.parse(v);
            } catch (e) {
                return fallback;
            }
        }

        // ------------------------------------------------------------
        // PUBLICATIONS MODAL OPEN + LOAD
        // ------------------------------------------------------------
        window.openPublicationsModal = async function(memberId) {
            const modalEl = document.getElementById('publicationsModal');
            const modal = new bootstrap.Modal(modalEl);

            document.getElementById('pubMemberId').value = memberId;

            const loader = document.getElementById('publicationsLoader');
            const content = document.getElementById('publicationsContent');

            loader.classList.remove('d-none');
            content.innerHTML = '';

            try {
                const res = await fetch(
                    `{{ url('admin/academic/staff-members') }}/${memberId}/publications/list`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                const data = await res.json();
                content.innerHTML = data.html;

                // Set create form action
                const createForm = document.getElementById('createPublicationForm');
                createForm.action =
                `{{ url('admin/academic/staff-members') }}/${memberId}/publications`;

                // Hook edit buttons
                content.querySelectorAll('.editPublicationBtn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const pubId = this.dataset.id;
                        document.getElementById('editPublicationId').value = pubId;

                        document.getElementById('editPubTitle').value = this.dataset
                            .title || '';
                        document.getElementById('editPubType').value = this.dataset
                            .type || '';
                        document.getElementById('editPubJournal').value = this.dataset
                            .journal || '';
                        document.getElementById('editPubPublisher').value = this.dataset
                            .publisher || '';
                        document.getElementById('editPubYear').value = this.dataset
                            .year || '';
                        document.getElementById('editPubDoi').value = this.dataset
                            .doi || '';
                        document.getElementById('editPubUrl').value = this.dataset
                            .url || '';

                        // Set edit form action
                        const editForm = document.getElementById('editPublicationForm');
                        editForm.action =
                            `{{ url('admin/academic/publications') }}/${pubId}`;
                    });
                });

                // Sortable (optional)
                const pubList = content.querySelector('#publicationsSortable');
                if (pubList && typeof Sortable !== 'undefined') {
                    new Sortable(pubList, {
                        handle: '.pub-sort-handle',
                        animation: 150,
                        onEnd: async function() {
                            const ids = Array.from(pubList.querySelectorAll(
                                    '.publication-item'))
                                .map(li => li.getAttribute('data-id'));

                            const sortUrl =
                                `{{ url('admin/academic/staff-members') }}/${memberId}/publications/sort`;

                            await fetch(sortUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({
                                    order: ids
                                })
                            });
                        }
                    });
                }

            } catch (e) {
                content.innerHTML =
                `<div class="alert alert-danger">Failed to load publications.</div>`;
            } finally {
                loader.classList.add('d-none');
            }

            modal.show();
        }

        // ------------------------------------------------------------
        // GLOBAL DELETE handler (SweetAlert style) - matches your pattern
        // ------------------------------------------------------------
        document.body.addEventListener('click', function(e) {
            const target = e.target.closest('a.delete');
            if (!target) return;

            e.preventDefault();

            const url = target.getAttribute('href');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
            }).then(async (result) => {
                if (!result.isConfirmed) return;

                try {
                    const res = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await res.json();

                    Swal.fire('Deleted!', data.message || 'Deleted.', 'success');

                    // If it was a publication, refresh list in modal
                    const pubMemberId = document.getElementById('pubMemberId');
                    if (pubMemberId && pubMemberId.value) {
                        // attempt reload publications modal content
                        window.openPublicationsModal(pubMemberId.value);
                    } else {
                        // otherwise just reload page
                        window.location.reload();
                    }

                } catch (err) {
                    Swal.fire('Error', 'Delete failed.', 'error');
                }
            });
        });

    });
</script>
