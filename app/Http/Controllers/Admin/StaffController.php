<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view staff')->only(['index']);
        $this->middleware('permission:create staff')->only(['create', 'store']);
        $this->middleware('permission:edit staff')->only(['edit', 'update']);
        $this->middleware('permission:delete staff')->only(['destroy']);
        $this->middleware('permission:show staff')->only(['show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'staffs' => Admin::latest('id')->get(),
        ];

        return view('admin.pages.staff.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'roles' => Role::orderBy('name')->get(),
        ];

        return view('admin.pages.staff.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'name'         => 'required|string|max:255',
                'username'     => 'nullable|string|max:255|unique:admins,username',
                'email'        => 'required|email|max:255|unique:admins,email',
                'password'     => 'required|string|min:8|confirmed',
                'designation'  => 'nullable|string|max:255',
                'phone'        => 'nullable|string|max:50|unique:admins,phone',
                'photo'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
                'country'      => 'nullable|string|max:255',
                'city'         => 'nullable|string|max:255',
                'zipcode'      => 'nullable|string|max:50',
                'company_name' => 'nullable|string|max:255',
                'address'      => 'nullable|string|max:500',
                'youtube'      => 'nullable|string|max:255',
                'facebook'     => 'nullable|string|max:255',
                'twitter'      => 'nullable|string|max:255',
                'linkedin'     => 'nullable|string|max:255',
                'website'      => 'nullable|string|max:255',
                'biometric_id' => 'nullable|string|max:255',
                'mail_status'  => 'nullable|string|max:255',
                'status'       => 'required|in:active,inactive',
                'roles'        => 'nullable|array',
                'roles.*'      => 'integer|exists:roles,id',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }

            // Photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $upload = customUpload($request->file('photo'), 'admins/photo');
                if ($upload['status'] === 0) {
                    return redirect()->back()->withInput()->with('error', $upload['error_message']);
                }
                $photoPath = $upload['file_path'];
            }

            $admin = Admin::create([
                'name'         => $request->name,
                'username'     => $request->username,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'designation'  => $request->designation,
                'phone'        => $request->phone,
                'photo'        => $photoPath,
                'country'      => $request->country,
                'city'         => $request->city,
                'zipcode'      => $request->zipcode,
                'company_name' => $request->company_name,
                'address'      => $request->address,
                'youtube'      => $request->youtube,
                'facebook'     => $request->facebook,
                'twitter'      => $request->twitter,
                'linkedin'     => $request->linkedin,
                'website'      => $request->website,
                'biometric_id' => $request->biometric_id,
                'mail_status'  => $request->mail_status,
                'status'       => $request->status,
            ]);

            // Attach roles (Spatie)
            if (!empty($request->roles)) {
                $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
                $admin->syncRoles($roleNames);
            }

            DB::commit();

            return redirect()->route('admin.staff.index')->with('success', 'Staff created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating staff: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = Admin::findOrFail($id);

        return view('admin.pages.staff.show', [
            'staff' => $staff,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = Admin::findOrFail($id);

        $data = [
            'staff' => $staff,
            'roles' => Role::orderBy('name')->get(),
            'staffRoleIds' => $staff->roles->pluck('id')->toArray(),
        ];

        return view('admin.pages.staff.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $admin = Admin::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name'         => 'required|string|max:255',
                'username'     => 'nullable|string|max:255|unique:admins,username,' . $admin->id,
                'email'        => 'required|email|max:255|unique:admins,email,' . $admin->id,
                'password'     => 'nullable|string|min:8|confirmed',
                'designation'  => 'nullable|string|max:255',
                'phone'        => 'nullable|string|max:50|unique:admins,phone,' . $admin->id,
                'photo'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
                'country'      => 'nullable|string|max:255',
                'city'         => 'nullable|string|max:255',
                'zipcode'      => 'nullable|string|max:50',
                'company_name' => 'nullable|string|max:255',
                'address'      => 'nullable|string|max:500',
                'youtube'      => 'nullable|string|max:255',
                'facebook'     => 'nullable|string|max:255',
                'twitter'      => 'nullable|string|max:255',
                'linkedin'     => 'nullable|string|max:255',
                'website'      => 'nullable|string|max:255',
                'biometric_id' => 'nullable|string|max:255',
                'mail_status'  => 'nullable|string|max:255',
                'status'       => 'required|in:active,inactive',
                'roles'        => 'nullable|array',
                'roles.*'      => 'integer|exists:roles,id',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }

            // Photo update
            if ($request->hasFile('photo')) {
                if (!empty($admin->photo)) {
                    Storage::delete('public/' . $admin->photo);
                }

                $upload = customUpload($request->file('photo'), 'admins/photo');
                if ($upload['status'] === 0) {
                    return redirect()->back()->withInput()->with('error', $upload['error_message']);
                }
                $admin->photo = $upload['file_path'];
            }

            // Password update (optional)
            if ($request->filled('password')) {
                $admin->password = Hash::make($request->password);
            }

            $admin->name         = $request->name;
            $admin->username     = $request->username;
            $admin->email        = $request->email;
            $admin->designation  = $request->designation;
            $admin->phone        = $request->phone;
            $admin->country      = $request->country;
            $admin->city         = $request->city;
            $admin->zipcode      = $request->zipcode;
            $admin->company_name = $request->company_name;
            $admin->address      = $request->address;
            $admin->youtube      = $request->youtube;
            $admin->facebook     = $request->facebook;
            $admin->twitter      = $request->twitter;
            $admin->linkedin     = $request->linkedin;
            $admin->website      = $request->website;
            $admin->biometric_id = $request->biometric_id;
            $admin->mail_status  = $request->mail_status;
            $admin->status       = $request->status;

            $admin->save();

            // Sync roles
            if (!empty($request->roles)) {
                $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
                $admin->syncRoles($roleNames);
            } else {
                $admin->syncRoles([]); // remove all roles if none selected
            }

            DB::commit();

            return redirect()->back()->with('success', 'Staff updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating staff: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = Admin::findOrFail($id);

        if (!empty($admin->photo)) {
            Storage::delete('public/' . $admin->photo);
        }

        // Remove roles
        $admin->syncRoles([]);

        $admin->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff deleted successfully.');
    }
}
