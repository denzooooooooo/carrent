@extends('admin.layouts.app')

@section('title', 'Détail code promo')

@section('content')
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

    <div class="mx-auto max-w-7xl space-y-8 py-2">
        <section class="admin-page-header">
            <div class="max-w-3xl">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $promoCode->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $promoCode->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $promoCode->is_currently_valid ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $promoCode->is_currently_valid ? 'Valide maintenant' : 'Hors fenêtre' }}
                    </span>
                </div>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">{{ $promoCode->code }}</h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                    Vue synthétique du code promo, de sa portée et de son historique d’utilisation.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.promo-codes.edit', $promoCode->id) }}" class="admin-btn-primary px-5 py-3 text-sm">
                    <i class="fas fa-pen"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.promo-codes.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
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

        @if (session('error'))
            <div class="rounded-[1.5rem] border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            <article class="admin-kpi p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600">Réduction</p>
                <p class="mt-5 text-3xl font-black text-slate-950">{{ $discountLabel }}</p>
                <p class="mt-2 text-sm text-slate-600">{{ $scopeLabel }}</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-emerald-600">Utilisations</p>
                <p class="mt-5 text-3xl font-black text-slate-950">{{ number_format((int) $promoCode->used_count, 0, ',', ' ') }}</p>
                <p class="mt-2 text-sm text-slate-600">{{ $promoCode->usage_limit ? 'sur ' . number_format((int) $promoCode->usage_limit, 0, ',', ' ') : 'illimité' }}</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-sky-600">Début</p>
                <p class="mt-5 text-xl font-black text-slate-950">{{ optional($promoCode->valid_from)->format('d/m/Y H:i') }}</p>
                <p class="mt-2 text-sm text-slate-600">mise en ligne</p>
            </article>
            <article class="admin-kpi admin-kpi-accent p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-700">Fin</p>
                <p class="mt-5 text-xl font-black text-slate-950">{{ optional($promoCode->valid_until)->format('d/m/Y H:i') }}</p>
                <p class="mt-2 text-sm text-slate-600">expiration</p>
            </article>
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr,0.95fr]">
            <article class="admin-panel p-6 sm:p-7">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Descriptions</p>
                <h2 class="mt-3 text-2xl font-black text-slate-950">Messages commerciaux</h2>

                <div class="mt-6 grid gap-4 lg:grid-cols-2">
                    <div class="rounded-[1.5rem] bg-slate-50 px-5 py-5">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Français</p>
                        <p class="mt-3 text-sm leading-7 text-slate-700">{{ $promoCode->description_fr ?: 'Aucune description française renseignée.' }}</p>
                    </div>
                    <div class="rounded-[1.5rem] bg-slate-50 px-5 py-5">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Anglais</p>
                        <p class="mt-3 text-sm leading-7 text-slate-700">{{ $promoCode->description_en ?: 'Aucune description anglaise renseignée.' }}</p>
                    </div>
                </div>
            </article>

            <article class="admin-panel p-6 sm:p-7">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Règles</p>
                <h2 class="mt-3 text-2xl font-black text-slate-950">Conditions d’application</h2>

                <dl class="mt-6 space-y-4 text-sm text-slate-600">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <dt class="font-semibold text-slate-500">Périmètre</dt>
                        <dd class="text-right font-semibold text-slate-900">{{ $scopeLabel }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <dt class="font-semibold text-slate-500">Montant minimum</dt>
                        <dd class="text-right font-semibold text-slate-900">{{ $promoCode->min_purchase_amount ? number_format((float) $promoCode->min_purchase_amount, 0, ',', ' ') . ' FCFA' : 'Aucun' }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <dt class="font-semibold text-slate-500">Plafond</dt>
                        <dd class="text-right font-semibold text-slate-900">{{ $promoCode->max_discount_amount ? number_format((float) $promoCode->max_discount_amount, 0, ',', ' ') . ' FCFA' : 'Aucun' }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <dt class="font-semibold text-slate-500">Créé le</dt>
                        <dd class="text-right font-semibold text-slate-900">{{ optional($promoCode->created_at)->format('d/m/Y H:i') ?: 'N/A' }}</dd>
                    </div>
                </dl>
            </article>
        </section>

        <section class="admin-panel p-6 sm:p-7">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Historique</p>
                    <h2 class="mt-3 text-2xl font-black text-slate-950">Dernières utilisations</h2>
                </div>
                <span class="inline-flex rounded-full bg-purple-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-purple-700">
                    {{ $promoCode->usages->count() }} ligne(s)
                </span>
            </div>

            @if ($promoCode->usages->isNotEmpty())
                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/80">
                            <tr class="text-left">
                                <th class="px-4 py-3 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Client</th>
                                <th class="px-4 py-3 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Réservation</th>
                                <th class="px-4 py-3 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Réduction</th>
                                <th class="px-4 py-3 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Utilisé le</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($promoCode->usages->sortByDesc('used_at')->take(10) as $usage)
                                <tr>
                                    <td class="px-4 py-4 text-sm text-slate-700">{{ trim(($usage->user?->first_name ?? '') . ' ' . ($usage->user?->last_name ?? '')) ?: ($usage->user?->email ?? 'Client') }}</td>
                                    <td class="px-4 py-4 text-sm font-semibold text-slate-900">
                                        @if ($usage->booking)
                                            <a href="{{ route('admin.bookings.show', $usage->booking) }}" class="text-purple-700 transition hover:text-purple-900">
                                                {{ $usage->booking->booking_number }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-700">{{ number_format((float) $usage->discount_amount, 0, ',', ' ') }} FCFA</td>
                                    <td class="px-4 py-4 text-sm text-slate-500">{{ optional($usage->used_at)->format('d/m/Y H:i') ?: 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mt-6 rounded-[1.5rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-sm font-medium text-slate-500">
                    Ce code promo n’a pas encore été utilisé.
                </div>
            @endif
        </section>
    </div>
@endsection
