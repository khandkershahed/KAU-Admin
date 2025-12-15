@php
    $publications = $member->publications ?? collect();
@endphp

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <div class="fw-bold">{{ $member->name }}</div>
        <div class="text-muted small">UUID: <span class="badge badge-light-primary">{{ $member->uuid ?? '-' }}</span></div>
    </div>
</div>

@if($publications->isEmpty())
    <div class="alert alert-light">
        No publications found.
    </div>
@else
    <ul class="list-group" id="publicationsSortable" data-member-id="{{ $member->id }}">
        @foreach($publications as $pub)
            <li class="list-group-item d-flex justify-content-between align-items-start publication-item"
                data-id="{{ $pub->id }}">
                <div class="d-flex align-items-start">
                    <span class="me-3 pub-sort-handle" style="cursor:grab;">
                        <i class="fa-solid fa-up-down text-primary"></i>
                    </span>

                    <div>
                        <div class="fw-semibold">{{ $pub->title }}</div>
                        <div class="text-muted small">
                            {{ $pub->type ? strtoupper($pub->type) : '—' }}
                            @if($pub->year) • {{ $pub->year }} @endif
                            @if($pub->journal_or_conference_name) • {{ $pub->journal_or_conference_name }} @endif
                        </div>

                        @if($pub->doi)
                            <div class="text-muted small">DOI: {{ $pub->doi }}</div>
                        @endif
                        @if($pub->url)
                            <div class="text-muted small">URL: {{ $pub->url }}</div>
                        @endif
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    {{-- EDIT (inline small form in modal) --}}
                    <button type="button"
                            class="btn btn-sm btn-light-info me-2 editPublicationBtn"
                            data-id="{{ $pub->id }}"
                            data-title="{{ e($pub->title) }}"
                            data-type="{{ $pub->type }}"
                            data-journal="{{ e($pub->journal_or_conference_name) }}"
                            data-publisher="{{ e($pub->publisher) }}"
                            data-year="{{ $pub->year }}"
                            data-doi="{{ e($pub->doi) }}"
                            data-url="{{ e($pub->url) }}">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>

                    <a href="{{ route('admin.academic.publications.destroy', $pub->id) }}" class="delete">
                        <i class="fa-solid fa-trash text-danger fs-4"></i>
                    </a>
                </div>
            </li>
        @endforeach
    </ul>

    {{-- EDIT FORM (inline) --}}
    <div class="mt-4">
        <h6 class="fw-bold">Edit Publication</h6>
        <form method="POST" id="editPublicationForm" class="border rounded p-3">
            @csrf
            @method('PUT')
            <input type="hidden" id="editPublicationId" value="">

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" name="title" id="editPubTitle" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="type" id="editPubType" class="form-select">
                        <option value="">--</option>
                        <option value="journal">Journal</option>
                        <option value="conference">Conference</option>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-semibold">Journal / Conference Name</label>
                    <input type="text" name="journal_or_conference_name" id="editPubJournal" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Publisher</label>
                    <input type="text" name="publisher" id="editPubPublisher" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Year</label>
                    <input type="number" name="year" id="editPubYear" class="form-control" min="1900" max="2100">
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-semibold">DOI</label>
                    <input type="text" name="doi" id="editPubDoi" class="form-control">
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-semibold">URL</label>
                    <input type="text" name="url" id="editPubUrl" class="form-control">
                </div>

                <div class="col-md-12 d-flex justify-content-end">
                    <button class="btn btn-success btn-sm">
                        <i class="fa fa-save me-2"></i>Update Publication
                    </button>
                </div>
            </div>
        </form>
    </div>
@endif
