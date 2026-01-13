<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicNavItem;
use App\Models\AcademicSite;
use App\Models\AcademicPage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AcademicNavController extends Controller
{
    public function __construct() 
    {
        $this->middleware('permission:manage academic sites');
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

        // If nav type is PAGE — enforce: matching AcademicPage must exist
        // if ($data['type'] === 'page') {
        //     $page = AcademicPage::where('academic_site_id', $site->id)
        //         ->where('slug', $data['slug'])
        //         ->first();

        //     if (!$page) {
        //         return back()->with('warning', 'A page with matching slug does not exist. Create the page first.');
        //     }
        // }

        AcademicNavItem::create([
            'academic_site_id' => $site->id,
            'parent_id'        => $data['parent_id'] ?? null,
            'label'            => $data['label'],
            'slug'             => $data['slug'],
            'menu_key'         => $data['menu_key'] ?? null,
            'type'             => $data['type'],
            'external_url'     => $data['type'] === 'external' ? $data['external_url'] : null,
            'icon'             => $data['icon'] ?? null,
            'position'         => AcademicNavItem::where('academic_site_id', $site->id)->where('parent_id', $data['parent_id'])->max('position') + 1,
            'status'           => $data['status'],
        ]);

        return back()->with('success', 'Navigation item created successfully.');
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

        // If nav type is PAGE — enforce linked page must exist
        // if ($data['type'] === 'page') {
        //     $page = AcademicPage::where('academic_site_id', $item->academic_site_id)
        //         ->where('slug', $data['slug'])
        //         ->first();

        //     if (!$page) {
        //         return back()->with('warning', 'A page with matching slug does not exist. Create the page first.');
        //     }
        // }

        $item->update([
            'label'        => $data['label'],
            'slug'         => $data['slug'],
            'menu_key'     => $data['menu_key'] ?? $item->menu_key,
            'type'         => $data['type'],
            'external_url' => $data['type'] === 'external' ? $data['external_url'] : null,
            'icon'         => $data['icon'] ?? null,
            'status'       => $data['status'],
        ]);

        return back()->with('success', 'Navigation item updated successfully.');
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
