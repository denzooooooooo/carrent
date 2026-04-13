@php
    $isEdit = isset($promoCode) && $promoCode->exists;
@endphp

<section class="grid gap-6 xl:grid-cols-[1fr,0.95fr]">
    <article class="admin-panel p-6 sm:p-7">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Offre commerciale</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-950">Configuration du code promo</h2>
            <p class="mt-3 text-sm leading-7 text-slate-600">
                Définis le code, son périmètre, sa réduction et sa fenêtre de validité.
            </p>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2">
            <div>
                <label for="code" class="mb-2 block text-sm font-semibold text-slate-700">Code *</label>
                <input id="code" name="code" type="text" value="{{ old('code', $promoCode->code ?? null) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm uppercase tracking-[0.18em]" placeholder="EX: VIP2026">
                @error('code')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="applicable_to" class="mb-2 block text-sm font-semibold text-slate-700">Applicable à *</label>
                <select id="applicable_to" name="applicable_to" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                    @foreach (['all' => 'Tous les parcours', 'flights' => 'Vols', 'events' => 'Événements', 'packages' => 'Packages'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('applicable_to', $promoCode->applicable_to ?? 'all') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('applicable_to')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="discount_type" class="mb-2 block text-sm font-semibold text-slate-700">Type de réduction *</label>
                <select id="discount_type" name="discount_type" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                    <option value="percentage" @selected(old('discount_type', $promoCode->discount_type ?? 'percentage') === 'percentage')>Pourcentage</option>
                    <option value="fixed" @selected(old('discount_type', $promoCode->discount_type ?? null) === 'fixed')>Montant fixe</option>
                </select>
                @error('discount_type')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="discount_value" class="mb-2 block text-sm font-semibold text-slate-700">Valeur *</label>
                <input id="discount_value" name="discount_value" type="number" min="0.01" step="0.01" value="{{ old('discount_value', $promoCode->discount_value ?? null) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                @error('discount_value')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="min_purchase_amount" class="mb-2 block text-sm font-semibold text-slate-700">Montant minimum</label>
                <input id="min_purchase_amount" name="min_purchase_amount" type="number" min="0" step="0.01" value="{{ old('min_purchase_amount', $promoCode->min_purchase_amount ?? null) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                @error('min_purchase_amount')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="max_discount_amount" class="mb-2 block text-sm font-semibold text-slate-700">Plafond réduction</label>
                <input id="max_discount_amount" name="max_discount_amount" type="number" min="0" step="0.01" value="{{ old('max_discount_amount', $promoCode->max_discount_amount ?? null) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                @error('max_discount_amount')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="usage_limit" class="mb-2 block text-sm font-semibold text-slate-700">Limite d’utilisation</label>
                <input id="usage_limit" name="usage_limit" type="number" min="1" value="{{ old('usage_limit', $promoCode->usage_limit ?? null) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm" placeholder="Illimité si vide">
                @error('usage_limit')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            @if ($isEdit)
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Utilisations comptées</label>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900">
                        {{ number_format((int) $promoCode->used_count, 0, ',', ' ') }}
                    </div>
                </div>
            @endif

            <div>
                <label for="valid_from" class="mb-2 block text-sm font-semibold text-slate-700">Début validité *</label>
                <input id="valid_from" name="valid_from" type="datetime-local" value="{{ old('valid_from', isset($promoCode) && $promoCode->valid_from ? $promoCode->valid_from->format('Y-m-d\\TH:i') : null) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                @error('valid_from')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="valid_until" class="mb-2 block text-sm font-semibold text-slate-700">Fin validité *</label>
                <input id="valid_until" name="valid_until" type="datetime-local" value="{{ old('valid_until', isset($promoCode) && $promoCode->valid_until ? $promoCode->valid_until->format('Y-m-d\\TH:i') : null) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                @error('valid_until')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label for="description_fr" class="mb-2 block text-sm font-semibold text-slate-700">Description FR</label>
                <textarea id="description_fr" name="description_fr" rows="4" class="w-full rounded-[1.35rem] border border-slate-200 bg-white px-4 py-3 text-sm">{{ old('description_fr', $promoCode->description_fr ?? null) }}</textarea>
                @error('description_fr')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label for="description_en" class="mb-2 block text-sm font-semibold text-slate-700">Description EN</label>
                <textarea id="description_en" name="description_en" rows="4" class="w-full rounded-[1.35rem] border border-slate-200 bg-white px-4 py-3 text-sm">{{ old('description_en', $promoCode->description_en ?? null) }}</textarea>
                @error('description_en')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </article>

    <article class="space-y-6">
        <div class="admin-panel p-6 sm:p-7">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Activation</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-950">Statut du code</h2>

            <div class="mt-6 rounded-[1.5rem] border border-slate-200 bg-slate-50 px-4 py-4">
                <label class="flex items-center gap-3 text-sm text-slate-700">
                    <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-[var(--admin-brand)] focus:ring-[var(--admin-brand)]" {{ old('is_active', $promoCode->is_active ?? true) ? 'checked' : '' }}>
                    <span>
                        <span class="block font-semibold text-slate-900">Code actif</span>
                        <span class="block text-xs text-slate-500">Peut être utilisé s’il est dans sa fenêtre de validité.</span>
                    </span>
                </label>
            </div>
        </div>

        <div class="admin-panel p-6 sm:p-7">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--admin-brand)]">Validation</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-950">{{ $isEdit ? 'Mettre à jour' : 'Créer le code' }}</h2>
            <p class="mt-3 text-sm leading-7 text-slate-600">
                Vérifie la portée, la réduction et les dates avant enregistrement.
            </p>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('admin.promo-codes.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">Annuler</a>
                <button type="submit" class="admin-btn-primary px-6 py-3 text-sm">
                    <i class="fas fa-floppy-disk"></i>
                    {{ $isEdit ? 'Enregistrer les modifications' : 'Créer le code promo' }}
                </button>
            </div>
        </div>
    </article>
</section>
