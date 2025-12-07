@php
    $setting = $setting ?? null;
    $socialLinks = [];

    if ($setting && $setting->social_links) {
        $socialLinks = is_array($setting->social_links)
            ? $setting->social_links
            : json_decode($setting->social_links, true);
    }

    $socialKeys = [
        'facebook'  => 'Facebook',
        'youtube'   => 'YouTube',
        'linkedin'  => 'LinkedIn',
        'twitter'   => 'Twitter',
        'instagram' => 'Instagram',
        'pinterest' => 'Pinterest',
        'reddit'    => 'Reddit',
        'tumblr'    => 'Tumblr',
        'tiktok'    => 'TikTok',
        'whatsapp'  => 'WhatsApp',
    ];
@endphp

<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Social Links</h5>
        <small class="text-muted">Leave blank if not applicable.</small>
    </div>

    @foreach($socialKeys as $key => $label)
        <div class="col-md-6 mb-7">
            <label class="form-label">{{ $label }} URL</label>
            <input type="url"
                   name="social_links[{{ $key }}]"
                   class="form-control"
                   value="{{ old("social_links.$key", $socialLinks[$key] ?? '') }}">
        </div>
    @endforeach
</div>
