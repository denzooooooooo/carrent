@extends('admin.layouts.app')

@section('title', 'Modérer un avis')

@section('content')
    @php
        $typeLabels = [
            'flight' => ['label' => 'Vol', 'tone' => 'bg-sky-100 text-sky-700'],
            'event' => ['label' => 'Événement', 'tone' => 'bg-purple-100 text-purple-700'],
            'package' => ['label' => 'Package', 'tone' => 'bg-amber-100 text-amber-700'],
        ];
        $type = $typeLabels[$review->item_type] ?? ['label' => ucfirst($review->item_type), 'tone' => 'bg-slate-100 text-slate-700'];
    @endphp

    <div class="mx-auto max-w-7xl space-y-8 py-2">
        <section class="admin-page-header">
            <div class="max-w-3xl">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $type['tone'] }}">
                        {{ $type['label'] }}
                    </span>
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $review->is_approved ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $review->is_approved ? 'Approuvé' : 'En attente' }}
                    </span>
                </div>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Modération d’avis</h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                    Vérifie le contenu, ajuste la publication et ajoute une réponse admin visible côté site si nécessaire.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                @if ($review->booking)
                    <a href="{{ route('admin.bookings.show', $review->booking) }}" class="admin-btn-ghost px-5 py-3 text-sm">
                        <i class="fas fa-ticket"></i>
                        Voir la réservation
                    </a>
                @endif
                <a href="{{ route('admin.reviews.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                    <i class="fas fa-arrow-left"></i>
                    Retour à la liste
                </a>
            </div>
        </section>

        @if ($errors->any())
            <div class="rounded-[1.5rem] border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                <p class="font-semibold text-red-800">Des champs sont invalides</p>
                <ul class="mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1.2fr,0.8fr]">
            <div class="space-y-6">
                <article class="admin-panel p-6 sm:p-7">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Contenu client</p>
                            <h2 class="mt-3 text-2xl font-black text-slate-950">{{ $review->title ?: 'Avis sans titre' }}</h2>
                        </div>
                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-amber-700">
                            {{ $review->rating_label }}
                        </span>
                    </div>

                    <div class="mt-6 grid gap-4 lg:grid-cols-2">
                        <div class="rounded-[1.5rem] bg-slate-50 px-5 py-5">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Commentaire</p>
                            <p class="mt-3 text-sm leading-7 text-slate-700">{{ $review->comment ?: 'Aucun commentaire.' }}</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 px-5 py-5">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Réponse admin actuelle</p>
                            <p class="mt-3 text-sm leading-7 text-slate-700">{{ $review->admin_response ?: 'Aucune réponse enregistrée.' }}</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 px-5 py-5">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Points positifs</p>
                            <p class="mt-3 text-sm leading-7 text-slate-700">{{ $review->pros ?: 'Aucun point positif renseigné.' }}</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 px-5 py-5">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Points négatifs</p>
                            <p class="mt-3 text-sm leading-7 text-slate-700">{{ $review->cons ?: 'Aucun point négatif renseigné.' }}</p>
                        </div>
                    </div>
                </article>

                <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST" class="admin-panel p-6 sm:p-7">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Modération</p>
                            <h2 class="mt-3 text-2xl font-black text-slate-950">Publier et répondre</h2>
                        </div>
                        <button type="submit" class="admin-btn-primary px-5 py-3 text-sm">
                            <i class="fas fa-floppy-disk"></i>
                            Enregistrer
                        </button>
                    </div>

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="rating" class="mb-2 block text-sm font-semibold text-slate-700">Note</label>
                            <select id="rating" name="rating" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                                @for ($rating = 1; $rating <= 5; $rating++)
                                    <option value="{{ $rating }}" @selected((int) old('rating', $review->rating) === $rating)>{{ $rating }}/5</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Titre</label>
                            <input id="title" name="title" type="text" value="{{ old('title', $review->title) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label for="comment" class="mb-2 block text-sm font-semibold text-slate-700">Commentaire</label>
                            <textarea id="comment" name="comment" rows="5" class="w-full rounded-[1.35rem] border border-slate-200 bg-white px-4 py-3 text-sm">{{ old('comment', $review->comment) }}</textarea>
                        </div>

                        <div>
                            <label for="pros" class="mb-2 block text-sm font-semibold text-slate-700">Points positifs</label>
                            <textarea id="pros" name="pros" rows="4" class="w-full rounded-[1.35rem] border border-slate-200 bg-white px-4 py-3 text-sm">{{ old('pros', $review->pros) }}</textarea>
                        </div>

                        <div>
                            <label for="cons" class="mb-2 block text-sm font-semibold text-slate-700">Points négatifs</label>
                            <textarea id="cons" name="cons" rows="4" class="w-full rounded-[1.35rem] border border-slate-200 bg-white px-4 py-3 text-sm">{{ old('cons', $review->cons) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label for="admin_response" class="mb-2 block text-sm font-semibold text-slate-700">Réponse admin</label>
                            <textarea id="admin_response" name="admin_response" rows="4" class="w-full rounded-[1.35rem] border border-slate-200 bg-white px-4 py-3 text-sm" placeholder="Réponse affichée côté client...">{{ old('admin_response', $review->admin_response) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <label class="flex items-center gap-3 rounded-[1.4rem] border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-700">
                            <input type="checkbox" name="is_verified" value="1" class="h-4 w-4 rounded border-slate-300 text-purple-700 focus:ring-purple-500" {{ old('is_verified', $review->is_verified) ? 'checked' : '' }}>
                            <span>
                                <span class="block font-semibold text-slate-900">Avis vérifié</span>
                                <span class="block text-xs text-slate-500">Lié à une réservation contrôlée.</span>
                            </span>
                        </label>

                        <label class="flex items-center gap-3 rounded-[1.4rem] border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-700">
                            <input type="checkbox" name="is_approved" value="1" class="h-4 w-4 rounded border-slate-300 text-purple-700 focus:ring-purple-500" {{ old('is_approved', $review->is_approved) ? 'checked' : '' }}>
                            <span>
                                <span class="block font-semibold text-slate-900">Publier l’avis</span>
                                <span class="block text-xs text-slate-500">Visible sur les pages publiques.</span>
                            </span>
                        </label>
                    </div>
                </form>
            </div>

            <aside class="space-y-6">
                <article class="admin-panel p-6">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Contexte</p>
                    <h2 class="mt-3 text-2xl font-black text-slate-950">Informations clés</h2>
                    <dl class="mt-6 space-y-4 text-sm text-slate-600">
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                            <dt class="font-semibold text-slate-500">Client</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ $review->reviewer_name }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                            <dt class="font-semibold text-slate-500">Email</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ $review->user?->email ?: 'Non renseigné' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                            <dt class="font-semibold text-slate-500">Élément noté</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ $review->item_label }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                            <dt class="font-semibold text-slate-500">Réservation</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ $review->booking?->booking_number ?: 'Non liée' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                            <dt class="font-semibold text-slate-500">Créé le</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ optional($review->created_at)->format('d/m/Y H:i') ?: 'N/A' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-semibold text-slate-500">Helpful count</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ $review->helpful_count }}</dd>
                        </div>
                    </dl>
                </article>

                <article class="admin-panel p-6">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Actions</p>
                    <div class="mt-5 grid gap-3">
                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cet avis ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn-ghost w-full justify-center border-red-200 text-red-700 hover:border-red-300 hover:bg-red-50">
                                <i class="fas fa-trash"></i>
                                Supprimer l’avis
                            </button>
                        </form>
                    </div>
                </article>
            </aside>
        </section>
    </div>
@endsection
