<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insert([
            /*
            |--------------------------------------------------------------------------
            | BRANDING
            |--------------------------------------------------------------------------
            */
            'website_name'           => 'Khulna Agricultural University',
            'site_title'             => 'Khulna Agricultural University – KAU',
            'site_motto'             => 'Advancing Agriculture Through Innovation & Research',
            'footer_description'     => 'Khulna Agricultural University is committed to excellence in agricultural education and research, serving Bangladesh with modern facilities and visionary leadership.',
            'site_logo_white'        => 'uploads/settings/logo-white.png',
            'site_logo_black'        => 'uploads/settings/logo-black.png',
            'site_favicon'           => 'uploads/settings/favicon.png',
            'login_background_image' => 'uploads/settings/login-bg.jpg',


            /*
            |--------------------------------------------------------------------------
            | CONTACT INFORMATION
            |--------------------------------------------------------------------------
            */
            'primary_email'          => 'registrar@kau.ac.bd',
            'support_email'          => null,
            'info_email'             => 'info@kau.ac.bd',
            'sales_email'            => null,
            'primary_phone'          => '+880000000000',
            'alternative_phone'      => null,
            'whatsapp_number'        => null,


            /*
            |--------------------------------------------------------------------------
            | ADDRESSES (EN + BN)
            |--------------------------------------------------------------------------
            */
            'addresses' => json_encode([
                'temporary_campus' => [
                    'en' => 'Temporary Campus: Khulna Agricultural University, 327 Jashore Road, Goalkhali, Khalishpur, Khulna-9000',
                    'bn' => 'অস্থায়ী অফিস: খুলনা কৃষি বিশ্ববিদ্যালয়, ৩২৭ যশোর রোড, গোয়ালখালী, খালিশপুর, খুলনা-৯০০০',
                ]
            ]),


            /*
            |--------------------------------------------------------------------------
            | LANGUAGE, CURRENCY, TIMEZONE
            |--------------------------------------------------------------------------
            */
            'default_language'       => 'bn',
            'default_currency'       => 'BDT',
            'system_timezone'        => 'Asia/Dhaka',
            'enable_multilanguage'   => true,


            /*
            |--------------------------------------------------------------------------
            | SEO & META
            |--------------------------------------------------------------------------
            */
            'site_url'               => 'https://kau.ac.bd',
            'meta_title'             => 'Khulna Agricultural University | KAU Bangladesh',
            'meta_keyword'           => 'KAU, Khulna Agricultural University, Bangladesh Agriculture, Public University',
            'meta_tags'              => 'agriculture, university, KAU, Bangladesh',
            'meta_description'       =>
                'Khulna Agricultural University (KAU) is a leading agricultural university in Bangladesh, dedicated to excellence in agricultural innovation, research, and education.',
            'google_analytics'       => null,
            'google_adsense'         => null,
            'facebook_pixel_id'      => null,
            'og_image'               => 'uploads/settings/og-image.jpg',
            'og_title'               => 'Khulna Agricultural University – Official Website',
            'og_description'         => 'Explore academic programs, admissions, research updates, notices, and official announcements from Khulna Agricultural University.',
            'canonical_url'          => 'https://kau.ac.bd',


            /*
            |--------------------------------------------------------------------------
            | COPYRIGHT
            |--------------------------------------------------------------------------
            */
            'copyright_title'        => '© ' . date('Y') . ' Khulna Agricultural University. All Rights Reserved.',
            'website_url'            => 'https://kau.ac.bd',


            /*
            |--------------------------------------------------------------------------
            | SOCIAL LINKS
            |--------------------------------------------------------------------------
            | (Based on real links from website footer)
            */
            'social_links' => json_encode([
                'facebook'  => 'https://www.facebook.com/khulnaagriculturaluniversity',
                'youtube'   => 'https://www.youtube.com/@kau-official',
                'linkedin'  => null,
                'twitter'   => null,
                'instagram' => null,
                'pinterest' => null,
                'reddit'    => null,
                'tumblr'    => null,
                'tiktok'    => null,
                'whatsapp'  => null,
            ]),


            /*
            |--------------------------------------------------------------------------
            | FEATURE TOGGLES
            |--------------------------------------------------------------------------
            */
            'maintenance_mode'          => false,
            'enable_user_registration'  => false,
            'enable_email_verification' => false,
            'enable_api_access'         => false,
            'is_demo'                   => false,
            'captcha_enabled'           => false,
            'cookie_consent_enabled'    => true,
            'cookie_consent_text'       => 'This website uses cookies to ensure the best experience.',
            'privacy_policy_url'        => 'https://kau.ac.bd/privacy',
            'terms_conditions_url'      => 'https://kau.ac.bd/terms',


            /*
            |--------------------------------------------------------------------------
            | BUSINESS HOURS (Universities usually office hours)
            |--------------------------------------------------------------------------
            */
            'business_hours' => json_encode([
                'saturday'   => ['start' => '09:00', 'end' => '17:00', 'closed' => false],
                'sunday'     => ['start' => '09:00', 'end' => '17:00', 'closed' => false],
                'monday'     => ['start' => '09:00', 'end' => '17:00', 'closed' => false],
                'tuesday'    => ['start' => '09:00', 'end' => '17:00', 'closed' => false],
                'wednesday'  => ['start' => '09:00', 'end' => '17:00', 'closed' => false],
                'thursday'   => ['start' => '09:00', 'end' => '17:00', 'closed' => false],
                'friday'     => ['closed' => true],
            ]),


            /*
            |--------------------------------------------------------------------------
            | SMTP EMAIL SETTINGS
            |--------------------------------------------------------------------------
            */
            'mail_driver'               => 'smtp',
            'mail_host'                 => 'smtp.gmail.com',
            'mail_port'                 => '587',
            'mail_username'             => 'registrar@kau.ac.bd',
            'mail_password'             => null,
            'mail_encryption'           => 'tls',
            'mail_from_address'         => 'registrar@kau.ac.bd',
            'mail_from_name'            => 'Khulna Agricultural University',
            'smtp_active'               => false,
            'smtp_debug_mode'           => false,


            /*
            |--------------------------------------------------------------------------
            | ADVANCED SETTINGS
            |--------------------------------------------------------------------------
            */
            'theme_color'               => '#0A4344', // site’s dominant color
            'dark_mode'                 => false,
            'custom_css'                => null,
            'custom_js'                 => null,


            /*
            |--------------------------------------------------------------------------
            | Custom Plugin Settings (Optional)
            |--------------------------------------------------------------------------
            */
            'custom_settings' => json_encode([
                'homepage_slider_enabled' => true,
                'max_upload_file_size_mb' => 15,
                'enable_notice_search'    => true,
            ]),


            /*
            |--------------------------------------------------------------------------
            | Auditing
            |--------------------------------------------------------------------------
            */
            'created_by'                => 1,
            'updated_by'                => 1,
            'created_at'                => Carbon::now(),
            'updated_at'                => Carbon::now(),
        ]);
    }
}
