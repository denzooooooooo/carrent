@php
    $detailRows = match ($booking->booking_type) {
        'event' => array_filter([
            ['label' => 'Événement', 'value' => $booking->event?->title ?? $booking->title],
            ['label' => $booking->event_selection_type_label, 'value' => $booking->event_selection_label],
            ['label' => 'Date', 'value' => $booking->travel_date_label ?: ($booking->event?->event_date?->format('d/m/Y') ?? null)],
            ['label' => 'Places', 'value' => $booking->number_of_passengers ?: null],
        ], fn ($row) => filled($row['value'])),
        'package' => array_filter([
            ['label' => 'Package', 'value' => $booking->package?->title ?? $booking->title],
            ['label' => 'Destination', 'value' => $booking->package?->destination],
            ['label' => 'Départ', 'value' => optional($booking->packageBooking?->travel_date)->format('d/m/Y') ?: $booking->travel_date_label],
            ['label' => 'Participants', 'value' => $booking->packageBooking?->participants_count ?: $booking->number_of_passengers ?: null],
        ], fn ($row) => filled($row['value'])),
        'location' => array_filter([
            ['label' => 'Location', 'value' => $booking->location?->name ?? $booking->title],
            ['label' => 'Catégorie', 'value' => $booking->location?->category ? ucfirst($booking->location->category) : null],
            ['label' => 'Période', 'value' => $booking->locationBooking?->start_date && $booking->locationBooking?->end_date ? $booking->locationBooking->start_date->format('d/m/Y') . ' - ' . $booking->locationBooking->end_date->format('d/m/Y') : null],
            ['label' => 'Durée', 'value' => $booking->locationBooking?->days ? $booking->locationBooking->days . ' jour' . ($booking->locationBooking->days > 1 ? 's' : '') : null],
        ], fn ($row) => filled($row['value'])),
        'flight' => array_filter([
            ['label' => 'Vol', 'value' => $booking->title],
            ['label' => 'Date', 'value' => $booking->travel_date_label],
            ['label' => 'Passagers', 'value' => $booking->number_of_passengers ?: null],
            ['label' => 'Classe', 'value' => $booking->seat_class],
        ], fn ($row) => filled($row['value'])),
        default => [],
    };

    $customerRows = array_filter([
        ['label' => 'Client', 'value' => $booking->customer_name],
        ['label' => 'Email', 'value' => $booking->customer_email],
        ['label' => 'Téléphone', 'value' => $booking->customer_phone],
    ], fn ($row) => filled($row['value']));

    $hasDiscount = (float) $booking->discount_amount > 0 || (float) $booking->total_amount !== (float) $booking->final_amount;
    $discountAmount = (float) $booking->discount_amount > 0
        ? (float) $booking->discount_amount
        : max((float) $booking->total_amount - (float) $booking->final_amount, 0);
@endphp

<div class="cp-panel rounded-[2rem] p-6 sm:p-7">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $heading ?? 'Récapitulatif' }}</p>
            <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $booking->booking_number }}</h2>
        </div>
        <span class="rounded-full bg-[rgba(75,40,112,0.08)] px-3 py-1 text-xs font-black uppercase tracking-[0.16em] text-[color:var(--cp-plum-800)]">
            {{ ucfirst($booking->booking_type) }}
        </span>
    </div>

    <div class="mt-6 space-y-3">
        @foreach($detailRows as $row)
            <div class="flex items-start justify-between gap-4 rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white/80 px-4 py-3">
                <span class="text-sm font-medium text-[color:var(--cp-ink-soft)]">{{ $row['label'] }}</span>
                <span class="text-right text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $row['value'] }}</span>
            </div>
        @endforeach
    </div>

    @if(!empty($customerRows))
        <div class="mt-6">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">Coordonnées</p>
            <div class="mt-3 space-y-3">
                @foreach($customerRows as $row)
                    <div class="flex items-start justify-between gap-4 rounded-[1.25rem] border border-[color:var(--cp-border)] bg-[rgba(255,255,255,0.72)] px-4 py-3">
                        <span class="text-sm font-medium text-[color:var(--cp-ink-soft)]">{{ $row['label'] }}</span>
                        <span class="text-right text-sm font-semibold text-[color:var(--cp-plum-950)]">{{ $row['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-6 rounded-[1.5rem] border border-[rgba(75,40,112,0.12)] bg-[linear-gradient(135deg,rgba(75,40,112,0.06),rgba(217,164,65,0.08))] px-5 py-5">
        <div class="flex items-center justify-between gap-3 text-sm text-[color:var(--cp-ink-soft)]">
            <span>Sous-total</span>
            <span class="font-semibold text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($booking->total_amount) }}</span>
        </div>
        @if($hasDiscount)
            <div class="mt-3 flex items-center justify-between gap-3 text-sm text-[color:var(--cp-success)]">
                <span>Ajustement</span>
                <span class="font-bold">-{{ \App\Helpers\CurrencyHelper::format($discountAmount) }}</span>
            </div>
        @endif
        <div class="mt-4 flex items-end justify-between gap-4 border-t border-[rgba(75,40,112,0.12)] pt-4">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Total à régler</p>
                <p class="mt-2 text-3xl font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($booking->final_amount) }}</p>
            </div>
            <div class="rounded-full bg-white/90 px-3 py-1 text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)] shadow-sm">
                {{ $booking->currency }}
            </div>
        </div>
    </div>
</div>
