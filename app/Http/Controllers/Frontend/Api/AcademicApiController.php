<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Models\AcademicPage;
use App\Models\AcademicSite;
use Illuminate\Http\Request;
use App\Models\AcademicMenuGroup;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class AcademicApiController extends Controller
{
    /**
     * GET /api/academics/sites
     * Groups with sites (for main "Academics" dropdown).
     */
    public function sites(): JsonResponse
    {
        $data = Cache::remember('api_academics_sites', 0, function () {
            $groups = AcademicMenuGroup::with(['sites' => function ($q) {
                $q->published()->orderBy('menu_order')->orderBy('id');
            }])
                ->active()
                ->ordered()
                ->get();

            return [
                'groups' => $groups->map(function ($group) {
                    return [
                        'id'       => $group->id,
                        'title'    => $group->title,
                        'slug'     => $group->slug,
                        'position' => (int) $group->position,
                        'sites'    => $group->sites->map(function ($site) {
                            return [
                                'id'                  => $site->id,
                                'slug'                => $site->slug,
                                'name'                => $site->name,
                                'short_name'          => $site->short_name,
                                'base_url'            => $site->base_url,
                                'short_description'   => $site->short_description,
                                'theme_primary_color' => $site->theme_primary_color,
                                'theme_secondary_color' => $site->theme_secondary_color,
                                'logo_url'            => $site->logo_path ? asset('storage/' . $site->logo_path) : null,
                                'menu_order'          => (int) $site->menu_order,
                            ];
                        })->values(),
                    ];
                })->values(),
            ];
        });

        return response()->json($data);
    }

    /**
     * GET /api/academics/sites/{site_slug}/pages
     *
     * - Without ?slug= : site info + nav + pages (no full content)
     * - With ?slug=xxx : single page with full content.
     */
    public function sitePages(Request $request, string $site_slug): JsonResponse
    {
        $site = AcademicSite::where('slug', $site_slug)
            ->published()
            ->firstOrFail();

        // If we have ?slug=... => return single page details
        if ($request->filled('slug')) {
            $pageSlug = $request->query('slug');

            $page = AcademicPage::where('academic_site_id', $site->id)
                ->where('slug', $pageSlug)
                ->active()
                ->firstOrFail();

            return response()->json([
                'site' => [
                    'id'                  => $site->id,
                    'slug'                => $site->slug,
                    'name'                => $site->name,
                    'short_name'          => $site->short_name,
                    'base_url'            => $site->base_url,
                    'theme_primary_color' => $site->theme_primary_color,
                    'theme_secondary_color' => $site->theme_secondary_color,
                    'logo_url'            => $site->logo_path ? asset('storage/' . $site->logo_path) : null,
                ],
                'page' => [
                    'id'                  => $page->id,
                    'page_key'            => $page->page_key,
                    'slug'                => $page->slug,
                    'title'               => $page->title,
                    'subtitle'            => $page->subtitle,
                    'is_home'             => (bool) $page->is_home,
                    'is_department_boxes' => (bool) $page->is_department_boxes,
                    'banner_image'        => $page->banner_image ? asset('storage/' . $page->banner_image): null,
                    'content'             => $page->content,
                    'meta_title'          => $page->meta_title,
                    'meta_tags'           => $page->meta_tags,
                    'meta_description'    => $page->meta_description,
                    'og_image'            => $page->og_image ? asset('storage/' . $page->og_image)        : null,
                    'banner_title'        => $page->banner_title,
                    'banner_subtitle'     => $page->banner_subtitle,
                    'banner_button'       => $page->banner_button,
                    'banner_button_url'   => $page->banner_button_url,
                ],
            ]);
        }

        // No slug: structure + pages list (no heavy content)
        $cacheKey = 'api_academics_site_' . $site->id . '_pages_index';

        $data = Cache::remember($cacheKey, 0, function () use ($site) {
            $site->load([
                'navItems' => function ($q) {
                    $q->active()->orderBy('position')->orderBy('id');
                },
                'pages' => function ($q) {
                    $q->active()->orderBy('position')->orderBy('id');
                },
            ]);

            $navItems = $site->navItems;

            // Build nav tree
            $navByParent = $navItems->groupBy('parent_id');

            $buildTree = function ($parentId) use (&$buildTree, $navByParent) {
                return ($navByParent[$parentId] ?? collect())->map(function ($item) use (&$buildTree) {
                    return [
                        'id'        => $item->id,
                        'label'     => $item->label,
                        'menu_key'  => $item->menu_key,
                        'type'      => $item->type,
                        'route'     => $item->route_path,
                        'external_url' => $item->external_url,
                        'page_id'   => $item->page_id,
                        'page_slug' => $item->page ? $item->page->slug : null,
                        'position'  => (int) $item->position,
                        'children'  => $buildTree($item->id),
                    ];
                })->values();
            };

            $navTree = $buildTree(null);

            // Pages (light)
            $pagesList = $site->pages->map(function ($p) {
                return [
                    'id'                => $p->id,
                    'page_key'          => $p->page_key,
                    'slug'              => $p->slug,
                    'is_home'           => (bool) $p->is_home,
                    'is_department_boxes'           => (bool) $p->is_department_boxes,
                    'title'             => $p->title,
                    'subtitle'          => $p->subtitle,
                    'content'           => $p->content,
                    'banner_image'      => $p->banner_image ? asset('storage/' . $p->banner_image): null,
                    'banner_title'      => $p->banner_title,
                    'banner_subtitle'   => $p->banner_subtitle,
                    'banner_button'     => $p->banner_button,
                    'banner_button_url' => $p->banner_button_url,
                    'meta_title'        => $p->meta_title,
                    'meta_tags'         => $p->meta_tags,
                    'meta_description'  => $p->meta_description,
                    'og_image'          => $p->og_image ? asset('storage/' . $p->og_image)        : null,

                    'position'          => (int) $p->position,
                ];
            })->values();

            return [
                'site' => [
                    'id'                  => $site->id,
                    'slug'                => $site->slug,
                    'name'                => $site->name,
                    'short_name'          => $site->short_name,
                    'base_url'            => $site->base_url,
                    'short_description'   => $site->short_description,
                    'theme_primary_color' => $site->theme_primary_color,
                    'theme_secondary_color' => $site->theme_secondary_color,
                    'logo_url'            => $site->logo_path ? asset('storage/' . $site->logo_path) : null,
                ],
                'navigation' => $navTree,
                'pages'      => $pagesList,
            ];
        });

        return response()->json($data);
    }

    /**
     * GET /api/academics/sites/{site_slug}/departments-and-staff
     *
     * Returns departments + departmentwise staff sections + members.
     */
    public function siteDepartmentsStaff(string $site_slug): JsonResponse
    {
        $site = AcademicSite::where('slug', $site_slug)
            ->published()
            ->firstOrFail();

        $cacheKey = 'api_academics_site_' . $site->id . '_departments_staff';

        $data = Cache::remember($cacheKey, 0, function () use ($site) {
            $site->load([
                'departments' => function ($q) {
                    $q->where('is_active', true)
                        ->orderBy('position')
                        ->orderBy('id');
                },
                'staffSections.members',
            ]);

            $departments = $site->departments;

            // index departments by id
            $deptById = $departments->keyBy('id');

            // group sections by department
            $sectionsByDept = $site->staffSections
                ->groupBy('academic_department_id');

            $staffBlock = $sectionsByDept->map(function ($sections, $deptId) use ($deptById) {
                $dept = $deptById->get($deptId);

                if (!$dept) {
                    return null;
                }

                return [
                    'department_id'    => $dept->id,
                    'department_title' => $dept->title,
                    'short_code'       => $dept->short_code,
                    'slug'             => $dept->slug,
                    'groups'           => $sections
                        ->sortBy('position')
                        ->values()
                        ->map(function ($section) {
                            return [
                                'id'       => $section->id,
                                'title'    => $section->title,
                                'position' => (int) $section->position,
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
                                            'position'    => (int) $m->position,
                                            'links'       => $m->links ?? [],
                                        ];
                                    }),
                            ];
                        })->values(),
                ];
            })->filter()->values();

            return [
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
                        'position'    => (int) $d->position,
                    ];
                })->values(),
                'staff' => $staffBlock,
            ];
        });

        return response()->json($data);
    }
}
