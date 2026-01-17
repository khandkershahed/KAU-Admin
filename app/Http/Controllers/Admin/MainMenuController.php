<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicNavItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MainMenuController extends Controller
{
    public function index(Request $request)
    {
        $location = $request->get('menu_location', 'navbar');
        if (!in_array($location, ['navbar', 'topbar'], true)) {
            $location = 'navbar';
        }

        $items = AcademicNavItem::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('menu_location', $location)
            ->orderBy('parent_id')
            ->orderBy('position')
            ->get();

        $byParent = $items->groupBy('parent_id');

        return view('admin.pages.cms.main.menu.index', compact('items', 'byParent', 'location'));
    }

    public function create(Request $request)
    {
        $location = $request->get('menu_location', 'navbar');
        if (!in_array($location, ['navbar', 'topbar'], true)) {
            $location = 'navbar';
        }

        $parents = AcademicNavItem::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('menu_location', $location)
            ->whereNull('parent_id')
            ->orderBy('position')
            ->get();

        return view('admin.pages.cms.main.menu.create', compact('parents', 'location'));
    }

    public function edit(int $item, Request $request)
    {
        $location = $request->get('menu_location', 'navbar');
        if (!in_array($location, ['navbar', 'topbar'], true)) {
            $location = 'navbar';
        }

        $item = AcademicNavItem::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('menu_location', $location)
            ->where('id', $item)
            ->firstOrFail();

        $parents = AcademicNavItem::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('menu_location', $location)
            ->whereNull('parent_id')
            ->where('id', '!=', $item->id)
            ->orderBy('position')
            ->get();

        return view('admin.pages.cms.main.menu.edit', compact('item', 'parents', 'location'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_location' => 'required|in:navbar,topbar',
            'label' => 'required|string|max:255',
            'slug'  => [
                'required', 'string', 'max:255',
                Rule::unique('academic_nav_items', 'slug')->where(function ($q) use ($request) {
                    return $q->where('owner_type', 'main')
                        ->whereNull('owner_id')
                        ->where('menu_location', $request->input('menu_location', 'navbar'));
                }),
            ],
            'menu_key' => 'nullable|string|max:255',
            'type' => 'required|in:route,page,external,group',
            'layout' => 'nullable|in:dropdown,mega',
            'parent_id' => 'nullable|integer',
            'external_url' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|in:published,draft,archived',
        ]);

        $parentId = $data['parent_id'] ?? null;
        if ($parentId) {
            $parent = AcademicNavItem::query()
                ->where('owner_type', 'main')
                ->whereNull('owner_id')
                ->where('menu_location', $data['menu_location'])
                ->where('id', $parentId)
                ->firstOrFail();

            $parentId = $parent->id;
        }

        AcademicNavItem::create([
            'academic_site_id' => null,
            'owner_type' => 'main',
            'owner_id' => null,
            'parent_id' => $parentId,
            'label' => $data['label'],
            'slug' => $data['slug'],
            'menu_key' => $data['menu_key'] ?? null,
            'type' => $data['type'],
            'menu_location' => $data['menu_location'],
            'layout' => $data['layout'] ?? null,
            'external_url' => $data['type'] === 'external' ? ($data['external_url'] ?? null) : null,
            'icon' => $data['icon'] ?? null,
            'status' => $data['status'],
            'position' => (int) AcademicNavItem::query()
                ->where('owner_type', 'main')
                ->whereNull('owner_id')
                ->where('menu_location', $data['menu_location'])
                ->where('parent_id', $parentId)
                ->max('position') + 1,
        ]);

        return redirect()
            ->route('admin.cms.main.menu.index', ['menu_location' => $data['menu_location']])
            ->with('success', 'Menu item created successfully.');
    }

    public function update(int $item, Request $request)
    {
        $item = AcademicNavItem::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('id', $item)
            ->firstOrFail();

        $data = $request->validate([
            'menu_location' => 'required|in:navbar,topbar',
            'label' => 'required|string|max:255',
            'slug'  => [
                'required', 'string', 'max:255',
                Rule::unique('academic_nav_items', 'slug')
                    ->ignore($item->id)
                    ->where(function ($q) use ($request) {
                        return $q->where('owner_type', 'main')
                            ->whereNull('owner_id')
                            ->where('menu_location', $request->input('menu_location', 'navbar'));
                    }),
            ],
            'menu_key' => 'nullable|string|max:255',
            'type' => 'required|in:route,page,external,group',
            'layout' => 'nullable|in:dropdown,mega',
            'parent_id' => 'nullable|integer',
            'external_url' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|in:published,draft,archived',
        ]);

        $parentId = $data['parent_id'] ?? null;
        if ($parentId) {
            $parent = AcademicNavItem::query()
                ->where('owner_type', 'main')
                ->whereNull('owner_id')
                ->where('menu_location', $data['menu_location'])
                ->where('id', $parentId)
                ->firstOrFail();

            $parentId = $parent->id;
        }

        $item->update([
            'label' => $data['label'],
            'slug' => $data['slug'],
            'menu_key' => $data['menu_key'] ?? null,
            'type' => $data['type'],
            'menu_location' => $data['menu_location'],
            'layout' => $data['layout'] ?? null,
            'parent_id' => $parentId,
            'external_url' => $data['type'] === 'external' ? ($data['external_url'] ?? null) : null,
            'icon' => $data['icon'] ?? null,
            'status' => $data['status'],
        ]);

        return redirect()
            ->route('admin.cms.main.menu.index', ['menu_location' => $data['menu_location']])
            ->with('success', 'Menu item updated successfully.');
    }

    public function destroy(int $item)
    {
        $item = AcademicNavItem::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('id', $item)
            ->firstOrFail();

        $item->delete();

        return response()->json(['success' => true, 'message' => 'Menu item deleted successfully.']);
    }

    public function sort(Request $request)
    {
        $data = $request->validate([
            'menu_location' => 'required|in:navbar,topbar',
            'parent_id' => 'nullable|integer',
            'order' => 'required|array',
        ]);

        $parentId = $data['parent_id'] ?? null;

        foreach ($data['order'] as $pos => $id) {
            AcademicNavItem::query()
                ->where('owner_type', 'main')
                ->whereNull('owner_id')
                ->where('menu_location', $data['menu_location'])
                ->where('id', $id)
                ->update([
                    'position' => $pos + 1,
                    'parent_id' => $parentId,
                ]);
        }

        return response()->json(['success' => true, 'message' => 'Menu order updated.']);
    }
}
