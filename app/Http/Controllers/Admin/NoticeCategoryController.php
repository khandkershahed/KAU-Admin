<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NoticeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class NoticeCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view notice category')->only(['index']);
        $this->middleware('permission:create notice category')->only(['store']);
        $this->middleware('permission:edit notice category')->only(['update']);
        $this->middleware('permission:delete notice category')->only(['destroy']);
    }

    /**
     * We manage categories & notices from the same page.
     * So just redirect to Notice index.
     */
    public function index()
    {
        return redirect()->route('admin.notice.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255|unique:notice_categories,name',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $message) {
                Session::flash('error', $message);
            }
            return redirect()->back()->withInput();
        }

        NoticeCategory::create([
            'name'   => $request->name,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->back()->with('success', 'Notice category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = NoticeCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255|unique:notice_categories,name,' . $category->id,
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $message) {
                Session::flash('error', $message);
            }
            return redirect()->back()->withInput();
        }

        $category->update([
            'name'   => $request->name,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->back()->with('success', 'Notice category updated successfully.');
    }

    public function destroy($id)
    {
        $category = NoticeCategory::findOrFail($id);

        // If you want, you can check if it has notices and prevent delete.
        $category->delete();

        return redirect()->back()->with('success', 'Notice category deleted successfully.');
    }
}
