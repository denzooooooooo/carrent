@extends('admin.layouts.app')

@section('title', 'Gestion des réservations')

@section('content')
@php
    $typeMap = [
        'flight' => ['label' => 'Vol', 'icon' => 'fa-plane-departure', 'tone' => 'bg-sky-100 text-sky-800'],
        'event' => ['label' => 'Événement', 'icon' => 'fa-ticket', 'tone' => 'bg-purple-100 text-purple-800'],
        'package' => ['label' => 'Package', 'icon' => 'fa-suitcase-rolling', 'tone' => 'bg-emerald-100 text-emerald-800'],
        'location' => ['label' => 'Location', 'icon' => 'fa-car-side', 'tone' => 'bg-amber-100 text-amber-800'],
    ];

    $statusMap = [
        'pending' => 'bg-amber-100 text-amber-800',
        'confirmed' => 'bg-emerald-100 text-emerald-800',
        'cancelled' => 'bg-red-100 text-red-800',
        'completed' => 'bg-slate-100 text-slate-800',
        'refunded' => 'bg-violet-100 text-violet-800',
    ];

    $paymentMap = [
        'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
        'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        'failed' => 'bg-red-100 text-red-800 border-red-200',
        'refunded' => 'bg-violet-100 text-violet-800 border-violet-200',
        'partially_paid' => 'bg-slate-100 text-slate-800 border-slate-200',
    ];
@endphp

