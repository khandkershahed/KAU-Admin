<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\EventSeatSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class, // 1st
            RolePermissionSeeder::class, // 2nd
            SettingSeeder::class, // 2nd
            NoticeCategorySeeder::class,
            NoticeSeeder::class,
            NewsSeeder::class,
            AdminGroupSeeder::class,
            AdminOfficeSeeder::class,
            AdminOfficeSectionSeeder::class,
            AdminOfficeMemberSeeder::class,

        ]);
        // $this->call(UserSeeder::class);
    }
}
