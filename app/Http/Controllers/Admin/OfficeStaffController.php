<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminOffice;
use App\Models\AdminOfficeMember;
use App\Models\AdminOfficeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OfficeStaffController extends Controller
{
    public function __construct()
    {
        /* SECTION PERMISSIONS */
        $this->middleware('permission:view admin section')->only(['officePage']);
        $this->middleware('permission:create admin section')->only(['sectionStore']);
        $this->middleware('permission:edit admin section')->only(['sectionUpdate', 'sectionSort']);
        $this->middleware('permission:delete admin section')->only(['sectionDelete']);

        /* MEMBER PERMISSIONS */
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
        SECTION PAGES (NO MODALS)
    ====================================================== */
    public function sectionCreate($slug)
    {
        $office = AdminOffice::where('slug', $slug)->firstOrFail();

        return view('admin.pages.administration.forms.section_create', compact('office'));
    }

    public function sectionEdit($slug, $id)
    {
        $office = AdminOffice::where('slug', $slug)->firstOrFail();
        $section = AdminOfficeSection::where('id', $id)->where('office_id', $office->id)->firstOrFail();

        return view('admin.pages.administration.forms.section_edit', compact('office', 'section'));
    }

    /* =====================================================
        MEMBER PAGES (NO MODALS)
    ====================================================== */
    public function memberCreate($slug, $sectionId)
    {
        $office = AdminOffice::where('slug', $slug)->firstOrFail();
        $section = AdminOfficeSection::where('id', $sectionId)->where('office_id', $office->id)->firstOrFail();

        return view('admin.pages.administration.forms.member_create', compact('office', 'section'));
    }

    public function memberEdit($slug, $id)
    {
        $office = AdminOffice::where('slug', $slug)->firstOrFail();
        $member = AdminOfficeMember::where('id', $id)->where('office_id', $office->id)->firstOrFail();
        $section = AdminOfficeSection::where('id', $member->section_id)->where('office_id', $office->id)->firstOrFail();

        return view('admin.pages.administration.forms.member_edit', compact('office', 'section', 'member'));
    }


    /* =====================================================
        SECTION CRUD
    ====================================================== */
    public function sectionStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'office_id'     => 'required|exists:admin_offices,id',
            'title'         => 'required|string|max:255',
            'section_type'  => 'nullable|string|max:100',
            'content'       => 'nullable',
            'tab_label'     => 'nullable|string|max:255',
            'tab_subtitle'  => 'nullable|string|max:255',
            'tab_icon'      => 'nullable|string|max:255',
            'tab_color'     => 'nullable|string|max:100',
            'extra_json'    => 'nullable'
        ], [
            'office_id.required' => 'Office reference missing.',
            'title.required'     => 'Section title is required.'
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $msg) {
                Session::flash('error', $msg);
            }
            return redirect()->back()->withInput();
        }

        try {
            DB::beginTransaction();

            $position = AdminOfficeSection::where('office_id', $request->office_id)->max('position') + 1;

            AdminOfficeSection::create([
                'office_id'    => $request->office_id,
                'title'        => $request->title,
                'section_type' => $request->section_type ?? 'content',
                'content'      => $request->content,
                'extra'        => $this->buildSectionExtra($request),
                'position'     => $position,
            ]);

            DB::commit();
            Session::flash('success', 'Section added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Something went wrong while creating section.');
        }

        return redirect()->back();
    }

    public function sectionUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'           => 'required|exists:admin_office_sections,id',
            'title'         => 'required|string|max:255',
            'section_type'  => 'nullable|string|max:100',
            'content'       => 'nullable',
            'tab_label'     => 'nullable|string|max:255',
            'tab_subtitle'  => 'nullable|string|max:255',
            'tab_icon'      => 'nullable|string|max:255',
            'tab_color'     => 'nullable|string|max:100',
            'extra_json'    => 'nullable'
        ], [

            'title.required' => 'Section title is required.',

        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $msg) {
                Session::flash('error', $msg);
            }
            return redirect()->back()->withInput();
        }

        try {
            DB::beginTransaction();

            AdminOfficeSection::findOrFail($request->id)->update([
                'title'        => $request->title,
                'section_type' => $request->section_type ?? 'content',
                'content'      => $request->content,
                'status'       => $request->status ?? 1,
                'extra'        => $this->buildSectionExtra($request),
            ]);

            DB::commit();
            Session::flash('success', 'Section updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Failed to update section.');
        }

        return redirect()->back();
    }

    public function sectionDelete(string $id)
    {
        AdminOfficeSection::findOrFail($id)->delete();
    }

    public function sectionSort(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->order as $pos => $id) {
                AdminOfficeSection::where('id', $id)->update(['position' => $pos + 1]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false], 500);
        }
    }

    /* =====================================================
        MEMBER CRUD
    ====================================================== */
    public function memberStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'office_id'  => 'required|exists:admin_offices,id',
            'section_id' => 'required|exists:admin_office_sections,id',
            'image'      => 'nullable|image|max:2048',
            'name'       => 'required|string|max:255',
            'designation'=> 'nullable|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:255',
            'label'      => 'nullable|string|max:255',
            'type'       => 'nullable|string|max:50'
        ], [

            'name.required' => 'Member name is required.',
            'image.image'   => 'Image must be a valid file.',
            'name.max'      => 'Name may not be greater than 255 characters.',
            'label.max'     => 'Label may not be greater than 250 characters.'

        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $msg) {
                Session::flash('error', $msg);
            }
            return redirect()->back()->withInput();
        }

        try {
            DB::beginTransaction();

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
                'label'       => $request->label,
                'image'       => $image,
                'position'    => $position,
            ]);

            DB::commit();
            Session::flash('success', 'Member added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Failed to add member.');
        }

        return redirect()->back();
    }

    public function memberUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'        => 'required|exists:admin_office_members,id',
            'office_id'  => 'required|exists:admin_offices,id',
            'section_id' => 'required|exists:admin_office_sections,id',
            'image'      => 'nullable|image|max:2048',
            'name'       => 'required|string|max:255',
            'designation'=> 'nullable|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:255',
            'label'      => 'nullable|string|max:255',
            'type'       => 'nullable|string|max:50'
        ], [

            'name.required' => 'Member name is required.',
            'image.image'   => 'Uploaded file must be an image.'

        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $msg) {
                Session::flash('error', $msg);
            }
            return redirect()->back()->withInput();
        }

        try {
            DB::beginTransaction();

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
                'label'      => $request->label,
                'type'       => $request->type,
                'image'       => $image,
            ]);

            DB::commit();
            Session::flash('success', 'Member updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Failed to update member.');
        }

        return redirect()->back();
    }

    public function memberDelete(string $id)
    {

        AdminOfficeMember::findOrFail($id)->delete();
    }

    public function memberSort(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->order as $pos => $id) {
                AdminOfficeMember::where('id', $id)->update(['position' => $pos + 1]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false], 500);
        }
    }


    /* =====================================================
        HELPERS
    ====================================================== */
    private function buildSectionExtra(Request $request): ?array
    {
        $extra = [];

        if ($request->filled('tab_label')) {
            $extra['tab_label'] = $request->tab_label;
        }
        if ($request->filled('tab_subtitle')) {
            $extra['tab_subtitle'] = $request->tab_subtitle;
        }
        if ($request->filled('tab_icon')) {
            $extra['tab_icon'] = $request->tab_icon;
        }
        if ($request->filled('tab_color')) {
            $extra['tab_color'] = $request->tab_color;
        }

        if ($request->filled('extra_json')) {
            $decoded = json_decode($request->extra_json, true);
            if (is_array($decoded)) {
                $extra = array_merge($extra, $decoded);
            }
        }

        return empty($extra) ? null : $extra;
    }

}
