<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newsItems = [

            [
                'title' => 'নানা আয়োজনে খুকৃবি-তে জুলাই গণঅভ্যুত্থান দিবস উদযাপিত',
                'slug' => Str::slug('নানা আয়োজনে খুকৃবি-তে জুলাই গণঅভ্যুত্থান দিবস উদযাপিত'),
                'thumb_image' => 'uploads/news/thumb1.jpg',
                'content_image' => 'uploads/news/content1.jpg',
                'banner_image' => 'uploads/news/banner1.jpg',
                'summary' => 'খুলনা কৃষি বিশ্ববিদ্যালয়ে জুলাই গণঅভ্যুত্থান দিবস নানা আয়োজনের মধ্য দিয়ে উদযাপিত হয়েছে।',
                'content' => '
                    <p>নানা আয়োজনের মধ্য দিয়ে খুলনা কৃষি বিশ্ববিদ্যালয়ে জুলাই গণঅভ্যুত্থান দিবস উদযাপিত হয়েছে।</p>
                    <p>প্রভাতফেরি, আলোচনা সভা, সাংস্কৃতিক অনুষ্ঠানসহ বিভিন্ন কর্মসূচি গ্রহণ করা হয়।</p>
                ',
                'author' => 'খুকৃবি সংবাদ',
                'published_at' => '2025-08-06',
                'read_time' => 3,
                'category' => 'ক্যাম্পাস',
                'tags' => json_encode(['দিবস', 'অনুষ্ঠান']),
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'title' => 'রাজধানীর উত্তরায় প্রশিক্ষণ বিমান বিধ্বস্ত হয়ে বহু হতাহতের ঘটনায় খুকৃবি উপাচার্যের শোক',
                'slug' => Str::slug('রাজধানীর উত্তরায় প্রশিক্ষণ বিমান বিধ্বস্ত হয়ে বহু হতাহতের ঘটনায় খুকৃবি উপাচার্যের শোক'),
                'thumb_image' => 'uploads/news/thumb2.jpg',
                'content_image' => 'uploads/news/content2.jpg',
                'banner_image' => 'uploads/news/banner2.jpg',
                'summary' => 'উত্তরায় বিমান বিধ্বস্তে হতাহতের ঘটনায় গভীর শোক প্রকাশ করেছেন খুলনা কৃষি বিশ্ববিদ্যালয়ের উপাচার্য।',
                'content' => '
                    <p>রাজধানীর উত্তরায় প্রশিক্ষণ বিমান বিধ্বস্ত হয়ে বহু হতাহতের ঘটনায় খুকৃবি উপাচার্য গভীর শোক প্রকাশ করেছেন।</p>
                    <p>তিনি নিহতদের পরিবারের প্রতি সমবেদনা জানান এবং আহতদের দ্রুত সুস্থতা কামনা করেন।</p>
                ',
                'author' => 'খুকৃবি সংবাদ',
                'published_at' => '2025-07-23',
                'read_time' => 2,
                'category' => 'দুর্ঘটনা',
                'tags' => json_encode(['শোক', 'দুর্ঘটনা']),
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'title' => 'খুলনা কৃষি বিশ্ববিদ্যালয়ে মহান স্বাধীনতা দিবস উদযাপন',
                'slug' => Str::slug('খুলনা কৃষি বিশ্ববিদ্যালয়ে মহান স্বাধীনতা দিবস উদযাপন'),
                'thumb_image' => 'uploads/news/thumb3.jpg',
                'content_image' => 'uploads/news/content3.jpg',
                'banner_image' => 'uploads/news/banner3.jpg',
                'summary' => 'মহান স্বাধীনতা দিবস উপলক্ষে খুলনা কৃষি বিশ্ববিদ্যালয়ে নানা কর্মসূচি পালিত হয়েছে।',
                'content' => '
                    <p>খুলনা কৃষি বিশ্ববিদ্যালয়ে মহান স্বাধীনতা দিবস উদযাপন উপলক্ষে পতাকা উত্তোলন, সাংস্কৃতিক অনুষ্ঠান ও আলোচনা সভার আয়োজন করা হয়।</p>
                ',
                'author' => 'খুকৃবি সংবাদ',
                'published_at' => '2025-03-26',
                'read_time' => 3,
                'category' => 'জাতীয়',
                'tags' => json_encode(['স্বাধীনতা', 'অনুষ্ঠান']),
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'title' => 'খুকৃবিতে আন্তর্জাতিক মাতৃভাষা দিবস পালিত',
                'slug' => Str::slug('খুকৃবিতে আন্তর্জাতিক মাতৃভাষা দিবস পালিত'),
                'thumb_image' => 'uploads/news/thumb4.jpg',
                'content_image' => 'uploads/news/content4.jpg',
                'banner_image' => 'uploads/news/banner4.jpg',
                'summary' => 'অমর একুশে ও আন্তর্জাতিক মাতৃভাষা দিবস উপলক্ষে খুকৃবিতে পুষ্পস্তবক অর্পণ ও আলোচনা সভা অনুষ্ঠিত।',
                'content' => '
                    <p>খুকৃবিতে আন্তর্জাতিক মাতৃভাষা দিবস উপলক্ষে শহীদ মিনারে পুষ্পস্তবক অর্পণ ও আলোচনা সভার আয়োজন করা হয়।</p>
                ',
                'author' => 'খুকৃবি সংবাদ',
                'published_at' => '2025-02-21',
                'read_time' => 2,
                'category' => 'দিবস',
                'tags' => json_encode(['মাতৃভাষা', 'একুশে']),
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'title' => 'খুলনা কৃষি বিশ্ববিদ্যালয়ের ভিসি হলেন প্রফেসর ড. নাজমুল আহসান',
                'slug' => Str::slug('খুলনা কৃষি বিশ্ববিদ্যালয়ের ভিসি হলেন প্রফেসর ড. নাজমুল আহসান'),
                'thumb_image' => 'uploads/news/thumb5.jpg',
                'content_image' => 'uploads/news/content5.jpg',
                'banner_image' => 'uploads/news/banner5.jpg',
                'summary' => 'খৃশুবির নতুন উপাচার্য হিসেবে দায়িত্ব গ্রহণ করলেন প্রফেসর ড. নাজমুল আহসান।',
                'content' => '
                    <p>খুলনা কৃষি বিশ্ববিদ্যালয়ের ভিসি হিসেবে দায়িত্ব গ্রহণ করেন প্রফেসর ড. নাজমুল আহসান।</p>
                    <p>তিনি বিশ্ববিদ্যালয়ের উন্নয়নে কার্যকর ভূমিকা রাখার প্রত্যয় ব্যক্ত করেন।</p>
                ',
                'author' => 'খুকৃবি সংবাদ',
                'published_at' => '2024-10-29',
                'read_time' => 4,
                'category' => 'প্রশাসন',
                'tags' => json_encode(['ভিসি', 'নিয়োগ']),
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'title' => 'খুলনা কৃষি বিশ্ববিদ্যালয়ে পরিবেশ সচেতনতায় অন্যরকম আয়োজন।',
                'slug' => Str::slug('খুলনা কৃষি বিশ্ববিদ্যালয়ে পরিবেশ সচেতনতায় অন্যরকম আয়োজন'),
                'thumb_image' => 'uploads/news/thumb6.jpg',
                'content_image' => 'uploads/news/content6.jpg',
                'banner_image' => 'uploads/news/banner6.jpg',
                'summary' => 'পরিবেশ সচেতনতা বাড়াতে খুকৃবিতে বিশেষ কর্মসূচির আয়োজন করা হয়।',
                'content' => '
                    <p>পরিবেশ সুরক্ষায় শিক্ষার্থীদের মধ্যে সচেতনতা বৃদ্ধি করতে খুকৃবিতে বিশেষ কর্মসূচির আয়োজন করা হয়।</p>
                ',
                'author' => 'খুকৃবি সংবাদ',
                'published_at' => '2024-06-11',
                'read_time' => 3,
                'category' => 'পরিবেশ',
                'tags' => json_encode(['পরিবেশ', 'সচেতনতা']),
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        DB::table('news')->insert($newsItems);
    }
}
