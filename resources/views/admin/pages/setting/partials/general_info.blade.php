@php
    $setting = $setting ?? null;
    $addresses = [];

    if ($setting && $setting->addresses) {
        $addresses = is_array($setting->addresses) ? $setting->addresses : json_decode($setting->addresses, true);
    }

    $temporaryCampus = $addresses['temporary_campus'] ?? [
        'en' => '',
        'bn' => '',
    ];
@endphp

<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-3">Branding</h5>
    </div>

    <div class="col-md-6 mb-7">
        <label class="form-label">Website Name</label>
        <input type="text" name="website_name" class="form-control"
            value="{{ old('website_name', optional($setting)->website_name) }}">
    </div>

    <div class="col-md-6 mb-7">
        <label class="form-label">Site Title</label>
        <input type="text" name="site_title" class="form-control"
            value="{{ old('site_title', optional($setting)->site_title) }}">
    </div>

    <div class="col-md-12 mb-7">
        <label class="form-label">Site Motto</label>
        <textarea name="site_motto" class="form-control" rows="2">{{ old('site_motto', optional($setting)->site_motto) }}</textarea>
    </div>

    <div class="col-md-12 mb-7">
        <label class="form-label">Footer Description</label>
        <textarea name="footer_description" class="form-control" rows="3">{{ old('footer_description', optional($setting)->footer_description) }}</textarea>
    </div>

    <div class="col-md-3 mb-7">
        <label class="form-label">Site Logo (White Path)</label>
        <input type="text" name="site_logo_white" class="form-control"
            value="{{ old('site_logo_white', optional($setting)->site_logo_white) }}">
    </div>
    <div class="col-md-3 mb-7">
        <label class="form-label">Site Logo (Black Path)</label>
        <input type="text" name="site_logo_black" class="form-control"
            value="{{ old('site_logo_black', optional($setting)->site_logo_black) }}">
    </div>
    <div class="col-md-3 mb-7">
        <label class="form-label">Favicon Path</label>
        <input type="text" name="site_favicon" class="form-control"
            value="{{ old('site_favicon', optional($setting)->site_favicon) }}">
    </div>
    <div class="col-md-3 mb-7">
        <label class="form-label">Login Background Image Path</label>
        <input type="text" name="login_background_image" class="form-control"
            value="{{ old('login_background_image', optional($setting)->login_background_image) }}">
    </div>

    <div class="col-md-4 mb-7">
        <label class="form-label">Theme Color (Hex)</label>
        <input type="text" name="theme_color" class="form-control"
            value="{{ old('theme_color', optional($setting)->theme_color) }}">
    </div>
    <div class="col-md-4 mb-7 d-flex align-items-center">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" name="dark_mode" value="1" id="dark_mode"
                {{ old('dark_mode', optional($setting)->dark_mode) ? 'checked' : '' }}>
            <label class="form-check-label" for="dark_mode">
                Enable Dark Mode
            </label>
        </div>
    </div>
</div>

<hr>

<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-3">Contact Information</h5>
    </div>

    <div class="col-md-3 mb-7">
        <label class="form-label">Primary Email</label>
        <input type="email" name="primary_email" class="form-control"
            value="{{ old('primary_email', optional($setting)->primary_email) }}">
    </div>
    <div class="col-md-3 mb-7">
        <label class="form-label">Support Email</label>
        <input type="email" name="support_email" class="form-control"
            value="{{ old('support_email', optional($setting)->support_email) }}">
    </div>
    <div class="col-md-3 mb-7">
        <label class="form-label">Info Email</label>
        <input type="email" name="info_email" class="form-control"
            value="{{ old('info_email', optional($setting)->info_email) }}">
    </div>
    <div class="col-md-3 mb-7">
        <label class="form-label">Sales Email</label>
        <input type="email" name="sales_email" class="form-control"
            value="{{ old('sales_email', optional($setting)->sales_email) }}">
    </div>

    <div class="col-md-4 mb-7">
        <label class="form-label">Primary Phone</label>
        <input type="text" name="primary_phone" class="form-control"
            value="{{ old('primary_phone', optional($setting)->primary_phone) }}">
    </div>
    <div class="col-md-4 mb-7">
        <label class="form-label">Alternative Phone</label>
        <input type="text" name="alternative_phone" class="form-control"
            value="{{ old('alternative_phone', optional($setting)->alternative_phone) }}">
    </div>
    <div class="col-md-4 mb-7">
        <label class="form-label">WhatsApp Number</label>
        <input type="text" name="whatsapp_number" class="form-control"
            value="{{ old('whatsapp_number', optional($setting)->whatsapp_number) }}">
    </div>
