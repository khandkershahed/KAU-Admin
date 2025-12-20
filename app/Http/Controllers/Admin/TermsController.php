<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Terms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TermsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view terms')->only(['index']);
        $this->middleware('permission:create terms')->only(['store']);
        $this->middleware('permission:edit terms')->only(['update', 'toggleStatus']);
        $this->middleware('permission:delete terms')->only(['destroy']);
    }

    /**
     * Show terms list (modal-based CRUD)
     */
    public function index()
    {
        $terms = Terms::latest()->get(); // ⚠️ modal UI → no pagination
        return view('admin.pages.terms.index', compact('terms'));
    }

    /**
     * Store new terms (Create modal)
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
        if ($request->status === 'active') {
            Terms::where('status', 'active')->update(['status' => 'inactive']);
        }
        Terms::create([
            'title'          => $request->title,
            'content'        => $request->content,
            'version'        => $request->version,
            'effective_date' => $request->effective_date,
            'expiration_date' => $request->expiration_date,
            'status'         => $request->status,
            'created_by'     => Auth::id(),
        ]);

        return redirect()
            ->route('admin.terms.index')
            ->with('success', 'Terms created successfully.');
    }

    /**
     * Update terms (Edit modal)
     */
    public function update(Request $request, Terms $term)
    {
        $request->validate([
            'title'            => 'nullable|string|max:255',
            'content'          => 'nullable|string',
            'version'          => 'nullable|string|max:50',
            'effective_date'   => 'nullable|date',
            'expiration_date'  => 'nullable|date|after_or_equal:effective_date',
            'status'           => 'required|in:active,inactive',
        ]);
        if ($request->status === 'active') {
            Terms::where('id', '!=', $term->id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']);
        }
        $term->update([
            'title'          => $request->title,
            'content'        => $request->content,
            'version'        => $request->version,
            'effective_date' => $request->effective_date,
            'expiration_date' => $request->expiration_date,
            'status'         => $request->status,
            'updated_by'     => Auth::id(),
        ]);

        return redirect()
            ->route('admin.terms.index')
            ->with('success', 'Terms updated successfully.');
    }

    /**
     * Delete terms
     */
    public function destroy(Terms $term)
    {
        $term->delete();

        return redirect()
            ->route('admin.terms.index')
            ->with('success', 'Terms deleted successfully.');
    }

    /**
     * Toggle status (AJAX – used by index blade)
     */
    public function toggleStatus(Terms $term)
    {
        $term->status = $term->status === 'active' ? 'inactive' : 'active';

        // OPTIONAL: Only one active term at a time
        if ($term->status === 'active') {
            Terms::where('id', '!=', $term->id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']);
        }

        $term->save();

        return response()->json([
            'status'     => true,
            'message'    => 'Status updated successfully.',
            'new_status' => $term->status,
        ]);
    }
}
