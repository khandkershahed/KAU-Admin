<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admission;

class AdmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
         * ROOT LEVEL (under main "Admission" menu in frontend)
         * - Undergraduate Programs
         * - Graduate Programs
         * - International Students
         */

        $undergrad = Admission::updateOrCreate(
            ['slug' => 'undergraduate-programs'],
            [
                'parent_id'       => null,
                'title'           => 'Undergraduate Programs',
                'type'            => 'menu',   // has children
                'external_url'    => null,
                'banner_image'    => null,
                'content'         => null,
                'meta_title'      => 'Undergraduate Programs',
                'meta_tags'       => 'undergraduate, admission',
                'meta_description' => 'Undergraduate admission and program information of Khulna Agricultural University.',
                'position'        => 1,
                'status'          => true,
            ]
        );

        $graduate = Admission::updateOrCreate(
            ['slug' => 'graduate-programs'],
            [
                'parent_id'       => null,
                'title'           => 'Graduate Programs',
                'type'            => 'menu',
                'external_url'    => null,
                'banner_image'    => null,
                'content'         => null,
                'meta_title'      => 'Graduate Programs',
                'meta_tags'       => 'graduate, masters, doctoral, admission',
                'meta_description' => 'Graduate (Masters and Doctoral) admission information.',
                'position'        => 2,
                'status'          => true,
            ]
        );

        $international = Admission::updateOrCreate(
            ['slug' => 'international-students'],
            [
                'parent_id'       => null,
                'title'           => 'International Students',
                'type'            => 'menu',
                'external_url'    => null,
                'banner_image'    => null,
                'content'         => null,
                'meta_title'      => 'International Students',
                'meta_tags'       => 'international students, admission',
                'meta_description' => 'Admission information and guidance for international students.',
                'position'        => 3,
                'status'          => true,
            ]
        );

        /*
         * UNDERGRADUATE CHILDREN
         *  - Admission Information (page)
         *  - Application Link (external)
         */

        $ugAdmissionInfo = Admission::updateOrCreate(
            ['slug' => 'undergraduate-admission-information'],
            [
                'parent_id'       => $undergrad->id,
                'title'           => 'Admission Information',
                'type'            => 'page',
                'external_url'    => null,
                'banner_image'    => null,
                'content'         => "Undergraduate Admission Information content (manage from admin).\n\nSource page: https://kau.ac.bd/undergraduate-programs-admission-information/",
                'meta_title'      => 'Undergraduate Admission Information',
                'meta_tags'       => 'undergraduate admission information',
                'meta_description' => 'Details about undergraduate admission requirements, process and documents.',
                'position'        => 1,
                'status'          => true,
            ]
        );

        $ugApplication = Admission::updateOrCreate(
            ['slug' => 'undergraduate-application-link'],
            [
                'parent_id'       => $undergrad->id,
                'title'           => 'Application Link',
                'type'            => 'external',
                'external_url'    => 'https://admission.kau.ac.bd/',
                'banner_image'    => null,
                'content'         => null,
                'meta_title'      => 'Undergraduate Application Link',
                'meta_tags'       => 'undergraduate application',
                'meta_description' => 'Online application portal for undergraduate admission.',
                'position'        => 2,
                'status'          => true,
            ]
        );

        /*
         * GRADUATE CHILDREN
         *   Graduate Programs
         *     ├─ Masters (menu)
         *     │   ├─ Admission Information (page)
         *     │   └─ Application Link (external)
         *     └─ Doctoral (menu)
         *         ├─ Admission Information (page)
         *         └─ Application Link (external)
         */

        $masters = Admission::updateOrCreate(
            ['slug' => 'graduate-masters'],
            [
                'parent_id'       => $graduate->id,
                'title'           => 'Masters',
                'type'            => 'menu',
                'external_url'    => null,
                'banner_image'    => null,
                'content'         => null,
                'meta_title'      => 'Masters Programs',
                'meta_tags'       => 'masters admission',
                'meta_description' => 'Masters program admission information.',
                'position'        => 1,
                'status'          => true,
            ]
        );

        $doctoral = Admission::updateOrCreate(
            ['slug' => 'graduate-doctoral'],
            [
                'parent_id'       => $graduate->id,
                'title'           => 'Doctoral',
                'type'            => 'menu',
                'external_url'    => null,
                'banner_image'    => null,
                'content'         => null,
                'meta_title'      => 'Doctoral Programs',
                'meta_tags'       => 'doctoral admission',
                'meta_description' => 'Doctoral program admission information.',
                'position'        => 2,
                'status'          => true,
            ]
        );

        // Masters -> Admission Information
        $mastersInfo = Admission::updateOrCreate(
            ['slug' => 'masters-admission-information'],
            [
                'parent_id'       => $masters->id,
                'title'           => 'Admission Information',
                'type'            => 'page',
                'external_url'    => null,
                'banner_image'    => null,
                'content'         => "Graduate (Masters) Admission Information. Manage details from admin.\n\nSource page: https://kau.ac.bd/graduate-program-masters/",
                'meta_title'      => 'Masters Admission Information',
                'meta_tags'       => 'masters admission information',
                'meta_description' => 'Masters admission requirements, eligibility and process.',
                'position'        => 1,
                'status'          => true,
            ]
        );

        // Masters -> Application Link
        $mastersApplication = Admission::updateOrCreate(
            ['slug' => 'masters-application-link'],
            [
                'parent_id'       => $masters->id,
                'title'           => 'Application Link',
                'type'            => 'external',
                'external_url'    => 'https://admission.kau.ac.bd/',
                'banner_image'    => null,
                'content'         => null,
                'meta_title'      => 'Masters Application Link',
                'meta_tags'       => 'masters application',
                'meta_description' => 'Online application portal for masters programs.',
                'position'        => 2,
                'status'          => true,
            ]
        );

        // Doctoral -> Admission Information
        $doctoralInfo = Admission::updateOrCreate(
            ['slug' => 'doctoral-admission-information'],
            [
                'parent_id'       => $doctoral->id,
                'title'           => 'Admission Information',
                'type'            => 'page',
                'external_url'    => null,
                'banner_image'    => null,
                'content'         => "Doctoral admission information. Manage full details in admin.",
                'meta_title'      => 'Doctoral Admission Information',
                'meta_tags'       => 'doctoral admission information',
                'meta_description' => 'Doctoral admission requirements, eligibility and application process.',
                'position'        => 1,
                'status'          => true,
            ]
        );

        // Doctoral -> Application Link
        $doctoralApplication = Admission::updateOrCreate(
            ['slug' => 'doctoral-application-link'],
            [
                'parent_id'       => $doctoral->id,
                'title'           => 'Application Link',
                'type'            => 'external',
                'external_url'    => 'https://admission.kau.ac.bd/',
                'banner_image'    => null,
                'content'         => null,
                'meta_title'      => 'Doctoral Application Link',
                'meta_tags'       => 'doctoral application',
                'meta_description' => 'Online application portal for doctoral programs.',
                'position'        => 2,
                'status'          => true,
            ]
        );

        /*
         * INTERNATIONAL STUDENTS CHILDREN
         *   - Admission Information (page)
         *   - Application Link (external)
         */

        $intlInfo = Admission::updateOrCreate(
            ['slug' => 'international-admission-information'],
            [
                'parent_id'       => $international->id,
                'title'           => 'Admission Information',
                'type'            => 'page',
                'external_url'    => null,
                'banner_image'    => null,
                'content'         => "Admission information for international students.",
                'meta_title'      => 'International Admission Information',
                'meta_tags'       => 'international admission information',
                'meta_description' => 'Guidelines for international applicants, visa, fees and process.',
                'position'        => 1,
                'status'          => true,
            ]
        );

        $intlApplication = Admission::updateOrCreate(
            ['slug' => 'international-application-link'],
            [
                'parent_id'       => $international->id,
                'title'           => 'Application Link',
                'type'            => 'external',
                'external_url'    => 'https://admission.kau.ac.bd/',
                'banner_image'    => null,
                'content'         => null,
                'meta_title'      => 'International Application Link',
                'meta_tags'       => 'international application',
                'meta_description' => 'Online application portal for international students.',
                'position'        => 2,
                'status'          => true,
            ]
        );
    }
}
