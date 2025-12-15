<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row mb-4 mx-aut0">
        <div class="col-md-6">
            <label class="form-label fw-bold">Navigation Item</label>
            <select name="nav_item_id" id="pageNavItemId" class="form-select form-select-sm" data-control="select2"
                data-allow-clear="true">
                <option value="">-- Select Navigation --</option>
                @foreach ($navItems as $item)
                    <option value="{{ $item->id }}" @if ($page && $page->nav_item_id == $item->id) selected @endif>
                        {{ $item->label }} ({{ $item->slug }})
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Selecting a nav item auto-syncs slug + page_key.</small>
        </div>


    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Title</label>
        <input type="text" name="title" id="pageTitle" class="form-control form-control-sm"
            value="{{ old('title', $page->title ?? '') }}" required>
    </div>

    {{-- FLAGS --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <label class="form-label fw-bold">Home Page?</label>
            <select name="is_home" id="is_home" class="form-select form-select-sm">
                <option value="0" @if (!$page || !$page->is_home) selected @endif>No</option>
                <option value="1" @if ($page && $page->is_home) selected @endif>Yes</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">Show Department Boxes?</label>
            <select name="is_department_boxes" id="is_department_boxes" class="form-select form-select-sm">
                <option value="0" @if (!$page || !$page->is_department_boxes) selected @endif>No</option>
                <option value="1" @if ($page && $page->is_department_boxes) selected @endif>Yes</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">Show Faculty Members?</label>
            <select name="is_faculty_members" id="is_faculty_members" class="form-select form-select-sm">
                <option value="0" @if (!$page || !$page->is_faculty_members) selected @endif>No</option>
                <option value="1" @if ($page && $page->is_faculty_members) selected @endif>Yes</option>
            </select>
        </div>
    </div>

    {{-- BANNER --}}
    <div class="row mb-4 gx-3">
        <div class="col-md-4">
            <div class="mb-4">
                <label class="form-label fw-bold">Banner Image</label>
                <x-metronic.image-input name="banner_image" id="bannerImageInput" :source="!empty($page?->banner_image) ? asset('storage/' . $page->banner_image) : ''" />
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="mb-4">
                    <label class="form-label fw-bold">Banner Title</label>
                    <input type="text" name="banner_title" class="form-control form-control-sm"
                        value="{{ old('banner_title', $page->banner_title ?? '') }}">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Banner Button Label</label>
                    <input type="text" name="banner_button" class="form-control form-control-sm"
                        value="{{ old('banner_button', $page->banner_button ?? '') }}">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Banner Button URL</label>
                    <input type="text" name="banner_button_url" class="form-control form-control-sm"
                        value="{{ old('banner_button_url', $page->banner_button_url ?? '') }}">
                </div>
            </div>
        </div>
    </div>


    {{-- EDITOR --}}
    <div class="mb-4">
        <x-metronic.editor name="content" label="Page Content" :value="old('content', $page->content ?? '')" rows="12" />
    </div>

    {{-- META --}}
    <div class="mb-4">
        <label class="form-label fw-bold">Meta Title</label>
        <input type="text" name="meta_title" class="form-control form-control-sm"
            value="{{ old('meta_title', $page->meta_title ?? '') }}">
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Meta Tags</label>
        <input type="text" name="meta_tags" class="form-control form-control-sm"
            value="{{ old('meta_tags', $page->meta_tags ?? '') }}">
        <small class="text-muted">comma separated</small>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Meta Description</label>
        <textarea name="meta_description" rows="3" class="form-control form-control-sm">
            {{ old('meta_description', $page->meta_description ?? '') }}
        </textarea>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">OG Image</label>
        <x-metronic.image-input name="og_image" id="ogImageInput" :source="!empty($page?->og_image) ? asset('storage/' . $page->og_image) : ''" />
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            Save Page
        </button>
    </div>
</form>



{{-- <div class="col-md-3">
            <label class="form-label fw-bold">Slug</label>
            <input type="text" name="slug" id="pageSlug" class="form-control form-control-sm"
                value="{{ old('slug', $page->slug ?? '') }}" required>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">Page Key</label>
            <input type="text" name="page_key" id="pageKey" class="form-control form-control-sm"
                value="{{ old('page_key', $page->page_key ?? '') }}">
        </div> --}}
