<x-admin-app-layout :title="'FAQ Management'">

    <div class="card card-flush">
        <div class="card-header mt-6 align-items-center">
            <h3 class="card-title fw-bold">FAQ Management</h3>

            <div class="card-toolbar d-flex">

                @can('create faq')
                    <button type="button" class="btn btn-primary btn-sm" id="openCreateFaqModal">
                        <i class="fa fa-plus me-2"></i> Add FAQ
                    </button>
                @endcan

            </div>
        </div>

        <div class="card-body pt-0">

            <div class="row mb-5">

                {{-- Search --}}
                <div class="col-md-4 mb-3">
                    <x-metronic.label for="faqSearch" class="col-form-label fw-bold fs-6">Search</x-metronic.label>
                    <input type="text" id="faqSearch" class="form-control form-control-sm"
                        placeholder="Question, category..." value="{{ $search }}">
                </div>

                {{-- Status Filter --}}
                <div class="col-md-3 mb-3">
                    <x-metronic.label for="faqStatusFilter"
                        class="col-form-label fw-bold fs-6">Status</x-metronic.label>
                    <x-metronic.select-option id="faqStatusFilter" class="form-select-sm" name="status"
                        data-hide-search="true">
                        <option value="">All</option>
                        <option value="active" {{ $filterStatus === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $filterStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </x-metronic.select-option>
                </div>

                {{-- Category Filter (Select2 AJAX) --}}
                <div class="col-md-3 mb-3">
                    <x-metronic.label for="faqCategoryFilter"
                        class="col-form-label fw-bold fs-6">Category</x-metronic.label>
                    <select id="faqCategoryFilter" class="form-select form-select-sm">
                        @if ($filterCategory)
                            <option value="{{ $filterCategory }}" selected>{{ $filterCategory }}</option>
                        @endif
                    </select>
                </div>

            </div>

            <div id="faqTableWrapper">
                @include('admin.pages.faq.partials.table', ['faqs' => $faqs])
            </div>
        </div>
    </div>


    {{-- Modal Container --}}
    <div class="modal fade" id="faqModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" id="faqModalContent">
                {{-- AJAX content loads here --}}
            </div>
        </div>
    </div>


    @push('scripts')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
        <script>
            const faqIndexUrl = "{{ route('admin.faq.index') }}";
            const faqCreateUrl = "{{ route('admin.faq.create') }}";
            const faqStoreUrl = "{{ route('admin.faq.store') }}";
            const faqCategorySuggestUrl = "{{ route('admin.faq.category-suggest') }}";
            const csrfToken = "{{ csrf_token() }}";

            /** ───────────────────────────────
             *  AJAX BUILD URL FOR TABLE
             *  ─────────────────────────────── */
            function buildFaqUrl(pageUrl = null) {
                const q = $('#faqSearch').val() || '';
                const status = $('#faqStatusFilter').val() || '';
                const category = $('#faqCategoryFilter').val() || '';

                let url = pageUrl ? pageUrl.split('?')[0] : faqIndexUrl;

                const params = new URLSearchParams();
                if (q) params.append('q', q);
                if (status) params.append('status', status);
                if (category) params.append('category', category);

                return url + (params.toString() ? '?' + params.toString() : '');
            }

            /** ───────────────────────────────
             *  LOAD TABLE
             *  ─────────────────────────────── */
            function loadFaqTable(pageUrl = null) {
                const url = buildFaqUrl(pageUrl);

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        ajax: 1
                    },
                    success: function(html) {
                        $('#faqTableWrapper').html(html);
                        initSortable();
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to load FAQ list.', 'error');
                    }
                });
            }

            /** ───────────────────────────────
             *  INIT SELECT2 CATEGORY INPUT
             *  ─────────────────────────────── */
            function initCategorySelect2(selector = '.faq-category-select2') {
                $(selector).select2({
                    dropdownParent: $('#faqModal'),
                    placeholder: "Type category...",
                    allowClear: true,
                    tags: true,
                    ajax: {
                        url: faqCategorySuggestUrl,
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            term: params.term
                        }),
                        processResults: data => ({
                            results: data
                        })
                    }
                });
            }

            /** ───────────────────────────────
             *  DEBOUNCE
             *  ─────────────────────────────── */
            function debounce(fn, delay) {
                let timer = null;
                return function() {
                    const context = this,
                        args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(() => fn.apply(context, args), delay);
                };
            }

            /** ───────────────────────────────
             *  SORTABLE (drag handle + full row except action)
             *  ─────────────────────────────── */
            function initSortable() {
                $('#faqSortable').sortable({
                    handle: '.sort-handle',
                    cancel: 'td:last-child',
                    update: function() {
                        const order = $(this).children().map(function() {
                            return $(this).data('id');
                        }).get();

                        $.post({
                            url: "{{ route('admin.faq.sort-order') }}",
                            data: {
                                _token: csrfToken,
                                order: order
                            },
                            success: res => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Order Updated',
                                    text: res.message,
                                    timer: 1200,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });
            }

            /** ───────────────────────────────
             *  DOCUMENT READY
             *  ─────────────────────────────── */
            $(document).ready(function() {

                initSortable();

                /** SEARCH */
                $('#faqSearch').on('input', debounce(() => loadFaqTable(), 400));

                /** STATUS FILTER */
                $('#faqStatusFilter').on('change', () => loadFaqTable());

                /** CATEGORY FILTER (Select2 AJAX) */
                $('#faqCategoryFilter').select2({
                    placeholder: "Filter by category",
                    allowClear: true,
                    ajax: {
                        url: faqCategorySuggestUrl,
                        dataType: 'json',
                        data: params => ({
                            term: params.term
                        }),
                        processResults: data => ({
                            results: data
                        })
                    }
                });
                $('#faqCategoryFilter').on('change', () => loadFaqTable());

                /** PAGINATION (AJAX) */
                $(document).on('click', '#faqTableWrapper .pagination a', function(e) {
                    e.preventDefault();
                    loadFaqTable($(this).attr('href'));
                });

                /** LOAD CREATE MODAL */
                $('#openCreateFaqModal').on('click', function() {
                    $.get(faqCreateUrl, function(html) {
                        $('#faqModalContent').html(html);
                        $('#faqModal').modal('show');
                        initCategorySelect2();
                    });
                });

                /** SAVE NEW FAQ */
                $(document).on('click', '#saveFaqBtn', function() {
                    const form = $('#faqCreateForm');

                    $.ajax({
                        url: faqStoreUrl,
                        type: "POST",
                        data: form.serialize(),
                        success: res => {
                            if (res.success) {
                                $('#faqModal').modal('hide');
                                Swal.fire('Success', res.message, 'success');
                                loadFaqTable();
                            }
                        },
                        error: xhr => Swal.fire('Error', 'Failed to create FAQ.', 'error')
                    });
                });

                /** LOAD EDIT MODAL */
                $(document).on('click', '.editFaqBtn', function() {
                    $.get($(this).data('url'), function(html) {
                        $('#faqModalContent').html(html);
                        $('#faqModal').modal('show');
                        initCategorySelect2();
                    });
                });

                /** UPDATE FAQ */
                $(document).on('click', '#updateFaqBtn', function() {
                    const id = $(this).data('id');
                    const form = $('#faqEditForm');

                    $.ajax({
                        url: "{{ url('admin/faq') }}/" + id,
                        type: "POST",
                        data: form.serialize(),
                        success: res => {
                            if (res.success) {
                                $('#faqModal').modal('hide');
                                Swal.fire('Updated', res.message, 'success');
                                loadFaqTable();
                            }
                        },
                        error: xhr => Swal.fire('Error', 'Failed to update FAQ.', 'error')
                    });
                });

                /** DELETE FAQ */
                // $(document).on('click', 'a.delete', function(e) {
                //     e.preventDefault();
                //     const url = $(this).attr('href');

                //     Swal.fire({
                //         title: 'Are you sure?',
                //         text: 'This FAQ will be permanently deleted.',
                //         icon: 'warning',
                //         showCancelButton: true,
                //         confirmButtonText: 'Yes, delete it',
                //         cancelButtonText: 'Cancel'
                //     }).then(result => {
                //         if (!result.isConfirmed) return;

                //         $.ajax({
                //             url: url,
                //             type: "DELETE",
                //             data: {
                //                 _token: csrfToken
                //             },
                //             success: res => {
                //                 if (res.success) {
                //                     Swal.fire('Deleted!', res.message, 'success');
                //                     loadFaqTable();
                //                 }
                //             },
                //             error: () => Swal.fire('Error', 'Failed to delete FAQ.', 'error')
                //         });
                //     });
                // });

                /** STATUS TOGGLE */
                $(document).on('change', '.js-status-toggle', function() {
                    const url = $(this).data('url');
                    const status = $(this).val();

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            _token: csrfToken,
                            status: status
                        },
                        success: res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated',
                                text: res.message,
                                timer: 1300,
                                showConfirmButton: false
                            });
                        }
                    });
                });

                /** FEATURE TOGGLE */
                $(document).on('change', '.js-feature-toggle', function() {
                    const url = $(this).data('url');
                    const isFeatured = this.checked ? 1 : 0;

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            _token: csrfToken,
                            is_featured: isFeatured
                        },
                        success: res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated',
                                text: res.message,
                                timer: 1300,
                                showConfirmButton: false
                            });
                        }
                    });
                });

            });
        </script>
    @endpush

</x-admin-app-layout>
