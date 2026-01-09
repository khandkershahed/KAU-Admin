<x-admin-app-layout :title="'About Module'">

    <div class="card card-flash">
        <div class="card-header mt-6 align-items-center">
            <h3 class="card-title fw-bold">About Pages Management</h3>

            <div class="card-toolbar d-flex">

                <div class="row g-2 me-4">
                    <div class="col">
                        <x-metronic.label for="aboutSearch" class="col-form-label fw-bold fs-6">
                            Search
                        </x-metronic.label>
                        <input type="text" id="aboutSearch" class="form-control form-control-sm"
                            placeholder="Title, slug..." value="{{ $search }}">
                    </div>

                    <div class="col">
                        <x-metronic.label for="aboutStatusFilter" class="col-form-label fw-bold fs-6">
                            Status
                        </x-metronic.label>
                        <x-metronic.select-option id="aboutStatusFilter" class="form-select-sm" name="status"
                            data-hide-search="true">
                            <option value="">All</option>
                            <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>Archived</option>
                        </x-metronic.select-option>
                    </div>
                </div>

                @can('create about page')
                    <a href="{{ route('admin.about.create') }}" class="btn btn-primary btn-sm ms-3">
                        <i class="fa fa-plus me-2"></i> Add About Page
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body pt-0">

            <div class="accordion" id="aboutAccordion">
                <div class="accordion-item mb-5">

                    <div class="accordion-header d-flex align-items-center justify-content-between px-3 py-2"
                        style="background: aliceblue;">
                        <div class="d-flex align-items-center flex-grow-1">
                            <span class="me-3">
                                <i class="fa-solid fa-circle-info text-primary"></i>
                            </span>

                            <button class="accordion-button py-2 px-2 shadow-none bg-transparent flex-grow-1"
                                type="button" data-bs-toggle="collapse" data-bs-target="#about-pages-body">
                                <span class="fw-semibold">About Menu Pages</span>
                            </button>
                        </div>
                    </div>

                    <div id="about-pages-body" class="accordion-collapse collapse show">
                        <div class="accordion-body">

                            <div id="aboutTableWrapper">
                                @include('admin.pages.about.partials.table', ['pages' => $pages])
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
        <script>
            const aboutIndexUrl = "{{ route('admin.about.index') }}";
            const aboutSortUrl = "{{ route('admin.about.sort.order') }}";
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

            function buildAboutUrl(pageUrl = null) {
                const q = $('#aboutSearch').val() || '';
                const status = $('#aboutStatusFilter').val() || '';
                let url = pageUrl ? pageUrl.split('?')[0] : aboutIndexUrl;

                const params = new URLSearchParams();
                if (q) params.append('q', q);
                if (status) params.append('status', status);

                return url + (params.toString() ? '?' + params.toString() : '');
            }

            function loadAboutTable(pageUrl = null) {
                const url = buildAboutUrl(pageUrl);

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        ajax: 1
                    },
                    success: function(html) {
                        $('#aboutTableWrapper').html(html);
                        initSortable(); // re-init after render
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to load about pages.', 'error');
                    }
                });
            }

            function initSortable() {
                $('#aboutPagesTbody').sortable({
                    handle: '.sort-handle',
                    cancel: 'td:last-child',
                    update: function() {
                        const order = $(this).children().map(function() {
                            return $(this).data('id');
                        }).get();

                        $.post({
                            url: aboutSortUrl,
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
            // function initSortable() {
            //     // use jQuery UI sortable on tbody
            //     if (typeof $.fn.sortable === 'undefined') {
            //         return;
            //     }

            //     $('#aboutPagesTbody').sortable({
            //         handle: '.sort-handle',
            //         axis: 'y',
            //         update: function () {
            //             const order = [];
            //             $('#aboutPagesTbody').find('tr.sort-row').each(function () {
            //                 order.push($(this).data('id'));
            //             });

            //             $.ajax({
            //                 url: aboutSortUrl,
            //                 method: 'POST',
            //                 data: {
            //                     _token: csrfToken,
            //                     order: order
            //                 },
            //                 success: function (res) {
            //                     if (res.success) {
            //                         Swal.fire({
            //                             icon: 'success',
            //                             title: 'Order Updated',
            //                             text: res.message || 'Menu order updated successfully.',
            //                             timer: 1500,
            //                             showConfirmButton: false
            //                         });
            //                     } else {
            //                         Swal.fire('Error', res.message || 'Failed to update order.', 'error');
            //                     }
            //                 },
            //                 error: function () {
            //                     Swal.fire('Error', 'Failed to update order.', 'error');
            //                 }
            //             });
            //         }
            //     });
            // }

            $(document).ready(function() {
                initSortable();
                // Search
                $('#aboutSearch').on('input', debounce(function() {
                    loadAboutTable();
                }, 400));

                // Status filter
                $('#aboutStatusFilter').on('change', function() {
                    loadAboutTable();
                });

                // Pagination
                $(document).on('click', '#aboutTableWrapper .pagination a', function(e) {
                    e.preventDefault();
                    const href = $(this).attr('href');
                    loadAboutTable(href);
                });

                // FEATURED TOGGLE
                $(document).on('change', '.js-about-feature-toggle', function() {
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
                                    text: json.message || 'Featured status updated.',
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

                // STATUS SELECT
                $(document).on('change', '.js-about-status-select', function() {
                    const select = this;
                    const url = select.dataset.url;
                    const newStatus = select.value;
                    const previousValue = select.getAttribute('data-prev') || '';

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
                                    text: json.message || 'Status updated successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
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
            });
        </script>
    @endpush

</x-admin-app-layout>