<div class="mx-auto max-w-7xl space-y-8">
    <section class="admin-page-header">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-purple-600">Opérations</p>
            <h1 class="mt-2 text-3xl font-black text-gray-900">Réservations</h1>
            <p class="mt-3 max-w-2xl text-gray-600">Suivi de l’activité, contrôle des paiements et accès rapide aux dossiers qui doivent être traités.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.dashboard') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                <i class="fas fa-chart-line"></i>
                Dashboard
            </a>
            <div class="admin-btn-ghost px-5 py-3 text-sm">
                <i class="fas fa-calendar-day"></i>
                {{ now()->format('d/m/Y') }}
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <article class="admin-kpi p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600">Total dossiers</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['total'] }}</p>
                    <p class="mt-2 text-sm text-gray-600">Vue globale des réservations</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-purple-100 text-purple-700">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi admin-kpi-accent p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-700">En attente</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['pending'] }}</p>
                    <p class="mt-2 text-sm text-gray-600">Demandent une action</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-emerald-600">Confirmées</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['confirmed'] }}</p>
                    <p class="mt-2 text-sm text-gray-600">Dossiers validés</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                    <i class="fas fa-circle-check text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600">Revenu confirmé</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ number_format($stats['total_revenue'], 0, ',', ' ') }}</p>
                    <p class="mt-2 text-sm text-gray-600">FCFA encaissés</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-purple-100 text-purple-700">
                    <i class="fas fa-sack-dollar text-2xl"></i>
                </div>
            </div>
        </article>
    </section>

    <section class="admin-panel p-6 sm:p-7">
        <div class="flex flex-col gap-4 border-b border-gray-100 pb-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Filtres</p>
                <h2 class="mt-2 text-2xl font-black text-gray-900">Trouver rapidement le bon dossier</h2>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="admin-btn-ghost px-4 py-3 text-sm">
                <i class="fas fa-rotate-right"></i>
                Réinitialiser
            </a>
        </div>

        <form method="GET" action="{{ route('admin.bookings.index') }}" class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6">
            <label class="xl:col-span-2">
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Recherche</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Référence, client, email..." class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
            </label>

            <label>
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Type</span>
                <select name="booking_type" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                    <option value="">Tous</option>
                    <option value="flight" @selected(request('booking_type') === 'flight')>Vol</option>
                    <option value="event" @selected(request('booking_type') === 'event')>Événement</option>
                    <option value="package" @selected(request('booking_type') === 'package')>Package</option>
                    <option value="location" @selected(request('booking_type') === 'location')>Location</option>
                </select>
            </label>

            <label>
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Statut dossier</span>
                <select name="status" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                    <option value="">Tous</option>
                    <option value="pending" @selected(request('status') === 'pending')>En attente</option>
                    <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmé</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Annulé</option>
                    <option value="completed" @selected(request('status') === 'completed')>Terminé</option>
                </select>
            </label>

            <label>
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Paiement</span>
                <select name="payment_status" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                    <option value="">Tous</option>
                    <option value="pending" @selected(request('payment_status') === 'pending')>En attente</option>
                    <option value="paid" @selected(request('payment_status') === 'paid')>Payé</option>
                    <option value="failed" @selected(request('payment_status') === 'failed')>Échoué</option>
                    <option value="refunded" @selected(request('payment_status') === 'refunded')>Remboursé</option>
                    <option value="partially_paid" @selected(request('payment_status') === 'partially_paid')>Partiel</option>
                </select>
            </label>

            <div class="grid grid-cols-2 gap-4 xl:col-span-2">
                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Du</span>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                </label>
                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Au</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                </label>
            </div>

            <div class="xl:col-span-6 flex flex-wrap gap-3 pt-2">
                <button type="submit" class="admin-btn-primary px-5 py-3 text-sm">
                    <i class="fas fa-magnifying-glass"></i>
                    Lancer la recherche
                </button>
                <span class="inline-flex items-center rounded-full bg-purple-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-purple-700">
                    {{ $bookings->total() }} résultat(s)
                </span>
            </div>
        </form>
    </section>

    <section class="space-y-4 lg:hidden">
        @forelse($bookings as $booking)
            @php
                $type = $typeMap[$booking->booking_type] ?? ['label' => ucfirst($booking->booking_type), 'icon' => 'fa-layer-group', 'tone' => 'bg-slate-100 text-slate-800'];
                $statusTone = $statusMap[$booking->status] ?? 'bg-slate-100 text-slate-800';
                $paymentTone = $paymentMap[$booking->payment_status] ?? 'bg-slate-100 text-slate-800 border-slate-200';
            @endphp
            <article class="admin-panel p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-purple-600">{{ $booking->booking_number }}</p>
                        <h2 class="mt-2 text-lg font-black text-gray-900">{{ $booking->customer_name }}</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ $booking->customer_email ?: 'Email non renseigné' }}</p>
                    </div>
                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $type['tone'] }}">
                        <i class="fas {{ $type['icon'] }} text-[11px]"></i>
                        {{ $type['label'] }}
                    </span>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-gray-50 px-4 py-3">
                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-gray-500">Date</p>
                        <p class="mt-2 text-sm font-bold text-gray-900">{{ $booking->booking_date?->format('d/m/Y H:i') ?: $booking->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="rounded-2xl bg-gray-50 px-4 py-3">
                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-gray-500">Montant</p>
                        <p class="mt-2 text-sm font-bold text-gray-900">{{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</p>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $statusTone }}">{{ $booking->status }}</span>
                    <span class="inline-flex rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $paymentTone }}">{{ str_replace('_', ' ', $booking->payment_status) }}</span>
                </div>

                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="admin-btn-primary px-4 py-3 text-sm">
                        <i class="fas fa-eye"></i>
                        Ouvrir
                    </a>

                    @if($booking->status === 'pending')
                        <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="admin-btn-ghost px-4 py-3 text-sm">
                                <i class="fas fa-check"></i>
                                Confirmer
                            </button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="admin-panel px-6 py-14 text-center">
                <i class="fas fa-inbox text-5xl text-gray-300"></i>
                <p class="mt-4 text-lg font-bold text-gray-700">Aucune réservation trouvée</p>
                <p class="mt-2 text-sm text-gray-500">Ajustez les filtres ou revenez à la vue complète.</p>
            </div>
        @endforelse
    </section>

    <section class="admin-panel hidden overflow-hidden lg:block">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Liste</p>
                <h2 class="mt-2 text-2xl font-black text-gray-900">Réservations récentes</h2>
            </div>
            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-amber-700">{{ $bookings->total() }} dossiers</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-gray-500">Référence</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-gray-500">Client</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-gray-500">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-gray-500">Réservation</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-gray-500">Voyage</th>
                        <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-[0.18em] text-gray-500">Montant</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-gray-500">Statut</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-gray-500">Paiement</th>
                        <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-[0.18em] text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($bookings as $booking)
                        @php
                            $type = $typeMap[$booking->booking_type] ?? ['label' => ucfirst($booking->booking_type), 'icon' => 'fa-layer-group', 'tone' => 'bg-slate-100 text-slate-800'];
                            $statusTone = $statusMap[$booking->status] ?? 'bg-slate-100 text-slate-800';
                            $paymentTone = $paymentMap[$booking->payment_status] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                        @endphp
                        <tr class="align-top transition hover:bg-[#fcfaf7]">
                            <td class="px-6 py-5">
                                <p class="text-sm font-black text-purple-700">{{ $booking->booking_number }}</p>
                                <p class="mt-1 text-xs text-gray-500">#{{ $booking->id }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-bold text-gray-900">{{ $booking->customer_name }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $booking->customer_email ?: 'Email non renseigné' }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $type['tone'] }}">
                                    <i class="fas {{ $type['icon'] }} text-[11px]"></i>
                                    {{ $type['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-sm text-gray-700">
                                {{ $booking->booking_date?->format('d/m/Y H:i') ?: $booking->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-5 text-sm text-gray-700">
                                {{ $booking->travel_date?->format('d/m/Y') ?: 'À confirmer' }}
                            </td>
                            <td class="px-6 py-5 text-right">
                                <p class="text-sm font-black text-gray-900">{{ number_format($booking->final_amount, 0, ',', ' ') }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $booking->currency }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $statusTone }}">{{ $booking->status }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $paymentTone }}">{{ str_replace('_', ' ', $booking->payment_status) }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-purple-50 text-purple-700 transition hover:bg-purple-100" title="Ouvrir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 transition hover:bg-emerald-100" title="Confirmer">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Supprimer cette réservation ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-red-50 text-red-700 transition hover:bg-red-100" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-14 text-center">
                                <i class="fas fa-inbox text-5xl text-gray-300"></i>
                                <p class="mt-4 text-lg font-bold text-gray-700">Aucune réservation trouvée</p>
                                <p class="mt-2 text-sm text-gray-500">Essayez une autre combinaison de filtres.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-4 border-t border-gray-100 px-6 py-5 md:flex-row md:items-center md:justify-between">
            <p class="text-sm text-gray-600">
                Affichage de <strong>{{ $bookings->firstItem() ?? 0 }}</strong> à <strong>{{ $bookings->lastItem() ?? 0 }}</strong> sur <strong>{{ $bookings->total() }}</strong> réservations
            </p>
            {{ $bookings->links('pagination::tailwind') }}
        </div>
    </section>

    @if($bookings->hasPages())
        <div class="lg:hidden">
            {{ $bookings->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
