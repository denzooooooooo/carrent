@extends('admin.layouts.app')

@section('title', 'Détail de la location')

@section('content')
    @php
        $placeholder = 'https://placehold.co/1200x800/4c1d95/ffffff?text=' . urlencode($location->name);
        $features = collect($location->features ?? [])->filter(fn ($feature) => filled($feature))->values();
        $categoryLabels = [
            'terrestre' => 'Terrestre',
            'aérien' => 'Aérien',
            'nautique' => 'Nautique',
        ];
    @endphp

    <div class="mx-auto max-w-7xl space-y-8 py-2">
        <section class="admin-page-header">
            <div class="max-w-3xl">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-black uppercase tracking-[0.18em] text-purple-700">
                        {{ $categoryLabels[$location->category] ?? ucfirst($location->category) }}
                    </span>
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-black uppercase tracking-[0.18em] {{ $location->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $location->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
                <h1 class="mt-4 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">{{ $location->name }}</h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                    Fiche complète de l’offre mobilité, avec le contenu éditorial, les caractéristiques et le statut de publication.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.locations.edit', $location) }}" class="admin-btn-primary px-5 py-3 text-sm">
                    <i class="fas fa-pen"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.locations.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                    <i class="fas fa-arrow-left"></i>
                    Retour à la liste
                </a>
            </div>
        </section>

        @if (session('success'))
            <div class="rounded-[1.5rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1.35fr,0.95fr]">
            <article class="admin-panel overflow-hidden">
                <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                    <img
                        src="{{ $location->image_url ?: $placeholder }}"
                        alt="{{ $location->name }}"
                        class="h-full w-full object-cover"
                        onerror="this.onerror=null;this.src='{{ $placeholder }}';"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-[#120a1d]/90 via-[#120a1d]/10 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 flex flex-wrap items-end justify-between gap-4 p-6 text-white">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-white/70">Tarif public</p>
                            <p class="mt-2 text-3xl font-black">{{ number_format((float) $location->price_per_day, 0, ',', ' ') }} FCFA</p>
                            <p class="mt-2 text-sm text-white/75">par jour</p>
                        </div>
                        <div class="rounded-[1.4rem] border border-white/15 bg-white/10 px-4 py-3 backdrop-blur">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-white/70">Capacité</p>
                            <p class="mt-2 text-lg font-bold">{{ $location->capacity }} personne(s)</p>
                        </div>
                    </div>
                </div>
            </article>

            <aside class="space-y-6">
                <article class="admin-panel p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Résumé</p>
                            <h2 class="mt-3 text-2xl font-black text-slate-950">Informations clés</h2>
                        </div>
                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-slate-700">
                            {{ ucfirst($location->type) }}
                        </span>
                    </div>

                    <dl class="mt-6 space-y-4 text-sm text-slate-600">
                        <div class="flex items-start justify-between gap-6 border-b border-slate-100 pb-4">
                            <dt class="font-semibold text-slate-500">Nom FR</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ $location->name_fr }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-6 border-b border-slate-100 pb-4">
                            <dt class="font-semibold text-slate-500">Nom EN</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ $location->name_en ?: 'Non renseigné' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-6 border-b border-slate-100 pb-4">
                            <dt class="font-semibold text-slate-500">Type</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ ucfirst($location->type) }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-6 border-b border-slate-100 pb-4">
                            <dt class="font-semibold text-slate-500">Catégorie</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ $categoryLabels[$location->category] ?? ucfirst($location->category) }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-6 border-b border-slate-100 pb-4">
                            <dt class="font-semibold text-slate-500">Créée le</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ optional($location->created_at)->format('d/m/Y H:i') ?: 'Non disponible' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-6">
                            <dt class="font-semibold text-slate-500">Dernière mise à jour</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ optional($location->updated_at)->format('d/m/Y H:i') ?: 'Non disponible' }}</dd>
                        </div>
                    </dl>
                </article>

                <article class="admin-panel p-6">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Actions</p>
                    <div class="mt-5 grid gap-3">
                        <a href="{{ route('admin.locations.edit', $location) }}" class="admin-btn-primary justify-center px-5 py-3 text-sm">
                            <i class="fas fa-pen"></i>
                            Modifier la fiche
                        </a>
                        <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cette location ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn-ghost w-full justify-center border-red-200 text-red-700 hover:border-red-300 hover:bg-red-50">
                                <i class="fas fa-trash"></i>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </article>
            </aside>
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <article class="admin-panel p-6 sm:p-7">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Contenu FR</p>
                <h2 class="mt-3 text-2xl font-black text-slate-950">Description française</h2>
                <div class="prose prose-slate mt-6 max-w-none text-sm leading-7 text-slate-600">
                    @if (filled($location->description_fr))
                        {!! nl2br(e($location->description_fr)) !!}
                    @else
                        <p class="font-medium text-slate-400">Aucune description française renseignée.</p>
                    @endif
                </div>
            </article>

            <article class="admin-panel p-6 sm:p-7">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Contenu EN</p>
                <h2 class="mt-3 text-2xl font-black text-slate-950">Description anglaise</h2>
                <div class="prose prose-slate mt-6 max-w-none text-sm leading-7 text-slate-600">
                    @if (filled($location->description_en))
                        {!! nl2br(e($location->description_en)) !!}
                    @else
                        <p class="font-medium text-slate-400">Aucune description anglaise renseignée.</p>
                    @endif
                </div>
            </article>
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1.15fr,0.85fr]">
            <article class="admin-panel p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Caractéristiques</p>
                        <h2 class="mt-3 text-2xl font-black text-slate-950">Atouts mis en avant</h2>
                    </div>
                    <span class="inline-flex rounded-full bg-purple-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-purple-700">
                        {{ $features->count() }} élément(s)
                    </span>
                </div>

                @if ($features->isNotEmpty())
                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        @foreach ($features as $feature)
                            <div class="flex items-start gap-3 rounded-[1.5rem] border border-slate-200 bg-slate-50 px-4 py-4">
                                <span class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                    <i class="fas fa-check text-xs"></i>
                                </span>
                                <p class="text-sm font-semibold leading-6 text-slate-700">{{ $feature }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mt-6 rounded-[1.5rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-sm font-medium text-slate-500">
                        Aucune caractéristique n’est encore renseignée.
                    </div>
                @endif
            </article>

            <article class="admin-panel p-6 sm:p-7">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Qualité fiche</p>
                <h2 class="mt-3 text-2xl font-black text-slate-950">Contrôle rapide</h2>
                <ul class="mt-6 space-y-3 text-sm leading-6 text-slate-600">
                    <li class="flex items-start gap-3 rounded-[1.35rem] bg-slate-50 px-4 py-4">
                        <span class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full {{ filled($location->description_fr) ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            <i class="fas {{ filled($location->description_fr) ? 'fa-check' : 'fa-exclamation' }} text-xs"></i>
                        </span>
                        <span>La description française {{ filled($location->description_fr) ? 'est renseignée.' : 'manque encore.' }}</span>
                    </li>
                    <li class="flex items-start gap-3 rounded-[1.35rem] bg-slate-50 px-4 py-4">
                        <span class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full {{ filled($location->description_en) ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            <i class="fas {{ filled($location->description_en) ? 'fa-check' : 'fa-exclamation' }} text-xs"></i>
                        </span>
                        <span>La description anglaise {{ filled($location->description_en) ? 'est renseignée.' : 'reste optionnelle mais vide.' }}</span>
                    </li>
                    <li class="flex items-start gap-3 rounded-[1.35rem] bg-slate-50 px-4 py-4">
                        <span class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full {{ filled($location->image) ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            <i class="fas {{ filled($location->image) ? 'fa-image' : 'fa-exclamation' }} text-xs"></i>
                        </span>
                        <span>{{ filled($location->image) ? 'Un visuel principal est bien associé à la fiche.' : 'Aucun visuel principal n’est associé à la fiche.' }}</span>
                    </li>
                    <li class="flex items-start gap-3 rounded-[1.35rem] bg-slate-50 px-4 py-4">
                        <span class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full {{ $location->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                            <i class="fas {{ $location->is_active ? 'fa-circle-check' : 'fa-circle-pause' }} text-xs"></i>
                        </span>
                        <span>La fiche est actuellement {{ $location->is_active ? 'active et visible dans le back-office.' : 'inactive et hors diffusion.' }}</span>
                    </li>
                </ul>
            </article>
        </section>
    </div>
@endsection
