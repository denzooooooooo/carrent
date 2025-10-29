@extends('admin.layouts.app')

@section('title', 'Acceuil')

@section('content')
    <!-- Page Content -->
    <main class="flex-1 overflow-y-auto p-4 md:p-6 page-transition">
        @if(session('success'))
            <div
                class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 rounded-lg shadow-md animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-2xl mr-3"></i>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div
                class="mb-6 p-4 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-md animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Navigation Bar -->
        <div class="mb-6">
            <nav class="glass rounded-xl shadow-sm p-4 flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <a href="#overview" class="nav-link active text-purple-600 font-semibold">Vue d'ensemble</a>
                    <a href="#stats" class="nav-link text-gray-600 hover:text-purple-600 transition-colors">Statistiques</a>
                    <a href="#charts" class="nav-link text-gray-600 hover:text-purple-600 transition-colors">Graphiques</a>
                    <a href="#reports" class="nav-link text-gray-600 hover:text-purple-600 transition-colors">Rapports</a>
                </div>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-clock mr-1"></i>
                    Dernière mise à jour: {{ now()->format('H:i') }}
                </div>
            </nav>
        </div>

        <div class="mb-8" id="overview">
            <h1 class="text-3xl font-black text-gray-900">Dashboard Administrateur</h1>
            <p class="text-gray-600 mt-2">Vue d'ensemble de votre plateforme</p>
        </div>

        <div class="space-y-6">
            <!-- Statistiques Principales - Ligne 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="stats">
                <!-- Réservations Aujourd'hui -->
                <div
                    class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-purple-100 text-sm font-semibold mb-1">Réservations Aujourd'hui</p>
                            <h3 class="text-4xl font-black" id="bookings-today">{{ $stats['bookings_today'] }}
                            </h3>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-calendar-check text-3xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="bg-white/20 px-3 py-1 rounded-full font-semibold">{{ $stats['bookings_week'] }}
                            cette semaine</span>
                    </div>
                </div>

                <!-- Revenus Aujourd'hui -->
                <div
                    class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-green-100 text-sm font-semibold mb-1">Revenus Aujourd'hui</p>
                            <h3 class="text-4xl font-black" id="revenue-today">
                                {{ number_format($stats['revenue_today'], 0, ',', ' ') }}
                            </h3>
                            <p class="text-green-100 text-xs">XOF</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-money-bill-wave text-3xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span
                            class="bg-white/20 px-3 py-1 rounded-full font-semibold">{{ number_format($stats['revenue_month'], 0, ',', ' ') }}
                            ce mois</span>
                    </div>
                </div>

                <!-- Nouveaux Utilisateurs -->
                <div
                    class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-blue-100 text-sm font-semibold mb-1">Nouveaux Utilisateurs</p>
                            <h3 class="text-4xl font-black">{{ $stats['new_users_today'] }}</h3>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-user-plus text-3xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="bg-white/20 px-3 py-1 rounded-full font-semibold">{{ $stats['total_users'] }}
                            total</span>
                    </div>
                </div>

                <!-- En Attente -->
                <div
                    class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-amber-100 text-sm font-semibold mb-1">En Attente</p>
                            <h3 class="text-4xl font-black" id="pending-bookings">
                                {{ $stats['pending_bookings'] }}
                            </h3>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-clock text-3xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="bg-white/20 px-3 py-1 rounded-full font-semibold">{{ $stats['pending_reviews'] }}
                            avis</span>
                    </div>
                </div>
            </div>

            <!-- Statistiques Secondaires - Ligne 2 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="glass rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Vols Réservés</p>
                            <h4 class="text-2xl font-black text-gray-800">{{ $stats['flight_bookings_total'] }}
                            </h4>
                        </div>
                        <i class="fas fa-plane text-3xl text-purple-500"></i>
                    </div>
                </div>

                <div class="glass rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Billets Événements</p>
                            <h4 class="text-2xl font-black text-gray-800">{{ $stats['event_tickets_sold'] }}
                            </h4>
                        </div>
                        <i class="fas fa-ticket-alt text-3xl text-blue-500"></i>
                    </div>
                </div>

                <div class="glass rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Packages Vendus</p>
                            <h4 class="text-2xl font-black text-gray-800">{{ $stats['package_bookings_total'] }}
                            </h4>
                        </div>
                        <i class="fas fa-suitcase text-3xl text-green-500"></i>
                    </div>
                </div>

                <div class="glass rounded-xl shadow-sm p-6 border-l-4 border-amber-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600">Note Moyenne</p>
                            <h4 class="text-2xl font-black text-gray-800">
                                {{ $stats['average_rating'] ?? '0.0' }}/5
                            </h4>
                        </div>
                        <i class="fas fa-star text-3xl text-amber-500"></i>
                    </div>
                </div>

                <div class="glass rounded-xl shadow-sm p-6 border-l-4 border-red-500">
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="charts">
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
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
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

                <!-- Top Destinations -->
                <div class="glass rounded-2xl shadow-lg p-8">
                    <h3 class="text-lg font-black text-gray-800 mb-6">
                        <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                        Top Destinations
                    </h3>
                    <div class="space-y-3">
                        @foreach($topDestinations as $dest)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl flex items-center justify-center text-white font-black text-sm">
                                        {{ $dest->destination }}
                                    </div>
                                    <span class="font-semibold text-gray-700">{{ $dest->destination }}</span>
                                </div>
                                <span
                                    class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-bold">{{ $dest->count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Statistiques 7 Derniers Jours -->
            <div class="glass rounded-2xl shadow-lg p-8" id="reports">
                <h3 class="text-xl font-black text-gray-800 mb-6">
                    <i class="fas fa-calendar-week text-purple-600 mr-2"></i>
                    Activité des 7 Derniers Jours
                </h3>
                <div class="h-80">
                    <canvas id="dailyStatsChart"></canvas>
                </div>
            </div>

            <!-- Top Produits -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Top Événements -->
                <div class="glass rounded-2xl shadow-lg p-8">
                    <h3 class="text-lg font-black text-gray-800 mb-6">
                        <i class="fas fa-trophy text-amber-600 mr-2"></i>
                        Top Événements
                    </h3>
                    <div class="space-y-4">
                        @foreach($topEvents as $event)
                            <div
                                class="flex items-start space-x-3 p-3 bg-gray-50 rounded-xl hover:bg-purple-50 transition-colors">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-purple-600 to-amber-600 rounded-xl flex items-center justify-center text-white font-black">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800 text-sm">{{ Str::limit($event->title, 30) }}
                                    </h4>
                                    <p class="text-xs text-gray-500">{{ $event->tickets_count ?? 0 }} billets vendus
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
@endsection