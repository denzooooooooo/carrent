@extends('admin.layouts.app')

@section('title', 'Gestion des packages')

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
@endphp

<div class="mx-auto max-w-7xl space-y-8">
    <section class="admin-page-header">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-purple-600">Catalogue</p>
            <h1 class="mt-2 text-3xl font-black text-gray-900">Packages</h1>
            <p class="mt-3 max-w-2xl text-gray-600">Collections voyage, événements signature et offres premium dans une vue plus propre et plus simple à piloter.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.packages.create') }}" class="admin-btn-primary px-5 py-3 text-sm">
                <i class="fas fa-plus"></i>
                Nouveau package
            </a>
        </div>
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
                    <p class="mt-2 text-sm text-gray-600">Offres dans le catalogue</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-purple-100 text-purple-700">
                    <i class="fas fa-suitcase-rolling text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-emerald-600">Actifs</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['active'] ?? 0 }}</p>
                    <p class="mt-2 text-sm text-gray-600">Visibles côté client</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                    <i class="fas fa-circle-check text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi admin-kpi-accent p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-700">Mis en avant</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['featured'] ?? 0 }}</p>
                    <p class="mt-2 text-sm text-gray-600">Packages vedettes</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                    <i class="fas fa-star text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600">Prix moyen</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ number_format((float) ($stats['average_price'] ?? 0), 0, ',', ' ') }}</p>
                    <p class="mt-2 text-sm text-gray-600">Base tarifaire</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-purple-100 text-purple-700">
                    <i class="fas fa-tags text-2xl"></i>
                </div>
            </div>
        </article>
    </section>

    <section class="admin-panel p-6 sm:p-7">
        <div class="flex flex-col gap-4 border-b border-gray-100 pb-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Filtres</p>
                <h2 class="mt-2 text-2xl font-black text-gray-900">Affiner le catalogue</h2>
            </div>
            <a href="{{ route('admin.packages.index') }}" class="admin-btn-ghost px-4 py-3 text-sm">
                <i class="fas fa-rotate-right"></i>
                Réinitialiser
            </a>
        </div>

        <form method="GET" action="{{ route('admin.packages.index') }}" class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6">
            <label class="xl:col-span-2">
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Recherche</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre, destination..." class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
            </label>

            <label>
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Type</span>
                <select name="package_type" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                    <option value="">Tous</option>
                    @foreach($packageTypes as $key => $label)
                        <option value="{{ $key }}" @selected(request('package_type') == $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>

            <label>
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Catégorie</span>
                <select name="category_id" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                    <option value="">Toutes</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name_fr }}</option>
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

            <div class="flex items-end">
                <button type="submit" class="admin-btn-primary w-full px-5 py-3 text-sm">
                    <i class="fas fa-magnifying-glass"></i>
                    Filtrer
                </button>
            </div>
        </form>
    </section>

    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($packages as $package)
            @php
                $type = $typeConfig[$package->package_type] ?? ['label' => $package->package_type, 'icon' => 'fa-tag', 'tone' => 'bg-slate-100 text-slate-800'];
                $imageUrl = $package->getFirstMediaUrl('avatar', 'normal');
                $placeholder = 'https://placehold.co/900x620/4c1d95/ffffff?text=' . urlencode($package->title_fr);
                $price = $package->discount_price && $package->discount_price < $package->price ? $package->discount_price : $package->price;
                $currency = $package->currency ?? 'XOF';
                $currencySymbol = match($currency) { 'EUR' => '€', 'USD' => '$', default => 'FCFA' };
                $decimals = $currency === 'XOF' ? 0 : 2;
            @endphp

            <article class="admin-panel overflow-hidden transition hover:-translate-y-1 hover:shadow-[0_24px_60px_rgba(38,24,59,0.12)]">
                <div class="relative aspect-[16/11] overflow-hidden">
                    <img src="{{ $imageUrl ?: $placeholder }}" alt="{{ $package->title_fr }}" class="h-full w-full object-cover transition duration-700 hover:scale-105" onerror="this.src='{{ $placeholder }}'">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#120a1d]/92 via-[#120a1d]/12 to-transparent"></div>
                    <div class="absolute left-4 top-4 flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $type['tone'] }}">
                            <i class="fas {{ $type['icon'] }} text-[11px]"></i>
                            {{ $type['label'] }}
                        </span>
                        @if($package->is_featured)
                            <span class="rounded-full bg-amber-300 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-[#2a163d]">Vedette</span>
                        @endif
                    </div>
                    <div class="absolute right-4 top-4 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $package->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        {{ $package->is_active ? 'Actif' : 'Inactif' }}
                    </div>
                </div>

                <div class="p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-black leading-tight text-gray-900">{{ $package->title_fr }}</h2>
                            <p class="mt-2 text-sm text-gray-600">{{ $package->category->name_fr ?? 'Sans catégorie' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-gray-500">Tarif</p>
                            <p class="mt-2 text-lg font-black text-gray-900">{{ number_format($price, $decimals, ',', ' ') }} {{ $currencySymbol }}</p>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-gray-50 px-4 py-3">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-gray-500">Destination</p>
                            <p class="mt-2 text-sm font-bold text-gray-900">{{ $package->destination }}</p>
                        </div>
                        <div class="rounded-2xl bg-gray-50 px-4 py-3">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-gray-500">Durée</p>
                            <p class="mt-2 text-sm font-bold text-gray-900">{{ $package->duration_text_fr ?: $package->duration . ' jours' }}</p>
                        </div>
                    </div>

                    <div class="mt-5 flex items-center justify-between gap-3 border-t border-gray-100 pt-4">
                        <a href="{{ route('admin.packages.show', $package) }}" class="text-sm font-bold text-purple-700 transition hover:text-purple-900">
                            Voir le détail
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.packages.edit', $package) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-sky-50 text-sky-700 transition hover:bg-sky-100" title="Modifier">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Supprimer ce package ?');">
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
                <i class="fas fa-suitcase-rolling text-5xl text-gray-300"></i>
                <p class="mt-4 text-lg font-bold text-gray-700">Aucun package trouvé</p>
                <p class="mt-2 text-sm text-gray-500">Essayez une autre combinaison de filtres ou créez une nouvelle offre.</p>
            </div>
        @endforelse
    </section>

    @if($packages->hasPages())
        <div>
            {{ $packages->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
