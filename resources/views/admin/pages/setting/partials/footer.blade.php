<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Footer & Copyright</h5>
    </div>

    <div class="col-md-12 mb-7">
        <label class="form-label">Footer Description</label>
        <textarea name="footer_description" class="form-control" rows="3">{{ old('footer_description', optional($setting)->footer_description) }}</textarea>
    </div>

    <div class="col-md-8 mb-7">
        <label class="form-label">Copyright Title</label>
        <input type="text" name="copyright_title" class="form-control"
               value="{{ old('copyright_title', optional($setting)->copyright_title) }}">
    </div>
    <div class="col-md-4 mb-7">
        <label class="form-label">Website URL</label>
        <input type="url" name="website_url" class="form-control"
               value="{{ old('website_url', optional($setting)->website_url) }}">
    </div>
</div>

<hr>

<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Privacy, Terms & Cookies</h5>
    </div>

    <div class="col-md-6 mb-7">
        <label class="form-label">Privacy Policy URL</label>
        <input type="text" name="privacy_policy_url" class="form-control"
               value="{{ old('privacy_policy_url', optional($setting)->privacy_policy_url) }}">
    </div>
    <div class="col-md-6 mb-7">
        <label class="form-label">Terms & Conditions URL</label>
        <input type="text" name="terms_conditions_url" class="form-control"
               value="{{ old('terms_conditions_url', optional($setting)->terms_conditions_url) }}">
    </div>

    <div class="col-md-12 mb-7">
        <label class="form-label">Cookie Consent Text</label>
        <textarea name="cookie_consent_text" class="form-control" rows="2">{{ old('cookie_consent_text', optional($setting)->cookie_consent_text) }}</textarea>
    </div>

    <div class="col-md-4 mb-2">
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="cookie_consent_enabled" name="cookie_consent_enabled" value="1"
                   {{ old('cookie_consent_enabled', optional($setting)->cookie_consent_enabled) ? 'checked' : '' }}>
            <label class="form-check-label" for="cookie_consent_enabled">
                Enable Cookie Consent
            </label>
        </div>
    </div>
</div>
