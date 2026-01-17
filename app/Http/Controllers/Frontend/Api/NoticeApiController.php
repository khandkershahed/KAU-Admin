<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoticeApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = max(1, min(50, (int) $request->query('per_page', 12)));
        $category = trim((string) $request->query('category', ''));

        $q = \App\Models\Notice::query()->with('category')->where('status','published');
        if ($category !== '') {
            $q->whereHas('category', fn($qq) => $qq->where('slug', $category));
        }
        $q->orderByDesc('published_at')->orderByDesc('id');
        $p = $q->paginate($perPage);

        return response()->json(['success'=>true,'data'=>$p->items(),'meta'=>['current_page'=>$p->currentPage(),'last_page'=>$p->lastPage(),'per_page'=>$p->perPage(),'total'=>$p->total()]]);
    }

    public function show(string $slug): JsonResponse
    {
        $notice = \App\Models\Notice::query()->with('category')->where('slug',$slug)->where('status','published')->firstOrFail();
        return response()->json(['success'=>true,'data'=>$notice]);
    }
}
