<div class="js-section-form" data-section-key="glance" style="display:none;">
    <h4 class="fw-bold mb-4">KAU at a Glance</h4>

    <div class="mb-3">
        <x-metronic.label class="col-form-label fw-bold fs-7">
            Section Title
        </x-metronic.label>
        <input type="text" name="glance_section_title" class="form-control form-control-sm"
            value="{{ old('glance_section_title', $glance->section_title) }}">
    </div>
    <div class="mb-3">
        <x-metronic.label class="col-form-label fw-bold fs-7">
            Section Subtitle
        </x-metronic.label>
        <input type="text" name="glance_section_subtitle" class="form-control form-control-sm"
            value="{{ old('glance_section_subtitle', $glance->section_subtitle) }}">
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold fs-7">Stat Boxes</label>
        <div id="glance_repeater">
            <div data-repeater-list="glance_items">
                @forelse($glanceItems as $item)
                    <div data-repeater-item class="row g-2 mb-3 align-items-end border rounded p-2">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="col-md-3">
                            <input type="text" name="icon" class="form-control form-control-sm"
                                placeholder="Icon class" value="{{ $item->icon }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="title" class="form-control form-control-sm"
                                placeholder="Title" value="{{ $item->title }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="number" class="form-control form-control-sm"
                                placeholder="Number" value="{{ $item->number }}">
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div data-repeater-item class="row g-2 mb-3 align-items-end border rounded p-2">
                        <input type="hidden" name="id" value="">
                        <div class="col-md-3">
                            <input type="text" name="icon" class="form-control form-control-sm"
                                placeholder="Icon class">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="title" class="form-control form-control-sm"
                                placeholder="Title">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="number" class="form-control form-control-sm"
                                placeholder="Number">
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
            <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                <i class="fa fa-plus"></i> Add Stat
            </a>
        </div>
    </div>
</div>
