<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSite;
use App\Models\AcademicNavItem;
use Illuminate\Http\Request;

class AcademicNavController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:edit academic nav');
    }

    public function store(Request $request, AcademicSite $site)
    {
        $data = $request->validate([
            'label'    => 'required|string|max:255',
            'menu_key' => 'nullable|string|max:255',
            'type'     => 'required|in:route,page,external,group',
            'page_id'  => 'nullable|exists:academic_pages,id',
            'route_path' => 'nullable|string|max:255',
            'external_url' => 'nullable|url',
            'parent_id' => 'nullable|exists:academic_nav_items,id',
            'icon'      => 'nullable|string|max:255',
        ]);

        $maxPos = AcademicNavItem::where('academic_site_id', $site->id)
            ->where('parent_id', $data['parent_id'] ?? null)
            ->max('position') ?? 0;

        AcademicNavItem::create([
            'academic_site_id' => $site->id,
            'parent_id'        => $data['parent_id'] ?? null,
            'label'            => $data['label'],
            'menu_key'         => $data['menu_key'] ?? null,
            'type'             => $data['type'],
            'page_id'          => $data['type'] === 'page' ? $data['page_id'] ?? null : null,
            'route_path'       => $data['type'] === 'route' ? $data['route_path'] ?? null : null,
            'external_url'     => $data['type'] === 'external' ? $data['external_url'] ?? null : null,
            'icon'             => $data['icon'] ?? null,
            'position'         => $maxPos + 1,
            'is_active'        => true,
        ]);

        return redirect()->back()->with('success', 'Menu item created.');
    }

    public function update(Request $request, AcademicNavItem $item)
    {
        $data = $request->validate([
            'label'    => 'required|string|max:255',
            'menu_key' => 'nullable|string|max:255',
            'type'     => 'required|in:route,page,external,group',
            'page_id'  => 'nullable|exists:academic_pages,id',
            'route_path'   => 'nullable|string|max:255',
            'external_url' => 'nullable|url',
            'icon'      => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $item->update([
            'label'        => $data['label'],
            'menu_key'     => $data['menu_key'] ?? null,
            'type'         => $data['type'],
            'page_id'      => $data['type'] === 'page' ? $data['page_id'] ?? null : null,
            'route_path'   => $data['type'] === 'route' ? $data['route_path'] ?? null : null,
            'external_url' => $data['type'] === 'external' ? $data['external_url'] ?? null : null,
            'icon'         => $data['icon'] ?? null,
            'is_active'    => $request->boolean('is_active'),
        ]);

        return redirect()->back()->with('success', 'Menu item updated.');
    }

    public function destroy(AcademicNavItem $item)
    {
        $item->delete();
        return response()->json(['success' => true]);
    }

    public function sort(Request $request, AcademicSite $site)
    {
        $request->validate([
            'order' => 'required|array', // flat ordered array of nav item IDs
        ]);

        foreach ($request->order as $position => $id) {
            AcademicNavItem::where('id', $id)
                ->where('academic_site_id', $site->id)
                ->update(['position' => $position + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Menu order updated.',
        ]);
    }
}
