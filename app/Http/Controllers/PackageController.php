<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use App\Models\Booking;
use App\Mail\PackageBookingConfirmation;
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

        $packages = $query->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

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

        return view('pages.packages', compact('packages', 'packageTypes', 'destinations'));
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
        $request->validate([
            'departure_date' => 'required|date|after:today',
            'participants' => 'required|integer|min:' . $package->min_participants . '|max:' . $package->max_participants,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        // Calculate total price
        $unitPrice = $package->discount_price ?? $package->price;
        $totalPrice = $unitPrice * $request->participants;

        // Create booking
        $booking = Booking::create([
            'booking_reference' => 'PKG-' . strtoupper(Str::random(8)),
            'package_id' => $package->id,
            'user_name' => $request->name,
            'user_email' => $request->email,
            'user_phone' => $request->phone,
            'departure_date' => $request->departure_date,
            'participants' => $request->participants,
            'special_requests' => $request->special_requests,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'status' => 'confirmed',
            'booking_type' => 'package',
        ]);

        // Send confirmation email
        try {
            Mail::to($request->email)->send(new PackageBookingConfirmation($booking));
        } catch (\Exception $e) {
            // Log email error but don't fail the booking
            \Log::error('Failed to send package booking confirmation email: ' . $e->getMessage());
        }

        return redirect()->route('packages.booking.confirmation', $booking);
    }

    /**
     * Display booking confirmation page.
     */
    public function bookingConfirmation(Booking $booking)
    {
        // Ensure this is a package booking
        if ($booking->booking_type !== 'package' || !$booking->package) {
            abort(404);
        }

        return view('pages.package-booking-confirmation', compact('booking'));
    }
}
