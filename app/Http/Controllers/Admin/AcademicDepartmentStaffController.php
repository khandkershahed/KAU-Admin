<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSite;
use App\Models\AcademicDepartment;
use App\Models\AcademicStaffSection;
use App\Models\AcademicStaffMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AcademicDepartmentStaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view academic staff')->only(['index']);
        $this->middleware('permission:manage academic staff')->only([
            'storeDepartment','updateDepartment','destroyDepartment','sortDepartments',
            'storeGroup','updateGroup','destroyGroup','sortGroups',
            'storeMember','updateMember','destroyMember','sortMembers',
        ]);
    }

    // public function __construct()
    // {
    //     $this->middleware('permission:view academic departments')->only(['index']);
    //     $this->middleware('permission:create academic departments')->only(['storeDepartment']);
    //     $this->middleware('permission:edit academic departments')->only(['updateDepartment', 'sortDepartments']);
    //     $this->middleware('permission:delete academic departments')->only(['destroyDepartment']);

    //     $this->middleware('permission:view academic staff')->only(['index']);
    //     $this->middleware('permission:create academic staff')->only(['storeGroup', 'storeMember']);
    //     $this->middleware('permission:edit academic staff')->only(['updateGroup', 'updateMember', 'sortGroups', 'sortMembers']);
    //     $this->middleware('permission:delete academic staff')->only(['destroyGroup', 'destroyMember']);
    // }
    /* ==========================================================================
        INDEX
       ========================================================================== */

    public function index(Request $request)
    {
        $sites = AcademicSite::orderBy('name')->get();
        $siteId = $request->get('site_id', optional($sites->first())->id);

        $selectedSite = null;
        $departments = collect();

        if ($siteId) {
            $selectedSite = AcademicSite::find($siteId);

            if ($selectedSite) {
                $departments = AcademicDepartment::with(['staffSections.members'])
                    ->where('academic_site_id', $siteId)
                    ->orderBy('position')
                    ->get();
            }
        }

        return view('admin.pages.academic.departments_staff', [
            'sites'        => $sites,
            'selectedSite' => $selectedSite,
            'departments'  => $departments,
        ]);
    }

    /* ==========================================================================
        DEPARTMENTS
       ========================================================================== */

    public function storeDepartment(AcademicSite $site, Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'short_code'  => 'nullable|string|max:50',
            'slug'        => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:published,draft,archived',
            'position'    => 'nullable|integer',
        ]);

        AcademicDepartment::create([
            'academic_site_id' => $site->id,
            'title'            => $data['title'],
            'short_code'       => $data['short_code'] ?? null,
            'slug'             => $data['slug'] ?? null,
            'description'      => $data['description'] ?? null,
            'status'           => $data['status'] ?? 'published',
            'position'         => $data['position'] ?? 0,
        ]);

        return back()->with('success', 'Department created.');
    }

    public function updateDepartment(AcademicDepartment $department, Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'short_code'  => 'nullable|string|max:50',
            'slug'        => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:published,draft,archived',
        ]);

        $department->update([
            'title'       => $data['title'],
            'short_code'  => $data['short_code'] ?? null,
            'slug'        => $data['slug'] ?? null,
            'description' => $data['description'] ?? null,
            'status'      => $data['status'] ?? $department->status,
        ]);

        return back()->with('success', 'Department updated.');
    }

    public function destroyDepartment(AcademicDepartment $department)
    {
        $department->delete();

        return response()->json(['success' => true, 'message' => 'Department deleted.']);
    }

    public function sortDepartments(AcademicSite $site, Request $request)
    {
        $order = $request->get('order', []);

        foreach ($order as $index => $id) {
            AcademicDepartment::where('id', $id)
                ->where('academic_site_id', $site->id)
                ->update(['position' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Department order updated.']);
    }

    /* ==========================================================================
        STAFF SECTIONS (Groups)
       ========================================================================== */

    public function storeGroup(AcademicDepartment $department, Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'status'   => 'nullable|in:published,draft,archived',
            'position' => 'nullable|integer',
        ]);

        AcademicStaffSection::create([
            'academic_site_id'       => $department->academic_site_id,
            'academic_department_id' => $department->id,
            'title'                  => $data['title'],
            'status'                 => $data['status'] ?? 'published',
            'position'               => $data['position'] ?? 0,
        ]);

        return back()->with('success', 'Staff group created.');
    }

    public function updateGroup(AcademicStaffSection $group, Request $request)
    {
        $data = $request->validate([
            'title'  => 'required|string|max:255',
            'status' => 'nullable|in:published,draft,archived',
        ]);

        $group->update([
            'title'  => $data['title'],
            'status' => $data['status'] ?? $group->status,
        ]);

        return back()->with('success', 'Staff group updated.');
    }

    public function destroyGroup(AcademicStaffSection $group)
    {
        $group->delete();

        return response()->json(['success' => true, 'message' => 'Staff group deleted.']);
    }

    public function sortGroups(AcademicDepartment $department, Request $request)
    {
        $order = $request->get('order', []);

        foreach ($order as $index => $id) {
            AcademicStaffSection::where('id', $id)
                ->where('academic_department_id', $department->id)
                ->update(['position' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Staff group order updated.']);
    }

    /* ==========================================================================
        STAFF MEMBERS
       ========================================================================== */

    public function storeMember(AcademicStaffSection $group, Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'email'       => 'nullable|string|max:255',
            'phone'       => 'nullable|string|max:50',
            'status'      => 'nullable|in:published,draft,archived',
            'position'    => 'nullable|integer',
            'image'       => 'nullable|image|max:4096',

            'links'        => 'nullable|array',
            'links.*.icon' => 'nullable|string|max:255',
            'links.*.url'  => 'nullable|string|max:1000',
        ]);

        // image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('academic/staff', 'public');
        }

        AcademicStaffMember::create([
            'staff_section_id' => $group->id,
            'name'             => $data['name'],
            'designation'      => $data['designation'] ?? null,
            'email'            => $data['email'] ?? null,
            'phone'            => $data['phone'] ?? null,
            'image_path'       => $imagePath,
            'status'           => $data['status'] ?? 'published',
            'position'         => $data['position'] ?? 0,
            'links'            => $data['links'] ?? null,
        ]);

        return back()->with('success', 'Staff member created.');
    }

    public function updateMember(AcademicStaffMember $member, Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'email'       => 'nullable|string|max:255',
            'phone'       => 'nullable|string|max:50',
            'status'      => 'nullable|in:published,draft,archived',
            'position'    => 'nullable|integer',
            'image'       => 'nullable|image|max:4096',

            'links'        => 'nullable|array',
            'links.*.icon' => 'nullable|string|max:255',
            'links.*.url'  => 'nullable|string|max:1000',
        ]);

        $imagePath = $member->image_path;

        // Replace image
        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('academic/staff', 'public');
        }

        $member->update([
            'name'        => $data['name'],
            'designation' => $data['designation'] ?? null,
            'email'       => $data['email'] ?? null,
            'phone'       => $data['phone'] ?? null,
            'image_path'  => $imagePath,
            'status'      => $data['status'] ?? $member->status,
            'position'    => $data['position'] ?? $member->position,
            'links'       => $data['links'] ?? null,
        ]);

        return back()->with('success', 'Staff member updated.');
    }

    public function destroyMember(AcademicStaffMember $member)
    {
        if ($member->image_path) {
            Storage::disk('public')->delete($member->image_path);
        }

        $member->delete();

        return response()->json(['success' => true, 'message' => 'Staff member deleted.']);
    }

    public function sortMembers(AcademicStaffSection $group, Request $request)
    {
        $order = $request->get('order', []);

        foreach ($order as $index => $id) {
            AcademicStaffMember::where('id', $id)
                ->where('staff_section_id', $group->id)
                ->update(['position' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Staff member order updated.']);
    }
}
