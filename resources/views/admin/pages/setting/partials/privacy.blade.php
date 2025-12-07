<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Privacy Settings</h5>
    </div>
    <div class="col-md-6 mb-7">
        <label class="form-label">Privacy Policy URL</label>
        <input type="text" name="privacy_policy_url" class="form-control"
               value="{{ old('privacy_policy_url', optional($setting)->privacy_policy_url) }}">
    </div>
    <div class="col-md-12 mb-7">
        <label class="form-label">Cookie Consent Text</label>
        <textarea name="cookie_consent_text" class="form-control" rows="3">{{ old('cookie_consent_text', optional($setting)->cookie_consent_text) }}</textarea>
    </div>
</div>
