@extends('layouts.app')

@section('title', $event->title . ' - Carré Premium')

@section('content')
@php
    $imageUrl = $event->getFirstMediaUrl('avatar', 'normal');
    $placeholder = 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=1200&h=600&fit=crop';
    $hasPackages = \Illuminate\Support\Facades\Schema::hasTable('event_packages') && $event->packages->count() > 0;
    $hasSeatZones = $event->seatZones->count() > 0;
    $hasInventory = $hasPackages || $hasSeatZones;
@endphp

<div class="min-h-screen bg-stone-50">
    <section class="relative overflow-hidden">
        <div class="h-[420px] md:h-[520px]">
            <img src="{{ $imageUrl ?: $placeholder }}" alt="{{ $event->title }}" class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
        </div>

        <div class="absolute inset-x-0 bottom-0">
            <div class="container mx-auto px-4 pb-8">
                <div class="max-w-4xl rounded-3xl bg-white/95 p-6 shadow-2xl backdrop-blur md:p-8">
                    <div class="mb-4 flex flex-wrap gap-2">
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-800">
                            {{ $event->category->name_fr ?? 'Événement' }}
                        </span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">
                            {{ $event->type->name_fr ?? 'Sport' }}
                        </span>
                        @if($event->tagline)
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-800">
                                Catalogue PDF
                            </span>
                        @endif
                    </div>

                    <h1 class="max-w-3xl text-3xl font-black leading-tight text-slate-950 md:text-5xl">
                        {{ $event->title }}
                    </h1>

                    @if($event->tagline)
                        <p class="mt-3 max-w-3xl text-sm font-medium text-slate-600 md:text-lg">
                            {{ $event->tagline }}
                        </p>
                    @endif

                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Dates</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $event->date_range_label ?? $event->short_date_label }}</p>
                            <p class="text-sm text-slate-600">
                                {{ $event->event_time }}
                                @if($event->end_time && $event->end_date && $event->end_date->isSameDay($event->event_date))
                                    - {{ $event->end_time }}
                                @endif
                            </p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Lieu</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $event->venue_name }}</p>
                            <p class="text-sm text-slate-600">{{ $event->city }}, {{ $event->country }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">À partir de</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ \App\Helpers\CurrencyHelper::format($event->min_price) }}</p>
                            <p class="text-sm text-slate-600">Tarif catalogue selon disponibilité</p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        @if($hasInventory)
                            <button onclick="document.getElementById('reservation-panel').scrollIntoView({ behavior: 'smooth' })" class="rounded-2xl bg-slate-950 px-6 py-4 text-sm font-bold uppercase tracking-[0.2em] text-white transition hover:bg-slate-800">
                                Choisir une formule
                            </button>
                        @endif
                        <a href="{{ route('contact') }}" class="rounded-2xl border border-slate-300 px-6 py-4 text-center text-sm font-bold uppercase tracking-[0.2em] text-slate-700 transition hover:border-slate-950 hover:text-slate-950">
                            Besoin d’aide
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.7fr)_minmax(340px,0.9fr)]">
            <div class="space-y-6">
                <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm md:p-8">
                    <h2 class="text-2xl font-black text-slate-950">Présentation</h2>
                    <div class="prose mt-4 max-w-none text-slate-700">
                        {!! nl2br(e($event->description_fr)) !!}
                    </div>
                </section>

                @if($event->program)
                    <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm md:p-8">
                        <h2 class="text-2xl font-black text-slate-950">Programme</h2>
                        <div class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-700 md:text-base">
                            {{ $event->program }}
                        </div>
                    </section>
                @endif

                <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm md:p-8">
                    <h2 class="text-2xl font-black text-slate-950">Lieu et organisation</h2>
                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Site</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $event->venue_name }}</p>
                            @if($event->venue_address)
                                <p class="mt-1 text-sm text-slate-600">{{ $event->venue_address }}</p>
                            @endif
                            <p class="mt-1 text-sm text-slate-600">{{ $event->city }}, {{ $event->country }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Organisateur</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $event->organizer ?: 'Carré Premium' }}</p>
                            @if($event->source_catalog)
                                <p class="mt-1 text-sm text-slate-600">Source: {{ $event->source_catalog }}</p>
                            @endif
                        </div>
                    </div>
                </section>

                @if($event->conditions)
                    <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm md:p-8">
                        <h2 class="text-2xl font-black text-slate-950">Conditions</h2>
                        <div class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-700 md:text-base">
                            {{ $event->conditions }}
                        </div>
                    </section>
                @endif
            </div>

            <aside id="reservation-panel" class="lg:sticky lg:top-6 lg:self-start">
                <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm md:p-6">
                    <div class="mb-5 border-b border-stone-200 pb-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Réservation</p>
                        <h3 class="mt-2 text-2xl font-black text-slate-950">Choisissez votre formule</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Les disponibilités et minimums affichés correspondent au catalogue chargé depuis les PDF.
                        </p>
                    </div>

                    @if($hasPackages)
                        <div class="space-y-5">
                            @foreach($event->packages as $package)
                                @php
                                    $includedLines = collect(preg_split('/\r\n|\r|\n/', (string) $package->description_included_fr))->filter();
                                @endphp
                                <article class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                    <div class="flex flex-col gap-4">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <div class="flex flex-wrap gap-2">
                                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.15em] text-slate-600">
                                                        {{ $package->package_code }}
                                                    </span>
                                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.15em] text-amber-800">
                                                        Minimum {{ max(1, $package->minimum_quantity ?? 1) }}
                                                    </span>
                                                </div>
                                                <h4 class="mt-3 text-xl font-black text-slate-950">{{ $package->name }}</h4>
                                                @if($package->venue_details)
                                                    <p class="mt-1 text-sm font-medium text-slate-500">{{ $package->venue_details }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">À partir de</p>
                                                <p class="mt-2 text-2xl font-black text-slate-950">{{ \App\Helpers\CurrencyHelper::format($package->price) }}</p>
                                            </div>
                                        </div>

                                        @if($package->description_fr)
                                            <p class="text-sm leading-6 text-slate-700">{{ $package->description_fr }}</p>
                                        @endif

                                        @if($includedLines->isNotEmpty())
                                            <div class="rounded-2xl bg-white p-4">
                                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Inclus dans l’offre</p>
                                                <ul class="mt-3 space-y-2 text-sm text-slate-700">
                                                    @foreach($includedLines as $line)
                                                        <li class="flex gap-2">
                                                            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-slate-950"></span>
                                                            <span>{{ $line }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @if($package->has_options)
                                            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                                                <div class="border-b border-slate-200 px-4 py-3">
                                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Options tarifaires</p>
                                                </div>
                                                <div class="divide-y divide-slate-200">
                                                    @foreach($package->options as $option)
                                                        <div class="px-4 py-4">
                                                            <div class="flex flex-col gap-3">
                                                                <div class="flex items-start justify-between gap-4">
                                                                    <div>
                                                                        <p class="font-bold text-slate-900">{{ $option->label }}</p>
                                                                        @if($option->context)
                                                                            <p class="mt-1 text-sm text-slate-600">{{ $option->context }}</p>
                                                                        @endif
                                                                        <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-500">
                                                                            {{ $option->available_quantity }} disponibles
                                                                        </p>
                                                                    </div>
                                                                    <p class="text-lg font-black text-slate-950">{{ \App\Helpers\CurrencyHelper::format($option->price) }}</p>
                                                                </div>
                                                                <button
                                                                    class="select-package-btn rounded-2xl bg-slate-950 px-4 py-3 text-sm font-bold uppercase tracking-[0.16em] text-white transition hover:bg-slate-800"
                                                                    data-package-id="{{ $package->id }}"
                                                                    data-package-option-id="{{ $option->id }}"
                                                                    data-package-name="{{ $package->name }}"
                                                                    data-selection-label="{{ $option->full_label }}"
                                                                    data-price="{{ $option->price }}"
                                                                    data-available="{{ $option->available_quantity }}"
                                                                    data-max-per-order="{{ $option->max_per_order }}"
                                                                    data-minimum-quantity="{{ max(1, $package->minimum_quantity ?? 1) }}"
                                                                >
                                                                    Sélectionner cette option
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-between gap-4 rounded-2xl bg-white p-4">
                                                <div>
                                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Disponibilité</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $package->available_quantity }} disponibles</p>
                                                </div>
                                                <button
                                                    class="select-package-btn rounded-2xl bg-slate-950 px-4 py-3 text-sm font-bold uppercase tracking-[0.16em] text-white transition hover:bg-slate-800"
                                                    data-package-id="{{ $package->id }}"
                                                    data-package-option-id=""
                                                    data-package-name="{{ $package->name }}"
                                                    data-selection-label="{{ $package->name }}"
                                                    data-price="{{ $package->price }}"
                                                    data-available="{{ $package->available_quantity }}"
                                                    data-max-per-order="{{ $package->max_per_order }}"
                                                    data-minimum-quantity="{{ max(1, $package->minimum_quantity ?? 1) }}"
                                                >
                                                    Réserver
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif

                    @if($hasSeatZones)
                        <div class="@if($hasPackages) mt-6 border-t border-stone-200 pt-6 @endif space-y-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Zones de sièges</p>
                            @foreach($event->seatZones as $zone)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h4 class="text-lg font-black text-slate-950">{{ $zone->zone_name }}</h4>
                                            @if($zone->description)
                                                <p class="mt-1 text-sm text-slate-600">{{ $zone->description }}</p>
                                            @endif
                                            <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-500">{{ $zone->available_seats }} restantes</p>
                                        </div>
                                        <p class="text-lg font-black text-slate-950">{{ \App\Helpers\CurrencyHelper::format($zone->price) }}</p>
                                    </div>
                                    <button
                                        class="select-seat-btn mt-4 w-full rounded-2xl border border-slate-950 px-4 py-3 text-sm font-bold uppercase tracking-[0.16em] text-slate-950 transition hover:bg-slate-950 hover:text-white"
                                        data-zone-id="{{ $zone->id }}"
                                        data-zone-name="{{ $zone->zone_name }}"
                                        data-selection-label="{{ $zone->zone_name }}"
                                        data-price="{{ $zone->price }}"
                                        data-available="{{ $zone->available_seats }}"
                                        data-max-per-order="{{ $zone->available_seats }}"
                                        data-minimum-quantity="1"
                                    >
                                        Sélectionner cette zone
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @unless($hasInventory)
                        <div class="rounded-3xl border border-dashed border-stone-300 bg-stone-50 px-4 py-8 text-center">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Bientôt disponible</p>
                            <p class="mt-2 text-sm text-slate-600">Les formules de réservation ne sont pas encore publiées pour cet événement.</p>
                        </div>
                    @endunless
                </div>
            </aside>
        </div>
    </div>
