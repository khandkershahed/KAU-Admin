<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSite;
use App\Models\AcademicDepartment;
use App\Models\AcademicStaffSection;
use App\Models\AcademicStaffMember;
use App\Models\AcademicMemberPublication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AcademicDepartmentStaffController extends Controller
{
    public function __construct()
    {
        // Departments
        $this->middleware('permission:view academic departments')->only(['index']);
        $this->middleware('permission:create academic departments')->only(['storeDepartment']);
        $this->middleware('permission:edit academic departments')->only([
            'updateDepartment',
            'sortDepartments',
            'toggleDepartmentStatus',
        ]);
        $this->middleware('permission:delete academic departments')->only(['destroyDepartment']);

        // Staff (groups + members)
        $this->middleware('permission:view academic staff')->only(['index']);
        $this->middleware('permission:create academic staff')->only([
            'storeGroup',
            'storeMember',
            'storePublication',
        ]);
        $this->middleware('permission:edit academic staff')->only([
            'updateGroup',
            'updateMember',
            'sortGroups',
            'sortMembers',
            'updatePublication',
            'sortPublications',
        ]);
        $this->middleware('permission:delete academic staff')->only([
            'destroyGroup',
            'destroyMember',
            'destroyPublication',
        ]);
    }

    /* =========================================================================
        INDEX
       ========================================================================= */

    public function index(Request $request)
    {
        $sites  = AcademicSite::orderBy('position')->get();
        $siteId = $request->get('site_id', optional($sites->first())->id);

        $selectedSite = null;
        $departments  = collect();

        if ($siteId) {
            $selectedSite = AcademicSite::find($siteId);

            if ($selectedSite) {
                $departments = AcademicDepartment::where('academic_site_id', $siteId)
                    ->orderBy('position')
                    ->get();
            }
        }

        // AJAX: load right panel for a specific department
        if ($request->ajax() && $request->filled('department_id')) {
            $department = AcademicDepartment::with([
                'staffSections.members',
            ])->findOrFail($request->department_id);

            $html = view('admin.pages.academic.partials.department_details', [
                'department' => $department,
            ])->render();

            return response()->json(['html' => $html]);
        }

        return view('admin.pages.academic.departments_staff', [
            'sites'        => $sites,
            'selectedSite' => $selectedSite,
            'departments'  => $departments,
        ]);
    }

    /* =========================================================================
        DEPARTMENTS
       ========================================================================= */
    private array $ignoredWords = [
        'and',
        'or',
        'of',
        'the',
        'for',
        'to',
        'in',
        'on',
        'with'
    ];
    private function generateUniqueShortCode(string $title, $ignoreId = null)
    {
        $words = collect(explode(' ', trim($title)))
            ->filter()
            ->reject(fn($word) => in_array(strtolower($word), $this->ignoredWords))
            ->values();

        if ($words->isEmpty()) {
            return strtoupper(Str::random(3));
        }

        $maxLength = max(array_map('strlen', $words->toArray()));

        // Increase letter depth progressively
        for ($depth = 1; $depth <= $maxLength; $depth++) {
            $code = '';

            foreach ($words as $word) {
                $length = min($depth, strlen($word));
                $part = substr($word, 0, $length);
                $code .= ucfirst(strtolower($part));
            }

            $exists = AcademicDepartment::where('short_code', $code)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists();

            if (! $exists) {
                return $code;
            }
        }

        // Final fallback
        return strtoupper(Str::random(5));
    }

    public function storeDepartment(Request $request)
    {
        $data = $request->validate([
            'academic_site_id' => 'required|exists:academic_sites,id',
            'title'            => 'required|string|max:255',
            'short_code'       => 'nullable|string|max:50',
            'slug'             => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'status'           => 'nullable|in:published,draft,archived',
            'position'         => 'nullable|integer',
        ]);

        $shortCode = $data['short_code']
            ?? $this->generateUniqueShortCode($data['title']);

        AcademicDepartment::create([
            'academic_site_id' => $data['academic_site_id'],
            'title'            => $data['title'],
            'short_code'       => $shortCode,
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
            'position'    => 'nullable|integer',
        ]);

        $shortCode = $data['short_code']
            ?? $this->generateUniqueShortCode($data['title'], $department->id);

        $department->update([
            'title'       => $data['title'],
            'short_code'  => $shortCode,
            'slug'        => $data['slug'] ?? null,
            'description' => $data['description'] ?? null,
            'status'      => $data['status'] ?? $department->status,
            'position'    => $data['position'] ?? $department->position,
        ]);

        return back()->with('success', 'Department updated.');
    }


    public function destroyDepartment(AcademicDepartment $department)
    {
        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted.',
        ]);
    }

    public function sortDepartments(AcademicSite $site, Request $request)
    {
        $order = $request->get('order', []);

        foreach ($order as $index => $id) {
            AcademicDepartment::where('id', $id)
                ->where('academic_site_id', $site->id)
                ->update(['position' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Department order updated.',
        ]);
    }

    public function toggleDepartmentStatus(AcademicDepartment $department)
    {
        $department->status = $department->status === 'published' ? 'draft' : 'published';
        $department->save();

        return response()->json([
            'success' => true,
            'status'  => $department->status,
            'message' => 'Department status updated.',
        ]);
    }

    /* =========================================================================
        STAFF SECTIONS (GROUPS)
       ========================================================================= */

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

        return response()->json([
            'success' => true,
            'message' => 'Staff group deleted.',
        ]);
    }

    public function sortGroups(AcademicDepartment $department, Request $request)
    {
        $order = $request->get('order', []);

        foreach ($order as $index => $id) {
            AcademicStaffSection::where('id', $id)
                ->where('academic_department_id', $department->id)
                ->update(['position' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Staff group order updated.',
        ]);
    }

    /* =========================================================================
        UUID GENERATOR (slug-based, with department short_code + numeric suffix)
       ========================================================================= */

    private function generateMemberUuid(string $name, ?string $departmentShortCode = null): string
    {
        $base = Str::slug($name);

        if ($base === '') {
            $base = 'faculty';
        }

        // 1) Try name slug first
        $uuid = $base;
        if (!AcademicStaffMember::where('uuid', $uuid)->exists()) {
            return $uuid;
        }

        // 2) If not unique, try with department short_code (lowercase)
        $dept = null;
        if ($departmentShortCode) {
            $dept = Str::of($departmentShortCode)
                ->lower()
                ->ascii()
                ->replaceMatches('/[^a-z0-9]+/', '')
                ->toString();
        }

        if ($dept) {
            $uuid = $base . '-' . $dept;

            if (!AcademicStaffMember::where('uuid', $uuid)->exists()) {
                return $uuid;
            }
        } else {
            $uuid = $base;
        }

        // 3) If still not unique, add numeric suffix
        $i = 1;
        while (AcademicStaffMember::where('uuid', $uuid . '-' . $i)->exists()) {
            $i++;
        }

        return $uuid . '-' . $i;
    }

    /* =========================================================================
        STAFF MEMBERS
       ========================================================================= */

    public function storeMember(AcademicStaffSection $group, Request $request)
    {
        $data = $request->validate(
            [
                'name'        => 'required|string|max:255',
                'designation' => 'nullable|string|max:255',
                'email'       => 'nullable|email|max:255',
                'phone'       => 'nullable|string|max:50',
                'mobile'      => 'nullable|string|max:20',
                'address'     => 'nullable',
                'research_interest' => 'nullable',
                'bio'         => 'nullable',
                'education'   => 'nullable',
                'experience'  => 'nullable',
                'scholarship' => 'nullable',
                'research'    => 'nullable',
                'teaching'    => 'nullable',

                'status'      => 'nullable|in:published,draft,archived',
                'position'    => 'nullable|integer',
                'image'       => 'nullable|image|max:4096',

                'links'        => 'nullable|array',
                'links.*.icon' => 'nullable|string|max:255',
                'links.*.url'  => 'nullable|url|max:1000',
            ],
            [
                'name.required'  => 'Staff member name is required.',
                'name.max'       => 'Name cannot exceed 255 characters.',
                'email.email'    => 'Please enter a valid email address.',
                'image.image'    => 'Uploaded file must be an image.',
                'image.max'      => 'Image size must not exceed 4MB.',
                'status.in'      => 'Invalid status selected.',
                'links.array'    => 'Links must be a valid list.',
                'links.*.url'    => 'Each link must be a valid URL.',
            ]
        );

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('academic/staff', 'public');
        }

        $departmentShortCode = AcademicDepartment::where(
            'id',
            $group->academic_department_id
        )->value('short_code');

        $uuid = $this->generateMemberUuid(
            $data['name'],
            $departmentShortCode
        );

        AcademicStaffMember::create([
            'staff_section_id' => $group->id,
            'uuid'             => $uuid,

            'name'             => $data['name'],
            'designation'      => $data['designation'] ?? null,
            'email'            => $data['email'] ?? null,
            'phone'            => $data['phone'] ?? null,
            'mobile'           => $data['mobile'] ?? null,
            'address'          => $data['address'] ?? null,
            'research_interest' => $data['research_interest'] ?? null,
            'bio'              => $data['bio'] ?? null,
            'education'        => $data['education'] ?? null,
            'experience'       => $data['experience'] ?? null,
            'scholarship'      => $data['scholarship'] ?? null,
            'research'         => $data['research'] ?? null,
            'teaching'         => $data['teaching'] ?? null,

            'image_path'       => $imagePath,
            'status'           => $data['status'] ?? 'published',
            'position'         => $data['position'] ?? 0,
            'links'            => $data['links'] ?? null,
        ]);

        return back()->with('success', 'Staff member created successfully.');
    }


    public function updateMember(AcademicStaffMember $member, Request $request)
    {

        $data = $request->validate(
            [
                'name'        => 'required|string|max:255',
                'designation' => 'nullable|string|max:255',
                'email'       => 'nullable|email|max:255',
                'phone'       => 'nullable|string|max:50',
                'mobile'      => 'nullable|string|max:20',
                'address'     => 'nullable',
                'research_interest' => 'nullable',
                'bio'         => 'nullable',
                'education'   => 'nullable',
                'experience'  => 'nullable',
                'scholarship' => 'nullable',
                'research'    => 'nullable',
                'teaching'    => 'nullable',

                'status'      => 'nullable|in:published,draft,archived',
                'position'    => 'nullable|integer',
                'image'       => 'nullable|image|max:4096',

                'links'        => 'nullable|array',
                'links.*.icon' => 'nullable|string|max:255',
                'links.*.url'  => 'nullable|url|max:1000',
            ],
            [
                'name.required'  => 'Staff member name is required.',
                'email.email'    => 'Please enter a valid email address.',
                'image.image'    => 'Uploaded file must be an image.',
                'image.max'      => 'Image size must not exceed 4MB.',
                'status.in'      => 'Invalid status selected.',
                'links.*.url'    => 'Each link must be a valid URL.',
            ]
        );

        $imagePath = $member->image_path;

        if ((int) $request->input('image_remove', 0) === 1 && $imagePath) {
            Storage::disk('public')->delete($imagePath);
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('academic/staff', 'public');
        }

        // Regenerate UUID if name changed or missing
        $uuid = $member->uuid;
        if (!$uuid || $data['name'] !== $member->name) {
            $section = AcademicStaffSection::find($member->staff_section_id);
            $departmentShortCode = null;

            if ($section) {
                $departmentShortCode = AcademicDepartment::where(
                    'id',
                    $section->academic_department_id
                )->value('short_code');
            }

            $uuid = $this->generateMemberUuid(
                $data['name'],
                $departmentShortCode
            );
        }
        $member->update([
            'uuid'             => $uuid,

            'name'              => $data['name'],
            'designation'       => $data['designation'] ?? null,
            'email'             => $data['email'] ?? null,
            'phone'             => $data['phone'] ?? null,
            'mobile'            => $data['mobile'] ?? null,
            'address'           => $data['address'] ?? null,
            'research_interest' => $request->research_interest,
            'bio'               => $request->bio,
            'education'         => $request->education,
            'experience'        => $request->experience,
            'scholarship'       => $request->scholarship,
            'research'          => $request->research,
            'teaching'          => $request->teaching,

            'image_path'        => $imagePath,
            'status'            => $data['status'] ?? $member->status,
            'position'          => $data['position'] ?? $member->position,
            'links'             => $data['links'] ?? null,
        ]);

        return back()->with('success', 'Staff member updated successfully.');
    }



    public function destroyMember(AcademicStaffMember $member)
    {
        if ($member->image_path) {
            Storage::disk('public')->delete($member->image_path);
        }

        $member->delete();

        return response()->json([
            'success' => true,
            'message' => 'Staff member deleted.',
        ]);
    }

    public function sortMembers(AcademicStaffSection $group, Request $request)
    {
        $order = $request->get('order', []);

        foreach ($order as $index => $id) {
            AcademicStaffMember::where('id', $id)
                ->where('staff_section_id', $group->id)
                ->update(['position' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Staff member order updated.',
        ]);
    }

    /* =========================================================================
        PUBLICATIONS (MODAL CRUD)
       ========================================================================= */

    public function publicationsList(AcademicStaffMember $member)
    {
        $member->load(['publications']);

        $html = view('admin.pages.academic.partials.publications_list', [
            'member' => $member,
        ])->render();

        return response()->json(['html' => $html]);
    }

    public function storePublication(AcademicStaffMember $member, Request $request)
    {
        $data = $request->validate([
            'title'  => 'required|string|max:500',
            'type'   => 'nullable|in:journal,conference',
            'journal_or_conference_name' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'year'   => 'nullable|integer|min:1900|max:2100',
            'doi'    => 'nullable|string|max:255',
            'url'    => 'nullable|string|max:1000',
            'position' => 'nullable|integer',
        ]);

        $member->publications()->create([
            'title'  => $data['title'],
            'type'   => $data['type'] ?? null,
            'journal_or_conference_name' => $data['journal_or_conference_name'] ?? null,
            'publisher' => $data['publisher'] ?? null,
            'year'   => $data['year'] ?? null,
            'doi'    => $data['doi'] ?? null,
            'url'    => $data['url'] ?? null,
            'position' => $data['position'] ?? 0,
        ]);

        return back()->with('success', 'Publication added.');
    }

    public function updatePublication(AcademicMemberPublication $publication, Request $request)
    {
        $data = $request->validate([
            'title'  => 'required|string|max:500',
            'type'   => 'nullable|in:journal,conference',
            'journal_or_conference_name' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'year'   => 'nullable|integer|min:1900|max:2100',
            'doi'    => 'nullable|string|max:255',
            'url'    => 'nullable|string|max:1000',
            'position' => 'nullable|integer',
        ]);

        $publication->update([
            'title'  => $data['title'],
            'type'   => $data['type'] ?? null,
            'journal_or_conference_name' => $data['journal_or_conference_name'] ?? null,
            'publisher' => $data['publisher'] ?? null,
            'year'   => $data['year'] ?? null,
            'doi'    => $data['doi'] ?? null,
            'url'    => $data['url'] ?? null,
            'position' => $data['position'] ?? $publication->position,
        ]);

        return back()->with('success', 'Publication updated.');
    }

    public function destroyPublication(AcademicMemberPublication $publication)
    {
        $publication->delete();

        return response()->json([
            'success' => true,
            'message' => 'Publication deleted.',
        ]);
    }

    public function sortPublications(AcademicStaffMember $member, Request $request)
    {
        $order = $request->get('order', []);

        foreach ($order as $index => $id) {
            AcademicMemberPublication::where('id', $id)
                ->where('academic_staff_member_id', $member->id)
                ->update(['position' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Publication order updated.',
        ]);
    }
}
