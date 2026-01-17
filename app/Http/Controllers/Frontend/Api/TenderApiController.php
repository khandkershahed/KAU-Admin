<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenderApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 12);
        if ($perPage < 1) $perPage = 12;
        if ($perPage > 50) $perPage = 50;

        $q = \App\Models\Tender::query()->where('status', 'published');
        $q->orderByDesc('published_at')->orderByDesc('id');
        $p = $q->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $p->items(),
            'meta' => [
                'current_page' => $p->currentPage(),
                'last_page' => $p->lastPage(),
                'per_page' => $p->perPage(),
                'total' => $p->total(),
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $tender = \App\Models\Tender::query()
            ->with(['attachments'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $data = $tender->toArray();
        $data['attachments'] = collect($tender->attachments ?? [])->map(function ($a) {
            $arr = is_array($a) ? $a : $a->toArray();
            if (!empty($arr['file_path'])) {
                $arr['file_url'] = asset('storage/' . ltrim($arr['file_path'], '/'));
            }
            return $arr;
        })->values();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
