@extends('admin.layouts.app')

@section('title', 'Détails du Package: ' . $package->title_fr)

@section('content')

    @php
        // Décodage des champs JSON pour l'affichage
        $includedServices = $package->included_services_fr ?? [];
        $excludedServices = $package->excluded_services_fr ?? [];
        $itinerary = $package->itinerary_fr ?? [];

        // Récupération de la galerie de photos
        $galleryImages = $package->getMedia('gallery');


    @endphp

    <div class="max-w-7xl mx-auto py-8">
        <div class="flex justify-between items-center mb-8 border-b pb-2">
            <h1 class="text-3xl font-bold text-dark gradient-text">Package : <span
                    class="text-primary">{{ $package->title_fr }}</span></h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.packages.edit', $package) }}"
                    class="py-2 px-4 rounded-lg text-white font-semibold bg-blue-600 hover:bg-blue-700 transition duration-300 shadow-md flex items-center">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>
                <a href="{{ route('admin.packages.index') }}"
                    class="py-2 px-4 rounded-lg text-white font-semibold bg-gray-600 hover:bg-gray-700 transition duration-300 shadow-md flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- COLONNE GAUCHE (IMAGE ET STATUT) --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-4 rounded-xl shadow-xl border border-gray-100">
                    @php
                        $imageUrl = $package->getFirstMediaUrl('avatar', 'normal');
                        $placeholder = 'https://placehold.co/800x600/4c1d95/ffffff?text=Image+Package';
                    @endphp
                    <img src="{{ $imageUrl ?: $placeholder }}" alt="Image de {{ $package->title_fr }}"
                        class="w-full h-auto object-cover rounded-lg shadow-lg"
                        onerror="this.onerror=null;this.src='{{ $placeholder }}';">
                </div>

                <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
                    <h3 class="text-xl font-bold text-dark mb-4 border-b pb-2">Statuts & Prix</h3>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex justify-between items-center">
                            <span class="font-medium">Statut :</span>
                            <span class="text-sm font-semibold px-3 py-1 rounded-full 
                            @if($package->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $package->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="font-medium">Mis en avant :</span>
                            <span class="text-sm font-semibold">
                                {!! $package->is_featured ? '<i class="fas fa-check-circle text-green-500"></i> Oui' : '<i class="fas fa-times-circle text-gray-400"></i> Non' !!}
                            </span>
                        </li>
                        <li class="flex justify-between items-center border-t pt-3 mt-3">
                            <span class="font-bold">Prix de Base :</span>
                            <span class="font-bold text-lg text-gray-800">{{ number_format($package->price, 2, ',', ' ') }}
                                €</span>
                        </li>
                        @if ($package->discount_price)
                            <li class="flex justify-between items-center">
                                <span class="font-bold">Prix Réduit :</span>
                                <span
                                    class="font-bold text-xl text-red-600">{{ number_format($package->discount_price, 2, ',', ' ') }}
                                    €</span>
                            </li>
                        @endif
                        <li class="flex justify-between items-center">
                            <span class="font-medium">Évaluation :</span>
                            <span class="font-semibold text-secondary">
                                @for ($i = 0; $i < round($package->rating); $i++) <i class="fas fa-star"></i> @endfor
                                ({{ $package->total_reviews }} avis)
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- COLONNE DROITE (DÉTAILS COMPLETS) --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- BLOC 1: INFOS VOYAGE --}}
                <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
                    <h3 class="text-xl font-bold text-primary mb-4 border-b pb-2"><i class="fas fa-map-marked-alt mr-2"></i>
                        Informations du Voyage</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
                        <div>
                            <p class="text-sm font-semibold">Catégorie :</p>
                            <p class="text-base">{{ $package->category->name_fr ?? 'Non définie' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Type de Package :</p>
                            <p class="text-base capitalize">{{ str_replace('_', ' ', $package->package_type) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Destination :</p>
                            <p class="text-base font-bold text-dark">{{ $package->destination }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Durée :</p>
                            <p class="text-base">{{ $package->duration }} jours
                                ({{ $package->duration_text_fr ?: 'Non Spécifié' }})</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Départ de :</p>
                            <p class="text-base">{{ $package->departure_city ?: 'Non spécifié' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Participants :</p>
                            <p class="text-base">Min: {{ $package->min_participants }} / Max:
                                {{ $package->max_participants }}</p>
                        </div>
                    </div>
                </div>

                {{-- BLOC 2: DESCRIPTIONS --}}
                <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
                    <h3 class="text-xl font-bold text-primary mb-4 border-b pb-2"><i class="fas fa-file-alt mr-2"></i>
                        Description</h3>
                    <div class="space-y-6">
                        <div>
                            <p class="text-lg font-semibold text-gray-800">Titre (Anglais) :</p>
                            <p class="text-base italic text-gray-600">{{ $package->title_en ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-gray-800">Description (Français) :</p>
                            <div class="mt-1 prose max-w-none text-gray-700">
                                {!! nl2br(e($package->description_fr)) !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BLOC 3: SERVICES INCLUS/EXCLUS --}}
                <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
                    <h3 class="text-xl font-bold text-primary mb-4 border-b pb-2"><i class="fas fa-check-circle mr-2"></i>
                        Services</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="text-lg font-semibold text-green-600 mb-2">Inclusions</p>
                            <ul class="list-none space-y-1">
                                @forelse ($includedServices as $service)
                                    <li class="flex items-start text-sm text-gray-700">
                                        <i class="fas fa-check text-green-500 w-4 mt-1 mr-2 flex-shrink-0"></i>
                                        <span>{{ $service }}</span>
                                    </li>
                                @empty
                                    <p class="text-sm italic text-gray-500">Aucun service inclus spécifié.</p>
                                @endforelse
                            </ul>
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-red-600 mb-2">Exclusions</p>
                            <ul class="list-none space-y-1">
                                @forelse ($excludedServices as $service)
                                    <li class="flex items-start text-sm text-gray-700">
                                        <i class="fas fa-times text-red-500 w-4 mt-1 mr-2 flex-shrink-0"></i>
                                        <span>{{ $service }}</span>
                                    </li>
                                @empty
                                    <p class="text-sm italic text-gray-500">Aucun service exclu spécifié.</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- BLOC 4: ITINÉRAIRE DÉTAILLÉ --}}
                <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
                    <h3 class="text-xl font-bold text-primary mb-4 border-b pb-2"><i class="fas fa-route mr-2"></i>
                        Itinéraire ({{ count($itinerary) }} jours)</h3>
                    <div class="space-y-6">
                        @forelse ($itinerary as $day)
                            <div class="p-4 border border-gray-100 rounded-lg bg-gray-50">
                                <p class="text-md font-bold text-dark mb-1">Jour {{ $day['day'] ?? '?' }}:
                                    {{ $day['title'] ?? 'Titre Manquant' }}</p>
                                <p class="text-sm text-gray-600">{{ $day['description'] ?? 'Description non disponible.' }}</p>
                            </div>
                        @empty
                            <p class="text-sm italic text-gray-500">L'itinéraire jour par jour n'est pas encore spécifié.</p>
                        @endforelse
                    </div>
                </div>

                {{-- BLOC 5: GALERIE PHOTOS --}}
                <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
                    <h3 class="text-xl font-bold text-primary mb-4 border-b pb-2"><i class="fas fa-images mr-2"></i> Galerie
                        Photos ({{ $galleryImages->count() }})</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse ($galleryImages as $media)
                            <div class="h-32 overflow-hidden rounded-lg shadow-md hover:opacity-80 transition duration-300">
                                <img src="{{ $media->getUrl('small') }}" alt="Image de galerie"
                                    class="w-full h-full object-cover">
                            </div>
                        @empty
                            <p class="col-span-3 text-sm italic text-gray-500">Aucune photo dans la galerie.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>


    </div>

@endsection