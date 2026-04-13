@extends('admin.layouts.app')

@section('title', 'Gestion des Locations')

@section('content')

    <div class="max-w-8xl mx-auto py-8">
        <div class="flex justify-between items-center mb-8 border-b pb-2">
            <h1 class="text-3xl font-bold text-dark gradient-text">Catalogue des Locations ({{ $locations->total() }})</h1>
            <a href="{{ route('admin.locations.create') }}"
                class="py-2 px-4 rounded-lg text-white font-semibold bg-green-600 hover:bg-green-700 transition duration-300 shadow-md flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> Ajouter une Location
            </a>
        </div>

        {{-- Messages de Session --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{!! session('success') !!}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Erreur!</strong>
                <span class="block sm:inline">{!! session('error') !!}</span>
            </div>
        @endif

        {{-- GRILLE D'AFFICHAGE DES LOCATIONS (Cartes) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($locations as $location)
                <div
                    class="bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 transform hover:scale-[1.02] transition duration-300 relative">

                    {{-- Image de la Location --}}
                    <a href="{{ route('admin.locations.show', $location) }}" class="block h-48 overflow-hidden group">
                        @php
                            $imageUrl = $location->image_url;
                            $placeholder = 'https://placehold.co/800x480/4c1d95/ffffff?text=Image+Location';
                        @endphp
                        <img src="{{ $imageUrl ?: $placeholder }}" alt="{{ $location->name }}"
                            class="w-full h-full object-cover transition duration-500 group-hover:opacity-90 group-hover:scale-105"
                         onerror="this.onerror=null;this.src='{{ $placeholder }}';">
                    </a>

                    {{-- Contenu de la carte --}}
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-bold text-gray-800 hover:text-primary transition duration-150">
                                <a href="{{ route('admin.locations.show', $location) }}">{{ $location->name }}</a>
                            </h3>
                            <span class="text-xs font-semibold px-2 py-1 rounded-full
                                @if($location->category === 'terrestre') bg-green-100 text-green-800
                                @elseif($location->category === 'aérien') bg-blue-100 text-blue-800
                                @else bg-purple-100 text-purple-800 @endif">
                                {{ ucfirst($location->category) }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $location->description }}</p>

                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg font-black bg-gradient-to-r from-amber-300 to-pink-300 bg-clip-text text-transparent">
                                {{ number_format($location->price_per_day, 0, ',', ' ') }} FCFA/jour
                            </span>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-users mr-1"></i>{{ $location->capacity }} pers.
                            </span>
                        </div>

                        {{-- Actions --}}
                        <div class="flex justify-between items-center">
                            <a href="{{ route('admin.locations.show', $location) }}"
                                class="text-sm font-semibold text-primary hover:text-purple-700 transition duration-150 flex items-center">
                                <i class="fas fa-eye mr-2"></i> Voir Détails
                            </a>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.locations.edit', $location) }}" title="Modifier"
                                    class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-100 transition duration-150">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- Formulaire de suppression --}}
                                <form action="{{ route('admin.locations.destroy', $location) }}" method="POST"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette location ? Cette action est irréversible.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Supprimer"
                                        class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition duration-150">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Badge "Actif/Inactif" --}}
                    <div class="absolute top-0 right-0 mt-3 mr-3">
                        <span class="text-xs font-semibold px-3 py-1 rounded-full shadow-lg
                            @if($location->is_active) bg-green-600 text-white @else bg-red-600 text-white @endif">
                            {{ $location->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-6 rounded-xl shadow-lg border border-gray-100 text-center">
                    <p class="text-xl text-gray-500">Aucune location n'a été trouvée.</p>
                    <a href="{{ route('admin.locations.create') }}" class="text-primary hover:underline mt-2 inline-block">Créer la première location</a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $locations->links() }}
        </div>


    </div>

@endsection