</div>

<div id="bookingModal" class="fixed inset-0 z-50 hidden bg-black/60 p-4">
    <div class="flex min-h-full items-center justify-center">
        <div class="w-full max-w-lg rounded-3xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-stone-200 px-6 py-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Réservation</p>
                    <h3 id="modalTitle" class="mt-1 text-xl font-black text-slate-950">Confirmer la sélection</h3>
                </div>
                <button id="closeModal" type="button" class="rounded-full border border-stone-200 p-2 text-slate-600 transition hover:border-slate-950 hover:text-slate-950">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="px-6 py-5">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p id="selectedItemType" class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Formule</p>
                    <p id="selectedItemName" class="mt-2 text-lg font-black text-slate-950"></p>
                    <p id="selectedItemMeta" class="mt-1 text-sm text-slate-600"></p>
                </div>

                <div class="mt-5 rounded-2xl border border-stone-200 p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Prix unitaire</span>
                        <span id="unitPrice" class="text-base font-bold text-slate-950"></span>
                    </div>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-sm text-slate-600">Minimum requis</span>
                        <span id="minRequired" class="text-base font-bold text-slate-950">1</span>
                    </div>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-sm text-slate-600">Maximum par commande</span>
                        <span id="maxAllowed" class="text-base font-bold text-slate-950">1</span>
                    </div>
                    <div class="mt-4 flex items-center gap-3">
                        <button id="decreaseQty" type="button" class="rounded-full bg-slate-100 p-3 text-slate-700 transition hover:bg-slate-200">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </button>
                        <div class="min-w-[72px] text-center">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Quantité</p>
                            <p id="quantity" class="mt-1 text-2xl font-black text-slate-950">1</p>
                        </div>
                        <button id="increaseQty" type="button" class="rounded-full bg-slate-100 p-3 text-slate-700 transition hover:bg-slate-200">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-4 border-t border-stone-200 pt-4">
                        <div class="flex items-center justify-between text-lg font-black">
                            <span class="text-slate-700">Total</span>
                            <span id="totalPrice" class="text-slate-950"></span>
                        </div>
                    </div>
                    <div id="paymentModeHint" class="mt-4 hidden rounded-2xl bg-amber-100 px-4 py-3 text-sm font-medium text-amber-900">
                        Montant supérieur à 1,5 M XOF : la réservation sera créée avec paiement par virement bancaire.
                    </div>
                </div>

                <form id="bookingForm" method="POST" action="{{ route('event.book', $event) }}" class="mt-5 space-y-4">
                    @csrf
                    <input type="hidden" name="zone_id" id="zoneIdInput">
                    <input type="hidden" name="package_id" id="packageIdInput">
                    <input type="hidden" name="package_option_id" id="packageOptionIdInput">
                    <input type="hidden" name="quantity" id="quantityInput" value="1">

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Nom complet</label>
                        <input type="text" name="name" required class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-950">
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" name="email" required class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-950">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">Téléphone</label>
                            <input type="tel" name="phone" required class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-950">
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-slate-950 px-5 py-4 text-sm font-bold uppercase tracking-[0.16em] text-white transition hover:bg-slate-800">
                        Confirmer la réservation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
