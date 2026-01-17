@php
    $isEdit = !empty($page);
@endphp

<div class="row g-6">
    <div class="col-lg-8">
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title fw-bold">Page Content</h3>
            </div>
            <div class="card-body">
                <div class="mb-5">
                    <label class="form-label fw-semibold">Menu Link (Required)</label>
                    <select name="nav_item_id" class="form-select form-select-sm" required>
                        <option value="">-- Select a Menu Item (type: page) --</option>
                        @foreach($navItems as $item)
                            <option value="{{ $item->id }}" @selected(old('nav_item_id', $page->nav_item_id ?? '') == $item->id)>
                                {{ $item->label }}  (/{{ $item->slug }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        Create the menu item first: <strong>Office CMS → Menu</strong> → type <code>page</code>.
                        This page will render at: <code>/offices/{{ $office->slug }}/[slug]</code>.
                        If you mark it as <code>Office Home</code>, it becomes <code>/offices/{{ $office->slug }}</code>.
                    </div>
                </div>

                <div class="mb-5">
                    <x-metronic.input name="title" label="Admin Title (Shown in list)" :value="old('title', $page->title ?? '')" required />
                    <div class="form-text">This is the backend title. The frontend menu label comes from the Menu item.</div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Template</label>
                        <select name="template_key" class="form-select form-select-sm">
                            @php $tpl = old('template_key', $page->template_key ?? 'default'); @endphp
                            <option value="default" @selected($tpl==='default')>Default</option>
                            <option value="landing" @selected($tpl==='landing')>Landing</option>
                            <option value="sidebar" @selected($tpl==='sidebar')>Sidebar</option>
                        </select>
                        <div class="form-text">Frontend can switch layouts based on template_key.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            @php $st = old('status', $page->status ?? 'published'); @endphp
                            <option value="published" @selected($st==='published')>Published</option>
                            <option value="draft" @selected($st==='draft')>Draft</option>
                            <option value="archived" @selected($st==='archived')>Archived</option>
                        </select>
                        <div class="form-text">Draft pages should not appear in frontend menus.</div>
                    </div>
                </div>

                <div class="d-flex align-items-center mt-6">
                    <label class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="is_home" value="1" @checked(old('is_home', $page->is_home ?? false)) />
                        <span class="form-check-label fw-semibold">Office Home Page</span>
                    </label>
                    <div class="text-muted ms-4">
                        If enabled, this page will render on <code>/offices/{{ $office->slug }}</code>.
                    </div>
                </div>

                <hr class="my-8"/>

                <h4 class="fw-bold mb-4">Banner (Optional)</h4>

                <div class="row g-4">
                    <div class="col-md-6">
                        <x-metronic.input name="banner_title" label="Banner Title" :value="old('banner_title', $page->banner_title ?? '')" />
                    </div>
                    <div class="col-md-6">
                        <x-metronic.input name="banner_subtitle" label="Banner Subtitle" :value="old('banner_subtitle', $page->banner_subtitle ?? '')" />
                    </div>
                    <div class="col-md-6">
                        <x-metronic.input name="banner_button" label="Banner Button Text" :value="old('banner_button', $page->banner_button ?? '')" />
                    </div>
                    <div class="col-md-6">
                        <x-metronic.input name="banner_button_url" label="Banner Button URL" :value="old('banner_button_url', $page->banner_button_url ?? '')" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">Banner Image</label>
                        <x-metronic.image-input name="banner_image" id="officePageBannerImage"
                            :source="!empty($page?->banner_image) ? asset('storage/' . $page->banner_image) : ''" />
                        <div class="form-text">Used in banner area of the page template.</div>
                    </div>
                </div>

                <hr class="my-8"/>

                <x-metronic.editor name="content" label="Main Content (Optional)" :value="old('content', $page->content ?? '')" rows="12" />
                <div class="form-text mt-2">
                    For advanced layout, use the <strong>Blocks Builder</strong> below (recommended).
                </div>
            </div>
        </div>

        @if($isEdit)
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Blocks Builder</h3>
                </div>
                <div class="card-body">
                    @include('admin.pages.academic.pages.partials.blocks', ['page' => $page, 'blocks' => $blocks ?? $page->blocks])
                </div>
            </div>
        @else
            <div class="alert alert-info">
                Save the page first to enable the Blocks Builder.
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title fw-bold">SEO</h3>
            </div>
            <div class="card-body">
                <x-metronic.input name="meta_title" label="Meta Title" :value="old('meta_title', $page->meta_title ?? '')" />

                <x-metronic.input name="meta_tags" label="Meta Tags" :value="old('meta_tags', $page->meta_tags ?? '')" />

                <x-metronic.textarea name="meta_description" label="Meta Description" :value="old('meta_description', $page->meta_description ?? '')" rows="4" />

                <label class="form-label fw-semibold mb-2">OG Image</label>
                <x-metronic.image-input name="og_image" id="officePageOgImage"
                    :source="!empty($page?->og_image) ? asset('storage/' . $page->og_image) : ''" />
                <div class="form-text">Used for social sharing cards.</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title fw-bold">Ordering</h3>
            </div>
            <div class="card-body">
                <x-metronic.input type="number" name="position" label="Position" :value="old('position', $page->position ?? 0)" />
                <div class="form-text">Lower position shows earlier in lists.</div>
            </div>
        </div>
    </div>
</div>

@if($isEdit)
    @include('admin.pages.academic.pages.partials.builder_js', ['page' => $page])
@endif
