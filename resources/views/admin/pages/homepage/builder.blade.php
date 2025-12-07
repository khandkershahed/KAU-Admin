<x-admin-app-layout :title="'Homepage Builder'">
    <form id="homepageBuilderForm" action="{{ route('admin.homepage.builder.update') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="row g-5 g-xl-10">
            {{-- ========= LEFT: SECTION LIST / ORDER ========= --}}
            <div class="col-xl-4">
                <div class="card card-flash h-100">
                    <div class="card-header align-items-center">
                        <div class="card-title">
                            <h3 class="fw-bold mb-0">Homepage Builder</h3>
                        </div>
                        {{-- <div class="card-toolbar">
                            <button type="button" id="btnHomepagePreview" class="btn btn-sm btn-light-primary">
                                <i class="fa fa-eye me-1"></i> Live Preview
                            </button>
                        </div> --}}
                    </div>

                    <div class="card-body pt-3 pb-5">
                        {{-- GLOBAL FORM (wraps both columns) --}}
                        {{-- SEARCH --}}
                        <div class="mb-4">
                            <x-metronic.label class="col-form-label fw-bold fs-7">
                                Search Sections
                            </x-metronic.label>
                            <div class="position-relative">
                                <input type="text" id="sectionSearch" class="form-control form-control-sm ps-10"
                                    placeholder="Type to filter sections...">
                                <span class="position-absolute top-50 translate-middle-y ms-3 text-muted">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                        </div>

                        {{-- SORTABLE SECTION ROWS --}}
                        <div id="sections-sortable-wrapper">
                            <ul id="sections-sortable" class="list-group list-group-a">
                                @foreach ($sections as $section)
                                    <li class="list-group-item d-flex align-items-center justify-content-between
                                               mb-5 rounded bg-light-primary cursor-pointer js-section-row"
                                        data-id="{{ $section->id }}" data-key="{{ $section->section_key }}">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <span class="me-3 text-gray-500 section-row-handle">
                                                <i class="fa fa-arrows-alt-v"></i>
                                            </span>
                                            <span class="fw-semibold text-capitalize section-title section-row-handle">
                                                {{ str_replace('_', ' ', $section->section_key) }}
                                            </span>
                                        </div>

                                        <div class="d-flex align-items-center gap-3">
                                            <div class="form-check form-switch form-switch-sm mb-0">
                                                <input class="form-check-input js-section-toggle" type="checkbox"
                                                    data-id="{{ $section->id }}"
                                                    name="sections[{{ $section->id }}][is_active]" value="1"
                                                    {{ $section->is_active ? 'checked' : '' }}>

                                            </div>

                                            <button type="button"
                                                class="btn btn-icon btn-sm btn-light-success js-edit-section"
                                                data-key="{{ $section->section_key }}" data-id="{{ $section->id }}"
                                                title="Edit section">
                                                <i class="fa fa-pen"></i>
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            {{-- Order inputs --}}
                            <div id="section-order-container">
                                @foreach ($sections as $section)
                                    <input type="hidden" name="section_order[]" value="{{ $section->id }}">
                                @endforeach
                            </div>
                        </div>

                        {{-- we will reopen form on right side via @php trick, see below --}}
                    </div>
                </div>
            </div>

            {{-- ========= RIGHT: SECTION EDITOR ========= --}}
            <div class="col-xl-8">
                <div class="card card-a h-100">
                    <div class="card-header align-items-center">
                        <div class="card-title">
                            <h3 class="fw-bold mb-0">
                                <span id="currentSectionTitle">Select a section to edit</span>
                            </h3>
                        </div>
                    </div>

                    <div class="card-body pt-4">

                        {{-- ===== BANNER FORM ===== --}}
                        @include('admin.pages.homepage.sections.banner')

                        {{-- ===== VC FORM ===== --}}
                        @include('admin.pages.homepage.sections.vc')

                        {{-- ===== EXPLORE FORM ===== --}}
                        @include('admin.pages.homepage.sections.explore')

                        {{-- ===== FACULTY FORM ===== --}}
                        @include('admin.pages.homepage.sections.faculty')

                        {{-- ===== GLANCE FORM ===== --}}
                        @include('admin.pages.homepage.sections.glance')

                        {{-- ===== ABOUT FORM ===== --}}
                        @include('admin.pages.homepage.sections.about')

                    </div>

                    {{-- SAVE BUTTON (BOTTOM) --}}
                    <div class="card-footer text-end">
                        <button type="submit" form="homepageBuilderForm" class="btn btn-primary btn-sm">
                            <i class="fa fa-save me-2"></i> Save Homepage
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- ========= FULLSCREEN PREVIEW MODAL ========= --}}
    <div class="modal fade" id="homepagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Homepage Live Preview</h5>
                    <button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-outline ki-cross fs-2"></i>
                    </button>
                </div>
                <div class="modal-body p-0 position-relative">

                    <div id="homepagePreviewOverlay"
                        class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center"
                        style="top:0;left:0;background:rgba(255,255,255,0.7);display:none;z-index:5;">
                        <div class="spinner-border spinner-border-sm align-middle"></div>
                        <span class="ms-2 text-muted">Loading preview...</span>
                    </div>

                    <div id="homepagePreviewContent" class="h-100 w-100 overflow-auto p-4">
                        @include('admin.pages.homepage.preview', [
                            'sections' => $sections,
                            'banners' => $banners,
                            'vc' => $vc,
                            'explore' => $explore,
                            'faculty' => $faculty,
                            'glance' => $glance,
                            'about' => $about,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @include('admin.pages.homepage.sections.home_js')
    @endpush


</x-admin-app-layout>
