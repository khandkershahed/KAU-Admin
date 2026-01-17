<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicNavItem;
use App\Models\AcademicSite;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AcademicNavController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage academic sites');
    }

    /**
     * CREATE NAV ITEM PAGE (NO MODAL)
     */
    public function create(AcademicSite $site, Request $request)
    {
        $parentId = $request->query('parent_id');

        $parents = AcademicNavItem::where('academic_site_id', $site->id)
            ->orderBy('parent_id')
            ->orderBy('position')
            ->get();

        return view('admin.pages.academic.nav.create', [
            'site' => $site,
            'parents' => $parents,
            'parentId' => $parentId,
        ]);
    }

    /**
     * EDIT NAV ITEM PAGE (NO MODAL)
     */
    public function edit(AcademicNavItem $item)
    {
        $site = AcademicSite::findOrFail($item->academic_site_id);

        $parents = AcademicNavItem::where('academic_site_id', $site->id)
            ->where('id', '!=', $item->id)
            ->orderBy('parent_id')
            ->orderBy('position')
            ->get();

        return view('admin.pages.academic.nav.edit', [
            'site' => $site,
            'item' => $item,
            'parents' => $parents,
        ]);
    }

    /**
     * STORE NAV ITEM
     */
    public function store(AcademicSite $site, Request $request)
    {
        $data = $request->validate([
            'label'        => 'required|string|max:255',
            'slug'         => [
                'required',
                'string',
                'max:255',
                Rule::unique('academic_nav_items')->where(function ($q) use ($site) {
                    return $q->where('academic_site_id', $site->id);
                }),
            ],
            'menu_key'     => 'nullable|string|max:255',
            'type'         => 'required|in:route,page,external,group',
            'parent_id'    => 'nullable|exists:academic_nav_items,id',
            'external_url' => 'nullable|string|max:1000',
            'icon'         => 'nullable|string|max:255',
            'status'       => 'required|in:published,draft,archived',
        ]);

        AcademicNavItem::create([
            'academic_site_id' => $site->id,
            'parent_id'        => $data['parent_id'] ?? null,
            'label'            => $data['label'],
            'slug'             => $data['slug'],
            'menu_key'         => $data['menu_key'] ?? null,
            'type'             => $data['type'],
            'external_url'     => $data['type'] === 'external' ? $data['external_url'] : null,
            'icon'             => $data['icon'] ?? null,
            'position'         => AcademicNavItem::where('academic_site_id', $site->id)
                                    ->where('parent_id', $data['parent_id'] ?? null)
                                    ->max('position') + 1,
            'status'           => $data['status'],
        ]);

        return redirect()->route('admin.academic.sites.index', ['site_id' => $site->id])
            ->with('success', 'Navigation item created successfully.');
    }

    /**
     * UPDATE NAV ITEM
     */
    public function update(AcademicNavItem $item, Request $request)
    {
        $data = $request->validate([
            'label'        => 'required|string|max:255',
            'slug'         => [
                'required',
                'string',
                'max:255',
                Rule::unique('academic_nav_items')
                    ->ignore($item->id)
                    ->where(function ($q) use ($item) {
                        return $q->where('academic_site_id', $item->academic_site_id);
                    }),
            ],
            'menu_key'     => 'nullable|string|max:255',
            'type'         => 'required|in:route,page,external,group',
            'external_url' => 'nullable|string|max:1000',
            'icon'         => 'nullable|string|max:255',
            'status'       => 'required|in:published,draft,archived',
        ]);

        $item->update([
            'label'        => $data['label'],
            'slug'         => $data['slug'],
            'menu_key'     => $data['menu_key'] ?? $item->menu_key,
            'type'         => $data['type'],
            'external_url' => $data['type'] === 'external' ? $data['external_url'] : null,
            'icon'         => $data['icon'] ?? null,
            'status'       => $data['status'],
        ]);

        return redirect()->route('admin.academic.sites.index', ['site_id' => $item->academic_site_id])
            ->with('success', 'Navigation item updated successfully.');
    }

    /**
     * DELETE NAV ITEM
     */
    public function destroy(AcademicNavItem $item)
    {
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Navigation item deleted successfully.',
        ]);
    }

    /**
     * SORT NAV ITEMS (drag & drop)
     */
    public function sort(AcademicSite $site, Request $request)
    {
        $order    = $request->get('order', []);
        $parentId = $request->get('parent_id');

        foreach ($order as $position => $id) {
            AcademicNavItem::where('id', $id)
                ->where('academic_site_id', $site->id)
                ->update([
                    'position'  => $position + 1,
                    'parent_id' => $parentId,
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Navigation order updated successfully.',
        ]);
    }
}
