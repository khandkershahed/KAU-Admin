<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'website_name',
        'website_name_bn',
        'site_title',
        'site_motto',

        'site_logo_white',
        'site_logo_black',
        'site_favicon',
        'login_background_image',

        'theme_color',
        'secondary_theme_color',
        'dark_mode',

        'custom_css',
        'custom_js',

        'footer_description',
        'footer_links',
        'copyright_text',
        'developer_text',
        'developer_link',
        'website_url',
        'contact_person',

        'emails',
        'phone',
        'addresses',
        'social_links',

        'company_name',
        'minimum_order_amount',

        'default_language',
        'default_currency',
        'system_timezone',
        'enable_multilanguage',

        'site_url',
        'meta_title',
        'meta_keyword',
        'meta_tags',
        'meta_description',

        'og_image',
        'og_title',
        'og_description',
        'canonical_url',

        'google_site_verification',
        'bing_site_verification',

        'google_analytics',
        'google_adsense',
        'facebook_pixel_id',

        'maintenance_mode',
        'enable_user_registration',
        'enable_email_verification',
        'enable_api_access',
        'is_demo',
        'captcha_enabled',
        'captcha_site_key',
        'captcha_secret_key',
        'cookie_consent_enabled',

        'privacy_policy_url',
        'terms_conditions_url',
        'cookie_consent_text',

        'business_hours',

        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',

        'smtp_active',
        'smtp_debug_mode',

        'custom_settings',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'footer_links'              => 'array',
        'contact_person'            => 'array',
        'emails'                    => 'array',
        'phone'                     => 'array',
        'addresses'                 => 'array',
        'social_links'              => 'array',
        'business_hours'            => 'array',
        'custom_settings'           => 'array',
        'dark_mode'                 => 'boolean',
        'enable_multilanguage'      => 'boolean',
        'maintenance_mode'          => 'boolean',
        'enable_user_registration'  => 'boolean',
        'enable_email_verification' => 'boolean',
        'enable_api_access'         => 'boolean',
        'is_demo'                   => 'boolean',
        'captcha_enabled'           => 'boolean',
        'cookie_consent_enabled'    => 'boolean',
        'smtp_active'               => 'boolean',
        'smtp_debug_mode'           => 'boolean',
    ];
}
