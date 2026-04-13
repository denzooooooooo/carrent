@php
    $isEdit = $package->exists;
    $includedServicesFr = old('included_services_fr', $package->included_services_fr ?? ['']);
    $includedServicesEn = old('included_services_en', $package->included_services_en ?? ['']);
    $excludedServicesFr = old('excluded_services_fr', $package->excluded_services_fr ?? ['']);
    $excludedServicesEn = old('excluded_services_en', $package->excluded_services_en ?? ['']);
    $itineraryFr = old('itinerary_fr', $package->itinerary_fr ?? ['']);
    $itineraryEn = old('itinerary_en', $package->itinerary_en ?? ['']);
    $currentAvatarUrl = $isEdit ? $package->getFirstMediaUrl('avatar', 'normal') : null;
    $galleryMedia = $isEdit ? $package->getMedia('gallery') : collect();
    $currency = old('currency', $package->currency ?? 'XOF');
@endphp

<section class="admin-panel p-6 sm:p-7">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Structure offre</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-950">Informations principales</h2>
            <p class="mt-3 text-sm leading-7 text-slate-600">
                Renseigne la destination, le type d’offre, les textes FR/EN et les métadonnées essentielles du package.
            </p>
        </div>
        <div class="rounded-[1.25rem] border border-[#eadfce] bg-slate-50 px-5 py-4 text-sm text-slate-600">
            <p class="font-semibold text-slate-900">{{ $isEdit ? 'Édition en cours' : 'Nouveau package' }}</p>
            <p class="mt-1">{{ $isEdit ? 'Le slug et les médias existants sont conservés jusqu’à modification.' : 'Le slug sera généré automatiquement à partir du titre français.' }}</p>
        </div>
    </div>

    <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div>
            <label for="category_id" class="mb-2 block text-sm font-semibold text-slate-700">Catégorie *</label>
            <select id="category_id" name="category_id" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                <option value="">Sélectionner</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('category_id', $package->category_id) === (string) $category->id)>
                        {{ $category->name_fr }}
                    </option>
                @endforeach
            </select>
            @error('category_id')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="package_type" class="mb-2 block text-sm font-semibold text-slate-700">Type *</label>
            <select id="package_type" name="package_type" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                <option value="">Sélectionner</option>
                @foreach ($packageTypes as $key => $label)
                    <option value="{{ $key }}" @selected(old('package_type', $package->package_type) === $key)>{{ $label }}</option>
                @endforeach
            </select>
            @error('package_type')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="xl:col-span-2">
            <label for="destination" class="mb-2 block text-sm font-semibold text-slate-700">Destination *</label>
            <input id="destination" name="destination" type="text" value="{{ old('destination', $package->destination) }}"
                class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm" placeholder="Ex: Djeddah, Arabie saoudite">
            @error('destination')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="xl:col-span-2">
            <label for="title_fr" class="mb-2 block text-sm font-semibold text-slate-700">Titre FR *</label>
            <input id="title_fr" name="title_fr" type="text" value="{{ old('title_fr', $package->title_fr) }}"
                class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm" placeholder="Ex: Grand Prix de Monaco, séjour signature">
            @error('title_fr')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="xl:col-span-2">
            <label for="title_en" class="mb-2 block text-sm font-semibold text-slate-700">Titre EN</label>
            <input id="title_en" name="title_en" type="text" value="{{ old('title_en', $package->title_en) }}"
                class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm" placeholder="Optionnel, repris du FR si vide">
            @error('title_en')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="xl:col-span-2">
            <label for="departure_city" class="mb-2 block text-sm font-semibold text-slate-700">Ville de départ</label>
            <input id="departure_city" name="departure_city" type="text" value="{{ old('departure_city', $package->departure_city) }}"
                class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm" placeholder="Ex: Abidjan">
            @error('departure_city')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="event_date_start" class="mb-2 block text-sm font-semibold text-slate-700">Date début</label>
            <input id="event_date_start" name="event_date_start" type="date"
                value="{{ old('event_date_start', optional($package->event_date_start)->format('Y-m-d')) }}"
                class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
            @error('event_date_start')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="event_date_end" class="mb-2 block text-sm font-semibold text-slate-700">Date fin</label>
            <input id="event_date_end" name="event_date_end" type="date"
                value="{{ old('event_date_end', optional($package->event_date_end)->format('Y-m-d')) }}"
                class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
            @error('event_date_end')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="md:col-span-2 xl:col-span-4">
            <label for="description_fr" class="mb-2 block text-sm font-semibold text-slate-700">Description FR</label>
            <textarea id="description_fr" name="description_fr" rows="5"
                class="w-full rounded-[1.4rem] border border-[#ddcfbb] bg-white px-4 py-3 text-sm">{{ old('description_fr', $package->description_fr) }}</textarea>
            @error('description_fr')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="md:col-span-2 xl:col-span-4">
            <label for="description_en" class="mb-2 block text-sm font-semibold text-slate-700">Description EN</label>
            <textarea id="description_en" name="description_en" rows="4"
                class="w-full rounded-[1.4rem] border border-[#ddcfbb] bg-white px-4 py-3 text-sm">{{ old('description_en', $package->description_en) }}</textarea>
            @error('description_en')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>
