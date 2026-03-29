@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <section class="admin-page-header">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-purple-600">Pilotage</p>
            <h1 class="mt-2 text-3xl font-black text-gray-900">Dashboard administrateur</h1>
            <p class="mt-3 max-w-2xl text-gray-600">Vue d’ensemble des réservations, du revenu, des alertes et des opérations qui demandent une action immédiate.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.bookings.index') }}" class="admin-btn-primary px-5 py-3 text-sm">
                <i class="fas fa-calendar-check"></i>
                Réservations
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                <i class="fas fa-users"></i>
                Utilisateurs
            </a>
            <div class="admin-btn-ghost px-5 py-3 text-sm">
                <i class="fas fa-clock"></i>
                Mise à jour {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <article class="admin-kpi p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600">Réservations aujourd’hui</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['bookings_today'] }}</p>
                    <p class="mt-2 text-sm text-gray-600">{{ $stats['bookings_week'] }} cette semaine</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-purple-100 text-purple-700">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi admin-kpi-accent p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-700">Revenu du mois</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ number_format($stats['revenue_month'], 0, ',', ' ') }}</p>
                    <p class="mt-2 text-sm text-gray-600">{{ number_format($stats['revenue_today'], 0, ',', ' ') }} aujourd’hui</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600">Base clients</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['total_users'] }}</p>
                    <p class="mt-2 text-sm text-gray-600">{{ $stats['new_users_today'] }} nouveaux aujourd’hui</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-purple-100 text-purple-700">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi admin-kpi-accent p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-700">File d’attente</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['pending_bookings'] }}</p>
                    <p class="mt-2 text-sm text-gray-600">{{ $stats['cancelled_bookings'] }} annulations</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </article>
    </section>

    <section class="grid grid-cols-1 gap-6 lg:grid-cols-5">
        <div class="admin-panel p-6 border-l-4 border-purple-500">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-gray-500">Vols</p>
            <p class="mt-3 text-3xl font-black text-gray-900">{{ $stats['flight_bookings_total'] }}</p>
            <p class="mt-2 text-sm text-gray-600">Réservations cumulées</p>
        </div>
        <div class="admin-panel p-6 border-l-4 border-amber-500">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-gray-500">Événements</p>
            <p class="mt-3 text-3xl font-black text-gray-900">{{ $stats['event_tickets_sold'] }}</p>
            <p class="mt-2 text-sm text-gray-600">Dossiers billetterie</p>
        </div>
        <div class="admin-panel p-6 border-l-4 border-green-500">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-gray-500">Packages</p>
            <p class="mt-3 text-3xl font-black text-gray-900">{{ $stats['package_bookings_total'] }}</p>
            <p class="mt-2 text-sm text-gray-600">Dossiers voyage</p>
        </div>
        <div class="admin-panel p-6 border-l-4 border-purple-500">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-gray-500">Avis moyen</p>
            <p class="mt-3 text-3xl font-black text-gray-900">{{ number_format($stats['average_rating'] ?? 0, 1) }}/5</p>
            <p class="mt-2 text-sm text-gray-600">Expérience client</p>
        </div>
        <div class="admin-panel p-6 border-l-4 border-red-500">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-gray-500">Paiements échoués</p>
            <p class="mt-3 text-3xl font-black text-gray-900">{{ $alerts['failed_payments'] }}</p>
            <p class="mt-2 text-sm text-gray-600">Sur les 7 derniers jours</p>
        </div>
    </section>

    @if($alerts['low_stock_events'] > 0 || $alerts['low_stock_packages'] > 0 || $alerts['failed_payments'] > 0)
        <section class="admin-panel border border-red-200 bg-red-50/80 p-6">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-red-600">
                    <i class="fas fa-triangle-exclamation text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-red-600">Alertes prioritaires</p>
                    <div class="mt-4 grid gap-3 md:grid-cols-3">
                        @if($alerts['low_stock_events'] > 0)
                            <div class="rounded-2xl border border-red-200 bg-white px-4 py-4 text-sm font-semibold text-gray-800">
                                {{ $alerts['low_stock_events'] }} événement(s) avec stock faible
                            </div>
                        @endif
                        @if($alerts['low_stock_packages'] > 0)
                            <div class="rounded-2xl border border-red-200 bg-white px-4 py-4 text-sm font-semibold text-gray-800">
                                {{ $alerts['low_stock_packages'] }} package(s) proches de la saturation
                            </div>
                        @endif
                        @if($alerts['failed_payments'] > 0)
                            <div class="rounded-2xl border border-red-200 bg-white px-4 py-4 text-sm font-semibold text-gray-800">
                                {{ $alerts['failed_payments'] }} paiement(s) échoué(s) à traiter
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-2" id="charts">
        <div class="admin-panel p-6 sm:p-8">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Performance</p>
                    <h2 class="mt-2 text-2xl font-black text-gray-900">Évolution des revenus</h2>
                </div>
                <span class="rounded-full bg-purple-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-purple-700">12 mois</span>
            </div>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="admin-panel p-6 sm:p-8">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-600">Volume</p>
                    <h2 class="mt-2 text-2xl font-black text-gray-900">Évolution des réservations</h2>
                </div>
                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-amber-700">12 mois</span>
            </div>
            <div class="h-80">
                <canvas id="bookingsChart"></canvas>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="admin-panel p-6 sm:p-8">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Mix produit</p>
                    <h2 class="mt-2 text-xl font-black text-gray-900">Répartition par type</h2>
                </div>
            </div>
            <div class="h-64">
                <canvas id="typeChart"></canvas>
            </div>
        </div>

        <div class="admin-panel p-6 sm:p-8">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-green-600">Traitement</p>
                    <h2 class="mt-2 text-xl font-black text-gray-900">Répartition par statut</h2>
                </div>
            </div>
            <div class="h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="admin-panel p-6 sm:p-8">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">7 jours</p>
                    <h2 class="mt-2 text-xl font-black text-gray-900">Activité récente</h2>
                </div>
            </div>
            <div class="h-64">
                <canvas id="dailyStatsChart"></canvas>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="admin-panel overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-5">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Flux</p>
                <h2 class="mt-2 text-2xl font-black text-gray-900">Dernières réservations</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentBookings as $booking)
                    <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4">
                        <div>
                            <p class="text-sm font-black text-gray-900">{{ $booking->booking_number }}</p>
                            <p class="mt-1 text-sm text-gray-600">{{ $booking->user->name }}</p>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p class="font-semibold text-gray-900">{{ ucfirst($booking->booking_type) }}</p>
                            <p>{{ number_format($booking->final_amount, 0, ',', ' ') }} XOF</p>
                        </div>
                        <div class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $booking->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : ($booking->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                            {{ $booking->status }}
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-sm text-gray-500">Aucune réservation récente à afficher.</div>
                @endforelse
            </div>
        </div>

        <div class="admin-panel overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-5">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Communauté</p>
                <h2 class="mt-2 text-2xl font-black text-gray-900">Derniers utilisateurs</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentUsers as $user)
                    <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4">
                        <div>
                            <p class="text-sm font-black text-gray-900">{{ $user->name }}</p>
                            <p class="mt-1 text-sm text-gray-600">{{ $user->email }}</p>
                        </div>
                        <div class="text-sm font-semibold text-gray-700">
                            {{ $user->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-sm text-gray-500">Aucun nouvel utilisateur récent.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="admin-panel overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-5">
                <h2 class="text-xl font-black text-gray-900">Top événements</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($topEvents as $event)
                    <div class="flex items-center justify-between gap-4 px-6 py-4 text-sm">
                        <span class="font-semibold text-gray-900">{{ $event->title }}</span>
                        <span class="rounded-full bg-purple-50 px-3 py-1 font-bold text-purple-700">{{ $event->tickets_count }}</span>
                    </div>
                @empty
                    <div class="px-6 py-10 text-sm text-gray-500">Aucun événement remonté.</div>
                @endforelse
            </div>
        </div>

        <div class="admin-panel overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-5">
                <h2 class="text-xl font-black text-gray-900">Top packages</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($topPackages as $package)
                    <div class="flex items-center justify-between gap-4 px-6 py-4 text-sm">
                        <span class="font-semibold text-gray-900">{{ $package->title }}</span>
                        <span class="rounded-full bg-amber-50 px-3 py-1 font-bold text-amber-700">{{ $package->bookings_count }}</span>
                    </div>
                @empty
                    <div class="px-6 py-10 text-sm text-gray-500">Aucun package remonté.</div>
                @endforelse
            </div>
        </div>

        <div class="admin-panel overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-5">
                <h2 class="text-xl font-black text-gray-900">Top destinations</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($topDestinations as $destination)
                    <div class="flex items-center justify-between gap-4 px-6 py-4 text-sm">
                        <span class="font-semibold text-gray-900">{{ $destination->destination }}</span>
                        <span class="rounded-full bg-green-50 px-3 py-1 font-bold text-green-700">{{ $destination->count }}</span>
                    </div>
                @empty
                    <div class="px-6 py-10 text-sm text-gray-500">Aucune destination disponible.</div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($revenueData->pluck('month')),
            datasets: [{
                label: 'Revenus (XOF)',
                data: @json($revenueData->pluck('total')),
                borderColor: 'rgb(91, 33, 182)',
                backgroundColor: 'rgba(91, 33, 182, 0.12)',
                fill: true,
                tension: 0.28
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });

    const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
    new Chart(bookingsCtx, {
        type: 'bar',
        data: {
            labels: @json($bookingsData->pluck('month')),
            datasets: [{
                label: 'Réservations',
                data: @json($bookingsData->pluck('total')),
                backgroundColor: 'rgba(200, 138, 42, 0.38)',
                borderColor: 'rgb(200, 138, 42)',
                borderWidth: 1,
                borderRadius: 10
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });

    const typeCtx = document.getElementById('typeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Vols', 'Événements', 'Packages'],
            datasets: [{
                data: @json($bookingsByType->pluck('count')),
                backgroundColor: ['rgb(91, 33, 182)', 'rgb(200, 138, 42)', 'rgb(31, 122, 91)']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
    });

    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Confirmé', 'En attente', 'Annulé', 'Terminé'],
            datasets: [{
                data: @json($bookingsByStatus->pluck('count')),
                backgroundColor: ['rgb(31, 122, 91)', 'rgb(184, 106, 22)', 'rgb(180, 35, 24)', 'rgb(91, 33, 182)']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
    });

    const dailyStatsCtx = document.getElementById('dailyStatsChart').getContext('2d');
    new Chart(dailyStatsCtx, {
        type: 'line',
        data: {
            labels: @json(collect($dailyStats)->pluck('date')),
            datasets: [{
                label: 'Réservations',
                data: @json(collect($dailyStats)->pluck('bookings')),
                borderColor: 'rgb(91, 33, 182)',
                backgroundColor: 'rgba(91, 33, 182, 0.08)',
                fill: true,
                tension: 0.25
            }, {
                label: 'Utilisateurs',
                data: @json(collect($dailyStats)->pluck('users')),
                borderColor: 'rgb(200, 138, 42)',
                backgroundColor: 'rgba(200, 138, 42, 0.08)',
                fill: true,
                tension: 0.25
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
    });
</script>
@endpush
