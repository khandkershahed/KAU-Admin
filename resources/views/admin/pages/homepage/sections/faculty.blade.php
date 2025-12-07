<div class="js-section-form" data-section-key="faculty" style="display:none;">
    <h4 class="fw-bold mb-4">Faculties / Programs</h4>

    <div class="mb-3">
        <x-metronic.label class="col-form-label fw-bold fs-7">
            Section Title
        </x-metronic.label>
        <input type="text" name="faculty_section_title" class="form-control form-control-sm"
            value="{{ old('faculty_section_title', $faculty->section_title) }}">
    </div>
    <div class="mb-3">
        <x-metronic.label class="col-form-label fw-bold fs-7">
            Section Subtitle
        </x-metronic.label>
        <input type="text" name="faculty_section_subtitle" class="form-control form-control-sm"
            value="{{ old('faculty_section_subtitle', $faculty->section_subtitle) }}">
    </div>

    <div class="text-muted fs-8">
        Faculty cards will be loaded from the Faculties table on the frontend.
    </div>
</div>
