@extends('admin.layouts.app')

@section('title', 'Gestion des Packages Touristiques')

@section('content')

<div class="max-w-8xl mx-auto py-8">
<div class="flex justify-between items-center mb-8 border-b pb-2">
<h1 class="text-3xl font-bold text-dark gradient-text">Catalogue des Packages ({{ $packages->total() }})</h1>
<a href="{{ route('admin.packages.create') }}" class="py-2 px-4 rounded-lg text-white font-semibold bg-green-600 hover:bg-green-700 transition duration-300 shadow-md flex items-center">
<i class="fas fa-plus-circle mr-2"></i> Ajouter un nouveau Package
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

{{-- GRILLE D'AFFICHAGE DES PACKAGES (Cartes) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse ($packages as $package)
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 transform hover:scale-[1.02] transition duration-300 relative">
            
            {{-- Image du Package --}}
            <a href="{{ route('admin.packages.show', $package) }}" class="block h-48 overflow-hidden group">
                @php
                    // Utilisation de la méthode de Spatie pour récupérer l'URL de l'image 'normal'
                    $imageUrl = $package->getFirstMediaUrl('avatar', 'normal');
                    $placeholder = 'https://placehold.co/800x480/4c1d95/ffffff?text=Image+Package+Voyage';
                @endphp
                <img src="{{ $imageUrl ?: $placeholder }}" 
                     alt="{{ $package->title_fr }}" 
                     class="w-full h-full object-cover transition duration-500 group-hover:opacity-90 group-hover:scale-105" 
                     onerror="this.onerror=null;this.src='{{ $placeholder }}';">
            </a>

            {{-- Contenu de la Carte --}}
            <div class="p-5">
                
                {{-- Statut et Catégorie --}}
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-semibold px-3 py-1 rounded-full 
                        @if($package->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                        {{ $package->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                    <span class="text-xs font-medium text-gray-600 border border-gray-200 px-3 py-1 rounded-full">
                        {{ $package->category->name_fr ?? 'Non Catégorisé' }}
                    </span>
                </div>

                {{-- Titre --}}
                <h2 class="text-xl font-bold text-gray-900 mb-2 truncate" title="{{ $package->title_fr }}">
                    {{ $package->title_fr }}
                </h2>

                {{-- Infos Clés --}}
                <div class="text-sm text-gray-600 space-y-1 mt-3 border-t pt-3">
                    <p class="flex items-center"><i class="fas fa-clock w-5 text-primary mr-2"></i> 
                        {{ $package->duration_text_fr ?: $package->duration . ' jours' }}
                    </p>
                    <p class="flex items-center"><i class="fas fa-plane w-5 text-primary mr-2"></i> 
                        Destination : **{{ $package->destination }}**
                    </p>
                    <p class="flex items-center"><i class="fas fa-city w-5 text-primary mr-2"></i> 
                        Départ de {{ $package->departure_city ?: 'Non spécifié' }}
                    </p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-tag w-5 text-primary mr-2"></i> 
                        @if ($package->discount_price && $package->discount_price < $package->price)
                            <span class="text-sm font-semibold text-gray-500 line-through mr-2">{{ number_format($package->price, 2, ',', ' ') }} €</span>
                            <span class="text-lg font-bold text-red-600">
                                {{ number_format($package->discount_price, 2, ',', ' ') }} €
                            </span>
                        @else
                            <span class="text-lg font-bold text-primary">
                                {{ number_format($package->price, 2, ',', ' ') }} €
                            </span>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Bloc d'Actions --}}
            <div class="p-5 pt-0 flex justify-between items-center border-t mt-3">
                <a href="{{ route('admin.packages.show', $package) }}" class="text-sm font-semibold text-primary hover:text-purple-700 transition duration-150 flex items-center">
                    <i class="fas fa-eye mr-2"></i> Voir Détails
                </a>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.packages.edit', $package) }}" title="Modifier"
                        class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-100 transition duration-150">
                        <i class="fas fa-edit"></i>
                    </a>
                    {{-- Formulaire de suppression --}}
                    <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce package ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Supprimer"
                            class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition duration-150">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Badge "Featured" --}}
            @if ($package->is_featured)
            <div class="absolute top-0 right-0 mt-3 mr-3 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg transform rotate-3">
                EN VEDETTE
            </div>
            @endif
        </div>
    @empty
        <div class="col-span-full bg-white p-6 rounded-xl shadow-lg border border-gray-100 text-center">
            <p class="text-xl text-gray-500">Aucun package touristique n'a été trouvé.</p>
            <a href="{{ route('admin.packages.create') }}" class="text-primary hover:underline mt-2 inline-block">Créer le premier package</a>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="mt-8">
    {{ $packages->links() }}
</div>


</div>

@endsection