@extends('layouts.admin')

@section('title', 'Rapports Financiers - Comptable')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Rapports Financiers</h1>
            <p class="text-muted">Analyse détaillée des revenus et performances</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.accountant.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Retour au Dashboard
            </a>
            <button onclick="window.print()" class="btn btn-outline-primary">
                <i class="fas fa-print mr-2"></i>Imprimer
            </button>
        </div>
    </div>

    <!-- Period Selection -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-2">
                    <label for="period" class="form-label">Période</label>
                    <select name="period" id="period" class="form-control">
                        <option value="week" {{ request('period', 'month') === 'week' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="month" {{ request('period', 'month') === 'month' ? 'selected' : '' }}>Ce mois</option>
                        <option value="quarter" {{ request('period', 'month') === 'quarter' ? 'selected' : '' }}>Ce trimestre</option>
                        <option value="year" {{ request('period', 'month') === 'year' ? 'selected' : '' }}>Cette année</option>
                        <option value="custom" {{ request('period') === 'custom' ? 'selected' : '' }}>Personnalisé</option>
                    </select>
                </div>
                <div class="col-md-2 custom-dates" style="display: {{ request('period') === 'custom' ? 'block' : 'none' }};">
                    <label for="start_date" class="form-label">Date début</label>
                    <input type="date" name="start_date" id="start_date" class="form-control"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2 custom-dates" style="display: {{ request('period') === 'custom' ? 'block' : 'none' }};">
                    <label for="end_date" class="form-label">Date fin</label>
                    <input type="date" name="end_date" id="end_date" class="form-control"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-search mr-2"></i>Générer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Revenus Packages
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($packageRevenue, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-suitcase fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Revenus Événements
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($eventRevenue, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Trend -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Évolution des Revenus (12 derniers mois)</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueTrendChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>

        <!-- Revenue Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Répartition des Revenus</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueDistributionChart" width="100%" height="200"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-sm">Packages</span>
                            <span class="font-weight-bold">{{ number_format($packageRevenue, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar"
                                 style="width: {{ ($packageRevenue + $eventRevenue) > 0 ? ($packageRevenue / ($packageRevenue + $eventRevenue)) * 100 : 0 }}%"
                                 aria-valuenow="{{ ($packageRevenue + $eventRevenue) > 0 ? ($packageRevenue / ($packageRevenue + $eventRevenue)) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-sm">Événements</span>
                            <span class="font-weight-bold">{{ number_format($eventRevenue, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ ($packageRevenue + $eventRevenue) > 0 ? ($eventRevenue / ($packageRevenue + $eventRevenue)) * 100 : 0 }}%"
                                 aria-valuenow="{{ ($packageRevenue + $eventRevenue) > 0 ? ($eventRevenue / ($packageRevenue + $eventRevenue)) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="row mb-4">
        <!-- Top Packages -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 10 Packages</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Package</th>
                                    <th>Réservations</th>
                                    <th>Revenus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topPackages as $package)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $package->package->title_fr ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $package->package->title_en ?? '' }}</small>
                                    </td>
                                    <td>{{ $package->bookings_count }}</td>
                                    <td class="font-weight-bold text-success">
                                        {{ number_format($package->total_revenue, 0, ',', ' ') }} FCFA
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        Aucune donnée disponible
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Events -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 10 Événements</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Événement</th>
                                    <th>Réservations</th>
                                    <th>Revenus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topEvents as $event)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $event->event->title_fr ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $event->event->title_en ?? '' }}</small>
                                    </td>
                                    <td>{{ $event->bookings_count }}</td>
                                    <td class="font-weight-bold text-success">
                                        {{ number_format($event->total_revenue, 0, ',', ' ') }} FCFA
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        Aucune donnée disponible
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Report -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Rapport Détaillé</h6>
            <div class="d-flex gap-2">
                <button onclick="exportToCSV()" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-download mr-2"></i>Exporter CSV
                </button>
                <button onclick="exportToPDF()" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-file-pdf mr-2"></i>Exporter PDF
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Période du rapport</h6>
                    <p><strong>Début:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</p>
                    <p><strong>Fin:</strong> {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
                    <p><strong>Total Revenus:</strong> {{ number_format($packageRevenue + $eventRevenue, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="col-md-6">
                    <h6>Résumé</h6>
                    <p><strong>Packages:</strong> {{ $topPackages->count() }} produits actifs</p>
                    <p><strong>Événements:</strong> {{ $topEvents->count() }} événements actifs</p>
                    <p><strong>Généré le:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Period selector
    document.getElementById('period').addEventListener('change', function() {
        const customDates = document.querySelectorAll('.custom-dates');
        if (this.value === 'custom') {
            customDates.forEach(el => el.style.display = 'block');
        } else {
            customDates.forEach(el => el.style.display = 'none');
        }
    });

    // Revenue Trend Chart
    const trendCtx = document.getElementById('revenueTrendChart').getContext('2d');
    const trendData = @json($monthlyRevenue);

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendData.map(item => item.month),
            datasets: [{
                label: 'Revenus (FCFA)',
                data: trendData.map(item => item.revenue),
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
                    text: 'Évolution Mensuelle des Revenus'
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

    // Revenue Distribution Chart
    const distributionCtx = document.getElementById('revenueDistributionChart').getContext('2d');
    const packageRevenue = {{ $packageRevenue }};
    const eventRevenue = {{ $eventRevenue }};

    new Chart(distributionCtx, {
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

function exportToCSV() {
    // Simple CSV export - in production, you'd want more sophisticated handling
    alert('Fonctionnalité d\'export CSV à implémenter');
}

function exportToPDF() {
    // Simple PDF export - in production, you'd want more sophisticated handling
    alert('Fonctionnalité d\'export PDF à implémenter');
}
</script>
@endpush
@endsection
