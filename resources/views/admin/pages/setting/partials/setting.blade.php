<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Company & Orders</h5>
    </div>

    <div class="col-md-6 mb-7">
        <label class="form-label">Company Name</label>
        <input type="text" name="company_name" class="form-control"
               value="{{ old('company_name', optional($setting)->company_name) }}">
    </div>

    <div class="col-md-6 mb-7">
        <label class="form-label">Minimum Order Amount</label>
        <input type="number" name="minimum_order_amount" class="form-control"
               value="{{ old('minimum_order_amount', optional($setting)->minimum_order_amount) }}">
    </div>
</div>

<hr>

<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">Captcha</h5>
    </div>

    <div class="col-md-6 mb-7">
        <label class="form-label">Captcha Site Key</label>
        <input type="text" name="captcha_site_key" class="form-control"
               value="{{ old('captcha_site_key', optional($setting)->captcha_site_key) }}">
    </div>
    <div class="col-md-6 mb-7">
        <label class="form-label">Captcha Secret Key</label>
        <input type="text" name="captcha_secret_key" class="form-control"
               value="{{ old('captcha_secret_key', optional($setting)->captcha_secret_key) }}">
    </div>
</div>

<hr>

<div class="row mb-7">
    <div class="col-12">
        <h5 class="mb-7">SMTP / Mail Settings</h5>
    </div>

    <div class="col-md-3 mb-7">
        <label class="form-label">Mail Driver</label>
        <input type="text" name="mail_driver" class="form-control"
               value="{{ old('mail_driver', optional($setting)->mail_driver) }}">
    </div>
    <div class="col-md-3 mb-7">
        <label class="form-label">Mail Host</label>
        <input type="text" name="mail_host" class="form-control"
               value="{{ old('mail_host', optional($setting)->mail_host) }}">
    </div>
    <div class="col-md-2 mb-7">
        <label class="form-label">Mail Port</label>
        <input type="text" name="mail_port" class="form-control"
               value="{{ old('mail_port', optional($setting)->mail_port) }}">
    </div>
    <div class="col-md-4 mb-7">
        <label class="form-label">Mail Username</label>
        <input type="text" name="mail_username" class="form-control"
               value="{{ old('mail_username', optional($setting)->mail_username) }}">
    </div>

    <div class="col-md-4 mb-7">
        <label class="form-label">Mail Password</label>
        <input type="password" name="mail_password" class="form-control"
               value="{{ old('mail_password', optional($setting)->mail_password) }}">
    </div>
    <div class="col-md-4 mb-7">
        <label class="form-label">Mail Encryption</label>
        <input type="text" name="mail_encryption" class="form-control"
               value="{{ old('mail_encryption', optional($setting)->mail_encryption) }}">
    </div>
    <div class="col-md-4 mb-7">
        <label class="form-label">Mail From Address</label>
        <input type="email" name="mail_from_address" class="form-control"
               value="{{ old('mail_from_address', optional($setting)->mail_from_address) }}">
    </div>

    <div class="col-md-6 mb-7">
        <label class="form-label">Mail From Name</label>
        <input type="text" name="mail_from_name" class="form-control"
               value="{{ old('mail_from_name', optional($setting)->mail_from_name) }}">
    </div>

    <div class="col-md-3 mb-2">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" id="smtp_active" name="smtp_active" value="1"
                   {{ old('smtp_active', optional($setting)->smtp_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="smtp_active">SMTP Active</label>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" id="smtp_debug_mode" name="smtp_debug_mode" value="1"
                   {{ old('smtp_debug_mode', optional($setting)->smtp_debug_mode) ? 'checked' : '' }}>
            <label class="form-check-label" for="smtp_debug_mode">SMTP Debug Mode</label>
        </div>
    </div>
</div>
