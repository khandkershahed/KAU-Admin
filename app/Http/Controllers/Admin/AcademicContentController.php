<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSite;
use App\Models\AcademicPage;
use Illuminate\Http\Request;
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

    public function index(Request $request)
    {
        $sites = AcademicSite::orderBy('name')->get();
        $siteId = $request->get('site_id', optional($sites->first())->id);

        $pages = collect();
        $selectedSite = null;

        if ($siteId) {
            $selectedSite = AcademicSite::find($siteId);
            $pages = AcademicPage::where('academic_site_id', $siteId)
                ->orderByDesc('is_home')
                ->orderBy('position')
                ->orderBy('id')
                ->get();
        }

        return view('admin.pages.academic.pages', [
            'sites'        => $sites,
            'selectedSite' => $selectedSite,
            'pages'        => $pages,
        ]);
    }

    public function storePage(Request $request)
    {
        $data = $request->validate([
            'academic_site_id' => 'required|exists:academic_sites,id',
            'page_key'         => 'nullable|string|max:255',
            'slug'             => 'required|string|max:255',
            'title'            => 'required|string|max:255',
            'subtitle'         => 'nullable|string|max:255',
            'is_home'          => 'nullable|boolean',
            'content'          => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_tags'        => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'banner_image'     => 'nullable|image|max:5120',
            'og_image'         => 'nullable|image|max:5120',
            'banner_title'     => 'nullable|string|max:255',
            'banner_subtitle'  => 'nullable|string|max:255',
            'banner_button'    => 'nullable|string|max:255',
            'banner_button_url' => 'nullable|string|max:255',
        ]);

        $siteId = $data['academic_site_id'];

        // If is_home=true, unset others
        $isHome = $request->boolean('is_home');
        if ($isHome) {
            AcademicPage::where('academic_site_id', $siteId)->update(['is_home' => false]);
        }

        $bannerPath = null;
        if ($request->hasFile('banner_image')) {
            $bannerPath = $request->file('banner_image')->store('academic/pages/banner', 'public');
        }

        $ogPath = null;
        if ($request->hasFile('og_image')) {
            $ogPath = $request->file('og_image')->store('academic/pages/og', 'public');
        }

        AcademicPage::create([
            'academic_site_id'  => $siteId,
            'page_key'          => $data['page_key'] ?? null,
            'slug'              => $data['slug'],
            'title'             => $data['title'],
            'subtitle'          => $data['subtitle'] ?? null,
            'is_home'           => $isHome,
            'banner_image'      => $bannerPath,
            'content'           => $data['content'] ?? null,
            'meta_title'        => $data['meta_title'] ?? null,
            'meta_tags'         => $data['meta_tags'] ?? null,
            'meta_description'  => $data['meta_description'] ?? null,
            'og_image'          => $ogPath,
            'banner_title'      => $data['banner_title'] ?? null,
            'banner_subtitle'   => $data['banner_subtitle'] ?? null,
            'banner_button'     => $data['banner_button'] ?? null,
            'banner_button_url' => $data['banner_button_url'] ?? null,
            'is_active'         => true,
            'position'          => 0,
        ]);

        return redirect()->back()->with('success', 'Page created successfully.');
    }

    public function updatePage(Request $request, AcademicPage $page)
    {
        $data = $request->validate([
            'page_key'         => 'nullable|string|max:255',
            'slug'             => 'required|string|max:255',
            'title'            => 'required|string|max:255',
            'subtitle'         => 'nullable|string|max:255',
            'is_home'          => 'nullable|boolean',
            'content'          => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_tags'        => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'banner_image'     => 'nullable|image|max:5120',
            'og_image'         => 'nullable|image|max:5120',
            'banner_title'     => 'nullable|string|max:255',
            'banner_subtitle'  => 'nullable|string|max:255',
            'banner_button'    => 'nullable|string|max:255',
            'banner_button_url' => 'nullable|string|max:255',
            'is_active'        => 'nullable|boolean',
        ]);

        $isHome = $request->boolean('is_home');
        if ($isHome && !$page->is_home) {
            AcademicPage::where('academic_site_id', $page->academic_site_id)->update(['is_home' => false]);
        }

        if ($request->hasFile('banner_image')) {
            if ($page->banner_image) {
                Storage::disk('public')->delete($page->banner_image);
            }
            $page->banner_image = $request->file('banner_image')->store('academic/pages/banner', 'public');
        }

        if ($request->hasFile('og_image')) {
            if ($page->og_image) {
                Storage::disk('public')->delete($page->og_image);
            }
            $page->og_image = $request->file('og_image')->store('academic/pages/og', 'public');
        }

        $page->page_key          = $data['page_key'] ?? $page->page_key;
        $page->slug              = $data['slug'];
        $page->title             = $data['title'];
        $page->subtitle          = $data['subtitle'] ?? null;
        $page->is_home           = $isHome;
        $page->content           = $data['content'] ?? null;
        $page->meta_title        = $data['meta_title'] ?? null;
        $page->meta_tags         = $data['meta_tags'] ?? null;
        $page->meta_description  = $data['meta_description'] ?? null;
        $page->banner_title      = $data['banner_title'] ?? null;
        $page->banner_subtitle   = $data['banner_subtitle'] ?? null;
        $page->banner_button     = $data['banner_button'] ?? null;
        $page->banner_button_url = $data['banner_button_url'] ?? null;
        $page->is_active         = $request->boolean('is_active', true);

        $page->save();

        return redirect()->back()->with('success', 'Page updated successfully.');
    }

    public function destroyPage(AcademicPage $page)
    {
        if ($page->banner_image) {
            Storage::disk('public')->delete($page->banner_image);
        }
        if ($page->og_image) {
            Storage::disk('public')->delete($page->og_image);
        }

        $page->delete();
        return response()->json(['success' => true]);
    }
}
