<div class="js-section-form" data-section-key="about" style="display:none;">
    <h4 class="fw-bold mb-4">About Section</h4>

    <div class="mb-3">
        <x-metronic.label class="col-form-label fw-bold fs-7">
            Badge
        </x-metronic.label>
        <input type="text" name="about_badge" class="form-control form-control-sm"
            value="{{ old('about_badge', $about->badge) }}">
    </div>
    <div class="mb-3">
        <x-metronic.label class="col-form-label fw-bold fs-7">
            Title
        </x-metronic.label>
        <input type="text" name="about_title" class="form-control form-control-sm"
            value="{{ old('about_title', $about->title) }}">
    </div>
    <div class="mb-3">
        <x-metronic.label class="col-form-label fw-bold fs-7">
            Subtitle
        </x-metronic.label>
        <input type="text" name="about_subtitle" class="form-control form-control-sm"
            value="{{ old('about_subtitle', $about->subtitle) }}">
    </div>
    <div class="mb-3">
        <x-metronic.label class="col-form-label fw-bold fs-7">
            Description
        </x-metronic.label>
        <textarea name="about_description" rows="4" class="form-control form-control-sm">{{ old('about_description', $about->description) }}</textarea>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-metronic.label class="col-form-label fw-bold fs-7">
                Experience Badge
            </x-metronic.label>
            <input type="text" name="about_experience_badge" class="form-control form-control-sm"
                value="{{ old('about_experience_badge', $about->experience_badge) }}">
        </div>
        <div class="col-md-6 mb-3">
            <x-metronic.label class="col-form-label fw-bold fs-7">
                Experience Title
            </x-metronic.label>
            <input type="text" name="about_experience_title" class="form-control form-control-sm"
                value="{{ old('about_experience_title', $about->experience_title) }}">
        </div>
    </div>

    <div class="mb-3">
        <x-metronic.label class="col-form-label fw-bold fs-7">
            Images (max 5)
        </x-metronic.label>

        <div class="d-flex flex-wrap gap-4">
            @php $aboutImages = $about->images_array ?? []; @endphp
            @for ($i = 1; $i <= 5; $i++)
                @php
                    $idx = $i - 1;
                    $path = $aboutImages[$idx] ?? null;
                    $url = $path ? asset('storage/' . $path) : asset('media/placeholder/blank-image.svg');
                @endphp
                <div class="image-input image-input-outline mb-7 me-7" data-kt-image-input="true">
                    <div class="image-input-wrapper w-150px h-120px"
                        style="background-image: url('{{ $url }}')">
                    </div>

                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                        data-kt-image-input-action="change">
                        <i class="bi bi-pencil-fill fs-7"></i>
                        <input type="file" name="about_image_{{ $i }}" accept=".png,.jpg,.jpeg" />
                        <input type="hidden" name="about_image_{{ $i }}_remove" value="0" />
                    </label>

                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                        data-kt-image-input-action="remove"
                        onclick="document.querySelector('input[name=about_image_{{ $i }}_remove]').value=1;">
                        <i class="bi bi-x fs-2"></i>
                    </span>
                </div>
            @endfor
        </div>
    </div>
</div>
