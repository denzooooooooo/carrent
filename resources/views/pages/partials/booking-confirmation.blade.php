@php
    $paymentState = match ($booking->payment_status) {
        'paid' => ['label' => 'Paiement validé', 'tone' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'copy' => 'Votre règlement est confirmé. Les documents peuvent être traités immédiatement.'],
        'pending' => ['label' => 'Paiement en attente', 'tone' => 'bg-amber-100 text-amber-800 border-amber-200', 'copy' => 'Votre dossier est créé. La confirmation finale dépend du règlement et de la vérification éventuelle.'],
        'failed' => ['label' => 'Paiement à reprendre', 'tone' => 'bg-red-100 text-red-800 border-red-200', 'copy' => 'Le dossier existe, mais le paiement doit être repris pour finaliser la réservation.'],
        default => ['label' => ucfirst($booking->payment_status ?? 'En cours'), 'tone' => 'bg-slate-100 text-slate-800 border-slate-200', 'copy' => 'Notre équipe suit votre dossier et vous recontacte si une action est nécessaire.'],
    };

    $nextSteps = $nextSteps ?? [
        ['title' => 'Email récapitulatif', 'body' => 'Un récapitulatif de la réservation est envoyé à l’adresse renseignée.'],
        ['title' => 'Validation opérationnelle', 'body' => 'L’équipe contrôle le paiement, les disponibilités et les documents associés.'],
        ['title' => 'Support prioritaire', 'body' => 'En cas d’urgence, le support vous répond par téléphone, email ou WhatsApp.'],
    ];
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.4rem] bg-gradient-to-br from-[#1d102a] via-[#4b2870] to-[#d9a441] px-5 py-8 text-white shadow-[0_28px_90px_rgba(34,18,52,0.24)] sm:px-7 sm:py-10">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px] lg:items-end">
                    <div class="max-w-3xl">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-[color:var(--cp-gold-300)] backdrop-blur">
                            <span class="h-2.5 w-2.5 rounded-full bg-current"></span>
                            {{ $serviceLabel }}
                        </span>
                        <h1 class="mt-4 text-3xl font-black sm:text-4xl">{{ $heroTitle ?? 'Votre réservation est enregistrée.' }}</h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-white/82 sm:text-base">
                            {{ $heroCopy ?? $paymentState['copy'] }}
                        </p>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ $primaryActionUrl }}" class="cp-primary-button !w-full sm:!w-auto !bg-[#f0bb61] !text-[#2a163d] hover:!bg-[#e4ad54]">
                                <i class="fa-solid fa-arrow-right text-sm"></i>
                                <span>{{ $primaryActionLabel }}</span>
                            </a>
                            <a href="{{ $secondaryActionUrl }}" class="cp-secondary-button !w-full sm:!w-auto !border-white/18 !bg-white/10 !text-white hover:!bg-white/14">
                                <i class="fa-solid fa-grid-2 text-sm"></i>
                                <span>{{ $secondaryActionLabel }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="rounded-[1.7rem] border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/60">Référence</p>
                            <p class="mt-3 text-2xl font-black">{{ $booking->booking_number }}</p>
                            <p class="mt-2 text-sm text-white/78">Conservez cette référence pour tout échange avec l’équipe.</p>
                        </div>
                        <div class="rounded-[1.7rem] border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/60">Montant</p>
                            <p class="mt-3 text-2xl font-black">{{ \App\Helpers\CurrencyHelper::format($booking->final_amount) }}</p>
                            <p class="mt-2 text-sm text-white/78">Statut actuel: {{ strtolower($paymentState['label']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-section">
        <div class="cp-shell">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_360px]">
                <div class="space-y-6">
                    <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">Statut dossier</p>
                                <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $booking->title }}</h2>
                            </div>
                            <span class="inline-flex items-center rounded-full border px-4 py-2 text-xs font-black uppercase tracking-[0.18em] {{ $paymentState['tone'] }}">
                                {{ $paymentState['label'] }}
                            </span>
                        </div>
                        <p class="mt-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            {{ $paymentState['copy'] }}
                        </p>
                    </div>

                    @include('pages.payment._booking-summary', ['booking' => $booking, 'heading' => 'Dossier enregistré'])

                    <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">Suite du parcours</p>
                        <div class="mt-5 grid gap-4 md:grid-cols-3">
                            @foreach($nextSteps as $step)
                                <div class="rounded-[1.5rem] border border-[color:var(--cp-border)] bg-[rgba(255,255,255,0.78)] p-5">
                                    <div class="mb-3 inline-flex h-11 w-11 items-center justify-center rounded-full bg-[rgba(75,40,112,0.08)] text-[color:var(--cp-plum-800)]">
                                        <i class="fa-solid fa-check text-sm"></i>
                                    </div>
                                    <h3 class="text-base font-black text-[color:var(--cp-plum-950)]">{{ $step['title'] }}</h3>
                                    <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $step['body'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="cp-panel rounded-[2rem] p-6 sm:p-7 xl:sticky xl:top-28">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">Besoin d’aide</p>
                        <h2 class="mt-3 text-2xl font-black text-[color:var(--cp-plum-950)]">L’équipe Carré Premium suit votre dossier.</h2>
                        <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            Pour modifier une information, envoyer une preuve de paiement ou accélérer le traitement, utilisez un canal direct.
                        </p>

                        <div class="mt-5 space-y-3">
                            <a href="{{ config('carre_premium.contact.mobile_link') }}" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)] transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-phone text-[color:var(--cp-plum-800)]"></i> {{ config('carre_premium.contact.mobile_display') }}</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                            </a>
                            <a href="{{ config('carre_premium.contact.whatsapp_url') }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)] transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                                <span class="flex items-center gap-3"><i class="fa-brands fa-whatsapp text-[color:var(--cp-success)]"></i> {{ config('carre_premium.contact.whatsapp_display') }}</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                            </a>
                            <a href="mailto:{{ config('carre_premium.contact.support_email') }}" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)] transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-envelope text-[color:var(--cp-plum-800)]"></i> {{ config('carre_premium.contact.support_email') }}</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                            </a>
                        </div>

                        @if($booking->has_billing_documents)
                            <div class="mt-6 rounded-[1.5rem] border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-800">
                                <p class="font-black uppercase tracking-[0.18em]">Documents</p>
                                <p class="mt-2 leading-7">Les documents de facturation sont déjà générés pour cette réservation.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
