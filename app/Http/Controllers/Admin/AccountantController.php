<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\EventBooking;
use App\Models\Package;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountantController extends Controller
{

    /**
     * Display financial reports
     */
    public function dashboard(Request $request)
    {
        // Total revenue
        $totalRevenue = Booking::where('status', 'confirmed')
                              ->sum('total_amount') +
                      EventBooking::where('status', 'confirmed')
                                 ->sum('total_price');

        // Monthly revenue
        $monthlyRevenue = Booking::where('status', 'confirmed')
                                ->whereBetween('created_at', [
                                    Carbon::now()->startOfMonth(),
                                    Carbon::now()->endOfMonth()
                                ])
                                ->sum('total_amount') +
                        EventBooking::where('status', 'confirmed')
                                   ->whereBetween('created_at', [
                                       Carbon::now()->startOfMonth(),
                                       Carbon::now()->endOfMonth()
                                   ])
                                   ->sum('total_price');

        // Total bookings
        $totalBookings = Booking::count() + EventBooking::count();

        // Confirmed bookings
        $confirmedBookings = Booking::where('status', 'confirmed')->count() +
                           EventBooking::where('status', 'confirmed')->count();

        // Package and event revenue breakdown
        $packageRevenue = Booking::where('status', 'confirmed')
                                ->sum('total_amount');

        $eventRevenue = EventBooking::where('status', 'confirmed')
                                   ->sum('total_price');

        // Monthly revenue trend for charts
        $monthlyRevenueData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->startOfMonth()->format('Y-m-d');
            $monthEnd = $date->endOfMonth()->format('Y-m-d');

            $revenue = Booking::where('status', 'confirmed')
                             ->whereBetween('created_at', [$monthStart, $monthEnd])
                             ->sum('total_amount') +
                      EventBooking::where('status', 'confirmed')
                                 ->whereBetween('created_at', [$monthStart, $monthEnd])
                                 ->sum('total_price');

            $monthlyRevenueData->push([
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ]);
        }

        // Recent bookings
        $recentBookings = collect();

        // Recent package bookings
        $packageBookings = Booking::with(['package', 'user'])
                                 ->latest()
                                 ->take(5)
                                 ->get()
                                 ->map(function ($booking) {
                                     return [
                                         'type' => 'package',
                                         'title' => $booking->package->title_fr ?? 'Package',
                                         'user' => $booking->user->name ?? 'Utilisateur',
                                         'amount' => $booking->total_amount,
                                         'status' => $booking->status,
                                         'date' => $booking->created_at
                                     ];
                                 });

        // Recent event bookings
        $eventBookings = EventBooking::with(['event', 'user'])
                                    ->latest()
                                    ->take(5)
                                    ->get()
                                    ->map(function ($booking) {
                                        return [
                                            'type' => 'event',
                                            'title' => $booking->event->title_fr ?? 'Événement',
                                            'user' => $booking->user->name ?? 'Utilisateur',
                                            'amount' => $booking->total_price,
                                            'status' => $booking->status,
                                            'date' => $booking->created_at
                                        ];
                                    });

        $recentBookings = $packageBookings->concat($eventBookings)
                                         ->sortByDesc('date')
                                         ->take(10);

        return view('admin.accountant.dashboard', compact(
            'totalRevenue',
            'monthlyRevenue',
            'totalBookings',
            'confirmedBookings',
            'packageRevenue',
            'eventRevenue',
            'monthlyRevenueData',
            'recentBookings')
        );
    }

    /**
     * Display financial reports
     */
    public function reports(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if (!$startDate || !$endDate) {
            switch ($period) {
                case 'week':
                    $startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                    $endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'month':
                    $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                    $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                    break;
                case 'quarter':
                    $startDate = Carbon::now()->startOfQuarter()->format('Y-m-d');
                    $endDate = Carbon::now()->endOfQuarter()->format('Y-m-d');
                    break;
                case 'year':
                    $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
                    $endDate = Carbon::now()->endOfYear()->format('Y-m-d');
                    break;
            }
        }

        // Revenue by type
        $packageRevenue = Booking::where('status', 'confirmed')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->sum('total_amount');

        $eventRevenue = EventBooking::where('status', 'confirmed')
                                   ->whereBetween('created_at', [$startDate, $endDate])
                                   ->sum('total_price');

        // Revenue by payment method (assuming payment_method field exists)
        $revenueByPaymentMethod = collect();

        // Monthly revenue trend for the last 12 months
        $monthlyRevenue = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->startOfMonth()->format('Y-m-d');
            $monthEnd = $date->endOfMonth()->format('Y-m-d');

            $revenue = Booking::where('status', 'confirmed')
                             ->whereBetween('created_at', [$monthStart, $monthEnd])
                             ->sum('total_amount') +
                      EventBooking::where('status', 'confirmed')
                                 ->whereBetween('created_at', [$monthStart, $monthEnd])
                                 ->sum('total_price');

            $monthlyRevenue->push([
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ]);
        }

        // Top selling packages/events
        $topPackages = Booking::select('package_id', DB::raw('COUNT(*) as bookings_count'), DB::raw('SUM(total_amount) as total_revenue'))
                             ->with('package')
                             ->where('status', 'confirmed')
                             ->whereBetween('created_at', [$startDate, $endDate])
                             ->groupBy('package_id')
                             ->orderBy('total_revenue', 'desc')
                             ->take(10)
                             ->get();

        $topEvents = EventBooking::select('event_id', DB::raw('COUNT(*) as bookings_count'), DB::raw('SUM(total_price) as total_revenue'))
                                ->with('event')
                                ->where('status', 'confirmed')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->groupBy('event_id')
                                ->orderBy('total_revenue', 'desc')
                                ->take(10)
                                ->get();

        return view('admin.accountant.reports', compact(
            'period',
            'startDate',
            'endDate',
            'packageRevenue',
            'eventRevenue',
            'revenueByPaymentMethod',
            'monthlyRevenue',
            'topPackages',
            'topEvents'
        ));
    }



    /**
     * Display bookings management for accountant
     */
    public function bookings(Request $request)
    {
        \Log::info('AccountantController@bookings called', [
            'request' => $request->all(),
            'user' => auth('admin')->user()->name ?? 'unknown'
        ]);

        $query = collect();

        // Get package bookings
        $packageBookings = \App\Models\Booking::with(['package', 'user'])
            ->latest()
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'type' => 'package',
                    'title' => $booking->package->title_fr ?? 'Package touristique',
                    'user' => $booking->user->name ?? 'Utilisateur',
                    'user_email' => $booking->user->email ?? '',
                    'total_amount' => $booking->total_amount,
                    'status' => $booking->status,
                    'created_at' => $booking->created_at,
                ];
            });

        \Log::info('Package bookings fetched', ['count' => $packageBookings->count()]);

        // Get event bookings
        $eventBookings = \App\Models\EventBooking::with(['event', 'zone'])
            ->latest()
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'type' => 'event',
                    'title' => $booking->event->title_fr ?? 'Événement',
                    'user' => $booking->user_name,
                    'user_email' => $booking->user_email,
                    'total_amount' => $booking->total_price,
                    'status' => $booking->status,
                    'created_at' => $booking->created_at,
                ];
            });

        \Log::info('Event bookings fetched', ['count' => $eventBookings->count()]);

        // Combine and sort bookings
        $allBookings = $packageBookings->concat($eventBookings)
            ->sortByDesc('created_at')
            ->values();

        \Log::info('All bookings combined', ['total_count' => $allBookings->count()]);

        // Apply filters
        if ($request->filled('status') && $request->status !== 'all') {
            $allBookings = $allBookings->where('status', $request->status);
            \Log::info('Applied status filter', ['status' => $request->status, 'filtered_count' => $allBookings->count()]);
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $allBookings = $allBookings->where('type', $request->type);
            \Log::info('Applied type filter', ['type' => $request->type, 'filtered_count' => $allBookings->count()]);
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $allBookings = $allBookings->filter(function ($booking) use ($search) {
                return str_contains(strtolower($booking['title']), $search) ||
                       str_contains(strtolower($booking['user']), $search) ||
                       str_contains(strtolower($booking['user_email']), $search);
            });
            \Log::info('Applied search filter', ['search' => $request->search, 'filtered_count' => $allBookings->count()]);
        }

        // Paginate manually
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $total = $allBookings->count();
        $bookings = $allBookings->forPage($currentPage, $perPage);

        \Log::info('Final bookings for page', [
            'page' => $currentPage,
            'per_page' => $perPage,
            'total' => $total,
            'bookings_count' => $bookings->count()
        ]);

        return view('admin.accountant.bookings', compact('bookings', 'total', 'perPage', 'currentPage'));
    }

    /**
     * Update booking status
     */
    public function updateBookingStatus(Request $request, $type, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,refunded'
        ]);

        if ($type === 'package') {
            $booking = \App\Models\Booking::findOrFail($id);
            $booking->update(['status' => $request->status]);
        } elseif ($type === 'event') {
            $booking = \App\Models\EventBooking::findOrFail($id);
            $booking->update(['status' => $request->status]);
        }

        return redirect()->back()->with('success', 'Statut de la réservation mis à jour avec succès.');
    }

    /**
     * Display payment gateways management
     */
    public function paymentGateways()
    {
        // Return the accountant payment gateways view
        return view('admin.accountant.payment-gateways');
    }


}
