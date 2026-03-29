@extends('admin.layouts.app')

@section('title', 'Gestion des locations')

@section('content')
<div class="mx-auto max-w-7xl space-y-8">
    <section class="admin-page-header">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-purple-600">Mobilité</p>
            <h1 class="mt-2 text-3xl font-black text-gray-900">Locations</h1>
            <p class="mt-3 max-w-2xl text-gray-600">Flotte premium, transferts et solutions de mobilité dans une interface catalogue plus claire et plus exploitable.</p>
        </div>
        <a href="{{ route('admin.locations.create') }}" class="admin-btn-primary px-5 py-3 text-sm">
            <i class="fas fa-plus"></i>
            Ajouter une location
        </a>
    </section>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
            {!! session('success') !!}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            {!! session('error') !!}
        </div>
    @endif

    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <article class="admin-kpi p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600">Total</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                    <p class="mt-2 text-sm text-gray-600">Entrées catalogue</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-purple-100 text-purple-700">
                    <i class="fas fa-car-side text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-emerald-600">Actives</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['active'] ?? 0 }}</p>
                    <p class="mt-2 text-sm text-gray-600">{{ $stats['inactive'] ?? 0 }} inactives</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                    <i class="fas fa-circle-check text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi admin-kpi-accent p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-700">Tarif moyen</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ number_format((float) ($stats['average_daily_rate'] ?? 0), 0, ',', ' ') }}</p>
                    <p class="mt-2 text-sm text-gray-600">FCFA / jour</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                    <i class="fas fa-tag text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600">Catégories</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ collect($categories ?? [])->count() }}</p>
                    <p class="mt-2 text-sm text-gray-600">Familles de mobilité</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-purple-100 text-purple-700">
                    <i class="fas fa-layer-group text-2xl"></i>
                </div>
            </div>
        </article>
    </section>

    <section class="admin-panel p-6 sm:p-7">
        <div class="flex flex-col gap-4 border-b border-gray-100 pb-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Filtres</p>
                <h2 class="mt-2 text-2xl font-black text-gray-900">Trier la flotte</h2>
            </div>
            <a href="{{ route('admin.locations.index') }}" class="admin-btn-ghost px-4 py-3 text-sm">
                <i class="fas fa-rotate-right"></i>
                Réinitialiser
            </a>
        </div>

        <form method="GET" action="{{ route('admin.locations.index') }}" class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
            <label class="xl:col-span-2">
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Recherche</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, type, description..." class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
            </label>

            <label>
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Catégorie</span>
                <select name="category" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                    <option value="">Toutes</option>
                    @foreach(($categories ?? collect()) as $category)
                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ ucfirst($category) }}</option>
                    @endforeach
                </select>
            </label>

            <label>
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Type</span>
                <select name="type" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                    <option value="">Tous</option>
                    @foreach(($types ?? collect()) as $type)
                        <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </label>

            <label>
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Statut</span>
                <select name="is_active" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                    <option value="">Tous</option>
                    <option value="1" @selected(request('is_active') === '1')>Actif</option>
                    <option value="0" @selected(request('is_active') === '0')>Inactif</option>
                </select>
            </label>

            <div class="xl:col-span-5 flex flex-wrap gap-3 pt-2">
                <button type="submit" class="admin-btn-primary px-5 py-3 text-sm">
                    <i class="fas fa-magnifying-glass"></i>
                    Filtrer
                </button>
                <span class="inline-flex items-center rounded-full bg-purple-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-purple-700">
                    {{ $locations->total() }} résultat(s)
                </span>
            </div>
        </form>
    </section>

    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($locations as $location)
            @php
                $placeholder = 'https://placehold.co/900x620/4c1d95/ffffff?text=' . urlencode($location->name);
            @endphp
            <article class="admin-panel overflow-hidden transition hover:-translate-y-1 hover:shadow-[0_24px_60px_rgba(38,24,59,0.12)]">
                <div class="relative aspect-[16/11] overflow-hidden">
                    <img src="{{ $location->image_url ?: $placeholder }}" alt="{{ $location->name }}" class="h-full w-full object-cover transition duration-700 hover:scale-105" onerror="this.src='{{ $placeholder }}'">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#120a1d]/92 via-[#120a1d]/12 to-transparent"></div>
                    <div class="absolute left-4 top-4 flex flex-wrap gap-2">
                        <span class="rounded-full bg-white/92 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-[#2a163d]">
                            {{ ucfirst($location->category) }}
                        </span>
                        <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $location->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            {{ $location->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>

                <div class="p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-black leading-tight text-gray-900">{{ $location->name }}</h2>
                            <p class="mt-2 text-sm text-gray-600">{{ ucfirst($location->type) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-gray-500">Tarif</p>
                            <p class="mt-2 text-lg font-black text-gray-900">{{ number_format($location->price_per_day, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-gray-50 px-4 py-3">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-gray-500">Capacité</p>
                            <p class="mt-2 text-sm font-bold text-gray-900">{{ $location->capacity }} pers.</p>
                        </div>
                        <div class="rounded-2xl bg-gray-50 px-4 py-3">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-gray-500">Type</p>
                            <p class="mt-2 text-sm font-bold text-gray-900">{{ ucfirst($location->type) }}</p>
                        </div>
                    </div>

                    <p class="mt-4 text-sm leading-7 text-gray-600">{{ \Illuminate\Support\Str::limit($location->description, 120) }}</p>

                    <div class="mt-5 flex items-center justify-between gap-3 border-t border-gray-100 pt-4">
                        <a href="{{ route('admin.locations.show', $location) }}" class="text-sm font-bold text-purple-700 transition hover:text-purple-900">
                            Voir le détail
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.locations.edit', $location) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-sky-50 text-sky-700 transition hover:bg-sky-100" title="Modifier">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" onsubmit="return confirm('Supprimer cette location ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-red-50 text-red-700 transition hover:bg-red-100" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="admin-panel col-span-full px-6 py-14 text-center">
                <i class="fas fa-car-side text-5xl text-gray-300"></i>
                <p class="mt-4 text-lg font-bold text-gray-700">Aucune location trouvée</p>
                <p class="mt-2 text-sm text-gray-500">Essayez une autre combinaison de filtres ou créez une nouvelle entrée.</p>
            </div>
        @endforelse
    </section>

    @if($locations->hasPages())
        <div>
            {{ $locations->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
