<x-admin-app-layout :title="'Bulk Add Publications'">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h3 class="card-title fw-bold mb-0">Bulk Add Publications</h3>
                <div class="small text-muted">
                    Member: <b>{{ $member->name }}</b>
                </div>
            </div>
            <a href="{{ route('admin.academic.publications.index', $member->id) }}" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.academic.publications.bulk-store', $member->id) }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="redirect_to" value="{{ route('admin.academic.publications.index', $member->id) }}">

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="text-muted small">Add multiple publications at once. Use the “+ Add Row” button.</div>
                    <button type="button" class="btn btn-sm btn-light-primary" id="addPubRow">
                        <i class="fa fa-plus me-2"></i> Add Row
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="pubTable">
                        <thead>
                            <tr class="fw-bold">
                                {{-- <th style="width:40px;">#</th> --}}
                                <th style="width:190px;">Title <span class="text-danger">*</span></th>
                                <th style="width:150px;">Type</th>
                                <th style="width:200px;">Journal / Conf. name</th>
                                <th style="width:200px;">Publisher</th>
                                <th style="width:90px;">Year</th>
                                <th style="width:150px;">DOI</th>
                                <th style="width:150px;">URL</th>
                                <th style="width:20px;"></th>
                            </tr>
                        </thead>
                        <tbody id="pubRows">
                            @php($rows = old('publications', [ [] ]))
                            @foreach($rows as $i => $row)
                                <tr class="pub-row">
                                    {{-- <td class="row-no">{{ $loop->iteration }}</td> --}}
                                    <td>
                                        <input type="text" name="publications[{{ $i }}][title]" class="form-control form-control-sm rounded-1" value="{{ $row['title'] ?? '' }}" required>
                                    </td>
                                    <td>
                                        <select name="publications[{{ $i }}][type]" class="form-select form-select-sm">
                                            <option value="">--</option>
                                            <option value="journal" @selected(($row['type'] ?? '')==='journal')>Journal</option>
                                            <option value="conference" @selected(($row['type'] ?? '')==='conference')>Conference</option>
                                            <option value="seminar" @selected(($row['type'] ?? '')==='seminar')>Seminar</option>
                                            <option value="book_chapter" @selected(($row['type'] ?? '')==='book_chapter')>Book Chapter</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="publications[{{ $i }}][journal_or_conference_name]" class="form-control form-control-sm rounded-1" value="{{ $row['journal_or_conference_name'] ?? '' }}"></td>
                                    <td><input type="text" name="publications[{{ $i }}][publisher]" class="form-control form-control-sm rounded-1" value="{{ $row['publisher'] ?? '' }}"></td>
                                    <td><input type="number" name="publications[{{ $i }}][year]" class="form-control form-control-sm rounded-1" value="{{ $row['year'] ?? '' }}"></td>
                                    <td><input type="text" name="publications[{{ $i }}][doi]" class="form-control form-control-sm rounded-1" value="{{ $row['doi'] ?? '' }}"></td>
                                    <td><input type="text" name="publications[{{ $i }}][url]" class="form-control form-control-sm rounded-1" value="{{ $row['url'] ?? '' }}"></td>
                                    {{-- <td><input type="number" name="publications[{{ $i }}][position]" class="form-control form-control-sm rounded-1" value="{{ $row['position'] ?? '' }}"></td> --}}
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-light-danger removePubRow"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk me-2"></i> Save</button>
                    <a href="{{ route('admin.academic.publications.index', $member->id) }}" class="btn btn-light btn-sm ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (function(){
                const tbody = document.getElementById('pubRows');
                const addBtn = document.getElementById('addPubRow');

                function reindex(){
                    const rows = tbody.querySelectorAll('tr.pub-row');
                    rows.forEach((row, idx) => {
                        row.querySelector('.row-no').textContent = (idx + 1);
                        row.querySelectorAll('input, select').forEach(el => {
                            const n = el.getAttribute('name') || '';
                            el.setAttribute('name', n.replace(/publications\[\d+\]/, 'publications[' + idx + ']'));
                        });
                    });
                }

                addBtn.addEventListener('click', function(){
                    const rows = tbody.querySelectorAll('tr.pub-row');
                    const clone = rows[rows.length - 1].cloneNode(true);
                    clone.querySelectorAll('input').forEach(i => i.value = '');
                    clone.querySelectorAll('select').forEach(s => s.value = '');
                    tbody.appendChild(clone);
                    reindex();
                });

                tbody.addEventListener('click', function(e){
                    if(!e.target.closest('.removePubRow')) return;
                    const rows = tbody.querySelectorAll('tr.pub-row');
                    if(rows.length <= 1) return;
                    e.target.closest('tr.pub-row').remove();
                    reindex();
                });
            })();
        </script>
    @endpush
</x-admin-app-layout>
