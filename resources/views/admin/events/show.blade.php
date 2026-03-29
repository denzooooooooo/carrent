@extends('admin.layouts.app')

@section('title', 'Détail événement')

@section('content')
    @php
        $imageUrl = $event->getFirstMediaUrl('avatar', 'normal');
        $placeholder = 'https://placehold.co/1200x720/4c1d95/ffffff?text=Event';
        $packages = $event->allPackages;
    @endphp

    <div class="mx-auto max-w-8xl space-y-8 py-2">
        <section class="admin-page-header">
            <div class="max-w-4xl">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $event->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $event->is_active ? 'Publié' : 'Brouillon' }}
                    </span>
                    @if ($event->is_featured)
                        <span class="rounded-full bg-[var(--admin-brand-soft)] px-3 py-1 text-xs font-semibold text-[var(--admin-brand)]">
                            Featured
                        </span>
                    @endif
                    <span class="rounded-full border border-[#eadfce] px-3 py-1 text-xs font-semibold text-slate-500">
                        {{ $event->category->name_fr ?? 'Sans catégorie' }}
                    </span>
                </div>
                <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    {{ $event->title_fr }}
                </h1>
                <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                    {{ $event->tagline_fr ?: \Illuminate\Support\Str::limit(strip_tags($event->description_fr ?: $event->description_en), 220) }}
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.events.edit', $event) }}" class="admin-btn-primary px-5 py-3 text-sm">
                    <i class="fas fa-pen"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.events.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                    <i class="fas fa-arrow-left"></i>
                    Retour
                </a>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="admin-kpi p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Date</p>
                <p class="mt-4 text-2xl font-bold text-slate-950">{{ $event->event_date?->translatedFormat('d M Y') ?? 'À définir' }}</p>
                <p class="mt-2 text-sm text-slate-600">{{ $event->event_time ?: 'Heure à définir' }}</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Prix</p>
                <p class="mt-4 text-2xl font-bold text-slate-950">{{ number_format((float) $event->min_price, 0, ',', ' ') }} FCFA</p>
                <p class="mt-2 text-sm text-slate-600">Max {{ number_format((float) ($event->max_price ?: $event->min_price), 0, ',', ' ') }} FCFA</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Inventaire</p>
                <p class="mt-4 text-2xl font-bold text-slate-950">{{ number_format((int) $event->available_seats, 0, ',', ' ') }}</p>
                <p class="mt-2 text-sm text-slate-600">sur {{ number_format((int) $event->total_seats, 0, ',', ' ') }} places</p>
            </article>
            <article class="admin-kpi admin-kpi-accent p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Offre</p>
                <p class="mt-4 text-2xl font-bold text-slate-950">{{ $packages->count() }} package(s)</p>
                <p class="mt-2 text-sm text-slate-600">{{ $event->seatZones->count() }} zone(s) de sièges configurées</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]">
            <div class="space-y-6">
                <article class="admin-panel overflow-hidden">
                    <div class="relative aspect-[16/8] overflow-hidden bg-slate-100">
                        <img src="{{ $imageUrl ?: $placeholder }}" alt="{{ $event->title_fr }}"
                            class="h-full w-full object-cover"
                            onerror="this.onerror=null;this.src='{{ $placeholder }}';">
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent px-6 pb-6 pt-16 text-white">
                            <p class="text-xs font-semibold uppercase tracking-[0.26em] text-white/70">
                                {{ $event->type->name_fr ?? 'Type libre' }}
                            </p>
                            <p class="mt-2 text-2xl font-bold">{{ $event->venue_name }}</p>
                            <p class="mt-2 text-sm text-white/80">{{ $event->city }}, {{ $event->country }}</p>
                        </div>
                    </div>
                </article>

                <article class="admin-panel p-6 sm:p-7">
                    <h2 class="text-xl font-bold text-slate-950">Informations générales</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Slug</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $event->slug }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Famille</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ ucfirst($event->family) }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Organisateur</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $event->organizer ?: 'Non renseigné' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Catalogue source</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $event->source_catalog ?: 'Création manuelle' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-4 py-4 sm:col-span-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Adresse</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $event->venue_address }}</p>
                        </div>
                    </div>
                </article>

                <article class="admin-panel p-6 sm:p-7">
                    <h2 class="text-xl font-bold text-slate-950">Descriptions et contenus</h2>
                    <div class="mt-6 space-y-6">
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Titre anglais</p>
                            <p class="mt-3 text-sm font-semibold text-slate-900">{{ $event->title_en }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Description FR</p>
                            <div class="prose mt-3 max-w-none text-sm leading-7 text-slate-700">
                                {!! nl2br(e($event->description_fr ?: 'Aucune description française renseignée.')) !!}
                            </div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Description EN</p>
                            <div class="prose mt-3 max-w-none text-sm leading-7 text-slate-700">
                                {!! nl2br(e($event->description_en ?: 'Aucune description anglaise renseignée.')) !!}
                            </div>
                        </div>
                        @if ($event->program_fr || $event->program_en || $event->conditions_fr || $event->conditions_en)
                            <div class="grid gap-4 lg:grid-cols-2">
                                <div class="rounded-2xl bg-slate-50 px-5 py-5">
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Programme</p>
                                    <div class="prose mt-3 max-w-none text-sm leading-7 text-slate-700">
                                        {!! nl2br(e($event->program_fr ?: $event->program_en ?: 'Non renseigné')) !!}
                                    </div>
                                </div>
                                <div class="rounded-2xl bg-slate-50 px-5 py-5">
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Conditions</p>
                                    <div class="prose mt-3 max-w-none text-sm leading-7 text-slate-700">
                                        {!! nl2br(e($event->conditions_fr ?: $event->conditions_en ?: 'Non renseigné')) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </article>
            </div>

            <div class="space-y-6">
                <article class="admin-panel p-6 sm:p-7">
                    <h2 class="text-xl font-bold text-slate-950">Packages détectés</h2>
                    <div class="mt-5 space-y-4">
                        @forelse ($packages as $package)
                            <div class="rounded-2xl border border-[#eadfce] bg-slate-50 px-5 py-5">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $package->package_name_fr }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-400">{{ $package->package_code ?: 'Sans code' }}</p>
                                    </div>
                                    <span class="rounded-full bg-white px-3 py-1 text-sm font-semibold text-[var(--admin-brand)]">
                                        {{ number_format((float) $package->price, 0, ',', ' ') }} {{ $package->currency }}
                                    </span>
                                </div>
                                @if ($package->description_fr)
                                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $package->description_fr }}</p>
                                @endif
                                <div class="mt-4 flex flex-wrap gap-2 text-xs font-medium text-slate-500">
                                    <span class="rounded-full border border-[#eadfce] px-3 py-1">Quantité: {{ $package->available_quantity }}</span>
                                    <span class="rounded-full border border-[#eadfce] px-3 py-1">Min: {{ $package->minimum_quantity }}</span>
                                    <span class="rounded-full border border-[#eadfce] px-3 py-1">Max/commande: {{ $package->max_per_order }}</span>
                                    <span class="rounded-full border border-[#eadfce] px-3 py-1">{{ $package->is_active ? 'Actif' : 'Inactif' }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-[#ddcfbb] px-5 py-6 text-sm text-slate-500">
                                Aucun package enregistré pour cet événement.
                            </div>
                        @endforelse
                    </div>
                </article>

                <article class="admin-panel p-6 sm:p-7">
                    <h2 class="text-xl font-bold text-slate-950">Zones de sièges</h2>
                    <div class="mt-5 space-y-4">
                        @forelse ($event->seatZones as $zone)
                            <div class="rounded-2xl border border-[#eadfce] bg-slate-50 px-5 py-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $zone->zone_name_fr }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-400">{{ $zone->zone_code ?: 'Sans code' }}</p>
                                    </div>
                                    <span class="rounded-full bg-white px-3 py-1 text-sm font-semibold text-[var(--admin-brand)]">
                                        {{ number_format((float) $zone->price, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                                <div class="mt-4 flex flex-wrap gap-2 text-xs font-medium text-slate-500">
                                    <span class="rounded-full border border-[#eadfce] px-3 py-1">Total: {{ $zone->total_seats }}</span>
                                    <span class="rounded-full border border-[#eadfce] px-3 py-1">Dispo: {{ $zone->available_seats }}</span>
                                    <span class="rounded-full border border-[#eadfce] px-3 py-1">{{ $zone->zone_type ?: 'standard' }}</span>
                                </div>
                                @if ($zone->description_fr)
                                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $zone->description_fr }}</p>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-[#ddcfbb] px-5 py-6 text-sm text-slate-500">
                                Aucune zone de sièges configurée.
                            </div>
                        @endforelse
                    </div>
                </article>

                <article class="admin-panel p-6 sm:p-7">
                    <h2 class="text-xl font-bold text-slate-950">SEO et publication</h2>
                    <div class="mt-5 space-y-4">
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Méta titre FR</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $event->meta_title_fr ?: 'Non renseigné' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Méta description FR</p>
                            <p class="mt-2 text-sm leading-6 text-slate-700">{{ $event->meta_description_fr ?: 'Non renseigné' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Méta titre EN</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $event->meta_title_en ?: 'Non renseigné' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Méta description EN</p>
                            <p class="mt-2 text-sm leading-6 text-slate-700">{{ $event->meta_description_en ?: 'Non renseigné' }}</p>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
@endsection
