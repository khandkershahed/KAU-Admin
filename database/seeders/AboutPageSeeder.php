<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutPage;

class AboutPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title'           => 'KAU at a Glance',
                'slug'            => 'kau-at-a-glance',
                'menu_label'      => 'KAU at a Glance',
                'banner_title'    => 'About KAU',
                'banner_subtitle' => 'Fostering new ideas and innovation to achieve excellence in academics and research.',
                'banner_icon'     => 'fa-solid fa-graduation-cap',
                'excerpt'         => 'Khulna Agricultural University (KAU) is the 5th public agricultural university of Bangladesh and 1st in the southern region.',
                'content'         => '<p>Khulna Agricultural University (KAU) is the 5th public agricultural university of Bangladesh and the 1st in the southern region. By fostering new ideas and innovation, it has begun a steady journey toward achieving a new level of excellence in academics and research in the agricultural sector.</p>',
                'is_featured'     => 1,
                'status'          => 'published',
            ],
            [
                'title'       => 'Chancellor',
                'slug'        => 'chancellor',
                'menu_label'  => 'Chancellor',
                'banner_title'=> 'Chancellor',
                'excerpt'     => 'Message from the Honorable Chancellor of Khulna Agricultural University.',
                'content'     => '<p>Content for the Chancellor page.</p>',
                'is_featured' => 0,
                'status'      => 'published',
            ],
            [
                'title'       => 'Vice-Chancellor',
                'slug'        => 'vice-chancellor',
                'menu_label'  => 'Vice-Chancellor',
                'banner_title'=> 'Vice-Chancellor',
                'excerpt'     => 'Message from the Honorable Vice-Chancellor of KAU.',
                'content'     => '<p>Content for the Vice-Chancellor page.</p>',
                'is_featured' => 0,
                'status'      => 'published',
            ],
            [
                'title'       => 'Pro Vice-Chancellor',
                'slug'        => 'pro-vice-chancellor',
                'menu_label'  => 'Pro Vice-Chancellor',
                'banner_title'=> 'Pro Vice-Chancellor',
                'excerpt'     => 'Message from the Pro Vice-Chancellor of KAU.',
                'content'     => '<p>Content for the Pro Vice-Chancellor page.</p>',
                'is_featured' => 0,
                'status'      => 'published',
            ],
            [
                'title'       => 'Treasurer',
                'slug'        => 'treasurer',
                'menu_label'  => 'Treasurer',
                'banner_title'=> 'Treasurer',
                'excerpt'     => 'Message from the Treasurer of KAU.',
                'content'     => '<p>Content for the Treasurer page.</p>',
                'is_featured' => 0,
                'status'      => 'published',
            ],
            [
                'title'           => 'Mission and Vision',
                'slug'            => 'mission-and-vision',
                'menu_label'      => 'Mission and Vision',
                'banner_title'    => 'Mission & Vision',
                'banner_subtitle' => 'Our unwavering commitment to excellence in agricultural education, research, and community engagement.',
                'excerpt'         => 'KAU aims to gain quality in agricultural sectors parallel to advanced countries.',
                'content'         => '<p>Content for Mission and Vision page. You can later replace this with your designed layout (vision and mission cards, images, etc.).</p>',
                'is_featured'     => 1,
                'status'          => 'published',
            ],
        ];

        foreach ($pages as $index => $data) {
            $data['menu_order'] = $index + 1;

            AboutPage::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
