<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminOffice;
use App\Models\AdminOfficeMember;
use App\Models\AdminOfficeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OfficeStaffController extends Controller
{

    public function __construct()
    {
        /* ============================
            SECTION PERMISSIONS
        ============================= */
        $this->middleware('permission:view admin section')->only(['officePage']);
        $this->middleware('permission:create admin section')->only(['sectionStore']);
        $this->middleware('permission:edit admin section')->only(['sectionUpdate', 'sectionSort']);
        $this->middleware('permission:delete admin section')->only(['sectionDelete']);

        /* ============================
            MEMBER PERMISSIONS
        ============================= */
        $this->middleware('permission:view admin member')->only(['officePage']);
        $this->middleware('permission:create admin member')->only(['memberStore']);
        $this->middleware('permission:edit admin member')->only(['memberUpdate', 'memberSort']);
        $this->middleware('permission:delete admin member')->only(['memberDelete']);
    }

    /* =====================================================
        PAGE 2 — SECTIONS + MEMBERS
    ====================================================== */
    public function officePage($slug)
    {
        $office = AdminOffice::where('slug', $slug)->firstOrFail();

        $sections = AdminOfficeSection::with([
            'members' => fn($q) => $q->orderBy('position')
        ])
            ->where('office_id', $office->id)
            ->orderBy('position')
            ->get();

        return view('admin.pages.administration.office', compact('office', 'sections'));
    }


    /* =====================================================
        SECTION CRUD
    ====================================================== */
    public function sectionStore(Request $request)
    {
        $request->validate([
            'office_id' => 'required|exists:admin_offices,id',
            'title'     => 'required|string|max:255'
        ]);

        $position = AdminOfficeSection::where('office_id', $request->office_id)->max('position') + 1;

        AdminOfficeSection::create([
            'office_id' => $request->office_id,
            'title'     => $request->title,
            'position'  => $position,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section added successfully.'
        ]);
    }


    public function sectionUpdate(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:admin_office_sections,id',
            'title' => 'required|string|max:255'
        ]);

        AdminOfficeSection::findOrFail($request->id)->update([
            'title' => $request->title,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section updated successfully.'
        ]);
    }


    public function sectionDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:admin_office_sections,id',
        ]);

        AdminOfficeSection::findOrFail($request->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Section deleted successfully.'
        ]);
    }


    public function sectionSort(Request $request)
    {
        foreach ($request->order as $pos => $id) {
            AdminOfficeSection::where('id', $id)->update(['position' => $pos + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Section order updated.'
        ]);
    }


    /* =====================================================
        MEMBER CRUD
    ====================================================== */
    public function memberStore(Request $request)
    {
        $request->validate([
            'office_id'  => 'required|exists:admin_offices,id',
            'section_id' => 'required|exists:admin_office_sections,id',
            'name'       => 'required|string|max:255',
            'image'      => 'nullable|image|max:2048'
        ]);

        $position = AdminOfficeMember::where('section_id', $request->section_id)->max('position') + 1;

        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('members', 'public');
        }

        AdminOfficeMember::create([
            'office_id'   => $request->office_id,
            'section_id'  => $request->section_id,
            'name'        => $request->name,
            'designation' => $request->designation,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'image'       => $image,
            'position'    => $position,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Member added successfully.'
        ]);
    }


    public function memberUpdate(Request $request)
    {
        $request->validate([
            'id'         => 'required|exists:admin_office_members,id',
            'name'       => 'required|string|max:255',
            'section_id' => 'required|exists:admin_office_sections,id',
            'image'      => 'nullable|image|max:2048'
        ]);

        $member = AdminOfficeMember::findOrFail($request->id);

        $image = $member->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('members', 'public');
        }

        $member->update([
            'name'        => $request->name,
            'designation' => $request->designation,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'section_id'  => $request->section_id,
            'image'       => $image,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Member updated successfully.'
        ]);
    }


    public function memberDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:admin_office_members,id',
        ]);

        AdminOfficeMember::findOrFail($request->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member deleted successfully.'
        ]);
    }


    public function memberSort(Request $request)
    {
        foreach ($request->order as $pos => $id) {
            AdminOfficeMember::where('id', $id)->update(['position' => $pos + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Member order updated.'
        ]);
    }
}
