<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view notice')->only(['index']);
        $this->middleware('permission:create notice')->only(['create', 'store']);
        $this->middleware('permission:edit notice')->only(['edit', 'update']);
        $this->middleware('permission:delete notice')->only(['destroy']);
    }

    public function index(Request $request)
    {
        // CATEGORY LIST (LEFT TABLE)
        $categoryQuery = NoticeCategory::orderBy('name');

        if ($request->filled('category_search')) {
            $search = $request->get('category_search');
            $categoryQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Use separate page parameter name so both tables can paginate independently
        $categories = $categoryQuery->paginate(10, ['*'], 'category_page');

        // NOTICE LIST (RIGHT TABLE)
        $noticeQuery = Notice::with('category')
            ->orderByDesc('publish_date')
            ->orderByDesc('id');

        if ($request->filled('notice_search')) {
            $search = $request->get('notice_search');
            $noticeQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $notices = $noticeQuery->paginate(20, ['*'], 'notice_page');

        // AJAX partial loading
        if ($request->ajax()) {
            $target = $request->get('target');

            if ($target === 'categories') {
                return view('admin.pages.notice.partials.category_table', [
                    'categories'      => $categories,
                ])->render();
            }

            if ($target === 'notices') {
                return view('admin.pages.notice.partials.notice_table', [
                    'notices'         => $notices,
                ])->render();
            }
        }

        // Full page (first load or normal request)
        return view('admin.pages.notice.index', [
            'categories'      => $categories,
            'notices'         => $notices,
            'categorySearch'  => $request->get('category_search'),
            'noticeSearch'    => $request->get('notice_search'),
        ]);
    }


    public function create()
    {
        $data = [
            'categories' => NoticeCategory::orderBy('name')->get(['id', 'name']),
        ];

        return view('admin.pages.notice.create', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'category_id'       => 'nullable|exists:notice_categories,id',
                'title'             => 'required|string|max:255',
                'body'              => 'nullable|string',
                'publish_date'      => 'nullable|date',
                'attachments.*'     => 'nullable|file|max:5120', // 5MB each
                'meta_title'        => 'nullable|string|max:255',
                'meta_tags'         => 'nullable|string|max:255',
                'meta_description'  => 'nullable|string',
                'is_featured'       => 'nullable|in:0,1',
                'status'            => 'nullable|in:draft,published,archived',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }

            // Handle attachments
            $storedAttachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file) {
                        $upload = customUpload($file, 'notices/attachments');
                        if ($upload['status'] === 0) {
                            return redirect()->back()->with('error', $upload['error_message']);
                        }
                        $storedAttachments[] = $upload['file_path'];
                    }
                }
            }

            $firstAttachmentType = null;
            if (count($storedAttachments)) {
                $ext = pathinfo($storedAttachments[0], PATHINFO_EXTENSION);
                $firstAttachmentType = strtolower($ext);
            }

            Notice::create([
                'category_id'      => $request->category_id,
                'title'            => $request->title,
                'body'             => $request->body,
                'publish_date'     => $request->publish_date,
                'attachments'      => count($storedAttachments) ? $storedAttachments : null,
                'attachment_type'  => $firstAttachmentType,
                'meta_title'       => $request->meta_title,
                'meta_tags'        => $request->meta_tags,
                'meta_description' => $request->meta_description,
                'views'            => 0,
                'is_featured'      => $request->is_featured ?? 0,
                'status'           => $request->status ?? 'published',
                'created_by'       => Auth::guard('admin')->id(),
            ]);

            DB::commit();

            return redirect()->route('admin.notice.index')
                ->with('success', 'Notice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error while creating notice: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $data = [
            'notice'     => Notice::findOrFail($id),
            'categories' => NoticeCategory::orderBy('name')->get(['id', 'name']),
        ];

        return view('admin.pages.notice.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $notice = Notice::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'category_id'       => 'nullable|exists:notice_categories,id',
                'title'             => 'required|string|max:255',
                'body'              => 'nullable|string',
                'publish_date'      => 'nullable|date',
                'attachments.*'     => 'nullable|file|max:5120',
                'meta_title'        => 'nullable|string|max:255',
                'meta_tags'         => 'nullable|string|max:255',
                'meta_description'  => 'nullable|string',
                'is_featured'       => 'nullable|in:0,1',
                'status'            => 'nullable|in:draft,published,archived',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }

            $storedAttachments = $notice->attachments ?? [];

            if ($request->hasFile('attachments')) {
                // Option A: overwrite existing attachments
                // delete old files
                if (is_array($storedAttachments)) {
                    foreach ($storedAttachments as $oldFile) {
                        if ($oldFile) {
                            Storage::delete('public/' . $oldFile);
                        }
                    }
                }
                $storedAttachments = [];

                foreach ($request->file('attachments') as $file) {
                    if ($file) {
                        $upload = customUpload($file, 'notices/attachments');
                        if ($upload['status'] === 0) {
                            return redirect()->back()->with('error', $upload['error_message']);
                        }
                        $storedAttachments[] = $upload['file_path'];
                    }
                }
            }

            $firstAttachmentType = null;
            if (count($storedAttachments)) {
                $ext = pathinfo($storedAttachments[0], PATHINFO_EXTENSION);
                $firstAttachmentType = strtolower($ext);
            }

            $notice->update([
                'category_id'      => $request->category_id,
                'title'            => $request->title,
                'body'             => $request->body,
                'publish_date'     => $request->publish_date,
                'attachments'      => count($storedAttachments) ? $storedAttachments : null,
                'attachment_type'  => $firstAttachmentType,
                'meta_title'       => $request->meta_title,
                'meta_tags'        => $request->meta_tags,
                'meta_description' => $request->meta_description,
                'is_featured'      => $request->is_featured ?? 0,
                'status'           => $request->status ?? 'published',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Notice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error while updating notice: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $notice = Notice::findOrFail($id);

        if (is_array($notice->attachments)) {
            foreach ($notice->attachments as $file) {
                if ($file) {
                    Storage::delete('public/' . $file);
                }
            }
        }

        $notice->delete();
        return response()->json(['success' => true]);
    }
}
