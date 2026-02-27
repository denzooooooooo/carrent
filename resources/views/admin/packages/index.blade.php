@extends('admin.layouts.app')

@section('title', 'Gestion des Packages Touristiques')

@section('content')

@php
$typeConfig = [
    'sport_event' => ['label' => 'Événement Sportif', 'icon' => 'fa-trophy',        'color' => 'bg-orange-100 text-orange-800 border-orange-200'],
    'motorsport'  => ['label' => 'Motorsport / F1',   'icon' => 'fa-flag-checkered', 'color' => 'bg-red-100 text-red-800 border-red-200'],
    'football'    => ['label' => 'Football',           'icon' => 'fa-futbol',         'color' => 'bg-green-100 text-green-800 border-green-200'],
    'helicopter'  => ['label' => 'Hélicoptère',        'icon' => 'fa-helicopter',     'color' => 'bg-sky-100 text-sky-800 border-sky-200'],
    'private_jet' => ['label' => 'Jet Privé',          'icon' => 'fa-plane',          'color' => 'bg-indigo-100 text-indigo-800 border-indigo-200'],
    'cruise'      => ['label' => 'Croisière',          'icon' => 'fa-ship',           'color' => 'bg-blue-100 text-blue-800 border-blue-200'],
    'safari'      => ['label' => 'Safari',             'icon' => 'fa-paw',            'color' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
    'city_tour'   => ['label' => 'Visite de Ville',   'icon' => 'fa-city',           'color' => 'bg-purple-100 text-purple-800 border-purple-200'],
    'adventure'   => ['label' => 'Aventure',           'icon' => 'fa-mountain',       'color' => 'bg-lime-100 text-lime-800 border-lime-200'],
    'luxury'      => ['label' => 'Luxe',               'icon' => 'fa-gem',            'color' => 'bg-pink-100 text-pink-800 border-pink-200'],
];
@endphp

<div class="max-w-8xl mx-auto py-8 px-4">

    {{-- ===== EN-TÊTE ===== --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-suitcase-rolling text-primary mr-2"></i>
                Packages Touristiques
            </h1>
            <p class="text-gray-500 mt-1">{{ $packages->total() }} package(s) au total</p>
        </div>
        <a href="{{ route('admin.packages.create') }}"
           class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-primary to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-purple-700 hover:to-primary transition-all duration-300">
            <i class="fas fa-plus-circle mr-2"></i> Nouveau Package
        </a>
    </div>

    {{-- ===== MESSAGES SESSION ===== --}}
    @if (session('success'))
        <div class="flex items-center bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-5 shadow-sm">
            <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
            <span>{!! session('success') !!}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-5 shadow-sm">
            <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg"></i>
            <span>{!! session('error') !!}</span>
        </div>
    @endif

    {{-- ===== BARRE DE FILTRES ===== --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-5 mb-8">
        <form method="GET" action="{{ route('admin.packages.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

            {{-- Recherche --}}
            <div class="lg:col-span-2 relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher un package..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-sm transition">
            </div>

            {{-- Type --}}
            <div>
                <select name="package_type" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary text-sm transition">
                    <option value="">Tous les types</option>
                    @foreach($packageTypes as $key => $label)
                        <option value="{{ $key }}" {{ request('package_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Catégorie --}}
            <div>
                <select name="category_id" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary text-sm transition">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name_fr }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Statut + Boutons --}}
            <div class="flex gap-2">
                <select name="is_active" class="flex-1 px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary text-sm transition">
                    <option value="">Tous statuts</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Actif</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactif</option>
                </select>
                <button type="submit" class="px-4 py-2.5 bg-primary text-white rounded-xl hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-filter"></i>
                </button>
                @if(request()->hasAny(['search','package_type','category_id','is_active']))
                    <a href="{{ route('admin.packages.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition text-sm font-medium" title="Réinitialiser">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- ===== GRILLE DES PACKAGES ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($packages as $package)
            @php
                $type    = $package->package_type ?? 'luxury';
                $cfg     = $typeConfig[$type] ?? ['label' => $type, 'icon' => 'fa-tag', 'color' => 'bg-gray-100 text-gray-700 border-gray-200'];
                $currency = $package->currency ?? 'XOF';
                $currencySymbol = match($currency) { 'EUR' => '€', 'USD' => '$', default => 'FCFA' };
                $priceDecimals  = $currency === 'XOF' ? 0 : 2;
                $imageUrl    = $package->getFirstMediaUrl('avatar', 'normal');
                $placeholder = 'https://placehold.co/800x480/4c1d95/ffffff?text=' . urlencode($package->title_fr);
                $isSport = in_array($type, ['sport_event', 'motorsport', 'football']);
            @endphp

            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden flex flex-col hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group">

                {{-- Image --}}
                <a href="{{ route('admin.packages.show', $package) }}" class="block h-48 overflow-hidden relative">
                    <img src="{{ $imageUrl ?: $placeholder }}"
                         alt="{{ $package->title_fr }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         onerror="this.src='{{ $placeholder }}'">
                    {{-- Overlay gradient --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>

                {{-- Badge EN VEDETTE --}}
                @if ($package->is_featured)
                    <div class="absolute top-3 left-3 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-lg flex items-center gap-1">
                        <i class="fas fa-star text-xs"></i> VEDETTE
                    </div>
                @endif

                {{-- Contenu --}}
                <div class="p-4 flex flex-col flex-1">

                    {{-- Badges statut + type --}}
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full border
                            {{ $package->is_active ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $package->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ $package->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full border {{ $cfg['color'] }}">
                            <i class="fas {{ $cfg['icon'] }} mr-1.5 text-xs"></i>
                            {{ $cfg['label'] }}
                        </span>
                    </div>

                    {{-- Titre --}}
                    <h2 class="text-base font-bold text-gray-900 mb-1 line-clamp-2 leading-snug" title="{{ $package->title_fr }}">
                        {{ $package->title_fr }}
                    </h2>

                    {{-- Catégorie --}}
                    <p class="text-xs text-gray-500 mb-3">
                        <i class="fas fa-layer-group mr-1"></i>{{ $package->category->name_fr ?? 'Non catégorisé' }}
                    </p>

                    {{-- Infos --}}
                    <div class="text-sm text-gray-600 space-y-1.5 border-t pt-3 flex-1">
                        <p class="flex items-center gap-2">
                            <i class="fas fa-map-marker-alt w-4 text-primary text-xs"></i>
                            <span class="truncate">{{ $package->destination }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fas fa-clock w-4 text-primary text-xs"></i>
                            {{ $package->duration_text_fr ?: $package->duration . ' jour(s)' }}
                        </p>

                        {{-- Dates événement (sport uniquement) --}}
                        @if($isSport && $package->event_date_start)
                            <p class="flex items-center gap-2 text-orange-600 font-medium">
                                <i class="fas fa-calendar-alt w-4 text-xs"></i>
                                {{ \Carbon\Carbon::parse($package->event_date_start)->format('d/m/Y') }}
                                @if($package->event_date_end && $package->event_date_end != $package->event_date_start)
                                    → {{ \Carbon\Carbon::parse($package->event_date_end)->format('d/m/Y') }}
                                @endif
                            </p>
                        @endif

                        {{-- Prix --}}
                        <div class="flex items-center gap-2 mt-2">
                            <i class="fas fa-tag w-4 text-primary text-xs"></i>
                            @if ($package->discount_price && $package->discount_price < $package->price)
                                <span class="text-xs text-gray-400 line-through">
                                    {{ number_format($package->price, $priceDecimals, ',', ' ') }} {{ $currencySymbol }}
                                </span>
                                <span class="text-base font-bold text-red-600">
                                    {{ number_format($package->discount_price, $priceDecimals, ',', ' ') }} {{ $currencySymbol }}
                                </span>
                            @else
                                <span class="text-base font-bold text-primary">
                                    {{ number_format($package->price, $priceDecimals, ',', ' ') }} {{ $currencySymbol }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                    <a href="{{ route('admin.packages.show', $package) }}"
                       class="text-sm font-semibold text-primary hover:text-purple-700 transition flex items-center gap-1">
                        <i class="fas fa-eye text-xs"></i> Détails
                    </a>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.packages.edit', $package) }}"
                           title="Modifier"
                           class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition">
                            <i class="fas fa-edit text-sm"></i>
                        </a>
                        <form action="{{ route('admin.packages.destroy', $package) }}" method="POST"
                              onsubmit="return confirm('Supprimer ce package ? Action irréversible.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Supprimer"
                                    class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-span-full bg-white rounded-2xl shadow-md border border-dashed border-gray-300 p-12 text-center">
                <i class="fas fa-suitcase-rolling text-5xl text-gray-300 mb-4"></i>
                <p class="text-xl font-semibold text-gray-500 mb-2">Aucun package trouvé</p>
                <p class="text-gray-400 mb-6">Essayez de modifier vos filtres ou créez un nouveau package.</p>
                <a href="{{ route('admin.packages.create') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-purple-700 transition">
                    <i class="fas fa-plus-circle mr-2"></i> Créer un package
                </a>
            </div>
        @endforelse
    </div>

    {{-- ===== PAGINATION ===== --}}
    <div class="mt-8 flex justify-center">
        {{ $packages->links() }}
    </div>

</div>

@endsection
