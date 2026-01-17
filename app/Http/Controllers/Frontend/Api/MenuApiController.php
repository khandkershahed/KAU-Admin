<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicNavItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuApiController extends Controller
{
    /**
     * GET /api/v1/menus?location=navbar|topbar
     * Returns MenuNode[] for Next.js DesktopMenu.tsx + MobileDrawer.tsx
     */
    public function index(Request $request): JsonResponse
    {
        $location = $request->query('location', 'navbar');
        if (!in_array($location, ['navbar', 'topbar'], true)) {
            $location = 'navbar';
        }

        $items = AcademicNavItem::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('menu_location', $location)
            ->where('status', 'published')
            ->orderBy('parent_id')
            ->orderBy('position')
            ->get();

        $byParent = $items->groupBy('parent_id');

        $build = function ($parentId) use (&$build, $byParent) {
            $nodes = [];

            foreach (($byParent[$parentId] ?? collect()) as $item) {
                $children = $build($item->id);

                $nodeType = 'page';
                if ($item->type === 'external') $nodeType = 'external';
                if ($item->type === 'group') $nodeType = 'group';

                // Layout:
                // - if DB layout set => use it
                // - otherwise if it has children => dropdown
                // - if leaf => null
                $layout = null;
                if (!empty($item->layout)) {
                    $layout = $item->layout;
                } elseif (count($children) > 0) {
                    $layout = 'dropdown';
                }

                $link = $this->makeLink($item);

                $nodes[] = [
                    'id' => $item->id,
                    'label' => $item->label,
                    'slug' => $item->slug,
                    'type' => (count($children) > 0 ? 'menu' : $nodeType),
                    'layout' => $layout,
                    'position' => (int) $item->position,
                    'visibility' => [
                        'desktop' => true,
                        'mobile' => true,
                    ],
                    'link' => $link,
                    'children' => $children,
                ];
            }

            usort($nodes, fn($a, $b) => ($a['position'] ?? 0) <=> ($b['position'] ?? 0));
            return $nodes;
        };

        return response()->json([
            'success' => true,
            'location' => $location,
            'data' => $build(null),
        ]);
    }

    private function makeLink(AcademicNavItem $item): ?array
    {
        // Groups are non-clickable
        if ($item->type === 'group') return null;

        if ($item->type === 'external') {
            return [
                'type' => 'external',
                'url' => (string) $item->external_url,
                'target' => '_blank',
            ];
        }

        // Internal routes:
        // - page => /page/{slug}
        // - route => /{slug} OR special: gallery linking
        if ($item->type === 'page') {
            return [
                'type' => 'internal',
                'url' => '/page/' . ltrim((string) $item->slug, '/'),
            ];
        }

        // route
        $slug = ltrim((string) $item->slug, '/');

        // If admin sets: slug = "gallery" and menu_key = "{gallerySlug}"
        if ($slug === 'gallery' && !empty($item->menu_key)) {
            return [
                'type' => 'internal',
                'url' => '/gallery/' . ltrim((string) $item->menu_key, '/'),
            ];
        }

        return [
            'type' => 'internal',
            'url' => '/' . $slug,
        ];
    }
}
