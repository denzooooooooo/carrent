@extends('layouts.app')

@section('title', __('Résultats de recherche pour') . ' "' . $q . '" - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 pt-20">
    <div class="container mx-auto px-4 py-8">
        {{-- En-tête de recherche --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-amber-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white">
                        {{ __('Résultats de recherche') }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ __('Recherche pour') }} "<span class="font-semibold text-purple-600 dark:text-purple-400">{{ $q }}</span>"
                    </p>
                </div>
            </div>
            
            {{-- Nouvelle recherche --}}
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <form action="{{ route('search') }}" method="GET" class="flex">
                        <input
                            type="text"
                            name="q"
                            value="{{ $q }}"
                            placeholder="{{ __('Rechercher...') }}"
                            class="flex-1 px-4 py-3 rounded-l-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        >
                        <button
                            type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-purple-600 to-amber-600 text-white rounded-r-xl hover:from-purple-700 hover:to-amber-700 transition-all duration-300"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Résultats --}}
        <div class="space-y-8">
            {{-- Packages --}}
            @if($packages->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h2 class="text-xl font-black text-white">{{ __('Packages') }} ({{ $packages->count() }})</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($packages as $package)
                                <a href="{{ route('packages.show', $package->slug) }}" class="group block">
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group-hover:-translate-y-1">
                                        @if($package->getFirstMediaUrl('gallery', 'thumb'))
                                            <img
                                                src="{{ $package->getFirstMediaUrl('gallery', 'thumb') }}"
                                                alt="{{ $package->title_fr }}"
                                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                            >
                                        @else
                                            <div class="w-full h-48 bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="p-4">
                                            <h3 class="font-bold text-gray-900 dark:text-white mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                                {{ $package->title_fr }}
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                                {{ Str::limit($package->description_fr, 100) }}
                                            </p>
                                            <div class="flex items-center justify-between">
                                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">
                                                    {{ __('À partir de') }} {{ \App\Helpers\CurrencyHelper::format($package->price) }}
                                                </span>
                                                <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Événements --}}
            @if($events->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-rose-500 to-pink-600 px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            <h2 class="text-xl font-black text-white">{{ __('Événements') }} ({{ $events->count() }})</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($events as $event)
                                <a href="{{ route('events.show', $event->slug) }}" class="group block">
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group-hover:-translate-y-1">
                                        @if($event->getFirstMediaUrl('avatar', 'thumb'))
                                            <img
                                                src="{{ $event->getFirstMediaUrl('avatar', 'thumb') }}"
                                                alt="{{ $event->title_fr }}"
                                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                            >
                                        @else
                                            <div class="w-full h-48 bg-gradient-to-br from-rose-400 to-pink-500 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="p-4">
                                            <h3 class="font-bold text-gray-900 dark:text-white mb-2 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                                                {{ $event->title_fr }}
                                            </h3>
                                            <div class="flex items-center space-x-2 mb-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $event->event_date ? $event->event_date->format('d/m/Y') : __('Date à confirmer') }}
                                                </span>
                                            </div>
                                            <div class="flex items-center space-x-2 mb-3">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                </svg>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $event->venue_name ?? $event->city }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-rose-600 dark:text-rose-400 font-bold">
                                                    {{ $event->min_price ? __('À partir de') . ' ' . \App\Helpers\CurrencyHelper::format($event->min_price) : __('Sur demande') }}
                                                </span>
                                                <svg class="w-5 h-5 text-gray-400 group-hover:text-rose-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Pages --}}
            @if($pages->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h2 class="text-xl font-black text-white">{{ __('Pages') }} ({{ $pages->count() }})</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($pages as $page)
                                <a href="{{ $page->slug ? '/' . $page->slug : '/' }}" class="group block p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                    {{ $page->title }}
                                                </h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ Str::limit($page->content ?? '', 120) }}
                                                </p>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Locations --}}
            @if($locations->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <h2 class="text-xl font-black text-white">{{ __('Destinations') }} ({{ $locations->count() }})</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($locations as $location)

                                <a href="{{ route('location.show', ['location' => $location->slug]) }}" class="group block">
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group-hover:-translate-y-1">
                                        @if($location->image_url)
                                            <img
                                                src="{{ $location->image_url }}"
                                                alt="{{ $location->name }}"
                                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                            >
                                        @else
                                            <div class="w-full h-48 bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="p-4">
                                            <h3 class="font-bold text-gray-900 dark:text-white mb-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                                                {{ $location->name }}
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                                {{ Str::limit($location->description, 100) }}
                                            </p>
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2 text-sm text-gray-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    </svg>
                                                    <span>{{ $location->city ?? $location->country }}</span>
                                                </div>
                                                <svg class="w-5 h-5 text-gray-400 group-hover:text-amber-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Aucun résultat --}}
            @if($packages->count() == 0 && $events->count() == 0 && $pages->count() == 0 && $locations->count() == 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ __('Aucun résultat trouvé') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                        {{ __('Nous n\'avons trouvé aucun résultat pour votre recherche. Essayez avec d\'autres mots-clés ou consultez nos') }}
                        <a href="{{ route('packages') }}" class="text-purple-600 dark:text-purple-400 hover:underline">{{ __('packages') }}</a>
                        {{ __('et') }}
                        <a href="{{ route('events') }}" class="text-purple-600 dark:text-purple-400 hover:underline">{{ __('événements') }}</a>.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a
                            href="{{ route('packages') }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-teal-700 transition-all duration-300"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('Voir les packages') }}
                        </a>
                        <a
                            href="{{ route('events') }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-600 text-white font-semibold rounded-xl hover:from-rose-600 hover:to-pink-700 transition-all duration-300"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            {{ __('Voir les événements') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