</section>

<section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
    <article class="admin-panel p-6 sm:p-7">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Pricing</p>
        <h2 class="mt-2 text-2xl font-bold text-slate-950">Tarifs et capacité</h2>

        <div class="mt-6 grid gap-5 md:grid-cols-2">
            <div>
                <label for="price" class="mb-2 block text-sm font-semibold text-slate-700">Prix principal *</label>
                <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $package->price) }}"
                    class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                @error('price')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="discount_price" class="mb-2 block text-sm font-semibold text-slate-700">Prix promo</label>
                <input id="discount_price" name="discount_price" type="number" min="0" step="0.01" value="{{ old('discount_price', $package->discount_price) }}"
                    class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                @error('discount_price')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="currency" class="mb-2 block text-sm font-semibold text-slate-700">Devise</label>
                <select id="currency" name="currency" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                    @foreach (['XOF' => 'XOF (FCFA)', 'EUR' => 'EUR (€)', 'USD' => 'USD ($)'] as $value => $label)
                        <option value="{{ $value }}" @selected($currency === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('currency')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="duration" class="mb-2 block text-sm font-semibold text-slate-700">Durée (jours) *</label>
                <input id="duration" name="duration" type="number" min="1" value="{{ old('duration', $package->duration ?? 1) }}"
                    class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                @error('duration')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="duration_text_fr" class="mb-2 block text-sm font-semibold text-slate-700">Durée lisible FR</label>
                <input id="duration_text_fr" name="duration_text_fr" type="text" value="{{ old('duration_text_fr', $package->duration_text_fr) }}"
                    class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm" placeholder="Ex: 4 jours / 3 nuits">
                @error('duration_text_fr')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="duration_text_en" class="mb-2 block text-sm font-semibold text-slate-700">Durée lisible EN</label>
                <input id="duration_text_en" name="duration_text_en" type="text" value="{{ old('duration_text_en', $package->duration_text_en) }}"
                    class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm" placeholder="Optional">
                @error('duration_text_en')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="min_participants" class="mb-2 block text-sm font-semibold text-slate-700">Participants min *</label>
                <input id="min_participants" name="min_participants" type="number" min="1" value="{{ old('min_participants', $package->min_participants ?? 1) }}"
                    class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                @error('min_participants')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="max_participants" class="mb-2 block text-sm font-semibold text-slate-700">Participants max *</label>
                <input id="max_participants" name="max_participants" type="number" min="1" value="{{ old('max_participants', $package->max_participants ?? 1) }}"
                    class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                @error('max_participants')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </article>

    <article class="admin-panel p-6 sm:p-7">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Publication</p>
        <h2 class="mt-2 text-2xl font-bold text-slate-950">Visibilité et SEO</h2>

        <div class="mt-6 space-y-5">
            <div class="grid gap-4 md:grid-cols-2">
                <label class="flex items-center gap-3 rounded-2xl border border-[#eadfce] bg-slate-50 px-4 py-4 text-sm text-slate-700">
                    <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-[var(--admin-brand)] focus:ring-[var(--admin-brand)]" {{ old('is_active', $package->is_active ?? true) ? 'checked' : '' }}>
                    <span>
                        <span class="block font-semibold text-slate-900">Package actif</span>
                        <span class="block text-xs text-slate-500">Visible côté client.</span>
                    </span>
                </label>

                <label class="flex items-center gap-3 rounded-2xl border border-[#eadfce] bg-slate-50 px-4 py-4 text-sm text-slate-700">
                    <input type="checkbox" name="is_featured" value="1" class="h-4 w-4 rounded border-slate-300 text-[var(--admin-brand)] focus:ring-[var(--admin-brand)]" {{ old('is_featured', $package->is_featured) ? 'checked' : '' }}>
                    <span>
                        <span class="block font-semibold text-slate-900">Mise en avant</span>
                        <span class="block text-xs text-slate-500">Badge vedette dans le catalogue.</span>
                    </span>
                </label>
            </div>

            <div>
                <label for="video_url" class="mb-2 block text-sm font-semibold text-slate-700">URL vidéo</label>
                <input id="video_url" name="video_url" type="url" value="{{ old('video_url', $package->video_url) }}"
                    class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm" placeholder="https://...">
                @error('video_url')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="meta_title_fr" class="mb-2 block text-sm font-semibold text-slate-700">Meta title FR</label>
                    <input id="meta_title_fr" name="meta_title_fr" type="text" value="{{ old('meta_title_fr', $package->meta_title_fr) }}"
                        class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                </div>
                <div>
                    <label for="meta_title_en" class="mb-2 block text-sm font-semibold text-slate-700">Meta title EN</label>
                    <input id="meta_title_en" name="meta_title_en" type="text" value="{{ old('meta_title_en', $package->meta_title_en) }}"
                        class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                </div>
                <div>
                    <label for="meta_description_fr" class="mb-2 block text-sm font-semibold text-slate-700">Meta description FR</label>
                    <textarea id="meta_description_fr" name="meta_description_fr" rows="3" class="w-full rounded-[1.25rem] border border-[#ddcfbb] bg-white px-4 py-3 text-sm">{{ old('meta_description_fr', $package->meta_description_fr) }}</textarea>
                </div>
                <div>
                    <label for="meta_description_en" class="mb-2 block text-sm font-semibold text-slate-700">Meta description EN</label>
                    <textarea id="meta_description_en" name="meta_description_en" rows="3" class="w-full rounded-[1.25rem] border border-[#ddcfbb] bg-white px-4 py-3 text-sm">{{ old('meta_description_en', $package->meta_description_en) }}</textarea>
                </div>
            </div>
        </div>
    </article>
</section>

<section class="grid gap-6 xl:grid-cols-2">
    @php
        $repeaters = [
            'included_services_fr' => ['label' => 'Services inclus FR', 'items' => $includedServicesFr],
            'included_services_en' => ['label' => 'Services inclus EN', 'items' => $includedServicesEn],
            'excluded_services_fr' => ['label' => 'Services exclus FR', 'items' => $excludedServicesFr],
            'excluded_services_en' => ['label' => 'Services exclus EN', 'items' => $excludedServicesEn],
            'itinerary_fr' => ['label' => 'Étapes / itinéraire FR', 'items' => $itineraryFr],
            'itinerary_en' => ['label' => 'Étapes / itinéraire EN', 'items' => $itineraryEn],
        ];
    @endphp

    @foreach ($repeaters as $field => $config)
        <article class="admin-panel p-6 sm:p-7">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Contenu</p>
                    <h2 class="mt-2 text-xl font-bold text-slate-950">{{ $config['label'] }}</h2>
                </div>
                <button type="button" class="admin-btn-ghost px-4 py-2 text-sm" data-add-array="{{ $field }}">
                    <i class="fas fa-plus"></i>
                    Ajouter
                </button>
            </div>

            <div class="mt-5 space-y-3" data-array-container="{{ $field }}">
                @foreach ($config['items'] as $item)
                    <div class="flex items-center gap-3">
                        <input type="text" name="{{ $field }}[]" value="{{ is_array($item) ? ($item['title'] ?? $item['description'] ?? '') : $item }}"
                            class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm"
                            placeholder="Saisir une ligne">
                        <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-red-50 text-red-700 transition hover:bg-red-100" data-remove-array-item>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        </article>
    @endforeach
</section>

<section class="admin-panel p-6 sm:p-7">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Médias</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-950">Image principale et galerie</h2>
            <p class="mt-3 text-sm leading-7 text-slate-600">
                Charge une image principale nette, puis ajoute une galerie si le package mérite plusieurs visuels.
            </p>
        </div>
        @if ($currentAvatarUrl)
            <img src="{{ $currentAvatarUrl }}" alt="{{ $package->title_fr }}" class="h-24 w-24 rounded-[1.25rem] object-cover shadow-md">
        @endif
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)]">
        <div class="space-y-5">
            <div>
                <label for="avatar" class="mb-2 block text-sm font-semibold text-slate-700">Image principale</label>
                <input id="avatar" name="avatar" type="file" accept="image/*"
                    class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                <p class="mt-2 text-xs text-slate-500">JPG, PNG ou WEBP. 2 Mo max.</p>
                @error('avatar')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="gallery" class="mb-2 block text-sm font-semibold text-slate-700">Galerie</label>
                <input id="gallery" name="gallery[]" type="file" accept="image/*" multiple
                    class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                <p class="mt-2 text-xs text-slate-500">Ajoute plusieurs visuels. Les images déjà présentes peuvent être retirées individuellement.</p>
                @error('gallery.*')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <p class="text-sm font-semibold text-slate-700">Galerie actuelle</p>
            <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($galleryMedia as $media)
                    <div class="group relative overflow-hidden rounded-[1.25rem] border border-[#eadfce] bg-slate-50">
                        <img src="{{ $media->getUrl('small') }}" alt="Galerie" class="h-28 w-full object-cover">
                        <button type="button" data-delete-gallery-image="{{ $media->id }}"
                            data-delete-gallery-url="{{ route('admin.packages.delete-gallery-image', [$package, $media->id]) }}"
                            class="absolute right-3 top-3 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-red-700 opacity-0 shadow-md transition group-hover:opacity-100">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @empty
                    <div class="rounded-[1.25rem] border border-dashed border-[#ddcfbb] px-5 py-8 text-sm text-slate-500 sm:col-span-2 xl:col-span-3">
                        Aucune image de galerie pour le moment.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<div class="flex flex-col gap-4 rounded-[1.75rem] border border-[#eadfce] bg-white/80 px-6 py-5 shadow-[0_12px_35px_rgba(38,24,59,0.06)] sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm font-semibold text-slate-900">Validation finale</p>
        <p class="mt-1 text-sm text-slate-600">
            Vérifie les prix, la visibilité, les dates et les contenus avant d’enregistrer.
        </p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.packages.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">Annuler</a>
        <button type="submit" class="admin-btn-primary px-6 py-3 text-sm">
            <i class="fas fa-floppy-disk"></i>
            {{ $isEdit ? 'Enregistrer les modifications' : 'Créer le package' }}
        </button>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            document.querySelectorAll('[data-add-array]').forEach((button) => {
                button.addEventListener('click', () => {
                    const field = button.dataset.addArray;
                    const container = document.querySelector(`[data-array-container="${field}"]`);

                    if (!container) {
                        return;
                    }

                    const row = document.createElement('div');
                    row.className = 'flex items-center gap-3';
                    row.innerHTML = `
                        <input type="text" name="${field}[]" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm" placeholder="Saisir une ligne">
                        <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-red-50 text-red-700 transition hover:bg-red-100" data-remove-array-item>
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    container.appendChild(row);
                });
            });

            document.addEventListener('click', (event) => {
                const removeButton = event.target.closest('[data-remove-array-item]');

                if (removeButton) {
                    const row = removeButton.closest('.flex.items-center.gap-3');
                    row?.remove();
                    return;
                }

                const deleteButton = event.target.closest('[data-delete-gallery-image]');

                if (!deleteButton || !csrfToken) {
                    return;
                }

                if (!confirm('Supprimer cette image de galerie ?')) {
                    return;
                }

                fetch(deleteButton.dataset.deleteGalleryUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                })
                    .then((response) => response.json())
                    .then((payload) => {
                        if (!payload.success) {
                            throw new Error(payload.message || 'Suppression impossible');
                        }

                        deleteButton.closest('.group')?.remove();
                    })
                    .catch(() => {
                        alert('La suppression de l’image a échoué.');
                    });
            });
        });
    </script>
@endpush
