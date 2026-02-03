@php
    $isEdit = !empty($page);
    $tpl = old('template_key', $page->template_key ?? 'default');
    $st = old('status', $page->status ?? 'published');
@endphp

{{-- Compact Tabbed Form --}}
<ul class="nav nav-tabs nav-line-tabs mb-6 fs-6" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active fw-semibold text-black" data-bs-toggle="tab" href="#tab_overview" role="tab">
            Overview
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#tab_content" role="tab">
            Content
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#tab_banner" role="tab">
            Banner
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#tab_seo" role="tab">
            SEO
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#tab_blocks" role="tab">
            Blocks
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link fw-semibold text-black" data-bs-toggle="tab" href="#tab_ordering" role="tab">
            Ordering
        </a>
    </li>
</ul>

<div class="tab-content">

    {{-- =======================
        TAB: OVERVIEW
    ======================== --}}
    <div class="tab-pane fade show active" id="tab_overview" role="tabpanel">
        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Navigation Item <span class="text-danger">*</span></label>
                <select name="nav_item_id" id="pageNavItemId" class="form-select form-select-sm" data-control="select2"
                    data-allow-clear="true" required>
                    <option value="">-- Select Navigation --</option>
                    @foreach ($navItems as $item)
                        <option value="{{ $item->id }}" @selected(old('nav_item_id', $page->nav_item_id ?? '') == $item->id)>
                            {{ $item->label }} ({{ $item->slug }})
                        </option>
                    @endforeach
                </select>
                <div class="form-text">
                    This connects the page to the frontend menu URL for the selected site.
                    The system keeps <code>slug</code> and <code>page_key</code> synced with the navigation item.
                </div>
            </div>

            <div class="col-md-8">
                <label class="form-label fw-semibold">Title (Frontend Heading) <span class="text-danger">*</span></label>
                <x-metronic.input name="title" :value="old('title', $page->title ?? '')" required />
                <div class="form-text">Shown as the main heading on the frontend.</div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Template</label>
                <select name="template_key" class="form-select form-select-sm">
                    <option value="default" @selected($tpl === 'default')>Default</option>
                    <option value="landing" @selected($tpl === 'landing')>Landing</option>
                    <option value="sidebar" @selected($tpl === 'sidebar')>Sidebar</option>
                </select>
                <div class="form-text">Frontend may change layout based on <code>template_key</code>.</div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="published" @selected($st === 'published')>Published</option>
                    <option value="draft" @selected($st === 'draft')>Draft</option>
                    <option value="archived" @selected($st === 'archived')>Archived</option>
                </select>
                <div class="form-text">Draft pages should not appear in frontend menus.</div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Home Page?</label>
                <select name="is_home" class="form-select form-select-sm">
                    <option value="0" @selected(!old('is_home', $page->is_home ?? false))>No</option>
                    <option value="1" @selected(old('is_home', $page->is_home ?? false))>Yes</option>
                </select>
                <div class="form-text">Only one page per site should be marked as Home.</div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Show Department Boxes?</label>
                <select name="is_department_boxes" class="form-select form-select-sm">
                    <option value="0" @selected(!old('is_department_boxes', $page->is_department_boxes ?? false))>No</option>
                    <option value="1" @selected(old('is_department_boxes', $page->is_department_boxes ?? false))>Yes</option>
                </select>
                <div class="form-text">Shows department grid for this site.</div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Show Faculty Members?</label>
                <select name="is_faculty_members" class="form-select form-select-sm">
                    <option value="0" @selected(!old('is_faculty_members', $page->is_faculty_members ?? false))>No</option>
                    <option value="1" @selected(old('is_faculty_members', $page->is_faculty_members ?? false))>Yes</option>
                </select>
                <div class="form-text">Shows staff listing section on the page.</div>
            </div>

            <div class="col-md-12">
                <div class="alert alert-light py-3 mb-0">
                    <div class="fw-semibold mb-1">Tip</div>
                    <div class="small text-muted">Fill Overview first, then Content and Banner. Use Blocks for complex
                        layouts.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- =======================
        TAB: CONTENT
    ======================== --}}
    <div class="tab-pane fade" id="tab_content" role="tabpanel">
        <div class="row g-4">
            <div class="col-md-12">
                <x-metronic.editor name="content" label="Main Content" :value="old('content', $page->content ?? '')" rows="12" />
                <div class="form-text mt-2">
                    For advanced layouts, use the <strong>Blocks</strong> tab (recommended).
                </div>
            </div>
        </div>
    </div>

    {{-- =======================
        TAB: BANNER
    ======================== --}}
    <div class="tab-pane fade" id="tab_banner" role="tabpanel">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Banner Title</label>
                <x-metronic.input name="banner_title" :value="old('banner_title', $page->banner_title ?? '')" />
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Banner Subtitle</label>
                <x-metronic.input name="banner_subtitle" :value="old('banner_subtitle', $page->banner_subtitle ?? '')" />
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Banner Button Text</label>
                <x-metronic.input name="banner_button" :value="old('banner_button', $page->banner_button ?? '')" />
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Banner Button URL</label>
                <x-metronic.input name="banner_button_url" :value="old('banner_button_url', $page->banner_button_url ?? '')" />
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-2">Banner Image</label>
                <x-metronic.image-input name="banner_image" id="academicPageBannerImage" :source="!empty($page?->banner_image) ? asset('storage/' . $page->banner_image) : ''" />
                <div class="form-text">Shown at the top of the page.</div>
            </div>
        </div>
    </div>

    {{-- =======================
        TAB: SEO
    ======================== --}}
    <div class="tab-pane fade" id="tab_seo" role="tabpanel">
        <div class="row g-4">
            <div class="col-md-12">
                <label class="form-label fw-semibold">Meta Title</label>
                <x-metronic.input name="meta_title" :value="old('meta_title', $page->meta_title ?? '')" />
            </div>

            <div class="col-md-12">
                <label class="form-label fw-semibold">Meta Tags</label>
                <x-metronic.input name="meta_tags" :value="old('meta_tags', $page->meta_tags ?? '')" />
                <div class="form-text">Comma separated keywords. Example: agriculture, research, faculty</div>
            </div>

            <div class="col-md-12">
                <label class="form-label fw-semibold">Meta Description</label>
                <x-metronic.textarea name="meta_description" :value="old('meta_description', $page->meta_description ?? '')"
                    rows="4" />
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold mb-2">OG Image</label>
                <x-metronic.image-input name="og_image" id="academicPageOgImage" :source="!empty($page?->og_image) ? asset('storage/' . $page->og_image) : ''" />
                <div class="form-text">Used for social sharing cards.</div>
            </div>
        </div>
    </div>

    {{-- =======================
        TAB: BLOCKS
    ======================== --}}
    <div class="tab-pane fade" id="tab_blocks" role="tabpanel">
        @if ($isEdit)
            <div class="mb-4">
                <div class="alert alert-warning py-3 mb-0">
                    <div class="fw-semibold mb-1">Blocks Builder</div>
                    <div class="small">Use blocks to build rich pages (hero, text, image, table, etc.). Drag to sort.
                    </div>
                </div>
            </div>

            @include('admin.pages.academic.pages.partials.blocks', [
                'page' => $page,
                'blocks' => $blocks ?? $page->blocks,
            ])
        @else
            <div class="alert alert-info mb-0">
                Save the page first to enable the Blocks Builder.
            </div>
        @endif
    </div>

    {{-- =======================
        TAB: ORDERING
    ======================== --}}
    <div class="tab-pane fade" id="tab_ordering" role="tabpanel">
        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Position</label>
                <x-metronic.input type="number" name="position" :value="old('position', $page->position ?? 0)" />
                <div class="form-text">Lower position appears earlier in lists.</div>
            </div>
        </div>
    </div>

</div>

@if ($isEdit)
    @include('admin.pages.academic.pages.partials.builder_js', ['page' => $page])
@endif
