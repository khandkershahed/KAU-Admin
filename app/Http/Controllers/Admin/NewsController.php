<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view news')->only(['index']);
        $this->middleware('permission:create news')->only(['create', 'store']);
        $this->middleware('permission:edit news')->only(['edit', 'update']);
        $this->middleware('permission:delete news')->only(['destroy']);
    }

    /**
     * List with search + AJAX pagination
     */
    public function index(Request $request)
    {
        $query = News::query()
            ->orderByDesc('published_at')
            ->orderByDesc('id');

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['draft', 'published', 'unpublished'])) {
            $query->where('status', $request->status);
        }

        $news = $query->paginate(20);

        if ($request->ajax()) {
            return view('admin.pages.news.partials.table', [
                'news'   => $news,
                'search' => $request->get('q'),
                'status' => $request->get('status'),
            ])->render();
        }

        return view('admin.pages.news.index', [
            'news'   => $news,
            'search' => $request->get('q'),
            'status' => $request->get('status'),
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        // You can predefine categories/tags if you want
        return view('admin.pages.news.create');
    }

    /**
     * Store new news
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'title'         => 'required|string|max:255|unique:news,title',
                'summary'       => 'nullable|string',
                'content'       => 'nullable|string',
                'author'        => 'nullable|string|max:255',
                'published_at'  => 'nullable|date',
                'read_time'     => 'nullable|integer|min:1',
                'category'      => 'nullable|string|max:255',
                'tags'          => 'nullable|string', // comma separated
                'thumb_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'content_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'banner_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'is_featured'   => 'nullable|boolean',
                'status'        => 'required|in:draft,published,unpublished',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }

            // Handle images
            $uploaded = [
                'thumb_image'   => null,
                'content_image' => null,
                'banner_image'  => null,
            ];

            $filesToUpload = [
                'thumb_image'   => 'news/thumb',
                'content_image' => 'news/content',
                'banner_image'  => 'news/banner',
            ];

            foreach ($filesToUpload as $field => $path) {
                if ($request->hasFile($field)) {
                    $upload = customUpload($request->file($field), $path);
                    if ($upload['status'] === 0) {
                        return redirect()->back()->withInput()->with('error', $upload['error_message']);
                    }
                    $uploaded[$field] = $upload['file_path'];
                }
            }

            // Parse tags
            $tagsArray = null;
            if ($request->filled('tags')) {
                $tagsArray = array_values(array_filter(array_map('trim', explode(',', $request->tags))));
            }

            News::create([
                'title'         => $request->title,
                'summary'       => $request->summary,
                'content'       => $request->content,
                'author'        => $request->author,
                'published_at'  => $request->published_at,
                'read_time'     => $request->read_time ?? 1,
                'category'      => $request->category,
                'tags'          => $tagsArray,
                'thumb_image'   => $uploaded['thumb_image'],
                'content_image' => $uploaded['content_image'],
                'banner_image'  => $uploaded['banner_image'],
                'is_featured'   => $request->boolean('is_featured'),
                'status'        => $request->status,
            ]);

            DB::commit();

            return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating news: ' . $e->getMessage());
        }
    }

    /**
     * Edit form
     */
    public function edit(string $id)
    {
        $news = News::findOrFail($id);

        return view('admin.pages.news.edit', [
            'news' => $news,
        ]);
    }

    /**
     * Update
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $news = News::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title'         => 'required|string|max:255|unique:news,title,' . $news->id,
                'summary'       => 'nullable|string',
                'content'       => 'nullable|string',
                'author'        => 'nullable|string|max:255',
                'published_at'  => 'nullable|date',
                'read_time'     => 'nullable|integer|min:1',
                'category'      => 'nullable|string|max:255',
                'tags'          => 'nullable|string',
                'thumb_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'content_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'banner_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'is_featured'   => 'nullable|boolean',
                'status'        => 'required|in:draft,published,unpublished',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }

            $fields = [
                'thumb_image'   => 'news/thumb',
                'content_image' => 'news/content',
                'banner_image'  => 'news/banner',
            ];

            foreach ($fields as $field => $path) {
                if ($request->hasFile($field)) {
                    if (!empty($news->$field)) {
                        Storage::delete('public/' . $news->$field);
                    }
                    $upload = customUpload($request->file($field), $path);
                    if ($upload['status'] === 0) {
                        return redirect()->back()->withInput()->with('error', $upload['error_message']);
                    }
                    $news->$field = $upload['file_path'];
                }
            }

            $tagsArray = null;
            if ($request->filled('tags')) {
                $tagsArray = array_values(array_filter(array_map('trim', explode(',', $request->tags))));
            }

            $news->title        = $request->title;
            $news->summary      = $request->summary;
            $news->content      = $request->content;
            $news->author       = $request->author;
            $news->published_at = $request->published_at;
            $news->read_time    = $request->read_time ?? 1;
            $news->category     = $request->category;
            $news->tags         = $tagsArray;
            $news->is_featured  = $request->boolean('is_featured');
            $news->status       = $request->status;

            $news->save();

            DB::commit();

            return redirect()->back()->with('success', 'News updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating news: ' . $e->getMessage());
        }
    }

    /**
     * Delete
     */
    public function destroy(string $id)
    {
        $news = News::findOrFail($id);

        $files = ['thumb_image', 'content_image', 'banner_image'];

        foreach ($files as $field) {
            if (!empty($news->$field)) {
                Storage::delete('public/' . $news->$field);
            }
        }

        $news->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }
}
