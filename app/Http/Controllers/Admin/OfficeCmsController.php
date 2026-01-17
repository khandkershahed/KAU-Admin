<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicNavItem;
use App\Models\AcademicPage;
use App\Models\AdminOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OfficeCmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view admin section')->only([
            'dashboard','pagesIndex','pagesCreate','pagesEdit',
        ]);
        $this->middleware('permission:create admin section')->only(['pagesCreate','pagesStore']);
        $this->middleware('permission:edit admin section')->only(['pagesEdit','pagesUpdate']);
        $this->middleware('permission:delete admin section')->only(['pagesDestroy']);
    }

    public function dashboard(string $slug)
    {
        $office = AdminOffice::where('slug', $slug)->firstOrFail();

        $pagesCount = AcademicPage::where('owner_type','office')->where('owner_id',$office->id)->count();
        $menuCount  = AcademicNavItem::where('owner_type','office')->where('owner_id',$office->id)->count();

        return view('admin.pages.administration.offices.dashboard', compact('office','pagesCount','menuCount'));
    }

    public function pagesIndex(string $slug)
    {
        $office = AdminOffice::where('slug', $slug)->firstOrFail();

        $pages = AcademicPage::where('owner_type','office')
            ->where('owner_id',$office->id)
            ->orderByDesc('is_home')
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        return view('admin.pages.administration.offices.pages.index', compact('office','pages'));
    }

    public function pagesCreate(string $slug)
    {
        $office = AdminOffice::where('slug', $slug)->firstOrFail();

        $navItems = AcademicNavItem::where('owner_type','office')
            ->where('owner_id',$office->id)
            ->where('type','page')
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        return view('admin.pages.administration.offices.pages.create', [
            'office' => $office,
            'navItems' => $navItems,
            'page' => null,
        ]);
    }

    public function pagesEdit(string $slug, int $page)
    {
        $office = AdminOffice::where('slug', $slug)->firstOrFail();
        $page = $this->officePageOrFail($office->id, $page);

        $navItems = AcademicNavItem::where('owner_type','office')
            ->where('owner_id',$office->id)
            ->where('type','page')
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        $blocks = $page->blocks()->get();

        return view('admin.pages.administration.offices.pages.edit', [
            'office' => $office,
            'navItems' => $navItems,
            'page' => $page,
            'blocks' => $blocks,
        ]);
    }

    public function pagesStore(string $slug, Request $request)
    {
        $office = AdminOffice::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'nav_item_id' => 'required|exists:academic_nav_items,id',
            'title' => 'required|string|max:255',
            'template_key' => 'nullable|string|max:255',
            'is_home' => 'nullable|boolean',
            'banner_title' => 'nullable|string|max:255',
            'banner_subtitle' => 'nullable|string|max:255',
            'banner_button' => 'nullable|string|max:255',
            'banner_button_url' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_tags' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'status' => 'nullable|in:published,draft,archived',
            'position' => 'nullable|integer',
            'banner_image' => 'nullable|image|max:4096',
            'og_image' => 'nullable|image|max:4096',
        ]);

        $navItem = AcademicNavItem::where('owner_type','office')
            ->where('owner_id',$office->id)
            ->where('id',$data['nav_item_id'])
            ->firstOrFail();

        if ($navItem->type !== 'page') {
            return back()->withInput()->with('error','Selected menu item is not a PAGE type.');
        }

        $bannerImage = null;
        $ogImage = null;

        if ($request->hasFile('banner_image')) {
            $bannerImage = $request->file('banner_image')->store('office/pages/banner','public');
        }
        if ($request->hasFile('og_image')) {
            $ogImage = $request->file('og_image')->store('office/pages/og','public');
        }

        DB::beginTransaction();
        try {
            if (!empty($data['is_home'])) {
                AcademicPage::where('owner_type','office')->where('owner_id',$office->id)->update(['is_home'=>false]);
            }

            AcademicPage::create([
                'academic_site_id' => null,
                'nav_item_id' => $navItem->id,
                'owner_type' => 'office',
                'owner_id' => $office->id,
                'page_key' => $navItem->menu_key,
                'slug' => $navItem->slug,
                'title' => $data['title'],
                'template_key' => $data['template_key'] ?? 'default',
                'is_home' => !empty($data['is_home']),
                'banner_title' => $data['banner_title'] ?? null,
                'banner_subtitle' => $data['banner_subtitle'] ?? null,
                'banner_button' => $data['banner_button'] ?? null,
                'banner_button_url' => $data['banner_button_url'] ?? null,
                'banner_image' => $bannerImage,
                'content' => $data['content'] ?? null,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_tags' => $data['meta_tags'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'og_image' => $ogImage,
                'status' => $data['status'] ?? 'published',
                'position' => $data['position'] ?? 0,
            ]);

            DB::commit();

            return redirect()->route('admin.administration.office.cms.pages.index', $office->slug)
                ->with('success','Office page created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error','Error creating office page: '.$e->getMessage());
        }
    }

    public function pagesUpdate(string $slug, int $page, Request $request)
    {
        $office = AdminOffice::where('slug', $slug)->firstOrFail();
        $page = $this->officePageOrFail($office->id, $page);

        $data = $request->validate([
            'nav_item_id' => 'required|exists:academic_nav_items,id',
            'title' => 'required|string|max:255',
            'template_key' => 'nullable|string|max:255',
            'is_home' => 'nullable|boolean',
            'banner_title' => 'nullable|string|max:255',
            'banner_subtitle' => 'nullable|string|max:255',
            'banner_button' => 'nullable|string|max:255',
            'banner_button_url' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_tags' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'status' => 'nullable|in:published,draft,archived',
            'position' => 'nullable|integer',
            'banner_image' => 'nullable|image|max:4096',
            'og_image' => 'nullable|image|max:4096',
        ]);

        $navItem = AcademicNavItem::where('owner_type','office')
            ->where('owner_id',$office->id)
            ->where('id',$data['nav_item_id'])
            ->firstOrFail();

        if ($navItem->type !== 'page') {
            return back()->withInput()->with('error','Selected menu item is not a PAGE type.');
        }

        $bannerImage = $page->banner_image;
        $ogImage = $page->og_image;

        if ($request->hasFile('banner_image')) {
            if ($bannerImage) Storage::disk('public')->delete($bannerImage);
            $bannerImage = $request->file('banner_image')->store('office/pages/banner','public');
        }
        if ($request->hasFile('og_image')) {
            if ($ogImage) Storage::disk('public')->delete($ogImage);
            $ogImage = $request->file('og_image')->store('office/pages/og','public');
        }

        DB::beginTransaction();
        try {
            if (!empty($data['is_home'])) {
                AcademicPage::where('owner_type','office')
                    ->where('owner_id',$office->id)
                    ->where('id','!=',$page->id)
                    ->update(['is_home'=>false]);
            }

            $page->update([
                'nav_item_id' => $navItem->id,
                'page_key' => $navItem->menu_key,
                'slug' => $navItem->slug,
                'title' => $data['title'],
                'template_key' => $data['template_key'] ?? $page->template_key,
                'is_home' => !empty($data['is_home']),
                'banner_title' => $data['banner_title'] ?? null,
                'banner_subtitle' => $data['banner_subtitle'] ?? null,
                'banner_button' => $data['banner_button'] ?? null,
                'banner_button_url' => $data['banner_button_url'] ?? null,
                'banner_image' => $bannerImage,
                'content' => $data['content'] ?? null,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_tags' => $data['meta_tags'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'og_image' => $ogImage,
                'status' => $data['status'] ?? $page->status,
                'position' => $data['position'] ?? $page->position,
            ]);

            DB::commit();

            return redirect()->route('admin.administration.office.cms.pages.index', $office->slug)
                ->with('success','Office page updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error','Error updating office page: '.$e->getMessage());
        }
    }

    public function pagesDestroy(string $slug, int $page)
    {
        $office = AdminOffice::where('slug', $slug)->firstOrFail();
        $page = $this->officePageOrFail($office->id, $page);

        if ($page->banner_image) Storage::disk('public')->delete($page->banner_image);
        if ($page->og_image) Storage::disk('public')->delete($page->og_image);

        $page->delete();

        return response()->json(['success'=>true,'message'=>'Office page deleted successfully.']);
    }

    protected function officePageOrFail(int $officeId, int $pageId): AcademicPage
    {
        return AcademicPage::where('owner_type','office')
            ->where('owner_id',$officeId)
            ->where('id',$pageId)
            ->firstOrFail();
    }
}
