@php
    $setting = $setting ?? null;
    $customSettings = [];

    if ($setting && $setting->custom_settings) {
        $customSettings = is_array($setting->custom_settings)
            ? $setting->custom_settings
            : json_decode($setting->custom_settings, true);
    }
@endphp




<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Language, Currency & Timezone</h5>
    </div>

    <div class="col-md-4 mb-7">
        <label class="form-label">Default Language</label>
        <input type="text" name="default_language" class="form-control"
            value="{{ old('default_language', optional($setting)->default_language) }}">
    </div>

    <div class="col-md-4 mb-7">
        <label class="form-label">Default Currency</label>
        <input type="text" name="default_currency" class="form-control"
            value="{{ old('default_currency', optional($setting)->default_currency) }}">
    </div>

    <div class="col-md-4 mb-7">
        <label class="form-label">System Timezone</label>
        <input type="text" name="system_timezone" class="form-control"
            value="{{ old('system_timezone', optional($setting)->system_timezone) }}">
    </div>

    <div class="col-md-4 mb-7 d-flex align-items-center">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" name="enable_multilanguage" value="1"
                id="enable_multilanguage"
                {{ old('enable_multilanguage', optional($setting)->enable_multilanguage) ? 'checked' : '' }}>
            <label class="form-check-label" for="enable_multilanguage">
                Enable Multilanguage
            </label>
        </div>
    </div>
</div>

<hr>

<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Feature Toggles</h5>
    </div>

    <div class="col-md-3 mb-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1"
                {{ old('maintenance_mode', optional($setting)->maintenance_mode) ? 'checked' : '' }}>
            <label class="form-check-label" for="maintenance_mode">Maintenance Mode</label>
        </div>
    </div>

    <div class="col-md-3 mb-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="enable_user_registration"
                name="enable_user_registration" value="1"
                {{ old('enable_user_registration', optional($setting)->enable_user_registration) ? 'checked' : '' }}>
            <label class="form-check-label" for="enable_user_registration">User Registration</label>
        </div>
    </div>

    <div class="col-md-3 mb-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="enable_email_verification"
                name="enable_email_verification" value="1"
                {{ old('enable_email_verification', optional($setting)->enable_email_verification) ? 'checked' : '' }}>
            <label class="form-check-label" for="enable_email_verification">Email Verification</label>
        </div>
    </div>

    <div class="col-md-3 mb-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="enable_api_access" name="enable_api_access"
                value="1" {{ old('enable_api_access', optional($setting)->enable_api_access) ? 'checked' : '' }}>
            <label class="form-check-label" for="enable_api_access">API Access</label>
        </div>
    </div>

    <div class="col-md-3 mb-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="is_demo" name="is_demo" value="1"
                {{ old('is_demo', optional($setting)->is_demo) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_demo">Demo Mode</label>
        </div>
    </div>

    <div class="col-md-3 mb-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="captcha_enabled" name="captcha_enabled" value="1"
                {{ old('captcha_enabled', optional($setting)->captcha_enabled) ? 'checked' : '' }}>
            <label class="form-check-label" for="captcha_enabled">Captcha Enabled</label>
        </div>
    </div>
</div>

<hr>

<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Advanced Frontend</h5>
    </div>

    <div class="col-md-12 mb-7">
        <label class="form-label">Custom CSS</label>
        <textarea name="custom_css" class="form-control" rows="6">{{ old('custom_css', optional($setting)->custom_css) }}</textarea>
    </div>

    <div class="col-md-12 mb-7">
        <label class="form-label">Custom JS</label>
        <textarea name="custom_js" class="form-control" rows="6">{{ old('custom_js', optional($setting)->custom_js) }}</textarea>
    </div>
</div>

<hr>

<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Custom Settings (Key / Value)</h5>
        <small class="text-muted">These map to the <code>custom_settings</code> JSON column.</small>
    </div>

    @php
        // Example keys based on seeder
        $defaultCustomSettings = [
            'homepage_slider_enabled' => true,
            'max_upload_file_size_mb' => 15,
            'enable_notice_search' => true,
        ];

        $combinedCustom = array_merge($defaultCustomSettings, $customSettings ?? []);
    @endphp

    @foreach ($combinedCustom as $key => $value)
        <div class="col-md-6 mb-7">
            <label class="form-label">{{ Str::title(str_replace('_', ' ', $key)) }}</label>
            <input type="text" name="custom_settings[{{ $key }}]" class="form-control"
                value="{{ old("custom_settings.$key", $value) }}">
        </div>
    @endforeach
</div>
