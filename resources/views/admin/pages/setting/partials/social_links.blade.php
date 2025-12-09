<div class="mb-10">
    <label class="form-label fw-bold">Social Links</label>

    <div id="socialLinksRepeater" class="repeater-wrapper" data-field="social_links">

        @foreach ($setting->social_links ?? [] as $i => $item)
            <div class="repeater-row d-flex gap-3 align-items-center mb-2">

                {{-- Sort Handle --}}
                <span class="sortable-handle cursor-pointer fs-3">☰</span>

                {{-- Icon Picker --}}
                <div class="input-group w-lg-250px">
                    <x-metronic.icon-picker id="socialIcon_{{ $i }}"
                        name="social_links[{{ $i }}][icon_class]" :value="$item['icon_class'] ?? ''" class="form-control-sm"
                        buttonClass="btn-sm btn-outline-info btn-active-info" />
                </div>

                {{-- URL --}}
                <input type="text" name="social_links[{{ $i }}][url]"
                    class="form-control form-control-sm social-url-input" placeholder="https://facebook.com/..."
                    value="{{ $item['url'] ?? '' }}">

                {{-- Delete --}}
                <button type="button" class="btn btn-danger btn-sm delete-row-btn">
                    <i class="fas fa-trash-alt"></i>
                </button>

            </div>
        @endforeach

    </div>

    <button type="button" id="addSocialBtn" class="btn btn-light-primary btn-sm mt-3">
        + Add Social Link
    </button>
</div>

{{-- TEMPLATE for JS --}}
<template id="socialIconTemplate">
    <x-metronic.icon-picker id="SOCIAL_ICON_ID" name="social_links[INDEX][icon_class]" class="form-control-sm"
        buttonClass="btn-sm btn-outline-info btn-active-info" :value="''" />
</template>
