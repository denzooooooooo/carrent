<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\GoogleFlightsService;


class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home');
    }

    public function events()
    {
        return view('pages.events');
    }

    public function packages()
    {
        return view('pages.packages');
    }

    public function location()
    {
        return view('pages.location');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function cookies()
    {
        return view('pages.cookies');
    }

    public function login()
    {
        return view('pages.login');
    }

    public function register()
    {
        return view('pages.register');
    }

    public function profile()
    {
        return view('pages.profile');
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Here you can add logic to send email or save to database
        // For now, we'll just flash a success message

        Session::flash('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');

        return redirect()->back();
    }
}
