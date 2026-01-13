<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicMenuGroup;
use App\Models\AcademicSite;
use App\Models\AcademicNavItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AcademicSiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view academic sites')->only(['index']);
        $this->middleware('permission:create academic sites')->only(['storeSite', 'storeGroup']);
        $this->middleware('permission:edit academic sites')->only(['updateSite', 'updateGroup']);
        $this->middleware('permission:delete academic sites')->only(['destroySite', 'destroyGroup']);
    }

    /**
     * MAIN PAGE — Groups + Sites + Nav
     */
    public function index(Request $request)
    {
        $groups = AcademicMenuGroup::with([
            'sites' => fn($q) => $q->orderBy('position'),
        ])->orderBy('position')->get();

        $firstSiteId = optional(optional($groups->first())->sites->first())->id;
        $selectedSiteId = $request->get('site_id', $firstSiteId);

        $selectedSite = null;
        $navItemsTree = collect(); // FIXED

        if ($selectedSiteId) {
            $selectedSite = AcademicSite::find($selectedSiteId);

            if ($selectedSite) {
                $navItems = AcademicNavItem::where('academic_site_id', $selectedSite->id)
                    ->orderBy('position')
                    ->get();

                $navItemsTree = $this->buildTree($navItems); // returns root nodes
            }
        }


        return view('admin.pages.academic.sites', [
            'groups'       => $groups,
            'selectedSite' => $selectedSite,
            'navItemsTree' => $navItemsTree,
        ]);
    }

    /**
     * Build tree structure from flat nav list
     */
    // private function buildTree($items)
    // {
    //     $grouped = $items->groupBy('parent_id');
    //     $build = function ($parentId) use (&$build, $grouped) {
    //         return ($grouped[$parentId] ?? collect())->map(function ($item) use (&$build) {
    //             return [
    //                 'model'    => $item,
    //                 'children' => $build($item->id)
    //             ];
    //         });
    //     };
    //     return $build(null);
    // }

    private function buildTree($items)
    {
        if (!$items instanceof \Illuminate\Support\Collection) {
            return collect();
        }

        $grouped = $items->groupBy('parent_id');

        $build = function ($parentId) use (&$build, $grouped) {
            $children = $grouped->get($parentId, collect());

            return $children->filter(function ($item) {
                return $item instanceof AcademicNavItem;
            })
                ->map(function ($item) use (&$build) {

                    // recursively attach children
                    $item->children = $build($item->id);

                    return $item;
                });
        };

        return $build(null);
    }






    public function storeGroup(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'status'   => 'required|in:published,draft,archived',
        ]);

        $data['position'] = AcademicMenuGroup::max('position') + 1;

        AcademicMenuGroup::create($data);

        return back()->with('success', 'Group created successfully.');
    }

    public function updateGroup(AcademicMenuGroup $group, Request $request)
    {
        $data = $request->validate([
            'title'  => 'required|string|max:255',
            'status' => 'required|in:published,draft,archived',
        ]);

        $group->update($data);

        return back()->with('success', 'Group updated successfully.');
    }

    public function destroyGroup(AcademicMenuGroup $group)
    {
        $group->delete();
        return response()->json(['success' => true, 'message' => 'Group deleted.']);
    }

    public function sortGroups(Request $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->order as $index => $id) {
                AcademicMenuGroup::where('id', $id)->update(['position' => $index + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Groups sorted successfully.']);
    }


    public function storeSite(Request $request)
    {
        $data = $request->validate([
            'academic_menu_group_id' => 'required|exists:academic_menu_groups,id',
            'name'                   => 'required|string|max:255',
            'short_name'             => 'nullable|string|max:50',
            'slug'                   => 'required|string|max:255|unique:academic_sites,slug',
            'short_description'      => 'nullable|string',
            'theme_primary_color'    => 'nullable|string|max:20',
            'theme_secondary_color'  => 'nullable|string|max:20',
            'status'                 => 'required|in:published,draft,archived',
            'logo'                   => 'nullable|image|max:2048',
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('academic/sites/logos', 'public');
        }

        $data['logo_path'] = $logoPath;
        $data['position']  = AcademicSite::where('academic_menu_group_id', $data['academic_menu_group_id'])->max('position') + 1;

        AcademicSite::create($data);

        return back()->with('success', 'Site created successfully.'); 
    }

    public function updateSite(AcademicSite $site, Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'short_name'            => 'nullable|string|max:50',
            'slug'                  => 'required|string|max:255|unique:academic_sites,slug,' . $site->id,
            'short_description'     => 'nullable|string',
            'theme_primary_color'   => 'nullable|string|max:20',
            'theme_secondary_color' => 'nullable|string|max:20',
            'status'                => 'required|in:published,draft,archived',
            'logo'                  => 'nullable|image|max:2048',
        ]);

        $logoPath = $site->logo_path;

        if ($request->hasFile('logo')) {
            if ($logoPath) Storage::disk('public')->delete($logoPath);
            $logoPath = $request->file('logo')->store('academic/sites/logos', 'public');
        }

        $data['logo_path'] = $logoPath;

        $site->update($data);

        return back()->with('success', 'Site updated successfully.');
    }

    public function destroySite(AcademicSite $site)
    {
        if ($site->logo_path) {
            Storage::disk('public')->delete($site->logo_path);
        }

        $site->delete();

        return response()->json(['success' => true, 'message' => 'Site deleted.']);
    }

    public function sortSites(Request $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->order as $index => $id) {
                AcademicSite::where('id', $id)->update([
                    'position'               => $index + 1,
                    'academic_menu_group_id' => $request->group_id,
                ]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Sites sorted successfully.']);
    }
}
