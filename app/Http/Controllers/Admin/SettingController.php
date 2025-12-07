<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view setting')->only(['index']);
        $this->middleware('permission:update setting')->only(['updateOrcreateSetting']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pages.setting.index', [
            'setting' => Setting::first(),
        ]);
    }

    /**
     * Update or create setting.
     */
    public function updateOrcreateSetting(Request $request)
    {
        try {
            $setting = Setting::firstOrFail();

            // Validation
            $data = $request->validate([
                // BRANDING
                'website_name'           => 'nullable|string|max:250',
                'site_title'             => 'nullable|string|max:250',
                'site_motto'             => 'nullable|string',
                'footer_description'     => 'nullable|string',

                'site_logo_white'        => 'nullable|string',
                'site_logo_black'        => 'nullable|string',
                'site_favicon'           => 'nullable|string',
                'login_background_image' => 'nullable|string',

                'theme_color'            => 'nullable|string|max:50',
                'dark_mode'              => 'nullable|boolean',

                // Custom assets
                'custom_css'             => 'nullable|string',
                'custom_js'              => 'nullable|string',

                // CONTACT INFORMATION
                'primary_email'          => 'nullable|email',
                'support_email'          => 'nullable|email',
                'info_email'             => 'nullable|email',
                'sales_email'            => 'nullable|email',

                'primary_phone'          => 'nullable|string|max:20',
                'alternative_phone'      => 'nullable|string|max:20',
                'whatsapp_number'        => 'nullable|string|max:20',

                // JSON fields
                'additional_emails'      => 'nullable|array',
                'addresses'              => 'nullable|array',
                'social_links'           => 'nullable|array',
                'business_hours'         => 'nullable|array',
                'custom_settings'        => 'nullable|array',

                // COMPANY / ORDER
                'company_name'           => 'nullable|string',
                'minimum_order_amount'   => 'nullable|integer',

                // LANGUAGE, CURRENCY, TIMEZONE
                'default_language'       => 'nullable|string|max:20',
                'default_currency'       => 'nullable|string|max:20',
                'system_timezone'        => 'nullable|string|max:100',
                'enable_multilanguage'   => 'nullable|boolean',

                // SEO & META
                'site_url'               => 'nullable|url',
                'meta_title'             => 'nullable|string',
                'meta_keyword'           => 'nullable|string',
                'meta_tags'              => 'nullable|string',
                'meta_description'       => 'nullable|string',

                // Open Graph
                'og_image'               => 'nullable|string',
                'og_title'               => 'nullable|string',
                'og_description'         => 'nullable|string',
                'canonical_url'          => 'nullable|string',

                // SEO Verification
                'google_site_verification' => 'nullable|string',
                'bing_site_verification'   => 'nullable|string',

                // Analytics
                'google_analytics'       => 'nullable|string',
                'google_adsense'         => 'nullable|string',
                'facebook_pixel_id'      => 'nullable|string',

                // COPYRIGHT
                'copyright_title'        => 'nullable|string',
                'website_url'            => 'nullable|url',

                // LEGAL & COOKIE
                'privacy_policy_url'     => 'nullable|string',
                'terms_conditions_url'   => 'nullable|string',
                'cookie_consent_text'    => 'nullable|string',

                // CAPTCHA
                'captcha_site_key'       => 'nullable|string',
                'captcha_secret_key'     => 'nullable|string',

                // MAIL / SMTP
                'mail_driver'            => 'nullable|string',
                'mail_host'              => 'nullable|string',
                'mail_port'              => 'nullable|string',
                'mail_username'          => 'nullable|string',
                'mail_password'          => 'nullable|string',
                'mail_encryption'        => 'nullable|string',
                'mail_from_address'      => 'nullable|email',
                'mail_from_name'         => 'nullable|string',

                // BOOLEAN TOGGLES (optional, they will be handled as checkbox too)
                'maintenance_mode'          => 'nullable|boolean',
                'enable_user_registration'  => 'nullable|boolean',
                'enable_email_verification' => 'nullable|boolean',
                'enable_api_access'         => 'nullable|boolean',
                'is_demo'                   => 'nullable|boolean',
                'captcha_enabled'           => 'nullable|boolean',
                'cookie_consent_enabled'    => 'nullable|boolean',
                'smtp_active'               => 'nullable|boolean',
                'smtp_debug_mode'           => 'nullable|boolean',
            ]);

            // Fill simple fields directly from validated data
            $setting->fill($data);

            // Handle booleans (checkboxes)
            $setting->maintenance_mode          = $request->boolean('maintenance_mode');
            $setting->enable_user_registration  = $request->boolean('enable_user_registration');
            $setting->enable_email_verification = $request->boolean('enable_email_verification');
            $setting->enable_api_access         = $request->boolean('enable_api_access');
            $setting->is_demo                   = $request->boolean('is_demo');
            $setting->captcha_enabled           = $request->boolean('captcha_enabled');
            $setting->cookie_consent_enabled    = $request->boolean('cookie_consent_enabled');
            $setting->smtp_active               = $request->boolean('smtp_active');
            $setting->smtp_debug_mode           = $request->boolean('smtp_debug_mode');
            $setting->enable_multilanguage      = $request->boolean('enable_multilanguage');
            $setting->dark_mode                 = $request->boolean('dark_mode');

            // JSON fields (encode to match migration + seeder style)
            $setting->additional_emails = $request->filled('additional_emails')
                ? json_encode($request->input('additional_emails'))
                : null;

            $setting->addresses = $request->filled('addresses')
                ? json_encode($request->input('addresses'))
                : null;

            $setting->social_links = $request->filled('social_links')
                ? json_encode($request->input('social_links'))
                : null;

            $setting->business_hours = $request->filled('business_hours')
                ? json_encode($request->input('business_hours'))
                : null;

            $setting->custom_settings = $request->filled('custom_settings')
                ? json_encode($request->input('custom_settings'))
                : null;

            // Auditing
            if (Auth::check()) {
                $setting->updated_by = Auth::id();
                if (! $setting->created_by) {
                    $setting->created_by = Auth::id();
                }
            }

            $setting->save();

            Session::flash('success', 'Settings updated successfully.');
            return redirect()->back();

        } catch (ValidationException $e) {
            Session::flash('error', 'Please correct the errors in the form.');
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()
                ->back()
                ->withInput();
        }
    }
}
