@extends('layouts.admin')

@section('title', 'Rapports Financiers - Comptable')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8 border-b pb-4">
        <div>
            <h1 class="text-3xl font-bold text-dark gradient-text">Rapports Financiers</h1>
            <p class="text-gray-600 mt-2">Analyse détaillée des revenus et performances</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.accountant.dashboard') }}" class="py-2 px-4 rounded-lg text-white font-semibold bg-gray-600 hover:bg-gray-700 transition duration-300 shadow-md flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Retour au Dashboard
            </a>
            <button onclick="window.print()" class="py-2 px-4 rounded-lg text-white font-semibold bg-primary hover:bg-purple-700 transition duration-300 shadow-md flex items-center">
                <i class="fas fa-print mr-2"></i>Imprimer
            </button>
        </div>
    </div>

    <!-- Period Selection -->
    <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Sélection de la période</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="period" class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                <select name="period" id="period" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">
                    <option value="week" {{ request('period', 'month') === 'week' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="month" {{ request('period', 'month') === 'month' ? 'selected' : '' }}>Ce mois</option>
                    <option value="quarter" {{ request('period', 'month') === 'quarter' ? 'selected' : '' }}>Ce trimestre</option>
                    <option value="year" {{ request('period', 'month') === 'year' ? 'selected' : '' }}>Cette année</option>
                    <option value="custom" {{ request('period') === 'custom' ? 'selected' : '' }}>Personnalisé</option>
                </select>
            </div>
            <div class="custom-dates" style="display: {{ request('period') === 'custom' ? 'block' : 'none' }};">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                <input type="date" name="start_date" id="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                       value="{{ request('start_date') }}">
            </div>
            <div class="custom-dates" style="display: {{ request('period') === 'custom' ? 'block' : 'none' }};">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                <input type="date" name="end_date" id="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                       value="{{ request('end_date') }}">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full py-2 px-4 rounded-lg text-white font-semibold bg-primary hover:bg-purple-700 transition duration-300 shadow-md">
                    <i class="fas fa-search mr-2"></i>Générer
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl shadow-xl border border-green-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-green-600 uppercase tracking-wide">Revenus Packages</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($packageRevenue, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-suitcase text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl shadow-xl border border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Revenus Événements</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($eventRevenue, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-calendar-alt text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Revenue Trend -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Évolution des Revenus (12 derniers mois)</h3>
                <div class="h-80">
                    <canvas id="revenueTrendChart" width="100%" height="100%"></canvas>
                </div>
            </div>
        </div>

        <!-- Revenue Distribution -->
        <div>
            <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Répartition des Revenus</h3>
                <div class="h-48 mb-4">
                    <canvas id="revenueDistributionChart" width="100%" height="100%"></canvas>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Packages</span>
                        <span class="font-semibold text-gray-800">{{ number_format($packageRevenue, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-primary h-2 rounded-full" style="width: {{ ($packageRevenue + $eventRevenue) > 0 ? ($packageRevenue / ($packageRevenue + $eventRevenue)) * 100 : 0 }}%"></div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Événements</span>
                        <span class="font-semibold text-gray-800">{{ number_format($eventRevenue, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($packageRevenue + $eventRevenue) > 0 ? ($eventRevenue / ($packageRevenue + $eventRevenue)) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Packages -->
        <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-trophy text-yellow-500 mr-2"></i>Top 10 Packages
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-2 font-semibold text-gray-700">Package</th>
                            <th class="text-center py-3 px-2 font-semibold text-gray-700">Réservations</th>
                            <th class="text-right py-3 px-2 font-semibold text-gray-700">Revenus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topPackages as $package)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-2">
                                <div class="font-semibold text-gray-800">{{ $package->package ? ($package->package->title_fr ?? 'N/A') : 'Package supprimé' }}</div>
                                <small class="text-gray-500">{{ $package->package ? ($package->package->title_en ?? '') : '' }}</small>
                            </td>
                            <td class="text-center py-3 px-2">{{ $package->bookings_count ?? 0 }}</td>
                            <td class="text-right py-3 px-2 font-semibold text-green-600">
                                {{ number_format($package->total_revenue ?? 0, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-500 py-6">
                                <i class="fas fa-inbox text-2xl mb-2"></i>
                                <p>Aucune donnée disponible</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Events -->
        <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-star text-purple-500 mr-2"></i>Top 10 Événements
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-2 font-semibold text-gray-700">Événement</th>
                            <th class="text-center py-3 px-2 font-semibold text-gray-700">Réservations</th>
                            <th class="text-right py-3 px-2 font-semibold text-gray-700">Revenus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topEvents as $event)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-2">
                                <div class="font-semibold text-gray-800">{{ $event->event ? ($event->event->title_fr ?? 'N/A') : 'Événement supprimé' }}</div>
                                <small class="text-gray-500">{{ $event->event ? ($event->event->title_en ?? '') : '' }}</small>
                            </td>
                            <td class="text-center py-3 px-2">{{ $event->bookings_count ?? 0 }}</td>
                            <td class="text-right py-3 px-2 font-semibold text-green-600">
                                {{ number_format($event->total_revenue ?? 0, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-500 py-6">
                                <i class="fas fa-inbox text-2xl mb-2"></i>
                                <p>Aucune donnée disponible</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detailed Report -->
    <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-file-alt text-blue-500 mr-2"></i>Rapport Détaillé
            </h3>
            <div class="flex gap-3">
                <button onclick="exportToCSV()" class="py-2 px-4 rounded-lg text-white font-semibold bg-green-600 hover:bg-green-700 transition duration-300 shadow-md flex items-center">
                    <i class="fas fa-download mr-2"></i>Exporter CSV
                </button>
                <button onclick="exportToPDF()" class="py-2 px-4 rounded-lg text-white font-semibold bg-red-600 hover:bg-red-700 transition duration-300 shadow-md flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>Exporter PDF
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-3">Période du rapport</h4>
                <div class="space-y-2">
                    <p class="text-gray-600"><strong>Début:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</p>
                    <p class="text-gray-600"><strong>Fin:</strong> {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
                    <p class="text-gray-600"><strong>Total Revenus:</strong> <span class="font-semibold text-green-600">{{ number_format($packageRevenue + $eventRevenue, 0, ',', ' ') }} FCFA</span></p>
                </div>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-3">Résumé</h4>
                <div class="space-y-2">
                    <p class="text-gray-600"><strong>Packages:</strong> {{ $topPackages->count() }} produits actifs</p>
                    <p class="text-gray-600"><strong>Événements:</strong> {{ $topEvents->count() }} événements actifs</p>
                    <p class="text-gray-600"><strong>Généré le:</strong> {{ now()->format('d/m/Y H:i') }}</p>
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
