<x-admin-app-layout title="Main Menu Builder">

    <div class="card">

        {{-- =======================
             CARD HEADER
        ======================== --}}
        <div class="card-header align-items-center">
            <div>
                <h3 class="card-title fw-bold">Main Menu Builder</h3>
                <div class="text-muted">Manage the site-wide navigation (Navbar / Topbar)</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.cms.main.menu.index', ['menu_location' => 'navbar']) }}"
                   class="btn btn-sm {{ $location === 'navbar' ? 'btn-primary' : 'btn-light' }}">
                    Navbar
                </a>

                <a href="{{ route('admin.cms.main.menu.index', ['menu_location' => 'topbar']) }}"
                   class="btn btn-sm {{ $location === 'topbar' ? 'btn-primary' : 'btn-light' }}">
                    Topbar
                </a>

                <a href="{{ route('admin.cms.main.menu.create', ['menu_location' => $location]) }}"
                   class="btn btn-dark btn-sm">
                    <i class="fa-solid fa-plus me-2"></i> Create Menu Item
                </a>
            </div>
        </div>

        {{-- =======================
             CARD BODY
        ======================== --}}
        <div class="card-body">

            <div class="alert alert-info mb-6">
                <strong>How it works:</strong>
                Use <code>type=page</code> for dynamic pages rendered at <code>/page/[slug]</code>.
                Use <code>type=group</code> for dropdown / mega menu headings.
                Set <code>layout=mega</code> on a root item to render a mega menu.
                Drag &amp; drop items to reorder <strong>within the same parent only</strong>.
            </div>

            @php
                $roots = $byParent[null] ?? collect();
            @endphp

            {{-- ROOT MENU --}}
            <ul class="list-group menu-sortable" id="mainMenuRoot" data-parent="">
                @foreach ($roots as $root)
                    @include('admin.pages.cms.main.menu.partials.item', [
                        'item' => $root,
                        'byParent' => $byParent,
                        'location' => $location
                    ])
                @endforeach
            </ul>

            @if ($roots->count() === 0)
                <div class="text-center text-muted py-10">
                    No menu items found. Create your first menu item.
                </div>
            @endif

        </div>
    </div>

    {{-- =======================
         PAGE SCRIPTS
    ======================== --}}
    @push('scripts')
        <script>
            (function () {

                const CSRF = '{{ csrf_token() }}';
                const sortUrl = '{{ route('admin.cms.main.menu.sort') }}';
                const location = '{{ $location }}';

                function jsonFetch(url, options = {}) {
                    options.headers = Object.assign({}, options.headers || {}, {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    });
                    return fetch(url, options).then(r => r.json());
                }

                function getDirectLiOrder(list) {
                    let items = [];
                    try {
                        items = Array.from(list.querySelectorAll(':scope > li.menu-li'));
                    } catch (e) {
                        items = Array.from(list.children).filter(el =>
                            el.matches && el.matches('li.menu-li')
                        );
                    }

                    return items
                        .map(li => parseInt(li.getAttribute('data-id'), 10))
                        .filter(id => !isNaN(id));
                }

                function initSortable() {
                    if (typeof Sortable === 'undefined') return;

                    document.querySelectorAll('.menu-sortable').forEach(function (list) {

                        if (list.__sortableInstance) {
                            list.__sortableInstance.destroy();
                        }

                        list.__sortableInstance = new Sortable(list, {
                            animation: 150,
                            handle: '.sort-handle',

                            // CRITICAL: only drag direct children
                            draggable: '> li.menu-li',

                            onEnd: function () {

                                const order = getDirectLiOrder(list);
                                const parentId = list.getAttribute('data-parent');

                                jsonFetch(sortUrl, {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({
                                        menu_location: location,
                                        parent_id: (parentId === '' || parentId === null)
                                            ? null
                                            : parseInt(parentId, 10),
                                        order: order
                                    })
                                })
                                .then(function (json) {
                                    if (json && json.success) {
                                        toastr && toastr.success(json.message || 'Order saved');
                                    } else {
                                        toastr && toastr.error(json?.message || 'Failed');
                                    }
                                })
                                .catch(function () {
                                    toastr && toastr.error('Failed');
                                });
                            }
                        });
                    });
                }

                document.addEventListener('DOMContentLoaded', initSortable);

                {{-- DELETE --}}
                document.addEventListener('click', function (e) {
                    const btn = e.target.closest('.delete-main-menu');
                    if (!btn) return;

                    e.preventDefault();
                    const url = btn.getAttribute('href');

                    Swal.fire({
                        title: 'Delete this menu item?',
                        text: 'This action cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete',
                        cancelButtonText: 'Cancel'
                    }).then(function (res) {
                        if (!res.isConfirmed) return;

                        jsonFetch(url, { method: 'DELETE' })
                            .then(function (json) {
                                if (json && json.success) {
                                    Swal.fire('Deleted', json.message || 'Deleted', 'success')
                                        .then(() => window.location.reload());
                                } else {
                                    Swal.fire('Error', json?.message || 'Failed', 'error');
                                }
                            })
                            .catch(function () {
                                Swal.fire('Error', 'Failed', 'error');
                            });
                    });
                });

            })();
        </script>
    @endpush

</x-admin-app-layout>
