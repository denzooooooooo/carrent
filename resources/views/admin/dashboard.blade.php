@extends('admin.layouts.app')

@section('title', 'Acceuil')

@section('content')
        <div class="space-y-8">
            <section class="admin-page-header" id="overview">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-purple-600">Pilotage</p>
                    <h1 class="mt-2 text-3xl font-black text-gray-900">Dashboard administrateur</h1>
                    <p class="mt-3 max-w-2xl text-gray-600">Vue d'ensemble des réservations, revenus, opérations en attente et alertes prioritaires.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="#stats" class="admin-btn-primary px-5 py-3 text-sm">Statistiques clés</a>
                    <a href="#charts" class="admin-btn-ghost px-5 py-3 text-sm">Graphiques</a>
                    <div class="admin-btn-ghost px-5 py-3 text-sm">
                        <i class="fas fa-clock"></i>
                        Mise à jour: {{ now()->format('H:i') }}
                    </div>
                </div>
            </section>

            <div class="space-y-6">
            <!-- Statistiques Principales - Ligne 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full" id="stats">
                <!-- Réservations Aujourd'hui -->
                <div class="admin-kpi p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600 mb-2">Réservations Aujourd'hui</p>
                            <h3 class="text-4xl font-black text-gray-900" id="bookings-today">{{ $stats['bookings_today'] }}
                            </h3>
                        </div>
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-purple-100 text-purple-700">
                            <i class="fas fa-calendar-check text-3xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="rounded-full bg-purple-50 px-3 py-1 font-semibold text-purple-700">{{ $stats['bookings_week'] }}
                            cette semaine</span>
                    </div>
                </div>

                <!-- Revenus Aujourd'hui -->
                <div class="admin-kpi admin-kpi-accent p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-700 mb-2">Revenus Aujourd'hui</p>
                            <h3 class="text-4xl font-black text-gray-900" id="revenue-today">
                                {{ number_format($stats['revenue_today'], 0, ',', ' ') }}
                            </h3>
                            <p class="text-xs text-gray-500">XOF</p>
                        </div>
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-amber-100 text-amber-700">
                            <i class="fas fa-money-bill-wave text-3xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span
                            class="rounded-full bg-amber-50 px-3 py-1 font-semibold text-amber-700">{{ number_format($stats['revenue_month'], 0, ',', ' ') }}
                            ce mois</span>
                    </div>
                </div>

                <!-- Nouveaux Utilisateurs -->
                <div class="admin-kpi p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600 mb-2">Nouveaux Utilisateurs</p>
                            <h3 class="text-4xl font-black text-gray-900">{{ $stats['new_users_today'] }}</h3>
                        </div>
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-purple-100 text-purple-700">
                            <i class="fas fa-user-plus text-3xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="rounded-full bg-purple-50 px-3 py-1 font-semibold text-purple-700">{{ $stats['total_users'] }}
                            total</span>
                    </div>
                </div>

                <!-- En Attente -->
                <div class="admin-kpi admin-kpi-accent p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-700 mb-2">En Attente</p>
                            <h3 class="text-4xl font-black text-gray-900" id="pending-bookings">
                                {{ $stats['pending_bookings'] }}
                            </h3>
                        </div>
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-amber-100 text-amber-700">
                            <i class="fas fa-clock text-3xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="rounded-full bg-amber-50 px-3 py-1 font-semibold text-amber-700">{{ $stats['pending_reviews'] }}
                            avis</span>
                    </div>
                </div>
            </div>

            <!-- Statistiques Secondaires - Ligne 2 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 w-full">
                <div class="admin-panel p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Vols Réservés</p>
                            <h4 class="text-2xl font-black text-gray-800">{{ $stats['flight_bookings_total'] }}
                            </h4>
                        </div>
                        <i class="fas fa-plane text-3xl text-purple-500"></i>
                    </div>
                </div>

                <div class="admin-panel p-6 border-l-4 border-amber-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Billets Événements</p>
                            <h4 class="text-2xl font-black text-gray-800">{{ $stats['event_tickets_sold'] }}
                            </h4>
                        </div>
                        <i class="fas fa-ticket-alt text-3xl text-amber-600"></i>
                    </div>
                </div>

                <div class="admin-panel p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Packages Vendus</p>
                            <h4 class="text-2xl font-black text-gray-800">{{ $stats['package_bookings_total'] }}
                            </h4>
                        </div>
                        <i class="fas fa-suitcase text-3xl text-green-500"></i>
                    </div>
                </div>

                <div class="admin-panel p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Note Moyenne</p>
                            <h4 class="text-2xl font-black text-gray-800">
                                {{ $stats['average_rating'] ?? '0.0' }}/5
                            </h4>
                        </div>
                        <i class="fas fa-star text-3xl text-purple-600"></i>
                    </div>
                </div>

                <div class="admin-panel p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Annulations</p>
                            <h4 class="text-2xl font-black text-gray-800">{{ $stats['cancelled_bookings'] }}
                            </h4>
                        </div>
                        <i class="fas fa-times-circle text-3xl text-red-500"></i>
                    </div>
                </div>
            </div>

            <!-- Alertes Importantes -->
            @if($alerts['low_stock_events'] > 0 || $alerts['low_stock_packages'] > 0 || $alerts['failed_payments'] > 0)
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-xl">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-4 mt-1"></i>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-red-800 mb-3">Alertes Importantes</h3>
                            <div class="space-y-2">
                                @if($alerts['low_stock_events'] > 0)
                                    <p class="text-red-700"><i
                                            class="fas fa-circle text-xs mr-2"></i>{{ $alerts['low_stock_events'] }}
                                        événement(s) avec stock faible</p>
                                @endif
                                @if($alerts['low_stock_packages'] > 0)
                                    <p class="text-red-700"><i
                                            class="fas fa-circle text-xs mr-2"></i>{{ $alerts['low_stock_packages'] }}
                                        package(s) avec stock faible</p>
                                @endif
                                @if($alerts['failed_payments'] > 0)
                                    <p class="text-red-700"><i
                                            class="fas fa-circle text-xs mr-2"></i>{{ $alerts['failed_payments'] }}
                                        paiement(s) échoué(s) cette semaine</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Graphiques -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full" id="charts">
                <!-- Graphique Revenus -->
                <div class="glass rounded-2xl shadow-lg p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-black text-gray-800">
                            <i class="fas fa-chart-line text-purple-600 mr-2"></i>
                            Évolution des Revenus
                        </h3>
                        <span class="text-sm text-gray-500">12 derniers mois</span>
                    </div>
                    <div class="h-80">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Graphique Réservations -->
                <div class="glass rounded-2xl shadow-lg p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-black text-gray-800">
                            <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                            Évolution des Réservations
                        </h3>
                        <span class="text-sm text-gray-500">12 derniers mois</span>
                    </div>
                    <div class="h-80">
                        <canvas id="bookingsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Graphiques Circulaires -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Réservations par Type -->
                <div class="glass rounded-2xl shadow-lg p-8">
                    <h3 class="text-lg font-black text-gray-800 mb-6">
                        <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                        Par Type
                    </h3>
                    <div class="h-64">
                        <canvas id="typeChart"></canvas>
                    </div>
                </div>

                <!-- Réservations par Statut -->
                <div class="glass rounded-2xl shadow-lg p-8">
                    <h3 class="text-lg font-black text-gray-800 mb-6">
                        <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                        Par Statut
                    </h3>
                    <div class="h-64">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Statistiques 7 Derniers Jours -->
            <div class="glass rounded-2xl shadow-lg p-8 w-full" id="reports">
                <h3 class="text-xl font-black text-gray-800 mb-6">
                    <i class="fas fa-calendar-week text-purple-600 mr-2"></i>
                    Activité des 7 Derniers Jours
                </h3>
                <div class="h-80">
                    <canvas id="dailyStatsChart"></canvas>
                </div>
            </div>

        </div>
    </div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($revenueData->pluck('month')),
            datasets: [{
                label: 'Revenus (XOF)',
                data: @json($revenueData->pluck('total')),
                borderColor: 'rgb(91, 33, 182)',
                backgroundColor: 'rgba(91, 33, 182, 0.12)',
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
                    text: 'Évolution des Revenus'
                }
            }
        }
    });

    // Bookings Chart
    const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
    const bookingsChart = new Chart(bookingsCtx, {
        type: 'bar',
        data: {
            labels: @json($bookingsData->pluck('month')),
            datasets: [{
                label: 'Réservations',
                data: @json($bookingsData->pluck('total')),
                backgroundColor: 'rgba(200, 138, 42, 0.35)',
                borderColor: 'rgb(200, 138, 42)',
                borderWidth: 1
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
                    text: 'Évolution des Réservations'
                }
            }
        }
    });

    // Type Chart
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    const typeChart = new Chart(typeCtx, {
        type: 'pie',
        data: {
            labels: ['Vols', 'Événements', 'Packages'],
            datasets: [{
                data: @json($bookingsByType->pluck('count')),
                backgroundColor: [
                    'rgb(91, 33, 182)',
                    'rgb(200, 138, 42)',
                    'rgb(31, 122, 91)'
                ]
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

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: ['Confirmé', 'En attente', 'Annulé', 'Terminé'],
            datasets: [{
                data: @json($bookingsByStatus->pluck('count')),
                backgroundColor: [
                    'rgb(31, 122, 91)',
                    'rgb(184, 106, 22)',
                    'rgb(180, 35, 24)',
                    'rgb(91, 33, 182)'
                ]
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

    // Daily Stats Chart
    const dailyStatsCtx = document.getElementById('dailyStatsChart').getContext('2d');
    const dailyStatsChart = new Chart(dailyStatsCtx, {
        type: 'line',
        data: {
            labels: @json(collect($dailyStats)->pluck('date')),
            datasets: [{
                label: 'Réservations',
                data: @json(collect($dailyStats)->pluck('bookings')),
                borderColor: 'rgb(91, 33, 182)',
                backgroundColor: 'rgba(91, 33, 182, 0.12)',
                tension: 0.1
            }, {
                label: 'Utilisateurs',
                data: @json(collect($dailyStats)->pluck('users')),
                borderColor: 'rgb(200, 138, 42)',
                backgroundColor: 'rgba(200, 138, 42, 0.12)',
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
                    text: 'Activité des 7 Derniers Jours'
                }
            }
        }
    });
</script>
@endpush

@endsection