</div>

<hr>

@php
    $setting = $setting ?? null;

    // Decode existing addresses
    $addressesRaw = [];

    if ($setting && $setting->addresses) {
        $addressesRaw = is_array($setting->addresses) ? $setting->addresses : json_decode($setting->addresses, true);
    }

    // Normalize into a list of items: [ ['title' => '', 'address' => ''], ... ]
    $addresses = [];

    // If it's already a list of title/address objects
if (is_array($addressesRaw) && array_is_list($addressesRaw)) {
    foreach ($addressesRaw as $item) {
        $addresses[] = [
            'title' => $item['title'] ?? '',
            'address' => $item['address'] ?? '',
        ];
    }
}
// If it's associative (like old seeder style), we try to flatten it
    elseif (is_array($addressesRaw)) {
        foreach ($addressesRaw as $key => $value) {
            // Example old style: 'temporary_campus' => ['en' => '...', 'bn' => '...']
            if (is_array($value)) {
                if (!empty($value['en'])) {
                    $addresses[] = [
                        'title' => ucfirst(str_replace('_', ' ', $key)) . ' (EN)',
                        'address' => $value['en'],
                    ];
                }
                if (!empty($value['bn'])) {
                    $addresses[] = [
                        'title' => ucfirst(str_replace('_', ' ', $key)) . ' (BN)',
                        'address' => $value['bn'],
                    ];
                }
            } else {
                $addresses[] = [
                    'title' => ucfirst(str_replace('_', ' ', $key)),
                    'address' => $value,
                ];
            }
        }
    }

    // If no data at all, give two default rows
    if (empty($addresses)) {
        $addresses = [
            ['title' => 'Address (EN)', 'address' => ''],
            ['title' => 'Address (BN)', 'address' => ''],
        ];
    }
@endphp

<div class="row mb-4">
    <div class="col-12">
        <h5 class="mb-3">Addresses</h5>
        <small class="text-muted d-block mb-2">
            Add as many addresses as you need, each with its own title (Temporary EN, Temporary BN, Permanent, etc.)
        </small>
    </div>

    <div class="col-12" id="addresses-repeater" data-next-index="{{ count($addresses) }}">
        @foreach ($addresses as $index => $item)
            <div class="row address-item align-items-start mb-3">
                <div class="col-md-4 mb-2">
                    <label class="form-label">Title</label>
                    <input type="text" name="addresses[{{ $index }}][title]" class="form-control"
                        value="{{ old("addresses.$index.title", $item['title']) }}"
                        placeholder="e.g. Address (EN)">
                </div>
                <div class="col-md-7 mb-2">
                    <label class="form-label">Address</label>
                    <textarea name="addresses[{{ $index }}][address]" class="form-control" rows="3"
                        placeholder="Write full address here">{{ old("addresses.$index.address", $item['address']) }}</textarea>
                </div>
                <div class="col-md-1 d-flex align-items-end mb-2">
                    <button type="button" class="btn btn-sm btn-danger remove-address">
                        &times;
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="col-12">
        <button type="button" class="btn btn-sm btn-primary" id="add-address-row">
            + Add Address
        </button>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const repeater = document.getElementById('addresses-repeater');
            if (!repeater) return;

            let nextIndex = parseInt(repeater.getAttribute('data-next-index') || '0', 10);

            const template = (index) => `
            <div class="row address-item align-items-start mb-3">
                <div class="col-md-4 mb-2">
                    <label class="form-label">Title</label>
                    <input type="text"
                           name="addresses[${index}][title]"
                           class="form-control"
                           placeholder="e.g. Permanent Campus (BN)">
                </div>
                <div class="col-md-7 mb-2">
                    <label class="form-label">Address</label>
                    <textarea name="addresses[${index}][address]"
                              class="form-control"
                              rows="3"
                              placeholder="Write full address here"></textarea>
                </div>
                <div class="col-md-1 d-flex align-items-end mb-2">
                    <button type="button" class="btn btn-sm btn-danger remove-address">
                        &times;
                    </button>
                </div>
            </div>
        `;

            document.getElementById('add-address-row').addEventListener('click', function() {
                repeater.insertAdjacentHTML('beforeend', template(nextIndex));
                nextIndex++;
            });

            repeater.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-address')) {
                    const row = e.target.closest('.address-item');
                    if (row) {
                        row.remove();
                    }
                }
            });
        });
    </script>
@endpush
