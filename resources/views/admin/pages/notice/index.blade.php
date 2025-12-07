<x-admin-app-layout :title="'Notice Module'">

    <div class="card card-flash">
        <div class="card-header align-items-center">
            <h3 class="card-title fw-bold">Notice & Category Management</h3>

            <div class="card-toolbar d-flex">

                @can('create notice')
                    <a href="{{ route('admin.notice.create') }}"
                        class="btn btn-outline btn-outline-primary btn-active-primary btn-sm me-3">
                        <i class="fa fa-plus me-2"></i> Add Notice
                    </a>
                @endcan

                @can('create notice category')
                    <button type="button" class="btn btn-outline btn-outline-info btn-active-info btn-sm"
                        data-bs-toggle="modal" data-bs-target="#createNoticeCategoryModal">
                        <i class="fa fa-plus me-2"></i> Add Category
                    </button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- Categories --}}
                <div class="col-md-4 mb-7 border-right">
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
            const csrfToken = "{{ csrf_token() }}";

            // Debounce helper
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
                        Swal.fire('Error', 'Failed to load data.', 'error');
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

                // CATEGORY PAGINATION
                $(document).on('click', '#categoryTableWrapper .pagination a', function(e) {
                    e.preventDefault();
                    const url = $(this).attr('href') + '&target=categories';
                    loadTable('categories', url);
                });

                // NOTICE PAGINATION
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

                // DELETE NOTICE via SweetAlert + AJAX
                document.addEventListener('click', function(e) {
                    const btn = e.target.closest('.deleteNoticeBtn');
                    if (!btn) return;

                    const url = btn.dataset.url;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This notice will be permanently deleted.',
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
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted',
                                        text: json.message ||
                                            'Notice deleted successfully.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });

                                    // reload only notice table
                                    const val = $('#noticeSearchInput').val() || '';
                                    const reloadUrl = noticeIndexUrl + '?notice_search=' +
                                        encodeURIComponent(val) + '&target=notices';
                                    loadTable('notices', reloadUrl);
                                } else {
                                    Swal.fire('Error', json.message || 'Failed to delete notice.',
                                        'error');
                                }
                            })
                            .catch(() => Swal.fire('Error', 'Failed to delete notice.', 'error'));
                    });
                });

                // FEATURED TOGGLE (no reload)
                $(document).on('change', '.js-notice-feature-toggle', function() {
                    const checkbox = this;
                    const url = checkbox.dataset.url;
                    const isFeatured = checkbox.checked ? 1 : 0;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                is_featured: isFeatured
                            })
                        })
                        .then(res => res.json())
                        .then(json => {
                            if (json.success) {
                                const label = checkbox.closest('.form-check').querySelector(
                                    '.form-check-label');
                                if (label) {
                                    label.textContent = isFeatured ? 'Yes' : 'No';
                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated',
                                    text: json.message || 'Featured status updated successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                checkbox.checked = !isFeatured;
                                Swal.fire('Error', json.message || 'Failed to update featured status.',
                                    'error');
                            }
                        })
                        .catch(() => {
                            checkbox.checked = !isFeatured;
                            Swal.fire('Error', 'Failed to update featured status.', 'error');
                        });
                });

                // STATUS SELECT (no reload)
                $(document).on('change', '.js-notice-status-select', function() {
                    const select = this;
                    const url = select.dataset.url;
                    const newStatus = select.value;
                    const previousValue = select.getAttribute('data-prev') || select.value;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                status: newStatus
                            })
                        })
                        .then(res => res.json())
                        .then(json => {
                            if (json.success) {
                                select.setAttribute('data-prev', newStatus);

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Status Updated',
                                    text: json.message || 'Notice status updated successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                select.value = previousValue;
                                Swal.fire('Error', json.message || 'Failed to update status.', 'error');
                            }
                        })
                        .catch(() => {
                            select.value = previousValue;
                            Swal.fire('Error', 'Failed to update status.', 'error');
                        });
                });

                // Initialize data-prev for status selects on first render
                $(document).on('ajaxComplete', function() {
                    $('.js-notice-status-select').each(function() {
                        if (!this.getAttribute('data-prev')) {
                            this.setAttribute('data-prev', this.value);
                        }
                    });
                });
            });
        </script>
    @endpush


</x-admin-app-layout>
