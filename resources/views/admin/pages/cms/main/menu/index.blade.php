<x-admin-app-layout title="Main Menu Builder">

    <div class="card">
        <div class="card-header align-items-center">
            <div>
                <h3 class="card-title fw-bold">Main Menu Builder</h3>
                <div class="text-muted">Manage the site-wide navigation (Navbar/Topbar)</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.cms.main.menu.index', ['menu_location' => 'navbar']) }}"
                    class="btn btn-sm {{ $location === 'navbar' ? 'btn-primary' : 'btn-light' }}">Navbar</a>
                <a href="{{ route('admin.cms.main.menu.index', ['menu_location' => 'topbar']) }}"
                    class="btn btn-sm {{ $location === 'topbar' ? 'btn-primary' : 'btn-light' }}">Topbar</a>

                <a href="{{ route('admin.cms.main.menu.create', ['menu_location' => $location]) }}"
                    class="btn btn-dark btn-sm">
                    <i class="fa-solid fa-plus me-2"></i> Create Menu Item
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="alert alert-info mb-6">
                <strong>How it works:</strong>
                Use <code>type=page</code> for dynamic pages rendered at <code>/page/[slug]</code>.
                Use <code>type=group</code> to create mega-menu column headings.
                Set <code>layout=mega</code> on a root item to render mega menu in your Next.js header.
                Drag &amp; drop items to reorder within the same parent.
            </div>

            @php $roots = $byParent[null] ?? collect(); @endphp

            <ul class="list-group menu-sortable" id="mainMenuRoot" data-parent="">
                @foreach ($roots as $root)
                    @include('admin.pages.cms.main.menu.partials.item', [
                        'item' => $root,
                        'byParent' => $byParent,
                        'location' => $location,
                    ])
                @endforeach
            </ul>

            @if (($roots ?? collect())->count() === 0)
                <div class="text-center text-muted py-10">No menu items found. Create your first one.</div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const CSRF = '{{ csrf_token() }}';
                const sortUrl = '{{ route('admin.cms.main.menu.sort') }}';
                const location = '{{ $location }}';

                function jsonFetch(url, options) {
                    const opts = options || {};
                    opts.headers = Object.assign({}, opts.headers || {}, {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    });
                    return fetch(url, opts).then(r => r.json());
                }

                function initSortable() {
                    if (typeof Sortable === 'undefined') return;

                    document.querySelectorAll('.menu-sortable').forEach(function(list) {
                        if (list.__sortableInstance) {
                            list.__sortableInstance.destroy();
                        }

                        list.__sortableInstance = new Sortable(list, {
                            animation: 150,
                            handle: '.sort-handle',
                            draggable: '.menu-item-row',
                            onEnd: function() {

                                // IMPORTANT:
                                // collect ONLY the direct children of THIS list (not nested lists)
                                const order = [];
                                Array.from(list.children).forEach(function(li) {
                                    // find the row for this direct <li> only
                                    const row = li.querySelector(':scope > .menu-item-row') ||
                                        li.querySelector(
                                            '.menu-item-row');
                                    if (!row) return;

                                    const id = parseInt(row.getAttribute('data-id'), 10);
                                    if (!isNaN(id)) order.push(id);
                                });

                                const parentId = list.getAttribute('data-parent') || null;

                                jsonFetch(sortUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        menu_location: location,
                                        parent_id: (parentId === '' ? null : parentId),
                                        order: order
                                    })
                                }).then(function(json) {
                                    if (json && json.success) {
                                        toastr && toastr.success(json.message || 'Saved');
                                    } else {
                                        toastr && toastr.error((json && json.message) ? json
                                            .message : 'Failed');
                                    }
                                }).catch(function() {
                                    toastr && toastr.error('Failed');
                                });
                            }
                        });
                    });
                }

                document.addEventListener('DOMContentLoaded', initSortable);

                document.addEventListener('click', function(e) {
                    const a = e.target.closest('a.delete-main-menu');
                    if (!a) return;
                    e.preventDefault();

                    const url = a.getAttribute('href');

                    Swal.fire({
                        title: 'Delete this item?',
                        text: 'This cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete',
                        cancelButtonText: 'Cancel'
                    }).then(function(res) {
                        if (!res.isConfirmed) return;

                        jsonFetch(url, {
                            method: 'DELETE'
                        }).then(function(json) {
                            if (json && json.success) {
                                Swal.fire('Deleted', json.message || 'Deleted', 'success').then(
                                () => window
                                    .location.reload());
                            } else {
                                Swal.fire('Error', (json && json.message) ? json.message : 'Failed',
                                    'error');
                            }
                        }).catch(function() {
                            Swal.fire('Error', 'Failed', 'error');
                        });
                    });
                });
            })();
        </script>
    @endpush

</x-admin-app-layout>
