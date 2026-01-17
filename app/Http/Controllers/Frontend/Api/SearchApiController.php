<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    /**
     * GET /api/v1/search?q=term
     */
    public function index(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $limit = (int) $request->query('limit', 30);
        if ($limit < 1) $limit = 30;
        if ($limit > 100) $limit = 100;

        $results = \App\Models\SearchIndex::query()
            ->where(function ($qq) use ($q) {
                $qq->where('title', 'like', '%' . $q . '%')
                   ->orWhere('content', 'like', '%' . $q . '%')
                   ->orWhere('keywords', 'like', '%' . $q . '%');
            })
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'title' => $r->title,
                    'snippet' => $this->makeSnippet((string) $r->content),
                    'url' => $r->url,
                    'type' => $r->type,
                    'owner_type' => $r->owner_type,
                    'owner_id' => $r->owner_id,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }

    private function makeSnippet(string $text): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        if (mb_strlen($text) <= 180) return $text;
        return mb_substr($text, 0, 180) . '...';
    }
}
