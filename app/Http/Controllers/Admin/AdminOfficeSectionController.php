<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminOfficeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AdminOfficeSectionController extends Controller
{
    /**
     * Store section
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'office_id' => 'required|exists:admin_offices,id',
                'title'     => 'required|string|max:255',
                'position'  => 'nullable|integer',
                'status'    => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $msg) {
                    Session::flash('error', $msg);
                }
                return redirect()->back()->withInput();
            }

            // POSITION FIX
            $position = $request->position ?? 0;
            $exists   = AdminOfficeSection::where('office_id', $request->office_id)
                        ->where('position', $position)->exists();

            if ($exists) {
                $suggest = AdminOfficeSection::where('office_id', $request->office_id)
                    ->max('position') + 1;
                
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Position already taken. Suggested: $suggest");
            }

            AdminOfficeSection::create([
                'office_id' => $request->office_id,
                'title'     => $request->title,
                'position'  => $position,
                'status'    => $request->status ?? 1,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Section created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update section
     */
    public function update(Request $request, $id)
    {
        $section = AdminOfficeSection::findOrFail($id);

        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'title'     => 'required|string|max:255',
                'position'  => 'nullable|integer',
                'status'    => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $msg) {
                    Session::flash('error', $msg);
                }
                return redirect()->back()->withInput();
            }

            $position = $request->position ?? $section->position;

            $exists = AdminOfficeSection::where('office_id', $section->office_id)
                ->where('position', $position)
                ->where('id', '!=', $section->id)
                ->exists();

            if ($exists) {
                $suggest = AdminOfficeSection::where('office_id', $section->office_id)
                    ->max('position') + 1;

                return redirect()->back()
                    ->withInput()
                    ->with('error', "Position already taken. Suggested: $suggest");
            }

            $section->update([
                'title'     => $request->title,
                'position'  => $position,
                'status'    => $request->status,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Section updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete
     */
    public function destroy($id)
    {
        $section = AdminOfficeSection::findOrFail($id);
        $section->delete();

        return redirect()->back()->with('success', 'Section deleted successfully.');
    }

    /**
     * Sort sections
     */
    public function sort(Request $request)
    {
        $request->validate([
            'order' => 'required|array'
        ]);

        foreach ($request->order as $pos => $id) {
            AdminOfficeSection::where('id', $id)->update(['position' => $pos]);
        }

        return response()->json(['success' => true]);
    }

}
