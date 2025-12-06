<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminGroupController extends Controller
{
    /**
     * Display a listing of groups.
     */
    public function index()
    {
        $groups = AdminGroup::orderBy('position', 'asc')->get();

        return view('admin.pages.administration.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new group.
     */

    /**
     * Store a newly created group in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $position = AdminGroup::max('position') + 1;

        AdminGroup::create([
            'name' => $request->name,
            'position' => $position,
            'status' => $request->status ?? 1,
        ]);

        return redirect()
            ->route('admin.admin-groups.index')
            ->with('success', 'Group created successfully.');
    }


    public function update(Request $request, AdminGroup $adminGroup)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $adminGroup->update([
            'name' => $request->name,
            'status' => $request->status ?? 1,
        ]);

        return redirect()
            ->route('admin.admin-groups.index')
            ->with('success', 'Group updated successfully.');
    }

    /**
     * Remove the specified group.
     */
    public function destroy(AdminGroup $adminGroup)
    {
        $adminGroup->delete();

        return redirect()
            ->back()
            ->with('success', 'Group deleted successfully.');
    }

    /**
     * Sort groups via AJAX.
     */
    public function sort(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
        ]);

        foreach ($request->order as $position => $id) {
            AdminGroup::where('id', $id)->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }
}
