<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\GoogleFlightsService;
use App\Models\Event;
use App\Models\Location;
use App\Mail\ContactMessage;


class HomeController extends Controller
{

    public function index()
    {
        // Charger les événements actifs avec leurs relations pour le carrousel
        $events = Event::where('is_active', true)
            ->with(['category', 'type', 'seatZones'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('pages.home', compact('events'));
    }

    public function events()
    {
        $events = Event::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pages.events', compact('events'));
    }

    public function packages()
    {
        return view('pages.packages');
    }

    public function location(Request $request)
    {
        $baseQuery = Location::query()->active();
        $query = Location::query()->active();

        if ($request->filled('q')) {
            $search = '%' . trim($request->input('q')) . '%';

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('name_fr', 'like', $search)
                    ->orWhere('name_en', 'like', $search)
                    ->orWhere('description_fr', 'like', $search)
                    ->orWhere('description_en', 'like', $search)
                    ->orWhere('category', 'like', $search)
                    ->orWhere('type', 'like', $search);
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('capacity')) {
            match ($request->input('capacity')) {
                '1-2' => $query->whereBetween('capacity', [1, 2]),
                '3-5' => $query->whereBetween('capacity', [3, 5]),
                '6+' => $query->where('capacity', '>=', 6),
                default => null,
            };
        }

        $selectedSort = $request->input('sort', 'recommended');

        match ($selectedSort) {
            'price_low' => $query->orderBy('price_per_day'),
            'price_high' => $query->orderByDesc('price_per_day'),
            'capacity_high' => $query->orderByDesc('capacity'),
            'name' => $query->orderBy('name_fr')->orderBy('name_en'),
            default => $query->orderByDesc('created_at'),
        };

        $locations = $query
            ->paginate(9)
            ->appends($request->query());

        $categories = (clone $baseQuery)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->values();

        $types = (clone $baseQuery)
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->distinct()
            ->orderBy('type')
            ->pluck('type')
            ->values();

        $sortOptions = [
            'recommended' => 'Recommandés',
            'price_low' => 'Prix croissant',
            'price_high' => 'Prix décroissant',
            'capacity_high' => 'Grande capacité',
            'name' => 'Nom A-Z',
        ];

        $totalLocationsCount = (clone $baseQuery)->count();
        $startingPrice = (clone $baseQuery)->min('price_per_day');

        return view('pages.location', compact(
            'locations',
            'categories',
            'types',
            'selectedSort',
            'sortOptions',
            'totalLocationsCount',
            'startingPrice'
        ));
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

    public function partnership()
    {
        return view('pages.partnership');
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'company_name' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'partnership_type' => 'nullable|string|max:100',
        ]);

        $message = $validated['message'];

        if (!empty($validated['company_name']) || !empty($validated['partnership_type']) || !empty($validated['website'])) {
            $message = collect([
                !empty($validated['company_name']) ? 'Entreprise: ' . $validated['company_name'] : null,
                !empty($validated['partnership_type']) ? 'Type de partenariat: ' . $validated['partnership_type'] : null,
                !empty($validated['website']) ? 'Site web: ' . $validated['website'] : null,
                null,
                $validated['message'],
            ])->filter(static fn ($value) => $value !== null)->implode("\n");
        }

        $payload = array_merge($validated, [
            'message' => $message,
            'source_page' => $request->headers->get('referer') ?: $request->fullUrl(),
            'submitted_at' => now()->toDateTimeString(),
        ]);

        try {
            Mail::to(config('carre_premium.contact.support_email'))
                ->send(new ContactMessage($payload));
        } catch (\Throwable $e) {
            Log::error('Contact request delivery failed', [
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Votre demande n’a pas pu être envoyée pour le moment. Veuillez réessayer ou nous contacter directement par téléphone.');
        }

        Session::flash('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');

        return redirect()->back();
    }

    public function visaService()
    {
        return view('pages.visa-service');
    }

    public function conciergeLuxury()
    {
        return view('pages.concierge.luxury');
    }

    public function personalShopper()
    {
        return view('pages.concierge.personal-shopper');
    }
}
