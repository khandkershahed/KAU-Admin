<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SiteFileController extends Controller
{
    public function index()
    {
        $files = SiteFile::query()->latest()->paginate(30);
        return view('admin.pages.site-files.index', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:51200'], // 50MB
        ]);

        $uploaded = $request->file('file');

        // Keep token for DB uniqueness if you already have it, but DO NOT use it in URL anymore
        $token = SiteFile::generateToken();

        $ext  = strtolower((string) $uploaded->getClientOriginalExtension());
        $name = (string) $uploaded->getClientOriginalName();
        $mime = (string) $uploaded->getClientMimeType();
        $size = (int) $uploaded->getSize();

        // Make a safe base name
        $safeBase = Str::slug(pathinfo($name, PATHINFO_FILENAME));
        $safeBase = $safeBase ?: 'file';

        // ✅ Generate unique filename WITHOUT token in URL
        // Example: ethical-approval-application-20260301-153522-6f3a2c1b.pdf
        $unique = now()->format('Ymd-His') . '-' . Str::lower(Str::random(8));
        $filename = $safeBase . '-' . $unique . ($ext ? ('.' . $ext) : '');

        // Keep your storage structure
        $path = 'site-files/' . date('Y/m') . '/' . $filename;

        // Store in private disk (local => storage/app/private)
        Storage::disk('local')->putFileAs(dirname($path), $uploaded, basename($path));

        $adminId = Auth::guard('admin')->id();

        $file = SiteFile::create([
            'token' => $token, // stored but NOT used in URL
            'disk' => 'local',
            'path' => $path,
            'original_name' => $name,
            'mime' => $mime,
            'size' => $size,
            'extension' => $ext ?: null,
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);

        // ✅ Build simple FRONTEND URL (NO token)
        // users will use: kau.ac.bd/files/{filename}
        $frontend = rtrim((string) config('app.frontend_url', config('app.url')), '/');
        $frontendUrl = $frontend . '/files/' . rawurlencode($filename);

        // (Optional) API url (useful for debugging, not required)
        $apiBase = rtrim((string) config('app.url'), '/');
        $apiUrl = $apiBase . '/api/v1/files/' . rawurlencode($filename);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $file->id,
                    'filename' => $filename,
                    'original_name' => $file->original_name,
                    'mime' => $file->mime,
                    'size' => $file->size,
                    'frontend_url' => $frontendUrl,
                    'api_url' => $apiUrl,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'File uploaded. Copy the Frontend URL from the list.');
    }

    public function destroy(SiteFile $siteFile)
    {
        if ($siteFile->storageExists()) {
            Storage::disk($siteFile->disk)->delete($siteFile->path);
        }

        $siteFile->delete();

        return redirect()->back()->with('success', 'File deleted.');
    }
}
