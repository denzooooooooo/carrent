<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View; // Ajout pour la clarté du type de retour

class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion de l'administrateur.
     * Cette méthode est ciblée par la route protégée par 'guest:admin'.
     */
    public function showLoginForm(): View
    {
        // Assurez-vous que cette vue existe : resources/views/admin/auth/login.blade.php
        return view('admin.login');
    }

    /**
     * Gère la tentative de connexion de l'administrateur.
     */
    public function login(Request $request)
    {
        // 1. Validation des champs (email et mot de passe)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Tentative de connexion en utilisant le garde 'admin'
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            // Régénère la session pour prévenir les attaques de fixation de session
            $request->session()->regenerate();

            // 🕒 Mettre à jour le champ last_login
            $admin = Auth::guard('admin')->user();
            $admin->update([
                'last_login' => now(),
            ]);

            // Succès : Redirige vers la destination prévue ou le tableau de bord (admin.dashboard)
            return redirect()->intended(route('admin.dashboard'));
        }

        // 3. Échec de l'authentification
        // Lève une exception de validation avec un message d'erreur pour l'email
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')], // Utilisez le message de traduction par défaut de Laravel
        ]);
    }

    /**
     * Affiche le tableau de bord de l'administrateur.
     * Cette méthode est protégée par le middleware 'auth:admin' dans les routes.
     */
    /* public function dashboard(): View
    {
        // Assurez-vous que cette vue existe : resources/views/admin/dashboard/index.blade.php
        return view('admin.dashboard');
    } */
    /**
     * Affiche le tableau de bord de l'administrateur avec des données réelles.
     */
    public function dashboard(): View
    {
        // Import des modèles nécessaires
        $bookingModel = \App\Models\Booking::class;
        $userModel = \App\Models\User::class;
        $eventModel = \App\Models\Event::class;
        $packageModel = \App\Models\TourPackage::class;
        $reviewModel = \App\Models\Review::class;

        // Dates pour les calculs
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $sevenDaysAgo = Carbon::now()->subDays(7);

        // Statistiques principales
        $stats = [
            // Réservations aujourd'hui
            'bookings_today' => $bookingModel::whereDate('created_at', $today)->count(),
            'bookings_week' => $bookingModel::where('created_at', '>=', $startOfWeek)->count(),

            // Revenus (conversion en XOF)
            'revenue_today' => $this->convertRevenue($bookingModel::whereDate('created_at', $today)->sum('final_amount')),
            'revenue_month' => $this->convertRevenue($bookingModel::where('created_at', '>=', $startOfMonth)->sum('final_amount')),

            // Utilisateurs
            'new_users_today' => $userModel::whereDate('created_at', $today)->count(),
            'total_users' => $userModel::count(),

            // Statuts des réservations
            'pending_bookings' => $bookingModel::where('status', 'pending')->count(),
            'pending_reviews' => 0, // Temporairement à 0 car la table reviews n'a pas de colonne status

            // Réservations par type
            'flight_bookings_total' => $bookingModel::where('booking_type', 'flight')->count(),
            'event_tickets_sold' => $bookingModel::where('booking_type', 'event')->count(),
            'package_bookings_total' => $bookingModel::where('booking_type', 'package')->count(),

            // Note moyenne (calculée à partir des reviews)
            'average_rating' => $reviewModel::avg('rating') ?? 0,

            // Annulations
            'cancelled_bookings' => $bookingModel::where('status', 'cancelled')->count(),
        ];

        // Alertes basées sur les données réelles
        $alerts = [
            'low_stock_events' => $eventModel::where('available_seats', '<', 10)->where('is_active', true)->count(),
            'low_stock_packages' => $packageModel::where('max_participants', '>', 0)
                ->whereRaw('(SELECT COUNT(*) FROM bookings WHERE package_id = tour_packages.id AND status IN ("confirmed", "pending")) >= max_participants * 0.9')
                ->count(),
            'failed_payments' => $bookingModel::where('payment_status', 'failed')->where('created_at', '>=', $sevenDaysAgo)->count(),
        ];

        // Données pour les graphiques de revenus (12 derniers mois)
        $revenueData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $revenue = $bookingModel::whereBetween('created_at', [$monthStart, $monthEnd])
                ->whereIn('status', ['confirmed', 'completed'])
                ->sum('final_amount');

            $revenueData->push([
                'month' => $date->format('M Y'),
                'total' => $this->convertRevenue($revenue),
            ]);
        }

        // Données pour les graphiques de réservations (12 derniers mois)
        $bookingsData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $count = $bookingModel::whereBetween('created_at', [$monthStart, $monthEnd])->count();

            $bookingsData->push([
                'month' => $date->format('M Y'),
                'total' => $count,
            ]);
        }

        // Réservations par type
        $bookingsByType = collect([
            ['booking_type' => 'flight', 'count' => $stats['flight_bookings_total']],
            ['booking_type' => 'event', 'count' => $stats['event_tickets_sold']],
            ['booking_type' => 'package', 'count' => $stats['package_bookings_total']],
        ]);

        // Réservations par statut
        $bookingsByStatus = collect([
            ['status' => 'confirmed', 'count' => $bookingModel::where('status', 'confirmed')->count()],
            ['status' => 'pending', 'count' => $stats['pending_bookings']],
            ['status' => 'cancelled', 'count' => $stats['cancelled_bookings']],
            ['status' => 'completed', 'count' => $bookingModel::where('status', 'completed')->count()],
        ]);

        // Top destinations (basé sur les réservations de vols)
        $topDestinations = $bookingModel::where('booking_type', 'flight')
            ->join('flights', 'bookings.flight_id', '=', 'flights.id')
            ->join('airports as arrival', 'flights.arrival_airport_id', '=', 'arrival.id')
            ->selectRaw('arrival.municipality as destination, COUNT(*) as count')
            ->groupBy('arrival.municipality')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Statistiques des 7 derniers jours
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyStats[] = [
                'date' => $date->format('d/m'),
                'bookings' => $bookingModel::whereDate('created_at', $date)->count(),
                'users' => $userModel::whereDate('created_at', $date)->count(),
            ];
        }

        // Top événements (basé sur le nombre de réservations)
        $topEvents = $eventModel::withCount(['bookings' => function ($query) {
            $query->whereIn('status', ['confirmed', 'completed']);
        }])
        ->orderBy('bookings_count', 'desc')
        ->limit(5)
        ->get()
        ->map(function ($event) {
            return (object) [
                'title' => $event->title_fr,
                'tickets_count' => $event->bookings_count,
            ];
        });

        // Top packages (basé sur le nombre de réservations)
        $topPackages = $packageModel::withCount(['bookings' => function ($query) {
            $query->whereIn('status', ['confirmed', 'completed']);
        }])
        ->orderBy('bookings_count', 'desc')
        ->limit(5)
        ->get()
        ->map(function ($package) {
            return (object) [
                'title' => $package->title_fr,
                'bookings_count' => $package->bookings_count,
            ];
        });

        // Utilisateurs récents
        $recentUsers = $userModel::select('first_name', 'last_name', 'email', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($user) {
                return (object) [
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                ];
            });

        // Réservations récentes
        $recentBookings = $bookingModel::with('user')
            ->select('booking_number', 'booking_type', 'final_amount', 'status', 'created_at', 'user_id')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($booking) {
                return (object) [
                    'booking_number' => $booking->booking_number,
                    'user' => (object) ['name' => $booking->user ? $booking->user->first_name . ' ' . $booking->user->last_name : 'N/A'],
                    'booking_type' => $booking->booking_type,
                    'final_amount' => $this->convertRevenue($booking->final_amount),
                    'status' => $booking->status,
                    'created_at' => $booking->created_at,
                ];
            });

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

    /**
     * Convertit le revenu dans la devise actuelle de l'utilisateur
     */
    private function convertRevenue($amount)
    {
        return \App\Helpers\CurrencyHelper::convert($amount, 'XOF', session('currency', 'XOF'));
    }

    /**
     * Déconnecte l'administrateur.
     */
    public function logout(Request $request)
    {
        // Déconnecte uniquement l'utilisateur du garde 'admin'
        Auth::guard('admin')->logout();

        // Invalide et régénère le jeton de session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirige vers la page de connexion de l'administrateur
        return redirect()->route('admin.login');
    }
}
