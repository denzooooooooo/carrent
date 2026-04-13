@php
    $isEdit = $location->exists;
    $featureValues = old('features', $location->features ?? ['']);
    $currentImageUrl = $location->image_url;
    $categoryOptions = [
        'terrestre' => 'Terrestre',
        'aérien' => 'Aérien',
        'nautique' => 'Nautique',
    ];
@endphp

<section class="admin-panel p-6 sm:p-7">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Mobilité premium</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-950">Informations principales</h2>
            <p class="mt-3 text-sm leading-7 text-slate-600">
                Gère le nom commercial, la catégorie de mobilité, la capacité et le tarif journalier de cette offre.
            </p>
        </div>
        @if ($currentImageUrl)
            <img src="{{ $currentImageUrl }}" alt="{{ $location->name }}" class="h-24 w-24 rounded-[1.25rem] object-cover shadow-md">
        @endif
    </div>

    <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="xl:col-span-2">
            <label for="name_fr" class="mb-2 block text-sm font-semibold text-slate-700">Nom FR *</label>
            <input id="name_fr" name="name_fr" type="text" value="{{ old('name_fr', $location->name_fr) }}"
                class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm" placeholder="Ex: Mercedes Classe S avec chauffeur">
            @error('name_fr')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="xl:col-span-2">
            <label for="name_en" class="mb-2 block text-sm font-semibold text-slate-700">Nom EN *</label>
            <input id="name_en" name="name_en" type="text" value="{{ old('name_en', $location->name_en) }}"
                class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm" placeholder="Ex: Mercedes S-Class with driver">
            @error('name_en')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="category" class="mb-2 block text-sm font-semibold text-slate-700">Catégorie *</label>
            <select id="category" name="category" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                <option value="">Sélectionner</option>
                @foreach ($categoryOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('category', $location->category) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('category')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="type" class="mb-2 block text-sm font-semibold text-slate-700">Type *</label>
            <input id="type" name="type" type="text" value="{{ old('type', $location->type) }}"
                class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm" placeholder="Ex: Voiture avec chauffeur">
            @error('type')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="price_per_day" class="mb-2 block text-sm font-semibold text-slate-700">Prix / jour *</label>
            <input id="price_per_day" name="price_per_day" type="number" min="0" step="0.01" value="{{ old('price_per_day', $location->price_per_day) }}"
                class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
            @error('price_per_day')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="capacity" class="mb-2 block text-sm font-semibold text-slate-700">Capacité *</label>
            <input id="capacity" name="capacity" type="number" min="1" value="{{ old('capacity', $location->capacity ?? 1) }}"
                class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
            @error('capacity')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="md:col-span-2 xl:col-span-4">
            <label for="description_fr" class="mb-2 block text-sm font-semibold text-slate-700">Description FR</label>
            <textarea id="description_fr" name="description_fr" rows="5" class="w-full rounded-[1.4rem] border border-[#ddcfbb] bg-white px-4 py-3 text-sm">{{ old('description_fr', $location->description_fr) }}</textarea>
            @error('description_fr')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="md:col-span-2 xl:col-span-4">
            <label for="description_en" class="mb-2 block text-sm font-semibold text-slate-700">Description EN</label>
            <textarea id="description_en" name="description_en" rows="4" class="w-full rounded-[1.4rem] border border-[#ddcfbb] bg-white px-4 py-3 text-sm">{{ old('description_en', $location->description_en) }}</textarea>
            @error('description_en')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>
</section>

<section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
    <article class="admin-panel p-6 sm:p-7">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Médias</p>
        <h2 class="mt-2 text-2xl font-bold text-slate-950">Image principale</h2>

        <div class="mt-6 space-y-5">
            <div>
                <label for="image" class="mb-2 block text-sm font-semibold text-slate-700">Image</label>
                <input id="image" name="image" type="file" accept="image/*"
                    class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm">
                <p class="mt-2 text-xs text-slate-500">JPG, PNG ou GIF. 2 Mo max.</p>
                @error('image')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            @if ($currentImageUrl)
                <div class="rounded-[1.5rem] border border-[#eadfce] bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-900">Image actuelle</p>
                    <img src="{{ $currentImageUrl }}" alt="{{ $location->name }}" class="mt-3 h-56 w-full rounded-[1.2rem] object-cover">
                </div>
            @endif
        </div>
    </article>

    <article class="admin-panel p-6 sm:p-7">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Atouts</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-950">Caractéristiques</h2>
            </div>
            <button type="button" class="admin-btn-ghost px-4 py-2 text-sm" data-add-location-feature>
                <i class="fas fa-plus"></i>
                Ajouter
            </button>
        </div>

        <div class="mt-5 space-y-3" data-location-features>
            @foreach ($featureValues as $feature)
                <div class="flex items-center gap-3">
                    <input type="text" name="features[]" value="{{ $feature }}"
                        class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm"
                        placeholder="Ex: Chauffeur inclus, Wi-Fi, bagagerie">
                    <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-red-50 text-red-700 transition hover:bg-red-100" data-remove-location-feature>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endforeach
        </div>

        <div class="mt-6 rounded-[1.5rem] border border-[#eadfce] bg-slate-50 px-4 py-4">
            <label class="flex items-center gap-3 text-sm text-slate-700">
                <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-[var(--admin-brand)] focus:ring-[var(--admin-brand)]" {{ old('is_active', $location->is_active ?? true) ? 'checked' : '' }}>
                <span>
                    <span class="block font-semibold text-slate-900">Location active</span>
                    <span class="block text-xs text-slate-500">Visible et réservable côté client.</span>
                </span>
            </label>
        </div>
    </article>
</section>

<div class="flex flex-col gap-4 rounded-[1.75rem] border border-[#eadfce] bg-white/80 px-6 py-5 shadow-[0_12px_35px_rgba(38,24,59,0.06)] sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm font-semibold text-slate-900">Validation finale</p>
        <p class="mt-1 text-sm text-slate-600">
            Vérifie la catégorie, le tarif et les atouts avant d’enregistrer.
        </p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.locations.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">Annuler</a>
        <button type="submit" class="admin-btn-primary px-6 py-3 text-sm">
            <i class="fas fa-floppy-disk"></i>
            {{ $isEdit ? 'Enregistrer les modifications' : 'Créer la location' }}
        </button>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addButton = document.querySelector('[data-add-location-feature]');
            const container = document.querySelector('[data-location-features]');

            addButton?.addEventListener('click', () => {
                if (!container) {
                    return;
                }

                const row = document.createElement('div');
                row.className = 'flex items-center gap-3';
                row.innerHTML = `
                    <input type="text" name="features[]" class="w-full rounded-2xl border border-[#ddcfbb] bg-white px-4 py-3 text-sm" placeholder="Ex: Chauffeur inclus, Wi-Fi, bagagerie">
                    <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-red-50 text-red-700 transition hover:bg-red-100" data-remove-location-feature>
                        <i class="fas fa-times"></i>
                    </button>
                `;
                container.appendChild(row);
            });

            document.addEventListener('click', (event) => {
                const removeButton = event.target.closest('[data-remove-location-feature]');
                if (!removeButton) {
                    return;
                }

                removeButton.closest('.flex.items-center.gap-3')?.remove();
            });
        });
    </script>
@endpush
