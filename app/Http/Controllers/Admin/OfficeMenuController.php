<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicNavItem;
use App\Models\AdminOffice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfficeMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view admin section')->only(['index','create','edit']);
        $this->middleware('permission:create admin section')->only(['create','store']);
        $this->middleware('permission:edit admin section')->only(['edit','update','sort']);
        $this->middleware('permission:delete admin section')->only(['destroy']);
    }

    public function index(string $slug)
    {
        $office = AdminOffice::where('slug',$slug)->firstOrFail();

        $items = AcademicNavItem::where('owner_type','office')
            ->where('owner_id',$office->id)
            ->orderBy('parent_id')
            ->orderBy('position')
            ->get();

        $byParent = $items->groupBy('parent_id');

        return view('admin.pages.administration.offices.menu.index', compact('office','items','byParent'));
    }

    public function create(string $slug)
    {
        $office = AdminOffice::where('slug',$slug)->firstOrFail();

        $parents = AcademicNavItem::where('owner_type','office')
            ->where('owner_id',$office->id)
            ->whereNull('parent_id')
            ->orderBy('position')
            ->get();

        return view('admin.pages.administration.offices.menu.create', compact('office','parents'));
    }

    public function edit(string $slug, int $item)
    {
        $office = AdminOffice::where('slug',$slug)->firstOrFail();

        $item = AcademicNavItem::where('owner_type','office')
            ->where('owner_id',$office->id)
            ->where('id',$item)
            ->firstOrFail();

        $parents = AcademicNavItem::where('owner_type','office')
            ->where('owner_id',$office->id)
            ->whereNull('parent_id')
            ->where('id','!=',$item->id)
            ->orderBy('position')
            ->get();

        return view('admin.pages.administration.offices.menu.edit', compact('office','item','parents'));
    }

    public function store(string $slug, Request $request)
    {
        $office = AdminOffice::where('slug',$slug)->firstOrFail();

        $data = $request->validate([
            'label' => 'required|string|max:255',
            'slug'  => [
                'required','string','max:255',
                Rule::unique('academic_nav_items', 'slug')->where(function($q) use ($office){
                    return $q->where('owner_type','office')->where('owner_id',$office->id);
                }),
            ],
            'menu_key' => 'nullable|string|max:255',
            'type' => 'required|in:route,page,external,group',
            'parent_id' => 'nullable|integer',
            'external_url' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|in:published,draft,archived',
        ]);

        $parentId = $data['parent_id'] ?? null;
        if ($parentId) {
            $parent = AcademicNavItem::where('owner_type','office')->where('owner_id',$office->id)->where('id',$parentId)->firstOrFail();
            $parentId = $parent->id;
        }

        AcademicNavItem::create([
            'academic_site_id' => null,
            'owner_type' => 'office',
            'owner_id' => $office->id,
            'parent_id' => $parentId,
            'label' => $data['label'],
            'slug' => $data['slug'],
            'menu_key' => $data['menu_key'] ?? null,
            'type' => $data['type'],
            'external_url' => $data['type'] === 'external' ? ($data['external_url'] ?? null) : null,
            'icon' => $data['icon'] ?? null,
            'status' => $data['status'],
            'position' => AcademicNavItem::where('owner_type','office')->where('owner_id',$office->id)->where('parent_id',$parentId)->max('position') + 1,
        ]);

        return redirect()->route('admin.administration.office.cms.menu.index', $office->slug)
            ->with('success','Menu item created successfully.');
    }

    public function update(string $slug, int $item, Request $request)
    {
        $office = AdminOffice::where('slug',$slug)->firstOrFail();

        $item = AcademicNavItem::where('owner_type','office')
            ->where('owner_id',$office->id)
            ->where('id',$item)
            ->firstOrFail();

        $data = $request->validate([
            'label' => 'required|string|max:255',
            'slug'  => [
                'required','string','max:255',
                Rule::unique('academic_nav_items', 'slug')
                    ->ignore($item->id)
                    ->where(function($q) use ($office){
                        return $q->where('owner_type','office')->where('owner_id',$office->id);
                    }),
            ],
            'menu_key' => 'nullable|string|max:255',
            'type' => 'required|in:route,page,external,group',
            'parent_id' => 'nullable|integer',
            'external_url' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|in:published,draft,archived',
        ]);

        $parentId = $data['parent_id'] ?? null;
        if ($parentId) {
            $parent = AcademicNavItem::where('owner_type','office')->where('owner_id',$office->id)->where('id',$parentId)->firstOrFail();
            $parentId = $parent->id;
        }

        $item->update([
            'label' => $data['label'],
            'slug' => $data['slug'],
            'menu_key' => $data['menu_key'] ?? null,
            'type' => $data['type'],
            'parent_id' => $parentId,
            'external_url' => $data['type'] === 'external' ? ($data['external_url'] ?? null) : null,
            'icon' => $data['icon'] ?? null,
            'status' => $data['status'],
        ]);

        return redirect()->route('admin.administration.office.cms.menu.index', $office->slug)
            ->with('success','Menu item updated successfully.');
    }

    public function destroy(string $slug, int $item)
    {
        $office = AdminOffice::where('slug',$slug)->firstOrFail();

        $item = AcademicNavItem::where('owner_type','office')
            ->where('owner_id',$office->id)
            ->where('id',$item)
            ->firstOrFail();

        $item->delete();

        return response()->json(['success'=>true,'message'=>'Menu item deleted successfully.']);
    }

    public function sort(string $slug, Request $request)
    {
        $office = AdminOffice::where('slug',$slug)->firstOrFail();

        $data = $request->validate([
            'parent_id' => 'nullable|integer',
            'order' => 'required|array',
        ]);

        $parentId = $data['parent_id'] ?? null;

        foreach ($data['order'] as $pos => $id) {
            AcademicNavItem::where('owner_type','office')
                ->where('owner_id',$office->id)
                ->where('id',$id)
                ->update([
                    'position' => $pos + 1,
                    'parent_id' => $parentId,
                ]);
        }

        return response()->json(['success'=>true,'message'=>'Menu order updated.']);
    }
}
