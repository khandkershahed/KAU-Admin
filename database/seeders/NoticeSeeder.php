<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NoticeSeeder extends Seeder
{
    public function run(): void
    {
        $notices = [

            [
                'category_id' => 1, // Notice
                'title' => 'কৃষি ভর্তি গুচ্ছ পরীক্ষার সিট প্ল্যান - ১২/০৪/২০২৫',
                'slug' => Str::slug('কৃষি ভর্তি গুচ্ছ পরীক্ষার সিট প্ল্যান 12-04-2025'),
                'body' => null,
                'publish_date' => '2025-04-10',
                'attachments' => json_encode(['seat-plan-2025.pdf']),
                'attachment_type' => 'pdf',
                'meta_title' => 'Seat Plan Notice',
                'meta_tags' => 'admission, seat plan',
                'meta_description' => 'Seat plan for agricultural cluster admission test.',
                'views' => 0,
                'is_featured' => true,
                'status' => 'published',
                'created_by' => 1,
            ],

            [
                'category_id' => 1,
                'title' => '২০২৫-২০২৬ শিক্ষাবর্ষে ভর্তি কার্যক্রম সংক্রান্ত নোটিশ',
                'slug' => Str::slug('২০২৫ ২০২৬ শিক্ষাবর্ষ ভর্তি কার্যক্রম নোটিশ'),
                'body' => '২০২৫-২০২৬ শিক্ষাবর্ষে ভর্তিকৃত শিক্ষার্থীদের ওরিয়েন্টেশন
                           এবং শিক্ষা কার্যক্রম আগামী ২০ ফেব্রুয়ারি সকাল ৯:৩০ ঘটিকায়
                           অনুষ্ঠিত হবে।',
                'publish_date' => '2025-02-12',
                'attachments' => json_encode(['admission-notice.pdf']),
                'attachment_type' => 'pdf',
                'meta_title' => 'Academic Activities Notice',
                'meta_tags' => 'academic notice',
                'meta_description' => 'Orientation and academic activity schedule.',
                'views' => 0,
                'is_featured' => false,
                'status' => 'published',
                'created_by' => 1,
            ],

            [
                'category_id' => 2, // Office Order
                'title' => 'খুলনা কৃষি বিশ্ববিদ্যালয়ের নবনিযুক্ত মনোমুগ্ধ ভাইস চ্যান্সেলরের নিয়োগ বিজ্ঞপ্তি',
                'slug' => Str::slug('খুলনা কৃষি বিশ্ববিদ্যালয় ভাইস চ্যান্সেলর নিয়োগ'),

                'body' => 'ড. মোঃ নাজমুল আহসান - এর নিয়োগ বিজ্ঞপ্তি প্রকাশিত।',
                'publish_date' => '2024-10-29',
                'attachments' => json_encode(['vc-appointment-order.pdf']),
                'attachment_type' => 'pdf',
                'meta_title' => 'VC Appointment Order',
                'meta_tags' => 'office order, appointment',
                'meta_description' => 'Official appointment notice of the new Vice Chancellor.',
                'views' => 0,
                'is_featured' => true,
                'status' => 'published',
                'created_by' => 1,
            ],

            [
                'category_id' => 3, // NOC
                'title' => 'NOC for Academic Purpose (25/10/2024)',
                'slug' => Str::slug('NOC For Academic Purpose 25-10-2024'),

                'body' => null,
                'publish_date' => '2024-10-25',
                'attachments' => json_encode(['noc-academic.pdf']),
                'attachment_type' => 'pdf',
                'meta_title' => 'NOC Academic',
                'meta_tags' => 'noc',
                'meta_description' => 'NOC issued for academic purposes.',
                'views' => 0,
                'is_featured' => false,
                'status' => 'published',
                'created_by' => 1,
            ],
        ];

        DB::table('notices')->insert($notices);
    }
}
