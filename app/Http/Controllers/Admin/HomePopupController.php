<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePopup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HomePopupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage home popup')->only(['index']);
        $this->middleware('permission:manage home popup')->only(['store', 'update', 'destroy', 'toggleStatus']);
    }

    public function index()
    {
        $popups = HomePopup::orderByDesc('id')->paginate(20);

        return view('admin.pages.home_popups.index', compact('popups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:100'],
            // 'slug'        => ['nullable', 'string', 'max:150', Rule::unique('home_popups', 'slug')],
            'content'     => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_url'   => ['nullable', 'string', 'max:255'],
            'badge'       => ['nullable', 'string', 'max:191'],
            'button_name' => ['nullable', 'string', 'max:200'],
            'button_link' => ['nullable', 'string'],
            'status'      => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('home_popups', 'public');
        }
        if ($request->status === 'active') {
            HomePopup::where('status', 'active')->update(['status' => 'inactive']);
        }
        HomePopup::create($data);

        return redirect()->back()->with('success', 'Home popup created successfully.');
    }

    public function update(Request $request, HomePopup $popup)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:245'],
            // 'slug'        => ['nullable', 'string', 'max:150', Rule::unique('home_popups', 'slug')->ignore($popup->id)],
            'content'     => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_url'   => ['nullable', 'string', 'max:255'],
            'badge'       => ['nullable', 'string', 'max:191'],
            'button_name' => ['nullable', 'string', 'max:200'],
            'button_link' => ['nullable', 'string'],
            'status'      => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($request->hasFile('image')) {
            if (!empty($popup->image) && Storage::disk('public')->exists($popup->image)) {
                Storage::disk('public')->delete($popup->image);
            }
            $data['image'] = $request->file('image')->store('home_popups', 'public');
        }
        if ($request->status === 'active') {
            HomePopup::where('id', '!=', $popup->id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']);
        }

        $popup->update($data);

        return redirect()->back()->with('success', 'Home popup updated successfully.');
    }

    public function destroy(HomePopup $popup)
    {
        if (!empty($popup->image) && Storage::disk('public')->exists($popup->image)) {
            Storage::disk('public')->delete($popup->image);
        }

        $popup->delete();

        return response()->json(['status' => true, 'message' => 'Deleted successfully.']);
    }

    public function toggleStatus(HomePopup $popup)
    {
        $popup->status = $popup->status === 'active' ? 'inactive' : 'active';
        if ($popup->status === 'active') {
            HomePopup::where('id', '!=', $popup->id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']);
        }
        $popup->save();

        return response()->json([
            'status' => true,
            'message' => 'Status updated.',
            'new_status' => $popup->status,
        ]);
    }
}
