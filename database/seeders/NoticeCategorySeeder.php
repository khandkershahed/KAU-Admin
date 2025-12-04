<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class NoticeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Notice',
                'slug' => Str::slug('Notice'),
                'meta_title' => 'University Notice',
                'meta_tags' => 'notice, university notice',
                'description' => 'General notices for students and staff.',
                'status' => 'active',
            ],
            [
                'name' => 'Office Order',
                'slug' => Str::slug('Office Order'),
                'meta_title' => 'Office Order',
                'meta_tags' => 'office order, university office order',
                'description' => 'Official orders released by the administration.',
                'status' => 'active',
            ],
            [
                'name' => 'NOC',
                'slug' => Str::slug('NOC'),
                'meta_title' => 'No Objection Certificate',
                'meta_tags' => 'noc, university noc',
                'description' => 'NOC-related documents and notices.',
                'status' => 'active',
            ],
        ];

        DB::table('notice_categories')->insert($categories);
    }
}
