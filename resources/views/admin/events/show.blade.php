@extends('admin.layouts.app')

@section('title', 'Détails de l\'Événement')

@section('content')

<div class="max-w-6xl mx-auto py-8">
<div class="flex justify-between items-center mb-8 border-b pb-2">
<h1 class="text-3xl font-bold text-dark gradient-text">Détails de l'Événement : <span class="text-primary">{{ $event->title_fr }}</span></h1>
<div class="flex space-x-3">
<a href="{{ route('admin.events.edit', $event) }}" class="py-2 px-4 rounded-lg text-white font-semibold bg-blue-600 hover:bg-blue-700 transition duration-300 shadow-md flex items-center">
<i class="fas fa-edit mr-2"></i> Modifier
</a>
<a href="{{ route('admin.events.index') }}" class="py-2 px-4 rounded-lg text-white font-semibold bg-gray-600 hover:bg-gray-700 transition duration-300 shadow-md flex items-center">
<i class="fas fa-arrow-left mr-2"></i> Retour à la liste
</a>
</div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- COLONNE GAUCHE (IMAGE ET INFOS CLÉS) --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white p-4 rounded-xl shadow-xl border border-gray-100">
            @php
                $imageUrl = $event->getFirstMediaUrl('avatar', 'normal');
                $placeholder = 'https://placehold.co/800x600/4c1d95/ffffff?text=Image+Event';
            @endphp
            <img src="{{ $imageUrl ?: $placeholder }}" 
                 alt="Image de {{ $event->title_fr }}" 
                 class="w-full h-auto object-cover rounded-lg shadow-lg" 
                 onerror="this.onerror=null;this.src='{{ $placeholder }}';">
        </div>

        <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
            <h3 class="text-xl font-bold text-dark mb-4 border-b pb-2">Statuts & Vente</h3>
            <ul class="space-y-3 text-gray-700">
                <li class="flex justify-between items-center">
                    <span class="font-medium">Statut :</span>
                    <span class="text-sm font-semibold px-3 py-1 rounded-full 
                        @if($event->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                        {{ $event->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="font-medium">Mis en avant :</span>
                    <span class="text-sm font-semibold">
                        {!! $event->is_featured ? '<i class="fas fa-check-circle text-green-500"></i> Oui' : '<i class="fas fa-times-circle text-gray-400"></i> Non' !!}
                    </span>
                </li>
                <li class="flex justify-between items-center border-t pt-3 mt-3">
                    <span class="font-bold">Prix Min. :</span>
                    <span class="font-bold text-lg text-primary">{{ number_format($event->min_price, 2, ',', ' ') }} €</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="font-bold">Prix Max. :</span>
                    <span class="font-bold text-lg text-primary">{{ $event->max_price ? number_format($event->max_price, 2, ',', ' ') . ' €' : 'N/A' }}</span>
                </li>
                <li class="flex justify-between items-center border-t pt-3 mt-3">
                    <span class="font-medium">Capacité Totale :</span>
                    <span>{{ number_format($event->total_seats, 0, ',', ' ') }}</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="font-medium text-green-600">Places Disponibles :</span>
                    <span class="font-semibold text-green-600">{{ number_format($event->available_seats, 0, ',', ' ') }}</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- COLONNE DROITE (DÉTAILS COMPLETS) --}}
    <div class="lg:col-span-2 space-y-8">
        
        {{-- BLOC 1: INFOS ÉVÉNEMENTIELLES --}}
        <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
            <h3 class="text-xl font-bold text-primary mb-4 border-b pb-2"><i class="fas fa-calendar-alt mr-2"></i> Informations de l'Événement</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
                <div>
                    <p class="text-sm font-semibold">Catégorie :</p>
                    <p class="text-base">{{ $event->category->name_fr ?? 'Non définie' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold">Type :</p>
                    <p class="text-base">{{ $event->type->name_fr ?? 'Non défini' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold">Organisateur :</p>
                    <p class="text-base">{{ $event->organizer ?: 'Non spécifié' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold">Date de Début :</p>
                    <p class="text-base">{{ \Carbon\Carbon::parse($event->event_date)->format('d F Y') }} à {{ $event->event_time }}</p>
                </div>
                @if($event->end_date)
                <div>
                    <p class="text-sm font-semibold">Date de Fin :</p>
                    <p class="text-base">{{ \Carbon\Carbon::parse($event->end_date)->format('d F Y') }} à {{ $event->end_time ?: 'N/A' }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- BLOC 2: LIEU --}}
        <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
            <h3 class="text-xl font-bold text-primary mb-4 border-b pb-2"><i class="fas fa-map-marker-alt mr-2"></i> Localisation</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
                <div>
                    <p class="text-sm font-semibold">Nom du Lieu :</p>
                    <p class="text-base">{{ $event->venue_name }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold">Adresse Complète :</p>
                    <p class="text-base">{{ $event->venue_address }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold">Ville :</p>
                    <p class="text-base">{{ $event->city }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold">Pays :</p>
                    <p class="text-base">{{ $event->country }}</p>
                </div>
            </div>
        </div>
        
        {{-- BLOC 3: DESCRIPTIONS --}}
        <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
            <h3 class="text-xl font-bold text-primary mb-4 border-b pb-2"><i class="fas fa-file-alt mr-2"></i> Descriptions</h3>
            <div class="space-y-6">
                <div>
                    <p class="text-lg font-semibold text-gray-800">Titre (Anglais) :</p>
                    <p class="text-base italic text-gray-600">{{ $event->title_en }}</p>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-800">Description (Français) :</p>
                    <div class="mt-1 prose max-w-none text-gray-700">
                        {!! nl2br(e($event->description_fr)) !!}
                    </div>
                </div>
                <div class="border-t pt-4">
                    <p class="text-lg font-semibold text-gray-800">Description (Anglais) :</p>
                    <div class="mt-1 prose max-w-none text-gray-700">
                        {!! nl2br(e($event->description_en)) !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOC 4: SEO --}}
        <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
            <h3 class="text-xl font-bold text-primary mb-4 border-b pb-2"><i class="fas fa-search mr-2"></i> SEO (Optimisation Moteur)</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
                <div>
                    <p class="text-sm font-semibold">Méta Titre (Français) :</p>
                    <p class="text-base">{{ $event->meta_title_fr ?: 'Non spécifié' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold">Méta Titre (Anglais) :</p>
                    <p class="text-base">{{ $event->meta_title_en ?: 'Non spécifié' }}</p>
                </div>
                <div class="col-span-1 sm:col-span-2">
                    <p class="text-sm font-semibold">Méta Description (Français) :</p>
                    <p class="text-base text-sm">{{ $event->meta_description_fr ?: 'Non spécifié' }}</p>
                </div>
                <div class="col-span-1 sm:col-span-2">
                    <p class="text-sm font-semibold">Méta Description (Anglais) :</p>
                    <p class="text-base text-sm">{{ $event->meta_description_en ?: 'Non spécifié' }}</p>
                </div>
            </div>
        </div>
        
    </div>
</div>


</div>

@endsection