<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

/**
 * Serves files from storage/app/public when the public/storage symlink
 * is not available (e.g. on shared hosting where symlinks are disabled).
 * Requests to /storage/* are served by the web server when the symlink
 * exists; otherwise they fall through to this route.
 */
class StorageLinkController extends Controller
{
    /**
     * Serve a file from the public storage disk.
     * Path must be relative (e.g. products/images/abc.jpg).
     */
    public function show(Request $request, string $path): Response
    {
        // Prevent directory traversal
        if (str_contains($path, '..')) {
            abort(404);
        }

        $path = trim($path, '/');

        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $fullPath = Storage::disk('public')->path($path);
        $mimeType = Storage::disk('public')->mimeType($path);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
        ]);
    }
}
