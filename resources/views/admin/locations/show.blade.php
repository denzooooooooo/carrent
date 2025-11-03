@extends('admin.layouts.app')

@section('title', 'Détails de la Location')

@section('content')

<div class="max-w-6xl mx-auto py-8">
<div class="flex justify-between items-center mb-8 border-b pb-2">
<h1 class="text-3xl font-bold text-dark gradient-text">Détails de la Location : <span class="text-primary">{{ $location->name }}</span></h1>
<div class="flex space-x-3">
<a href="{{ route('admin.locations.edit', $location) }}" class="py-2 px-4 rounded-lg text-white font-semibold bg-blue-600 hover:bg-blue-700 transition duration-300 shadow-md flex items-center">
<i class="fas fa-edit mr-2"></i> Modifier
</a>
<a href="{{ route('admin.locations.index') }}" class="py-2 px-4 rounded-lg text-white font-semibold bg-gray-600 hover:bg-gray-700 transition duration-300 shadow-md flex items-center">
<i class="fas fa-arrow-left mr-2"></i> Retour à la liste
</a>
</div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- COLONNE GAUCHE (IMAGE ET INFOS CLÉS) --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white p-4 rounded-xl shadow-xl border border-gray-100">
            @php
                $imageUrl = $location->getImageUrl();
                $placeholder = 'https://placehold.co/800x600/4c1d95/ffffff?text=Image+Location';
            @endphp
            <img src="{{ $imageUrl ?: $placeholder }}"
                 alt="Image de {{ $location->name }}"
                 class="w-full h-auto object-cover rounded-lg shadow-lg"
                 onerror="this.onerror=null;this.src='{{ $placeholder }}';">
        </div>

        <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
            <h3 class="text-xl font-bold text-dark mb-4 border-b pb-2">Informations Clés</h3>
            <ul class="space-y-3 text-gray-700">
                <li class="flex justify-between items-center">
                    <span class="font-medium">Catégorie :</span>
                    <span class="text-sm font-semibold px-3 py-1 rounded-full
                        @if($location->category === 'terrestre') bg-green-100 text-green-800
                        @elseif($location->category === 'aérien') bg-blue-100 text-blue-800
                        @else bg-purple-100 text-purple-800 @endif">
                        {{ ucfirst($location->category) }}
                    </span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="font-medium">Type :</span>
                    <span class="font-semibold text-primary">{{ $location->type }}</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="font-medium">Prix par jour :</span>
                    <span class="font-bold text-lg text-primary">{{ number_format($location->price_per_day, 0, ',', ' ') }} FCFA</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="font-medium">Capacité :</span>
                    <span class="font-semibold">{{ $location->capacity }} personne(s)</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="font-medium">Statut :</span>
                    <span class="text-sm font-semibold px-3 py-1 rounded-full
                        @if($location->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                        {{ $location->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </li>
            </ul>
        </div>
    </div>

    {{-- COLONNE DROITE (DESCRIPTIONS ET CARACTÉRISTIQUES) --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- BLOC DESCRIPTIONS --}}
        <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
            <h3 class="text-xl font-bold text-primary mb-4 border-b pb-2"><i class="fas fa-file-alt mr-2"></i> Descriptions</h3>
            <div class="space-y-6">
                <div>
                    <p class="text-lg font-semibold text-gray-800">Nom (Français) :</p>
                    <p class="text-base italic text-gray-600">{{ $location->name_fr }}</p>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-800">Nom (Anglais) :</p>
                    <p class="text-base italic text-gray-600">{{ $location->name_en }}</p>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-800">Description (Français) :</p>
                    <div class="mt-1 prose max-w-none text-gray-700">
                        {!! nl2br(e($location->description_fr)) ?: '<em class="text-gray-500">Non spécifiée</em>' !!}
                    </div>
                </div>
                <div class="border-t pt-4">
                    <p class="text-lg font-semibold text-gray-800">Description (Anglais) :</p>
                    <div class="mt-1 prose max-w-none text-gray-700">
                        {!! nl2br(e($location->description_en)) ?: '<em class="text-gray-500">Non spécifiée</em>' !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOC CARACTÉRISTIQUES --}}
        @if($location->features && count($location->features) > 0)
        <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
            <h3 class="text-xl font-bold text-primary mb-4 border-b pb-2"><i class="fas fa-star mr-2"></i> Caractéristiques</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($location->features as $feature)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <span class="text-gray-700">{{ $feature }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- BLOC ACTIONS --}}
        <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
            <h3 class="text-xl font-bold text-primary mb-4 border-b pb-2"><i class="fas fa-cogs mr-2"></i> Actions</h3>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.locations.edit', $location) }}"
                    class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>

                <form action="{{ route('admin.locations.destroy', $location) }}" method="POST"
                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette location ? Cette action est irréversible.');"
                    class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300">
                        <i class="fas fa-trash mr-2"></i> Supprimer
                    </button>
                </form>

                <a href="{{ route('admin.locations.index') }}"
                    class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
