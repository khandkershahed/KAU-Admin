<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AboutPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view about page')->only(['index']);
        $this->middleware('permission:create about page')->only(['create', 'store']);
        $this->middleware('permission:edit about page')->only(['edit', 'update', 'toggleFeatured', 'toggleStatus', 'updateOrder']);
        $this->middleware('permission:delete about page')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $search = $request->get('q');
        $status = $request->get('status');

        $query = AboutPage::query()
            ->orderBy('menu_order')
            ->orderBy('id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('menu_label', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $pages = $query->paginate(20);

        if ($request->ajax()) {
            return view('admin.pages.about.partials.table', [
                'pages'  => $pages,
                'search' => $search,
                'status' => $status,
            ])->render();
        }

        return view('admin.pages.about.index', [
            'pages'  => $pages,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function create()
    {
        return view('admin.pages.about.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'title'            => 'required|string|max:255',
                'menu_label'       => 'nullable|string|max:255',
                'banner_title'     => 'nullable|string|max:255',
                'banner_subtitle'  => 'nullable|string|max:255',
                'banner_icon'      => 'nullable|string|max:255',
                'banner_image'     => 'nullable|image|max:5120',
                'excerpt'          => 'nullable|string',
                'content'          => 'nullable|string',
                'meta_title'       => 'nullable|string|max:255',
                'meta_tags'        => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'is_featured'      => 'nullable|in:0,1',
                'status'           => 'nullable|in:draft,published,archived',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }



            $bannerImagePath = null;
            if ($request->hasFile('banner_image')) {
                $upload = customUpload($request->file('banner_image'), 'about/banner');
                if ($upload['status'] === 0) {
                    return redirect()->back()->withInput()->with('error', $upload['error_message']);
                }
                $bannerImagePath = $upload['file_path'];
            }

            $maxOrder = AboutPage::max('menu_order') ?? 0;

            AboutPage::create([
                'title'            => $request->title,
                'menu_label'       => $request->menu_label ?: $request->title,
                'banner_title'     => $request->banner_title ?: $request->title,
                'banner_subtitle'  => $request->banner_subtitle,
                'banner_icon'      => $request->banner_icon,
                'banner_image'     => $bannerImagePath,
                'excerpt'          => $request->excerpt,
                'content'          => $request->content,
                'menu_order'       => $maxOrder + 1,
                'is_featured'      => $request->is_featured ?? 0,
                'status'           => $request->status ?? 'published',
                'meta_title'       => $request->meta_title,
                'meta_tags'        => $request->meta_tags,
                'meta_description' => $request->meta_description,
                'created_by'       => Auth::guard('admin')->id(),
            ]);

            DB::commit();

            return redirect()->route('admin.about.index')
                ->with('success', 'About page created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error while creating page: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $page = AboutPage::findOrFail($id);

        return view('admin.pages.about.edit', [
            'page' => $page,
        ]);
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $page = AboutPage::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title'            => 'required|string|max:255',
                'menu_label'       => 'nullable|string|max:255',
                'banner_title'     => 'nullable|string|max:255',
                'banner_subtitle'  => 'nullable|string|max:255',
                'banner_icon'      => 'nullable|string|max:255',
                'banner_image'     => 'nullable|image|max:5120',
                'excerpt'          => 'nullable|string',
                'content'          => 'nullable|string',
                'meta_title'       => 'nullable|string|max:255',
                'meta_tags'        => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'is_featured'      => 'nullable|in:0,1',
                'status'           => 'nullable|in:draft,published,archived',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }



            $bannerImagePath = $page->banner_image;
            if ($request->hasFile('banner_image')) {
                $upload = customUpload($request->file('banner_image'), 'about/banner');
                if ($upload['status'] === 0) {
                    return redirect()->back()->withInput()->with('error', $upload['error_message']);
                }
                $bannerImagePath = $upload['file_path'];
            }

            $page->update([
                'title'            => $request->title,
                'menu_label'       => $request->menu_label ?: $request->title,
                'banner_title'     => $request->banner_title ?: $request->title,
                'banner_subtitle'  => $request->banner_subtitle,
                'banner_icon'      => $request->banner_icon,
                'banner_image'     => $bannerImagePath,
                'excerpt'          => $request->excerpt,
                'content'          => $request->content,
                'is_featured'      => $request->is_featured ?? 0,
                'status'           => $request->status ?? 'published',
                'meta_title'       => $request->meta_title,
                'meta_tags'        => $request->meta_tags,
                'meta_description' => $request->meta_description,
                'updated_by'       => Auth::guard('admin')->id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'About page updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error while updating page: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $page = AboutPage::findOrFail($id);
        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully.',
        ]);
    }

    public function toggleFeatured(AboutPage $page, Request $request)
    {
        $request->validate(['is_featured' => 'required|boolean']);

        $page->is_featured = $request->boolean('is_featured');
        $page->save();

        return response()->json([
            'success' => true,
            'message' => 'Featured status updated.',
        ]);
    }

    public function toggleStatus(AboutPage $page, Request $request)
    {
        $request->validate(['status' => 'required|in:draft,published,archived']);

        $page->status = $request->status;
        $page->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated.',
        ]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->order as $index => $id) {
                AboutPage::where('id', $id)->update(['menu_order' => $index + 1]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Menu order updated successfully.',
        ]);
    }
}
