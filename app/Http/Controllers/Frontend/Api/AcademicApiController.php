<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Models\AcademicPage;
use App\Models\AcademicSite;
use App\Models\AcademicMenuGroup;
use App\Models\AcademicDepartment;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademicApiController extends Controller
{
    /**
     * GET /api/v1/academics/sites
     * Returns groups and their published sites.
     */
    public function sites(): JsonResponse
    {
        $groups = AcademicMenuGroup::with([
            'sites' => function ($q) {
                $q->where('status', 'published')
                    ->orderBy('position')
                    ->orderBy('id');
            }
        ])
            ->where('status', 'published')
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        return response()->json([
            'groups' => $groups->map(function ($group) {
                return [
                    'id'       => $group->id,
                    'title'    => $group->title,
                    'slug'     => $group->slug,
                    'position' => (int)$group->position,
                    'sites'    => $group->sites->map(function ($site) {
                        return [
                            'id'                   => $site->id,
                            'name'                 => $site->name,
                            'short_name'           => $site->short_name,
                            'slug'                 => $site->slug,
                            'short_description'    => $site->short_description,
                            'theme_primary_color'  => $site->theme_primary_color,
                            'theme_secondary_color' => $site->theme_secondary_color,
                            'logo_url'             => $site->logo_path ? asset('storage/' . $site->logo_path) : null,
                            'position'             => (int) $site->position,
                        ];
                    })->values(),
                ];
            })->values()
        ]);
    }

    /**
     * GET /api/v1/academics/sites/{site_slug}/pages
     * Without ?slug= → returns: site info + nav tree + pages list
     * With    ?slug= → returns: full page details
     */
    // public function sitePages(Request $request, string $site_slug): JsonResponse
    // {
    //     $site = AcademicSite::where('slug', $site_slug)
    //         ->where('status', 'published')
    //         ->firstOrFail();

    //     /* --------------------------------------------------------
    //      * SINGLE PAGE REQUEST
    //      * -------------------------------------------------------- */
    //     if ($request->filled('slug')) {
    //         $page = AcademicPage::where('academic_site_id', $site->id)
    //             ->where('slug', $request->query('slug'))
    //             ->where('status', 'published')
    //             ->firstOrFail();

    //         return response()->json([
    //             'site' => [
    //                 'id'                   => $site->id,
    //                 'slug'                 => $site->slug,
    //                 'name'                 => $site->name,
    //                 'short_name'           => $site->short_name,
    //                 'short_description'    => $site->short_description,
    //                 'theme_primary_color'  => $site->theme_primary_color,
    //                 'theme_secondary_color' => $site->theme_secondary_color,
    //                 'logo_url'             => $site->logo_path ? asset('storage/' . $site->logo_path) : null,
    //             ],
    //             'page' => [
    //                 'id'                   => $page->id,
    //                 'page_key'             => $page->page_key,
    //                 'slug'                 => $page->slug,
    //                 'title'                => $page->title,
    //                 'subtitle'             => $page->subtitle,
    //                 'is_home'              => (bool) $page->is_home,
    //                 'is_department_boxes'  => (bool) $page->is_department_boxes,

    //                 'banner_image'         => $page->banner_image ? asset('storage/' . $page->banner_image) : null,
    //                 'banner_title'         => $page->banner_title,
    //                 'banner_subtitle'      => $page->banner_subtitle,
    //                 'banner_button'        => $page->banner_button,
    //                 'banner_button_url'    => $page->banner_button_url,

    //                 'content'              => $page->content,
    //                 'meta_title'           => $page->meta_title,
    //                 'meta_tags'            => $page->meta_tags,
    //                 'meta_description'     => $page->meta_description,
    //                 'og_image'             => $page->og_image ? asset('storage/' . $page->og_image) : null,

    //                 'position'             => (int) $page->position,
    //             ]
    //         ]);
    //     }

    //     /* --------------------------------------------------------
    //      * FULL SITE: SITE + NAV + PAGES
    //      * -------------------------------------------------------- */

    //     $site->load([
    //         'navItems' => fn($q) => $q->where('status', 'published')
    //             ->with('page')
    //             ->orderBy('position')->orderBy('id'),

    //         'pages' => fn($q) => $q->where('status', 'published')
    //             ->orderBy('position')->orderBy('id'),
    //     ]);

    //     // $navItems = $site->navItems;
    //     // $grouped  = $navItems->groupBy('parent_id');

    //     // $buildTree = function ($parentId) use (&$buildTree, $grouped) {
    //     //     return $grouped->get($parentId, collect())
    //     //         ->map(function ($item) use (&$buildTree) {
    //     //             return [
    //     //                 'label'        => $item->label,
    //     //                 'slug'         => $item->slug,
    //     //                 'type'         => $item->type,
    //     //                 'external_url' => $item->external_url,
    //     //                 'page_slug'    => $item->page?->slug,
    //     //                 'position'     => (int)$item->position,
    //     //                 'children'     => $buildTree($item->id),
    //     //             ];
    //     //         })->values();
    //     // };

    //     // $navTree = $buildTree(null);
    //     // Preload departments for faculty member navigation expansion
    //     $departments = AcademicDepartment::where('academic_site_id', $site->id)
    //         ->where('status', 'published')
    //         ->orderBy('position')
    //         ->orderBy('id')
    //         ->get();

    //     $navItems = $site->navItems;
    //     $grouped  = $navItems->groupBy('parent_id');

    //     $buildTree = function ($parentId) use (&$buildTree, $grouped, $departments) {
    //         return $grouped->get($parentId, collect())
    //             ->map(function ($item) use (&$buildTree, $departments) {

    //                 // Build normal children first
    //                 $children = $buildTree($item->id);

    //                 /** ----------------------------------------------------
    //                  * EXPAND NAV ITEM IF PAGE HAS is_faculty_members = true
    //                  * ---------------------------------------------------- */
    //                 if ($item->page && $item->page->is_faculty_members) {

    //                     $deptChildren = $departments->map(function ($dept) {
    //                         return [
    //                             'label'        => $dept->title,
    //                             'slug'         => $dept->slug,
    //                             'type'         => 'department',
    //                             'external_url' => null,
    //                             'page_slug'    => null,
    //                             'position'     => (int)$dept->position,
    //                             'children'     => [], // departments do not have sub-items
    //                         ];
    //                     })->values();

    //                     // Append department nodes
    //                     $children = $children->merge($deptChildren);
    //                 }

    //                 return [
    //                     'label'        => $item->label,
    //                     'slug'         => $item->slug,
    //                     'type'         => $item->type,
    //                     'external_url' => $item->external_url,
    //                     'page_slug'    => $item->page?->slug,
    //                     'position'     => (int)$item->position,
    //                     'children'     => $children,
    //                 ];
    //             })->values();
    //     };

    //     $navTree = $buildTree(null);


    //     return response()->json([
    //         'site' => [
    //             'slug'                 => $site->slug,
    //             'name'                 => $site->name,
    //             'short_name'           => $site->short_name,
    //             'short_description'    => $site->short_description,
    //             'theme_primary_color'  => $site->theme_primary_color,
    //             'theme_secondary_color' => $site->theme_secondary_color,
    //             'logo_url'             => $site->logo_path ? asset('storage/' . $site->logo_path) : null,
    //         ],
    //         'navigation' => $navTree,
    //         'pages'      => $site->pages->map(function ($p) {
    //             return [
    //                 'slug'                => $p->slug,
    //                 'title'               => $p->title,
    //                 'is_home'             => (bool)$p->is_home,
    //                 'is_department_boxes' => (bool)$p->is_department_boxes,
    //                 'is_faculty_members'  => (bool)$p->is_faculty_members,

    //                 'banner_image'        => $p->banner_image ? asset('storage/' . $p->banner_image) : null,
    //                 'banner_title'        => $p->banner_title,
    //                 'banner_subtitle'     => $p->banner_subtitle,
    //                 'banner_button'       => $p->banner_button,
    //                 'banner_button_url'   => $p->banner_button_url,

    //                 'content'             => $p->content,
    //                 'meta_title'          => $p->meta_title,
    //                 'meta_tags'           => $p->meta_tags,
    //                 'meta_description'    => $p->meta_description,
    //                 'og_image'            => $p->og_image ? asset('storage/' . $p->og_image) : null,

    //                 'position'            => (int)$p->position,
    //             ];
    //         })->values()
    //     ]);
    // }

    public function sitePages(string $site_slug)
    {
        $site = AcademicSite::where('slug', $site_slug)
            ->with([
                // pages for mapping & output
                'pages' => function ($q) {
                    $q->orderByDesc('is_home')
                        ->orderBy('position')
                        ->orderBy('id');
                },
                // nav items + their linked page
                'navItems.page',
            ])
            ->firstOrFail();

        // All departments of this site (used when nav item is_faculty_members)
        $departments = AcademicDepartment::where('academic_site_id', $site->id)
            ->where('status', 'published')
            ->orderBy('position')
            ->orderBy('id')
            ->get();

            

        // Raw nav items collection
        $navItems = $site->navItems->sortBy('position')->values();

        // Group nav items by parent
        $grouped = $navItems->groupBy('parent_id');

        $buildTree = function ($parentId) use (&$buildTree, $grouped, $departments) {
            return $grouped->get($parentId, collect())
                ->map(function ($item) use (&$buildTree, $departments) {

                    // Normal children first (other nav items)
                    $children = $buildTree($item->id);

                    // Check if this nav item is bound to a faculty-members page
                    $isFacultyMembersPage = $item->page && $item->page->is_faculty_members;

                    /**
                     * If this is the Faculty Member page:
                     * append department nodes as children
                     */
                    if ($isFacultyMembersPage) {
                        $deptChildren = $departments->map(function ($dept) {
                            return [
                                'label'         => $dept->title,
                                'slug'          => $dept->slug,
                                'type'          => 'faculty_member_department', // frontend can check this
                                'external_url'  => null,
                                'page_slug'     => null, // no direct page slug; usually /faculty-member/{dept.slug}
                                'department_id' => $dept->id,
                                'short_code'    => $dept->short_code,
                                'position'      => (int) $dept->position,
                                'children'      => [], // departments do not have sub-items
                            ];
                        })->values();

                        // Merge existing children + department children
                        $children = $children->merge($deptChildren);
                    }

                    return [
                        'label'        => $item->label,
                        'slug'         => $item->slug,
                        // if it's the faculty-members page, expose a special type
                        'type'         => $isFacultyMembersPage ? 'faculty_member_page' : $item->type,
                        'external_url' => $item->external_url,
                        'page_slug'    => $item->page?->slug,
                        'position'     => (int) $item->position,
                        'children'     => $children,
                    ];
                })->values();
        };

        $navTree = $buildTree(null);

        return response()->json([
            'site' => [
                'slug'                  => $site->slug,
                'name'                  => $site->name,
                'short_name'            => $site->short_name,
                'short_description'     => $site->short_description,
                'theme_primary_color'   => $site->theme_primary_color,
                'theme_secondary_color' => $site->theme_secondary_color,
                'logo_url'              => $site->logo_path ? asset('storage/' . $site->logo_path) : null,
            ],
            'navigation' => $navTree,
            'pages'      => $site->pages->map(function ($p) {
                return [
                    'slug'                => $p->slug,
                    'title'               => $p->title,
                    'is_home'             => (bool) $p->is_home,
                    'is_department_boxes' => (bool) $p->is_department_boxes,
                    'is_faculty_members'  => (bool) $p->is_faculty_members,

                    'banner_image'        => $p->banner_image ? asset('storage/' . $p->banner_image) : null,
                    'banner_title'        => $p->banner_title,
                    'banner_subtitle'     => $p->banner_subtitle,
                    'banner_button'       => $p->banner_button,
                    'banner_button_url'   => $p->banner_button_url,

                    'content'             => $p->content,
                    'meta_title'          => $p->meta_title,
                    'meta_tags'           => $p->meta_tags,
                    'meta_description'    => $p->meta_description,
                    'og_image'            => $p->og_image ? asset('storage/' . $p->og_image) : null,

                    'position'            => (int) $p->position,
                ];
            })->values(),
        ]);
    }



    public function siteDepartmentsStaff(string $site_slug): JsonResponse
    {
        $site = AcademicSite::where('slug', $site_slug)
            ->where('status', 'published')
            ->firstOrFail();

        $site->load([
            'departments' => fn($q) => $q->where('status', 'published')
                ->orderBy('position')->orderBy('id'),

            'staffSections.members' => fn($q) =>
            $q->where('status', 'published')
                ->orderBy('position')->orderBy('id'),
        ]);

        // Index by department ID
        $departments = $site->departments;
        $deptById    = $departments->keyBy('id');

        // Group staff sections by department
        $sectionsByDept = $site->staffSections->groupBy('academic_department_id');

        $staffStructure = $sectionsByDept
            ->map(function ($sections, $deptId) use ($deptById) {

                $dept = $deptById->get($deptId);
                if (!$dept) return null;

                return [
                    'department_id'    => $dept->id,
                    'department_title' => $dept->title,
                    'short_code'       => $dept->short_code,
                    'slug'             => $dept->slug,
                    'groups'           => $sections->sortBy('position')
                        ->values()
                        ->map(function ($section) {
                            return [
                                'id'       => $section->id,
                                'title'    => $section->title,
                                'position' => (int)$section->position,
                                'members'  => $section->members
                                    ->sortBy('position')
                                    ->values()
                                    ->map(function ($m) {
                                        return [
                                            'id'          => $m->id,
                                            'name'        => $m->name,
                                            'designation' => $m->designation,
                                            'email'       => $m->email,
                                            'phone'       => $m->phone,
                                            'image'       => $m->image_path ? asset('storage/' . $m->image_path) : null,
                                            'position'    => (int)$m->position,
                                            'links'       => $m->links ?? [],
                                        ];
                                    }),
                            ];
                        }),
                ];
            })
            ->filter()
            ->values();

        return response()->json([
            'site' => [
                'id'         => $site->id,
                'slug'       => $site->slug,
                'name'       => $site->name,
                'short_name' => $site->short_name,
            ],
            'departments' => $departments->map(function ($d) {
                return [
                    'id'          => $d->id,
                    'title'       => $d->title,
                    'short_code'  => $d->short_code,
                    'slug'        => $d->slug,
                    'description' => $d->description,
                    'position'    => (int)$d->position,
                ];
            })->values(),
            'staff' => $staffStructure,
        ]);
    }
}
