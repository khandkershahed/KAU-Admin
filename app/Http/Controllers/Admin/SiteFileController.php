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

        $token = SiteFile::generateToken();
        $ext = strtolower((string) $uploaded->getClientOriginalExtension());
        $name = (string) $uploaded->getClientOriginalName();
        $mime = (string) $uploaded->getClientMimeType();
        $size = (int) $uploaded->getSize();

        $safeName = Str::slug(pathinfo($name, PATHINFO_FILENAME));
        $safeName = $safeName ?: 'file';

        $path = 'site-files/' . date('Y/m') . '/' . $safeName . '-' . substr($token, 0, 12) . ($ext ? ('.' . $ext) : '');

        // Store in private disk (config/filesystems.php disk: local -> storage/app/private)
        Storage::disk('local')->putFileAs(dirname($path), $uploaded, basename($path));

        $adminId = Auth::guard('admin')->id();

        $file = SiteFile::create([
            'token' => $token,
            'disk' => 'local',
            'path' => $path,
            'original_name' => $name,
            'mime' => $mime,
            'size' => $size,
            'extension' => $ext ?: null,
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);

        // Build FRONTEND URL (kau.ac.bd) that proxies through Next.js
        $frontend = rtrim((string) config('app.frontend_url', config('app.url')), '/');
        $q = $file->signedQuery();
        $frontendUrl = $frontend . '/files/' . $file->token . '?exp=' . $q['exp'] . '&sig=' . $q['sig'];

        // If request expects JSON (AJAX), return JSON.
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $file->id,
                    'token' => $file->token,
                    'original_name' => $file->original_name,
                    'mime' => $file->mime,
                    'size' => $file->size,
                    'frontend_url' => $frontendUrl,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'File uploaded. Frontend URL copied from the list.');
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
