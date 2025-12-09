<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicMenuGroup;
use App\Models\AcademicSite;
use App\Models\AcademicNavItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicSiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view academic sites')->only(['index']);
        $this->middleware('permission:create academic sites')->only(['storeSite', 'storeGroup']);
        $this->middleware('permission:edit academic sites')->only(['updateSite', 'sortSites']);
        $this->middleware('permission:delete academic sites')->only(['destroySite']);

        $this->middleware('permission:view academic groups')->only(['index']);
        $this->middleware('permission:create academic groups')->only(['storeGroup']);
        $this->middleware('permission:edit academic groups')->only(['updateGroup', 'sortGroups']);
        $this->middleware('permission:delete academic groups')->only(['destroyGroup']);
    }

    public function index(Request $request)
    {
        $groups = AcademicMenuGroup::with(['sites' => function ($q) {
            $q->orderBy('menu_order')->orderBy('id');
        }])
            ->orderBy('position')
            ->get();

        // Optionally, pre-load nav for a selected site
        $selectedSiteId = $request->get('site_id');
        $selectedSite = null;
        $navItemsTree = [];

        if ($selectedSiteId) {
            $selectedSite = AcademicSite::with(['navItems.page'])
                ->find($selectedSiteId);

            if ($selectedSite) {
                $navItems = $selectedSite->navItems()->orderBy('position')->orderBy('id')->get();
                $navByParent = $navItems->groupBy('parent_id');
                $buildTree = function ($parentId) use (&$buildTree, $navByParent) {
                    return ($navByParent[$parentId] ?? collect())->map(function ($item) use (&$buildTree) {
                        return [
                            'model' => $item,
                            'children' => $buildTree($item->id),
                        ];
                    });
                };
                $navItemsTree = $buildTree(null);
            }
        }

        return view('admin.pages.academic.sites', [
            'groups'       => $groups,
            'selectedSite' => $selectedSite,
            'navItemsTree' => $navItemsTree,
        ]);
    }

    // ----- GROUPS -----

    public function storeGroup(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'slug'     => 'required|string|max:255|unique:academic_menu_groups,slug',
            'position' => 'nullable|integer',
        ]);

        AcademicMenuGroup::create([
            'title'     => $data['title'],
            'slug'      => $data['slug'],
            'position'  => $data['position'] ?? 0,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Group created successfully.');
    }

    public function updateGroup(Request $request, AcademicMenuGroup $group)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'slug'     => 'required|string|max:255|unique:academic_menu_groups,slug,' . $group->id,
            'position' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $group->update([
            'title'     => $data['title'],
            'slug'      => $data['slug'],
            'position'  => $data['position'] ?? $group->position,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->back()->with('success', 'Group updated successfully.');
    }

    public function destroyGroup(AcademicMenuGroup $group)
    {
        $group->delete();
        return response()->json(['success' => true]);
    }

    public function sortGroups(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
        ]);

        foreach ($request->order as $position => $id) {
            AcademicMenuGroup::where('id', $id)->update(['position' => $position + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Group order updated.',
        ]);
    }

    // ----- SITES -----

    public function storeSite(Request $request)
    {
        $data = $request->validate([
            'academic_menu_group_id' => 'required|exists:academic_menu_groups,id',
            'name'                   => 'required|string|max:255',
            'short_name'             => 'nullable|string|max:50',
            'slug'                   => 'required|string|max:255|unique:academic_sites,slug',
            'base_url'               => 'nullable|string|max:255',
            'short_description'      => 'nullable|string',
            'theme_primary_color'    => 'nullable|string|max:50',
        ]);

        $maxOrder = AcademicSite::where('academic_menu_group_id', $data['academic_menu_group_id'])->max('menu_order') ?? 0;

        AcademicSite::create([
            'academic_menu_group_id' => $data['academic_menu_group_id'],
            'name'                   => $data['name'],
            'short_name'             => $data['short_name'] ?? null,
            'slug'                   => $data['slug'],
            'base_url'               => $data['base_url'] ?? null,
            'short_description'      => $data['short_description'] ?? null,
            'theme_primary_color'    => $data['theme_primary_color'] ?? null,
            'menu_order'             => $maxOrder + 1,
            'status'                 => 'published',
        ]);

        return redirect()->back()->with('success', 'Site created successfully.');
    }

    public function updateSite(Request $request, AcademicSite $site)
    {
        $data = $request->validate([
            'name'                   => 'required|string|max:255',
            'short_name'             => 'nullable|string|max:50',
            'slug'                   => 'required|string|max:255|unique:academic_sites,slug,' . $site->id,
            'base_url'               => 'nullable|string|max:255',
            'short_description'      => 'nullable|string',
            'theme_primary_color'    => 'nullable|string|max:50',
            'theme_secondary_color'  => 'nullable|string|max:50',
            'status'                 => 'nullable|in:draft,published,archived',
        ]);

        $site->update($data);

        return redirect()->back()->with('success', 'Site updated successfully.');
    }

    public function destroySite(AcademicSite $site)
    {
        $site->delete();
        return response()->json(['success' => true]);
    }

    public function sortSites(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
        ]);

        foreach ($request->order as $position => $id) {
            AcademicSite::where('id', $id)->update(['menu_order' => $position + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Site order updated.',
        ]);
    }
}
