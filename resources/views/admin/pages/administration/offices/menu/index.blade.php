<x-admin-app-layout :title="'Office Menu - ' . $office->title">

    <div class="card">
        <div class="card-header align-items-center">
            <div>
                <h3 class="card-title fw-bold">Office Menu</h3>
                <div class="text-muted">Office: <strong>{{ $office->title }}</strong></div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.administration.office.cms.dashboard', $office->slug) }}" class="btn btn-light btn-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                </a>

                <a href="{{ route('admin.administration.office.cms.menu.create', $office->slug) }}" class="btn btn-dark btn-sm">
                    <i class="fa-solid fa-plus me-2"></i> Create Menu Item
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="alert alert-info mb-6">
                <strong>How it works:</strong>
                Create items with type <code>page</code> for internal office pages.
                Then create matching Office Pages and link them by selecting the menu item.
                Drag items to reorder within the same parent.
            </div>

            @php $roots = $byParent[null] ?? collect(); @endphp

            <ul class="list-group menu-sortable" id="officeMenuRoot" data-parent="">
                @foreach($roots as $root)
                    @include('admin.pages.administration.offices.menu.partials.item', ['item'=>$root, 'office'=>$office, 'byParent'=>$byParent])
                @endforeach
            </ul>

            @if(($roots ?? collect())->count() === 0)
                <div class="text-center text-muted py-10">No menu items found. Create your first one.</div>
            @endif
        </div>
    </div>

    <script>
        (function(){
            const CSRF = '{{ csrf_token() }}';
            const sortUrl = '{{ route('admin.administration.office.cms.menu.sort', $office->slug) }}';

            function jsonFetch(url, options){
                const opts = options || {};
                opts.headers = Object.assign({}, opts.headers || {}, { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' });
                return fetch(url, opts).then(r => r.json());
            }

            function initSortable(){
                if (typeof Sortable === 'undefined') return;

                document.querySelectorAll('.menu-sortable').forEach(function(list){
                    if (list.__sortableInstance) { list.__sortableInstance.destroy(); }

                    list.__sortableInstance = new Sortable(list, {
                        animation: 150,
                        handle: '.sort-handle',
                        draggable: '.menu-item-row',
                        onEnd: function(){
                            const order = [];
                            list.querySelectorAll('.menu-item-row').forEach(function(row){
                                order.push(parseInt(row.getAttribute('data-id'), 10));
                            });

                            const parentId = list.getAttribute('data-parent') || null;

                            jsonFetch(sortUrl, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ parent_id: (parentId === '' ? null : parentId), order: order })
                            }).then(function(json){
                                if (json && json.success) {
                                    toastr && toastr.success(json.message || 'Saved');
                                } else {
                                    toastr && toastr.error((json && json.message) ? json.message : 'Failed');
                                }
                            }).catch(function(){ toastr && toastr.error('Failed'); });
                        }
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', initSortable);

            document.addEventListener('click', function(e){
                const a = e.target.closest('a.delete-office-menu');
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
                }).then(function(res){
                    if (!res.isConfirmed) return;

                    jsonFetch(url, { method: 'DELETE' }).then(function(json){
                        if (json && json.success) {
                            Swal.fire('Deleted', json.message || 'Deleted', 'success').then(() => window.location.reload());
                        } else {
                            Swal.fire('Error', (json && json.message) ? json.message : 'Failed', 'error');
                        }
                    }).catch(function(){
                        Swal.fire('Error', 'Failed', 'error');
                    });
                });
            });
        })();
    </script>

</x-admin-app-layout>
