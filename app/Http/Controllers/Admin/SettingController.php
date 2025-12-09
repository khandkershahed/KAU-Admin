<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view setting')->only(['index']);
        $this->middleware('permission:update setting')->only(['updateOrcreateSetting']);
    }


    /**
     * Show Settings page
     */
    public function index()
    {
        $setting = Setting::first();

        if (!$setting) {
            $setting = Setting::create([
                'created_by' => Auth::guard('admin')->id(),
            ]);
        }

        return view('admin.pages.setting.index', compact('setting'));
    }



    /**
     * Update settings via AJAX
     */
    public function updateOrcreateSetting(Request $request)
    {
        $setting = Setting::firstOrFail();

        $data = $request->except(['_token']);


        // JSON fields (repeater / multi-input)
        $jsonFields = [
            'footer_links',
            'contact_person',
            'emails',
            'phone',
            'addresses',
            'social_links',
        ];

        foreach ($jsonFields as $field) {

            $value = $request->input($field);

            // A) Already array
            if (is_array($value)) {
                $data[$field] = array_values($value);
            }

            // B) JSON string
            elseif (is_string($value) && strlen($value) > 0) {
                $decoded = json_decode($value, true);
                $data[$field] = is_array($decoded) ? $decoded : [];
            }

            // C) empty / null
            else {
                $data[$field] = [];
            }
        }




        /* ----------------------------------------------
            BUSINESS HOURS (SAFE UPDATE)
        ---------------------------------------------- */
        if ($request->has('business_hours')) {
            $hours = $request->input('business_hours');

            if (is_string($hours) && ($decoded = json_decode($hours, true)) !== null) {
                $data['business_hours'] = $decoded;
            } elseif (is_array($hours)) {
                $data['business_hours'] = $hours;
            } else {
                $data['business_hours'] = [];
            }
        }



        $fileUploads = [
            'site_logo_white',
            'site_logo_black',
            'site_favicon',
            'login_background_image',
            'og_image',
        ];

        foreach ($fileUploads as $field) {

            $oldFile = $setting->$field;


            if ($request->input($field . '_remove') == 1) {

                if (!empty($oldFile) && Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }

                $data[$field] = null;
                continue; // skip upload handling
            }


            if ($request->hasFile($field)) {

                // Delete old file (only if exists)
                if (!empty($oldFile) && Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }

                // Upload new one
                $upload = customUpload($request->file($field), 'settings');

                if ($upload['status'] === 1) {
                    $data[$field] = $upload['file_path'];
                }
            }
        }



        $data['updated_by'] = Auth::guard('admin')->id();



        $setting->update($data);


        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully.',
        ]);
    }
}
