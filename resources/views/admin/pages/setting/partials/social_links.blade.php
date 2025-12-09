@php
    $socialLinks = $setting->social_links ?? [];
@endphp

<div class="mb-10">
    <label class="form-label fw-bold">Social Links</label>

    <div id="socialLinksRepeater" class="repeater-wrapper" data-field="social_links">
        @foreach ($socialLinks as $i => $item)
            <div class="repeater-row d-flex gap-2 align-items-center mb-2">

                <span class="sortable-handle cursor-pointer">☰</span>

                <div class="input-group">
                    <x-metronic.icon-picker
                        id="socialIcon_{{ $i }}"
                        class="form-control-sm"
                        buttonClass="btn-sm btn-outline-info btn-active-info"
                        name="social_links[{{ $i }}][icon_class]"
                        :value="$item['icon_class'] ?? ''"
                    />
                </div>

                <input type="text"
                       name="social_links[{{ $i }}][url]"
                       class="form-control form-control-sm"
                       placeholder="URL"
                       value="{{ $item['url'] ?? '' }}">

                <button type="button" class="btn btn-danger btn-sm delete-row-btn">
                    <i class="fas fa-trash-alt"></i>
                </button>

            </div>
        @endforeach
    </div>

    <button type="button" id="addSocialBtn" class="btn btn-light-primary btn-sm mt-3">+ Add Social Link</button>
</div>


{{-- THIS HIDDEN TEMPLATE ALLOWS ICON PICKER TO WORK INSIDE JS --}}
<div id="socialIconTemplate" class="d-none">
    {!! str_replace("\n", "", view('components.metronic.icon-picker', [
        'id'   => 'socialIcon_INDEX',
        'name' => 'social_links[INDEX][icon_class]',
        'value'=> ''
    ])->render()) !!}
</div>
