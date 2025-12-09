<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicMenuGroup;
use App\Models\AcademicSite;
use App\Models\AcademicPage;
use App\Models\AcademicDepartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademicApiController extends Controller
{
    /**
     * 1) List groups + sites for main Academic menu.
     */
    public function sites(): JsonResponse
    {
        $groups = AcademicMenuGroup::with(['sites' => function ($q) {
            $q->orderBy('menu_order');
        }])
            ->active()
            ->ordered()
            ->get();

        $data = $groups->map(function ($group) {
            return [
                'id'       => $group->id,
                'title'    => $group->title,
                'slug'     => $group->slug,
                'position' => (int) $group->position,
                'sites'    => $group->sites->map(function ($site) {
                    return [
                        'id'                => $site->id,
                        'name'              => $site->name,
                        'short_name'        => $site->short_name,
                        'slug'              => $site->slug,
                        'base_url'          => $site->base_url,
                        'short_description' => $site->short_description,
                        'menu_order'        => (int) $site->menu_order,
                    ];
                })->values(),
            ];
        });

        return response()->json([
            'groups' => $data,
        ]);
    }

    /**
     * 2) Single site info + menu tree.
     */
    public function site(AcademicSite $site): JsonResponse
    {
        $site->load([
            'navItems' => function ($q) {
                $q->where('is_active', true)->orderBy('position');
            },
        ]);

        // Build tree
        $items = $site->navItems;
        $itemsByParent = $items->groupBy('parent_id');

        $buildTree = function ($parentId) use (&$buildTree, $itemsByParent) {
            return ($itemsByParent[$parentId] ?? collect())->map(function ($item) use (&$buildTree) {
                return [
                    'id'         => $item->id,
                    'parent_id'  => $item->parent_id,
                    'label'      => $item->label,
                    'menu_key'   => $item->menu_key,
                    'type'       => $item->type,
                    'page_id'    => $item->page_id,
                    'route_name' => $item->route_name,
                    'external_url' => $item->external_url,
                    'icon'       => $item->icon,
                    'position'   => (int) $item->position,
                    'children'   => $buildTree($item->id),
                ];
            })->values();
        };

        return response()->json([
            'site' => [
                'id'          => $site->id,
                'name'        => $site->name,
                'short_name'  => $site->short_name,
                'slug'        => $site->slug,
                'base_url'    => $site->base_url,
                'subdomain'   => $site->subdomain,
                'theme'       => [
                    'primary'   => $site->theme_primary_color,
                    'secondary' => $site->theme_secondary_color,
                ],
                'logo_url'    => $site->logo_path ? asset('storage/' . $site->logo_path) : null,
            ],
            'menu' => $buildTree(null),
        ]);
    }

    /**
     * 3) Home data (widgets) for a site.
     */
    public function home(AcademicSite $site): JsonResponse
    {
        $site->load(['homeWidgets' => function ($q) {
            $q->where('is_active', true)->orderBy('position');
        }]);

        $widgets = $site->homeWidgets->map(function ($w) {
            return [
                'id'          => $w->id,
                'widget_type' => $w->widget_type,
                'title'       => $w->title,
                'subtitle'    => $w->subtitle,
                'content'     => $w->content,
                'image_url'   => $w->image_path ? asset('storage/' . $w->image_path) : null,
                'button_text' => $w->button_text,
                'button_url'  => $w->button_url,
                'icon'        => $w->icon,
                'extra'       => $w->extra ?? [],
                'position'    => (int) $w->position,
            ];
        })->values();

        return response()->json([
            'site'    => ['id' => $site->id, 'slug' => $site->slug],
            'widgets' => $widgets,
        ]);
    }

    /**
     * 4) Generic page by slug (banner + sections).
     */
    public function page(AcademicSite $site, string $pageSlug): JsonResponse
    {
        $page = AcademicPage::where('academic_site_id', $site->id)
            ->where('slug', $pageSlug)
            ->active()
            ->with('sections')
            ->firstOrFail();

        return response()->json([
            'site' => [
                'id'         => $site->id,
                'slug'       => $site->slug,
                'short_name' => $site->short_name,
            ],
            'page' => [
                'id'        => $page->id,
                'page_key'  => $page->page_key,
                'slug'      => $page->slug,
                'title'     => $page->title,
                'subtitle'  => $page->subtitle,
                'page_type' => $page->page_type,
                'banner'    => [
                    'title'     => $page->banner_title ?: $page->title,
                    'subtitle'  => $page->banner_subtitle,
                    'image_url' => $page->banner_image_path
                        ? asset('storage/' . $page->banner_image_path)
                        : null,
                ],
                'meta'      => [
                    'meta_title'       => $page->meta_title,
                    'meta_description' => $page->meta_description,
                    'meta_tags'        => $page->meta_tags,
                ],
                'sections' => $page->sections->map(function ($s) {
                    return [
                        'id'          => $s->id,
                        'section_key' => $s->section_key,
                        'title'       => $s->title,
                        'subtitle'    => $s->subtitle,
                        'content'     => $s->content,
                        'extra'       => $s->extra ?? [],
                        'position'    => (int) $s->position,
                    ];
                })->values(),
            ],
        ]);
    }

    /**
     * 5) Departments + staff (combined).
     */
    public function staff(AcademicSite $site, Request $request): JsonResponse
    {
        $site->load([
            'departments.staffGroups.members',
        ]);

        $departmentFilter = $request->query('department');
        $departmentSlug   = $request->query('department_slug');

        $departments = $site->departments->where('is_active', true);

        if ($departmentFilter) {
            $departments = $departments->where('short_code', $departmentFilter);
        }

        if ($departmentSlug) {
            $departments = $departments->where('slug', $departmentSlug);
        }

        $departments = $departments->sortBy('position')->values();

        $data = $departments->map(function ($dept) {
            return [
                'id'         => $dept->id,
                'title'      => $dept->title,
                'short_code' => $dept->short_code,
                'slug'       => $dept->slug,
                'position'   => (int) $dept->position,
                'staff_groups' => $dept->staffGroups->sortBy('position')->values()->map(function ($group) {
                    return [
                        'id'       => $group->id,
                        'title'    => $group->title,
                        'position' => (int) $group->position,
                        'members'  => $group->members->sortBy('position')->values()->map(function ($m) {
                            return [
                                'id'          => $m->id,
                                'name'        => $m->name,
                                'designation' => $m->designation,
                                'email'       => $m->email,
                                'phone'       => $m->phone,
                                'image_url'   => $m->image_path ? asset('storage/' . $m->image_path) : null,
                                'position'    => (int) $m->position,
                                'links'       => $m->links ?? [],
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json([
            'site'        => ['id' => $site->id, 'slug' => $site->slug],
            'departments' => $data,
        ]);
    }
}
