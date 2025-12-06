<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminOfficeMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AdminOfficeMemberController extends Controller
{
    /**
     * Store member
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'office_id'   => 'required|exists:admin_offices,id',
                'section_id'  => 'required|exists:admin_office_sections,id',
                'name'        => 'required|string|max:255',
                'designation' => 'nullable|string|max:255',
                'email'       => 'nullable|email',
                'phone'       => 'nullable|string|max:255',
                'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
                'position'    => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $msg) {
                    Session::flash('error', $msg);
                }
                return redirect()->back()->withInput();
            }

            // POSITION CHECK
            $position = $request->position ?? 0;

            $exists = AdminOfficeMember::where('section_id', $request->section_id)
                ->where('position', $position)
                ->exists();

            if ($exists) {
                $suggest = AdminOfficeMember::where('section_id', $request->section_id)
                    ->max('position') + 1;

                return redirect()->back()
                    ->with('error', "Position already taken. Suggested: $suggest")
                    ->withInput();
            }

            $member = AdminOfficeMember::create([
                'office_id'   => $request->office_id,
                'section_id'  => $request->section_id,
                'name'        => $request->name,
                'designation' => $request->designation,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'position'    => $position,
            ]);

            // Upload image
            if ($request->file('image')) {
                $path = "administration/offices/$member->office_id/members";
                $upload = customUpload($request->file('image'), $path);

                if ($upload['status'] == 0) {
                    return redirect()->back()->with('error', $upload['error_message']);
                }

                $member->update([
                    'image' => $upload['file_path']
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Member created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update member
     */
    public function update(Request $request, $id)
    {
        $member = AdminOfficeMember::findOrFail($id);

        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'designation' => 'nullable|string|max:255',
                'email'       => 'nullable|email',
                'phone'       => 'nullable|string|max:255',
                'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
                'position'    => 'nullable|integer',
                'section_id'  => 'required|exists:admin_office_sections,id',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $msg) {
                    Session::flash('error', $msg);
                }
                return redirect()->back()->withInput();
            }

            $position = $request->position ?? $member->position;

            $exists = AdminOfficeMember::where('section_id', $request->section_id)
                ->where('position', $position)
                ->where('id', '!=', $member->id)
                ->exists();

            if ($exists) {
                $suggest = AdminOfficeMember::where('section_id', $request->section_id)
                    ->max('position') + 1;

                return redirect()->back()
                    ->with('error', "Position already taken. Suggested: $suggest")
                    ->withInput();
            }

            $member->update([
                'name'        => $request->name,
                'designation' => $request->designation,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'position'    => $position,
                'section_id'  => $request->section_id,
            ]);

            // Replace image
            if ($request->file('image')) {

                if ($member->image) {
                    Storage::disk('public')->delete($member->image);
                }

                $path = "administration/offices/$member->office_id/members";
                $upload = customUpload($request->file('image'), $path);

                if ($upload['status'] == 0) {
                    return redirect()->back()->with('error', $upload['error_message']);
                }

                $member->update([
                    'image' => $upload['file_path']
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Member updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Delete
     */
    public function destroy($id)
    {
        $member = AdminOfficeMember::findOrFail($id);

        if ($member->image) {
            Storage::disk('public')->delete($member->image);
        }

        $member->delete();

        return redirect()->back()->with('success', 'Member deleted successfully.');
    }

    /**
     * Sort members inside each section
     */
    public function sort(Request $request)
    {
        $request->validate([
            'order'      => 'required|array',
            'section_id' => 'required|exists:admin_office_sections,id',
        ]);

        foreach ($request->order as $pos => $id) {
            AdminOfficeMember::where('id', $id)->update(['position' => $pos]);
        }

        return response()->json(['success' => true]);
    }
}
