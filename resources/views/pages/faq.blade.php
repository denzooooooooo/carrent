@extends('layouts.app')

@section('title', 'FAQ - Carré Premium')
@section('meta_description', 'Retrouvez les réponses essentielles sur les réservations, paiements, documents et demandes premium chez Carré Premium.')
@section('meta_keywords', 'faq carré premium, aide réservation, paiement, documents, support client')
@section('og_title', 'FAQ - Carré Premium')
@section('og_description', 'Toutes les réponses utiles sur les réservations, paiements et documents Carré Premium.')

@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $faqGroups = [
        [
            'title' => $t('Réservations', 'Bookings'),
            'items' => [
                ['q' => $t('Comment réserver un événement ?', 'How do I book an event?'), 'a' => $t('Ouvrez la fiche événement, choisissez votre offre ou votre zone, renseignez les coordonnées demandées puis poursuivez vers le paiement.', 'Open the event page, select your offer or zone, fill in the requested details and continue to payment.')],
                ['q' => $t('Puis-je réserver sans créer de compte ?', 'Can I book without creating an account?'), 'a' => $t('Oui pour plusieurs services, mais créer un compte facilite le suivi des documents, des paiements et des confirmations.', 'Yes for several services, but creating an account makes it easier to track documents, payments and confirmations.')],
                ['q' => $t('Comment retrouver une réservation déjà commencée ?', 'How do I recover a started booking?'), 'a' => $t('Si le dossier a déjà été créé, reconnectez-vous ou utilisez le lien reçu par email pour reprendre votre réservation.', 'If the booking record already exists, sign in or use the link received by email to resume your booking.')],
            ],
        ],
        [
            'title' => $t('Paiement', 'Payment'),
            'items' => [
                ['q' => $t('Quels moyens de paiement sont proposés ?', 'Which payment methods are available?'), 'a' => $t('Selon le service, le site peut proposer CinetPay, Mobile Money, carte bancaire ou virement bancaire.', 'Depending on the service, the site may offer CinetPay, Mobile Money, card payment or bank transfer.')],
                ['q' => $t('Que se passe-t-il si le paiement échoue ?', 'What happens if payment fails?'), 'a' => $t('Le dossier reste généralement en attente. Vous pouvez relancer le paiement depuis votre espace ou contacter un conseiller.', 'The booking usually stays pending. You can restart payment from your account or contact an advisor.')],
                ['q' => $t('Comment envoyer une preuve de virement ?', 'How do I send bank transfer proof?'), 'a' => $t('Lorsque le règlement se fait par virement, vous pouvez envoyer le justificatif directement depuis les instructions de paiement.', 'When payment is made by bank transfer, you can upload the proof directly from the payment instructions.')],
            ],
        ],
        [
            'title' => $t('Documents', 'Documents'),
            'items' => [
                ['q' => $t('Où télécharger ma facture ?', 'Where can I download my invoice?'), 'a' => $t('Depuis Mes réservations ou le détail d’une réservation payée, la facture et le reçu sont disponibles au téléchargement.', 'From My bookings or a paid booking detail page, the invoice and receipt are available for download.')],
                ['q' => $t('Quand les billets sont-ils disponibles ?', 'When are tickets available?'), 'a' => $t('Ils sont générés une fois le paiement confirmé et le dossier finalisé selon le type de service.', 'They are generated once payment is confirmed and the booking is finalized depending on the service.')],
                ['q' => $t('Puis-je recevoir mes documents par email ?', 'Can I receive my documents by email?'), 'a' => $t('Oui. Un bouton dans le détail de réservation permet de renvoyer les documents.', 'Yes. A button inside the booking detail lets you resend documents by email.')],
            ],
        ],
        [
            'title' => $t('Support', 'Support'),
            'items' => [
                ['q' => $t('Comment contacter l’équipe rapidement ?', 'How do I contact the team quickly?'), 'a' => $t('Le plus direct reste le téléphone, WhatsApp ou le formulaire contact selon l’urgence de votre demande.', 'The fastest route remains phone, WhatsApp or the contact form depending on the urgency of your request.')],
                ['q' => $t('Proposez-vous du sur-mesure ?', 'Do you offer bespoke services?'), 'a' => $t('Oui. Décrivez votre besoin dans le formulaire contact ou contactez un conseiller pour être orienté.', 'Yes. Describe your need in the contact form or contact an advisor for guidance.')],
            ],
        ],
    ];
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($faqGroups)->flatMap(fn ($group) => collect($group['items'])->map(fn ($item) => [
            '@type' => 'Question',
            'name' => $item['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $item['a'],
            ],
        ]))->values()->all(),
    ];
