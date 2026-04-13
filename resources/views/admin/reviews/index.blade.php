@extends('admin.layouts.app')

@section('title', 'Gestion des avis')

@section('content')
    @php
        $typeLabels = [
            'flight' => ['label' => 'Vol', 'tone' => 'bg-sky-100 text-sky-700'],
            'event' => ['label' => 'Événement', 'tone' => 'bg-purple-100 text-purple-700'],
            'package' => ['label' => 'Package', 'tone' => 'bg-amber-100 text-amber-700'],
        ];
    @endphp

    <div class="mx-auto max-w-7xl space-y-8">
        <section class="admin-page-header">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-purple-600">Réputation</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">Avis clients</h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                    Modère les retours clients, valide les avis vérifiés et apporte des réponses visibles côté site.
                </p>
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

        <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-5">
            <article class="admin-kpi p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600">Total</p>
                <p class="mt-5 text-4xl font-black text-slate-950">{{ $stats['total'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-slate-600">avis enregistrés</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-emerald-600">Approuvés</p>
                <p class="mt-5 text-4xl font-black text-slate-950">{{ $stats['approved'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-slate-600">visibles côté client</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-600">En attente</p>
                <p class="mt-5 text-4xl font-black text-slate-950">{{ $stats['pending'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-slate-600">à modérer</p>
            </article>
            <article class="admin-kpi p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-sky-600">Vérifiés</p>
                <p class="mt-5 text-4xl font-black text-slate-950">{{ $stats['verified'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-slate-600">liés à une réservation</p>
            </article>
            <article class="admin-kpi admin-kpi-accent p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-700">Note moyenne</p>
                <p class="mt-5 text-4xl font-black text-slate-950">{{ number_format((float) ($stats['average_rating'] ?? 0), 1, ',', ' ') }}</p>
                <p class="mt-2 text-sm text-slate-600">sur 5</p>
            </article>
        </section>

        <section class="admin-panel p-6 sm:p-7">
            <div class="flex flex-col gap-4 border-b border-slate-100 pb-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Filtres</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-950">Trier les retours clients</h2>
                </div>
                <a href="{{ route('admin.reviews.index') }}" class="admin-btn-ghost px-4 py-3 text-sm">
                    <i class="fas fa-rotate-right"></i>
                    Réinitialiser
                </a>
            </div>

            <form method="GET" action="{{ route('admin.reviews.index') }}" class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                <label class="xl:col-span-2">
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-500">Recherche</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre, commentaire, client, email..." class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                </label>

                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-500">Type</span>
                    <select name="item_type" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                        <option value="">Tous</option>
                        <option value="flight" @selected(request('item_type') === 'flight')>Vols</option>
                        <option value="event" @selected(request('item_type') === 'event')>Événements</option>
                        <option value="package" @selected(request('item_type') === 'package')>Packages</option>
                    </select>
                </label>

                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-500">Publication</span>
                    <select name="is_approved" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                        <option value="">Tous</option>
                        <option value="1" @selected(request('is_approved') === '1')>Approuvés</option>
                        <option value="0" @selected(request('is_approved') === '0')>En attente</option>
                    </select>
                </label>

                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-500">Note</span>
                    <select name="rating" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                        <option value="">Toutes</option>
                        @for ($rating = 5; $rating >= 1; $rating--)
                            <option value="{{ $rating }}" @selected(request('rating') == (string) $rating)>{{ $rating }}/5</option>
                        @endfor
                    </select>
                </label>

                <div class="xl:col-span-5 flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="admin-btn-primary px-5 py-3 text-sm">
                        <i class="fas fa-magnifying-glass"></i>
                        Filtrer
                    </button>
                    <span class="inline-flex items-center rounded-full bg-purple-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-purple-700">
                        {{ $reviews->total() }} résultat(s)
                    </span>
                </div>
            </form>
        </section>

        <section class="admin-panel overflow-hidden">
            @if ($reviews->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/80">
                            <tr class="text-left">
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Client</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Avis</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Contexte</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Statut</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-[0.18em] text-slate-500">Date</th>
                                <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-[0.18em] text-slate-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($reviews as $review)
                                @php
                                    $type = $typeLabels[$review->item_type] ?? ['label' => ucfirst($review->item_type), 'tone' => 'bg-slate-100 text-slate-700'];
                                @endphp
                                <tr class="align-top">
                                    <td class="px-6 py-5">
                                        <p class="font-semibold text-slate-900">{{ $review->reviewer_name }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $review->user?->email ?? 'Sans email' }}</p>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="max-w-xl">
                                            <div class="flex items-center gap-3">
                                                <p class="font-semibold text-slate-900">{{ $review->title ?: 'Sans titre' }}</p>
                                                <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700">{{ $review->rating_label }}</span>
                                            </div>
                                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($review->comment ?: 'Aucun commentaire.', 150) }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $type['tone'] }}">
                                            {{ $type['label'] }}
                                        </span>
                                        <p class="mt-3 text-sm font-semibold text-slate-900">{{ $review->item_label }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $review->booking?->booking_number ?: 'Sans réservation liée' }}</p>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col gap-2">
                                            <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $review->is_approved ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                                {{ $review->is_approved ? 'Approuvé' : 'En attente' }}
                                            </span>
                                            <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $review->is_verified ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-600' }}">
                                                {{ $review->is_verified ? 'Vérifié' : 'Non vérifié' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ optional($review->created_at)->format('d/m/Y H:i') ?: 'N/A' }}
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.reviews.edit', $review->id) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-sky-50 text-sky-700 transition hover:bg-sky-100" title="Modérer">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Supprimer cet avis ?');">
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

                @if ($reviews->hasPages())
                    <div class="border-t border-slate-100 px-6 py-5">
                        {{ $reviews->links('pagination::tailwind') }}
                    </div>
                @endif
            @else
                <div class="px-6 py-16 text-center">
                    <i class="fas fa-star text-5xl text-slate-300"></i>
                    <p class="mt-4 text-lg font-bold text-slate-700">Aucun avis trouvé</p>
                    <p class="mt-2 text-sm text-slate-500">Ajuste les filtres ou attends les premiers retours clients.</p>
                </div>
            @endif
        </section>
    </div>
@endsection
