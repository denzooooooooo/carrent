@extends('admin.layouts.app')

@section('title', 'Import événements')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 py-2">
        <section class="admin-page-header">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--admin-brand)]">Automatisation catalogue</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Importer depuis PDF ou tableur
                </h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                    Crée un brouillon d’événement depuis un PDF texte ou ajoute des packages depuis un fichier CSV/XLSX. Après import, tu peux finaliser la fiche, l’image et la publication.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.events.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                    <i class="fas fa-arrow-left"></i>
                    Retour aux événements
                </a>
                <a href="{{ route('admin.events.create') }}" class="admin-btn-primary px-5 py-3 text-sm">
                    <i class="fas fa-plus-circle"></i>
                    Création manuelle
                </a>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.25fr)_minmax(0,1fr)]">
            <article class="admin-panel p-6 sm:p-7">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-red-500">Import intelligent</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-950">Créer un événement depuis un PDF</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-600">
                            Le fichier est analysé, les titres, dates, ville et packages sont détectés puis un brouillon éditable est généré automatiquement.
                        </p>
                    </div>
                    <span class="inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-red-50 text-red-500">
                        <i class="fas fa-file-pdf text-2xl"></i>
                    </span>
                </div>

                <form action="{{ route('admin.events.import-pdf') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <label for="pdf_file" class="mb-2 block text-sm font-semibold text-slate-700">Catalogue PDF *</label>
                        <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" required
                            class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                        <p class="mt-2 text-xs leading-6 text-slate-500">
                            PDF texte recommandé. Les scans image bruts ne sont pas fiables sans OCR.
                        </p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="category_id" class="mb-2 block text-sm font-semibold text-slate-700">Catégorie forcée</label>
                            <select name="category_id" id="category_id" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                                <option value="">Détection automatique</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name_fr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="type_id" class="mb-2 block text-sm font-semibold text-slate-700">Type forcé</label>
                            <select name="type_id" id="type_id" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                                <option value="">Détection automatique</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}" @selected((string) old('type_id') === (string) $type->id)>{{ $type->name_fr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="city" class="mb-2 block text-sm font-semibold text-slate-700">Ville</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm"
                                placeholder="Optionnel si le PDF est incomplet">
                        </div>
                        <div>
                            <label for="country" class="mb-2 block text-sm font-semibold text-slate-700">Pays</label>
                            <input type="text" name="country" id="country" value="{{ old('country') }}" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm"
                                placeholder="Optionnel">
                        </div>
                        <div class="md:col-span-2">
                            <label for="organizer" class="mb-2 block text-sm font-semibold text-slate-700">Organisateur</label>
                            <input type="text" name="organizer" id="organizer" value="{{ old('organizer') }}" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm"
                                placeholder="Carré Premium par défaut">
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="flex items-center gap-3 rounded-2xl border border-[#eadfce] bg-slate-50 px-4 py-4">
                            <input type="checkbox" name="is_featured" value="1"
                                class="h-4 w-4 rounded border-slate-300 text-[var(--admin-brand)] focus:ring-[var(--admin-brand)]"
                                {{ old('is_featured') ? 'checked' : '' }}>
                            <span>
                                <span class="block text-sm font-semibold text-slate-900">Marquer en vedette</span>
                                <span class="block text-xs text-slate-500">Active le flag marketing dès la création.</span>
                            </span>
                        </label>
                        <label class="flex items-center gap-3 rounded-2xl border border-[#eadfce] bg-slate-50 px-4 py-4">
                            <input type="checkbox" name="publish_immediately" value="1"
                                class="h-4 w-4 rounded border-slate-300 text-[var(--admin-brand)] focus:ring-[var(--admin-brand)]"
                                {{ old('publish_immediately') ? 'checked' : '' }}>
                            <span>
                                <span class="block text-sm font-semibold text-slate-900">Publier immédiatement</span>
                                <span class="block text-xs text-slate-500">Sinon l’événement reste en brouillon.</span>
                            </span>
                        </label>
                    </div>

                    <div class="rounded-[1.5rem] border border-red-100 bg-red-50/70 px-5 py-4 text-sm text-red-700">
                        <p class="font-semibold text-red-800">Ce que fait l’import PDF</p>
                        <ul class="mt-2 space-y-1 leading-6">
                            <li>Détecte automatiquement le titre, les dates, la localisation et plusieurs packages si le texte est exploitable.</li>
                            <li>Crée un brouillon éditable sans publier par défaut.</li>
                            <li>Te redirige ensuite vers la fiche complète pour correction manuelle.</li>
                        </ul>
                    </div>

                    <button type="submit" class="admin-btn-primary w-full px-6 py-4 text-sm sm:w-auto">
                        <i class="fas fa-wand-magic"></i>
                        Générer un brouillon depuis le PDF
                    </button>
                </form>
            </article>

            <div class="space-y-6">
                <article class="admin-panel p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-accent)]">Import ciblé</p>
                            <h2 class="mt-2 text-2xl font-bold text-slate-950">Ajouter des packages par tableur</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                Utilise cette option pour enrichir un événement existant avec une grille tarifaire structurée.
                            </p>
                        </div>
                        <span class="inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-amber-50 text-[var(--admin-accent)]">
                            <i class="fas fa-file-excel text-2xl"></i>
                        </span>
                    </div>

                    <form action="{{ route('admin.events.import-packages') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
                        @csrf

                        <div>
                            <label for="event_id" class="mb-2 block text-sm font-semibold text-slate-700">Événement cible</label>
                            <select name="event_id" id="event_id" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                                <option value="">Détection automatique depuis le fichier</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}" @selected((string) old('event_id') === (string) $event->id)>{{ $event->title_fr }} · {{ $event->city }}</option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-slate-500">Si renseigné, l’import est forcé sur cet événement.</p>
                        </div>

                        <div>
                            <label for="excel_file" class="mb-2 block text-sm font-semibold text-slate-700">Fichier CSV / Excel *</label>
                            <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv,.txt" required
                                class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                        </div>

                        <div class="rounded-[1.5rem] border border-sky-100 bg-sky-50/70 px-5 py-4 text-sm text-sky-800">
                            <p class="font-semibold">Colonnes acceptées</p>
                            <p class="mt-2 leading-6">
                                `event_title`, `city`, `package_name_fr`, `package_name_en`, `package_code`, `description_fr`, `included`, `price`, `currency`, `available_quantity`, `max_per_order`
                            </p>
                        </div>

                        <button type="submit" class="admin-btn-secondary w-full px-6 py-4 text-sm sm:w-auto">
                            <i class="fas fa-file-import"></i>
                            Importer la grille tarifaire
                        </button>
                    </form>
                </article>

                <article class="admin-panel p-6 sm:p-7">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-400">Bonnes pratiques</p>
                    <div class="mt-4 space-y-4 text-sm leading-7 text-slate-600">
                        <div class="rounded-2xl bg-slate-50 px-4 py-4">
                            <p class="font-semibold text-slate-900">1. Importe, puis vérifie</p>
                            <p class="mt-1">Le PDF crée une base de travail. Vérifie systématiquement les dates, prix, inventaires et descriptions avant publication.</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-4 py-4">
                            <p class="font-semibold text-slate-900">2. Ajoute l’image et les CTA</p>
                            <p class="mt-1">L’import ne suffit pas pour une page premium. Complète l’image principale, les messages et le SEO dans la fiche événement.</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-4 py-4">
                            <p class="font-semibold text-slate-900">3. Contrôle la vente</p>
                            <p class="mt-1">Vérifie la cohérence entre packages, zones, stock et prix avant d’activer la publication côté client.</p>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
@endsection
