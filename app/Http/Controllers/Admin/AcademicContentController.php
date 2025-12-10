<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSite;
use App\Models\AcademicPage;
use App\Models\AcademicNavItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AcademicContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view academic pages')->only(['index']);
        $this->middleware('permission:create academic pages')->only(['storePage']);
        $this->middleware('permission:edit academic pages')->only(['updatePage']);
        $this->middleware('permission:delete academic pages')->only(['destroyPage']);
    }

    /* ==========================================================================
        INDEX
       ========================================================================== */

    public function index(Request $request)
    {
        $sites = AcademicSite::orderBy('name')->get();
        $siteId = $request->get('site_id', optional($sites->first())->id);

        $selectedSite = null;
        $pages = collect();
        $navItems = collect();

        if ($siteId) {
            $selectedSite = AcademicSite::find($siteId);

            if ($selectedSite) {
                $pages = AcademicPage::where('academic_site_id', $siteId)
                    ->orderByDesc('is_home')
                    ->orderBy('position')
                    ->orderBy('id')
                    ->get();

                $navItems = AcademicNavItem::where('academic_site_id', $siteId)
                    ->where('type', 'page')
                    ->orderBy('position')
                    ->orderBy('id')
                    ->get();
            }
        }

        return view('admin.pages.academic.pages', [
            'sites'        => $sites,
            'selectedSite' => $selectedSite,
            'pages'        => $pages,
            'navItems'     => $navItems,
        ]);
    }

    /* ==========================================================================
        IMAGE HANDLER (reusable)
       ========================================================================== */

    protected function handleImages(Request $request, ?AcademicPage $page = null): array
    {
        $bannerImage = $page?->banner_image;
        $ogImage     = $page?->og_image;

        if ($request->hasFile('banner_image')) {
            if ($bannerImage) {
                Storage::disk('public')->delete($bannerImage);
            }
            $bannerImage = $request->file('banner_image')->store('academic/pages/banner', 'public');
        }

        if ($request->hasFile('og_image')) {
            if ($ogImage) {
                Storage::disk('public')->delete($ogImage);
            }
            $ogImage = $request->file('og_image')->store('academic/pages/og', 'public');
        }

        return [$bannerImage, $ogImage];
    }

    /* ==========================================================================
        STORE PAGE (STRICT VERSION)
       ========================================================================== */

    public function storePage(Request $request)
    {
        $data = $request->validate([
            'academic_site_id'     => 'required|exists:academic_sites,id',
            'nav_item_id'          => 'required|exists:academic_nav_items,id',

            'title'                => 'required|string|max:255',

            'is_home'              => 'nullable|boolean',
            'is_department_boxes'  => 'nullable|boolean',
            'is_faculty_members'   => 'nullable|boolean',

            'banner_title'         => 'nullable|string|max:255',
            'banner_subtitle'      => 'nullable|string|max:255',
            'banner_button'        => 'nullable|string|max:255',
            'banner_button_url'    => 'nullable|string|max:1000',

            'content'              => 'nullable|string',

            'meta_title'           => 'nullable|string|max:255',
            'meta_tags'            => 'nullable|string|max:255',
            'meta_description'     => 'nullable|string',

            'status'               => 'nullable|in:published,draft,archived',
            'position'             => 'nullable|integer',

            'banner_image'         => 'nullable|image|max:4096',
            'og_image'             => 'nullable|image|max:4096',
        ]);

        $site = AcademicSite::findOrFail($data['academic_site_id']);

        /* -----------------------------
            Validate Navigation Item
        ------------------------------*/
        $navItem = AcademicNavItem::where('academic_site_id', $site->id)
            ->where('id', $data['nav_item_id'])
            ->firstOrFail();

        if ($navItem->type !== 'page') {
            return back()->with('error', 'Selected navigation item is not a PAGE type menu.');
        }

        // forced sync
        $data['slug'] = $navItem->slug;
        $data['page_key'] = $navItem->menu_key;

        [$bannerImage, $ogImage] = $this->handleImages($request);

        DB::beginTransaction();
        try {
            if (!empty($data['is_home'])) {
                AcademicPage::where('academic_site_id', $site->id)->update(['is_home' => false]);
            }

            AcademicPage::create([
                'academic_site_id'    => $site->id,
                'nav_item_id'         => $navItem->id,
                'page_key'            => $data['page_key'],
                'slug'                => $data['slug'],

                'title'               => $data['title'],

                'is_home'             => !empty($data['is_home']),
                'is_department_boxes' => !empty($data['is_department_boxes']),
                'is_faculty_members'  => !empty($data['is_faculty_members']),

                'banner_title'        => $data['banner_title'] ?? null,
                'banner_subtitle'     => $data['banner_subtitle'] ?? null,
                'banner_button'       => $data['banner_button'] ?? null,
                'banner_button_url'   => $data['banner_button_url'] ?? null,

                'banner_image'        => $bannerImage,
                'content'             => $data['content'] ?? null,

                'meta_title'          => $data['meta_title'] ?? null,
                'meta_tags'           => $data['meta_tags'] ?? null,
                'meta_description'    => $data['meta_description'] ?? null,
                'og_image'            => $ogImage,

                'status'              => $data['status'] ?? 'published',
                'position'            => $data['position'] ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.academic.pages.index', ['site_id' => $site->id])
                ->with('success', 'Page created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating page: ' . $e->getMessage());
        }
    }

    /* ==========================================================================
        UPDATE PAGE (STRICT VERSION)
       ========================================================================== */

    public function updatePage(AcademicPage $page, Request $request)
    {
        $data = $request->validate([
            'nav_item_id'          => 'required|exists:academic_nav_items,id',

            'title'                => 'required|string|max:255',

            'is_home'              => 'nullable|boolean',
            'is_department_boxes'  => 'nullable|boolean',
            'is_faculty_members'   => 'nullable|boolean',

            'banner_title'         => 'nullable|string|max:255',
            'banner_subtitle'      => 'nullable|string|max:255',
            'banner_button'        => 'nullable|string|max:255',
            'banner_button_url'    => 'nullable|string|max:1000',

            'content'              => 'nullable|string',

            'meta_title'           => 'nullable|string|max:255',
            'meta_tags'            => 'nullable|string|max:255',
            'meta_description'     => 'nullable|string',

            'status'               => 'nullable|in:published,draft,archived',
            'position'             => 'nullable|integer',

            'banner_image'         => 'nullable|image|max:4096',
            'og_image'             => 'nullable|image|max:4096',
        ]);

        $site = AcademicSite::findOrFail($page->academic_site_id);

        /* -----------------------------
            Validate Navigation Item
        ------------------------------*/
        $navItem = AcademicNavItem::where('academic_site_id', $site->id)
            ->where('id', $data['nav_item_id'])
            ->firstOrFail();

        if ($navItem->type !== 'page') {
            return back()->with('error', 'Selected navigation item is not a PAGE type menu.');
        }

        // forced sync
        $data['slug'] = $navItem->slug;
        $data['page_key'] = $navItem->menu_key;

        [$bannerImage, $ogImage] = $this->handleImages($request, $page);

        DB::beginTransaction();
        try {
            if (!empty($data['is_home'])) {
                AcademicPage::where('academic_site_id', $site->id)
                    ->where('id', '!=', $page->id)
                    ->update(['is_home' => false]);
            }

            $page->update([
                'nav_item_id'         => $navItem->id,
                'page_key'            => $data['page_key'],
                'slug'                => $data['slug'],

                'title'               => $data['title'],

                'is_home'             => !empty($data['is_home']),
                'is_department_boxes' => !empty($data['is_department_boxes']),
                'is_faculty_members'  => !empty($data['is_faculty_members']),

                'banner_title'        => $data['banner_title'] ?? null,
                'banner_subtitle'     => $data['banner_subtitle'] ?? null,
                'banner_button'       => $data['banner_button'] ?? null,
                'banner_button_url'   => $data['banner_button_url'] ?? null,

                'banner_image'        => $bannerImage,
                'content'             => $data['content'] ?? null,

                'meta_title'          => $data['meta_title'] ?? null,
                'meta_tags'           => $data['meta_tags'] ?? null,
                'meta_description'    => $data['meta_description'] ?? null,
                'og_image'            => $ogImage,

                'status'              => $data['status'] ?? $page->status,
                'position'            => $data['position'] ?? $page->position,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.academic.pages.index', ['site_id' => $page->academic_site_id])
                ->with('success', 'Page updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating page: ' . $e->getMessage());
        }
    }

    /* ==========================================================================
        DELETE PAGE (AJAX PATTERN)
       ========================================================================== */

    public function destroyPage(AcademicPage $page)
    {
        if ($page->banner_image) {
            Storage::disk('public')->delete($page->banner_image);
        }
        if ($page->og_image) {
            Storage::disk('public')->delete($page->og_image);
        }

        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully.'
        ]);
    }
}
