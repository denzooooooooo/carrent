@extends('admin.layouts.app')

@section('title', $pageTitle)

@section('content')
    @php
        $isEdit = $event->exists;
        $route = $isEdit ? route('admin.events.update', $event) : route('admin.events.store');
        $formatAdminTime = static function ($value): string {
            if (blank($value)) {
                return '';
            }

            $stringValue = $value instanceof \DateTimeInterface ? $value->format('H:i') : trim((string) $value);

            return preg_match('/^\d{2}:\d{2}:\d{2}$/', $stringValue) ? substr($stringValue, 0, 5) : $stringValue;
        };

        $packageDefaults = [
            [
                'id' => null,
                'package_name_fr' => '',
                'package_name_en' => '',
                'package_code' => '',
                'description_fr' => '',
                'venue_details_fr' => '',
                'description_included_fr' => '',
                'price' => '',
                'currency' => 'XOF',
                'minimum_quantity' => 1,
                'available_quantity' => '',
                'max_per_order' => 6,
                'sort_order' => 1,
                'is_active' => true,
            ],
        ];

        $zoneDefaults = [
            [
                'id' => null,
                'zone_name_fr' => '',
                'zone_name_en' => '',
                'zone_code' => '',
                'zone_type' => 'standard',
                'price' => '',
                'total_seats' => '',
                'available_seats' => '',
                'description_fr' => '',
                'is_active' => true,
            ],
        ];

        $packages = old(
            'packages',
            $isEdit
                ? ($event->allPackages->map(fn ($package) => [
                    'id' => $package->id,
                    'package_name_fr' => $package->package_name_fr,
                    'package_name_en' => $package->package_name_en,
                    'package_code' => $package->package_code,
                    'description_fr' => $package->description_fr,
                    'venue_details_fr' => $package->venue_details_fr,
                    'description_included_fr' => $package->description_included_fr,
                    'price' => $package->price,
                    'currency' => $package->currency ?: 'XOF',
                    'minimum_quantity' => $package->minimum_quantity ?: 1,
                    'available_quantity' => $package->available_quantity,
                    'max_per_order' => $package->max_per_order ?: 6,
                    'sort_order' => $package->sort_order ?? 0,
                    'is_active' => $package->is_active,
                ])->values()->all())
                : $packageDefaults,
        );

        $seatZones = old(
            'seat_zones',
            $isEdit
                ? ($event->seatZones->map(fn ($zone) => [
                    'id' => $zone->id,
                    'zone_name_fr' => $zone->zone_name_fr,
                    'zone_name_en' => $zone->zone_name_en,
                    'zone_code' => $zone->zone_code,
                    'zone_type' => $zone->zone_type ?: 'standard',
                    'price' => $zone->price,
                    'total_seats' => $zone->total_seats,
                    'available_seats' => $zone->available_seats,
                    'description_fr' => $zone->description_fr,
                    'is_active' => $zone->is_active,
                ])->values()->all())
                : $zoneDefaults,
        );

        if (empty($packages)) {
            $packages = $packageDefaults;
        }

        if (empty($seatZones)) {
            $seatZones = $zoneDefaults;
        }

        $errorKeys = collect($errors->keys());
        $defaultTab = 'essentials';

        if ($errorKeys->contains(fn ($key) => str_starts_with($key, 'packages') || str_starts_with($key, 'seat_zones'))) {
            $defaultTab = 'offers';
        } elseif ($errorKeys->contains(fn ($key) => str_contains($key, '_en') || str_starts_with($key, 'meta_') || in_array($key, ['program_fr', 'conditions_fr']))) {
            $defaultTab = 'advanced';
        } elseif ($errorKeys->contains(fn ($key) => in_array($key, ['tagline_fr', 'description_fr', 'organizer', 'source_catalog', 'image']))) {
            $defaultTab = 'content';
        }

        $coverUrl = $isEdit ? $event->getFirstMediaUrl('avatar', 'small') : null;
        $previewTitle = old('title_fr', $event->title_fr) ?: 'Titre à renseigner';
        $previewDate = old('event_date', $event->event_date ? $event->event_date->format('Y-m-d') : '');
        $previewTime = $formatAdminTime(old('event_time', $event->event_time));
        $previewVenue = old('venue_name', $event->venue_name) ?: 'Lieu à renseigner';
        $previewCity = old('city', $event->city) ?: 'Ville';
        $previewCountry = old('country', $event->country) ?: 'Pays';
        $previewMinPrice = old('min_price', $event->min_price);
        $previewMaxPrice = old('max_price', $event->max_price);
        $previewSeats = old('total_seats', $event->total_seats);
        $isActiveChecked = old('is_active', $event->is_active ?? true);
        $isFeaturedChecked = old('is_featured', $event->is_featured);
        $titleEnValue = old('title_en', blank($event->title_en) || $event->title_en === $event->title_fr ? '' : $event->title_en);
        $taglineEnValue = old('tagline_en', blank($event->tagline_en) || $event->tagline_en === $event->tagline_fr ? '' : $event->tagline_en);
        $descriptionEnValue = old('description_en', blank($event->description_en) || $event->description_en === $event->description_fr ? '' : $event->description_en);
        $programEnValue = old('program_en', blank($event->program_en) || $event->program_en === $event->program_fr ? '' : $event->program_en);
        $conditionsEnValue = old('conditions_en', blank($event->conditions_en) || $event->conditions_en === $event->conditions_fr ? '' : $event->conditions_en);
        $metaTitleEnValue = old('meta_title_en', blank($event->meta_title_en) || $event->meta_title_en === $event->meta_title_fr ? '' : $event->meta_title_en);
        $metaDescriptionEnValue = old('meta_description_en', blank($event->meta_description_en) || $event->meta_description_en === $event->meta_description_fr ? '' : $event->meta_description_en);
    @endphp

    <div class="mx-auto max-w-8xl space-y-6 py-2" x-data="{ tab: '{{ $defaultTab }}' }">
        <section class="admin-page-header">
            <div class="max-w-4xl">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--admin-brand)]">
                    {{ $isEdit ? 'Édition événement' : 'Création événement' }}
                </p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    {{ $pageTitle }}
                </h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                    Formulaire allégé: l’essentiel d’abord, les offres ensuite, et les champs avancés séparés pour éviter le bruit.
                </p>

                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="rounded-full bg-[var(--admin-brand-soft)] px-3 py-1 text-xs font-semibold text-[var(--admin-brand)]">
                        {{ count($packages) }} package(s)
                    </span>
                    <span class="rounded-full bg-[var(--admin-accent-soft)] px-3 py-1 text-xs font-semibold text-[var(--admin-warning)]">
                        {{ count($seatZones) }} zone(s)
                    </span>
                    @if ($coverUrl)
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                            Image existante
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.events.import.form') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                    <i class="fas fa-file-import"></i>
                    Importer un PDF
                </a>
                @if ($isEdit)
                    <a href="{{ route('admin.events.show', $event) }}" class="admin-btn-ghost px-5 py-3 text-sm">
                        <i class="fas fa-eye"></i>
                        Voir la fiche
                    </a>
                @endif
                <a href="{{ route('admin.events.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
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

        <form action="{{ $route }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <section class="admin-panel border border-[#eadfce] bg-white p-3 sm:p-4">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="-mx-1 overflow-x-auto px-1">
                        <div class="flex min-w-max flex-nowrap gap-2">
                            <button type="button" @click="tab = 'essentials'" :class="tab === 'essentials' ? 'admin-btn-primary' : 'admin-btn-ghost'" class="px-4 py-2.5 text-sm">
                            <i class="fas fa-pen-ruler"></i>
                            Essentiel
                            </button>
                            <button type="button" @click="tab = 'content'" :class="tab === 'content' ? 'admin-btn-primary' : 'admin-btn-ghost'" class="px-4 py-2.5 text-sm">
                            <i class="fas fa-align-left"></i>
                            Contenu
                            </button>
                            <button type="button" @click="tab = 'offers'" :class="tab === 'offers' ? 'admin-btn-primary' : 'admin-btn-ghost'" class="px-4 py-2.5 text-sm">
                            <i class="fas fa-box-open"></i>
                            Offres
                            </button>
                            <button type="button" @click="tab = 'advanced'" :class="tab === 'advanced' ? 'admin-btn-primary' : 'admin-btn-ghost'" class="px-4 py-2.5 text-sm">
                            <i class="fas fa-sliders"></i>
                            Avancé
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 lg:justify-end">
                        <button type="submit" class="admin-btn-primary px-6 py-3 text-sm">
                            <i class="fas fa-floppy-disk"></i>
                            {{ $isEdit ? 'Enregistrer' : 'Créer l’événement' }}
                        </button>
                    </div>
                </div>
            </section>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
                <div class="space-y-6">
                    <section x-show="tab === 'essentials'" x-transition.opacity x-cloak class="space-y-6">
                        <article class="admin-panel p-6 sm:p-7">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Base</p>
                                    <h2 class="mt-2 text-xl font-bold text-slate-950">Fiche courte</h2>
                                    <p class="mt-2 text-sm text-slate-600">Ces champs suffisent pour créer une fiche claire et exploitable.</p>
                                </div>
                                <span class="rounded-full bg-[var(--admin-brand-soft)] px-3 py-1 text-xs font-semibold text-[var(--admin-brand)]">
                                    Obligatoire en priorité
                                </span>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-3">
                                <div>
                                    <label for="category_id" class="mb-2 block text-sm font-semibold text-slate-700">Catégorie *</label>
                                    <select name="category_id" id="category_id" required class="w-full px-4 py-3 text-sm">
                                        <option value="">Sélectionner</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected((string) old('category_id', $event->category_id) === (string) $category->id)>
                                                {{ $category->name_fr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="type_id" class="mb-2 block text-sm font-semibold text-slate-700">Type</label>
                                    <select name="type_id" id="type_id" class="w-full px-4 py-3 text-sm">
                                        <option value="">Sélectionner</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}" @selected((string) old('type_id', $event->type_id) === (string) $type->id)>
                                                {{ $type->name_fr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="family" class="mb-2 block text-sm font-semibold text-slate-700">Famille</label>
                                    <select name="family" id="family" class="w-full px-4 py-3 text-sm">
                                        <option value="">Détection automatique</option>
                                        <option value="sportif" @selected(old('family', $event->family) === 'sportif')>Sportif</option>
                                        <option value="culturel" @selected(old('family', $event->family) === 'culturel')>Culturel</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="title_fr" class="mb-2 block text-sm font-semibold text-slate-700">Titre FR *</label>
                                    <input type="text" name="title_fr" id="title_fr" required value="{{ old('title_fr', $event->title_fr) }}" placeholder="Ex: Chelsea vs PSG - Loges et hospitalité" class="w-full px-4 py-3 text-sm">
                                </div>

                                <div>
                                    <label for="min_price" class="mb-2 block text-sm font-semibold text-slate-700">Prix min *</label>
                                    <input type="number" step="0.01" min="0" name="min_price" id="min_price" required value="{{ old('min_price', $event->min_price) }}" class="w-full px-4 py-3 text-sm">
                                </div>

                                <div>
                                    <label for="max_price" class="mb-2 block text-sm font-semibold text-slate-700">Prix max</label>
                                    <input type="number" step="0.01" min="0" name="max_price" id="max_price" value="{{ old('max_price', $event->max_price) }}" class="w-full px-4 py-3 text-sm">
                                </div>

                                <div>
                                    <label for="total_seats" class="mb-2 block text-sm font-semibold text-slate-700">Capacité *</label>
                                    <input type="number" min="1" name="total_seats" id="total_seats" required value="{{ old('total_seats', $event->total_seats) }}" class="w-full px-4 py-3 text-sm">
                                </div>
                            </div>
                        </article>

                        <article class="admin-panel p-6 sm:p-7">
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Agenda</p>
                            <h2 class="mt-2 text-xl font-bold text-slate-950">Date et lieu</h2>

                            <div class="mt-6 grid gap-4 md:grid-cols-4">
                                <div>
                                    <label for="event_date" class="mb-2 block text-sm font-semibold text-slate-700">Date début *</label>
                                    <input type="date" name="event_date" id="event_date" required value="{{ old('event_date', $event->event_date ? $event->event_date->format('Y-m-d') : '') }}" class="w-full px-4 py-3 text-sm">
                                </div>
                                <div>
                                    <label for="event_time" class="mb-2 block text-sm font-semibold text-slate-700">Heure début *</label>
                                    <input type="time" name="event_time" id="event_time" required value="{{ $formatAdminTime(old('event_time', $event->event_time)) }}" class="w-full px-4 py-3 text-sm">
                                </div>
                                <div>
                                    <label for="end_date" class="mb-2 block text-sm font-semibold text-slate-700">Date fin</label>
                                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d') : '') }}" class="w-full px-4 py-3 text-sm">
                                </div>
                                <div>
                                    <label for="end_time" class="mb-2 block text-sm font-semibold text-slate-700">Heure fin</label>
                                    <input type="time" name="end_time" id="end_time" value="{{ $formatAdminTime(old('end_time', $event->end_time)) }}" class="w-full px-4 py-3 text-sm">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="venue_name" class="mb-2 block text-sm font-semibold text-slate-700">Lieu *</label>
                                    <input type="text" name="venue_name" id="venue_name" required value="{{ old('venue_name', $event->venue_name) }}" class="w-full px-4 py-3 text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="venue_address" class="mb-2 block text-sm font-semibold text-slate-700">Adresse *</label>
                                    <input type="text" name="venue_address" id="venue_address" required value="{{ old('venue_address', $event->venue_address) }}" class="w-full px-4 py-3 text-sm">
                                </div>

                                <div>
                                    <label for="city" class="mb-2 block text-sm font-semibold text-slate-700">Ville *</label>
                                    <input type="text" name="city" id="city" required value="{{ old('city', $event->city) }}" class="w-full px-4 py-3 text-sm">
                                </div>
                                <div>
                                    <label for="country" class="mb-2 block text-sm font-semibold text-slate-700">Pays *</label>
                                    <input type="text" name="country" id="country" required value="{{ old('country', $event->country) }}" class="w-full px-4 py-3 text-sm">
                                </div>
                            </div>
                        </article>
                    </section>

                    <section x-show="tab === 'content'" x-transition.opacity x-cloak class="space-y-6">
                        <article class="admin-panel p-6 sm:p-7">
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Présentation</p>
                            <h2 class="mt-2 text-xl font-bold text-slate-950">Texte visible côté client</h2>
                            <p class="mt-2 text-sm text-slate-600">Reste simple. Un message court, une description utile, rien de plus.</p>

                            <div class="mt-6 grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="tagline_fr" class="mb-2 block text-sm font-semibold text-slate-700">Tagline FR</label>
                                    <input type="text" name="tagline_fr" id="tagline_fr" value="{{ old('tagline_fr', $event->tagline_fr) }}" placeholder="Ex: expérience VIP officielle" class="w-full px-4 py-3 text-sm">
                                </div>

                                <div>
                                    <label for="organizer" class="mb-2 block text-sm font-semibold text-slate-700">Organisateur</label>
                                    <input type="text" name="organizer" id="organizer" value="{{ old('organizer', $event->organizer) }}" class="w-full px-4 py-3 text-sm">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="description_fr" class="mb-2 block text-sm font-semibold text-slate-700">Description FR</label>
                                    <textarea name="description_fr" id="description_fr" rows="6" class="w-full px-4 py-3 text-sm">{{ old('description_fr', $event->description_fr) }}</textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="source_catalog" class="mb-2 block text-sm font-semibold text-slate-700">Source catalogue</label>
                                    <input type="text" name="source_catalog" id="source_catalog" value="{{ old('source_catalog', $event->source_catalog) }}" placeholder="Nom du PDF ou source interne" class="w-full px-4 py-3 text-sm">
                                </div>
                            </div>
                        </article>
                    </section>

                    <section x-show="tab === 'offers'" x-transition.opacity x-cloak class="space-y-6">
                        <section class="admin-panel p-6 sm:p-7">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Offre commerciale</p>
                                    <h2 class="mt-2 text-xl font-bold text-slate-950">Packages</h2>
                                    <p class="mt-2 text-sm text-slate-600">Montre d’abord le nom, le prix et la disponibilité. Le reste reste replié.</p>
                                </div>
                                <button type="button" id="add-package" class="admin-btn-secondary px-5 py-3 text-sm">
                                    <i class="fas fa-plus"></i>
                                    Ajouter un package
                                </button>
                            </div>

                            <div id="packages-container" class="mt-6 space-y-4">
                                @foreach ($packages as $index => $package)
                                    <div class="package-item rounded-[1.5rem] border border-[#eadfce] bg-slate-50 p-5" data-index="{{ $index }}">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="text-base font-bold text-slate-950" data-block-title>Package {{ $index + 1 }}</p>
                                                <p class="text-sm text-slate-500">Bloc de vente visible sur la page client.</p>
                                            </div>
                                            <button type="button" class="remove-package inline-flex items-center gap-2 rounded-full bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                                                <i class="fas fa-trash"></i>
                                                Supprimer
                                            </button>
                                        </div>

                                        <input type="hidden" name="packages[{{ $index }}][id]" value="{{ $package['id'] ?? '' }}">

                                        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                                            <div class="xl:col-span-2">
                                                <label class="mb-2 block text-sm font-semibold text-slate-700">Nom FR *</label>
                                                <input type="text" name="packages[{{ $index }}][package_name_fr]" value="{{ $package['package_name_fr'] ?? '' }}" class="w-full px-4 py-3 text-sm">
                                            </div>
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-slate-700">Prix *</label>
                                                <input type="number" min="0" step="0.01" name="packages[{{ $index }}][price]" value="{{ $package['price'] ?? '' }}" class="w-full px-4 py-3 text-sm">
                                            </div>
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-slate-700">Disponible</label>
                                                <input type="number" min="0" name="packages[{{ $index }}][available_quantity]" value="{{ $package['available_quantity'] ?? '' }}" class="w-full px-4 py-3 text-sm">
                                            </div>
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-slate-700">Max / commande</label>
                                                <input type="number" min="1" name="packages[{{ $index }}][max_per_order]" value="{{ $package['max_per_order'] ?? 6 }}" class="w-full px-4 py-3 text-sm">
                                            </div>

                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-slate-700">Quantité min</label>
                                                <input type="number" min="1" name="packages[{{ $index }}][minimum_quantity]" value="{{ $package['minimum_quantity'] ?? 1 }}" class="w-full px-4 py-3 text-sm">
                                            </div>
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-slate-700">Devise</label>
                                                <select name="packages[{{ $index }}][currency]" class="w-full px-4 py-3 text-sm">
                                                    @foreach (['XOF', 'EUR', 'USD'] as $currency)
                                                        <option value="{{ $currency }}" @selected(($package['currency'] ?? 'XOF') === $currency)>{{ $currency }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <details class="mt-4 rounded-[1.2rem] border border-[#eadfce] bg-white px-4 py-4">
                                            <summary class="cursor-pointer text-sm font-semibold text-slate-800">Texte et options</summary>

                                            <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                                <div class="xl:col-span-2">
                                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nom EN</label>
                                                    <input type="text" name="packages[{{ $index }}][package_name_en]" value="{{ $package['package_name_en'] ?? '' }}" class="w-full px-4 py-3 text-sm">
                                                </div>
                                                <div>
                                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Code</label>
                                                    <input type="text" name="packages[{{ $index }}][package_code]" value="{{ $package['package_code'] ?? '' }}" class="w-full px-4 py-3 text-sm">
                                                </div>
                                                <div>
                                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Ordre</label>
                                                    <input type="number" min="0" name="packages[{{ $index }}][sort_order]" value="{{ $package['sort_order'] ?? $index + 1 }}" class="w-full px-4 py-3 text-sm">
                                                </div>
                                                <div class="md:col-span-2 xl:col-span-4">
                                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Détails lieu</label>
                                                    <input type="text" name="packages[{{ $index }}][venue_details_fr]" value="{{ $package['venue_details_fr'] ?? '' }}" class="w-full px-4 py-3 text-sm">
                                                </div>
                                                <div class="md:col-span-2 xl:col-span-4">
                                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Description FR</label>
                                                    <textarea name="packages[{{ $index }}][description_fr]" rows="3" class="w-full px-4 py-3 text-sm">{{ $package['description_fr'] ?? '' }}</textarea>
                                                </div>
                                                <div class="md:col-span-2 xl:col-span-4">
                                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Inclus FR</label>
                                                    <textarea name="packages[{{ $index }}][description_included_fr]" rows="3" class="w-full px-4 py-3 text-sm">{{ $package['description_included_fr'] ?? '' }}</textarea>
                                                </div>
                                            </div>

                                            <label class="mt-4 inline-flex items-center gap-3 text-sm text-slate-700">
                                                <input type="checkbox" name="packages[{{ $index }}][is_active]" value="1" class="h-4 w-4 rounded border-slate-300 text-[var(--admin-brand)] focus:ring-[var(--admin-brand)]" {{ ($package['is_active'] ?? true) ? 'checked' : '' }}>
                                                Package actif
                                            </label>
                                        </details>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <section class="admin-panel p-6 sm:p-7">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Billetterie</p>
                                    <h2 class="mt-2 text-xl font-bold text-slate-950">Zones de sièges</h2>
                                    <p class="mt-2 text-sm text-slate-600">Optionnel. Garde seulement les zones réellement utiles à la vente.</p>
                                </div>
                                <button type="button" id="add-seat-zone" class="admin-btn-secondary px-5 py-3 text-sm">
                                    <i class="fas fa-plus"></i>
                                    Ajouter une zone
                                </button>
                            </div>

                            <div id="seat-zones-container" class="mt-6 space-y-4">
                                @foreach ($seatZones as $index => $zone)
                                    <div class="seat-zone-item rounded-[1.5rem] border border-[#eadfce] bg-slate-50 p-5" data-index="{{ $index }}">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="text-base font-bold text-slate-950" data-block-title>Zone {{ $index + 1 }}</p>
                                                <p class="text-sm text-slate-500">Prix et capacité avant le détail.</p>
                                            </div>
                                            <button type="button" class="remove-seat-zone inline-flex items-center gap-2 rounded-full bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                                                <i class="fas fa-trash"></i>
                                                Supprimer
                                            </button>
                                        </div>

                                        <input type="hidden" name="seat_zones[{{ $index }}][id]" value="{{ $zone['id'] ?? '' }}">

                                        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                            <div class="xl:col-span-2">
                                                <label class="mb-2 block text-sm font-semibold text-slate-700">Nom FR *</label>
                                                <input type="text" name="seat_zones[{{ $index }}][zone_name_fr]" value="{{ $zone['zone_name_fr'] ?? '' }}" class="w-full px-4 py-3 text-sm">
                                            </div>
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-slate-700">Prix *</label>
                                                <input type="number" min="0" step="0.01" name="seat_zones[{{ $index }}][price]" value="{{ $zone['price'] ?? '' }}" class="w-full px-4 py-3 text-sm">
                                            </div>
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-slate-700">Capacité *</label>
                                                <input type="number" min="0" name="seat_zones[{{ $index }}][total_seats]" value="{{ $zone['total_seats'] ?? '' }}" class="w-full px-4 py-3 text-sm">
                                            </div>
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-slate-700">Places dispo</label>
                                                <input type="number" min="0" name="seat_zones[{{ $index }}][available_seats]" value="{{ $zone['available_seats'] ?? '' }}" class="w-full px-4 py-3 text-sm">
                                            </div>
                                        </div>

                                        <details class="mt-4 rounded-[1.2rem] border border-[#eadfce] bg-white px-4 py-4">
                                            <summary class="cursor-pointer text-sm font-semibold text-slate-800">Texte et options</summary>

                                            <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                                <div class="xl:col-span-2">
                                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nom EN</label>
                                                    <input type="text" name="seat_zones[{{ $index }}][zone_name_en]" value="{{ $zone['zone_name_en'] ?? '' }}" class="w-full px-4 py-3 text-sm">
                                                </div>
                                                <div>
                                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Code zone</label>
                                                    <input type="text" name="seat_zones[{{ $index }}][zone_code]" value="{{ $zone['zone_code'] ?? '' }}" class="w-full px-4 py-3 text-sm">
                                                </div>
                                                <div>
                                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Type</label>
                                                    <input type="text" name="seat_zones[{{ $index }}][zone_type]" value="{{ $zone['zone_type'] ?? 'standard' }}" class="w-full px-4 py-3 text-sm">
                                                </div>
                                                <div class="md:col-span-2 xl:col-span-4">
                                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Description FR</label>
                                                    <textarea name="seat_zones[{{ $index }}][description_fr]" rows="3" class="w-full px-4 py-3 text-sm">{{ $zone['description_fr'] ?? '' }}</textarea>
                                                </div>
                                            </div>

                                            <label class="mt-4 inline-flex items-center gap-3 text-sm text-slate-700">
                                                <input type="checkbox" name="seat_zones[{{ $index }}][is_active]" value="1" class="h-4 w-4 rounded border-slate-300 text-[var(--admin-brand)] focus:ring-[var(--admin-brand)]" {{ ($zone['is_active'] ?? true) ? 'checked' : '' }}>
                                                Zone active
                                            </label>
                                        </details>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </section>

                    <section x-show="tab === 'advanced'" x-transition.opacity x-cloak class="space-y-6">
                        <article class="admin-panel p-6 sm:p-7">
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Traduction</p>
                            <h2 class="mt-2 text-xl font-bold text-slate-950">Version anglaise optionnelle</h2>
                            <p class="mt-2 text-sm text-slate-600">Laisse vide si le français suffit: le back reprendra automatiquement la version FR.</p>

                            <div class="mt-6 grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="title_en" class="mb-2 block text-sm font-semibold text-slate-700">Titre EN</label>
                                    <input type="text" name="title_en" id="title_en" value="{{ $titleEnValue }}" placeholder="Optionnel" class="w-full px-4 py-3 text-sm">
                                </div>
                                <div>
                                    <label for="tagline_en" class="mb-2 block text-sm font-semibold text-slate-700">Tagline EN</label>
                                    <input type="text" name="tagline_en" id="tagline_en" value="{{ $taglineEnValue }}" class="w-full px-4 py-3 text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="description_en" class="mb-2 block text-sm font-semibold text-slate-700">Description EN</label>
                                    <textarea name="description_en" id="description_en" rows="5" class="w-full px-4 py-3 text-sm">{{ $descriptionEnValue }}</textarea>
                                </div>
                            </div>
                        </article>

                        <article class="admin-panel p-6 sm:p-7">
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Informations complémentaires</p>
                            <h2 class="mt-2 text-xl font-bold text-slate-950">Programme et conditions</h2>

                            <div class="mt-6 grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="program_fr" class="mb-2 block text-sm font-semibold text-slate-700">Programme FR</label>
                                    <textarea name="program_fr" id="program_fr" rows="4" class="w-full px-4 py-3 text-sm">{{ old('program_fr', $event->program_fr) }}</textarea>
                                </div>
                                <div>
                                    <label for="program_en" class="mb-2 block text-sm font-semibold text-slate-700">Programme EN</label>
                                    <textarea name="program_en" id="program_en" rows="4" class="w-full px-4 py-3 text-sm">{{ $programEnValue }}</textarea>
                                </div>
                                <div>
                                    <label for="conditions_fr" class="mb-2 block text-sm font-semibold text-slate-700">Conditions FR</label>
                                    <textarea name="conditions_fr" id="conditions_fr" rows="4" class="w-full px-4 py-3 text-sm">{{ old('conditions_fr', $event->conditions_fr) }}</textarea>
                                </div>
                                <div>
                                    <label for="conditions_en" class="mb-2 block text-sm font-semibold text-slate-700">Conditions EN</label>
                                    <textarea name="conditions_en" id="conditions_en" rows="4" class="w-full px-4 py-3 text-sm">{{ $conditionsEnValue }}</textarea>
                                </div>
                            </div>
                        </article>

                        <article class="admin-panel p-6 sm:p-7">
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">SEO</p>
                            <h2 class="mt-2 text-xl font-bold text-slate-950">Métadonnées</h2>

                            <div class="mt-6 grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="meta_title_fr" class="mb-2 block text-sm font-semibold text-slate-700">Méta titre FR</label>
                                    <input type="text" name="meta_title_fr" id="meta_title_fr" value="{{ old('meta_title_fr', $event->meta_title_fr) }}" class="w-full px-4 py-3 text-sm">
                                </div>
                                <div>
                                    <label for="meta_title_en" class="mb-2 block text-sm font-semibold text-slate-700">Méta titre EN</label>
                                    <input type="text" name="meta_title_en" id="meta_title_en" value="{{ $metaTitleEnValue }}" class="w-full px-4 py-3 text-sm">
                                </div>
                                <div>
                                    <label for="meta_description_fr" class="mb-2 block text-sm font-semibold text-slate-700">Méta description FR</label>
                                    <textarea name="meta_description_fr" id="meta_description_fr" rows="3" class="w-full px-4 py-3 text-sm">{{ old('meta_description_fr', $event->meta_description_fr) }}</textarea>
                                </div>
                                <div>
                                    <label for="meta_description_en" class="mb-2 block text-sm font-semibold text-slate-700">Méta description EN</label>
                                    <textarea name="meta_description_en" id="meta_description_en" rows="3" class="w-full px-4 py-3 text-sm">{{ $metaDescriptionEnValue }}</textarea>
                                </div>
                            </div>
                        </article>
                    </section>

                    <div class="flex flex-col gap-4 rounded-[1.75rem] border border-[#eadfce] bg-white/80 px-6 py-5 shadow-[0_12px_35px_rgba(38,24,59,0.06)] sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Validation finale</p>
                            <p class="mt-1 text-sm text-slate-600">
                                Tu peux enregistrer à n’importe quelle étape. Les blocs avancés ne sont pas obligatoires.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.events.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                                Annuler
                            </a>
                            <button type="submit" class="admin-btn-primary px-6 py-3 text-sm">
                                <i class="fas fa-floppy-disk"></i>
                                {{ $isEdit ? 'Enregistrer les modifications' : 'Créer l’événement' }}
                            </button>
                        </div>
                    </div>
                </div>

                <aside class="space-y-6 xl:sticky xl:top-24 self-start">
                    <article class="admin-panel p-6">
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Publication</p>
                        <h2 class="mt-2 text-xl font-bold text-slate-950">{{ $isActiveChecked ? 'Publié' : 'Brouillon' }}</h2>
                        <p class="mt-2 text-sm text-slate-600">Les deux réglages principaux restent ici, toujours visibles.</p>

                        <div class="mt-5 space-y-3">
                            <label class="flex items-center gap-3 rounded-2xl border border-[#eadfce] bg-slate-50 px-4 py-4">
                                <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-[var(--admin-brand)] focus:ring-[var(--admin-brand)]" {{ $isActiveChecked ? 'checked' : '' }}>
                                <span>
                                    <span class="block text-sm font-semibold text-slate-900">Publier sur le site</span>
                                    <span class="block text-xs text-slate-500">Décoche pour garder ce brouillon en interne.</span>
                                </span>
                            </label>

                            <label class="flex items-center gap-3 rounded-2xl border border-[#eadfce] bg-slate-50 px-4 py-4">
                                <input type="checkbox" name="is_featured" value="1" class="h-4 w-4 rounded border-slate-300 text-[var(--admin-brand)] focus:ring-[var(--admin-brand)]" {{ $isFeaturedChecked ? 'checked' : '' }}>
                                <span>
                                    <span class="block text-sm font-semibold text-slate-900">Mettre en avant</span>
                                    <span class="block text-xs text-slate-500">Utilisé pour les mises en avant home et marketing.</span>
                                </span>
                            </label>
                        </div>
                    </article>

                    <article class="admin-panel p-6">
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Image</p>
                        <h2 class="mt-2 text-xl font-bold text-slate-950">Visuel principal</h2>

                        <div class="mt-5 overflow-hidden rounded-[1.35rem] border border-[#eadfce] bg-slate-50">
                            @if ($coverUrl)
                                <img id="event-image-preview" src="{{ $coverUrl }}" alt="Image actuelle" class="h-44 w-full object-cover">
                            @else
                                <div id="event-image-empty" class="flex h-44 items-center justify-center bg-[linear-gradient(135deg,#f7f2ff,#f8ede0)] text-center text-sm font-semibold text-slate-500">
                                    Aperçu image
                                </div>
                                <img id="event-image-preview" src="" alt="Aperçu de l’image" class="hidden h-44 w-full object-cover">
                            @endif
                        </div>

                        <div class="mt-4">
                            <label for="image" class="mb-2 block text-sm font-semibold text-slate-700">Téléverser une image</label>
                            <input type="file" name="image" id="image" accept="image/*" class="w-full px-4 py-3 text-sm">
                            <p class="mt-2 text-xs text-slate-500">Format image, 5 MB max.</p>
                        </div>

                        @if ($isEdit && $coverUrl)
                            <label class="mt-4 inline-flex items-center gap-3 text-sm text-red-700">
                                <input type="checkbox" name="remove_image" value="1" class="h-4 w-4 rounded border-slate-300 text-red-600 focus:ring-red-500" {{ old('remove_image') ? 'checked' : '' }}>
                                Supprimer l’image existante
                            </label>
                        @endif
                    </article>

                    <article class="admin-panel p-6">
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Résumé</p>
                        <h2 class="mt-2 text-xl font-bold text-slate-950">Ce que tu es en train d’éditer</h2>

                        <div class="mt-5 space-y-4 text-sm">
                            <div class="rounded-2xl bg-slate-50 px-4 py-4">
                                <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Titre</p>
                                <p class="mt-2 font-semibold text-slate-900">{{ $previewTitle }}</p>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                                <div class="rounded-2xl bg-slate-50 px-4 py-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Date</p>
                                    <p class="mt-2 font-semibold text-slate-900">{{ $previewDate ?: 'À renseigner' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $previewTime ?: 'Heure non définie' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 px-4 py-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Lieu</p>
                                    <p class="mt-2 font-semibold text-slate-900">{{ $previewVenue }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $previewCity }} · {{ $previewCountry }}</p>
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                                <div class="rounded-2xl bg-slate-50 px-4 py-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Prix</p>
                                    <p class="mt-2 font-semibold text-slate-900">
                                        {{ $previewMinPrice !== null && $previewMinPrice !== '' ? number_format((float) $previewMinPrice, 0, ',', ' ') . ' XOF' : 'À définir' }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $previewMaxPrice !== null && $previewMaxPrice !== '' ? 'Max ' . number_format((float) $previewMaxPrice, 0, ',', ' ') . ' XOF' : 'Prix max non défini' }}
                                    </p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 px-4 py-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Capacité</p>
                                    <p class="mt-2 font-semibold text-slate-900">{{ $previewSeats ?: 'À définir' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">Places globales affichées</p>
                                </div>
                            </div>
                        </div>
                    </article>
                </aside>
            </div>
        </form>
    </div>

    <template id="package-template">
        <div class="package-item rounded-[1.5rem] border border-[#eadfce] bg-slate-50 p-5" data-index="__INDEX__">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-base font-bold text-slate-950" data-block-title>Package __NUMBER__</p>
                    <p class="text-sm text-slate-500">Bloc de vente visible sur la page client.</p>
                </div>
                <button type="button" class="remove-package inline-flex items-center gap-2 rounded-full bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                    <i class="fas fa-trash"></i>
                    Supprimer
                </button>
            </div>

            <input type="hidden" name="packages[__INDEX__][id]" value="">

            <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <div class="xl:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nom FR *</label>
                    <input type="text" name="packages[__INDEX__][package_name_fr]" class="w-full px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Prix *</label>
                    <input type="number" min="0" step="0.01" name="packages[__INDEX__][price]" class="w-full px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Disponible</label>
                    <input type="number" min="0" name="packages[__INDEX__][available_quantity]" class="w-full px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Max / commande</label>
                    <input type="number" min="1" name="packages[__INDEX__][max_per_order]" value="6" class="w-full px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Quantité min</label>
                    <input type="number" min="1" name="packages[__INDEX__][minimum_quantity]" value="1" class="w-full px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Devise</label>
                    <select name="packages[__INDEX__][currency]" class="w-full px-4 py-3 text-sm">
                        <option value="XOF">XOF</option>
                        <option value="EUR">EUR</option>
                        <option value="USD">USD</option>
                    </select>
                </div>
            </div>

            <details class="mt-4 rounded-[1.2rem] border border-[#eadfce] bg-white px-4 py-4">
                <summary class="cursor-pointer text-sm font-semibold text-slate-800">Texte et options</summary>

                <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="xl:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nom EN</label>
                        <input type="text" name="packages[__INDEX__][package_name_en]" class="w-full px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Code</label>
                        <input type="text" name="packages[__INDEX__][package_code]" class="w-full px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Ordre</label>
                        <input type="number" min="0" name="packages[__INDEX__][sort_order]" value="0" class="w-full px-4 py-3 text-sm">
                    </div>
                    <div class="md:col-span-2 xl:col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Détails lieu</label>
                        <input type="text" name="packages[__INDEX__][venue_details_fr]" class="w-full px-4 py-3 text-sm">
                    </div>
                    <div class="md:col-span-2 xl:col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Description FR</label>
                        <textarea name="packages[__INDEX__][description_fr]" rows="3" class="w-full px-4 py-3 text-sm"></textarea>
                    </div>
                    <div class="md:col-span-2 xl:col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Inclus FR</label>
                        <textarea name="packages[__INDEX__][description_included_fr]" rows="3" class="w-full px-4 py-3 text-sm"></textarea>
                    </div>
                </div>

                <label class="mt-4 inline-flex items-center gap-3 text-sm text-slate-700">
                    <input type="checkbox" name="packages[__INDEX__][is_active]" value="1" checked class="h-4 w-4 rounded border-slate-300 text-[var(--admin-brand)] focus:ring-[var(--admin-brand)]">
                    Package actif
                </label>
            </details>
        </div>
    </template>

    <template id="seat-zone-template">
        <div class="seat-zone-item rounded-[1.5rem] border border-[#eadfce] bg-slate-50 p-5" data-index="__INDEX__">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-base font-bold text-slate-950" data-block-title>Zone __NUMBER__</p>
                    <p class="text-sm text-slate-500">Prix et capacité avant le détail.</p>
                </div>
                <button type="button" class="remove-seat-zone inline-flex items-center gap-2 rounded-full bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                    <i class="fas fa-trash"></i>
                    Supprimer
                </button>
            </div>

            <input type="hidden" name="seat_zones[__INDEX__][id]" value="">

            <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="xl:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nom FR *</label>
                    <input type="text" name="seat_zones[__INDEX__][zone_name_fr]" class="w-full px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Prix *</label>
                    <input type="number" min="0" step="0.01" name="seat_zones[__INDEX__][price]" class="w-full px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Capacité *</label>
                    <input type="number" min="0" name="seat_zones[__INDEX__][total_seats]" class="w-full px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Places dispo</label>
                    <input type="number" min="0" name="seat_zones[__INDEX__][available_seats]" class="w-full px-4 py-3 text-sm">
                </div>
            </div>

            <details class="mt-4 rounded-[1.2rem] border border-[#eadfce] bg-white px-4 py-4">
                <summary class="cursor-pointer text-sm font-semibold text-slate-800">Texte et options</summary>

                <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="xl:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nom EN</label>
                        <input type="text" name="seat_zones[__INDEX__][zone_name_en]" class="w-full px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Code zone</label>
                        <input type="text" name="seat_zones[__INDEX__][zone_code]" class="w-full px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Type</label>
                        <input type="text" name="seat_zones[__INDEX__][zone_type]" value="standard" class="w-full px-4 py-3 text-sm">
                    </div>
                    <div class="md:col-span-2 xl:col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Description FR</label>
                        <textarea name="seat_zones[__INDEX__][description_fr]" rows="3" class="w-full px-4 py-3 text-sm"></textarea>
                    </div>
                </div>

                <label class="mt-4 inline-flex items-center gap-3 text-sm text-slate-700">
                    <input type="checkbox" name="seat_zones[__INDEX__][is_active]" value="1" checked class="h-4 w-4 rounded border-slate-300 text-[var(--admin-brand)] focus:ring-[var(--admin-brand)]">
                    Zone active
                </label>
            </details>
        </div>
    </template>
@endsection

@push('scripts')
    <script>
        (() => {
            const packagesContainer = document.getElementById('packages-container');
            const packageTemplate = document.getElementById('package-template');
            const addPackageButton = document.getElementById('add-package');

            const seatZonesContainer = document.getElementById('seat-zones-container');
            const seatZoneTemplate = document.getElementById('seat-zone-template');
            const addSeatZoneButton = document.getElementById('add-seat-zone');

            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('event-image-preview');
            const imageEmpty = document.getElementById('event-image-empty');

            function createBlock(template, index) {
                return template.innerHTML
                    .replaceAll('__INDEX__', index)
                    .replaceAll('__NUMBER__', index + 1);
            }

            function getNextIndex(container) {
                const indexes = Array.from(container.querySelectorAll('[data-index]'))
                    .map((item) => Number(item.dataset.index))
                    .filter((value) => Number.isFinite(value));

                return indexes.length ? Math.max(...indexes) + 1 : 0;
            }

            function appendBlock(container, template) {
                const index = getNextIndex(container);
                container.insertAdjacentHTML('beforeend', createBlock(template, index));
            }

            function refreshTitles(container, selector, label) {
                container.querySelectorAll(selector).forEach((item, index) => {
                    const title = item.querySelector('[data-block-title]');
                    if (title) {
                        title.textContent = `${label} ${index + 1}`;
                    }
                });
            }

            addPackageButton?.addEventListener('click', () => {
                appendBlock(packagesContainer, packageTemplate);
            });

            addSeatZoneButton?.addEventListener('click', () => {
                appendBlock(seatZonesContainer, seatZoneTemplate);
            });

            packagesContainer?.addEventListener('click', (event) => {
                const button = event.target.closest('.remove-package');
                if (!button) {
                    return;
                }

                const items = packagesContainer.querySelectorAll('.package-item');
                if (items.length <= 1) {
                    return;
                }

                button.closest('.package-item')?.remove();
                refreshTitles(packagesContainer, '.package-item', 'Package');
            });

            seatZonesContainer?.addEventListener('click', (event) => {
                const button = event.target.closest('.remove-seat-zone');
                if (!button) {
                    return;
                }

                const items = seatZonesContainer.querySelectorAll('.seat-zone-item');
                if (items.length <= 1) {
                    return;
                }

                button.closest('.seat-zone-item')?.remove();
                refreshTitles(seatZonesContainer, '.seat-zone-item', 'Zone');
            });

            imageInput?.addEventListener('change', (event) => {
                const file = event.target.files?.[0];
                if (!file || !imagePreview) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = ({ target }) => {
                    imagePreview.src = target?.result ?? '';
                    imagePreview.classList.remove('hidden');
                    imageEmpty?.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            });
        })();
    </script>
@endpush
