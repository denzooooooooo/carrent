@extends('admin.layouts.app')

@section('title', 'Détail package')

@section('content')
    @php
        $typeConfig = [
            'sport_event' => ['label' => 'Événement sportif', 'icon' => 'fa-trophy', 'tone' => 'bg-orange-100 text-orange-800'],
            'motorsport' => ['label' => 'Motorsport / F1', 'icon' => 'fa-flag-checkered', 'tone' => 'bg-red-100 text-red-800'],
            'football' => ['label' => 'Football', 'icon' => 'fa-futbol', 'tone' => 'bg-green-100 text-green-800'],
            'helicopter' => ['label' => 'Hélicoptère', 'icon' => 'fa-helicopter', 'tone' => 'bg-sky-100 text-sky-800'],
            'private_jet' => ['label' => 'Jet privé', 'icon' => 'fa-plane', 'tone' => 'bg-indigo-100 text-indigo-800'],
            'cruise' => ['label' => 'Croisière', 'icon' => 'fa-ship', 'tone' => 'bg-blue-100 text-blue-800'],
            'safari' => ['label' => 'Safari', 'icon' => 'fa-paw', 'tone' => 'bg-yellow-100 text-yellow-800'],
            'city_tour' => ['label' => 'City tour', 'icon' => 'fa-city', 'tone' => 'bg-purple-100 text-purple-800'],
            'adventure' => ['label' => 'Aventure', 'icon' => 'fa-mountain', 'tone' => 'bg-lime-100 text-lime-800'],
            'luxury' => ['label' => 'Luxe', 'icon' => 'fa-gem', 'tone' => 'bg-pink-100 text-pink-800'],
        ];

        $type = $typeConfig[$package->package_type] ?? ['label' => $package->package_type, 'icon' => 'fa-tag', 'tone' => 'bg-slate-100 text-slate-800'];
        $mainImage = $package->getFirstMediaUrl('avatar', 'normal');
        $placeholder = 'https://placehold.co/1200x720/4c1d95/ffffff?text=' . urlencode($package->title_fr);
        $gallery = $package->getMedia('gallery');
        $currency = $package->currency ?? 'XOF';
        $currencyLabel = match ($currency) {
            'EUR' => 'EUR',
            'USD' => 'USD',
            default => 'FCFA',
        };
        $priceDecimals = $currency === 'XOF' ? 0 : 2;
        $displayPrice = ($package->discount_price && $package->discount_price < $package->price) ? $package->discount_price : $package->price;

        $renderList = static function ($items) {
            return collect($items ?? [])
                ->map(function ($item) {
                    if (is_array($item)) {
                        return $item['title'] ?? $item['description'] ?? implode(' - ', array_filter($item));
                    }

                    return $item;
                })
                ->filter(fn ($item) => filled($item))
                ->values();
        };

        $includedFr = $renderList($package->included_services_fr);
        $excludedFr = $renderList($package->excluded_services_fr);
        $itineraryFr = $renderList($package->itinerary_fr);
    @endphp

    <div class="mx-auto max-w-7xl space-y-8 py-2">
        <section class="admin-page-header">
            <div class="max-w-4xl">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $package->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $package->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $type['tone'] }}">
                        <i class="fas {{ $type['icon'] }}"></i>
                        {{ $type['label'] }}
                    </span>
                    @if ($package->is_featured)
                        <span class="rounded-full bg-[var(--admin-accent-soft)] px-3 py-1 text-xs font-semibold text-[var(--admin-warning)]">
                            Mis en avant
                        </span>
                    @endif
                </div>
                <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">{{ $package->title_fr }}</h1>
                <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                    {{ $package->description_fr ?: 'Aucune description française renseignée pour ce package.' }}
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.packages.edit', $package) }}" class="admin-btn-primary px-5 py-3 text-sm">
                    <i class="fas fa-pen"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.packages.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                    <i class="fas fa-arrow-left"></i>
                    Retour
                </a>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <article class="admin-kpi p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Réservations</p>
                <p class="mt-4 text-3xl font-bold text-slate-950">{{ number_format($stats['total_bookings'], 0, ',', ' ') }}</p>
                <p class="mt-2 text-sm text-slate-600">{{ number_format($stats['confirmed_bookings'], 0, ',', ' ') }} confirmées</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Revenus</p>
                <p class="mt-4 text-3xl font-bold text-slate-950">{{ number_format($stats['total_revenue'], $priceDecimals, ',', ' ') }}</p>
                <p class="mt-2 text-sm text-slate-600">{{ $currencyLabel }} confirmés</p>
            </article>
            <article class="admin-kpi admin-kpi-accent p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Prix affiché</p>
                <p class="mt-4 text-3xl font-bold text-slate-950">{{ number_format($displayPrice, $priceDecimals, ',', ' ') }}</p>
                <p class="mt-2 text-sm text-slate-600">{{ $currencyLabel }}</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Participants</p>
                <p class="mt-4 text-3xl font-bold text-slate-950">{{ $package->max_participants }}</p>
                <p class="mt-2 text-sm text-slate-600">min {{ $package->min_participants }}</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Avis</p>
                <p class="mt-4 text-3xl font-bold text-slate-950">{{ number_format($stats['average_rating'], 1, ',', ' ') }}/5</p>
                <p class="mt-2 text-sm text-slate-600">{{ number_format($stats['total_reviews'], 0, ',', ' ') }} avis</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]">
            <div class="space-y-6">
                <article class="admin-panel overflow-hidden">
                    <div class="relative aspect-[16/8] overflow-hidden bg-slate-100">
                        <img src="{{ $mainImage ?: $placeholder }}" alt="{{ $package->title_fr }}" class="h-full w-full object-cover" onerror="this.onerror=null;this.src='{{ $placeholder }}';">
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 to-transparent px-6 pb-6 pt-14 text-white">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-white/70">{{ $package->category->name_fr ?? 'Sans catégorie' }}</p>
                            <p class="mt-2 text-2xl font-bold">{{ $package->destination }}</p>
                            <p class="mt-2 text-sm text-white/80">{{ $package->duration_text_fr ?: $package->duration . ' jours' }} · {{ $package->departure_city ?: 'Départ flexible' }}</p>
                        </div>
                    </div>
                    @if ($gallery->count() > 0)
                        <div class="grid gap-3 border-t border-[#eadfce] p-5 sm:grid-cols-3">
                            @foreach ($gallery as $media)
                                <img src="{{ $media->getUrl('small') }}" alt="Galerie" class="h-24 w-full rounded-[1rem] object-cover">
                            @endforeach
                        </div>
                    @endif
                </article>

                <article class="admin-panel p-6 sm:p-7">
                    <h2 class="text-xl font-bold text-slate-950">Descriptions et contenus</h2>
                    <div class="mt-6 grid gap-4 lg:grid-cols-2">
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Titre EN</p>
                            <p class="mt-3 text-sm font-semibold text-slate-900">{{ $package->title_en ?: 'Non renseigné' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Description EN</p>
                            <p class="mt-3 text-sm leading-7 text-slate-700">{{ $package->description_en ?: 'Non renseignée' }}</p>
                        </div>
                    </div>
                </article>

                <article class="admin-panel p-6 sm:p-7">
                    <h2 class="text-xl font-bold text-slate-950">Services et itinéraire</h2>
                    <div class="mt-6 grid gap-4 lg:grid-cols-3">
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Inclus FR</p>
                            <div class="mt-3 space-y-2 text-sm text-slate-700">
                                @forelse ($includedFr as $item)
                                    <p>{{ $item }}</p>
                                @empty
                                    <p class="text-slate-500">Aucun service inclus.</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Exclus FR</p>
                            <div class="mt-3 space-y-2 text-sm text-slate-700">
                                @forelse ($excludedFr as $item)
                                    <p>{{ $item }}</p>
                                @empty
                                    <p class="text-slate-500">Aucun service exclu.</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Itinéraire FR</p>
                            <div class="mt-3 space-y-2 text-sm text-slate-700">
                                @forelse ($itineraryFr as $index => $item)
                                    <p><span class="font-semibold text-slate-900">Étape {{ $index + 1 }}:</span> {{ $item }}</p>
                                @empty
                                    <p class="text-slate-500">Aucun itinéraire renseigné.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <div class="space-y-6">
                <article class="admin-panel p-6 sm:p-7">
                    <h2 class="text-xl font-bold text-slate-950">Fiche commerciale</h2>
                    <div class="mt-5 space-y-4 text-sm text-slate-700">
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Destination</p>
                            <p class="mt-2 font-semibold text-slate-900">{{ $package->destination }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Départ</p>
                            <p class="mt-2 font-semibold text-slate-900">{{ $package->departure_city ?: 'Flexible' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Prix de base</p>
                            <p class="mt-2 font-semibold text-slate-900">{{ number_format($package->price, $priceDecimals, ',', ' ') }} {{ $currencyLabel }}</p>
                            @if ($package->discount_price && $package->discount_price < $package->price)
                                <p class="mt-1 text-xs text-slate-500">Prix promo {{ number_format($package->discount_price, $priceDecimals, ',', ' ') }} {{ $currencyLabel }}</p>
                            @endif
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Dates événement</p>
                            <p class="mt-2 font-semibold text-slate-900">
                                {{ optional($package->event_date_start)->format('d/m/Y') ?: 'Non renseignées' }}
                                @if ($package->event_date_end)
                                    - {{ $package->event_date_end->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Slug</p>
                            <p class="mt-2 font-semibold text-slate-900">{{ $package->slug }}</p>
                        </div>
                    </div>
                </article>

                <article class="admin-panel p-6 sm:p-7">
                    <h2 class="text-xl font-bold text-slate-950">Disponibilités et SEO</h2>
                    <div class="mt-5 space-y-4">
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Dates disponibles</p>
                            <div class="mt-3 space-y-2 text-sm text-slate-700">
                                @forelse ($availableDates as $date)
                                    <p>{{ \Carbon\Carbon::parse($date->available_date)->format('d/m/Y') }} · {{ $date->available_spots ?? 'n/a' }} places</p>
                                @empty
                                    <p class="text-slate-500">Aucune date d’inventaire enregistrée.</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Meta title FR</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $package->meta_title_fr ?: 'Non renseigné' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Meta description FR</p>
                            <p class="mt-2 text-sm leading-7 text-slate-700">{{ $package->meta_description_fr ?: 'Non renseignée' }}</p>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
@endsection
