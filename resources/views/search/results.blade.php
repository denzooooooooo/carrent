@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Résultats de la recherche</h1>

    <form action="{{ route('search') }}" method="GET" class="mb-6">
        <input type="search" name="q" value="{{ old('q', $q ?? '') }}" placeholder="Rechercher..." class="w-full md:w-1/2 px-3 py-2 border rounded" />
    </form>

    @if(empty($q))
        <p class="text-gray-600">Entrez un terme de recherche pour trouver des packages, événements ou vols.</p>
    @else
        <p class="text-sm text-gray-500 mb-4">Recherche pour : <strong>{{ $q }}</strong></p>

        {{-- Packages --}}
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-3">Packages touristiques</h2>
            @if(!empty($packages) && $packages->count())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($packages as $p)
                        <a href="{{ route('packages.show', $p->slug) }}" class="block p-4 border rounded hover:shadow">
                            <h3 class="font-bold">{{ $p->title_fr ?? $p->title_en ?? 'Package' }}</h3>
                            <p class="text-sm text-gray-600 truncate">{{ \Illuminate\Support\Str::limit(strip_tags($p->description_fr ?? $p->description_en ?? ''), 120) }}</p>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">Aucun package trouvé.</p>
            @endif
        </section>

        {{-- Events --}}
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-3">Événements</h2>
            @if(!empty($events) && $events->count())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($events as $e)
                        <a href="{{ route('events.show', $e->slug) }}" class="block p-4 border rounded hover:shadow">
                            <h3 class="font-bold">{{ $e->title_fr ?? $e->title_en ?? 'Événement' }}</h3>
                            <p class="text-sm text-gray-600 truncate">{{ \Illuminate\Support\Str::limit(strip_tags($e->description_fr ?? $e->description_en ?? ''), 120) }}</p>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">Aucun événement trouvé.</p>
            @endif
        </section>

        {{-- Flights --}}
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-3">Vols</h2>
            @if(!empty($flights) && $flights->count())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($flights as $f)
                        <div class="p-4 border rounded">
                            <h3 class="font-bold">{{ $f->flight_number ?? 'Vol' }}</h3>
                            <p class="text-sm text-gray-600">{{ optional($f->departureAirport)->iata_code ?? '' }} → {{ optional($f->arrivalAirport)->iata_code ?? '' }} • {{ optional($f->departure_date)->format('d M Y') }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">Aucun vol trouvé.</p>
            @endif
        </section>

        {{-- Pages --}}
        <section>
            <h2 class="text-xl font-semibold mb-3">Pages</h2>
            @if(!empty($pages) && $pages->count())
                <ul class="space-y-2">
                    @foreach($pages as $pg)
                        <li><a href="/{{ $pg->slug ?? '' }}" class="text-indigo-600 hover:underline">{{ $pg->title ?? ($pg->slug ?? 'Page') }}</a></li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">Aucune page trouvée.</p>
            @endif
        </section>
    @endif
</div>
@endsection
