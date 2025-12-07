<x-admin-app-layout :title="'News Module'">

    <div class="card card-flash">
        <div class="card-header mt-6 align-items-center">
            <h3 class="card-title fw-bold">News Management</h3>

            <div class="card-toolbar d-flex">

                @can('create news')
                    <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus me-2"></i> Add News
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body pt-0">

            <div class="row mb-5">
                <div class="col-md-4 mb-3">
                    <x-metronic.label for="newsSearch" class="col-form-label fw-bold fs-6">
                        Search
                    </x-metronic.label>
                    <input type="text" id="newsSearch" class="form-control form-control-sm"
                        placeholder="Title, author, category..." value="{{ $search }}">
                </div>

                <div class="col-md-3 mb-3">
                    <x-metronic.label for="newsStatusFilter" class="col-form-label fw-bold fs-6">
                        Status
                    </x-metronic.label>
                    <x-metronic.select-option id="newsStatusFilter" class="form-select-sm" name="status" data-hide-search="true">
                        <option value="">All</option>
                        <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="unpublished" {{ $status === 'unpublished' ? 'selected' : '' }}>Unpublished
                        </option>
                    </x-metronic.select-option>
                </div>
            </div>

            <div id="newsTableWrapper">
                @include('admin.pages.news.partials.table', ['news' => $news])
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const newsIndexUrl = "{{ route('admin.news.index') }}";
            const csrfToken = "{{ csrf_token() }}";

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

            function buildNewsUrl(pageUrl = null) {
                const q = $('#newsSearch').val() || '';
                const status = $('#newsStatusFilter').val() || '';

                let url = pageUrl ? pageUrl.split('?')[0] : newsIndexUrl;

                const params = new URLSearchParams();
                if (q) params.append('q', q);
                if (status) params.append('status', status);

                return url + (params.toString() ? '?' + params.toString() : '');
            }

            function loadNewsTable(pageUrl = null) {
                const url = buildNewsUrl(pageUrl);

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        ajax: 1
                    },
                    success: function(html) {
                        $('#newsTableWrapper').html(html);
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to load news.', 'error');
                    }
                });
            }

            $(document).ready(function() {
                // Search input
                $('#newsSearch').on('input', debounce(function() {
                    loadNewsTable();
                }, 400));

                // Status filter
                $('#newsStatusFilter').on('change', function() {
                    loadNewsTable();
                });

                // Pagination links (AJAX)
                $(document).on('click', '#newsTableWrapper .pagination a', function(e) {
                    e.preventDefault();
                    const href = $(this).attr('href');
                    loadNewsTable(href);
                });

                // DELETE via SweetAlert + AJAX
                document.addEventListener('click', function(e) {
                    const btn = e.target.closest('.deleteNewsBtn');
                    if (!btn) return;

                    const url = btn.dataset.url;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This news item will be permanently deleted.',
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
                                    Swal.fire('Deleted!', json.message ||
                                        'News deleted successfully.', 'success');
                                    loadNewsTable();
                                } else {
                                    Swal.fire('Error', json.message || 'Failed to delete news.',
                                        'error');
                                }
                            })
                            .catch(() => Swal.fire('Error', 'Failed to delete news.', 'error'));
                    });
                });

                // FEATURED TOGGLE (no reload)
                $(document).on('change', '.js-feature-toggle', function() {
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
                                // Update label text
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
                                // Revert checkbox on error
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
                $(document).on('change', '.js-status-select', function() {
                    const select = this;
                    const url = select.dataset.url;
                    const newStatus = select.value;
                    const previousValue = select.getAttribute('data-prev') || "{{ $status ?? '' }}";

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
                                    text: json.message || 'News status updated successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                // Revert on error
                                if (previousValue) {
                                    select.value = previousValue;
                                }
                                Swal.fire('Error', json.message || 'Failed to update status.', 'error');
                            }
                        })
                        .catch(() => {
                            if (previousValue) {
                                select.value = previousValue;
                            }
                            Swal.fire('Error', 'Failed to update status.', 'error');
                        });
                });

                // Initialize data-prev for status selects on first load
                $(document).on('DOMContentLoaded ajaxComplete', function() {
                    $('.js-status-select').each(function() {
                        if (!this.getAttribute('data-prev')) {
                            this.setAttribute('data-prev', this.value);
                        }
                    });
                });
            });
        </script>
    @endpush


</x-admin-app-layout>
