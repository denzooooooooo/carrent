@extends('admin.layouts.app')

@section('title', $eventSeries->name_fr)

@section('content')

<div class="max-w-8xl mx-auto py-8">
    <div class="flex justify-between items-center mb-8 border-b pb-2">
        <h1 class="text-3xl font-bold text-dark gradient-text">{{ $eventSeries->name_fr }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.event-series.edit', $eventSeries) }}" class="py-2 px-4 rounded-lg text-white font-semibold bg-blue-600 hover:bg-blue-700 transition duration-300 shadow-md flex items-center">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <a href="{{ route('admin.event-series.index') }}" class="py-2 px-4 rounded-lg text-white font-semibold bg-gray-600 hover:bg-gray-700 transition duration-300 shadow-md flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Succès!</strong>
            <span class="block sm:inline">{!! session('success') !!}</span>
        </div>
    @endif

    {{-- Info Série --}}
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                @php
                    $imageUrl = $eventSeries->getFirstMediaUrl('main_image');
                    $placeholder = 'https://placehold.co/600x400/4c1d95/ffffff?text=' . urlencode($eventSeries->name_fr);
                @endphp
                <img src="{{ $imageUrl ?: $placeholder }}" alt="{{ $eventSeries->name_fr }}"
                    class="w-full h-48 object-cover rounded-lg"
                    onerror="this.onerror=null;this.src='{{ $placeholder }}';">
            </div>
            <div class="md:col-span-2">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full 
                            @if($eventSeries->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                            {{ $eventSeries->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($eventSeries->is_featured)
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-red-100 text-red-800 ml-2">
                                Vedette
                            </span>
                        @endif
                    </div>
                
                <h2 class="text-xl font-bold mb-2">{{ $eventSeries->name_en }}</h2>
                <p class="text-gray-600 mb-4">{{ $eventSeries->description_fr }}</p>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    @if($eventSeries->venue_name)
                        <div>
                            <span class="text-gray-500">Lieu:</span>
                            <p class="font-medium">{{ $eventSeries->venue_name }}</p>
                        </div>
                    @endif
                    @if($eventSeries->city)
                        <div>
                            <span class="text-gray-500">Ville:</span>
                            <p class="font-medium">{{ $eventSeries->city }}</p>
                        </div>
                    @endif
                    @if($eventSeries->country)
                        <div>
                            <span class="text-gray-500">Pays:</span>
                            <p class="font-medium">{{ $eventSeries->country }}</p>
                        </div>
                    @endif
                    @if($eventSeries->start_date)
                        <div>
                            <span class="text-gray-500">Dates:</span>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($eventSeries->start_date)->format('d M Y') }}
                                @if($eventSeries->end_date)
                                    - {{ \Carbon\Carbon::parse($eventSeries->end_date)->format('d M Y') }}
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
        </div>

    {{-- Événements de la série --}}
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Matchs / Événements ({{ $eventSeries->events->count() }})</h2>
            <a href="{{ route('admin.events.create', ['series_id' => $eventSeries->id]) }}" 
                class="py-2 px-4 rounded-lg text-white font-semibold bg-green-600 hover:bg-green-700 transition duration-300 shadow-md flex items-center">
                <i class="fas fa-plus mr-2"></i> Ajouter un Match
            </a>
        </div>

        @if($eventSeries->events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($eventSeries->events as $event)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                {{ $event->match_number ?? 'Match' }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                            </span>
                        </div>
                        <h3 class="font-bold text-lg mb-1 truncate">{{ $event->title_fr }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $event->venue_name }}, {{ $event->city }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-primary">
                                À partir de {{ number_format($event->min_price, 0, ',', ' ') }} XAF
                            </span>
                            <div class="flex space-x-1">
                                <a href="{{ route('admin.events.edit', $event) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-calendar-times text-4xl mb-4"></i>
                <p>Aucun événement dans cette série.</p>
                <a href="{{ route('admin.events.create', ['series_id' => $eventSeries->id]) }}" class="text-primary hover:underline mt-2 inline-block">
                    Ajouter le premier match
                </a>
            </div>
        @endif
    </div>

@endsection
