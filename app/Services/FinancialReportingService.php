<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Service de Reporting Financier
 * 
 * Génère des rapports financiers détaillés pour le comptable
 * avec filtres par client, période, service, etc.
 */
class FinancialReportingService
{
    /**
     * Obtenir le chiffre d'affaires total
     * 
     * @param string $startDate
     * @param string $endDate
     * @param array $filters
     * @return array
     */
    public function getTotalRevenue($startDate, $endDate, $filters = [])
    {
        $query = Payment::where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate]);

        // Appliquer les filtres
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['booking_type'])) {
            $query->whereHas('booking', function($q) use ($filters) {
                $q->where('booking_type', $filters['booking_type']);
            });
        }

        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        $totalRevenue = $query->sum('amount');
        $totalTransactions = $query->count();
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        return [
            'total_revenue' => $totalRevenue,
            'total_transactions' => $totalTransactions,
            'average_transaction' => $averageTransaction,
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ];
    }

    /**
     * Chiffre d'affaires par service
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getRevenueByService($startDate, $endDate)
    {
        $results = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->select(
                'bookings.booking_type as service',
                DB::raw('SUM(payments.amount) as total_revenue'),
                DB::raw('COUNT(payments.id) as transaction_count'),
                DB::raw('AVG(payments.amount) as average_amount')
            )
            ->where('payments.status', 'completed')
            ->whereBetween('payments.payment_date', [$startDate, $endDate])
            ->groupBy('bookings.booking_type')
            ->get();

        $data = [];
        $labels = [
            'flight' => 'Vols',
            'event' => 'Événements',
            'package' => 'Packages Touristiques'
        ];

        foreach ($results as $result) {
            $data[] = [
                'service' => $labels[$result->service] ?? $result->service,
                'service_code' => $result->service,
                'total_revenue' => (float)$result->total_revenue,
                'transaction_count' => $result->transaction_count,
                'average_amount' => (float)$result->average_amount,
                'percentage' => 0 // Sera calculé après
            ];
        }

        // Calculer les pourcentages
        $totalRevenue = array_sum(array_column($data, 'total_revenue'));
        foreach ($data as &$item) {
            $item['percentage'] = $totalRevenue > 0 
                ? round(($item['total_revenue'] / $totalRevenue) * 100, 2) 
                : 0;
        }

        return $data;
    }

    /**
     * Chiffre d'affaires par client
     * 
     * @param string $startDate
     * @param string $endDate
     * @param int $limit
     * @return array
     */
    public function getRevenueByClient($startDate, $endDate, $limit = 10)
    {
        $results = DB::table('payments')
            ->join('users', 'payments.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                DB::raw('SUM(payments.amount) as total_spent'),
                DB::raw('COUNT(payments.id) as transaction_count'),
                DB::raw('AVG(payments.amount) as average_transaction')
            )
            ->where('payments.status', 'completed')
            ->whereBetween('payments.payment_date', [$startDate, $endDate])
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit($limit)
            ->get();

        return $results->map(function($result) {
            return [
                'user_id' => $result->id,
                'name' => $result->first_name . ' ' . $result->last_name,
                'email' => $result->email,
                'total_spent' => (float)$result->total_spent,
                'transaction_count' => $result->transaction_count,
                'average_transaction' => (float)$result->average_transaction
            ];
        })->toArray();
    }

    /**
     * Chiffre d'affaires par période (jour, semaine, mois)
     * 
     * @param string $startDate
     * @param string $endDate
     * @param string $groupBy (day, week, month)
     * @return array
     */
    public function getRevenueByPeriod($startDate, $endDate, $groupBy = 'day')
    {
        $dateFormat = match($groupBy) {
            'day' => '%Y-%m-%d',
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            'year' => '%Y',
            default => '%Y-%m-%d'
        };

        $results = DB::table('payments')
            ->select(
                DB::raw("DATE_FORMAT(payment_date, '{$dateFormat}') as period"),
                DB::raw('SUM(amount) as revenue'),
                DB::raw('COUNT(id) as transaction_count')
            )
            ->where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return $results->map(function($result) use ($groupBy) {
            return [
                'period' => $result->period,
                'period_label' => $this->formatPeriodLabel($result->period, $groupBy),
                'revenue' => (float)$result->revenue,
                'transaction_count' => $result->transaction_count
            ];
        })->toArray();
    }

    /**
     * Statistiques de réservations
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getBookingStatistics($startDate, $endDate)
    {
        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate]);

        return [
            'total_bookings' => $bookings->count(),
            'confirmed_bookings' => (clone $bookings)->where('status', 'confirmed')->count(),
            'pending_bookings' => (clone $bookings)->where('status', 'pending')->count(),
            'cancelled_bookings' => (clone $bookings)->where('status', 'cancelled')->count(),
            'completed_bookings' => (clone $bookings)->where('status', 'completed')->count(),
            'total_passengers' => (clone $bookings)->sum('number_of_passengers'),
            'by_type' => (clone $bookings)
                ->select('booking_type', DB::raw('count(*) as count'))
                ->groupBy('booking_type')
                ->get()
                ->mapWithKeys(function($item) {
                    return [$item->booking_type => $item->count];
                }),
            'by_status' => (clone $bookings)
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->mapWithKeys(function($item) {
                    return [$item->status => $item->count];
                })
        ];
    }

    /**
     * Top clients
     * 
     * @param string $startDate
     * @param string $endDate
     * @param int $limit
     * @return array
     */
    public function getTopClients($startDate, $endDate, $limit = 10)
    {
        return $this->getRevenueByClient($startDate, $endDate, $limit);
    }

    /**
     * Taux de conversion (réservations confirmées / total)
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getConversionRate($startDate, $endDate)
    {
        $totalBookings = Booking::whereBetween('created_at', [$startDate, $endDate])->count();
        $confirmedBookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();

        $conversionRate = $totalBookings > 0 
            ? round(($confirmedBookings / $totalBookings) * 100, 2) 
            : 0;

        return [
            'total_bookings' => $totalBookings,
            'confirmed_bookings' => $confirmedBookings,
            'conversion_rate' => $conversionRate,
            'cancelled_bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'cancelled')
                ->count()
        ];
    }

    /**
     * Panier moyen
     * 
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public function getAverageBasket($startDate, $endDate)
    {
        return Payment::where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->avg('amount') ?? 0;
    }

    /**
     * Rapport complet
     * 
     * @param string $startDate
     * @param string $endDate
     * @param array $filters
     * @return array
     */
    public function getCompleteReport($startDate, $endDate, $filters = [])
    {
        return [
            'summary' => $this->getTotalRevenue($startDate, $endDate, $filters),
            'by_service' => $this->getRevenueByService($startDate, $endDate),
            'by_period' => $this->getRevenueByPeriod($startDate, $endDate, $filters['group_by'] ?? 'day'),
            'top_clients' => $this->getTopClients($startDate, $endDate, $filters['top_limit'] ?? 10),
            'booking_stats' => $this->getBookingStatistics($startDate, $endDate),
            'conversion' => $this->getConversionRate($startDate, $endDate),
            'average_basket' => $this->getAverageBasket($startDate, $endDate),
            'payment_methods' => $this->getRevenueByPaymentMethod($startDate, $endDate),
            'generated_at' => now()->toDateTimeString()
        ];
    }

    /**
     * Chiffre d'affaires par méthode de paiement
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getRevenueByPaymentMethod($startDate, $endDate)
    {
        $results = Payment::select(
                'payment_method',
                DB::raw('SUM(amount) as total_revenue'),
                DB::raw('COUNT(id) as transaction_count')
            )
            ->where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('payment_method')
            ->get();

        $labels = [
            'credit_card' => 'Carte de Crédit',
            'mobile_money' => 'Mobile Money',
            'bank_transfer' => 'Virement Bancaire',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe'
        ];

        return $results->map(function($result) use ($labels) {
            return [
                'method' => $labels[$result->payment_method] ?? $result->payment_method,
                'method_code' => $result->payment_method,
                'total_revenue' => (float)$result->total_revenue,
                'transaction_count' => $result->transaction_count
            ];
        })->toArray();
    }

    /**
     * Détails des transactions
     * 
     * @param string $startDate
     * @param string $endDate
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getTransactionDetails($startDate, $endDate, $filters = [], $perPage = 50)
    {
        $query = Payment::with(['booking', 'user'])
            ->whereBetween('payment_date', [$startDate, $endDate]);

        // Filtres
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (!empty($filters['booking_type'])) {
            $query->whereHas('booking', function($q) use ($filters) {
                $q->where('booking_type', $filters['booking_type']);
            });
        }

        return $query->orderBy('payment_date', 'desc')->paginate($perPage);
    }

    /**
     * Formater le label de période
     * 
     * @param string $period
     * @param string $groupBy
     * @return string
     */
    protected function formatPeriodLabel($period, $groupBy)
    {
        try {
            switch ($groupBy) {
                case 'day':
                    return Carbon::parse($period)->format('d/m/Y');
                case 'week':
                    list($year, $week) = explode('-', $period);
                    return "Semaine $week, $year";
                case 'month':
                    return Carbon::parse($period . '-01')->format('F Y');
                case 'year':
                    return $period;
                default:
                    return $period;
            }
        } catch (\Exception $e) {
            return $period;
        }
    }

    /**
     * Générer un rapport PDF
     * 
     * @param array $reportData
     * @param string $title
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generatePDFReport($reportData, $title = 'Rapport Financier')
    {
        $pdf = Pdf::loadView('admin.reports.pdf', [
            'title' => $title,
            'data' => $reportData,
            'generated_at' => now()->format('d/m/Y H:i:s')
        ]);

        return $pdf;
    }

    /**
     * Statistiques par devise
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getRevenueByCurrency($startDate, $endDate)
    {
        $results = Payment::select(
                'currency',
                DB::raw('SUM(amount) as total_revenue'),
                DB::raw('COUNT(id) as transaction_count')
            )
            ->where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('currency')
            ->get();

        return $results->map(function($result) {
            return [
                'currency' => $result->currency,
                'total_revenue' => (float)$result->total_revenue,
                'transaction_count' => $result->transaction_count
            ];
        })->toArray();
    }

    /**
     * Analyse des tendances (comparaison avec période précédente)
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getTrendAnalysis($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $daysDiff = $start->diffInDays($end);

        // Période actuelle
        $currentRevenue = Payment::where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        // Période précédente (même durée)
        $previousStart = $start->copy()->subDays($daysDiff + 1);
        $previousEnd = $start->copy()->subDay();

        $previousRevenue = Payment::where('status', 'completed')
            ->whereBetween('payment_date', [$previousStart, $previousEnd])
            ->sum('amount');

        $change = $previousRevenue > 0 
            ? round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 2)
            : 0;

        return [
            'current_period' => [
                'start' => $startDate,
                'end' => $endDate,
                'revenue' => $currentRevenue
            ],
            'previous_period' => [
                'start' => $previousStart->format('Y-m-d'),
                'end' => $previousEnd->format('Y-m-d'),
                'revenue' => $previousRevenue
            ],
            'change_percentage' => $change,
            'trend' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'stable')
        ];
    }

    /**
     * Rapport des remboursements
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getRefundReport($startDate, $endDate)
    {
        $refunds = Payment::where('status', 'refunded')
            ->whereBetween('refund_date', [$startDate, $endDate]);

        return [
            'total_refunds' => $refunds->count(),
            'total_refund_amount' => $refunds->sum('refund_amount'),
            'by_service' => $refunds->with('booking')
                ->get()
                ->groupBy('booking.booking_type')
                ->map(function($group) {
                    return [
                        'count' => $group->count(),
                        'amount' => $group->sum('refund_amount')
                    ];
                })
        ];
    }

    /**
     * KPIs (Key Performance Indicators)
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getKPIs($startDate, $endDate)
    {
        $payments = Payment::where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate]);

        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate]);

        return [
            'total_revenue' => $payments->sum('amount'),
            'total_transactions' => $payments->count(),
            'total_bookings' => $bookings->count(),
            'total_customers' => $payments->distinct('user_id')->count('user_id'),
            'average_basket' => $payments->avg('amount'),
            'conversion_rate' => $this->getConversionRate($startDate, $endDate)['conversion_rate'],
            'total_passengers' => $bookings->sum('number_of_passengers'),
            'revenue_per_passenger' => $bookings->sum('number_of_passengers') > 0
                ? $payments->sum('amount') / $bookings->sum('number_of_passengers')
                : 0
        ];
    }

    /**
     * Exporter en Excel
     * 
     * @param array $data
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportToExcel($data, $filename = 'rapport_financier')
    {
        // Note: Nécessite maatwebsite/excel
        // Pour l'instant, retourner les données en CSV
        return $this->exportToCSV($data, $filename);
    }

    /**
     * Exporter en CSV
     * 
     * @param array $data
     * @param string $filename
     * @return string
     */
    public function exportToCSV($data, $filename = 'rapport_financier')
    {
        $csv = fopen('php://temp', 'r+');
        
        // En-têtes
        if (!empty($data)) {
            fputcsv($csv, array_keys($data[0]));
            
            // Données
            foreach ($data as $row) {
                fputcsv($csv, $row);
            }
        }
        
        rewind($csv);
        $output = stream_get_contents($csv);
        fclose($csv);
        
        return $output;
    }
}
