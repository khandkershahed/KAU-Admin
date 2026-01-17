<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicPage;
use App\Models\AcademicPageBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AcademicPageBlockController extends Controller
{
    public function __construct()
    {
        // same permission used for editing pages
        $this->middleware('permission:edit academic pages');
    }

    public function store(AcademicPage $page, Request $request)
    {
        $data = $request->validate([
            'block_type' => ['required', 'string', 'max:50'],
            'status'     => ['nullable', Rule::in(['published','draft','archived'])],
            'data'       => ['nullable', 'array'],
        ]);

        $position = (int) AcademicPageBlock::where('academic_page_id', $page->id)->max('position');
        $position = $position + 1;

        $block = AcademicPageBlock::create([
            'academic_page_id' => $page->id,
            'block_type'       => $data['block_type'],
            'data'             => $data['data'] ?? [],
            'position'         => $position,
            'status'           => $data['status'] ?? 'published',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Block added.',
            'block'   => $block,
        ]);
    }

    public function update(AcademicPage $page, AcademicPageBlock $block, Request $request)
    {
        if ((int) $block->academic_page_id !== (int) $page->id) {
            return response()->json(['success' => false, 'message' => 'Invalid block.'], 422);
        }

        $data = $request->validate([
            'status' => ['nullable', Rule::in(['published','draft','archived'])],
            'data'   => ['nullable', 'array'],
        ]);

        $block->update([
            'status' => $data['status'] ?? $block->status,
            'data'   => $data['data'] ?? $block->data,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Block updated.',
        ]);
    }

    public function destroy(AcademicPage $page, AcademicPageBlock $block)
    {
        if ((int) $block->academic_page_id !== (int) $page->id) {
            return response()->json(['success' => false, 'message' => 'Invalid block.'], 422);
        }

        $block->delete();

        return response()->json([
            'success' => true,
            'message' => 'Block deleted.',
        ]);
    }

    public function sort(AcademicPage $page, Request $request)
    {
        $data = $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        DB::beginTransaction();
        try {
            foreach ($data['order'] as $idx => $id) {
                AcademicPageBlock::where('academic_page_id', $page->id)
                    ->where('id', $id)
                    ->update(['position' => $idx + 1]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Block order updated.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update block order: ' . $e->getMessage(),
            ], 500);
        }
    }
}
