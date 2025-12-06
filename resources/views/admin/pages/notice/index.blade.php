<x-admin-app-layout :title="'Notice Module'">

    <div class="card card-flash">
        <div class="card-header mt-6 align-items-center">
            <h3 class="card-title fw-bold">Notice & Category Management</h3>

            <div class="card-toolbar d-flex">

                @can('create notice')
                    <a href="{{ route('admin.notice.create') }}" class="btn btn-primary btn-sm me-3">
                        <i class="fa fa-plus me-2"></i> Add Notice
                    </a>
                @endcan

                @can('create notice category')
                    <button type="button" class="btn btn-light-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#createNoticeCategoryModal">
                        <i class="fa fa-plus me-2"></i> Add Category
                    </button>
                @endcan
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="row">
                {{-- Categories --}}
                <div class="col-md-4 mb-7">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Notice Categories</h5>
                        <input type="text" id="categorySearchInput" class="form-control form-control-sm w-50"
                            placeholder="Search..." value="{{ $categorySearch }}">
                    </div>

                    <div id="categoryTableWrapper">
                        @include('admin.pages.notice.partials.category_table', [
                            'categories' => $categories,
                        ])
                    </div>
                </div>

                {{-- Notices --}}
                <div class="col-md-8 mb-7">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Notices</h5>
                        <input type="text" id="noticeSearchInput" class="form-control form-control-sm w-50"
                            placeholder="Search notices..." value="{{ $noticeSearch }}">
                    </div>

                    <div id="noticeTableWrapper">
                        @include('admin.pages.notice.partials.notice_table', ['notices' => $notices])
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- CREATE CATEGORY MODAL --}}
    <div class="modal fade" id="createNoticeCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.notice-category.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Notice Category</h5>
                    <button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-5">
                        <x-metronic.label for="category_name" class="col-form-label fw-bold fs-6">
                            Name
                        </x-metronic.label>
                        <x-metronic.input id="category_name" type="text" name="name" required />
                    </div>
                    <div class="mb-5">
                        <x-metronic.label for="category_status" class="col-form-label fw-bold fs-6">
                            Status
                        </x-metronic.label>
                        <x-metronic.select-option id="category_status" name="status" data-hide-search="true">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </x-metronic.select-option>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT CATEGORY MODAL --}}
    <div class="modal fade" id="editNoticeCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="editCategoryForm" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Notice Category</h5>
                    <button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-5">
                        <x-metronic.label for="edit_category_name" class="col-form-label fw-bold fs-6">
                            Name
                        </x-metronic.label>
                        <x-metronic.input id="edit_category_name" type="text" name="name" required />
                    </div>
                    <div class="mb-5">
                        <x-metronic.label for="edit_category_status" class="col-form-label fw-bold fs-6">
                            Status
                        </x-metronic.label>
                        <x-metronic.select-option id="edit_category_status" name="status" data-hide-search="true">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </x-metronic.select-option>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const noticeIndexUrl = "{{ route('admin.notice.index') }}";

            // Debounce helper (simple)
            function debounce(fn, delay) {
                let timer = null;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        fn.apply(context, args);
                    }, delay);
                };
            }

            // Load table via AJAX
            function loadTable(target, url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        target: target
                    },
                    success: function(html) {
                        if (target === 'categories') {
                            $('#categoryTableWrapper').html(html);
                        } else if (target === 'notices') {
                            $('#noticeTableWrapper').html(html);
                        }
                    },
                    error: function() {
                        alert('Failed to load data.');
                    }
                });
            }

            $(document).ready(function() {

                // CATEGORY SEARCH
                $('#categorySearchInput').on('input', debounce(function() {
                    const val = $(this).val();
                    const url = noticeIndexUrl + '?category_search=' + encodeURIComponent(val);
                    loadTable('categories', url);
                }, 400));

                // NOTICE SEARCH
                $('#noticeSearchInput').on('input', debounce(function() {
                    const val = $(this).val();
                    const url = noticeIndexUrl + '?notice_search=' + encodeURIComponent(val);
                    loadTable('notices', url);
                }, 400));

                // CATEGORY PAGINATION (delegate)
                $(document).on('click', '#categoryTableWrapper .pagination a', function(e) {
                    e.preventDefault();
                    const url = $(this).attr('href') + '&target=categories';
                    loadTable('categories', url);
                });

                // NOTICE PAGINATION (delegate)
                $(document).on('click', '#noticeTableWrapper .pagination a', function(e) {
                    e.preventDefault();
                    const url = $(this).attr('href') + '&target=notices';
                    loadTable('notices', url);
                });

                // Fill edit category modal
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.editCategoryBtn')) {
                        const btn = e.target.closest('.editCategoryBtn');
                        const id = btn.dataset.id;
                        const name = btn.dataset.name;
                        const status = btn.dataset.status;

                        const form = document.getElementById('editCategoryForm');
                        form.action = "{{ route('admin.notice-category.update', ':id') }}".replace(':id', id);

                        document.getElementById('edit_category_name').value = name;
                        document.getElementById('edit_category_status').value = status;

                        const modal = new bootstrap.Modal(document.getElementById('editNoticeCategoryModal'));
                        modal.show();
                    }
                });

                // Delete notice via AJAX (unchanged)
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.deleteNoticeBtn')) {
                        const btn = e.target.closest('.deleteNoticeBtn');
                        const url = btn.dataset.url;

                        if (!confirm('Delete this notice?')) return;

                        fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            }).then(res => res.json())
                            .then(json => {
                                if (json.success) {
                                    // reload only notice table
                                    const val = $('#noticeSearchInput').val() || '';
                                    const reloadUrl = noticeIndexUrl + '?notice_search=' +
                                        encodeURIComponent(val) + '&target=notices';
                                    loadTable('notices', reloadUrl);
                                } else {
                                    alert('Failed to delete notice.');
                                }
                            }).catch(() => alert('Failed to delete notice.'));
                    }
                });
            });
        </script>
    @endpush

</x-admin-app-layout>