window.currentCurrency = 'XOF';

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('bookingModal');
    const closeModal = document.getElementById('closeModal');
    const quantitySpan = document.getElementById('quantity');
    const quantityInput = document.getElementById('quantityInput');
    const zoneIdInput = document.getElementById('zoneIdInput');
    const packageIdInput = document.getElementById('packageIdInput');
    const packageOptionIdInput = document.getElementById('packageOptionIdInput');
    const unitPriceEl = document.getElementById('unitPrice');
    const totalPriceEl = document.getElementById('totalPrice');
    const selectedItemTypeEl = document.getElementById('selectedItemType');
    const selectedItemNameEl = document.getElementById('selectedItemName');
    const selectedItemMetaEl = document.getElementById('selectedItemMeta');
    const minRequiredEl = document.getElementById('minRequired');
    const maxAllowedEl = document.getElementById('maxAllowed');
    const paymentModeHint = document.getElementById('paymentModeHint');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');

    let currentItem = null;
    let currentQuantity = 1;
    let minRequired = 1;
    let maxAllowed = 1;

    const openModalForSelection = (item) => {
        minRequired = item.minimumQuantity;
        maxAllowed = Math.min(item.available, item.maxPerOrder);

        if (maxAllowed < minRequired) {
            alert('Cette offre n’est plus disponible pour la quantité minimale requise.');
            return;
        }

        currentItem = item;
        currentQuantity = minRequired;

        selectedItemTypeEl.textContent = item.typeLabel;
        selectedItemNameEl.textContent = item.name;
        selectedItemMetaEl.textContent = item.meta || '';
        unitPriceEl.textContent = formatPrice(item.price);
        minRequiredEl.textContent = minRequired;
        maxAllowedEl.textContent = maxAllowed;

        quantitySpan.textContent = currentQuantity;
        quantityInput.value = currentQuantity;

        zoneIdInput.value = item.type === 'seat' ? item.id : '';
        packageIdInput.value = item.type === 'package' ? item.packageId : '';
        packageOptionIdInput.value = item.type === 'package' ? (item.packageOptionId || '') : '';

        updateTotal();
        modal.classList.remove('hidden');
    };

    document.querySelectorAll('.select-seat-btn').forEach((button) => {
        button.addEventListener('click', function () {
            openModalForSelection({
                type: 'seat',
                id: this.dataset.zoneId,
                packageId: '',
                packageOptionId: '',
                name: this.dataset.zoneName,
                meta: this.dataset.selectionLabel,
                price: parseFloat(this.dataset.price),
                available: parseInt(this.dataset.available, 10),
                maxPerOrder: parseInt(this.dataset.maxPerOrder, 10),
                minimumQuantity: parseInt(this.dataset.minimumQuantity, 10),
                typeLabel: 'Zone de sièges',
            });
        });
    });

    document.querySelectorAll('.select-package-btn').forEach((button) => {
        button.addEventListener('click', function () {
            openModalForSelection({
                type: 'package',
                id: this.dataset.packageId,
                packageId: this.dataset.packageId,
                packageOptionId: this.dataset.packageOptionId,
                name: this.dataset.packageName,
                meta: this.dataset.selectionLabel,
                price: parseFloat(this.dataset.price),
                available: parseInt(this.dataset.available, 10),
                maxPerOrder: parseInt(this.dataset.maxPerOrder, 10),
                minimumQuantity: parseInt(this.dataset.minimumQuantity, 10),
                typeLabel: 'Formule VIP',
            });
        });
    });

    const closeModalWindow = () => {
        modal.classList.add('hidden');
    };

    closeModal.addEventListener('click', closeModalWindow);

    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModalWindow();
        }
    });

    decreaseBtn.addEventListener('click', function () {
        if (currentQuantity > minRequired) {
            currentQuantity--;
            syncQuantity();
        }
    });

    increaseBtn.addEventListener('click', function () {
        if (currentQuantity < maxAllowed) {
            currentQuantity++;
            syncQuantity();
        }
    });

    function syncQuantity() {
        quantitySpan.textContent = currentQuantity;
        quantityInput.value = currentQuantity;
        updateTotal();
    }

    function updateTotal() {
        if (!currentItem) {
            return;
        }

        const total = currentItem.price * currentQuantity;
        totalPriceEl.textContent = formatPrice(total);

        if (currentItem.type === 'package' && total > 1500000) {
            paymentModeHint.classList.remove('hidden');
        } else {
            paymentModeHint.classList.add('hidden');
        }
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: window.currentCurrency,
            minimumFractionDigits: 0,
        }).format(price);
    }
});
</script>
@endsection
