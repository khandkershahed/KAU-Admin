<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicNavItem;
use Illuminate\Http\Request;

class MainMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage academic sites');
    }

    public function index()
    {
        $items = AcademicNavItem::where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('menu_location', 'navbar')
            ->orderBy('parent_id')
            ->orderBy('position')
            ->get();

        return view('admin.pages.cms.main.menu.index', compact('items'));
    }

    public function create(Request $request)
    {
        $parents = AcademicNavItem::where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('menu_location', 'navbar')
            ->orderBy('label')
            ->get();

        $parentId = $request->query('parent_id');

        return view('admin.pages.cms.main.menu.create', compact('parents', 'parentId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label'        => 'required|string|max:255',
            'slug'         => 'required|string|max:255',
            'menu_key'     => 'nullable|string|max:255',
            'type'         => 'required|in:page,route,external,group',
            'external_url' => 'nullable|string|max:1000',
            'parent_id'    => 'nullable|exists:academic_nav_items,id',
            'icon'         => 'nullable|string|max:255',
            'status'       => 'required|in:published,draft',
        ]);

        AcademicNavItem::create([
            'owner_type'    => 'main',
            'owner_id'      => null,
            'menu_location' => 'navbar',
            'parent_id'     => $data['parent_id'],
            'label'         => $data['label'],
            'slug'          => $data['slug'],
            'menu_key'      => $data['menu_key'],
            'type'          => $data['type'],
            'external_url'  => $data['type'] === 'external' ? $data['external_url'] : null,
            'icon'          => $data['icon'],
            'status'        => $data['status'],
            'position'      => AcademicNavItem::where('owner_type', 'main')
                                  ->whereNull('owner_id')
                                  ->where('parent_id', $data['parent_id'])
                                  ->max('position') + 1,
        ]);

        return redirect()->route('cms.main.menu.index')
            ->with('success', 'Menu item created successfully.');
    }

    public function edit(AcademicNavItem $item)
    {
        $parents = AcademicNavItem::where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('id', '!=', $item->id)
            ->orderBy('label')
            ->get();

        return view('admin.pages.cms.main.menu.edit', compact('item', 'parents'));
    }

    public function update(Request $request, AcademicNavItem $item)
    {
        $data = $request->validate([
            'label'        => 'required|string|max:255',
            'slug'         => 'required|string|max:255',
            'menu_key'     => 'nullable|string|max:255',
            'type'         => 'required|in:page,route,external,group',
            'external_url' => 'nullable|string|max:1000',
            'icon'         => 'nullable|string|max:255',
            'status'       => 'required|in:published,draft',
        ]);

        $item->update([
            'label'        => $data['label'],
            'slug'         => $data['slug'],
            'menu_key'     => $data['menu_key'],
            'type'         => $data['type'],
            'external_url' => $data['type'] === 'external' ? $data['external_url'] : null,
            'icon'         => $data['icon'],
            'status'       => $data['status'],
        ]);

        return redirect()->route('cms.main.menu.index')
            ->with('success', 'Menu item updated successfully.');
    }

    public function destroy(AcademicNavItem $item)
    {
        $item->delete();

        return response()->json(['success' => true]);
    }

    public function sort(Request $request)
    {
        foreach ($request->order as $index => $id) {
            AcademicNavItem::where('id', $id)->update(['position' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
