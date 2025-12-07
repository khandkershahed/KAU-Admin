<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            [
                'group_name' => 'Dashboard',
                'permissions' => [
                    'view dashboard',
                ],
            ],
            [
                'group_name' => 'Manage Homepage',
                'permissions' => [
                    'manage homepage'
                ],
            ],
            [
                'group_name' => 'Manage About Page',
                'permissions' => [
                    'view about page',
                    'create about page',
                    'edit about page',
                    'delete about page',
                ],
            ],
            // [
            //     'group_name' => 'Banner',
            //     'permissions' => [
            //         'view banner',
            //         'create banner',
            //         'edit banner',
            //         'delete banner',
            //         'toggle banner status',
            //     ],
            // ],

            // [
            //     'group_name' => 'Blog Category',
            //     'permissions' => [
            //         'view blog category',
            //         'create blog category',
            //         'edit blog category',
            //         'delete blog category',
            //     ],
            // ],

            // [
            //     'group_name' => 'Blog',
            //     'permissions' => [
            //         'view blog',
            //         'create blog',
            //         'edit blog',
            //         'delete blog',
            //     ],
            // ],

            [
                'group_name' => 'FAQ',
                'permissions' => [
                    'view faq',
                    'create faq',
                    'edit faq',
                    'delete faq',
                ],
            ],

            [
                'group_name' => 'Terms',
                'permissions' => [
                    'view terms',
                    'create terms',
                    'edit terms',
                    'delete terms',
                ],
            ],

            [
                'group_name' => 'Privacy',
                'permissions' => [
                    'view privacy',
                    'create privacy',
                    'edit privacy',
                    'delete privacy',
                ],
            ],

            [
                'group_name' => 'Setting',
                'permissions' => [
                    'view setting',
                    'update setting',
                ],
            ],

            [
                'group_name' => 'Contact',
                'permissions' => [
                    'view contact',
                    'create contact',
                    'edit contact',
                    'delete contact',
                ],
            ],

            [
                'group_name' => 'Subscription',
                'permissions' => [
                    'view subscription',
                    'create subscription',
                    'edit subscription',
                    'delete subscription',
                ],
            ],
            [
                'group_name' => 'Notice Category',
                'permissions' => [
                    'view notice category',
                    'create notice category',
                    'edit notice category',
                    'delete notice category',
                ],
            ],
            [
                'group_name' => 'Notice',
                'permissions' => [
                    'view notice',
                    'create notice',
                    'edit notice',
                    'delete notice',
                ],
            ],
            [
                'group_name' => 'News',
                'permissions' => [
                    'view news',
                    'create news',
                    'edit news',
                    'delete news',
                ],
            ],


            [
                'group_name' => 'Administration Menu',
                'permissions' => [
                    'view admin group',
                    'create admin group',
                    'edit admin group',
                    'delete admin group',
                ],
            ],
            [
                'group_name' => 'Administration Office',
                'permissions' => [
                    'view admin office',
                    'create admin office',
                    'edit admin office',
                    'delete admin office',
                ],
            ],
            [
                'group_name' => 'Administration Section',
                'permissions' => [
                    'view admin section',
                    'create admin section',
                    'edit admin section',
                    'delete admin section',
                ],
            ],
            [
                'group_name' => 'Administration Member',
                'permissions' => [
                    'view admin member',
                    'create admin member',
                    'edit admin member',
                    'delete admin member',
                ],
            ],

            [
                'group_name' => 'Admission',
                'permissions' => [
                    'view admission',
                    'create admission',
                    'edit admission',
                    'delete admission',
                ],
            ],


            [
                'group_name' => 'User',
                'permissions' => [
                    'view user',
                    'create user',
                    'edit user',
                    'delete user',
                    'show user',
                ],
            ],

            [
                'group_name' => 'Staff',
                'permissions' => [
                    'view staff',
                    'create staff',
                    'edit staff',
                    'delete staff',
                    'show staff',
                ],
            ],

            [
                'group_name' => 'Role',
                'permissions' => [
                    'view role',
                    'create role',
                    'edit role',
                    'delete role',
                    'give permission role',
                    'store permission role',
                ],
            ],

            [
                'group_name' => 'Permission',
                'permissions' => [
                    'view permission',
                    'create permission',
                    'edit permission',
                    'delete permission',
                ],
            ],

        ];

        // Create Super Admin role
        $roleAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'admin',
        ]);

        // Create permissions
        foreach ($permissions as $permissionGroup) {
            $group = $permissionGroup['group_name'];

            foreach ($permissionGroup['permissions'] as $permissionName) {

                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'group_name' => $group,
                    'guard_name' => 'admin',
                ]);

                $roleAdmin->givePermissionTo($permission);
            }
        }

        // Assign role to default admin
        $admin = Admin::where('email', 'khandkershahed23@gmail.com')->first();
        if ($admin) {
            $admin->assignRole($roleAdmin);
        }
    }
}
