<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditorController extends Controller
{
    public function upload(Request $request)
    {
        // TinyMCE will send the file as "file"
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded.'], 422);
        }

        $file = $request->file('file');

        if (!$file->isValid()) {
            return response()->json(['error' => 'Invalid file upload.'], 422);
        }

        // Validate mime if you want
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowed)) {
            return response()->json(['error' => 'Unsupported file type.'], 422);
        }

        // Store in public disk: storage/app/public/editor/...
        $path = $file->storeAs(
            'editor',
            Str::random(20) . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        $url = asset('storage/' . $path);

        // TinyMCE expects a JSON response with { "location": "url" }
        return response()->json(['location' => $url]);
    }
}
