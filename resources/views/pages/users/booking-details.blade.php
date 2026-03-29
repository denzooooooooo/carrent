@extends('layouts.app')

@section('title', 'Détail de réservation - Carré Premium')
@section('meta_description', 'Consultez le détail de votre réservation, vos documents et vos montants depuis votre espace Carré Premium.')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $user = auth()->user();
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
                                    <span>{{ $t('Détail de réservation', 'Booking detail') }}</span>
                                </div>
                                <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl">{{ $booking->title }}</h1>
                                <p class="mt-3 text-sm leading-7 text-white/82 sm:text-base">{{ $t('Référence', 'Reference') }} {{ $booking->booking_number }} · {{ optional($booking->created_at)->format('d/m/Y H:i') }}</p>
                            </div>

                            <div class="rounded-[1.5rem] border border-white/15 bg-white/10 px-5 py-4 backdrop-blur">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Montant final', 'Final amount') }}</p>
                                <p class="mt-2 text-2xl font-black">{{ number_format((float) $booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</p>
                                <p class="mt-2 text-sm font-semibold text-white/78">{{ ucfirst($booking->status) }} · {{ ucfirst($booking->payment_status) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.05fr)_340px]">
                        <div class="space-y-6">
                            <div class="cp-panel rounded-[2rem] p-6 sm:p-8">
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Client', 'Customer') }}</p>
                                <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Informations principales', 'Main details') }}</h2>

                                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-[1.25rem] bg-[#faf6ff] px-4 py-3">
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Nom', 'Name') }}</p>
                                        <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $booking->customer_name }}</p>
                                    </div>
                                    <div class="rounded-[1.25rem] bg-[#faf6ff] px-4 py-3">
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Email', 'Email') }}</p>
                                        <p class="mt-2 break-all text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $booking->customer_email ?? $t('Non renseigné', 'Not provided') }}</p>
                                    </div>
                                    <div class="rounded-[1.25rem] bg-[#faf6ff] px-4 py-3">
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Téléphone', 'Phone') }}</p>
                                        <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $booking->customer_phone ?? $t('Non renseigné', 'Not provided') }}</p>
                                    </div>
                                    <div class="rounded-[1.25rem] bg-[#faf6ff] px-4 py-3">
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Date de service', 'Service date') }}</p>
                                        <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $booking->travel_date_label ?? $t('Non renseignée', 'Not provided') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="cp-panel rounded-[2rem] p-6 sm:p-8">
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Réservation', 'Booking') }}</p>
                                <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Détail du dossier', 'Booking breakdown') }}</h2>

                                <div class="mt-5 overflow-hidden rounded-[1.6rem] border border-[color:var(--cp-border)]">
                                    <table class="min-w-full divide-y divide-[color:var(--cp-border)] text-sm">
                                        <tbody class="divide-y divide-[color:var(--cp-border)] bg-white">
                                            <tr>
                                                <td class="px-5 py-4 text-[color:var(--cp-ink-soft)]">{{ $t('Type', 'Type') }}</td>
                                                <td class="px-5 py-4 font-bold text-[color:var(--cp-plum-950)]">{{ ucfirst($booking->booking_type) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="px-5 py-4 text-[color:var(--cp-ink-soft)]">{{ $t('Référence', 'Reference') }}</td>
                                                <td class="px-5 py-4 font-bold text-[color:var(--cp-plum-950)]">{{ $booking->booking_number }}</td>
                                            </tr>
                                            <tr>
                                                <td class="px-5 py-4 text-[color:var(--cp-ink-soft)]">{{ $t('Date de réservation', 'Booking date') }}</td>
                                                <td class="px-5 py-4 font-bold text-[color:var(--cp-plum-950)]">{{ optional($booking->created_at)->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="px-5 py-4 text-[color:var(--cp-ink-soft)]">{{ $t('Paiement', 'Payment') }}</td>
                                                <td class="px-5 py-4 font-bold text-[color:var(--cp-plum-950)]">{{ $booking->payment_method_label }}</td>
                                            </tr>
                                            <tr>
                                                <td class="px-5 py-4 text-[color:var(--cp-ink-soft)]">{{ $t('Transaction', 'Transaction') }}</td>
                                                <td class="px-5 py-4 font-bold text-[color:var(--cp-plum-950)]">{{ $booking->payment?->transaction_id ?? ($booking->payment_transaction_id ?: $t('Non renseignée', 'Not provided')) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if($booking->eventTickets->isNotEmpty())
                                <div class="cp-panel rounded-[2rem] p-6 sm:p-8">
                                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Billets', 'Tickets') }}</p>
                                    <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Billets générés', 'Generated tickets') }}</h2>

                                    <div class="mt-5 grid gap-3">
                                        @foreach($booking->eventTickets as $ticket)
                                            <a href="{{ route('user.booking.tickets.download', ['booking' => $booking, 'ticket' => $ticket]) }}" class="flex items-center justify-between rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-5 py-4 text-sm font-bold text-[color:var(--cp-plum-950)] transition hover:border-[color:var(--cp-border-strong)] hover:bg-[#faf6ff]">
                                                <span>{{ $ticket->document_filename }}</span>
                                                <span>{{ $t('Télécharger', 'Download') }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-6">
                            <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Actions', 'Actions') }}</p>
                                <div class="mt-5 grid gap-3">
                                    <a href="{{ route('user.booking.documents.download', ['booking' => $booking, 'documentType' => 'invoice']) }}" class="cp-secondary-button !flex !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                                        <i class="fa-regular fa-file-lines text-sm"></i>
                                        <span>{{ $t('Télécharger la facture', 'Download invoice') }}</span>
                                    </a>
                                    <a href="{{ route('user.booking.documents.download', ['booking' => $booking, 'documentType' => 'receipt']) }}" class="cp-secondary-button !flex !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                                        <i class="fa-regular fa-file-invoice text-sm"></i>
                                        <span>{{ $t('Télécharger le reçu', 'Download receipt') }}</span>
                                    </a>
                                    <form action="{{ route('user.booking.resend-documents', $booking) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="cp-primary-button !flex !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                                            <i class="fa-regular fa-paper-plane text-sm"></i>
                                            <span>{{ $t('Recevoir par email', 'Receive by email') }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Montants', 'Amounts') }}</p>
                                <div class="mt-5 space-y-3 text-sm">
                                    <div class="flex items-center justify-between rounded-[1.2rem] bg-[#faf6ff] px-4 py-3">
                                        <span class="text-[color:var(--cp-ink-soft)]">{{ $t('Montant de base', 'Base amount') }}</span>
                                        <span class="font-bold text-[color:var(--cp-plum-950)]">{{ number_format((float) $booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-[1.2rem] bg-[#faf6ff] px-4 py-3">
                                        <span class="text-[color:var(--cp-ink-soft)]">{{ $t('Réduction', 'Discount') }}</span>
                                        <span class="font-bold text-[color:var(--cp-plum-950)]">-{{ number_format((float) $booking->discount_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-[1.2rem] bg-[#faf6ff] px-4 py-3">
                                        <span class="text-[color:var(--cp-ink-soft)]">{{ $t('Taxes', 'Taxes') }}</span>
                                        <span class="font-bold text-[color:var(--cp-plum-950)]">{{ number_format((float) $booking->tax_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-[1.2rem] bg-[color:var(--cp-plum-900)] px-4 py-3 text-white">
                                        <span class="font-bold">{{ $t('Total', 'Total') }}</span>
                                        <span class="text-lg font-black">{{ number_format((float) $booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($booking->payment_status === 'pending')
                                <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Paiement', 'Payment') }}</p>
                                    <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Le dossier est créé mais le paiement reste en attente.', 'The booking exists but payment is still pending.') }}</p>
                                    <a href="{{ route('payment.checkout', $booking) }}" class="cp-primary-button !mt-5 !flex !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                                        <i class="fa-solid fa-credit-card text-sm"></i>
                                        <span>{{ $t('Finaliser le paiement', 'Complete payment') }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
