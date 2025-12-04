<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('website_name', 250)->nullable();
            $table->string('site_title', 250)->nullable();
            $table->text('site_motto')->nullable();
            $table->text('footer_description')->nullable();

            $table->string('site_logo_white')->nullable();
            $table->string('site_logo_black')->nullable();
            $table->string('site_favicon')->nullable();
            $table->string('login_background_image')->nullable();

            $table->string('theme_color', 50)->nullable();
            $table->boolean('dark_mode')->default(false);

            // Custom assets
            $table->longText('custom_css')->nullable();
            $table->longText('custom_js')->nullable();


            $table->string('primary_email')->nullable();
            $table->string('support_email')->nullable();
            $table->string('info_email')->nullable();
            $table->string('sales_email')->nullable();
            $table->json('additional_emails')->nullable(); // instead of too many individual columns

            $table->string('primary_phone', 20)->nullable();
            $table->string('alternative_phone', 20)->nullable();
            $table->string('whatsapp_number', 20)->nullable();

            $table->json('addresses')->nullable();


            $table->string('company_name')->nullable();
            $table->integer('minimum_order_amount')->nullable();


            $table->string('default_language', 20)->nullable();
            $table->string('default_currency', 20)->nullable();
            $table->string('system_timezone', 100)->nullable();
            $table->boolean('enable_multilanguage')->default(false);


            $table->string('site_url')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->text('meta_tags')->nullable();
            $table->text('meta_description')->nullable();

            // Open Graph
            $table->string('og_image')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('canonical_url')->nullable();

            // SEO Verification
            $table->string('google_site_verification')->nullable();
            $table->string('bing_site_verification')->nullable();

            // Analytics
            $table->text('google_analytics')->nullable();
            $table->text('google_adsense')->nullable();
            $table->text('facebook_pixel_id')->nullable();


            $table->json('social_links')->nullable();


            $table->text('copyright_title')->nullable();
            $table->string('website_url')->nullable();

            $table->boolean('maintenance_mode')->default(false);
            $table->boolean('enable_user_registration')->default(true);
            $table->boolean('enable_email_verification')->default(false);
            $table->boolean('enable_api_access')->default(false);
            $table->boolean('is_demo')->default(false);
            $table->boolean('captcha_enabled')->default(false);
            $table->string('captcha_site_key')->nullable();
            $table->string('captcha_secret_key')->nullable();
            $table->boolean('cookie_consent_enabled')->default(false);

            $table->text('privacy_policy_url')->nullable();
            $table->text('terms_conditions_url')->nullable();
            $table->text('cookie_consent_text')->nullable();

            $table->json('business_hours')->nullable();

            $table->string('mail_driver')->nullable();
            $table->string('mail_host')->nullable();
            $table->string('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_encryption')->nullable();
            $table->string('mail_from_address')->nullable();
            $table->string('mail_from_name')->nullable();

            $table->boolean('smtp_active')->default(false);
            $table->boolean('smtp_debug_mode')->default(false);


            $table->json('custom_settings')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
