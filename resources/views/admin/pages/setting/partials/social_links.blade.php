<div class="mb-10">
    <label class="form-label fw-bold">Social Links</label>

    <div id="socialLinksRepeater" class="repeater-wrapper" data-field="social_links">
        @foreach ($setting->social_links ?? [] as $i => $item)
            <div class="repeater-row d-flex gap-2 align-items-center mb-2">

                <span class="sortable-handle cursor-pointer">☰</span>

                <div class="input-group">
                    <x-metronic.icon-picker id="socialIcon_{{ $i }}" class="form-control-sm" buttonClass="btn-sm btn-outline-info btn-active-info"
                        name="social_links[{{ $i }}][icon_class]" :value="$item['icon_class'] ?? ''" />


                    {{-- <input type="text" name="social_links[{{ $i }}][icon_class]"
                        class="form-control icon-input" placeholder="fa-brands fa-facebook"
                        value="{{ $item['icon_class'] ?? '' }}">

                    <button type="button" class="btn btn-outline-secondary icon-picker-btn">
                        <i class="{{ $item['icon_class'] ?? 'fa fa-icons' }}"></i>
                    </button> --}}
                </div>

                <input type="text" name="social_links[{{ $i }}][url]" class="form-control"
                    placeholder="URL" value="{{ $item['url'] ?? '' }}">

                <button type="button" class="btn btn-danger btn-sm delete-row-btn">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        @endforeach
    </div>

    <button type="button" id="addSocialBtn" class="btn btn-light-primary btn-sm mt-3">+ Add Social Link</button>
</div>


