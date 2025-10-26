<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;
use App\Models\Booking;
use App\Models\FlightBooking;
use App\Models\PackageBooking;
use App\Models\Event;
use App\Models\TourPackage;
use App\Models\Payment;
use App\Models\EventTicket;
use App\Models\Review;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        // Statistiques principales
        $stats = [
            'bookings_today' => Booking::whereDate('created_at', Carbon::today())->count() +
                               FlightBooking::whereDate('created_at', Carbon::today())->count() +
                               PackageBooking::whereDate('created_at', Carbon::today())->count(),
            'bookings_week' => Booking::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count() +
                              FlightBooking::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count() +
                              PackageBooking::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'revenue_today' => Payment::where('status', 'completed')
                                     ->whereDate('created_at', Carbon::today())
                                     ->sum('amount'),
            'revenue_month' => Payment::where('status', 'completed')
                                     ->whereYear('created_at', Carbon::now()->year)
                                     ->whereMonth('created_at', Carbon::now()->month)
                                     ->sum('amount'),
            'new_users_today' => User::whereDate('created_at', Carbon::today())->count(),
            'total_users' => User::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count() +
                                 FlightBooking::where('status', 'pending')->count() +
                                 PackageBooking::where('status', 'pending')->count(),
            'pending_reviews' => Review::where('status', 'pending')->count(),
            'flight_bookings_total' => FlightBooking::count(),
            'event_tickets_sold' => EventTicket::where('status', 'sold')->count(),
            'package_bookings_total' => PackageBooking::count(),
            'average_rating' => Review::avg('rating') ?? 0,
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count() +
                                   FlightBooking::where('status', 'cancelled')->count() +
                                   PackageBooking::where('status', 'cancelled')->count(),
        ];

        // Alertes
        $alerts = [
            'low_stock_events' => Event::where('available_tickets', '<', 10)->count(),
            'low_stock_packages' => TourPackage::where('available_slots', '<', 5)->count(),
            'failed_payments' => Payment::where('status', 'failed')
                                       ->where('created_at', '>=', Carbon::now()->subWeek())
                                       ->count(),
        ];

        // Données pour les graphiques de revenus (12 derniers mois)
        $revenueData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenueData->push([
                'month' => $date->format('M Y'),
                'total' => Payment::where('status', 'completed')
                                ->whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->sum('amount')
            ]);
        }

        // Données pour les graphiques de réservations (12 derniers mois)
        $bookingsData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $bookingsData->push([
                'month' => $date->format('M Y'),
                'total' => Booking::whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->count() +
                        FlightBooking::whereYear('created_at', $date->year)
                                   ->whereMonth('created_at', $date->month)
                                   ->count() +
                        PackageBooking::whereYear('created_at', $date->year)
                                    ->whereMonth('created_at', $date->month)
                                    ->count()
            ]);
        }

        // Réservations par type
        $bookingsByType = collect([
            ['booking_type' => 'flight', 'count' => FlightBooking::count()],
            ['booking_type' => 'event', 'count' => EventTicket::count()],
            ['booking_type' => 'package', 'count' => PackageBooking::count()],
        ]);

        // Réservations par statut
        $bookingsByStatus = collect([
            ['status' => 'confirmed', 'count' => Booking::where('status', 'confirmed')->count() +
                                                FlightBooking::where('status', 'confirmed')->count() +
                                                PackageBooking::where('status', 'confirmed')->count()],
            ['status' => 'pending', 'count' => Booking::where('status', 'pending')->count() +
                                              FlightBooking::where('status', 'pending')->count() +
                                              PackageBooking::where('status', 'pending')->count()],
            ['status' => 'cancelled', 'count' => Booking::where('status', 'cancelled')->count() +
                                                FlightBooking::where('status', 'cancelled')->count() +
                                                PackageBooking::where('status', 'cancelled')->count()],
            ['status' => 'completed', 'count' => Booking::where('status', 'completed')->count() +
                                                FlightBooking::where('status', 'completed')->count() +
                                                PackageBooking::where('status', 'completed')->count()],
        ]);

        // Top destinations (simulé avec des données d'exemple)
        $topDestinations = collect([
            (object)['destination' => 'Paris', 'count' => 45],
            (object)['destination' => 'New York', 'count' => 38],
            (object)['destination' => 'London', 'count' => 32],
            (object)['destination' => 'Tokyo', 'count' => 28],
            (object)['destination' => 'Dubai', 'count' => 25],
        ]);

        // Statistiques des 7 derniers jours
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyStats[] = [
                'date' => $date->format('d/m'),
                'bookings' => Booking::whereDate('created_at', $date)->count() +
                             FlightBooking::whereDate('created_at', $date)->count() +
                             PackageBooking::whereDate('created_at', $date)->count(),
                'users' => User::whereDate('created_at', $date)->count(),
            ];
        }

        // Top événements
        $topEvents = Event::withCount('tickets')
                          ->orderBy('tickets_count', 'desc')
                          ->take(5)
                          ->get()
                          ->map(function($event) {
                              return (object) [
                                  'title' => $event->title,
                                  'tickets_count' => $event->tickets_count ?? 0
                              ];
                          });

        // Top packages
        $topPackages = TourPackage::withCount('bookings')
                                 ->orderBy('bookings_count', 'desc')
                                 ->take(5)
                                 ->get()
                                 ->map(function($package) {
                                     return (object) [
                                         'title' => $package->title,
                                         'bookings_count' => $package->bookings_count ?? 0
                                     ];
                                 });

        // Utilisateurs récents
        $recentUsers = User::orderBy('created_at', 'desc')
                          ->take(5)
                          ->get();

        // Réservations récentes
        $recentBookings = collect();

        // Récupérer les réservations de différents types
        $flightBookings = FlightBooking::with('user')
                                      ->orderBy('created_at', 'desc')
                                      ->take(3)
                                      ->get()
                                      ->map(function($booking) {
                                          return (object) [
                                              'booking_number' => $booking->booking_number ?? 'FB-' . $booking->id,
                                              'user' => $booking->user,
                                              'booking_type' => 'flight',
                                              'final_amount' => $booking->total_amount ?? 0,
                                              'status' => $booking->status ?? 'pending',
                                              'created_at' => $booking->created_at
                                          ];
                                      });

        $eventBookings = EventTicket::with(['user', 'event'])
                                   ->orderBy('created_at', 'desc')
                                   ->take(3)
                                   ->get()
                                   ->map(function($ticket) {
                                       return (object) [
                                           'booking_number' => $ticket->ticket_number ?? 'ET-' . $ticket->id,
                                           'user' => $ticket->user,
                                           'booking_type' => 'event',
                                           'final_amount' => $ticket->price ?? 0,
                                           'status' => $ticket->status ?? 'confirmed',
                                           'created_at' => $ticket->created_at
                                       ];
                                   });

        $packageBookings = PackageBooking::with('user')
                                        ->orderBy('created_at', 'desc')
                                        ->take(3)
                                        ->get()
                                        ->map(function($booking) {
                                            return (object) [
                                                'booking_number' => $booking->booking_number ?? 'PB-' . $booking->id,
                                                'user' => $booking->user,
                                                'booking_type' => 'package',
                                                'final_amount' => $booking->total_amount ?? 0,
                                                'status' => $booking->status ?? 'pending',
                                                'created_at' => $booking->created_at
                                            ];
                                        });

        $recentBookings = $flightBookings->concat($eventBookings)->concat($packageBookings)
                                        ->sortByDesc('created_at')
                                        ->take(10);

        return view('admin.dashboard', compact(
            'stats',
            'alerts',
            'revenueData',
            'bookingsData',
            'bookingsByType',
            'bookingsByStatus',
            'topDestinations',
            'dailyStats',
            'topEvents',
            'topPackages',
            'recentUsers',
            'recentBookings'
        ));
    }

    public function realtimeStats()
    {
        return response()->json([
            'bookings_today' => Booking::whereDate('created_at', Carbon::today())->count() +
                               FlightBooking::whereDate('created_at', Carbon::today())->count() +
                               PackageBooking::whereDate('created_at', Carbon::today())->count(),
            'revenue_today' => Payment::where('status', 'completed')
                                     ->whereDate('created_at', Carbon::today())
                                     ->sum('amount'),
            'pending_bookings' => Booking::where('status', 'pending')->count() +
                                 FlightBooking::where('status', 'pending')->count() +
                                 PackageBooking::where('status', 'pending')->count(),
        ]);
    }
}
