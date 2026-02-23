<x-admin-app-layout :title="'Bulk Add Publications'">


    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-none">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div>
                        <h5 class="mb-0">Bulk Add Publications</h5>
                        <p class="text-sm mb-0">
                            Member: <b>{{ $member->name }}</b>
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('admin.academic.publications.index', $member->id) }}"
                            class="btn btn-sm btn-dark">
                            <i class="fa-solid fa-arrow-left me-2"></i> Back
                        </a>
                    </div>

                </div>

                <div class="card-body">
                    <div class="mb-5" id="bulkPreviewContainer"></div>
                    <form action="{{ route('admin.academic.publications.bulk-store', $member->id) }}" method="POST"
                        novalidate>
                        @csrf
                        <input type="hidden" name="redirect_to"
                            value="{{ route('admin.academic.publications.index', $member->id) }}">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <div class="text-muted text-sm">
                                Paste from CV / Excel and Preview before import. (Excel Columns: N, O, P, Q)
                            </div>

                            <div class="btn-group" role="group" aria-label="Bulk actions">
                                <button type="button" class="btn btn-sm btn-dark me-2"
                                    onclick="previewPublications({{ $member->id }})">
                                    <i class="fa-solid fa-eye me-2"></i> Preview
                                </button>

                                <button type="button" class="btn btn-sm btn-success" id="btnConfirmImport"
                                    onclick="confirmPublications({{ $member->id }})" disabled>
                                    <i class="fa-solid fa-check me-2"></i> Confirm Import
                                </button>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="border rounded-2 p-2 bg-white">
                                    <x-metronic.editor name="bulk_text[journal]" label="Journal (Excel Column N)"
                                        :value="old('bulk_text[journal]')" rows="12" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded-2 p-2 bg-white">
                                    <x-metronic.editor name="bulk_text[conference]" label="Conference (Excel Column Q)"
                                        :value="old('bulk_text[conference]')" rows="12" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded-2 p-2 bg-white">
                                    <x-metronic.editor name="bulk_text[seminar]"
                                        label="Seminar / Workshop (Excel Column O)" :value="old('bulk_text[seminar]')" rows="12" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded-2 p-2 bg-white">
                                    <x-metronic.editor name="bulk_text[book_chapter]"
                                        label="Book Chapter (Excel Column P)" :value="old('bulk_text[book_chapter]')" rows="12" />
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            async function previewPublications(memberId) {

                const url = "{{ route('admin.academic.publications.bulk-preview', ':id') }}"
                    .replace(':id', memberId);

                function readEditorValueByName(name) {
                    const el = document.querySelector(`[name="${name}"]`);
                    if (!el) return '';

                    const id = el.getAttribute('id');
                    if (window.tinymce && id && tinymce.get(id)) {
                        return tinymce.get(id).getContent();
                    }

                    return el.value || '';
                }

                const payload = {
                    bulk_text: {
                        journal: readEditorValueByName('bulk_text[journal]'),
                        conference: readEditorValueByName('bulk_text[conference]'),
                        seminar: readEditorValueByName('bulk_text[seminar]'),
                        book_chapter: readEditorValueByName('bulk_text[book_chapter]'),
                    }
                };

                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();

                if (!json.ok) return alert('Preview failed');

                document.getElementById('bulkPreviewContainer').innerHTML = json.html;
                document.getElementById('btnConfirmImport').disabled = false;
            }

            async function confirmPublications(memberId) {

                const rows = [];

                document.querySelectorAll('#bulkPreviewContainer tbody tr')
                    .forEach(tr => {

                        const typeEl = tr.querySelector('.js-type');
                        const titleEl = tr.querySelector('.js-title');
                        const yearEl = tr.querySelector('.js-year');
                        const skipEl = tr.querySelector('.js-skip');

                        if (!typeEl || !titleEl || !yearEl || !skipEl) {
                            return;
                        }

                        const yearRaw = (yearEl.value || '').trim();
                        const yearVal = yearRaw === '' ? null : parseInt(yearRaw, 10);

                        rows.push({
                            type: typeEl.value,
                            title: titleEl.value,
                            year: Number.isNaN(yearVal) ? null : yearVal,
                            skip: !!skipEl.checked
                        });
                    });

                if (rows.length === 0) {
                    return alert('No valid rows found to import.');
                }

                const url = "{{ route('admin.academic.publications.bulk-confirm', ':id') }}"
                    .replace(':id', memberId);

                const btn = document.getElementById('btnConfirmImport');
                btn.disabled = true;

                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        rows
                    })
                });

                const json = await res.json();

                if (!json.ok) {
                    btn.disabled = false;
                    return alert('Import failed');
                }

                // ✅ Redirect to publications list
                const redirectUrl = json.redirect_url || "{{ route('admin.academic.publications.index', $member->id) }}";
                window.location.href = redirectUrl;
            }
        </script>
    @endpush

</x-admin-app-layout>
