<div class="row mb-10">

    {{-- Website Name --}}
    <div class="col-lg-6 mb-5">
        <label class="form-label fw-bold">Website Name</label>
        <input type="text" name="website_name" class="form-control" value="{{ $setting->website_name }}">
    </div>
    <div class="col-lg-6 mb-5">
        <label class="form-label fw-bold">Website Name Bangla</label>
        <input type="text" name="website_name_bn" class="form-control" value="{{ $setting->website_name_bn }}">
    </div>

    {{-- Site Title --}}
    <div class="col-lg-6 mb-5">
        <label class="form-label fw-bold">Site Title</label>
        <input type="text" name="site_title" class="form-control" value="{{ $setting->site_title }}">
    </div>

    {{-- Motto --}}
    <div class="col-lg-12 mb-5">
        <label class="form-label fw-bold">Site Motto</label>
        <textarea name="site_motto" rows="2" class="form-control">{{ $setting->site_motto }}</textarea>
    </div>

    {{-- Theme Color --}}
    <div class="col-lg-4 mb-5">
        <label class="form-label fw-bold">Theme Color</label>
        <x-metronic.color-picker id="themeColor" name="theme_color" :value="old('theme_color', optional($setting)->theme_color)" class="form-control-sm"
            buttonClass="btn-sm" />

    </div>

    {{-- Dark Mode --}}
    <div class="col-lg-4 mb-5">
        <label class="form-label fw-bold d-block">Dark Mode</label>

        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input" type="checkbox" name="dark_mode" value="1"
                {{ $setting->dark_mode ? 'checked' : '' }}>
        </div>
    </div>

    {{-- Website URL --}}
    <div class="col-lg-4 mb-5">
        <label class="form-label fw-bold">Website URL</label>
        <input type="text" name="website_url" class="form-control" value="{{ $setting->website_url }}">
    </div>

    {{-- Logos --}}
    <div class="col-md-4 mb-7">
        <label class="form-label">Logo (White Path)</label>
        <x-metronic.file-input name="site_logo_white" class="form-control-sm" :value="old('site_logo_white', optional($setting)->site_logo_white)"></x-metronic.file-input>

    </div>
    <div class="col-md-4 mb-7">
        <label class="form-label">Logo (Black Path)</label>
        <x-metronic.file-input name="site_logo_black" class="form-control-sm" :value="old('site_logo_black', optional($setting)->site_logo_black)"></x-metronic.file-input>
    </div>
    <div class="col-md-4 mb-7">
        <label class="form-label">Favicon</label>
        <x-metronic.file-input name="site_favicon" class="form-control-sm" :value="old('site_favicon', optional($setting)->site_favicon)"></x-metronic.file-input>

    </div>

</div>

<div class="row">
    {{-- Emails --}}
    <div class="mb-10">
        <label class="form-label fw-bold">Emails</label>

        <div id="emailRepeater" class="repeater-wrapper" data-field="emails">
            @foreach ($setting->emails ?? [] as $i => $email)
                <div class="repeater-row d-flex gap-2 align-items-center mb-2">
                    <input type="text" name="emails[{{ $i }}][title]" class="form-control form-control-sm w-lg-350px"
                        placeholder="Title" value="{{ $email['title'] ?? '' }}">
                    <input type="email" name="emails[{{ $i }}][email]" class="form-control form-control-sm"
                        placeholder="Email" value="{{ $email['email'] ?? '' }}">
                    <button type="button" class="btn btn-danger btn-sm delete-row-btn">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            @endforeach
        </div>

        <button type="button" id="addEmailBtn" class="btn btn-light-primary btn-sm mt-3">+ Add Email</button>
    </div>

    {{-- Phones --}}
    <div class="mb-10">
        <label class="form-label fw-bold">Phone Numbers</label>

        <div id="phoneRepeater" class="repeater-wrapper" data-field="phone">
            @foreach ($setting->phone ?? [] as $i => $phone)
                <div class="repeater-row d-flex gap-2 align-items-center mb-2">
                    <input type="text" name="phone[{{ $i }}][title]" class="form-control form-control-sm w-lg-350px"
                        placeholder="Title" value="{{ $phone['title'] ?? '' }}">
                    <input type="text" name="phone[{{ $i }}][phone]" class="form-control form-control-sm w-lg-350px"
                        placeholder="Phone" value="{{ $phone['phone'] ?? '' }}">
                    <button type="button" class="btn btn-danger btn-sm delete-row-btn">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            @endforeach
        </div>

        <button type="button" id="addPhoneBtn" class="btn btn-light-primary btn-sm mt-3">+ Add Phone</button>
    </div>

    {{-- Addresses --}}
    <div class="mb-10">
        <label class="form-label fw-bold">Addresses</label>

        <div id="addressRepeater" class="repeater-wrapper" data-field="addresses">
            @foreach ($setting->addresses ?? [] as $i => $addr)
                <div class="repeater-row d-flex gap-2 align-items-center mb-2">
                    <input type="text" name="addresses[{{ $i }}][title]" class="form-control form-control-sm w-lg-400px"
                        placeholder="Title" value="{{ $addr['title'] ?? '' }}">
                    <input type="text" name="addresses[{{ $i }}][address]" class="form-control form-control-sm"
                        placeholder="Address" value="{{ $addr['address'] ?? '' }}">
                    <button type="button" class="btn btn-danger btn-sm delete-row-btn">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            @endforeach
        </div>

        <button type="button" id="addAddressBtn" class="btn btn-light-primary btn-sm mt-3">+ Add Address</button>
    </div>

</div>
