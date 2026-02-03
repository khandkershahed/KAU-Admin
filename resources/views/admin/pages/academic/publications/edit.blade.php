<x-admin-app-layout :title="'Edit Publication'">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold mb-0">Edit Publication</h3>
            <a href="{{ route('admin.academic.publications.index', $member->id) }}" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.academic.publications.update', $publication->id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_to" value="{{ route('admin.academic.publications.index', $member->id) }}">

                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm" value="{{ old('title', $publication->title) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Type</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">--</option>
                            <option value="journal" @selected(old('type',$publication->type)==='journal')>Journal</option>
                            <option value="conference" @selected(old('type',$publication->type)==='conference')>Conference</option>
                            <option value="seminar" @selected(old('type',$publication->type)==='seminar')>Seminar</option>
                            <option value="book_chapter" @selected(old('type',$publication->type)==='book_chapter')>Book Chapter</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Journal / Conf.</label>
                        <input type="text" name="journal_or_conference_name" class="form-control form-control-sm" value="{{ old('journal_or_conference_name', $publication->journal_or_conference_name) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Publisher</label>
                        <input type="text" name="publisher" class="form-control form-control-sm" value="{{ old('publisher', $publication->publisher) }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Year</label>
                        <input type="number" name="year" class="form-control form-control-sm" value="{{ old('year', $publication->year) }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Position</label>
                        <input type="number" name="position" class="form-control form-control-sm" value="{{ old('position', $publication->position) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">DOI</label>
                        <input type="text" name="doi" class="form-control form-control-sm" value="{{ old('doi', $publication->doi) }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">URL</label>
                        <input type="text" name="url" class="form-control form-control-sm" value="{{ old('url', $publication->url) }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk me-2"></i> Update</button>
                    <a href="{{ route('admin.academic.publications.index', $member->id) }}" class="btn btn-light btn-sm ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
