@extends('admin.layouts.app')

@section('title', 'Codes promo')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8">
        <section class="admin-page-header">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-purple-600">Offres & incentives</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">Codes promo</h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                    Gère les remises, leur fenêtre de validité, leur portée commerciale et le volume d’utilisation.
                </p>
            </div>
            <a href="{{ route('admin.promo-codes.create') }}" class="admin-btn-primary px-5 py-3 text-sm">
                <i class="fas fa-plus"></i>
                Nouveau code promo
            </a>
        </section>

        @if (session('success'))
            <div class="rounded-[1.5rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-[1.5rem] border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-5">
            <article class="admin-kpi p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600">Total</p>
                <p class="mt-5 text-4xl font-black text-slate-950">{{ $stats['total'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-slate-600">codes enregistrés</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-emerald-600">Actifs</p>
                <p class="mt-5 text-4xl font-black text-slate-950">{{ $stats['active'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-slate-600">activés dans le back-office</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-sky-600">Valides maintenant</p>
                <p class="mt-5 text-4xl font-black text-slate-950">{{ $stats['current'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-slate-600">dans leur fenêtre</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-600">Utilisations</p>
                <p class="mt-5 text-4xl font-black text-slate-950">{{ number_format((int) ($stats['usages'] ?? 0), 0, ',', ' ') }}</p>
                <p class="mt-2 text-sm text-slate-600">totales</p>
            </article>
            <article class="admin-kpi admin-kpi-accent p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-700">Réduction cumulée</p>
                <p class="mt-5 text-4xl font-black text-slate-950">{{ number_format((float) ($stats['discount_amount'] ?? 0), 0, ',', ' ') }}</p>
                <p class="mt-2 text-sm text-slate-600">FCFA distribués</p>
            </article>
        </section>

        <section class="admin-panel p-6 sm:p-7">
            <div class="flex flex-col gap-4 border-b border-slate-100 pb-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Filtres</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-950">Suivre les campagnes</h2>
                </div>
                <a href="{{ route('admin.promo-codes.index') }}" class="admin-btn-ghost px-4 py-3 text-sm">
                    <i class="fas fa-rotate-right"></i>
                    Réinitialiser
                </a>
            </div>

            <form method="GET" action="{{ route('admin.promo-codes.index') }}" class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                <label class="xl:col-span-2">
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-500">Recherche</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Code, description..." class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                </label>

                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-500">Périmètre</span>
                    <select name="applicable_to" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                        <option value="">Tous</option>
                        <option value="all" @selected(request('applicable_to') === 'all')>Tous les parcours</option>
                        <option value="flights" @selected(request('applicable_to') === 'flights')>Vols</option>
                        <option value="events" @selected(request('applicable_to') === 'events')>Événements</option>
                        <option value="packages" @selected(request('applicable_to') === 'packages')>Packages</option>
                    </select>
                </label>

                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-500">Statut logique</span>
                    <select name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                        <option value="">Tous</option>
                        <option value="current" @selected(request('status') === 'current')>Valides maintenant</option>
                        <option value="upcoming" @selected(request('status') === 'upcoming')>À venir</option>
                        <option value="expired" @selected(request('status') === 'expired')>Expirés</option>
                        <option value="exhausted" @selected(request('status') === 'exhausted')>Épuisés</option>
                    </select>
                </label>

                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-500">Activation</span>
                    <select name="is_active" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                        <option value="">Tous</option>
                        <option value="1" @selected(request('is_active') === '1')>Actifs</option>
                        <option value="0" @selected(request('is_active') === '0')>Inactifs</option>
                    </select>
                </label>

                <div class="xl:col-span-5 flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="admin-btn-primary px-5 py-3 text-sm">
                        <i class="fas fa-magnifying-glass"></i>
                        Filtrer
                    </button>
                    <span class="inline-flex items-center rounded-full bg-purple-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-purple-700">
                        {{ $promoCodes->total() }} résultat(s)
                    </span>
                </div>
            </form>
        </section>

        <section class="admin-panel overflow-hidden">
            @if ($promoCodes->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/80">
                            <tr class="text-left">
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Code</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Portée</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Réduction</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Validité</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Usage</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-[0.18em] text-slate-500">État</th>
                                <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-[0.18em] text-slate-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($promoCodes as $promoCode)
                                @php
                                    $scopeLabel = match ($promoCode->applicable_to) {
                                        'flights' => 'Vols',
                                        'events' => 'Événements',
                                        'packages' => 'Packages',
                                        default => 'Tous les parcours',
                                    };
                                    $discountLabel = $promoCode->discount_type === 'percentage'
                                        ? number_format((float) $promoCode->discount_value, 0, ',', ' ') . '%'
                                        : number_format((float) $promoCode->discount_value, 0, ',', ' ') . ' FCFA';
                                @endphp
                                <tr class="align-top">
                                    <td class="px-6 py-5">
                                        <p class="font-black uppercase tracking-[0.18em] text-slate-900">{{ $promoCode->code }}</p>
                                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ \Illuminate\Support\Str::limit($promoCode->description_fr ?: $promoCode->description_en ?: 'Sans description.', 80) }}</p>
                                    </td>
                                    <td class="px-6 py-5 text-sm font-semibold text-slate-900">{{ $scopeLabel }}</td>
                                    <td class="px-6 py-5">
                                        <p class="font-semibold text-slate-900">{{ $discountLabel }}</p>
                                        @if ($promoCode->min_purchase_amount)
                                            <p class="mt-1 text-xs text-slate-500">Min {{ number_format((float) $promoCode->min_purchase_amount, 0, ',', ' ') }} FCFA</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        <p>{{ optional($promoCode->valid_from)->format('d/m/Y H:i') }}</p>
                                        <p class="mt-1">{{ optional($promoCode->valid_until)->format('d/m/Y H:i') }}</p>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        <p class="font-semibold text-slate-900">{{ number_format((int) $promoCode->used_count, 0, ',', ' ') }}</p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $promoCode->usage_limit ? 'sur ' . number_format((int) $promoCode->usage_limit, 0, ',', ' ') : 'illimité' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col gap-2">
                                            <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $promoCode->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                                {{ $promoCode->is_active ? 'Actif' : 'Inactif' }}
                                            </span>
                                            <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $promoCode->is_currently_valid ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700' }}">
                                                {{ $promoCode->is_currently_valid ? 'Valide maintenant' : 'Hors fenêtre' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.promo-codes.show', $promoCode->id) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-700 transition hover:bg-slate-200" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.promo-codes.edit', $promoCode->id) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-sky-50 text-sky-700 transition hover:bg-sky-100" title="Modifier">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form action="{{ route('admin.promo-codes.destroy', $promoCode->id) }}" method="POST" onsubmit="return confirm('Supprimer ce code promo ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-red-50 text-red-700 transition hover:bg-red-100" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($promoCodes->hasPages())
                    <div class="border-t border-slate-100 px-6 py-5">
                        {{ $promoCodes->links('pagination::tailwind') }}
                    </div>
                @endif
            @else
                <div class="px-6 py-16 text-center">
                    <i class="fas fa-tags text-5xl text-slate-300"></i>
                    <p class="mt-4 text-lg font-bold text-slate-700">Aucun code promo trouvé</p>
                    <p class="mt-2 text-sm text-slate-500">Crée une nouvelle offre ou ajuste les filtres.</p>
                </div>
            @endif
        </section>
    </div>
@endsection
