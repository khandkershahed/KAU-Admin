<x-admin-app-layout :title="'Office Pages - ' . $office->title">

    <div class="card">
        <div class="card-header align-items-center">
            <div>
                <h3 class="card-title fw-bold">Office Pages</h3>
                <div class="text-muted">Office: <strong>{{ $office->title }}</strong> — <code>/offices/{{ $office->slug }}</code></div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.administration.office.cms.dashboard', $office->slug) }}" class="btn btn-light btn-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                </a>

                <a href="{{ route('admin.administration.office.cms.pages.create', $office->slug) }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus me-2"></i> Create Page
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Template</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $p)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $p->title }}</div>
                                    @if($p->is_home)
                                        <span class="badge badge-light-success">Office Home</span>
                                    @endif
                                </td>
                                <td><code>{{ $p->slug }}</code></td>
                                <td><span class="badge badge-light">{{ $p->template_key }}</span></td>
                                <td>
                                    @if($p->status === 'published')
                                        <span class="badge badge-light-success">Published</span>
                                    @elseif($p->status === 'draft')
                                        <span class="badge badge-light-warning">Draft</span>
                                    @else
                                        <span class="badge badge-light-danger">Archived</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.administration.office.cms.pages.edit', [$office->slug, $p->id]) }}" class="btn btn-light-success btn-sm me-2">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="{{ route('admin.administration.office.cms.pages.destroy', [$office->slug, $p->id]) }}" class="btn btn-light-danger btn-sm delete-office-page">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-10">
                                    No pages found. Create your first office page.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="alert alert-info mt-6 mb-0">
                <strong>Frontend routes:</strong>
                <div class="mt-2">
                    <div><code>/offices/{{ $office->slug }}</code> = Office Home page (one page must be marked as Home)</div>
                    <div><code>/offices/{{ $office->slug }}/[pageSlug]</code> = other Office pages</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', function(e) {
            const a = e.target.closest('a.delete-office-page');
            if (!a) return;
            e.preventDefault();

            const url = a.getAttribute('href');

            Swal.fire({
                title: 'Delete this page?',
                text: 'This cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel'
            }).then(function(res) {
                if (!res.isConfirmed) return;

                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(json => {
                    if (json && json.success) {
                        Swal.fire('Deleted', json.message || 'Deleted', 'success').then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', (json && json.message) ? json.message : 'Failed to delete', 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Failed to delete', 'error'));
            });
        });
    </script>

</x-admin-app-layout>
