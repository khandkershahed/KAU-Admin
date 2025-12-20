<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Privacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivacyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view privacy')->only(['index']);
        $this->middleware('permission:create privacy')->only(['store']);
        $this->middleware('permission:edit privacy')->only(['update', 'toggleStatus']);
        $this->middleware('permission:delete privacy')->only(['destroy']);
    }

    /**
     * Show privacy list (modal-based CRUD)
     */
    public function index()
    {
        $privacies = Privacy::latest()->get(); // modal UI → no pagination
        return view('admin.pages.privacy.index', compact('privacies'));
    }

    /**
     * Store new privacy policy
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'nullable|string|max:255',
            'content'          => 'nullable|string',
            'version'          => 'nullable|string|max:50',
            'effective_date'   => 'nullable|date',
            'expiration_date'  => 'nullable|date|after_or_equal:effective_date',
            'status'           => 'required|in:active,inactive',
        ]);

        // Only one active privacy at a time
        if ($request->status === 'active') {
            Privacy::where('status', 'active')->update(['status' => 'inactive']);
        }

        Privacy::create([
            'title'           => $request->title,
            'content'         => $request->content,
            'version'         => $request->version,
            'effective_date'  => $request->effective_date,
            'expiration_date' => $request->expiration_date,
            'status'          => $request->status,
            'created_by'      => Auth::id(),
        ]);

        return redirect()
            ->route('admin.privacy.index')
            ->with('success', 'Privacy policy created successfully.');
    }

    /**
     * Update privacy policy
     */
    public function update(Request $request, Privacy $privacy)
    {
        $request->validate([
            'title'            => 'nullable|string|max:255',
            'content'          => 'nullable|string',
            'version'          => 'nullable|string|max:50',
            'effective_date'   => 'nullable|date',
            'expiration_date'  => 'nullable|date|after_or_equal:effective_date',
            'status'           => 'required|in:active,inactive',
        ]);

        // Only one active privacy at a time
        if ($request->status === 'active') {
            Privacy::where('id', '!=', $privacy->id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']);
        }

        $privacy->update([
            'title'           => $request->title,
            'content'         => $request->content,
            'version'         => $request->version,
            'effective_date'  => $request->effective_date,
            'expiration_date' => $request->expiration_date,
            'status'          => $request->status,
            'updated_by'      => Auth::id(),
        ]);

        return redirect()
            ->route('admin.privacy.index')
            ->with('success', 'Privacy policy updated successfully.');
    }

    /**
     * Delete privacy policy
     */
    public function destroy(Privacy $privacy)
    {
        $privacy->delete();

        return redirect()
            ->route('admin.privacy.index')
            ->with('success', 'Privacy policy deleted successfully.');
    }

    /**
     * Toggle status (AJAX – index blade)
     */
    public function toggleStatus(Privacy $privacy)
    {
        $privacy->status = $privacy->status === 'active' ? 'inactive' : 'active';

        // Only one active privacy at a time
        if ($privacy->status === 'active') {
            Privacy::where('id', '!=', $privacy->id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']);
        }

        $privacy->save();

        return response()->json([
            'status'     => true,
            'message'    => 'Status updated successfully.',
            'new_status' => $privacy->status,
        ]);
    }
}
