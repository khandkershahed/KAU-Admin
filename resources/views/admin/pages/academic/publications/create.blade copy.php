<x-admin-app-layout :title="'Bulk Add Publications'">

    <div class="row">
        <div class="col-12">

            <div class="card border-0 shadow-none">

                {{-- ✅ Header contract (bg-custom like your other admin pages) --}}
                <div class="card-header p-3 bg-custom text-white">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5 class="mb-0">Bulk Add Publications</h5>
                            <p class="text-sm mb-0">
                                Member: <b>{{ $member->name }}</b>
                            </p>
                        </div>

                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <a href="{{ route('admin.academic.publications.index', $member->id) }}"
                                class="btn btn-sm btn-dark">
                                <i class="fa-solid fa-arrow-left me-2"></i> Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.academic.publications.bulk-store', $member->id) }}" method="POST"
                        novalidate>
                        @csrf
                        <input type="hidden" name="redirect_to"
                            value="{{ route('admin.academic.publications.index', $member->id) }}">

                        {{-- Preview container --}}
                        <div id="bulkPreviewContainer"></div>

                        <hr class="my-4">
                        {{-- ✅ Bulk paste section header --}}
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <div class="text-muted text-sm">
                                Paste from CV / Excel and Preview before import. (Excel Columns: N, O, P, Q)
                            </div>

                            <div class="btn-group" role="group" aria-label="Bulk actions">
                                <button type="button" class="btn btn-sm btn-dark"
                                    onclick="previewPublications({{ $member->id }})">
                                    <i class="fa-solid fa-eye me-2"></i> Preview
                                </button>

                                <button type="button" class="btn btn-sm btn-success" id="btnConfirmImport"
                                    onclick="confirmPublications({{ $member->id }})" disabled>
                                    <i class="fa-solid fa-check me-2"></i> Confirm Import
                                </button>
                            </div>
                        </div>

                        {{-- ✅ Editors grid --}}
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



                        {{-- ✅ Manual section header + Add row button --}}
                        {{-- <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                    <div>
                                        <h6 class="mb-0 fw-bold">Or Add Manually</h6>
                                        <p class="text-sm text-muted mb-0">Use “Add Row” to insert multiple publications
                                            quickly.</p>
                                    </div>

                                    <button type="button" class="btn btn-sm btn-dark" id="addPubRow">
                                        <i class="fa-solid fa-plus me-2"></i> Add Row
                                    </button>
                                </div> --}}

                        {{-- ✅ Manual table (table contract styling) --}}
                        {{-- <div class="table-responsive p-0">
                                    <table class="table table-striped mb-0 align-middle" id="pubTable"
                                        style="width:100%">
                                        <thead class="text-center">
                                            <tr>
                                                <th style="width:190px;" class="text-center">Title <span
                                                        class="text-danger">*</span></th>
                                                <th style="width:150px;" class="text-center">Type</th>
                                                <th style="width:200px;" class="text-center">Journal / Conf. name</th>
                                                <th style="width:200px;" class="text-center">Publisher</th>
                                                <th style="width:90px;" class="text-center">Year</th>
                                                <th style="width:150px;" class="text-center">DOI</th>
                                                <th style="width:150px;" class="text-center">URL</th>
                                                <th style="width:60px;" class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody id="pubRows">
                                            @php($rows = old('publications', [[]]))
                                            @foreach ($rows as $i => $row)
                                                <tr class="pub-row">
                                                    <td>
                                                        <input type="text"
                                                            name="publications[{{ $i }}][title]"
                                                            class="form-control form-control-sm rounded-1"
                                                            value="{{ $row['title'] ?? '' }}" required>
                                                    </td>

                                                    <td>
                                                        <select name="publications[{{ $i }}][type]"
                                                            class="form-select form-select-sm rounded-1">
                                                            <option value="">--</option>
                                                            <option value="journal" @selected(($row['type'] ?? '') === 'journal')>Journal
                                                            </option>
                                                            <option value="conference" @selected(($row['type'] ?? '') === 'conference')>
                                                                Conference</option>
                                                            <option value="seminar" @selected(($row['type'] ?? '') === 'seminar')>Seminar
                                                            </option>
                                                            <option value="book_chapter" @selected(($row['type'] ?? '') === 'book_chapter')>
                                                                Book Chapter</option>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <input type="text"
                                                            name="publications[{{ $i }}][journal_or_conference_name]"
                                                            class="form-control form-control-sm rounded-1"
                                                            value="{{ $row['journal_or_conference_name'] ?? '' }}">
                                                    </td>

                                                    <td>
                                                        <input type="text"
                                                            name="publications[{{ $i }}][publisher]"
                                                            class="form-control form-control-sm rounded-1"
                                                            value="{{ $row['publisher'] ?? '' }}">
                                                    </td>

                                                    <td>
                                                        <input type="number"
                                                            name="publications[{{ $i }}][year]"
                                                            class="form-control form-control-sm rounded-1"
                                                            value="{{ $row['year'] ?? '' }}">
                                                    </td>

                                                    <td>
                                                        <input type="text"
                                                            name="publications[{{ $i }}][doi]"
                                                            class="form-control form-control-sm rounded-1"
                                                            value="{{ $row['doi'] ?? '' }}">
                                                    </td>

                                                    <td>
                                                        <input type="text"
                                                            name="publications[{{ $i }}][url]"
                                                            class="form-control form-control-sm rounded-1"
                                                            value="{{ $row['url'] ?? '' }}">
                                                    </td>

                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn btn-sm btn-dark removePubRow">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-4 d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-dark btn-sm">
                                        <i class="fa-solid fa-floppy-disk me-2"></i> Save
                                    </button>

                                    <a href="{{ route('admin.academic.publications.index', $member->id) }}"
                                        class="btn btn-light btn-sm">
                                        Cancel
                                    </a>
                                </div> --}}
                    </form>
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
                                return tinymce.get(id).getContent(); // send html; server strips tags
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
                                rows.push({
                                    type: tr.querySelector('.js-type').value,
                                    // category: tr.querySelector('.js-category').value,
                                    title: tr.querySelector('.js-title').value,
                                    year: tr.querySelector('.js-year').value,
                                    skip: tr.querySelector('.js-skip').checked
                                });
                            });

                        const url = "{{ route('admin.academic.publications.bulk-confirm', ':id') }}"
                            .replace(':id', memberId);

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

                        if (!json.ok) return alert('Import failed');

                        alert('Import successful');
                        window.location.reload();
                    }
                </script>

                <script>
                    (function() {
                        const tbody = document.getElementById('pubRows');
                        const addBtn = document.getElementById('addPubRow');

                        function reindex() {
                            const rows = tbody.querySelectorAll('tr.pub-row');
                            rows.forEach((row, idx) => {
                                row.querySelectorAll('input, select').forEach(el => {
                                    const n = el.getAttribute('name') || '';
                                    el.setAttribute('name', n.replace(/publications\[\d+\]/, 'publications[' + idx +
                                        ']'));
                                });
                            });
                        }

                        addBtn.addEventListener('click', function() {
                            const rows = tbody.querySelectorAll('tr.pub-row');
                            const clone = rows[rows.length - 1].cloneNode(true);
                            clone.querySelectorAll('input').forEach(i => i.value = '');
                            clone.querySelectorAll('select').forEach(s => s.value = '');
                            tbody.appendChild(clone);
                            reindex();
                        });

                        tbody.addEventListener('click', function(e) {
                            if (!e.target.closest('.removePubRow')) return;
                            const rows = tbody.querySelectorAll('tr.pub-row');
                            if (rows.length <= 1) return;
                            e.target.closest('tr.pub-row').remove();
                            reindex();
                        });
                    })();
                </script>
            @endpush

        </div>
    </div>

</x-admin-app-layout>
