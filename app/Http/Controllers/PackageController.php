<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use App\Models\Booking;
use App\Models\PackageBooking;
use App\Mail\PackageBookingConfirmation;
use App\Services\BookingAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    /**
     * Afficher la liste des packages avec filtres.
     */
    public function index(Request $request)
    {
        $query = TourPackage::where('is_active', true)
            ->with(['category']);

        if ($request->filled('q')) {
            $search = '%' . trim($request->input('q')) . '%';

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('title_fr', 'like', $search)
                    ->orWhere('title_en', 'like', $search)
                    ->orWhere('description_fr', 'like', $search)
                    ->orWhere('description_en', 'like', $search)
                    ->orWhere('destination', 'like', $search)
                    ->orWhere('package_type', 'like', $search);
            });
        }

        // Filtre par type de package
        if ($request->filled('type')) {
            $query->where('package_type', $request->type);
        }

        // Filtre par destination
        if ($request->filled('destination')) {
            $query->where('destination', 'like', '%' . $request->destination . '%');
        }

        // Filtre par durée
        if ($request->filled('duration')) {
            switch ($request->duration) {
                case '1-3':
                    $query->whereBetween('duration', [1, 3]);
                    break;
                case '4-7':
                    $query->whereBetween('duration', [4, 7]);
                    break;
                case '1-2-weeks':
                    $query->whereBetween('duration', [7, 14]);
                    break;
                case 'more-than-2-weeks':
                    $query->where('duration', '>', 14);
                    break;
            }
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $selectedSort = $request->input('sort', 'featured');

        match ($selectedSort) {
            'price_low' => $query->orderByRaw('COALESCE(discount_price, price) asc'),
            'price_high' => $query->orderByRaw('COALESCE(discount_price, price) desc'),
            'duration_short' => $query->orderBy('duration'),
            'duration_long' => $query->orderByDesc('duration'),
            'newest' => $query->orderByDesc('created_at'),
            default => $query->orderByDesc('is_featured')->orderByDesc('created_at'),
        };

        $packages = $query
            ->paginate(12)
            ->appends($request->query());

        // Récupérer les types de packages distincts pour les filtres
        $packageTypes = TourPackage::where('is_active', true)
            ->distinct()
            ->pluck('package_type')
            ->filter()
            ->values();

        // Récupérer les destinations distinctes
        $destinations = TourPackage::where('is_active', true)
            ->distinct()
            ->pluck('destination')
            ->filter()
            ->values();

        $sortOptions = [
            'featured' => 'Sélection Carré Premium',
            'price_low' => 'Prix croissant',
            'price_high' => 'Prix décroissant',
            'duration_short' => 'Durée courte',
            'duration_long' => 'Durée longue',
            'newest' => 'Nouveautés',
        ];

        $totalPackagesCount = TourPackage::where('is_active', true)->count();
        $featuredPackagesCount = TourPackage::where('is_active', true)->where('is_featured', true)->count();
        $startingPrice = TourPackage::where('is_active', true)
            ->selectRaw('MIN(COALESCE(discount_price, price)) as starting_price')
            ->value('starting_price');

        return view('pages.packages', compact(
            'packages',
            'packageTypes',
            'destinations',
            'selectedSort',
            'sortOptions',
            'totalPackagesCount',
            'featuredPackagesCount',
            'startingPrice'
        ));
    }

    /**
     * Display the specified package.
     */
    public function show($slug)
    {
        $package = TourPackage::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'reviews'])
            ->firstOrFail();

        // Get similar packages from same category
        $similarPackages = TourPackage::where('category_id', $package->category_id)
            ->where('id', '!=', $package->id)
            ->where('is_active', true)
            ->limit(3)
            ->get();

        return view('pages.package-details', compact('package', 'similarPackages'));
    }

    /**
     * Handle package booking submission.
     */
    public function book(Request $request, TourPackage $package)
    {
        // Si le package a une date fixe, la date de départ n'est pas requise dans le formulaire
        $hasFixedDate = !empty($package->event_date_start);

        $request->validate([
            'departure_date' => $hasFixedDate ? 'nullable|date' : 'required|date|after:today',
            'participants' => 'required|integer|min:' . $package->min_participants . '|max:' . $package->max_participants,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        // Utiliser la date du package si elle est définie, sinon utiliser la date saisie
        $departureDate = $hasFixedDate
            ? $package->event_date_start->format('Y-m-d')
            : $request->departure_date;

        // Calculate total price
        $unitPrice = $package->discount_price ?? $package->price;
        $totalPrice = $unitPrice * $request->participants;

        // Create booking
        $booking = Booking::create([
            'booking_number' => 'PKG-' . strtoupper(Str::random(8)),
            'user_id' => auth()->check() ? auth()->id() : null,
            'booking_type' => 'package',
            'package_id' => $package->id,
            'booking_date' => now(),
            'travel_date' => $departureDate,
            'number_of_passengers' => $request->participants,
            'passenger_details' => [
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'type' => 'adult'
                ]
            ],
            'total_amount' => $totalPrice,
            'currency' => 'XOF',
            'final_amount' => $totalPrice,
            'status' => 'pending',
            'payment_status' => 'pending',
            'special_requests' => $request->special_requests,
        ]);

        // Créer l'enregistrement PackageBooking
        $packageBooking = PackageBooking::create([
            'booking_id' => $booking->id,
            'package_id' => $package->id,
            'confirmation_number' => $booking->booking_number,
            'travel_date' => $departureDate,
            'participants_count' => $request->participants,
            'participants_details' => [
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]
            ],
            'base_price' => $package->price,
            'margin_amount' => $unitPrice - $package->price,
            'final_price' => $totalPrice,
            'status' => 'pending',
            'special_requests' => $request->special_requests,
        ]);

        // Mettre à jour le booking avec la référence du package booking
        $booking->update(['package_booking_id' => $packageBooking->id]);

        // Send confirmation email
        try {
            $passengerName = $request->first_name . ' ' . $request->last_name;
            Mail::to($request->email)->send(new PackageBookingConfirmation($booking, $passengerName));
        } catch (\Exception $e) {
            // Log email error but don't fail the booking
            \Log::error('Failed to send package booking confirmation email: ' . $e->getMessage());
        }

        return redirect(app(BookingAccessService::class)->bookingRoute('payment.cinetpay.redirect', $booking))
            ->with('success', 'Votre réservation a été créée. Redirection vers le paiement sécurisé...');
    }

    /**
     * Display booking confirmation page.
     */
    public function bookingConfirmation(Request $request, Booking $booking)
    {
        app(BookingAccessService::class)->authorize($request, $booking);

        // Ensure this is a package booking
        if ($booking->booking_type !== 'package' || !$booking->package) {
            abort(404);
        }

        return view('pages.package-booking-confirmation', compact('booking'));
    }
}
