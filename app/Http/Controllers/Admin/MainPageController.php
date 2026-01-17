<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicNavItem;
use App\Models\AcademicPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MainPageController extends Controller
{
    public function index()
    {
        $pages = AcademicPage::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->orderByDesc('is_home')
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        return view('admin.pages.cms.main.pages.index', compact('pages'));
    }

    public function create()
    {
        $navItems = AcademicNavItem::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('type', 'page')
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        return view('admin.pages.cms.main.pages.create', [
            'navItems' => $navItems,
            'page' => null,
            'blocks' => collect(),
        ]);
    }

    public function edit(int $page)
    {
        $page = $this->mainPageOrFail($page);

        $navItems = AcademicNavItem::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('type', 'page')
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        $blocks = $page->blocks()->get();

        return view('admin.pages.cms.main.pages.edit', [
            'navItems' => $navItems,
            'page' => $page,
            'blocks' => $blocks,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nav_item_id' => 'required|exists:academic_nav_items,id',
            'title' => 'required|string|max:255',
            'template_key' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
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

        $navItem = AcademicNavItem::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('id', $data['nav_item_id'])
            ->firstOrFail();

        if ($navItem->type !== 'page') {
            return back()->withInput()->with('error', 'Selected menu item is not a PAGE type.');
        }

        $bannerImage = null;
        $ogImage = null;

        if ($request->hasFile('banner_image')) {
            $bannerImage = $request->file('banner_image')->store('main/pages/banner', 'public');
        }

        if ($request->hasFile('og_image')) {
            $ogImage = $request->file('og_image')->store('main/pages/og', 'public');
        }

        DB::beginTransaction();
        try {
            if (!empty($data['is_home'])) {
                AcademicPage::query()->where('owner_type', 'main')->whereNull('owner_id')->update(['is_home' => false]);
            }

            AcademicPage::create([
                'academic_site_id' => null,
                'nav_item_id' => $navItem->id,
                'owner_type' => 'main',
                'owner_id' => null,
                'page_key' => $navItem->menu_key,
                'slug' => $navItem->slug,
                'title' => $data['title'],
                'template_key' => $data['template_key'] ?? 'default',
                'settings' => $data['settings'] ?? [],
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

            return redirect()->route('admin.cms.main.pages.index')->with('success', 'Main page created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating main page: ' . $e->getMessage());
        }
    }

    public function update(int $page, Request $request)
    {
        $page = $this->mainPageOrFail($page);

        $data = $request->validate([
            'nav_item_id' => 'required|exists:academic_nav_items,id',
            'title' => 'required|string|max:255',
            'template_key' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
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

        $navItem = AcademicNavItem::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('id', $data['nav_item_id'])
            ->firstOrFail();

        if ($navItem->type !== 'page') {
            return back()->withInput()->with('error', 'Selected menu item is not a PAGE type.');
        }

        $bannerImage = $page->banner_image;
        $ogImage = $page->og_image;

        if ($request->hasFile('banner_image')) {
            if ($bannerImage) Storage::disk('public')->delete($bannerImage);
            $bannerImage = $request->file('banner_image')->store('main/pages/banner', 'public');
        }

        if ($request->hasFile('og_image')) {
            if ($ogImage) Storage::disk('public')->delete($ogImage);
            $ogImage = $request->file('og_image')->store('main/pages/og', 'public');
        }

        DB::beginTransaction();
        try {
            if (!empty($data['is_home'])) {
                AcademicPage::query()
                    ->where('owner_type', 'main')
                    ->whereNull('owner_id')
                    ->where('id', '!=', $page->id)
                    ->update(['is_home' => false]);
            }

            $page->update([
                'nav_item_id' => $navItem->id,
                'page_key' => $navItem->menu_key,
                'slug' => $navItem->slug,
                'title' => $data['title'],
                'template_key' => $data['template_key'] ?? $page->template_key,
                'settings' => $data['settings'] ?? ($page->settings ?? []),
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

            return redirect()->route('admin.cms.main.pages.index')->with('success', 'Main page updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating main page: ' . $e->getMessage());
        }
    }

    public function destroy(int $page)
    {
        $page = $this->mainPageOrFail($page);

        if ($page->banner_image) Storage::disk('public')->delete($page->banner_image);
        if ($page->og_image) Storage::disk('public')->delete($page->og_image);

        $page->delete();

        return response()->json(['success' => true, 'message' => 'Main page deleted successfully.']);
    }

    private function mainPageOrFail(int $id): AcademicPage
    {
        return AcademicPage::query()
            ->where('owner_type', 'main')
            ->whereNull('owner_id')
            ->where('id', $id)
            ->firstOrFail();
    }
}
