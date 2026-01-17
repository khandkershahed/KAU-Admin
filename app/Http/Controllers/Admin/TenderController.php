<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TenderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view tenders')->only(['index']);
        $this->middleware('permission:create tenders')->only(['create', 'store']);
        $this->middleware('permission:edit tenders')->only(['edit', 'update']);
        $this->middleware('permission:delete tenders')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Tender::query()
            ->orderByDesc('publish_date')
            ->orderByDesc('id');

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('reference_no', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['draft', 'published', 'archived'], true)) {
            $query->where('status', $request->status);
        }

        $tenders = $query->paginate(20);

        if ($request->ajax()) {
            return view('admin.pages.tenders.partials.table', [
                'tenders' => $tenders,
                'search'  => $request->get('q'),
                'status'  => $request->get('status'),
            ])->render();
        }

        return view('admin.pages.tenders.index', [
            'tenders' => $tenders,
            'search'  => $request->get('q'),
            'status'  => $request->get('status'),
        ]);
    }

    public function create()
    {
        return view('admin.pages.tenders.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'title'             => 'required|string|max:255',
                'excerpt'           => 'nullable|string',
                'body'              => 'nullable|string',
                'publish_date'      => 'nullable|date',
                'closing_date'      => 'nullable|date|after_or_equal:publish_date',
                'reference_no'      => 'nullable|string|max:255',
                'department'        => 'nullable|string|max:255',
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

            $storedAttachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file) {
                        $upload = customUpload($file, 'tenders/attachments');
                        if ($upload['status'] === 0) {
                            return redirect()->back()->withInput()->with('error', $upload['error_message']);
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

            Tender::create([
                'title'            => $request->title,
                'excerpt'          => $request->excerpt,
                'body'             => $request->body,
                'publish_date'     => $request->publish_date,
                'closing_date'     => $request->closing_date,
                'reference_no'     => $request->reference_no,
                'department'       => $request->department,
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

            return redirect()->route('admin.tenders.index')->with('success', 'Tender created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error while creating tender: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $tender = Tender::findOrFail($id);
        return view('admin.pages.tenders.edit', compact('tender'));
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $tender = Tender::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title'             => 'required|string|max:255',
                'excerpt'           => 'nullable|string',
                'body'              => 'nullable|string',
                'publish_date'      => 'nullable|date',
                'closing_date'      => 'nullable|date|after_or_equal:publish_date',
                'reference_no'      => 'nullable|string|max:255',
                'department'        => 'nullable|string|max:255',
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

            $storedAttachments = $tender->attachments ?? [];
            if ($request->hasFile('attachments')) {
                if (is_array($storedAttachments)) {
                    foreach ($storedAttachments as $oldFile) {
                        if ($oldFile) {
                            Storage::disk('public')->delete($oldFile);
                        }
                    }
                }

                $storedAttachments = [];

                foreach ($request->file('attachments') as $file) {
                    if ($file) {
                        $upload = customUpload($file, 'tenders/attachments');
                        if ($upload['status'] === 0) {
                            return redirect()->back()->withInput()->with('error', $upload['error_message']);
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

            $tender->update([
                'title'            => $request->title,
                'excerpt'          => $request->excerpt,
                'body'             => $request->body,
                'publish_date'     => $request->publish_date,
                'closing_date'     => $request->closing_date,
                'reference_no'     => $request->reference_no,
                'department'       => $request->department,
                'attachments'      => count($storedAttachments) ? $storedAttachments : null,
                'attachment_type'  => $firstAttachmentType,
                'meta_title'       => $request->meta_title,
                'meta_tags'        => $request->meta_tags,
                'meta_description' => $request->meta_description,
                'is_featured'      => $request->is_featured ?? 0,
                'status'           => $request->status ?? 'published',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Tender updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error while updating tender: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $tender = Tender::findOrFail($id);

        if (is_array($tender->attachments)) {
            foreach ($tender->attachments as $file) {
                if ($file) {
                    Storage::disk('public')->delete($file);
                }
            }
        }

        $tender->delete();

        return response()->json(['success' => true]);
    }

    public function toggleFeatured(Tender $tender, Request $request)
    {
        $request->validate(['is_featured' => 'required|boolean']);
        $tender->is_featured = $request->boolean('is_featured');
        $tender->save();

        return response()->json(['success' => true, 'message' => 'Featured status updated.']);
    }

    public function toggleStatus(Tender $tender, Request $request)
    {
        $request->validate(['status' => 'required|in:draft,published,archived']);
        $tender->status = $request->status;
        $tender->save();

        return response()->json(['success' => true, 'message' => 'Status updated.']);
    }
}
