<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class OfficeAliasApiController extends Controller
{
    /**
     * GET /api/v1/offices/{slug}
     * Alias to existing CMS bundle office endpoint.
     */
    public function show(string $slug): JsonResponse
    {
        $ctrl = app(\App\Http\Controllers\Frontend\Api\CmsBundleController::class);
        return $ctrl->office($slug);
    }

    /**
     * GET /api/v1/offices/page/{pageSlug}
     * Finds office CMS page by slug and returns it with its office context.
     */
    public function page(string $pageSlug): JsonResponse
    {
        $page = \App\Models\AcademicPage::query()
            ->where('owner_type', 'office')
            ->where('slug', $pageSlug)
            ->where('status', 'published')
            ->firstOrFail();

        $office = \App\Models\AdminOffice::query()
            ->where('id', $page->owner_id)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'office' => $office,
                'page' => $page,
            ],
        ]);
    }
}
