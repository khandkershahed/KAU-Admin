<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Models\Gallery;
use App\Models\AdminOffice;
use App\Models\AcademicPage;
use App\Models\AcademicSite;
use App\Models\AcademicNavItem;
use Illuminate\Http\JsonResponse;
use App\Models\AcademicDepartment;
use App\Http\Controllers\Controller;

class CmsBundleController extends Controller
{
    /**
     * MAIN SITE
     * GET /api/v1/cms/main
     */
    public function main(): JsonResponse
    {
        return response()->json($this->bundleForOwner('main', null));
    }

    /**
     * ACADEMIC SITE
     * GET /api/v1/cms/site/{siteSlug}
     */
    public function site(string $siteSlug): JsonResponse
    {
        $site = AcademicSite::where('slug', $siteSlug)->where('status', 'published')->firstOrFail();
        return response()->json($this->bundleForOwner('site', $site->id, $site));
    }

    /**
     * DEPARTMENT SITE (standalone)
     * GET /api/v1/cms/department/{departmentSlug}
     */
    public function department(string $departmentSlug): JsonResponse
    {
        $department = AcademicDepartment::where('slug', $departmentSlug)->where('status', 'published')->firstOrFail();
        $site = $department->academic_site_id
            ? AcademicSite::where('id', $department->academic_site_id)->first()
            : null;

        return response()->json($this->bundleForOwner('department', $department->id, $site, $department));
    }

    private function bundleForOwner(string $ownerType, ?int $ownerId, $site = null, $department = null): array
    {
        $navItems = AcademicNavItem::query()
            ->where('owner_type', $ownerType)
            ->where(function ($q) use ($ownerId) {
                if ($ownerId === null) $q->whereNull('owner_id');
                else $q->where('owner_id', $ownerId);
            })
            ->where('status', 'published')
            ->orderBy('position')
            ->get();

        $pages = AcademicPage::query()
            ->with(['blocks' => function ($q) {
                $q->where('status', 'published')->orderBy('position');
            }])
            ->where('owner_type', $ownerType)
            ->where(function ($q) use ($ownerId) {
                if ($ownerId === null) $q->whereNull('owner_id');
                else $q->where('owner_id', $ownerId);
            })
            ->where('status', 'published')
            ->orderBy('position')
            ->get();

        $home = $pages->firstWhere('is_home', true) ?: $pages->first();


        return [
            'owner' => [
                'type' => $ownerType,
                'id' => $ownerId,
            ],
            'site' => $site ? [
                'id' => $site->id,
                'slug' => $site->slug,
                'name' => $site->name,
                'short_name' => $site->short_name,
                'short_description' => $site->short_description,
                'theme_primary_color' => $site->theme_primary_color,
                'theme_secondary_color' => $site->theme_secondary_color,
                'logo_url' => $site->logo_path ? asset('storage/' . $site->logo_path) : null,
            ] : null,
            'department' => $department ? [
                'id' => $department->id,
                'title' => $department->title,
                'slug' => $department->slug,
                'short_code' => $department->short_code,
                'description' => $department->description,
            ] : null,
            'navigation' => $this->tree($navItems),
            'pages' => $pages->map(fn($p) => $this->pageShape($p))->values(),
            'home' => $home ? $this->pageShape($home) : null,
            'galleries' => Gallery::query()
                ->with('items')
                ->where('owner_type', $ownerType)
                ->where(function ($q) use ($ownerId) {
                    if ($ownerId === null) $q->whereNull('owner_id');
                    else $q->where('owner_id', $ownerId);
                })
                ->where('is_active', 1)
                ->orderBy('position')
                ->get()
                ->map(fn($g) => [
                    'id' => $g->id,
                    'title' => $g->title,
                    'slug' => $g->slug,
                    'type' => $g->type,
                    'items' => $g->items->map(fn($it) => [
                        'id' => $it->id,
                        'item_type' => $it->item_type,
                        'title' => $it->title,
                        'media_url' => $it->media_path ? asset('storage/' . $it->media_path) : null,
                        'video_url' => $it->video_url,
                        'position' => (int) $it->position,
                    ])->values(),
                ])->values(),

        ];
    }

    private function pageShape(AcademicPage $p): array
    {
        return [
            'id' => $p->id,
            'slug' => $p->slug,
            'title' => $p->title,
            'template_key' => $p->template_key ?: 'default',
            'is_home' => (bool) $p->is_home,
            'banner_title' => $p->banner_title,
            'banner_subtitle' => $p->banner_subtitle,
            'banner_button' => $p->banner_button,
            'banner_button_url' => $p->banner_button_url,
            'banner_image' => $p->banner_image,
            'content' => $p->content, // keep for legacy
            'settings' => $p->settings,
            'blocks' => $p->blocks->map(fn($b) => [
                'id' => $b->id,
                'block_type' => $b->block_type,
                'data' => $b->data,
                'position' => $b->position,
            ])->values(),
            'meta_title' => $p->meta_title,
            'meta_description' => $p->meta_description,
            'meta_tags' => $p->meta_tags,
            'og_image' => $p->og_image,
        ];
    }

    private function tree($items): array
    {
        $byId = [];
        foreach ($items as $i) {
            $byId[$i->id] = [
                'id' => $i->id,
                'label' => $i->label,
                'slug' => $i->slug,
                'type' => $i->type,
                'external_url' => $i->external_url,
                'page_slug' => $i->menu_key, // optional
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

        $sortFn = function (&$nodes) use (&$sortFn) {
            usort($nodes, fn($a, $b) => ($a['position'] ?? 0) <=> ($b['position'] ?? 0));
            foreach ($nodes as &$n) {
                if (!empty($n['children'])) $sortFn($n['children']);
            }
        };
        $sortFn($tree);

        return $tree;
    }

    public function office(string $slug)
    {
        $office = AdminOffice::where('slug', $slug)->where('is_active', 1)->firstOrFail();

        $pages = AcademicPage::where('owner_type', 'office')
            ->where('owner_id', $office->id)
            ->where('status', 'published')
            ->orderBy('position')
            ->get();

        $navItems = AcademicNavItem::where('owner_type', 'office')
            ->where('owner_id', $office->id)
            ->where('status', 'published')
            ->orderBy('position')
            ->get();

        return response()->json([
            'owner_type' => 'office',
            'office' => $office,
            'pages' => $pages,
            'navigation' => $navItems,
        ]);
    }
}
