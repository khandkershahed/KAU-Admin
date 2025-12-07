<div class="js-section-form" data-section-key="banner" style="display:none;">
    <h4 class="fw-bold mb-4">Banner Slider</h4>

    <div id="banner_repeater">
        <div data-repeater-list="banners">
            @forelse($banners as $index => $banner)
                <div data-repeater-item class="border rounded p-3 mb-3">
                    <input type="hidden" name="id" value="{{ $banner->id }}">

                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-semibold mb-0">Slide</h6>
                        <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <x-metronic.label class="col-form-label fw-bold fs-7">
                                    Title
                                </x-metronic.label>
                                <input type="text" name="title" class="form-control form-control-sm"
                                    value="{{ $banner->title }}">
                            </div>
                            <div class="mb-3">
                                <x-metronic.label class="col-form-label fw-bold fs-7">
                                    Subtitle
                                </x-metronic.label>
                                <textarea name="subtitle" rows="2" class="form-control form-control-sm">{{ $banner->subtitle }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-7">
                                Background Image
                            </x-metronic.label>
                            <div class="image-input image-input-outline" data-kt-image-input="true">
                                @php
                                    $img = $banner->image_path
                                        ? asset('storage/' . $banner->image_path)
                                        : asset('media/placeholder/blank-image.svg');
                                @endphp
                                <div class="image-input-wrapper w-200px h-150px"
                                    style="background-image: url('{{ $img }}')"></div>

                                <label
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change image">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <input type="file" name="banner_images[{{ $loop->index }}]"
                                        accept=".png,.jpg,.jpeg" />
                                </label>

                                <span
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove image"
                                    onclick="this.closest('[data-repeater-item]').querySelector('input[name=&quot;remove_image&quot;]').value = 1;">
                                    <i class="bi bi-x fs-2"></i>
                                </span>

                                <input type="hidden" name="remove_image" value="0" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-metronic.label class="col-form-label fw-bold fs-7">
                                Button Text
                            </x-metronic.label>
                            <input type="text" name="button_text" class="form-control form-control-sm"
                                value="{{ $banner->button_text }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-metronic.label class="col-form-label fw-bold fs-7">
                                Button URL
                            </x-metronic.label>
                            <input type="text" name="button_url" class="form-control form-control-sm"
                                value="{{ $banner->button_url }}">
                        </div>
                    </div>
                </div>
            @empty
                <div data-repeater-item class="border rounded p-3 mb-3">
                    <input type="hidden" name="id" value="">

                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-semibold mb-0">Slide</h6>
                        <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <x-metronic.label class="col-form-label fw-bold fs-7">
                                    Title
                                </x-metronic.label>
                                <input type="text" name="title" class="form-control form-control-sm">
                            </div>
                            <div class="mb-3">
                                <x-metronic.label class="col-form-label fw-bold fs-7">
                                    Subtitle
                                </x-metronic.label>
                                <textarea name="subtitle" rows="2" class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <x-metronic.label class="col-form-label fw-bold fs-7">
                                Background Image
                            </x-metronic.label>
                            <div class="image-input image-input-outline" data-kt-image-input="true">
                                <div class="image-input-wrapper w-200px h-150px"
                                    style="background-image: url('{{ asset('media/placeholder/blank-image.svg') }}')">
                                </div>

                                <label
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change image">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <input type="file" name="banner_images[0]" accept=".png,.jpg,.jpeg" />
                                </label>

                                <span
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove image"
                                    onclick="this.closest('[data-repeater-item]').querySelector('input[name=&quot;remove_image&quot;]').value = 1;">
                                    <i class="bi bi-x fs-2"></i>
                                </span>

                                <input type="hidden" name="remove_image" value="0" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-metronic.label class="col-form-label fw-bold fs-7">
                                Button Text
                            </x-metronic.label>
                            <input type="text" name="button_text" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-metronic.label class="col-form-label fw-bold fs-7">
                                Button URL
                            </x-metronic.label>
                            <input type="text" name="button_url" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            <small class="text-muted d-block mb-1">
                Max 3 slides will be used.
            </small>
            <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                <i class="fa fa-plus"></i> Add Slide
            </a>
        </div>
    </div>
</div>
