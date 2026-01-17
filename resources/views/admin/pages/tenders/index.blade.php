<x-admin-app-layout :title="'Tenders'">

    <div class="card card-flush shadow-sm">
        <div class="card-header align-items-center py-5">
            <div class="d-flex flex-column flex-md-row justify-content-between w-100 align-items-md-center gap-3">
                <div>
                    <h3 class="card-title fw-bold mb-1">Tenders</h3>
                    <span class="text-muted fs-7">Manage tenders (list, publish, archive).</span>
                </div>

                <div class="card-toolbar d-flex gap-2">
                    <a href="{{ route('admin.tenders.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>
                        Add Tender
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="row g-3 align-items-end mb-6">
                <div class="col-md-5">
                    <x-metronic.label for="q" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Search</x-metronic.label>
                    <x-metronic.input id="q" name="q" type="text" :value="$search" placeholder="Title / Reference / Department" />
                </div>

                <div class="col-md-3">
                    <x-metronic.label for="status" class="col-form-label fw-bold fs-7 text-uppercase text-muted">Status</x-metronic.label>
                    <x-metronic.select-option id="status" name="status" data-hide-search="true">
                        <option value="">-- All --</option>
                        <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>Archived</option>
                    </x-metronic.select-option>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="button" class="btn btn-light btn-sm mt-6" id="btn_clear">
                        <i class="fas fa-times me-1"></i>
                        Clear
                    </button>
                </div>
            </div>

            <div id="table_container">
                @include('admin.pages.tenders.partials.table', ['tenders' => $tenders, 'search' => $search, 'status' => $status])
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const qEl = document.getElementById('q');
                const statusEl = document.getElementById('status');
                const clearBtn = document.getElementById('btn_clear');
                const container = document.getElementById('table_container');

                const buildUrl = (pageUrl) => {
                    const base = pageUrl || "{{ route('admin.tenders.index') }}";
                    const url = new URL(base, window.location.origin);
                    if (qEl.value.trim()) url.searchParams.set('q', qEl.value.trim());
                    if (statusEl.value) url.searchParams.set('status', statusEl.value);
                    return url.toString();
                };

                const load = async (pageUrl) => {
                    const url = buildUrl(pageUrl);
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    container.innerHTML = await res.text();
                };

                const debounce = (fn, wait=350) => {
                    let t;
                    return (...args) => {
                        clearTimeout(t);
                        t = setTimeout(() => fn(...args), wait);
                    };
                };

                const onChange = debounce(() => load());

                qEl.addEventListener('input', onChange);
                statusEl.addEventListener('change', () => load());

                clearBtn.addEventListener('click', () => {
                    qEl.value = '';
                    statusEl.value = '';
                    load();
                });

                document.addEventListener('click', (e) => {
                    const a = e.target.closest('a[data-ajax-pagination]');
                    if (!a) return;
                    e.preventDefault();
                    load(a.href);
                });

                document.addEventListener('click', (e) => {
                    const btn = e.target.closest('a.delete');
                    if (!btn) return;
                    e.preventDefault();

                    const url = btn.getAttribute('href');

                    Swal.fire({
                        title: 'Delete?',
                        text: 'This will permanently remove the tender.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it',
                    }).then(async (result) => {
                        if (!result.isConfirmed) return;

                        const res = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            }
                        });

                        if (res.ok) {
                            toastr?.success('Deleted successfully.');
                            load();
                        } else {
                            toastr?.error('Failed to delete.');
                        }
                    });
                });
            })();
        </script>
    @endpush

</x-admin-app-layout>
