<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view admission')->only(['index']);
        $this->middleware('permission:create admission')->only(['create', 'store']);
        $this->middleware('permission:edit admission')->only(['edit', 'update']);
        $this->middleware('permission:delete admission')->only(['destroy']);
    }

    /**
     * Show nested admission tree.
     */
    public function index()
    {
        $data = [
            // Only roots; children will be loaded lazily in the view (small tree).
            'roots' => Admission::whereNull('parent_id')
                ->orderBy('position', 'asc')
                ->get(),
        ];

        return view('admin.pages.admission.index', $data);
    }

    /**
     * Sort top-level admission groups (Undergraduate / Graduate / International…)
     */
    public function sortParents(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:admissions,id',
        ]);

        foreach ($request->order as $index => $id) {
            Admission::where('id', $id)->update([
                'position' => $index + 1,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Sort children inside ANY parent (works for nested levels).
     */
    public function sortChildren(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|integer|exists:admissions,id',
            'order'     => 'required|array',
            'order.*'   => 'integer|exists:admissions,id',
        ]);

        foreach ($request->order as $index => $id) {
            Admission::where('id', $id)->update([
                'position' => $index + 1,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function create()
    {
        $data = [
            'parents' => Admission::latest('id')->get(['id', 'title']),
        ];

        return view('admin.pages.admission.create', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'parent_id'    => 'nullable|exists:admissions,id',
                'title'        => 'required|string|max:255',
                'type'         => 'required|in:menu,page,external',
                'external_url' => 'nullable|required_if:type,external|url',
                'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
                'content'      => 'nullable|string',
                'position'     => 'nullable|integer',
                'status'       => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }

            // Auto-calc position if empty -> last + 1 among same parent
            // Auto-calc position if empty -> last + 1 among same parent
            if ($request->filled('position')) {
                $position = (int) $request->position;
            } else {
                if ($request->parent_id) {
                    // children
                    $maxPos = Admission::where('parent_id', $request->parent_id)->max('position');
                } else {
                    // ROOTS (parent_id = null)
                    $maxPos = Admission::whereNull('parent_id')->max('position');
                }

                $position = ($maxPos ?? 0) + 1;
            }


            // File upload (banner)
            $bannerImage = null;
            if ($request->hasFile('banner_image')) {
                $file   = $request->file('banner_image');
                $path   = 'admissions/banner';
                $upload = customUpload($file, $path); // your helper

                if ($upload['status'] === 0) {
                    return redirect()->back()->with('error', $upload['error_message']);
                }

                $bannerImage = $upload['file_path'];
            }

            Admission::create([
                'parent_id'        => $request->parent_id,
                'title'            => $request->title,
                'type'             => $request->type,
                'external_url'     => $request->type === 'external' ? $request->external_url : null,
                'banner_image'     => $bannerImage,
                'content'          => $request->content,
                'meta_title'       => $request->meta_title,
                'meta_tags'        => $request->meta_tags,
                'meta_description' => $request->meta_description,
                'position'         => $position,
                'status'           => $request->status ?? 1,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.admission.index')
                ->with('success', 'Admission item created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the admission item: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $data = [
            'admission' => Admission::findOrFail($id),
            'parents'   => Admission::where('id', '<>', $id)
                ->latest('id')
                ->get(['id', 'title']),
        ];

        return view('admin.pages.admission.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $admission = Admission::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'parent_id'    => 'nullable|exists:admissions,id',
                'title'        => 'required|string|max:255',
                'type'         => 'required|in:menu,page,external',
                'external_url' => 'nullable|required_if:type,external|url',
                'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
                'content'      => 'nullable|string',
                'position'     => 'nullable|integer',
                'status'       => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }

            // Position: keep old unless explicitly changed
            if ($request->filled('position')) {
                $position = (int) $request->position;
            } else {
                $position = $admission->position;
            }

            // banner update
            $bannerImage = $admission->banner_image;
            if ($request->hasFile('banner_image')) {
                if ($bannerImage) {
                    Storage::delete('public/' . $bannerImage);
                }
                $file   = $request->file('banner_image');
                $path   = 'admissions/banner';
                $upload = customUpload($file, $path);
                if ($upload['status'] === 0) {
                    return redirect()->back()->with('error', $upload['error_message']);
                }
                $bannerImage = $upload['file_path'];
            }

            $admission->update([
                'parent_id'        => $request->parent_id,
                'title'            => $request->title,
                'type'             => $request->type,
                'external_url'     => $request->type === 'external' ? $request->external_url : null,
                'banner_image'     => $bannerImage,
                'content'          => $request->content,
                'meta_title'       => $request->meta_title,
                'meta_tags'        => $request->meta_tags,
                'meta_description' => $request->meta_description,
                'position'         => $position,
                'status'           => $request->status ?? 1,
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Admission item updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the admission item: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $admission = Admission::findOrFail($id);

        if ($admission->banner_image) {
            Storage::delete('public/' . $admission->banner_image);
        }

        // If it has children, Eloquent will keep them; you can decide whether to re-parent or cascade.
        $admission->delete();

        // return response()->json(['success' => true]);
    }
}
