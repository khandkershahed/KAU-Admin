<x-admin-app-layout :title="'Create News'">
    <div class="card card-flash">
        <div class="mt-6 card-header">
            <div class="card-toolbar">
                <a href="{{ route('admin.news.index') }}" class="btn btn-light-info">
                    <span class="svg-icon svg-icon-3"><i class="fas fa-arrow-left"></i></span>
                    Back to the list
                </a>
            </div>
        </div>

        <div class="pt-0 card-body">
            <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-8 gap-7 gap-lg-10">
                        <ul class="border-0 nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x fs-4 fw-semibold mb-n2">
                            <li class="nav-item">
                                <a class="pb-4 nav-link text-active-primary active" data-bs-toggle="tab"
                                    href="#news_general">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="pb-4 nav-link text-active-primary" data-bs-toggle="tab"
                                    href="#news_media">Media</a>
                            </li>
                            <li class="nav-item">
                                <a class="pb-4 nav-link text-active-primary" data-bs-toggle="tab"
                                    href="#news_content">Content</a>
                            </li>
                            <li class="nav-item">
                                <a class="pb-4 nav-link text-active-primary" data-bs-toggle="tab"
                                    href="#news_meta">Meta</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            {{-- GENERAL --}}
                            <div class="tab-pane fade show active" id="news_general" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10 mt-3 py-4 card card-flush">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>General</h2>
                                        </div>
                                    </div>
                                    <div class="pt-0 card-body">
                                        <div class="row">
                                            <div class="col-lg-12 mb-7">
                                                <x-metronic.label for="title"
                                                    class="col-form-label required fw-bold fs-6">
                                                    Title
                                                </x-metronic.label>
                                                <x-metronic.input id="title" type="text" name="title"
                                                    placeholder="Enter news title" :value="old('title')" required />
                                            </div>

                                            <div class="col-lg-6 mb-7">
                                                <x-metronic.label for="author"
                                                    class="col-form-label fw-bold fs-6">
                                                    Author
                                                </x-metronic.label>
                                                <x-metronic.input id="author" type="text" name="author"
                                                    placeholder="Author name" :value="old('author')" />
                                            </div>

                                            <div class="col-lg-3 mb-7">
                                                <x-metronic.label for="published_at"
                                                    class="col-form-label fw-bold fs-6">
                                                    Published At
                                                </x-metronic.label>
                                                <x-metronic.input id="published_at" type="date"
                                                    name="published_at" :value="old('published_at')" />
                                            </div>

                                            <div class="col-lg-3 mb-7">
                                                <x-metronic.label for="read_time"
                                                    class="col-form-label fw-bold fs-6">
                                                    Read Time (min)
                                                </x-metronic.label>
                                                <x-metronic.input id="read_time" type="number"
                                                    name="read_time" :value="old('read_time', 1)" />
                                            </div>

                                            <div class="col-lg-6 mb-7">
                                                <x-metronic.label for="category"
                                                    class="col-form-label fw-bold fs-6">
                                                    Category
                                                </x-metronic.label>
                                                <x-metronic.input id="category" type="text" name="category"
                                                    placeholder="e.g. Campus, Research, Events"
                                                    :value="old('category')" />
                                            </div>

                                            <div class="col-lg-6 mb-7">
                                                <x-metronic.label for="tags"
                                                    class="col-form-label fw-bold fs-6">
                                                    Tags
                                                </x-metronic.label>
                                                <x-metronic.input id="tags" type="text" name="tags"
                                                    placeholder="comma,separated,tags"
                                                    :value="old('tags')" />
                                                <div class="text-muted fs-7">
                                                    Example: admission, scholarship, seminar
                                                </div>
                                            </div>

                                            <div class="col-lg-12 mb-7">
                                                <x-metronic.label for="summary"
                                                    class="col-form-label fw-bold fs-6">
                                                    Summary
                                                </x-metronic.label>
                                                <x-metronic.textarea id="summary" name="summary"
                                                    placeholder="Short summary shown in listing">{{ old('summary') }}</x-metronic.textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- MEDIA --}}
                            <div class="tab-pane fade" id="news_media" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10 py-4 mt-3 card card-flush">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Media</h2>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4 mb-7">
                                                <x-metronic.label for="thumb_image"
                                                    class="col-form-label fw-bold fs-6">
                                                    Thumb Image
                                                </x-metronic.label>
                                                <x-metronic.file-input id="thumb_image" name="thumb_image" />
                                                <div class="text-muted fs-7">Used in listing cards.</div>
                                            </div>

                                            <div class="col-lg-4 mb-7">
                                                <x-metronic.label for="content_image"
                                                    class="col-form-label fw-bold fs-6">
                                                    Content Image
                                                </x-metronic.label>
                                                <x-metronic.file-input id="content_image" name="content_image" />
                                            </div>

                                            <div class="col-lg-4 mb-7">
                                                <x-metronic.label for="banner_image"
                                                    class="col-form-label fw-bold fs-6">
                                                    Banner Image
                                                </x-metronic.label>
                                                <x-metronic.file-input id="banner_image" name="banner_image" />
                                                <div class="text-muted fs-7">Displayed at top of news details.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- CONTENT --}}
                            <div class="tab-pane fade" id="news_content" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10 py-4 mt-3 card card-flush">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>News Content</h2>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <x-metronic.label for="content" class="col-form-label fw-bold fs-6">
                                            Content
                                        </x-metronic.label>
                                        <textarea id="content" name="content" class="form-control tinymce-editor" rows="10">{!! old('content') !!}</textarea>
                                        <div class="text-muted fs-7">Full news body content.</div>
                                    </div>
                                </div>
                            </div>

                            {{-- META / SEO --}}
                            <div class="tab-pane fade" id="news_meta" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10 py-4 mt-3 card card-flush">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Meta / Options</h2>
                                        </div>
                                    </div>
                                    <div class="card-body row">
                                        <div class="col-lg-4 mb-7">
                                            <x-metronic.label for="status"
                                                class="col-form-label fw-bold fs-6">
                                                Status
                                            </x-metronic.label>
                                            <x-metronic.select-option id="status" name="status"
                                                data-hide-search="true">
                                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="unpublished" {{ old('status') === 'unpublished' ? 'selected' : '' }}>Unpublished</option>
                                            </x-metronic.select-option>
                                            <div class="text-muted fs-7">Control visibility on frontend.</div>
                                        </div>

                                        <div class="col-lg-4 mb-7">
                                            <x-metronic.label for="is_featured"
                                                class="col-form-label fw-bold fs-6">
                                                Featured?
                                            </x-metronic.label>
                                            <x-metronic.select-option id="is_featured" name="is_featured"
                                                data-hide-search="true">
                                                <option value="0" {{ old('is_featured') == '0' ? 'selected' : '' }}>No</option>
                                                <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>Yes</option>
                                            </x-metronic.select-option>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> {{-- tab-content --}}

                        <div class="mt-10 d-flex justify-content-end">
                            <a href="{{ route('admin.news.index') }}" class="btn btn-danger me-5">
                                Back To News List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label"> Save News </span>
                            </button>
                        </div>
                    </div>

                    {{-- SIDE COLUMN (could be used for extra options later) --}}
                    <div class="col-4 gap-7 gap-lg-10 mb-7">
                        {{-- Keep empty or put shortcuts, etc. --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
