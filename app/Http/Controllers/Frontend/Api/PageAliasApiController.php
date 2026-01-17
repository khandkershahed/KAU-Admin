<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class PageAliasApiController extends Controller
{
    public function show(string $slug): JsonResponse
    {
        $page = \App\Models\AcademicPage::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => ['page' => $page],
        ]);
    }
}
    public function show(string $slug): JsonResponse
    {
        $page = \App\Models\AcademicPage::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $page,
        ]);
    }
}
