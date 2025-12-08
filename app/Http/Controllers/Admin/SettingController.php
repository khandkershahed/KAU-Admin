<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view setting')->only(['index']);
        $this->middleware('permission:update setting')->only(['updateOrcreateSetting']);
    }

    /**
     * Show settings page.
     */
    public function index()
    {
        $setting = Setting::first();

        if (!$setting) {
            $setting = Setting::create([
                'created_by' => Auth::guard('admin')->id(),
            ]);
        }

        return view('admin.pages.setting.index', [
            'setting' => $setting,
        ]);
    }

    /**
     * Update or create settings (AJAX)
     */
    public function updateOrcreateSetting(Request $request)
    {
        $setting = Setting::firstOrFail();

        $data = $request->except(['_token', '_method']);

        /*
        |--------------------------------------------------------------------------
        | JSON FIELDS
        |--------------------------------------------------------------------------
        */
        $jsonFields = [
            'footer_links',
            'contact_person',
            'emails',
            'phone',
            'addresses',
            'social_links',
            'business_hours',
            'custom_settings',
        ];

        foreach ($jsonFields as $field) {
            $data[$field] = $request->has($field)
                ? array_values($request->$field)
                : [];
        }

        /*
        |--------------------------------------------------------------------------
        | FILE UPLOADS
        |--------------------------------------------------------------------------
        */
        $fileUploads = [
            'site_logo_white',
            'site_logo_black',
            'site_favicon',
            'login_background_image',
            'og_image',
        ];

        foreach ($fileUploads as $fileField) {
            if ($request->hasFile($fileField)) {
                $upload = customUpload($request->file($fileField), 'settings');
                if ($upload['status'] === 1) {
                    $data[$fileField] = $upload['file_path'];
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update fields
        |--------------------------------------------------------------------------
        */
        $data['updated_by'] = Auth::guard('admin')->id();

        $setting->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully.',
        ]);
    }
}
