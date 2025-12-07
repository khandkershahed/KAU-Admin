<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view faq')->only(['index']);
        $this->middleware('permission:create faq')->only(['create', 'store']);
        $this->middleware('permission:edit faq')->only(['edit', 'update']);
        $this->middleware('permission:delete faq')->only(['destroy']);
    }

    /**
     * INDEX — full page + AJAX partial reload
     */
    public function index(Request $request)
    {
        $query = Faq::orderBy('order')
            ->orderBy('id', 'desc');

        if ($request->filled('q')) {
            $s = $request->get('q');
            $query->where(function ($q2) use ($s) {
                $q2->where('question', 'like', "%{$s}%")
                    ->orWhere('category', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $faqs = $query->paginate(20);

        if ($request->ajax()) {
            return view('admin.pages.faq.partials.table', [
                'faqs' => $faqs,
            ])->render();
        }

        return view('admin.pages.faq.index', [
            'faqs' => $faqs,
            'search' => $request->q,
            'filterStatus' => $request->status,
            'filterCategory' => $request->category,
        ]);
    }

    /**
     * CATEGORY SUGGEST FOR SELECT2 (AJAX)
     */
    public function categorySuggest(Request $request)
    {
        $term = strtolower($request->get('term', ''));

        $results = Faq::whereRaw("LOWER(category) LIKE ?", ["%{$term}%"])
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->category,
                    'text' => $item->category,
                ];
            });

        return response()->json($results);
    }

    /**
     * CREATE MODAL HTML (AJAX)
     */
    public function create()
    {
        return view('admin.pages.faq.partials.modal_create')->render();
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'question' => 'required|string|max:500',
                'answer' => 'required|string',
                'category' => 'nullable|string|max:200',
                'status' => 'required|in:active,inactive',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $msg) {
                    Session::flash('error', $msg);
                }
                return response()->json(['success' => false], 422);
            }

            // Normalize category (soft uniqueness)
            $normalized = strtolower(trim($request->category));
            $existing = Faq::whereRaw('LOWER(category) = ?', [$normalized])->first();

            $finalCategory = $existing ? $existing->category : $request->category;

            // Auto order = max + 1
            $maxOrder = Faq::max('order') ?? 0;

            Faq::create([
                'question' => $request->question,
                'answer' => $request->answer,
                'category' => $finalCategory,
                'tag' => null,
                'order' => $maxOrder + 1,
                'status' => $request->status,
                'views' => 0,
                'related_faqs' => [],
                'is_featured' => false,
                'additional_info' => null,
                'created_by' => Auth::guard('admin')->id(),
                'updated_by' => null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'FAQ created successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * EDIT MODAL HTML (AJAX)
     */
    public function edit(Faq $faq)
    {
        return view('admin.pages.faq.partials.modal_edit', [
            'faq' => $faq,
        ])->render();
    }

    /**
     * UPDATE
     */
    public function update(Request $request, Faq $faq)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'question' => 'required|string|max:500',
                'answer' => 'required|string',
                'category' => 'nullable|string|max:200',
                'status' => 'required|in:active,inactive',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $msg) {
                    Session::flash('error', $msg);
                }
                return response()->json(['success' => false], 422);
            }

            // Normalize category
            $normalized = strtolower(trim($request->category));
            $existing = Faq::whereRaw('LOWER(category) = ?', [$normalized])->first();

            $finalCategory = $existing ? $existing->category : $request->category;

            $faq->update([
                'question'   => $request->question,
                'answer'     => $request->answer,
                'category'   => $finalCategory,
                'status'     => $request->status,
                'updated_by' => Auth::guard('admin')->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'FAQ updated successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE (SweetAlert + AJAX)
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return response()->json([
            'success' => true,
            'message' => 'FAQ deleted successfully.',
        ]);
    }

    /**
     * TOGGLE FEATURED
     */
    public function toggleFeatured(Faq $faq, Request $request)
    {
        $request->validate(['is_featured' => 'required|boolean']);

        $faq->is_featured = $request->boolean('is_featured');
        $faq->save();

        return response()->json([
            'success' => true,
            'message' => 'Featured status updated.',
        ]);
    }

    /**
     * TOGGLE STATUS
     */
    public function toggleStatus(Faq $faq, Request $request)
    {
        $request->validate(['status' => 'required|in:active,inactive']);

        $faq->status = $request->status;
        $faq->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated.',
        ]);
    }

    /**
     * SORT ORDER (drag & drop)
     */
    public function sortOrder(Request $request)
    {
        $items = $request->order;

        foreach ($items as $position => $id) {
            Faq::where('id', $id)->update(['order' => $position + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully.',
        ]);
    }
}
