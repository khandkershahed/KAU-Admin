<x-admin-app-layout :title="'Academic Staff Finder'" :breadcrumbs="[
    ['name' => __('Dashboard'), 'url' => route('admin.dashboard')],
    ['name' => 'Academic Staff Finder', 'url' => url()->current()],
]">

    <div class="card card-flush">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold">Academic Staff Finder</h3>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.academic.staff.index') }}" class="btn btn-sm btn-light">
                    Back to Departments & Staff
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="row g-3 mb-5">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" class="form-control form-control-sm" id="staffFinderQ"
                        placeholder="Search by name / email / phone / mobile">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Faculty</label>
                    <select class="form-select form-select-sm" id="staffFinderSite" data-control="select2"
                        data-placeholder="Select a faculty" data-allow-clear="true">
                        <option value="">All Sites</option>
                        @foreach ($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->name }} ({{ $site->short_name }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Department</label>
                    <select class="form-select form-select-sm" id="staffFinderDept" data-control="select2"
                        data-placeholder="Select a department" data-allow-clear="true">
                        <option value="">All Departments</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select form-select-sm" id="staffFinderStatus">
                        {{-- 'published','draft','archived' --}}
                        <option value="">All</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="text-muted small">
                    Type to search. Results update automatically.
                </div>

                <button type="button" class="btn btn-sm btn-light" id="staffFinderClear">
                    Clear filter
                </button>
            </div>

            <div id="staffFinderTableWrap">
                {{-- AJAX loads table here --}}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {

                const qEl = document.getElementById('staffFinderQ');
                const statusEl = document.getElementById('staffFinderStatus');
                const wrap = document.getElementById('staffFinderTableWrap');
                const clearBtn = document.getElementById('staffFinderClear');

                const siteEl = $('#staffFinderSite');
                const deptEl = $('#staffFinderDept');

                let timer = null;
                const BASE_URL = '{{ route('admin.academic.staff.finder.table') }}';

                /* ===============================
                    INIT SELECT2 (MUST COME FIRST)
                =============================== */
                siteEl.select2({
                    placeholder: 'Select a faculty',
                    allowClear: true,
                    width: '100%'
                });

                deptEl.select2({
                    placeholder: 'Select a department',
                    allowClear: true,
                    width: '100%'
                });

                /* ===============================
                    BUILD URL WITH FILTERS
                =============================== */
                function buildUrl(url) {
                    const u = new URL(url, window.location.origin);

                    u.searchParams.set('q', qEl.value || '');
                    u.searchParams.set('site_id', siteEl.val() || '');
                    u.searchParams.set('department_id', deptEl.val() || '');
                    u.searchParams.set('status', statusEl.value || '');

                    return u.toString();
                }

                /* ===============================
                    LOAD TABLE
                =============================== */
                function load(url = BASE_URL) {
                    fetch(buildUrl(url), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(r => r.text())
                        .then(html => wrap.innerHTML = html)
                        .catch(() => {
                            wrap.innerHTML = '<div class="alert alert-danger">Failed to load results.</div>';
                        });
                }

                function debounceLoad() {
                    clearTimeout(timer);
                    timer = setTimeout(load, 300);
                }

                /* ===============================
                    EVENTS
                =============================== */

                // 🔎 Search
                qEl.addEventListener('input', debounceLoad);

                // ✅ Select2 → LISTEN TO NORMAL CHANGE
                siteEl.on('change', function() {
                    load();
                });

                deptEl.on('change', function() {
                    load();
                });

                // Status
                statusEl.addEventListener('change', load);

                // 🧹 Clear filters
                clearBtn.addEventListener('click', function() {
                    qEl.value = '';
                    siteEl.val(null).trigger('change');
                    deptEl.val(null).trigger('change');
                    statusEl.value = '';
                    load();
                });

                // 📄 Pagination
                wrap.addEventListener('click', function(e) {
                    const a = e.target.closest('a');
                    if (!a) return;

                    const href = a.getAttribute('href');
                    if (href && href.includes('page=')) {
                        e.preventDefault();
                        load(href);
                    }
                });

                // 🚀 Initial load
                load();

            })();
        </script>
    @endpush

</x-admin-app-layout>
