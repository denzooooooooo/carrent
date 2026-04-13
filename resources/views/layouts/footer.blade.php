@php
    $companyName = config('carre_premium.company.name');
    $companyAddress = config('carre_premium.company.address');
    $companyCity = config('carre_premium.company.city');
    $companyCountry = config('carre_premium.company.country');
    $supportEmail = config('carre_premium.contact.support_email');
    $landlineDisplay = config('carre_premium.contact.landline_display');
    $landlineLink = config('carre_premium.contact.landline_link');
    $mobileDisplay = config('carre_premium.contact.mobile_display');
    $mobileLink = config('carre_premium.contact.mobile_link');
    $whatsAppUrl = config('carre_premium.contact.whatsapp_url');

    $serviceLinks = [
        ['label' => 'Événements', 'url' => route('events')],
        ['label' => 'Packages', 'url' => route('packages')],
        ['label' => 'Location', 'url' => route('location')],
        ['label' => 'Vols accompagnés', 'url' => route('flights.index')],
    ];

    $companyLinks = [
        ['label' => 'À propos', 'url' => route('about')],
        ['label' => 'Contact', 'url' => route('contact')],
        ['label' => 'FAQ', 'url' => route('faq')],
        ['label' => 'Partenariat', 'url' => route('partnership')],
    ];

    $legalLinks = [
        ['label' => 'Mentions & CGU', 'url' => route('terms')],
        ['label' => 'Confidentialité', 'url' => route('privacy')],
        ['label' => 'Cookies', 'url' => route('cookies')],
    ];
@endphp

<footer class="theme-shell-footer px-3 pb-4 pt-12 sm:px-4 sm:pt-16">
    <div class="cp-shell">
        <div class="overflow-hidden rounded-[2.25rem] bg-gradient-to-br from-[#211130] via-[#3a1f57] to-[#7a492d] text-white shadow-[0_30px_90px_rgba(33,17,48,0.28)]">
            <div class="border-b border-white/10 px-5 py-8 sm:px-8 lg:px-10 lg:py-10">
                <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-end">
                    <div>
                        <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                            <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                            <span>Support premium</span>
                        </div>
                        <h2 class="mt-4 max-w-2xl text-3xl font-black leading-tight sm:text-4xl">
                            Un client doit comprendre l’offre, trouver l’action suivante et nous contacter sans friction.
                        </h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                            Réservation accompagnée, tunnel de paiement clarifié et support humain visible sur chaque parcours.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <a href="{{ route('contact') }}" class="cp-primary-button !w-full !justify-center !bg-[#f0bb61] !text-[#2a163d] hover:!bg-[#e2aa54]">
                            <i class="fa-regular fa-envelope text-sm"></i>
                            <span>Demander un devis</span>
                        </a>
                        <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="cp-secondary-button !w-full !justify-center !border-white/20 !bg-white/10 !text-white hover:!bg-white/16">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            <span>WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="px-5 py-8 sm:px-8 lg:px-10 lg:py-10">
                <div class="grid gap-10 lg:grid-cols-[1.1fr_repeat(3,minmax(0,1fr))]">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-14 w-14 items-center justify-center overflow-hidden rounded-2xl border border-white/15 bg-white/10">
                                <img src="{{ asset('logos/logo2.jpg') }}" alt="{{ $companyName }}" class="h-full w-full object-cover">
                            </span>
                            <div>
                                <p class="text-lg font-black tracking-[0.12em]">{{ strtoupper($companyName) }}</p>
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-white/60">Travel, Events, Concierge</p>
                            </div>
                        </div>

                        <p class="mt-5 max-w-sm text-sm leading-7 text-white/70">
                            Billetterie événementielle, packages, location et demandes de vols traitées avec un accompagnement plus clair pour vos clients.
                        </p>

                        <div class="mt-6 grid gap-3">
                            <a href="{{ $landlineLink }}" class="flex items-center gap-3 rounded-[1.2rem] border border-white/10 bg-white/10 px-4 py-3 text-sm font-semibold text-white/90 transition hover:bg-white/15">
                                <i class="fa-solid fa-phone-volume text-[color:var(--cp-gold-300)]"></i>
                                <span>{{ $landlineDisplay }}</span>
                            </a>
                            <a href="{{ $mobileLink }}" class="flex items-center gap-3 rounded-[1.2rem] border border-white/10 bg-white/10 px-4 py-3 text-sm font-semibold text-white/90 transition hover:bg-white/15">
                                <i class="fa-solid fa-mobile-screen text-[color:var(--cp-gold-300)]"></i>
                                <span>{{ $mobileDisplay }}</span>
                            </a>
                            <a href="mailto:{{ $supportEmail }}" class="flex items-center gap-3 rounded-[1.2rem] border border-white/10 bg-white/10 px-4 py-3 text-sm font-semibold text-white/90 transition hover:bg-white/15">
                                <i class="fa-regular fa-envelope text-[color:var(--cp-gold-300)]"></i>
                                <span>{{ $supportEmail }}</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-black uppercase tracking-[0.24em] text-white/60">Services</h3>
                        <ul class="mt-5 space-y-3">
                            @foreach($serviceLinks as $item)
                                <li>
                                    <a href="{{ $item['url'] }}" class="inline-flex items-center gap-3 text-sm font-semibold text-white/80 transition hover:text-white">
                                        <span class="h-2 w-2 rounded-full bg-[color:var(--cp-gold-300)]"></span>
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-black uppercase tracking-[0.24em] text-white/60">Pages utiles</h3>
                        <ul class="mt-5 space-y-3">
                            @foreach($companyLinks as $item)
                                <li>
                                    <a href="{{ $item['url'] }}" class="inline-flex items-center gap-3 text-sm font-semibold text-white/80 transition hover:text-white">
                                        <span class="h-2 w-2 rounded-full bg-white/35"></span>
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-black uppercase tracking-[0.24em] text-white/60">Adresse & disponibilité</h3>
                        <div class="mt-5 space-y-4 text-sm leading-7 text-white/80">
                            <p>{{ $companyAddress }}<br>{{ $companyCity }}, {{ $companyCountry }}</p>
                            <p>Support commercial visible et joignable tous les jours.</p>
                            <div class="rounded-[1.4rem] border border-white/10 bg-white/10 px-4 py-4">
                                <p class="text-xs font-black uppercase tracking-[0.18em] text-white/50">Canal recommandé</p>
                                <p class="mt-2 text-base font-black text-white">WhatsApp ou appel direct</p>
                                <p class="mt-1 text-sm text-white/70">Pour une demande urgente, un montant élevé ou un besoin sur mesure.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10 px-5 py-5 sm:px-8 lg:px-10">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <p class="text-sm font-medium text-white/70">
                        © {{ date('Y') }} {{ $companyName }}. Tous droits réservés.
                    </p>

                    <div class="flex flex-wrap gap-3">
                        @foreach($legalLinks as $item)
                            <a href="{{ $item['url'] }}" class="rounded-full border border-white/10 px-4 py-2 text-sm font-semibold text-white/80 transition hover:bg-white/10 hover:text-white">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
