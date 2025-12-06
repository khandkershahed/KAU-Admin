<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminGroup;
use App\Models\AdminOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdministrationController extends Controller
{
    public function __construct()
    {
        /* ============================
           GROUP PERMISSIONS
        ============================= */
        $this->middleware('permission:view admin group')->only(['index']);
        $this->middleware('permission:create admin group')->only(['groupStore']);
        $this->middleware('permission:edit admin group')->only(['groupUpdate', 'groupSort']);
        $this->middleware('permission:delete admin group')->only(['groupDelete']);

        /* ============================
           OFFICE PERMISSIONS
        ============================= */
        $this->middleware('permission:view admin office')->only(['index']);
        $this->middleware('permission:create admin office')->only(['officeStore']);
        $this->middleware('permission:edit admin office')->only(['officeUpdate', 'officeSort']);
        $this->middleware('permission:delete admin office')->only(['officeDelete']);
    }



    /* =====================================================
        PAGE 1 — GROUPS + OFFICES
    ====================================================== */
    public function index()
    {
        $groups = AdminGroup::with([
            'offices' => fn($q) => $q->orderBy('position')
        ])
        ->orderBy('position')
        ->get();

        return view('admin.pages.administration.index', compact('groups'));
    }



    /* =====================================================
        GROUP STORE
    ====================================================== */
    public function groupStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255'
        ], [
            'name.required' => 'Group name is required.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $msg) {
                Session::flash('error', $msg);
            }
            return redirect()->back()->withInput();
        }

        try {
            DB::beginTransaction();

            $pos = AdminGroup::max('position') + 1;

            AdminGroup::create([
                'name'      => $request->name,
                'position'  => $pos,
            ]);

            DB::commit();
            Session::flash('success', 'Group created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Failed to create group: '.$e->getMessage());
        }

        return redirect()->back();
    }



    /* =====================================================
        GROUP UPDATE
    ====================================================== */
    public function groupUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:admin_groups,id',
            'name'  => 'required|string|max:255',
        ], [
            'name.required' => 'Group name is required.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $msg) {
                Session::flash('error', $msg);
            }
            return redirect()->back()->withInput();
        }

        try {
            DB::beginTransaction();

            AdminGroup::findOrFail($request->id)->update([
                'name'  => $request->name,
            ]);

            DB::commit();
            Session::flash('success', 'Group updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Failed to update group: '.$e->getMessage());
        }

        return redirect()->back();
    }



    /* =====================================================
        GROUP DELETE  (DELETE METHOD)
    ====================================================== */
    public function groupDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:admin_groups,id'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $msg)
                Session::flash('error', $msg);

            return response()->json(['error' => true], 422);
        }

        try {
            DB::beginTransaction();

            $group = AdminGroup::findOrFail($request->id);
            $group->delete();

            DB::commit();

            return response()->json(['success' => true]);
        }
        catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed: '.$e->getMessage()
            ], 500);
        }
    }



    /* =====================================================
        GROUP SORT (AJAX)
    ====================================================== */
    public function groupSort(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->order as $pos => $id) {
                AdminGroup::where('id', $id)->update([
                    'position' => $pos + 1
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Group order updated.'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed: '.$e->getMessage()
            ], 500);
        }
    }



    /* =====================================================
        OFFICE STORE
    ====================================================== */
    public function officeStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id'        => 'required|exists:admin_groups,id',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'meta_title'      => 'nullable|string|max:255',
            'meta_tags'       => 'nullable|string|max:255',
            'meta_description'=> 'nullable|string',
        ], [
            'group_id.required' => 'Please select a group.',
            'title.required'    => 'Office title is required.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $msg)
                Session::flash('error', $msg);
            return redirect()->back()->withInput();
        }

        try {
            DB::beginTransaction();

            $position = AdminOffice::where('group_id', $request->group_id)->max('position') + 1;

            AdminOffice::create([
                'group_id'        => $request->group_id,
                'title'           => $request->title,
                'description'     => $request->description,
                'meta_title'      => $request->meta_title,
                'meta_tags'       => $request->meta_tags,
                'meta_description'=> $request->meta_description,
                'position'        => $position,
            ]);

            DB::commit();
            Session::flash('success', 'Office created successfully.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Failed to create office: '.$e->getMessage());
        }

        return redirect()->back();
    }



    /* =====================================================
        OFFICE UPDATE
    ====================================================== */
    public function officeUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'              => 'required|exists:admin_offices,id',
            'group_id'        => 'required|exists:admin_groups,id',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'meta_title'      => 'nullable|string|max:255',
            'meta_tags'       => 'nullable|string|max:255',
            'meta_description'=> 'nullable|string',
        ], [
            'title.required' => 'Office title is required.'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $msg)
                Session::flash('error', $msg);
            return redirect()->back()->withInput();
        }

        try {
            DB::beginTransaction();

            AdminOffice::findOrFail($request->id)->update([
                'group_id'        => $request->group_id,
                'title'           => $request->title,
                'description'     => $request->description,
                'meta_title'      => $request->meta_title,
                'meta_tags'       => $request->meta_tags,
                'meta_description'=> $request->meta_description,
            ]);

            DB::commit();
            Session::flash('success', 'Office updated successfully.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Failed to update office: '.$e->getMessage());
        }

        return redirect()->back();
    }



    /* =====================================================
        OFFICE DELETE (DELETE METHOD)
    ====================================================== */
    public function officeDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:admin_offices,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $office = AdminOffice::findOrFail($request->id);
            $office->delete();

            DB::commit();

            return response()->json(['success' => true]);
        }
        catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed: '.$e->getMessage()
            ], 500);
        }
    }



    /* =====================================================
        OFFICE SORT (AJAX)
    ====================================================== */
    public function officeSort(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->order as $pos => $id) {
                AdminOffice::where('id', $id)->update([
                    'position' => $pos + 1
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Office order updated.'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed: '.$e->getMessage()
            ], 500);
        }
    }
}
