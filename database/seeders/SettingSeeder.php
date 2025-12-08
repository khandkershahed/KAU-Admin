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
            'website_name_bn'        => 'খুলনা কৃষি বিশ্ববিদ্যালয়',
            'site_title'             => 'Khulna Agricultural University – KAU',
            'site_motto'             => 'Advancing Agriculture Through Innovation & Research',
            'site_logo_white'        => null,
            'site_logo_black'        => null,
            'site_favicon'           => null,
            'login_background_image' => null,
            'theme_color'            => '#0A4344',
            'dark_mode'              => false,


            /*
            |--------------------------------------------------------------------------
            | FOOTER
            |--------------------------------------------------------------------------
            */
            'footer_description' => 'Khulna Agricultural University is committed to excellence in agricultural education and research, serving Bangladesh with modern facilities and visionary leadership.',

            'footer_links' => json_encode([
                ['title' => 'Application Form', 'url' => '/application', 'order' => 1],
                ['title' => 'Job Circular',     'url' => '/jobs',        'order' => 2],
                ['title' => 'Privacy Policy',   'url' => '/privacy',     'order' => 3],
            ]),

            'contact_person' => json_encode([
                [
                    'name'        => 'Registrar',
                    'designation' => 'Chief Administration Officer',
                    'email'       => 'registrar@kau.ac.bd',
                    'phone'       => '+880123456789'
                ],
            ]),

            'copyright_text' => '© ' . date('Y') . ' Khulna Agricultural University. All Rights Reserved.',
            'developer_text' => 'ICT Cell, KAU',
            'developer_link' => 'https://kau.ac.bd',
            'website_url'    => 'https://kau.ac.bd',


            /*
            |--------------------------------------------------------------------------
            | COMMON CONTACT DETAILS
            |--------------------------------------------------------------------------
            */
            'emails' => json_encode([
                ['title' => 'Info', 'email' => 'info@kau.ac.bd'],
                ['title' => 'Support', 'email' => 'support@kau.ac.bd'],
            ]),

            'phone' => json_encode([
                ['title' => 'Hotline', 'phone' => '+880000000000'],
            ]),

            'addresses' => json_encode([
                [
                    'title' => 'Temporary Campus',
                    'address' =>'Khulna Agricultural University
                     327, Jashore Road, Goalkhali,
                     Khalishpur, Khulna-9000
                     E-mail: registrar@kau.ac.bd
                     Web: www.kau.ac.bd'
                ],
                [
                    'title' => 'অস্থায়ী অফিস',
                    'address' =>
                    'খুলনা কৃষি বিশ্ববিদ্যালয়
                     ৩২৭, যশোর রোড, গোয়ালখালী,
                     খালিশপুর, খুলনা-৯০০০
                     ই-মেইল: registrar@kau.ac.bd
                     ওয়বে: www.kau.ac.bd'
                ],
            ]),

            'social_links' => json_encode([
                ['icon_class' => 'fab fa-facebook-f', 'url' => 'https://facebook.com/khulnaagriculturaluniversity', 'order' => 1],
                ['icon_class' => 'fab fa-youtube',    'url' => 'https://www.youtube.com/@kau-official',            'order' => 2],
            ]),


            /*
            |--------------------------------------------------------------------------
            | COMPANY INFO
            |--------------------------------------------------------------------------
            */
            'company_name'          => 'Khulna Agricultural University',
            'minimum_order_amount'  => null,


            /*
            |--------------------------------------------------------------------------
            | LANGUAGE, CURRENCY & TIMEZONE
            |--------------------------------------------------------------------------
            */
            'default_language'     => 'bn',
            'default_currency'     => 'BDT',
            'system_timezone'      => 'Asia/Dhaka',
            'enable_multilanguage' => true,


            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */
            'site_url'          => 'https://kau.ac.bd',
            'meta_title'        => 'Khulna Agricultural University | KAU Bangladesh',
            'meta_keyword'      => 'KAU, Khulna Agricultural University, Agriculture Bangladesh',
            'meta_tags'         => 'agriculture, university, KAU, Bangladesh',
            'meta_description'  =>
            'Khulna Agricultural University (KAU) is a leading agricultural university in Bangladesh.',

            'og_image'          => 'uploads/settings/og-image.jpg',
            'og_title'          => 'Khulna Agricultural University – Official Website',
            'og_description'    => 'Explore academic programs, admissions, research updates, and notices.',
            'canonical_url'     => 'https://kau.ac.bd',

            'google_site_verification' => null,
            'bing_site_verification'   => null,
            'google_analytics'         => null,
            'google_adsense'           => null,
            'facebook_pixel_id'        => null,


            /*
            |--------------------------------------------------------------------------
            | LEGAL & CONSENT
            |--------------------------------------------------------------------------
            */
            'privacy_policy_url'     => 'https://kau.ac.bd/privacy',
            'terms_conditions_url'   => 'https://kau.ac.bd/terms',
            'cookie_consent_enabled' => true,
            'cookie_consent_text'    => 'This website uses cookies to ensure the best experience.',


            /*
            |--------------------------------------------------------------------------
            | BUSINESS HOURS
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
            'mail_driver'       => 'smtp',
            'mail_host'         => 'smtp.gmail.com',
            'mail_port'         => '587',
            'mail_username'     => 'registrar@kau.ac.bd',
            'mail_password'     => null,
            'mail_encryption'   => 'tls',
            'mail_from_address' => 'registrar@kau.ac.bd',
            'mail_from_name'    => 'Khulna Agricultural University',
            'smtp_active'       => false,
            'smtp_debug_mode'   => false,


            /*
            |--------------------------------------------------------------------------
            | ADVANCED
            |--------------------------------------------------------------------------
            */
            'custom_css' => null,
            'custom_js'  => null,


            /*
            |--------------------------------------------------------------------------
            | CUSTOM SETTINGS
            |--------------------------------------------------------------------------
            */
            'custom_settings' => json_encode([
                'homepage_slider_enabled' => true,
                'max_upload_file_size_mb' => 15,
                'enable_notice_search'    => true,
            ]),


            /*
            |--------------------------------------------------------------------------
            | AUDIT
            |--------------------------------------------------------------------------
            */
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
