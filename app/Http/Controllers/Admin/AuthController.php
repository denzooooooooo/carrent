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
     * Affiche le tableau de bord de l'administrateur avec des données statiques.
     */
    public function dashboard(): View
    {
        // Données statiques pour simuler une plateforme active
        $stats = [
            'bookings_today' => 42,
            'bookings_week' => 287,
            'revenue_today' => 1250000,
            'revenue_month' => 28500000,
            'new_users_today' => 23,
            'total_users' => 1542,
            'pending_bookings' => 8,
            'pending_reviews' => 12,
            'flight_bookings_total' => 156,
            'event_tickets_sold' => 89,
            'package_bookings_total' => 67,
            'average_rating' => 4.7,
            'cancelled_bookings' => 5,
        ];

        // Alertes statiques
        $alerts = [
            'low_stock_events' => 2,
            'low_stock_packages' => 1,
            'failed_payments' => 3,
        ];

        // Données pour les graphiques de revenus (12 derniers mois)
        $revenueData = collect([
            ['month' => 'Jan 2024', 'total' => 1850000],
            ['month' => 'Fév 2024', 'total' => 2100000],
            ['month' => 'Mar 2024', 'total' => 1980000],
            ['month' => 'Avr 2024', 'total' => 2450000],
            ['month' => 'Mai 2024', 'total' => 2780000],
            ['month' => 'Jun 2024', 'total' => 3120000],
            ['month' => 'Jul 2024', 'total' => 3450000],
            ['month' => 'Aoû 2024', 'total' => 2980000],
            ['month' => 'Sep 2024', 'total' => 3250000],
            ['month' => 'Oct 2024', 'total' => 3560000],
            ['month' => 'Nov 2024', 'total' => 3850000],
            ['month' => 'Déc 2024', 'total' => 4120000],
        ]);

        // Données pour les graphiques de réservations (12 derniers mois)
        $bookingsData = collect([
            ['month' => 'Jan 2024', 'total' => 45],
            ['month' => 'Fév 2024', 'total' => 52],
            ['month' => 'Mar 2024', 'total' => 48],
            ['month' => 'Avr 2024', 'total' => 65],
            ['month' => 'Mai 2024', 'total' => 72],
            ['month' => 'Jun 2024', 'total' => 78],
            ['month' => 'Jul 2024', 'total' => 85],
            ['month' => 'Aoû 2024', 'total' => 76],
            ['month' => 'Sep 2024', 'total' => 82],
            ['month' => 'Oct 2024', 'total' => 88],
            ['month' => 'Nov 2024', 'total' => 92],
            ['month' => 'Déc 2024', 'total' => 98],
        ]);

        // Réservations par type
        $bookingsByType = collect([
            ['booking_type' => 'flight', 'count' => 156],
            ['booking_type' => 'event', 'count' => 89],
            ['booking_type' => 'package', 'count' => 67],
        ]);

        // Réservations par statut
        $bookingsByStatus = collect([
            ['status' => 'confirmed', 'count' => 285],
            ['status' => 'pending', 'count' => 8],
            ['status' => 'cancelled', 'count' => 5],
            ['status' => 'completed', 'count' => 312],
        ]);

        // Top destinations
        $topDestinations = collect([
            (object) ['destination' => 'Paris', 'count' => 45],
            (object) ['destination' => 'Dakar', 'count' => 38],
            (object) ['destination' => 'Abidjan', 'count' => 32],
            (object) ['destination' => 'Lomé', 'count' => 28],
            (object) ['destination' => 'Bamako', 'count' => 25],
        ]);

        // Statistiques des 7 derniers jours
        $dailyStats = [
            ['date' => '01/01', 'bookings' => 35, 'users' => 12],
            ['date' => '02/01', 'bookings' => 42, 'users' => 18],
            ['date' => '03/01', 'bookings' => 38, 'users' => 15],
            ['date' => '04/01', 'bookings' => 45, 'users' => 20],
            ['date' => '05/01', 'bookings' => 52, 'users' => 22],
            ['date' => '06/01', 'bookings' => 48, 'users' => 19],
            ['date' => '07/01', 'bookings' => 42, 'users' => 16],
        ];

        // Top événements
        $topEvents = collect([
            (object) ['title' => 'Festival Jazz de Saint-Louis', 'tickets_count' => 156],
            (object) ['title' => 'Concert Youssou N\'Dour', 'tickets_count' => 142],
            (object) ['title' => 'Semaine de la Mode Dakar', 'tickets_count' => 128],
            (object) ['title' => 'Festival des Arts Nègres', 'tickets_count' => 115],
            (object) ['title' => 'Gala des Entrepreneurs', 'tickets_count' => 98],
        ]);

        // Top packages
        $topPackages = collect([
            (object) ['title' => 'Package Découverte Sénégal', 'bookings_count' => 67],
            (object) ['title' => 'Tour Culturel Côte d\'Ivoire', 'bookings_count' => 54],
            (object) ['title' => 'Aventure Mali', 'bookings_count' => 48],
            (object) ['title' => 'Escapade Togo', 'bookings_count' => 42],
            (object) ['title' => 'Expérience Ghana', 'bookings_count' => 38],
        ]);

        // Utilisateurs récents simulés
        $recentUsers = collect([
            (object) [
                'name' => 'Marie Diop',
                'email' => 'marie.diop@example.com',
                'created_at' => Carbon::now()->subHours(2)
            ],
            (object) [
                'name' => 'Jean Traoré',
                'email' => 'jean.traore@example.com',
                'created_at' => Carbon::now()->subHours(5)
            ],
            (object) [
                'name' => 'Aïcha Koné',
                'email' => 'aicha.kone@example.com',
                'created_at' => Carbon::now()->subDays(1)
            ],
            (object) [
                'name' => 'Paul Ndiaye',
                'email' => 'paul.ndiaye@example.com',
                'created_at' => Carbon::now()->subDays(1)
            ],
            (object) [
                'name' => 'Fatou Sow',
                'email' => 'fatou.sow@example.com',
                'created_at' => Carbon::now()->subDays(2)
            ],
        ]);

        // Réservations récentes simulées
        $recentBookings = collect([
            (object) [
                'booking_number' => 'FB-001245',
                'user' => (object) ['name' => 'Marie Diop'],
                'booking_type' => 'flight',
                'final_amount' => 245000,
                'status' => 'confirmed',
                'created_at' => Carbon::now()->subHours(1)
            ],
            (object) [
                'booking_number' => 'ET-008976',
                'user' => (object) ['name' => 'Jean Traoré'],
                'booking_type' => 'event',
                'final_amount' => 75000,
                'status' => 'confirmed',
                'created_at' => Carbon::now()->subHours(3)
            ],
            (object) [
                'booking_number' => 'PB-003421',
                'user' => (object) ['name' => 'Aïcha Koné'],
                'booking_type' => 'package',
                'final_amount' => 450000,
                'status' => 'pending',
                'created_at' => Carbon::now()->subHours(6)
            ],
            (object) [
                'booking_number' => 'FB-001246',
                'user' => (object) ['name' => 'Paul Ndiaye'],
                'booking_type' => 'flight',
                'final_amount' => 320000,
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(1)
            ],
            (object) [
                'booking_number' => 'ET-008977',
                'user' => (object) ['name' => 'Fatou Sow'],
                'booking_type' => 'event',
                'final_amount' => 50000,
                'status' => 'confirmed',
                'created_at' => Carbon::now()->subDays(1)
            ],
        ]);

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
