@extends('layouts.app')

@section('title', 'Mes réservations - Carré Premium')
@section('meta_description', 'Consultez et suivez toutes vos réservations Carré Premium depuis votre espace client.')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $user = auth()->user();
    $statusSummary = [
        'confirmed' => $bookings->where('status', 'confirmed')->count(),
        'pending' => $bookings->where('status', 'pending')->count(),
        'cancelled' => $bookings->where('status', 'cancelled')->count(),
    ];
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="grid gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
                @include('pages.users._account-nav', ['accountUser' => $user, 'activePage' => 'bookings'])

                <div class="space-y-6">
                    <div class="overflow-hidden rounded-[2.3rem] bg-gradient-to-br from-[#22112f] via-[#4c2872] to-[#d9a64d] px-6 py-8 text-white shadow-[0_28px_90px_rgba(41,20,58,0.22)] sm:px-8 sm:py-10">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                            <div class="max-w-3xl">
                                <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                                    <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                                    <span>{{ $t('Mes réservations', 'My bookings') }}</span>
                                </div>
                                <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl">{{ $t('Un suivi plus lisible de tous vos dossiers clients.', 'A clearer overview of all your client bookings.') }}</h1>
                                <p class="mt-4 text-sm leading-7 text-white/82 sm:text-base">
                                    {{ $t('Chaque réservation doit permettre d’identifier immédiatement le service, le statut, le paiement et l’action suivante.', 'Every booking should make the service, status, payment and next action immediately clear.') }}
                                </p>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-3">
                                <div class="rounded-[1.35rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Total', 'Total') }}</p>
                                    <p class="mt-2 text-2xl font-black">{{ $bookings->total() }}</p>
                                </div>
                                <div class="rounded-[1.35rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Confirmées', 'Confirmed') }}</p>
                                    <p class="mt-2 text-2xl font-black">{{ $statusSummary['confirmed'] }}</p>
                                </div>
                                <div class="rounded-[1.35rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('En attente', 'Pending') }}</p>
                                    <p class="mt-2 text-2xl font-black">{{ $statusSummary['pending'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($bookings->count() > 0)
                        <div class="cp-panel rounded-[2rem] p-5 sm:p-6">
                            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div>
                                    <label for="status_filter" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Statut', 'Status') }}</label>
                                    <select id="status_filter" class="w-full rounded-[1.2rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none">
                                        <option value="">{{ $t('Tous les statuts', 'All statuses') }}</option>
                                        <option value="confirmed">{{ $t('Confirmé', 'Confirmed') }}</option>
                                        <option value="pending">{{ $t('En attente', 'Pending') }}</option>
                                        <option value="cancelled">{{ $t('Annulé', 'Cancelled') }}</option>
                                        <option value="completed">{{ $t('Terminé', 'Completed') }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="type_filter" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Service', 'Service') }}</label>
                                    <select id="type_filter" class="w-full rounded-[1.2rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none">
                                        <option value="">{{ $t('Tous les services', 'All services') }}</option>
                                        <option value="event">{{ $t('Événements', 'Events') }}</option>
                                        <option value="package">{{ $t('Packages', 'Packages') }}</option>
                                        <option value="flight">{{ $t('Vols', 'Flights') }}</option>
                                        <option value="location">{{ $t('Location', 'Rental') }}</option>
                                    </select>
                                </div>

                                <div class="rounded-[1.2rem] bg-[#faf6ff] px-4 py-3">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Paiements à traiter', 'Pending payments') }}</p>
                                    <p class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $bookings->where('payment_status', 'pending')->count() }}</p>
                                </div>

                                <div class="rounded-[1.2rem] bg-[#fff6e8] px-4 py-3">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[#9f6510]">{{ $t('Documents prêts', 'Ready documents') }}</p>
                                    <p class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $bookings->where('payment_status', 'paid')->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach($bookings as $booking)
                                @php
                                    $statusClasses = match ($booking->status) {
                                        'confirmed' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
                                        'pending' => 'border-amber-200 bg-amber-50 text-amber-800',
                                        'cancelled' => 'border-red-200 bg-red-50 text-red-700',
                                        default => 'border-slate-200 bg-slate-50 text-slate-700',
                                    };
                                @endphp

                                <article class="cp-panel rounded-[2rem] p-5 sm:p-6" data-booking data-status="{{ $booking->status }}" data-type="{{ $booking->booking_type }}">
                                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="rounded-full border px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] {{ $statusClasses }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                                <span class="rounded-full bg-[#f3eaff] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">
                                                    {{ ucfirst($booking->booking_type) }}
                                                </span>
                                                <span class="rounded-full bg-[#f8f3ea] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[#9f6510]">
                                                    {{ ucfirst($booking->payment_status) }}
                                                </span>
                                            </div>

                                            <h2 class="mt-4 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $booking->title }}</h2>
                                            <p class="mt-2 text-sm text-[color:var(--cp-ink-soft)]">{{ $booking->booking_number }} · {{ optional($booking->created_at)->format('d/m/Y H:i') }}</p>

                                            <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                                                <div class="rounded-[1.25rem] bg-[#faf6ff] px-4 py-3">
                                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Montant', 'Amount') }}</p>
                                                    <p class="mt-2 text-lg font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($booking->final_amount)) }}</p>
                                                </div>
                                                <div class="rounded-[1.25rem] bg-[#faf6ff] px-4 py-3">
                                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Date service', 'Service date') }}</p>
                                                    <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $booking->travel_date_label ?? $t('À confirmer', 'To be confirmed') }}</p>
                                                </div>
                                                <div class="rounded-[1.25rem] bg-[#faf6ff] px-4 py-3">
                                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Référence', 'Reference') }}</p>
                                                    <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $booking->booking_number }}</p>
                                                </div>
                                                <div class="rounded-[1.25rem] bg-[#faf6ff] px-4 py-3">
                                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Paiement', 'Payment') }}</p>
                                                    <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $booking->payment_method_label }}</p>
                                                </div>
                                            </div>

                                            <div class="mt-5 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                                @if($booking->booking_type === 'event')
                                                    <p>{{ $t('Événement :', 'Event:') }} <span class="font-bold text-[color:var(--cp-plum-950)]">{{ $booking->event?->title ?? $t('Non renseigné', 'Not provided') }}</span></p>
                                                    <p>{{ $booking->event_selection_type_label }} : <span class="font-bold text-[color:var(--cp-plum-950)]">{{ $booking->event_selection_label }}</span></p>
                                                @elseif($booking->booking_type === 'package')
                                                    <p>{{ $t('Package :', 'Package:') }} <span class="font-bold text-[color:var(--cp-plum-950)]">{{ $booking->package?->title ?? $t('Non renseigné', 'Not provided') }}</span></p>
                                                @elseif($booking->booking_type === 'location')
                                                    <p>{{ $t('Véhicule :', 'Vehicle:') }} <span class="font-bold text-[color:var(--cp-plum-950)]">{{ $booking->location?->name ?? $t('Non renseigné', 'Not provided') }}</span></p>
                                                @elseif($booking->booking_type === 'flight')
                                                    <p>{{ $t('Vol :', 'Flight:') }} <span class="font-bold text-[color:var(--cp-plum-950)]">{{ $booking->flightBooking->flight_number ?? $booking->flight->flight_number ?? $t('Non renseigné', 'Not provided') }}</span></p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="grid w-full gap-3 lg:w-[250px]">
                                            @if($booking->payment_status === 'pending')
                                                <a href="{{ route('payment.checkout', $booking) }}" class="cp-primary-button !flex !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                                                    <i class="fa-solid fa-credit-card text-sm"></i>
                                                    <span>{{ $t('Payer maintenant', 'Pay now') }}</span>
                                                </a>
                                            @else
                                                <a href="{{ route('user.booking.details', $booking) }}" class="cp-primary-button !flex !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                                                    <i class="fa-regular fa-eye text-sm"></i>
                                                    <span>{{ $t('Voir les détails', 'View details') }}</span>
                                                </a>
                                            @endif

                                            @if($booking->payment_status === 'paid')
                                                <a href="{{ route('user.booking.documents.download', ['booking' => $booking, 'documentType' => 'invoice']) }}" class="cp-secondary-button !flex !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                                                    <i class="fa-regular fa-file-lines text-sm"></i>
                                                    <span>{{ $t('Télécharger la facture', 'Download invoice') }}</span>
                                                </a>
                                            @endif

                                            @if(in_array($booking->status, ['confirmed', 'pending'], true))
                                                <button type="button" onclick="cancelBooking({{ $booking->id }}, @json($booking->booking_number))" class="rounded-[1.2rem] border border-red-200 bg-red-50 px-4 py-3 text-sm font-black text-red-700 transition hover:bg-red-100">
                                                    {{ $t('Annuler la réservation', 'Cancel booking') }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        @if($bookings->hasPages())
                            <div class="pt-2">
                                {{ $bookings->links() }}
                            </div>
                        @endif
                    @else
                        <div class="cp-panel rounded-[2rem] px-6 py-12 text-center">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Aucune réservation', 'No bookings') }}</p>
                            <h2 class="mt-3 text-3xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Votre espace est encore vide.', 'Your account is still empty.') }}</h2>
                            <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                {{ $t('Commencez par un événement, un package ou un service de location. Le suivi apparaîtra ici automatiquement.', 'Start with an event, package or rental. Tracking will appear here automatically.') }}
                            </p>
                            <div class="mt-6 flex flex-col items-center justify-center gap-3 sm:flex-row">
                                <a href="{{ route('events') }}" class="cp-primary-button">{{ $t('Voir les événements', 'Browse events') }}</a>
                                <a href="{{ route('packages') }}" class="cp-secondary-button">{{ $t('Explorer les packages', 'Explore packages') }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusFilter = document.getElementById('status_filter');
    const typeFilter = document.getElementById('type_filter');

    if (!statusFilter || !typeFilter) {
        return;
    }

    const filterBookings = function () {
        const statusValue = statusFilter.value.toLowerCase();
        const typeValue = typeFilter.value.toLowerCase();

        document.querySelectorAll('[data-booking]').forEach(function (booking) {
            const bookingStatus = (booking.dataset.status || '').toLowerCase();
            const bookingType = (booking.dataset.type || '').toLowerCase();
            const statusMatch = !statusValue || bookingStatus === statusValue;
            const typeMatch = !typeValue || bookingType === typeValue;

            booking.style.display = statusMatch && typeMatch ? '' : 'none';
        });
    };

    statusFilter.addEventListener('change', filterBookings);
    typeFilter.addEventListener('change', filterBookings);
});

function cancelBooking(bookingId, bookingNumber) {
    if (!confirm(`Êtes-vous sûr de vouloir annuler la réservation ${bookingNumber} ? Cette action est irréversible.`)) {
        return;
    }

    fetch(`/bookings/${bookingId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ reason: 'Annulé par l’utilisateur' })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
                return;
            }

            alert('Erreur: ' + data.error);
        })
        .catch(() => {
            alert('Une erreur s’est produite lors de l’annulation.');
        });
}
</script>
@endpush
@endsection
