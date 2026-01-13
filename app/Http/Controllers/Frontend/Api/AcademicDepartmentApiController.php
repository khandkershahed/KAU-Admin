<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Models\AcademicDepartment;
use App\Models\AcademicNavItem;
use App\Models\AcademicPage;

class AcademicDepartmentApiController extends Controller
{
    /**
     * Standalone department page data by department slug:
     * /api/v1/academics/departments/{departmentSlug}
     *
     * Returns:
     * - department
     * - site (parent faculty/institute/school)
     * - navigation (same site nav, so you can render header/menu)
     * - pages (same as site pages, useful if you want tabs or internal links)
     * - staff (only staff for THIS department, shaped exactly like Next expects)
     */
    public function show(string $departmentSlug): JsonResponse
    {
        $cacheKey = 'api_academic_department_' . $departmentSlug;

        $payload = Cache::remember($cacheKey, 600, function () use ($departmentSlug) {

            $department = AcademicDepartment::query()
                ->with([
                    'site:id,slug,name,short_name,short_description,theme_primary_color,theme_secondary_color,logo_path,config',
                    'staffSections' => function ($q) {
                        $q->where('status', 'published')->orderBy('position');
                    },
                    'staffSections.members' => function ($q) {
                        $q->where('status', 'published')->orderBy('position');
                    },
                ])
                ->where('slug', $departmentSlug)
                ->where('status', 'published')
                ->firstOrFail();

            $site = $department->site;

            // Navigation: site-level nav tree
            $navItems = AcademicNavItem::query()
                ->where('academic_site_id', $site->id)
                ->where('status', 'published')
                ->orderBy('position')
                ->get();

            $navigation = $this->buildNavTree($navItems);

            // Pages: site pages (useful for tabs or links)
            $pages = AcademicPage::query()
                ->where('academic_site_id', $site->id)
                ->where('status', 'published')
                ->orderBy('position')
                ->get()
                ->map(function ($p) {
                    return [
                        'slug' => $p->slug,
                        'title' => $p->title,
                        'is_home' => (bool) $p->is_home,
                        'is_department_boxes' => (bool) $p->is_department_boxes,
                        'is_faculty_members' => (bool) $p->is_faculty_members,
                        'banner_image' => $p->banner_image ? asset('storage/' . $p->banner_image) : null,
                        'banner_title' => $p->banner_title,
                        'banner_subtitle' => $p->banner_subtitle,
                        'banner_button' => $p->banner_button,
                        'banner_button_url' => $p->banner_button_url,
                        'content' => $p->content,
                        'meta_title' => $p->meta_title,
                        'meta_tags' => $p->meta_tags,
                        'meta_description' => $p->meta_description,
                        'og_image' => $p->og_image ? asset('storage/' . $p->og_image) : null,
                        'position' => $p->position,
                    ];
                });

            // Staff shape EXACTLY how your Next staff route currently uses it:
            // staffData = data.staff.find(s => s.slug === childSlug)
            $staff = [
                [
                    'department_id' => $department->id,
                    'department_title' => $department->title,
                    'slug' => $department->slug,
                    'groups' => $department->staffSections->map(function ($section) {
                        return [
                            'id' => $section->id,
                            'title' => $section->title,
                            'position' => $section->position,
                            'members' => $section->members->map(function ($m) {
                                return [
                                    'id' => $m->id,
                                    'name' => $m->name,
                                    'designation' => $m->designation,
                                    'email' => $m->email,
                                    'phone' => $m->phone ?? null,
                                    'room' => $m->room ?? null,
                                    'bio' => $m->bio ?? null,
                                    'image_url' => $m->image_path ? asset('storage/' . $m->image_path) : null,
                                    'position' => $m->position,
                                    'links' => $m->links,
                                ];
                            })->values(),
                        ];
                    })->values(),
                ]
            ];

            return [
                'site' => [
                    'slug' => $site->slug,
                    'name' => $site->name,
                    'short_name' => $site->short_name,
                    'short_description' => $site->short_description,
                    'theme_primary_color' => $site->theme_primary_color,
                    'theme_secondary_color' => $site->theme_secondary_color,
                    'logo_url' => $site->logo_path ? asset('storage/' . $site->logo_path) : null,
                    'config' => $site->config,
                ],
                'department' => [
                    'id' => $department->id,
                    'title' => $department->title,
                    'short_code' => $department->short_code,
                    'slug' => $department->slug,
                    'description' => $department->description,
                    'position' => $department->position,
                ],
                'navigation' => $navigation,
                'pages' => $pages,
                'staff' => $staff,
            ];
        });

        return response()->json($payload);
    }

    /**
     * Convert flat nav items into a nested tree (parent_id relationship).
     * Keeps your existing structure and supports children[] recursively.
     */
    private function buildNavTree($items): array
    {
        $byId = [];
        foreach ($items as $i) {
            $byId[$i->id] = [
                'label' => $i->label,
                'slug' => $i->slug,
                'type' => $i->type,
                'external_url' => $i->external_url,
                'page_slug' => $i->page_slug,
                'position' => $i->position,
                'children' => [],
            ];
        }

        $tree = [];
        foreach ($items as $i) {
            if ($i->parent_id && isset($byId[$i->parent_id])) {
                $byId[$i->parent_id]['children'][] = &$byId[$i->id];
            } else {
                $tree[] = &$byId[$i->id];
            }
        }

        // sort children by position recursively
        $sortFn = function (&$nodes) use (&$sortFn) {
            usort($nodes, fn($a, $b) => ($a['position'] ?? 0) <=> ($b['position'] ?? 0));
            foreach ($nodes as &$n) {
                if (!empty($n['children'])) $sortFn($n['children']);
            }
        };
        $sortFn($tree);

        return $tree;
    }
}
