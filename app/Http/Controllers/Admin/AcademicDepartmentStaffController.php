<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSite;
use App\Models\AcademicDepartment;
use App\Models\AcademicStaffSection;
use App\Models\AcademicStaffMember;
use Illuminate\Http\Request;

class AcademicDepartmentStaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view academic departments')->only(['index']);
        $this->middleware('permission:create academic departments')->only(['storeDepartment']);
        $this->middleware('permission:edit academic departments')->only(['updateDepartment', 'sortDepartments']);
        $this->middleware('permission:delete academic departments')->only(['destroyDepartment']);

        $this->middleware('permission:view academic staff')->only(['index']);
        $this->middleware('permission:create academic staff')->only(['storeGroup', 'storeMember']);
        $this->middleware('permission:edit academic staff')->only(['updateGroup', 'updateMember', 'sortGroups', 'sortMembers']);
        $this->middleware('permission:delete academic staff')->only(['destroyGroup', 'destroyMember']);
    }

    public function index(Request $request)
    {
        $sites = AcademicSite::orderBy('name')->get();
        $siteId = $request->get('site_id', optional($sites->first())->id);

        $departments = collect();
        $selectedSite = null;

        if ($siteId) {
            $selectedSite = AcademicSite::find($siteId);
            $departments = AcademicDepartment::with(['staffSections.members'])
                ->where('academic_site_id', $siteId)
                ->orderBy('position')
                ->orderBy('id')
                ->get();
        }

        return view('admin.pages.academic.departments_staff', [
            'sites'        => $sites,
            'selectedSite' => $selectedSite,
            'departments'  => $departments,
        ]);
    }

    // ===== DEPARTMENTS =====
    public function storeDepartment(Request $request, AcademicSite $site)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'short_code' => 'nullable|string|max:50',
            'slug'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $maxPos = AcademicDepartment::where('academic_site_id', $site->id)->max('position') ?? 0;

        AcademicDepartment::create([
            'academic_site_id' => $site->id,
            'title'            => $data['title'],
            'short_code'       => $data['short_code'] ?? null,
            'slug'             => $data['slug'] ?? null,
            'description'      => $data['description'] ?? null,
            'position'         => $maxPos + 1,
            'is_active'        => true,
        ]);

        return redirect()->back()->with('success', 'Department created successfully.');
    }

    public function updateDepartment(Request $request, AcademicDepartment $department)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'short_code' => 'nullable|string|max:50',
            'slug'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active'  => 'nullable|boolean',
        ]);

        $department->update([
            'title'       => $data['title'],
            'short_code'  => $data['short_code'] ?? null,
            'slug'        => $data['slug'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->back()->with('success', 'Department updated successfully.');
    }

    public function destroyDepartment(AcademicDepartment $department)
    {
        $department->delete();
        return response()->json(['success' => true]);
    }

    public function sortDepartments(Request $request, AcademicSite $site)
    {
        $request->validate([
            'order' => 'required|array',
        ]);

        foreach ($request->order as $position => $id) {
            AcademicDepartment::where('id', $id)
                ->where('academic_site_id', $site->id)
                ->update(['position' => $position + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Department order updated.',
        ]);
    }

    // ===== STAFF SECTIONS (GROUPS) =====
    public function storeGroup(Request $request, AcademicDepartment $department)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $maxPos = AcademicStaffSection::where('academic_department_id', $department->id)->max('position') ?? 0;

        AcademicStaffSection::create([
            'academic_site_id'      => $department->academic_site_id,
            'academic_department_id' => $department->id,
            'title'                 => $data['title'],
            'position'              => $maxPos + 1,
        ]);

        return redirect()->back()->with('success', 'Staff group created.');
    }

    public function updateGroup(Request $request, AcademicStaffSection $group)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $group->update([
            'title' => $data['title'],
        ]);

        return redirect()->back()->with('success', 'Staff group updated.');
    }

    public function destroyGroup(AcademicStaffSection $group)
    {
        $group->delete();
        return response()->json(['success' => true]);
    }

    public function sortGroups(Request $request, AcademicDepartment $department)
    {
        $request->validate([
            'order' => 'required|array',
        ]);

        foreach ($request->order as $position => $id) {
            AcademicStaffSection::where('id', $id)
                ->where('academic_department_id', $department->id)
                ->update(['position' => $position + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Staff groups order updated.',
        ]);
    }

    // ===== STAFF MEMBERS =====
    public function storeMember(Request $request, AcademicStaffSection $group)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'links'       => 'nullable|array',
        ]);

        $maxPos = AcademicStaffMember::where('staff_section_id', $group->id)->max('position') ?? 0;

        AcademicStaffMember::create([
            'staff_section_id' => $group->id,
            'name'             => $data['name'],
            'designation'      => $data['designation'] ?? null,
            'email'            => $data['email'] ?? null,
            'phone'            => $data['phone'] ?? null,
            'position'         => $maxPos + 1,
            'links'            => $data['links'] ?? [],
        ]);

        return redirect()->back()->with('success', 'Staff member created.');
    }

    public function updateMember(Request $request, AcademicStaffMember $member)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'links'       => 'nullable|array',
        ]);

        $member->update([
            'name'        => $data['name'],
            'designation' => $data['designation'] ?? null,
            'email'       => $data['email'] ?? null,
            'phone'       => $data['phone'] ?? null,
            'links'       => $data['links'] ?? [],
        ]);

        return redirect()->back()->with('success', 'Staff member updated.');
    }

    public function destroyMember(AcademicStaffMember $member)
    {
        $member->delete();
        return response()->json(['success' => true]);
    }

    public function sortMembers(Request $request, AcademicStaffSection $group)
    {
        $request->validate([
            'order' => 'required|array',
        ]);

        foreach ($request->order as $position => $id) {
            AcademicStaffMember::where('id', $id)
                ->where('staff_section_id', $group->id)
                ->update(['position' => $position + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Staff members order updated.',
        ]);
    }
}
