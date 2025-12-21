<x-admin-app-layout :title="'Edit News'">
    <div class="card card-flush shadow-sm">
        {{-- HEADER --}}
        <div class="card-header align-items-center py-5">
            <div class="d-flex flex-column flex-md-row justify-content-between w-100 align-items-md-center gap-3">
                <div>
                    <h3 class="card-title fw-bold mb-1">
                        Edit News
                    </h3>
                    <span class="text-muted fs-7">
                        Update this news article, its media, content and SEO options.
                    </span>
                </div>

                <div class="card-toolbar">
                    <a href="{{ route('admin.news.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to the list
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            {{-- GLOBAL ALERTS --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-6">
                    <div class="d-flex flex-column">
                        <h5 class="mb-1">Please fix the following errors:</h5>
                        <ul class="mb-0 ps-4">
                            @foreach ($errors->all() as $error)
                                <li class="small">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.news.update', $news->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- MAIN COLUMN --}}
                    <div class="col-lg-8 mb-10">
                        {{-- TABS HEADER --}}
                        <div class="border rounded-3 p-3 mb-4 bg-light">
                            <ul
                                class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 fw-semibold flex-nowrap overflow-auto border-0">
                                <li class="nav-item">
                                    <a class="pb-3 nav-link text-active-primary active" data-bs-toggle="tab"
                                        href="#news_general">
                                        <i class="fas fa-info-circle me-1 fs-6"></i>
                                        General
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="pb-3 nav-link text-active-primary" data-bs-toggle="tab"
                                        href="#news_media">
                                        <i class="fas fa-image me-1 fs-6"></i>
                                        Media
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="pb-3 nav-link text-active-primary" data-bs-toggle="tab"
                                        href="#news_content">
                                        <i class="fas fa-align-left me-1 fs-6"></i>
                                        Content
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="pb-3 nav-link text-active-primary" data-bs-toggle="tab" href="#news_meta">
                                        <i class="fas fa-search me-1 fs-6"></i>
                                        Meta & SEO
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            {{-- GENERAL --}}
                            <div class="tab-pane fade show active" id="news_general" role="tab-panel">
                                <div class="card card-flush border rounded-3 shadow-none mb-6">
                                    <div class="card-header border-0 pb-0">
                                        <div class="card-title">
                                            <h4 class="fw-semibold mb-0">General Information</h4>
                                        </div>
                                    </div>
                                    <div class="card-body pt-4">
                                        <div class="row">
                                            <div class="col-lg-12 mb-6">
                                                <x-metronic.label for="title"
                                                    class="col-form-label required fw-bold fs-7 text-uppercase text-muted">
                                                    Title
                                                </x-metronic.label>
                                                <x-metronic.input id="title" type="text" name="title"
                                                    placeholder="Enter news title" :value="old('title', $news->title)" required />
                                            </div>

                                            <div class="col-lg-6 mb-6">
                                                <x-metronic.label for="author"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Author
                                                </x-metronic.label>
                                                <x-metronic.input id="author" type="text" name="author"
                                                    placeholder="Author name" :value="old('author', $news->author)" />
                                            </div>

                                            <div class="col-lg-3 mb-6">
                                                <x-metronic.label for="published_at"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Published At
                                                </x-metronic.label>
                                                <x-metronic.input id="published_at" type="date" name="published_at"
                                                    :value="old(
                                                        'published_at',
                                                        optional($news->published_at)->format('Y-m-d'),
                                                    )" />
                                            </div>

                                            {{-- <div class="col-lg-3 mb-6">
                                                <x-metronic.label for="read_time"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Read Time (min)
                                                </x-metronic.label>
                                                <x-metronic.input id="read_time" type="number" name="read_time"
                                                    :value="old('read_time', $news->read_time)" />
                                            </div> --}}

                                            <div class="col-lg-6 mb-6">
                                                <x-metronic.label for="category"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Category
                                                </x-metronic.label>
                                                <x-metronic.input id="category" type="text" name="category"
                                                    placeholder="e.g. Campus, Research, Events" :value="old('category', $news->category)" />
                                            </div>

                                            <div class="col-lg-6 mb-6">
                                                <x-metronic.label for="tags"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Tags
                                                </x-metronic.label>
                                                <x-metronic.input id="tags" type="text" name="tags"
                                                    placeholder="comma,separated,tags" :value="old(
                                                        'tags',
                                                        is_array($news->tags)
                                                            ? implode(',', $news->tags)
                                                            : (is_string($news->tags)
                                                                ? $news->tags
                                                                : ''),
                                                    )" />
                                                <div class="text-muted fs-8 mt-1">
                                                    Example: admission, scholarship, seminar
                                                </div>
                                            </div>

                                            <div class="col-lg-12 mb-4">
                                                <x-metronic.label for="summary"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Summary
                                                </x-metronic.label>
                                                <x-metronic.textarea id="summary" name="summary"
                                                    placeholder="Short summary shown in listing">{{ old('summary', $news->summary) }}</x-metronic.textarea>
                                                <div class="text-muted fs-8 mt-1">
                                                    Keep it concise; 1–3 sentences works best.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- MEDIA --}}
                            <div class="tab-pane fade" id="news_media" role="tab-panel">
                                <div class="card card-flush border rounded-3 shadow-none mb-6">
                                    <div class="card-header border-0 pb-0">
                                        <div class="card-title">
                                            <h4 class="fw-semibold mb-0">Media</h4>
                                        </div>
                                    </div>
                                    <div class="card-body pt-4">
                                        <div class="row">
                                            <div class="col-lg-4 mb-6">
                                                <x-metronic.label for="thumb_image"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Thumb Image
                                                </x-metronic.label>
                                                <x-metronic.file-input id="thumb_image" name="thumb_image"
                                                    :source="isset($news->thumb_image)
                                                        ? asset('storage/' . $news->thumb_image)
                                                        : null" />
                                                <div class="text-muted fs-8 mt-1">
                                                    Used in listing cards. Prefer 16:9 ratio.
                                                </div>
                                            </div>

                                            <div class="col-lg-4 mb-6">
                                                <x-metronic.label for="content_image"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Content Image
                                                </x-metronic.label>
                                                <x-metronic.file-input id="content_image" name="content_image"
                                                    :source="isset($news->content_image)
                                                        ? asset('storage/' . $news->content_image)
                                                        : null" />
                                                <div class="text-muted fs-8 mt-1">
                                                    Shown inside the main article body.
                                                </div>
                                            </div>

                                            <div class="col-lg-4 mb-6">
                                                <x-metronic.label for="banner_image"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Banner Image
                                                </x-metronic.label>
                                                <x-metronic.file-input id="banner_image" name="banner_image"
                                                    :source="isset($news->banner_image)
                                                        ? asset('storage/' . $news->banner_image)
                                                        : null" />
                                                <div class="text-muted fs-8 mt-1">
                                                    Displayed at top of news details page.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- CONTENT --}}
                            <div class="tab-pane fade" id="news_content" role="tab-panel">
                                <div class="card card-flush border rounded-3 shadow-none mb-6">
                                    <div class="card-header border-0 pb-0">
                                        <div class="card-title">
                                            <h4 class="fw-semibold mb-0">News Content</h4>
                                        </div>
                                    </div>
                                    <div class="card-body pt-4">
                                        <x-metronic.label for="content"
                                            class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                            Content
                                        </x-metronic.label>
                                        <x-metronic.editor name="content" label="News Content" :value="old('content', $news->content)"
                                            rows="12" />
                                        <div class="text-muted fs-8 mt-1">
                                            Write the full news body. You can use formatting, links and images.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- META / SEO --}}
                            <div class="tab-pane fade" id="news_meta" role="tab-panel">
                                <div class="card card-flush border rounded-3 shadow-none mb-6">
                                    <div class="card-header border-0 pb-0">
                                        <div class="card-title">
                                            <h4 class="fw-semibold mb-0">Meta & SEO</h4>
                                        </div>
                                    </div>
                                    <div class="card-body pt-4">
                                        <div class="row">
                                            <div class="col-lg-12 mb-6">
                                                <x-metronic.label for="meta_title"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Meta Title
                                                </x-metronic.label>
                                                <x-metronic.input id="meta_title" type="text" name="meta_title"
                                                    placeholder="Custom SEO title (optional)" :value="old('meta_title', $news->meta_title)" />
                                                <div class="text-muted fs-8 mt-1">
                                                    If empty, the main title may be used for SEO.
                                                </div>
                                            </div>

                                            <div class="col-lg-12 mb-6">
                                                <x-metronic.label for="meta_description"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Meta Description
                                                </x-metronic.label>
                                                <x-metronic.textarea id="meta_description" name="meta_description"
                                                    placeholder="Short description for search engines">{{ old('meta_description', $news->meta_description) }}</x-metronic.textarea>
                                                <div class="text-muted fs-8 mt-1">
                                                    Ideal length: 120–160 characters.
                                                </div>
                                            </div>

                                            <div class="col-lg-12 mb-6">
                                                <x-metronic.label for="meta_keywords"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Meta Keywords
                                                </x-metronic.label>
                                                <x-metronic.textarea id="meta_keywords" name="meta_keywords"
                                                    placeholder="Comma separated keywords">{{ old('meta_keywords', $news->meta_keywords) }}</x-metronic.textarea>
                                                <div class="text-muted fs-8 mt-1">
                                                    Example: খুলনা কৃষি বিশ্ববিদ্যালয়, ভর্তি, নোটিশ
                                                </div>
                                            </div>

                                            <div class="col-lg-12 mb-6">
                                                <x-metronic.label for="canonical_url"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    Canonical URL
                                                </x-metronic.label>
                                                <x-metronic.input id="canonical_url" type="text"
                                                    name="canonical_url" placeholder="https://example.com/news/slug"
                                                    :value="old('canonical_url', $news->canonical_url)" />
                                            </div>
                                        </div>

                                        <hr class="my-6">

                                        <div class="row">
                                            <div class="col-lg-12 mb-4">
                                                <h5 class="fw-semibold fs-6 mb-1">Open Graph (Social Share)</h5>
                                                <div class="text-muted fs-8 mb-3">
                                                    Customize how the article appears when shared on Facebook,
                                                    Twitter, etc.
                                                </div>
                                            </div>

                                            <div class="col-lg-12 mb-6">
                                                <x-metronic.label for="og_title"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    OG Title
                                                </x-metronic.label>
                                                <x-metronic.input id="og_title" type="text" name="og_title"
                                                    placeholder="Title for social share" :value="old('og_title', $news->og_title)" />
                                            </div>

                                            <div class="col-lg-12 mb-6">
                                                <x-metronic.label for="og_description"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    OG Description
                                                </x-metronic.label>
                                                <x-metronic.textarea id="og_description" name="og_description"
                                                    placeholder="Description for social share">{{ old('og_description', $news->og_description) }}</x-metronic.textarea>
                                            </div>

                                            <div class="col-lg-12 mb-2">
                                                <x-metronic.label for="og_image"
                                                    class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                                    OG Image URL
                                                </x-metronic.label>
                                                <x-metronic.input id="og_image" type="text" name="og_image"
                                                    placeholder="https://example.com/path/to/og-image.jpg"
                                                    :value="old('og_image', $news->og_image)" />
                                                <div class="text-muted fs-8 mt-1">
                                                    If empty, you can fallback to banner image in your frontend logic.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> {{-- /tab-content --}}

                        {{-- ACTIONS --}}
                        <div class="mt-8 d-flex justify-content-end">
                            <a href="{{ route('admin.news.index') }}" class="btn btn-light-danger me-3">
                                <i class="fas fa-times me-1"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">
                                    <i class="fas fa-save me-1"></i>
                                    Update News
                                </span>
                            </button>
                        </div>
                    </div>

                    {{-- SIDE COLUMN --}}
                    <div class="col-lg-4 mb-10">
                        {{-- PUBLISHING CARD (status + featured) --}}
                        <div class="card border rounded-3 mb-5">
                            <div class="card-header border-0 pb-0">
                                <div class="card-title">
                                    <h5 class="fw-semibold mb-0">Publishing</h5>
                                </div>
                            </div>
                            <div class="card-body pt-4">
                                <div class="mb-5">
                                    <x-metronic.label for="status"
                                        class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                        Status
                                    </x-metronic.label>
                                    <x-metronic.select-option id="status" name="status" data-hide-search="true">
                                        <option value="draft"
                                            {{ old('status', $news->status) === 'draft' ? 'selected' : '' }}>
                                            Draft
                                        </option>
                                        <option value="published"
                                            {{ old('status', $news->status) === 'published' ? 'selected' : '' }}>
                                            Published
                                        </option>
                                        <option value="unpublished"
                                            {{ old('status', $news->status) === 'unpublished' ? 'selected' : '' }}>
                                            Unpublished
                                        </option>
                                    </x-metronic.select-option>
                                    <div class="text-muted fs-8 mt-1">
                                        Control visibility on the frontend.
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <x-metronic.label for="is_featured"
                                        class="col-form-label fw-bold fs-7 text-uppercase text-muted">
                                        Featured
                                    </x-metronic.label>
                                    @php
                                        $featuredOld = old('is_featured', $news->is_featured ? '1' : '0');
                                    @endphp
                                    <x-metronic.select-option id="is_featured" name="is_featured"
                                        data-hide-search="true">
                                        <option value="0" {{ $featuredOld == '0' ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ $featuredOld == '1' ? 'selected' : '' }}>Yes
                                        </option>
                                    </x-metronic.select-option>
                                    <div class="text-muted fs-8 mt-1">
                                        Featured news can be highlighted on homepage or sliders.
                                    </div>
                                </div>

                                <div class="separator my-4"></div>

                                <div class="d-flex align-items-center mb-3">
                                    <div class="symbol symbol-35px me-3">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="fas fa-lightbulb text-primary"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Publishing Tips</div>
                                        <div class="text-muted fs-8">
                                            Verify status and featured flag before updating, especially on live site.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- EXTRA SIDE WIDGET (optional) --}}
                        <div class="card border-dashed rounded-3">
                            <div class="card-body py-4">
                                <div class="fw-semibold fs-8 text-muted text-uppercase mb-1">
                                    SEO Reminder
                                </div>
                                <div class="text-muted fs-8">
                                    Updating meta fields for important articles can improve search ranking and
                                    click-through rate.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
