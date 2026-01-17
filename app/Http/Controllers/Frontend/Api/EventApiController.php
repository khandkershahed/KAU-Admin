<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 12);
        if ($perPage < 1) $perPage = 12;
        if ($perPage > 50) $perPage = 50;

        $q = \App\Models\Event::query()
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->orderByDesc('id');

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
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 12);
        if ($perPage < 1) $perPage = 12;
        if ($perPage > 50) $perPage = 50;

        $q = \App\Models\Event::query()
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->orderByDesc('id');

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
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 12);
        if ($perPage < 1) $perPage = 12;
        if ($perPage > 50) $perPage = 50;

        $q = \App\Models\Event::query()
            ->where('status', 'published');

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
        $event = \App\Models\Event::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $event,
        ]);
    }

                'per_page' => $p->perPage(),
                'total' => $p->total(),
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $event = \App\Models\Event::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $event,
        ]);
    }
}
