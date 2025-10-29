@extends('admin.layouts.app')

@section('title', 'Détails du Package')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header avec Actions -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <div class="flex items-center space-x-3 mb-2">
                <h1 class="text-3xl font-bold text-gray-900">{{ $package->title_fr }}</h1>
                @if($package->is_featured)
                    <span class="px-3 py-1 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold rounded-full">
                        <i class="fas fa-star mr-1"></i>EN VEDETTE
                    </span>
                @endif
                <span class="px-3 py-1 text-xs font-semibold rounded-full
                    {{ $package->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $package->is_active ? 'Actif' : 'Inactif' }}
                </span>
            </div>
            <p class="text-gray-600">{{ $package->destination }} • {{ $package->duration_text_fr ?: $package->duration . ' jours' }}</p>
        </div>
        
        <div class="flex space-x-2">
            <a href="{{ route('admin.packages.index') }}" 
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
            <a href="{{ route('admin.packages.edit', $package) }}" 
               class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
        </div>
    </div>

    <!-- Statistiques Clés -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-calendar-check text-3xl opacity-80"></i>
                <span class="text-sm font-medium">Réservations</span>
            </div>
            <p class="text-3xl font-bold">{{ $stats['total_bookings'] }}</p>
            <p class="text-xs opacity-80 mt-1">{{ $stats['confirmed_bookings'] }} confirmées</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-euro-sign text-3xl opacity-80"></i>
                <span class="text-sm font-medium">Revenus</span>
            </div>
            <p class="text-3xl font-bold">{{ number_format($stats['total_revenue'], 0) }}€</p>
            <p class="text-xs opacity-80 mt-1">Total généré</p>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-star text-3xl opacity-80"></i>
                <span class="text-sm font-medium">Note</span>
            </div>
            <p class="text-3xl font-bold">{{ number_format($stats['average_rating'], 1) }}/5</p>
            <p class="text-xs opacity-80 mt-1">{{ $stats['total_reviews'] }} avis</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-tag text-3xl opacity-80"></i>
                <span class="text-sm font-medium">Prix</span>
            </div>
            <p class="text-3xl font-bold">{{ number_format($package->price, 0) }}€</p>
            @if($package->discount_price)
                <p class="text-xs opacity-80 mt-1 line-through">{{ number_format($package->discount_price, 0) }}€</p>
            @endif
        </div>

        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-users text-3xl opacity-80"></i>
                <span class="text-sm font-medium">Participants</span>
            </div>
            <p class="text-3xl font-bold">{{ $package->max_participants }}</p>
            <p class="text-xs opacity-80 mt-1">max par groupe</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne Principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Images -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Image Principale -->
                @php
                    $mainImage = $package->getFirstMediaUrl('avatar', 'normal');
                    $placeholder = 'https://placehold.co/800x480/4c1d95/ffffff?text=Package+Voyage';
                @endphp
                <img src="{{ $mainImage ?: $placeholder }}" 
                     alt="{{ $package->title_fr }}" 
                     class="w-full h-96 object-cover"
                     onerror="this.src='{{ $placeholder }}'">

                <!-- Galerie -->
                @if($package->getMedia('gallery')->count() > 0)
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 mb-3">Galerie Photos</h3>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($package->getMedia('gallery') as $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" class="group">
                                    <img src="{{ $media->getUrl('small') }}" 
                                         alt="Galerie" 
                                         class="w-full h-24 object-cover rounded-lg shadow hover:shadow-lg transition transform group-hover:scale-105">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Description -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-align-left text-primary mr-2"></i>
                    Description
                </h2>
                
                <div class="prose max-w-none">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Français</h3>
                    <p class="text-gray-700 mb-4 leading-relaxed">
                        {{ $package->description_fr ?: 'Aucune description disponible' }}
                    </p>

                    @if($package->description_en)
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 mt-6">English</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $package->description_en }}</p>
                    @endif
                </div>
            </div>

            <!-- Services Inclus/Exclus -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-list-check text-primary mr-2"></i>
                    Services
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Services Inclus -->
                    <div>
                        <h3 class="font-semibold text-green-700 mb-3 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>Inclus
                        </h3>
                        @if($package->included_services_fr && count($package->included_services_fr) > 0)
                            <ul class="space-y-2">
                                @foreach($package->included_services_fr as $service)
                                    <li class="flex items-start text-gray-700">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>{{ $service }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">Aucun service inclus spécifié</p>
                        @endif
                    </div>

                    <!-- Services Exclus -->
                    <div>
                        <h3 class="font-semibold text-red-700 mb-3 flex items-center">
                            <i class="fas fa-times-circle mr-2"></i>Non Inclus
                        </h3>
                        @if($package->excluded_services_fr && count($package->excluded_services_fr) > 0)
                            <ul class="space-y-2">
                                @foreach($package->excluded_services_fr as $service)
                                    <li class="flex items-start text-gray-700">
                                        <i class="fas fa-times text-red-500 mr-2 mt-1"></i>
                                        <span>{{ $service }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">Aucun service exclu spécifié</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Itinéraire -->
            @if($package->itinerary_fr && count($package->itinerary_fr) > 0)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-route text-primary mr-2"></i>
                        Itinéraire Détaillé
                    </h2>

                    <div class="space-y-4">
                        @foreach($package->itinerary_fr as $index => $day)
                            <div class="flex">
                                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-primary to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ml-4 flex-1 bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-bold text-gray-900 mb-1">
                                        {{ $day['title'] ?? 'Jour ' . ($index + 1) }}
                                    </h4>
                                    <p class="text-gray-700">{{ $day['description'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Colonne Latérale -->
        <div class="space-y-6">
            <!-- Informations Essentielles -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Informations</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-gray-600 flex items-center">
                            <i class="fas fa-layer-group w-5 text-primary mr-2"></i>Catégorie
                        </span>
                        <span class="font-semibold">{{ $package->category->name_fr ?? 'N/A' }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-gray-600 flex items-center">
                            <i class="fas fa-plane-departure w-5 text-primary mr-2"></i>Type
                        </span>
                        <span class="font-semibold">{{ $packageTypes[$package->package_type] ?? $package->package_type }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-gray-600 flex items-center">
                            <i class="fas fa-map-marker-alt w-5 text-primary mr-2"></i>Destination
                        </span>
                        <span class="font-semibold">{{ $package->destination }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-gray-600 flex items-center">
                            <i class="fas fa-city w-5 text-primary mr-2"></i>Départ
                        </span>
                        <span class="font-semibold">{{ $package->departure_city ?: 'Flexible' }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-gray-600 flex items-center">
                            <i class="fas fa-clock w-5 text-primary mr-2"></i>Durée
                        </span>
                        <span class="font-semibold">{{ $package->duration }} jour(s)</span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-gray-600 flex items-center">
                            <i class="fas fa-users w-5 text-primary mr-2"></i>Participants
                        </span>
                        <span class="font-semibold">{{ $package->min_participants }} - {{ $package->max_participants }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-600 flex items-center">
                            <i class="fas fa-hashtag w-5 text-primary mr-2"></i>Slug
                        </span>
                        <span class="font-mono text-sm">{{ $package->slug }}</span>
                    </div>
                </div>
            </div>

            <!-- Dates Disponibles -->
            @if($availableDates->count() > 0)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt text-primary mr-2"></i>
                        Dates Disponibles
                    </h3>
                    
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach($availableDates as $date)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $date->available_date->format('d/m/Y') }}
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        {{ $date->available_spots }} places
                                    </p>
                                </div>
                                @if($date->price_override)
                                    <span class="text-sm font-bold text-primary">
                                        {{ number_format($date->price_override, 0) }}€
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Actions Rapides -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Actions</h3>
                
                <div class="space-y-2">
                    <form action="{{ route('admin.packages.toggle-status', $package) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2 px-4 rounded-lg transition
                            {{ $package->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                            <i class="fas fa-{{ $package->is_active ? 'ban' : 'check' }} mr-2"></i>
                            {{ $package->is_active ? 'Désactiver' : 'Activer' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.packages.toggle-featured', $package) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2 px-4 rounded-lg transition
                            {{ $package->is_featured ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' }}">
                            <i class="fas fa-star mr-2"></i>
                            {{ $package->is_featured ? 'Retirer Vedette' : 'Mettre en Vedette' }}
                        </button>
                    </form>

                    <a href="{{ route('admin.packages.edit', $package) }}" 
                       class="block w-full py-2 px-4 text-center bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>

                    <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 px-4 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <!-- Métadonnées -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Métadonnées</h3>
                
                <div class="text-sm text-gray-600 space-y-2">
                    <p><strong>Créé le:</strong> {{ $package->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Modifié le:</strong> {{ $package->updated_at->format('d/m/Y H:i') }}</p>
                    @if($package->video_url)
                        <p><strong>Vidéo:</strong> <a href="{{ $package->video_url }}" target="_blank" class="text-primary hover:underline">Voir</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
