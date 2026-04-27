<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProfileImageController extends Controller
{
    /**
     * Serve a private profile image.
     */
    public function serve(string $filename): BinaryFileResponse
    {
        // Simple authentication check
        if (!Auth::check()) {
            abort(403);
        }

        $fullPath = storage_path("app/private/profile-image/{$filename}");

        if (!file_exists($fullPath)) {
            abort(404);
        }

        return response()->file($fullPath);
    }
}
