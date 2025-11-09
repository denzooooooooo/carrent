<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Change the current language
     */
    public function change(Request $request)
    {
        $request->validate([
            'language' => 'required|string|in:fr,en'
        ]);

        $language = $request->input('language');

        // Store language in session
        Session::put('locale', $language);

        // Set the application locale
        App::setLocale($language);

        // If user is authenticated, update their preferred language
        if (auth()->check()) {
            auth()->user()->update([
                'preferred_language' => $language
            ]);
        }

        return response()->json([
            'success' => true,
            'language' => $language,
            'message' => $language === 'fr' ? 'Langue changée avec succès' : 'Language changed successfully'
        ]);
    }

    /**
     * Get current language
     */
    public function current()
    {
        $language = Session::get('locale', config('app.locale', 'fr'));

        return response()->json([
            'language' => $language
        ]);
    }
}