@endphp

@push('head')
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.35rem] bg-gradient-to-br from-[#22112f] via-[#4d2973] to-[#d9a64d] px-6 py-8 text-white shadow-[0_28px_90px_rgba(41,20,58,0.22)] sm:px-8 sm:py-10">
                <div class="max-w-3xl">
                    <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                        <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                        <span>{{ $t('FAQ', 'FAQ') }}</span>
                    </div>
                    <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl lg:text-5xl">{{ $t('Les réponses utiles avant de réserver, payer ou récupérer vos documents.', 'Useful answers before you book, pay or retrieve your documents.') }}</h1>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-white/84 sm:text-base">
                        {{ $t('Retrouvez les informations essentielles sur les réservations, les paiements, les billets, les factures et les échanges avec l’équipe.', 'Find the key information about bookings, payments, tickets, invoices and exchanges with the team.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-overlap">
        <div class="cp-shell">
            <div class="cp-panel rounded-[2rem] px-5 py-6 sm:px-7 sm:py-8">
                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_240px] lg:items-end">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Recherche rapide', 'Quick search') }}</p>
                        <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Trouvez la bonne réponse en quelques secondes', 'Find the right answer in seconds') }}</h2>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[color:var(--cp-ink-muted)]"></i>
                        <input id="faq-search" type="search" placeholder="{{ $t('Chercher une question...', 'Search a question...') }}" class="w-full rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white py-3 pl-11 pr-4 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                    </div>
                </div>

                <div class="mt-6 space-y-5">
                    @foreach($faqGroups as $group)
                        <section class="rounded-[1.8rem] border border-[color:var(--cp-border)] bg-white/80 p-4 sm:p-5">
                            <h3 class="text-xl font-black text-[color:var(--cp-plum-950)]">{{ $group['title'] }}</h3>
                            <div class="mt-4 space-y-3">
                                @foreach($group['items'] as $index => $item)
                                    <details class="event-accordion" data-faq-item data-search="{{ Str::lower($group['title'] . ' ' . $item['q'] . ' ' . $item['a']) }}" @if($loop->first && $loop->parent->first) open @endif>
                                        <summary class="cursor-pointer list-none px-4 py-4 text-sm font-black text-[color:var(--cp-plum-950)] sm:text-base">
                                            {{ $item['q'] }}
                                        </summary>
                                        <div class="px-4 pb-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                            {{ $item['a'] }}
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>

                <div id="faq-empty-state" class="mt-6 hidden rounded-[1.8rem] border border-dashed border-[color:var(--cp-border-strong)] bg-[#faf6ff] px-6 py-10 text-center">
                    <p class="text-xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Aucun résultat pour cette recherche.', 'No result for this search.') }}</p>
                    <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Essayez un mot plus large ou contactez directement l’équipe.', 'Try a broader term or contact the team directly.') }}</p>
                    <a href="{{ route('contact') }}" class="cp-primary-button !mt-5">{{ $t('Contacter l’équipe', 'Contact the team') }}</a>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-section-lg">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.1rem] bg-gradient-to-r from-[#26153a] via-[#4d2d72] to-[#d7a147] px-5 py-8 text-white shadow-[0_24px_70px_rgba(41,20,58,0.18)] sm:px-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('Toujours bloqué ?', 'Still blocked?') }}</p>
                        <h2 class="mt-3 text-2xl font-black sm:text-3xl">{{ $t('Passez à un conseiller si la réponse dépend de votre dossier.', 'Switch to an advisor if the answer depends on your booking.') }}</h2>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('contact') }}" class="cp-primary-button !bg-[#f0bb61] !text-[#2a163d] hover:!bg-[#e2aa54]">{{ $t('Écrire au support', 'Write to support') }}</a>
                        <a href="{{ config('carre_premium.contact.mobile_link') }}" class="cp-secondary-button !border-white/25 !bg-white/10 !text-white hover:!bg-white/15">{{ $t('Appeler maintenant', 'Call now') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('faq-search');
    const items = Array.from(document.querySelectorAll('[data-faq-item]'));
    const emptyState = document.getElementById('faq-empty-state');

    if (!input || !items.length || !emptyState) {
        return;
    }

    input.addEventListener('input', function () {
        const query = input.value.trim().toLowerCase();
        let visibleCount = 0;

        items.forEach(function (item) {
            const haystack = item.dataset.search || '';
            const visible = !query || haystack.includes(query);
            item.style.display = visible ? '' : 'none';
            visibleCount += visible ? 1 : 0;
        });

        emptyState.classList.toggle('hidden', visibleCount > 0);
    });
});
</script>
@endpush
