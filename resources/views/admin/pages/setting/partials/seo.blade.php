<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">SEO Meta</h5>
    </div>

    <div class="col-md-6 mb-7">
        <label class="form-label">Site URL</label>
        <input type="url" name="site_url" class="form-control"
            value="{{ old('site_url', optional($setting)->site_url) }}">
    </div>

    <div class="col-md-6 mb-7">
        <label class="form-label">Meta Title</label>
        <input type="text" name="meta_title" class="form-control"
            value="{{ old('meta_title', optional($setting)->meta_title) }}">
    </div>

    <div class="col-md-12 mb-7">
        <label class="form-label">Meta Keywords</label>
        <textarea name="meta_keyword" class="form-control" rows="2">{{ old('meta_keyword', optional($setting)->meta_keyword) }}</textarea>
    </div>

    <div class="col-md-12 mb-7">
        <label class="form-label">Meta Tags</label>
        <textarea name="meta_tags" class="form-control" rows="2">{{ old('meta_tags', optional($setting)->meta_tags) }}</textarea>
    </div>

    <div class="col-md-12 mb-7">
        <label class="form-label">Meta Description</label>
        <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', optional($setting)->meta_description) }}</textarea>
    </div>
</div>

<hr>

<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Open Graph</h5>
    </div>

    <div class="col-md-6 mb-7">
        <label class="form-label">OG Image Path</label>
        <input type="text" name="og_image" class="form-control"
            value="{{ old('og_image', optional($setting)->og_image) }}">
    </div>

    <div class="col-md-6 mb-7">
        <label class="form-label">OG Title</label>
        <input type="text" name="og_title" class="form-control"
            value="{{ old('og_title', optional($setting)->og_title) }}">
    </div>

    <div class="col-md-12 mb-7">
        <label class="form-label">OG Description</label>
        <textarea name="og_description" class="form-control" rows="3">{{ old('og_description', optional($setting)->og_description) }}</textarea>
    </div>

    <div class="col-md-12 mb-7">
        <label class="form-label">Canonical URL</label>
        <input type="text" name="canonical_url" class="form-control"
            value="{{ old('canonical_url', optional($setting)->canonical_url) }}">
    </div>
</div>

<hr>

<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Search Engine Verification</h5>
    </div>
    <div class="col-md-6 mb-7">
        <label class="form-label">Google Site Verification</label>
        <input type="text" name="google_site_verification" class="form-control"
            value="{{ old('google_site_verification', optional($setting)->google_site_verification) }}">
    </div>
    <div class="col-md-6 mb-7">
        <label class="form-label">Bing Site Verification</label>
        <input type="text" name="bing_site_verification" class="form-control"
            value="{{ old('bing_site_verification', optional($setting)->bing_site_verification) }}">
    </div>
</div>

<hr>

<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Analytics & Ads</h5>
    </div>
    <div class="col-md-12 mb-7">
        <label class="form-label">Google Analytics Code</label>
        <textarea name="google_analytics" class="form-control" rows="4">{{ old('google_analytics', optional($setting)->google_analytics) }}</textarea>
    </div>
    <div class="col-md-12 mb-7">
        <label class="form-label">Google Adsense Code</label>
        <textarea name="google_adsense" class="form-control" rows="4">{{ old('google_adsense', optional($setting)->google_adsense) }}</textarea>
    </div>
    <div class="col-md-12 mb-7">
        <label class="form-label">Facebook Pixel ID / Script</label>
        <textarea name="facebook_pixel_id" class="form-control" rows="2">{{ old('facebook_pixel_id', optional($setting)->facebook_pixel_id) }}</textarea>
    </div>
</div>
