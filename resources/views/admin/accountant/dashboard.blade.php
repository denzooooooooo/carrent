@extends('admin.layouts.app')

@section('title', 'Dashboard Comptable')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Dashboard Comptable</h1>
            <p class="text-muted">Vue d'ensemble des finances et réservations</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.accountant.reports') }}" class="btn btn-outline-primary">
                <i class="fas fa-chart-line mr-2"></i>Rapports
            </a>
            <a href="{{ route('admin.accountant.bookings') }}" class="btn btn-outline-success">
                <i class="fas fa-ticket-alt mr-2"></i>Réservations
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Chiffre d'Affaires Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalRevenue, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                CA du Mois
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($monthlyRevenue, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Réservations
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalBookings, 0, ',', ' ') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmed Bookings -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Réservations Confirmées
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($confirmedBookings, 0, ',', ' ') }}
                            </div>
                            <div class="text-xs text-muted">
                                {{ $totalBookings > 0 ? round(($confirmedBookings / $totalBookings) * 100, 1) : 0 }}% du total
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Évolution du Chiffre d'Affaires (12 derniers mois)</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>

        <!-- Revenue Breakdown -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Répartition des Revenus</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueBreakdownChart" width="100%" height="200"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-sm">Packages</span>
                            <span class="font-weight-bold">{{ number_format($packageRevenue ?? 0, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar"
                                 style="width: {{ ($totalRevenue > 0) ? (($packageRevenue ?? 0) / $totalRevenue) * 100 : 0 }}%"
                                 aria-valuenow="{{ ($packageRevenue ?? 0) / $totalRevenue * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-sm">Événements</span>
                            <span class="font-weight-bold">{{ number_format($eventRevenue ?? 0, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ ($totalRevenue > 0) ? (($eventRevenue ?? 0) / $totalRevenue) * 100 : 0 }}%"
                                 aria-valuenow="{{ ($eventRevenue ?? 0) / $totalRevenue * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Transactions Récentes</h6>
            <a href="{{ route('admin.accountant.bookings') }}" class="btn btn-sm btn-outline-primary">
                Voir Tout
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Produit</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                        <tr>
                            <td>
                                <span class="badge badge-{{ $booking['type'] === 'package' ? 'primary' : 'success' }}">
                                    {{ ucfirst($booking['type']) }}
                                </span>
                            </td>
                            <td class="font-weight-bold">{{ $booking['title'] }}</td>
                            <td>{{ $booking['user'] }}</td>
                            <td class="font-weight-bold text-success">
                                {{ number_format($booking['amount'], 0, ',', ' ') }} FCFA
                            </td>
                            <td>
                                <span class="badge badge-{{ $booking['status'] === 'confirmed' ? 'success' : ($booking['status'] === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($booking['status']) }}
                                </span>
                            </td>
                            <td class="text-muted">{{ $booking['date']->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Aucune transaction récente
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($monthlyRevenue);

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => item.month),
            datasets: [{
                label: 'Chiffre d\'Affaires (FCFA)',
                data: revenueData.map(item => item.revenue),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Évolution Mensuelle du CA'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' FCFA';
                        }
                    }
                }
            }
        }
    });

    // Revenue Breakdown Chart
    const breakdownCtx = document.getElementById('revenueBreakdownChart').getContext('2d');
    const packageRevenue = {{ $packageRevenue ?? 0 }};
    const eventRevenue = {{ $eventRevenue ?? 0 }};

    new Chart(breakdownCtx, {
        type: 'doughnut',
        data: {
            labels: ['Packages', 'Événements'],
            datasets: [{
                data: [packageRevenue, eventRevenue],
                backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(75, 192, 192)'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endpush
@endsection
