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
                        placeholder="Title, author, category..."
                        value="{{ $search }}">
                </div>

                <div class="col-md-3 mb-3">
                    <x-metronic.label for="newsStatusFilter" class="col-form-label fw-bold fs-6">
                        Status
                    </x-metronic.label>
                    <x-metronic.select-option id="newsStatusFilter" name="status" data-hide-search="true">
                        <option value="">All</option>
                        <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="unpublished" {{ $status === 'unpublished' ? 'selected' : '' }}>Unpublished</option>
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
                    data: { ajax: 1 },
                    success: function(html) {
                        $('#newsTableWrapper').html(html);
                    },
                    error: function() {
                        alert('Failed to load news.');
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

                // Pagination links
                $(document).on('click', '#newsTableWrapper .pagination a', function(e) {
                    e.preventDefault();
                    const href = $(this).attr('href');
                    loadNewsTable(href);
                });

                // Delete via AJAX
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.deleteNewsBtn')) {
                        const btn = e.target.closest('.deleteNewsBtn');
                        const url = btn.dataset.url;

                        if (!confirm('Delete this news item?')) return;

                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        }).then(res => res.json())
                            .then(json => {
                                if (json.success) {
                                    loadNewsTable();
                                } else {
                                    alert('Failed to delete news.');
                                }
                            }).catch(() => alert('Failed to delete news.'));
                    }
                });
            });
        </script>
    @endpush

</x-admin-app-layout>
