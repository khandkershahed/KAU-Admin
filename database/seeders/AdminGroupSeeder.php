<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminGroup;

class AdminGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'name'     => 'Authorities',
                'slug'     => 'authorities',
                'position' => 1,
            ],
            [
                'name'     => 'Administrative',
                'slug'     => 'administrative',
                'position' => 2,
            ],
            [
                'name'     => 'Directorates',
                'slug'     => 'directorates',
                'position' => 3,
            ],
        ];

        foreach ($groups as $g) {
            AdminGroup::updateOrCreate(
                ['slug' => $g['slug']],
                [
                    'name'     => $g['name'],
                    'position' => $g['position'],
                    'status'   => true,
                ]
            );
        }
    }
}
