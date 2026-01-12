<x-admin-app-layout :title="'Add Section - ' . $office->title">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold mb-0">Add New Section</h3>
            <a href="{{ route('admin.administration.office.page', $office->slug) }}" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.administration.section.store') }}" method="POST" novalidate>
                @csrf

                <input type="hidden" name="office_id" value="{{ $office->id }}">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Section Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm"
                            value="{{ old('title') }}" placeholder="Enter section title..." required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Section Type</label>
                        <select name="section_type" class="form-select form-select-sm">
                            <option value="content" @if (old('section_type') == 'content') selected @endif>Content</option>
                            <option value="overview" @if (old('section_type') == 'overview') selected @endif>Overview</option>
                            <option value="message" @if (old('section_type') == 'message') selected @endif>Message</option>
                            <option value="officer_cards" @if (old('section_type') == 'officer_cards') selected @endif>Officer Cards</option>
                            <option value="alumni_cards" @if (old('section_type') == 'alumni_cards') selected @endif>Alumni Cards</option>
                            <option value="download_list" @if (old('section_type') == 'download_list') selected @endif>Download List</option>
                            <option value="faq" @if (old('section_type') == 'faq') selected @endif>FAQ</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <x-metronic.label for="content" class="col-form-label fw-bold fs-6">
                            {{ __('Page Content') }}
                        </x-metronic.label>
                        <x-metronic.editor name="content" label="Page Content" :value="old('content')" rows="12" />
                    
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Tab Label</label>
                        <input type="text" name="tab_label" class="form-control form-control-sm"
                            value="{{ old('tab_label') }}" placeholder="Left menu label...">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Tab Subtitle</label>
                        <input type="text" name="tab_subtitle" class="form-control form-control-sm"
                            value="{{ old('tab_subtitle') }}" placeholder="Short subtitle...">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Tab Icon</label>
                        <input type="text" name="tab_icon" class="form-control form-control-sm"
                            value="{{ old('tab_icon') }}" placeholder="e.g. fa-solid fa-circle-info">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Tab Color</label>
                        <input type="text" name="tab_color" class="form-control form-control-sm"
                            value="{{ old('tab_color') }}" placeholder="e.g. primary / success / #hex">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Extra JSON (optional)</label>
                        <textarea name="extra_json" rows="6" class="form-control form-control-sm"
                            placeholder='{"quote":"...","cards":[{"title":"Our Vision","text":"..."},{"title":"Our Mission","text":"..."}]}'>{{ old('extra_json') }}</textarea>
                        <small class="text-muted">This is optional. Only use if you need advanced custom data for this section.</small>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Save
                    </button>
                    <a href="{{ route('admin.administration.office.page', $office->slug) }}" class="btn btn-light btn-sm ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
