<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ThemeController extends Controller
{
    /**
     * Change the current theme
     */
    public function change(Request $request)
    {
        $request->validate([
            'theme' => 'required|string|in:light,dark'
        ]);

        $theme = $request->input('theme');

        // Store theme in session
        Session::put('theme', $theme);

        // If user is authenticated, update their preferred theme
        if (auth()->check()) {
            auth()->user()->update([
                'preferred_theme' => $theme
            ]);
        }

        return response()->json([
            'success' => true,
            'theme' => $theme,
            'message' => $theme === 'dark' ? 'Mode sombre activé' : 'Mode clair activé'
        ]);
    }

    /**
     * Get current theme
     */
    public function current()
    {
        $theme = Session::get('theme', 'light');

        return response()->json([
            'theme' => $theme
        ]);
    }
}
