<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CurrencyController extends Controller
{
    /**
     * Change the current currency
     */
    public function change(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|in:XOF,EUR,USD,GBP'
        ]);

        $currency = $request->input('currency');

        // Store currency in session
        Session::put('currency', $currency);

        // If user is authenticated, update their preferred currency
        if (auth()->check()) {
            auth()->user()->update([
                'preferred_currency' => $currency
            ]);
        }

        return response()->json([
            'success' => true,
            'currency' => $currency,
            'message' => 'Devise changée avec succès'
        ]);
    }

    /**
     * Get current currency
     */
    public function current()
    {
        $currency = Session::get('currency', 'XOF');

        return response()->json([
            'currency' => $currency
        ]);
    }
}
