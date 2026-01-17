<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicPage;
use Illuminate\Http\JsonResponse;

class PageAliasApiController extends Controller
{
    /**
     * GET /api/v1/page/{slug}
     * Main dynamic page resolver (used by Next.js /page/[slug]).
     */
    public function show(string $slug): JsonResponse
    {
        $page = AcademicPage::query()
            ->with(['blocks' => function ($q) {
                $q->where('status', 'published')->orderBy('position');
            }])
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'page' => [
                    'id' => $page->id,
                    'slug' => $page->slug,
                    'title' => $page->title,
                    'template_key' => $page->template_key ?: 'default',
                    'is_home' => (bool) $page->is_home,
                    'banner_title' => $page->banner_title,
                    'banner_subtitle' => $page->banner_subtitle,
                    'banner_button' => $page->banner_button,
                    'banner_button_url' => $page->banner_button_url,
                    'banner_image' => $page->banner_image,
                    'banner_image_url' => $page->banner_image ? asset('storage/' . $page->banner_image) : null,
                    'content' => $page->content,
                    'settings' => $page->settings,
                    'blocks' => $page->blocks->map(fn($b) => [
                        'id' => $b->id,
                        'block_type' => $b->block_type,
                        'data' => $b->data,
                        'position' => (int) $b->position,
                    ])->values(),
                    'meta_title' => $page->meta_title,
                    'meta_description' => $page->meta_description,
                    'meta_tags' => $page->meta_tags,
                    'og_image' => $page->og_image,
                    'og_image_url' => $page->og_image ? asset('storage/' . $page->og_image) : null,
                ],
            ],
        ]);
    }
}
