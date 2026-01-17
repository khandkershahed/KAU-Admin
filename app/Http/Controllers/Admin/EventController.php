<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view events')->only(['index']);
        $this->middleware('permission:create events')->only(['create', 'store']);
        $this->middleware('permission:edit events')->only(['edit', 'update']);
        $this->middleware('permission:delete events')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Event::query()
            ->orderByDesc('start_at')
            ->orderByDesc('id');

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['draft', 'published', 'archived'], true)) {
            $query->where('status', $request->status);
        }

        $events = $query->paginate(20);

        if ($request->ajax()) {
            return view('admin.pages.events.partials.table', [
                'events' => $events,
                'search' => $request->get('q'),
                'status' => $request->get('status'),
            ])->render();
        }

        return view('admin.pages.events.index', [
            'events' => $events,
            'search' => $request->get('q'),
            'status' => $request->get('status'),
        ]);
    }

    public function create()
    {
        return view('admin.pages.events.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'title'             => 'required|string|max:255',
                'excerpt'           => 'nullable|string',
                'body'              => 'nullable|string',
                'start_at'          => 'nullable|date',
                'end_at'            => 'nullable|date|after_or_equal:start_at',
                'venue'             => 'nullable|string|max:255',
                'organizer'         => 'nullable|string|max:255',
                'contact_email'     => 'nullable|email|max:255',
                'contact_phone'     => 'nullable|string|max:255',
                'registration_url'  => 'nullable|string|max:1000',
                'banner_image'      => 'nullable|image|max:4096',
                'attachments.*'     => 'nullable|file|max:5120',
                'meta_title'        => 'nullable|string|max:255',
                'meta_tags'         => 'nullable|string|max:255',
                'meta_description'  => 'nullable|string',
                'is_featured'       => 'nullable|in:0,1',
                'status'            => 'nullable|in:draft,published,archived',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }

            // Banner image
            $bannerImage = null;
            if ($request->hasFile('banner_image')) {
                $upload = customUpload($request->file('banner_image'), 'events/banner');
                if ($upload['status'] === 0) {
                    return redirect()->back()->withInput()->with('error', $upload['error_message']);
                }
                $bannerImage = $upload['file_path'];
            }

            // Attachments
            $storedAttachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file) {
                        $upload = customUpload($file, 'events/attachments');
                        if ($upload['status'] === 0) {
                            return redirect()->back()->withInput()->with('error', $upload['error_message']);
                        }
                        $storedAttachments[] = $upload['file_path'];
                    }
                }
            }

            $firstAttachmentType = null;
            if (count($storedAttachments)) {
                $ext = pathinfo($storedAttachments[0], PATHINFO_EXTENSION);
                $firstAttachmentType = strtolower($ext);
            }

            Event::create([
                'title'            => $request->title,
                'excerpt'          => $request->excerpt,
                'body'             => $request->body,
                'start_at'         => $request->start_at,
                'end_at'           => $request->end_at,
                'venue'            => $request->venue,
                'organizer'        => $request->organizer,
                'contact_email'    => $request->contact_email,
                'contact_phone'    => $request->contact_phone,
                'registration_url' => $request->registration_url,
                'banner_image'     => $bannerImage,
                'attachments'      => count($storedAttachments) ? $storedAttachments : null,
                'attachment_type'  => $firstAttachmentType,
                'meta_title'       => $request->meta_title,
                'meta_tags'        => $request->meta_tags,
                'meta_description' => $request->meta_description,
                'views'            => 0,
                'is_featured'      => $request->is_featured ?? 0,
                'status'           => $request->status ?? 'published',
                'created_by'       => Auth::guard('admin')->id(),
            ]);

            DB::commit();

            return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error while creating event: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        return view('admin.pages.events.edit', compact('event'));
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $event = Event::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title'             => 'required|string|max:255',
                'excerpt'           => 'nullable|string',
                'body'              => 'nullable|string',
                'start_at'          => 'nullable|date',
                'end_at'            => 'nullable|date|after_or_equal:start_at',
                'venue'             => 'nullable|string|max:255',
                'organizer'         => 'nullable|string|max:255',
                'contact_email'     => 'nullable|email|max:255',
                'contact_phone'     => 'nullable|string|max:255',
                'registration_url'  => 'nullable|string|max:1000',
                'banner_image'      => 'nullable|image|max:4096',
                'attachments.*'     => 'nullable|file|max:5120',
                'meta_title'        => 'nullable|string|max:255',
                'meta_tags'         => 'nullable|string|max:255',
                'meta_description'  => 'nullable|string',
                'is_featured'       => 'nullable|in:0,1',
                'status'            => 'nullable|in:draft,published,archived',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }

            // Banner image
            if ($request->hasFile('banner_image')) {
                if (!empty($event->banner_image)) {
                    Storage::disk('public')->delete($event->banner_image);
                }
                $upload = customUpload($request->file('banner_image'), 'events/banner');
                if ($upload['status'] === 0) {
                    return redirect()->back()->withInput()->with('error', $upload['error_message']);
                }
                $event->banner_image = $upload['file_path'];
            }

            // Attachments overwrite (same as Notice)
            $storedAttachments = $event->attachments ?? [];
            if ($request->hasFile('attachments')) {
                if (is_array($storedAttachments)) {
                    foreach ($storedAttachments as $oldFile) {
                        if ($oldFile) {
                            Storage::disk('public')->delete($oldFile);
                        }
                    }
                }
                $storedAttachments = [];

                foreach ($request->file('attachments') as $file) {
                    if ($file) {
                        $upload = customUpload($file, 'events/attachments');
                        if ($upload['status'] === 0) {
                            return redirect()->back()->withInput()->with('error', $upload['error_message']);
                        }
                        $storedAttachments[] = $upload['file_path'];
                    }
                }
            }

            $firstAttachmentType = null;
            if (count($storedAttachments)) {
                $ext = pathinfo($storedAttachments[0], PATHINFO_EXTENSION);
                $firstAttachmentType = strtolower($ext);
            }

            $event->update([
                'title'            => $request->title,
                'excerpt'          => $request->excerpt,
                'body'             => $request->body,
                'start_at'         => $request->start_at,
                'end_at'           => $request->end_at,
                'venue'            => $request->venue,
                'organizer'        => $request->organizer,
                'contact_email'    => $request->contact_email,
                'contact_phone'    => $request->contact_phone,
                'registration_url' => $request->registration_url,
                'attachments'      => count($storedAttachments) ? $storedAttachments : null,
                'attachment_type'  => $firstAttachmentType,
                'meta_title'       => $request->meta_title,
                'meta_tags'        => $request->meta_tags,
                'meta_description' => $request->meta_description,
                'is_featured'      => $request->is_featured ?? 0,
                'status'           => $request->status ?? 'published',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Event updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error while updating event: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);

        if (!empty($event->banner_image)) {
            Storage::disk('public')->delete($event->banner_image);
        }

        if (is_array($event->attachments)) {
            foreach ($event->attachments as $file) {
                if ($file) {
                    Storage::disk('public')->delete($file);
                }
            }
        }

        $event->delete();

        return response()->json(['success' => true]);
    }

    public function toggleFeatured(Event $event, Request $request)
    {
        $request->validate(['is_featured' => 'required|boolean']);
        $event->is_featured = $request->boolean('is_featured');
        $event->save();

        return response()->json(['success' => true, 'message' => 'Featured status updated.']);
    }

    public function toggleStatus(Event $event, Request $request)
    {
        $request->validate(['status' => 'required|in:draft,published,archived']);
        $event->status = $request->status;
        $event->save();

        return response()->json(['success' => true, 'message' => 'Status updated.']);
    }
}
