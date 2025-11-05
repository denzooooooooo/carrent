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
     * Display payment gateways management
     */
    public function paymentGateways()
    {
        // This would integrate with payment gateway management
        // For now, just redirect to the existing payment gateway controller
        return redirect()->route('admin.payment-gateways.index');
    }


}
