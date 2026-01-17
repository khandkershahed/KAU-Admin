<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicPage;
use Illuminate\Http\Request;

class MainPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage main pages');
    }

    public function index()
    {
        $pages = AcademicPage::where('owner_type', 'main')
            ->whereNull('owner_id')
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.cms.main.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.cms.main.pages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'slug'         => 'required|string|max:255|unique:academic_pages,slug',
            'template_key' => 'required|string|max:255',
            'status'       => 'required|in:draft,published',
        ]);

        AcademicPage::create([
            'owner_type'   => 'main',
            'owner_id'     => null,
            'title'        => $data['title'],
            'slug'         => $data['slug'],
            'template_key' => $data['template_key'],
            'status'       => $data['status'],
        ]);

        return redirect()->route('cms.main.pages.index')
            ->with('success', 'Page created successfully.');
    }

    public function edit(AcademicPage $page)
    {
        return view('admin.pages.cms.main.pages.edit', compact('page'));
    }

    public function update(Request $request, AcademicPage $page)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'slug'         => 'required|string|max:255|unique:academic_pages,slug,' . $page->id,
            'template_key' => 'required|string|max:255',
            'status'       => 'required|in:draft,published',
        ]);

        $page->update($data);

        return redirect()->route('cms.main.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    public function destroy(AcademicPage $page)
    {
        $page->delete();

        return response()->json(['success' => true]);
    }
}
