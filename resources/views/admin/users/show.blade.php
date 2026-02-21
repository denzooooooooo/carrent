@extends('admin.layouts.app')

@section('title', 'Détails de l\'utilisateur - ' . $user->name)

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
                    <a href="#bookings" class="nav-link text-gray-600 hover:text-purple-600 transition-colors">Réservations</a>
                    <a href="#reviews" class="nav-link text-gray-600 hover:text-purple-600 transition-colors">Avis</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la liste
                    </a>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                </div>
            </nav>
        </div>

        <div class="mb-8" id="overview">
            <h1 class="text-3xl font-black text-gray-900">Détails de l'utilisateur</h1>
            <p class="text-gray-600 mt-2">Informations complètes sur {{ $user->name }}</p>
        </div>



        <div class="space-y-6">
            <!-- Informations principales -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" id="stats">
                <!-- Profil -->
                <div class="bg-white rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all">
                    <div class="text-center">
                        @if($user->avatar)
                            <img src="{{ $user->getFirstMediaUrl('avatar', 'normal') }}" alt="Avatar" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover border-4 border-gray-200">
                        @else
                            <div class="w-24 h-24 bg-purple-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-user text-3xl text-purple-600"></i>
                            </div>
                        @endif
                        <h3 class="text-xl font-bold text-gray-800">{{ $user->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ $user->email }}</p>
                        <div class="flex justify-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas {{ $user->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                {{ $user->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-envelope mr-1"></i>
                                    Vérifié
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="lg:col-span-2">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-4 text-white transform hover:scale-105 transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="text-purple-100 text-sm font-semibold">Réservations</p>
                                    <p class="text-2xl font-black">{{ $user->bookings->count() }}</p>
                                </div>
                                <i class="fas fa-shopping-cart text-2xl text-purple-200"></i>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-4 text-white transform hover:scale-105 transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="text-green-100 text-sm font-semibold">Confirmées</p>
                                    <p class="text-2xl font-black">{{ $user->bookings->where('status', 'confirmed')->count() }}</p>
                                </div>
                                <i class="fas fa-check-circle text-2xl text-green-200"></i>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-xl p-4 text-white transform hover:scale-105 transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="text-yellow-100 text-sm font-semibold">Avis</p>
                                    <p class="text-2xl font-black">{{ $user->reviews->count() }}</p>
                                </div>
                                <i class="fas fa-star text-2xl text-yellow-200"></i>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-4 text-white transform hover:scale-105 transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="text-blue-100 text-sm font-semibold">Total dépensé</p>
                                    <p class="text-lg font-black">{{ $user->bookings->sum('total_price') ? \App\Helpers\CurrencyHelper::format($user->bookings->sum('total_price')) : '0 FCFA' }}</p>
                                </div>
                                <i class="fas fa-euro-sign text-2xl text-blue-200"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations détaillées -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations personnelles -->
                <div class="glass rounded-2xl shadow-lg p-6">
                    <h4 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-user mr-2 text-purple-600"></i>Informations personnelles
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nom complet:</span>
                            <span class="font-medium">{{ $user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium">{{ $user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Téléphone:</span>
                            <span class="font-medium">{{ $user->phone ?? 'Non défini' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Adresse:</span>
                            <span class="font-medium">{{ $user->address ?? 'Non définie' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ville:</span>
                            <span class="font-medium">{{ $user->city ?? 'Non définie' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date d'inscription:</span>
                            <span class="font-medium">{{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Activité récente -->
                <div class="glass rounded-2xl shadow-lg p-6">
                    <h4 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-clock mr-2 text-green-600"></i>Activité récente
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dernière connexion:</span>
                            <span class="font-medium">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dernière réservation:</span>
                            <span class="font-medium">{{ $user->bookings->sortByDesc('created_at')->first()?->created_at->format('d/m/Y') ?? 'Aucune' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dernier avis:</span>
                            <span class="font-medium">{{ $user->reviews->sortByDesc('created_at')->first()?->created_at->format('d/m/Y') ?? 'Aucun' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Réservations -->
            <div class="glass rounded-2xl shadow-lg p-6" id="bookings">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-xl font-black text-gray-800">
                        <i class="fas fa-shopping-cart mr-2 text-purple-600"></i>Réservations ({{ $user->bookings->count() }})
                    </h4>
                </div>
                @if($user->bookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Service
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Prix
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($user->bookings->sortByDesc('created_at') as $booking)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ $booking->id }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ ucfirst($booking->booking_type) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($booking->booking_type === 'package')
                                                <div class="flex items-center">
                                                    <i class="fas fa-suitcase text-purple-500 mr-2"></i>
                                                    {{ $booking->package->title ?? 'Package supprimé' }}
                                                </div>
                                            @elseif($booking->booking_type === 'flight')
                                                <div class="flex items-center">
                                                    <i class="fas fa-plane text-blue-500 mr-2"></i>
                                                    {{ $booking->flight->departure_city ?? 'Vol supprimé' }} → {{ $booking->flight->arrival_city ?? '' }}
                                                </div>
                                            @elseif($booking->booking_type === 'event')
                                                <div class="flex items-center">
                                                    <i class="fas fa-calendar-alt text-green-500 mr-2"></i>
                                                    {{ $booking->event->title ?? 'Événement supprimé' }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $booking->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                @if($booking->status === 'confirmed')
                                                    <i class="fas fa-check-circle mr-1"></i>Confirmé
                                                @elseif($booking->status === 'pending')
                                                    <i class="fas fa-clock mr-1"></i>En attente
                                                @else
                                                    <i class="fas fa-times-circle mr-1"></i>Annulé
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ \App\Helpers\CurrencyHelper::format($booking->total_price) }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button class="text-blue-600 hover:text-blue-900" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Aucune réservation trouvée</p>
                        <p class="text-gray-400 text-sm">Cet utilisateur n'a pas encore effectué de réservation.</p>
                    </div>
                @endif
            </div>

            <!-- Avis -->
            <div class="glass rounded-2xl shadow-lg p-6" id="reviews">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-xl font-black text-gray-800">
                        <i class="fas fa-star mr-2 text-yellow-600"></i>Avis ({{ $user->reviews->count() }})
                    </h4>
                </div>
                @if($user->reviews->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($user->reviews->sortByDesc('created_at') as $review)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h5 class="font-semibold text-gray-800 mb-2">
                                            @if($review->reviewable_type === 'App\\Models\\Package')
                                                <i class="fas fa-suitcase text-purple-500 mr-2"></i>Package: {{ $review->reviewable->title ?? 'Supprimé' }}
                                            @elseif($review->reviewable_type === 'App\\Models\\Event')
                                                <i class="fas fa-calendar-alt text-green-500 mr-2"></i>Événement: {{ $review->reviewable->title ?? 'Supprimé' }}
                                            @else
                                                <i class="fas fa-concierge-bell text-blue-500 mr-2"></i>Service
                                            @endif
                                        </h5>
                                        <div class="flex items-center mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600">({{ $review->rating }}/5)</span>
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                                </div>
                                <p class="text-gray-700">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-star-half-alt text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Aucun avis trouvé</p>
                        <p class="text-gray-400 text-sm">Cet utilisateur n'a pas encore laissé d'avis.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
