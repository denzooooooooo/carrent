@extends('admin.layouts.app')

@section('title', 'Gestion des événements')

@section('content')
    @php
        $status = request('status');
        $featured = request('featured');
        $search = request('search');
        $selectedCategory = request('category_id');
    @endphp

    <div class="mx-auto max-w-8xl space-y-8 py-2">
        <section class="admin-page-header">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--admin-brand)]">Catalogue premium</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Pilotage des événements
                </h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                    Gère le catalogue, l’état de publication, les prix, les packages et la création rapide depuis PDF depuis une seule interface.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.events.import.form') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                    <i class="fas fa-file-import"></i>
                    Import PDF / tableur
                </a>
                <a href="{{ route('admin.events.create') }}" class="admin-btn-primary px-5 py-3 text-sm">
                    <i class="fas fa-plus-circle"></i>
                    Créer un événement
                </a>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="admin-kpi p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Total événements</p>
                <p class="mt-4 text-3xl font-bold text-slate-950">{{ number_format($stats['total'], 0, ',', ' ') }}</p>
                <p class="mt-3 text-sm text-slate-600">Tous statuts confondus.</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Actifs</p>
                <p class="mt-4 text-3xl font-bold text-[var(--admin-success)]">{{ number_format($stats['active'], 0, ',', ' ') }}</p>
                <p class="mt-3 text-sm text-slate-600">Événements visibles côté client.</p>
            </article>
            <article class="admin-kpi admin-kpi-accent p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">En vedette</p>
                <p class="mt-4 text-3xl font-bold text-[var(--admin-accent)]">{{ number_format($stats['featured'], 0, ',', ' ') }}</p>
                <p class="mt-3 text-sm text-slate-600">Mises en avant marketing.</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Brouillons</p>
                <p class="mt-4 text-3xl font-bold text-[var(--admin-brand)]">{{ number_format($stats['drafts'], 0, ',', ' ') }}</p>
                <p class="mt-3 text-sm text-slate-600">Catalogues à compléter avant publication.</p>
            </article>
        </section>

        <section class="admin-panel p-5 sm:p-6">
            <form method="GET" action="{{ route('admin.events.index') }}" class="grid gap-4 lg:grid-cols-[minmax(0,2fr)_repeat(4,minmax(0,1fr))] lg:items-end">
                <div>
                    <label for="search" class="mb-2 block text-sm font-semibold text-slate-700">Recherche</label>
                    <input type="text" id="search" name="search" value="{{ $search }}"
                        placeholder="Titre, slug, ville, lieu..."
                        class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                </div>

                <div>
                    <label for="category_id" class="mb-2 block text-sm font-semibold text-slate-700">Catégorie</label>
                    <select id="category_id" name="category_id" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                        <option value="">Toutes</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $selectedCategory === (string) $category->id)>
                                {{ $category->name_fr }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">Statut</label>
                    <select id="status" name="status" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                        <option value="">Tous</option>
                        <option value="active" @selected($status === 'active')>Actifs</option>
                        <option value="draft" @selected($status === 'draft')>Brouillons</option>
                    </select>
                </div>

                <div>
                    <label for="featured" class="mb-2 block text-sm font-semibold text-slate-700">Mise en avant</label>
                    <select id="featured" name="featured" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                        <option value="">Toutes</option>
                        <option value="1" @selected($featured === '1')>Featured</option>
                        <option value="0" @selected($featured === '0')>Standard</option>
                    </select>
                </div>

                <div>
                    <label for="sort" class="mb-2 block text-sm font-semibold text-slate-700">Tri</label>
                    <select id="sort" name="sort" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                        <option value="newest" @selected($sort === 'newest')>Plus récents</option>
                        <option value="soonest" @selected($sort === 'soonest')>Plus proches</option>
                        <option value="title" @selected($sort === 'title')>Titre A-Z</option>
                    </select>
                </div>

                <div class="flex gap-3 lg:justify-end">
                    <button type="submit" class="admin-btn-primary px-5 py-3 text-sm">
                        <i class="fas fa-sliders"></i>
                        Filtrer
                    </button>
                    <a href="{{ route('admin.events.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </section>

        <section class="grid gap-5 xl:grid-cols-2 2xl:grid-cols-3">
            @forelse ($events as $event)
                <article class="admin-panel card-hover overflow-hidden">
                    <div class="grid h-full md:grid-cols-[220px_minmax(0,1fr)]">
                        <a href="{{ route('admin.events.show', $event) }}" class="relative block min-h-[220px] overflow-hidden bg-slate-100">
                            @php
                                $imageUrl = $event->getFirstMediaUrl('avatar', 'normal');
                                $placeholder = 'https://placehold.co/900x700/4c1d95/ffffff?text=Event';
                            @endphp
                            <img src="{{ $imageUrl ?: $placeholder }}" alt="{{ $event->title_fr }}"
                                class="h-full w-full object-cover transition duration-500 hover:scale-105"
                                onerror="this.onerror=null;this.src='{{ $placeholder }}';">
                            <div class="absolute inset-x-0 top-0 flex items-center justify-between gap-3 p-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $event->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $event->is_active ? 'Publié' : 'Brouillon' }}
                                </span>
                                @if ($event->is_featured)
                                    <span class="rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-[var(--admin-brand)]">
                                        Featured
                                    </span>
                                @endif
                            </div>
                        </a>

                        <div class="flex flex-col p-5 sm:p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                                        {{ $event->category->name_fr ?? 'Sans catégorie' }}
                                    </p>
                                    <h2 class="mt-2 text-xl font-bold tracking-tight text-slate-950">
                                        {{ $event->title_fr }}
                                    </h2>
                                    <p class="mt-3 text-sm leading-6 text-slate-600">
                                        {{ $event->venue_name }}, {{ $event->city }} · {{ $event->country }}
                                    </p>
                                </div>
                                <span class="rounded-full border border-[#eadfce] bg-white px-3 py-1 text-xs font-semibold text-slate-500">
                                    {{ $event->type->name_fr ?? 'Type libre' }}
                                </span>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-2xl bg-slate-50 px-4 py-3">
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Date</p>
                                    <p class="mt-2 text-sm font-semibold text-slate-900">
                                        {{ $event->event_date?->translatedFormat('d M Y') ?? 'À définir' }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $event->event_time }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 px-4 py-3">
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Tarifs</p>
                                    <p class="mt-2 text-sm font-semibold text-slate-900">
                                        {{ number_format((float) $event->min_price, 0, ',', ' ') }} FCFA
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Max {{ number_format((float) ($event->max_price ?: $event->min_price), 0, ',', ' ') }} FCFA
                                    </p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 px-4 py-3">
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Inventaire</p>
                                    <p class="mt-2 text-sm font-semibold text-slate-900">
                                        {{ number_format((int) $event->available_seats, 0, ',', ' ') }} places
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $event->packages_count }} package(s) · {{ $event->seat_zones_count }} zone(s)
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 flex flex-wrap gap-3">
                                <a href="{{ route('admin.events.show', $event) }}" class="admin-btn-ghost px-4 py-2.5 text-sm">
                                    <i class="fas fa-eye"></i>
                                    Voir
                                </a>
                                <a href="{{ route('admin.events.edit', $event) }}" class="admin-btn-primary px-4 py-2.5 text-sm">
                                    <i class="fas fa-pen"></i>
                                    Modifier
                                </a>
                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                    onsubmit="return confirm('Supprimer cet événement ? Cette action est irréversible.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                                        <i class="fas fa-trash"></i>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="admin-panel col-span-full px-6 py-16 text-center">
                    <span class="inline-flex h-16 w-16 items-center justify-center rounded-3xl bg-[var(--admin-brand-soft)] text-[var(--admin-brand)]">
                        <i class="fas fa-calendar-days text-2xl"></i>
                    </span>
                    <h2 class="mt-6 text-2xl font-bold text-slate-950">Aucun événement trouvé</h2>
                    <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-600">
                        Ajuste les filtres ou crée un nouvel événement. Tu peux aussi importer un catalogue PDF pour générer automatiquement une première fiche brouillon.
                    </p>
                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        <a href="{{ route('admin.events.create') }}" class="admin-btn-primary px-5 py-3 text-sm">
                            Créer un événement
                        </a>
                        <a href="{{ route('admin.events.import.form') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                            Importer un PDF
                        </a>
                    </div>
                </div>
            @endforelse
        </section>

        @if ($events->hasPages())
            <div class="pt-2">
                {{ $events->links() }}
            </div>
        @endif
    </div>
@endsection
