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
     * Display the accountant dashboard
     */
    public function dashboard()
    {
        // Financial statistics
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_amount') +
                       EventBooking::where('status', 'confirmed')->sum('total_amount');

        $monthlyRevenue = Booking::where('status', 'confirmed')
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->sum('total_amount') +
                       EventBooking::where('status', 'confirmed')
                                  ->whereMonth('created_at', Carbon::now()->month)
                                  ->whereYear('created_at', Carbon::now()->year)
                                  ->sum('total_amount');

        $totalBookings = Booking::count() + EventBooking::count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count() +
                           EventBooking::where('status', 'confirmed')->count();

        $pendingPayments = Booking::where('status', 'pending')->count() +
                          EventBooking::where('status', 'pending')->count();

        // Recent transactions
        $recentBookings = collect();

        $packageBookings = Booking::with(['user', 'package'])
                                 ->latest()
                                 ->take(5)
                                 ->get()
                                 ->map(function($booking) {
                                     return [
                                         'id' => $booking->id,
                                         'type' => 'package',
                                         'title' => $booking->package->title_fr ?? 'Package',
                                         'user' => $booking->user->name ?? 'N/A',
                                         'amount' => $booking->total_amount,
                                         'status' => $booking->status,
                                         'date' => $booking->created_at
                                     ];
                                 });

        $eventBookings = EventBooking::with(['user', 'event'])
                                    ->latest()
                                    ->take(5)
                                    ->get()
                                    ->map(function($booking) {
                                        return [
                                            'id' => $booking->id,
                                            'type' => 'event',
                                            'title' => $booking->event->title_fr ?? 'Événement',
                                            'user' => $booking->user->name ?? 'N/A',
                                            'amount' => $booking->total_amount,
                                            'status' => $booking->status,
                                            'date' => $booking->created_at
                                        ];
                                    });

        $recentBookings = $packageBookings->concat($eventBookings)->sortByDesc('date')->take(10);

        return view('admin.accountant.dashboard', compact(
            'totalRevenue',
            'monthlyRevenue',
            'totalBookings',
            'confirmedBookings',
            'pendingPayments',
            'recentBookings'
        ));
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
                                   ->sum('total_amount');

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
                                 ->sum('total_amount');

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

        $topEvents = EventBooking::select('event_id', DB::raw('COUNT(*) as bookings_count'), DB::raw('SUM(total_amount) as total_revenue'))
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
     * Display all bookings for accountant review
     */
    public function bookings(Request $request)
    {
        $status = $request->get('status', 'all');
        $type = $request->get('type', 'all'); // package, event, or all

        $query = collect();

        if ($type === 'all' || $type === 'package') {
            $packageBookings = Booking::with(['user', 'package'])
                                     ->when($status !== 'all', function($q) use ($status) {
                                         return $q->where('status', $status);
                                     })
                                     ->latest()
                                     ->get()
                                     ->map(function($booking) {
                                         return (object) [
                                             'id' => $booking->id,
                                             'type' => 'package',
                                             'title' => $booking->package->title_fr ?? 'Package',
                                             'user' => $booking->user,
                                             'total_amount' => $booking->total_amount,
                                             'status' => $booking->status,
                                             'created_at' => $booking->created_at,
                                             'booking' => $booking
                                         ];
                                     });
            $query = $query->concat($packageBookings);
        }

        if ($type === 'all' || $type === 'event') {
            $eventBookings = EventBooking::with(['user', 'event'])
                                        ->when($status !== 'all', function($q) use ($status) {
                                            return $q->where('status', $status);
                                        })
                                        ->latest()
                                        ->get()
                                        ->map(function($booking) {
                                            return (object) [
                                                'id' => $booking->id,
                                                'type' => 'event',
                                                'title' => $booking->event->title_fr ?? 'Événement',
                                                'user' => $booking->user,
                                                'total_amount' => $booking->total_amount,
                                                'status' => $booking->status,
                                                'created_at' => $booking->created_at,
                                                'booking' => $booking
                                            ];
                                        });
            $query = $query->concat($eventBookings);
        }

        $bookings = $query->sortByDesc('created_at')->paginate(20);

        return view('admin.accountant.bookings', compact('bookings', 'status', 'type'));
    }

    /**
     * Update booking payment status
     */
    public function updatePaymentStatus(Request $request, $type, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,refunded'
        ]);

        if ($type === 'package') {
            $booking = Booking::findOrFail($id);
        } elseif ($type === 'event') {
            $booking = EventBooking::findOrFail($id);
        } else {
            return redirect()->back()->with('error', 'Type de réservation invalide');
        }

        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Statut de paiement mis à jour avec succès');
    }

    /**
     * Display payment gateways management
     */
    public function paymentGateways()
    {
        // This would integrate with payment gateway management
        // For now, just redirect to the existing payment gateway controller
        return redirect()->route('admin.payment-gateways.index');
    }

    /**
     * Display pricing rules management
     */
    public function pricingRules()
    {
        // This would integrate with pricing rules management
        // For now, just redirect to the existing pricing rules controller
        return redirect()->route('admin.pricing-rules.index');
    }
}
