@extends('layouts.app')

@section('title', 'Partenariat - Carré Premium')
@section('meta_description', 'Proposez un partenariat à Carré Premium pour des collaborations autour des voyages, événements, hébergements et services premium.')
@section('meta_keywords', 'partenariat carré premium, partenariat voyage premium, partenariat événements, collaboration conciergerie')
@section('og_title', 'Partenariat - Carré Premium')
@section('og_description', 'Déposez une demande de partenariat auprès de Carré Premium.')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $supportEmail = config('carre_premium.contact.support_email');
    $landlineDisplay = config('carre_premium.contact.landline_display');
    $mobileDisplay = config('carre_premium.contact.mobile_display');
    $companyAddress = collect([
        config('carre_premium.company.address'),
        config('carre_premium.company.city'),
        config('carre_premium.company.country'),
    ])->filter()->implode(', ');
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.35rem] bg-gradient-to-br from-[#22112f] via-[#4d2973] to-[#d9a64d] px-6 py-8 text-white shadow-[0_28px_90px_rgba(41,20,58,0.22)] sm:px-8 sm:py-10">
                <div class="max-w-3xl">
                    <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                        <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                        <span>{{ $t('Partenariats', 'Partnerships') }}</span>
                    </div>
                    <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl lg:text-5xl">{{ $t('Créer des partenariats utiles, crédibles et directement exploitables.', 'Build partnerships that are useful, credible and directly actionable.') }}</h1>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-white/84 sm:text-base">
                        {{ $t('Hôtels, restaurants, agences, opérateurs, lieux ou prestataires premium: cette page pose un cadre clair pour soumettre une collaboration.', 'Hotels, restaurants, agencies, operators, venues or premium providers: this page offers a clear framework to submit a collaboration.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-overlap">
        <div class="cp-shell">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
                <div class="space-y-6">
                    <div class="grid gap-5 md:grid-cols-3">
                        @foreach([
                            ['title' => $t('Visibilité qualifiée', 'Qualified visibility'), 'text' => $t('Présenter votre offre à une audience déjà orientée premium.', 'Present your offer to an audience already oriented toward premium services.')],
                            ['title' => $t('Parcours plus clair', 'Clearer journey'), 'text' => $t('Des offres mieux reliées aux besoins réels et à la conversion.', 'Offers better tied to actual customer needs and conversion.')],
                            ['title' => $t('Relation directe', 'Direct relationship'), 'text' => $t('Un échange rapide avec l’équipe pour qualifier la collaboration.', 'A direct exchange with the team to qualify the collaboration.')],
                        ] as $benefit)
                            <div class="cp-panel rounded-[1.8rem] p-6">
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Bénéfice', 'Benefit') }}</p>
                                <h2 class="mt-3 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $benefit['title'] }}</h2>
                                <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $benefit['text'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="cp-panel rounded-[2rem] p-6 sm:p-8">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Demande de partenariat', 'Partnership request') }}</p>
                        <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Présentez votre activité et votre proposition', 'Present your business and your proposal') }}</h2>

                        @if(session('success'))
                            <div class="mt-5 rounded-[1.4rem] border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mt-5 rounded-[1.4rem] border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.store') }}" class="mt-6 grid gap-4">
                            @csrf
                            <input type="hidden" name="subject" value="partnership">

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="company_name" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Entreprise', 'Company') }}</label>
                                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                    @error('company_name') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="name" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Contact principal', 'Primary contact') }}</label>
                                    <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                    @error('name') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="email" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Email', 'Email') }}</label>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                    @error('email') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="phone" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Téléphone', 'Phone') }}</label>
                                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                    @error('phone') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-[220px_minmax(0,1fr)]">
                                <div>
                                    <label for="partnership_type" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Type', 'Type') }}</label>
                                    <select id="partnership_type" name="partnership_type" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        <option value="hotel" @selected(old('partnership_type') === 'hotel')>{{ $t('Hôtel / Resort', 'Hotel / Resort') }}</option>
                                        <option value="restaurant" @selected(old('partnership_type') === 'restaurant')>{{ $t('Restaurant', 'Restaurant') }}</option>
                                        <option value="travel_agency" @selected(old('partnership_type') === 'travel_agency')>{{ $t('Agence / opérateur', 'Agency / operator') }}</option>
                                        <option value="event_venue" @selected(old('partnership_type') === 'event_venue')>{{ $t('Lieu / événementiel', 'Venue / events') }}</option>
                                        <option value="other" @selected(old('partnership_type') === 'other')>{{ $t('Autre', 'Other') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="website" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Site web', 'Website') }}</label>
                                    <input id="website" name="website" type="url" value="{{ old('website') }}" class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]" placeholder="https://...">
                                </div>
                            </div>

                            <div>
                                <label for="message" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Votre proposition', 'Your proposal') }}</label>
                                <textarea id="message" name="message" rows="7" required class="w-full rounded-[1.5rem] border border-[color:var(--cp-border)] bg-white px-4 py-4 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]" placeholder="{{ $t('Expliquez votre activité, votre positionnement et ce que vous proposez à Carré Premium.', 'Explain your business, positioning and what you are proposing to Carré Premium.') }}">{{ old('message') }}</textarea>
                                @error('message') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="cp-primary-button !flex !w-full !justify-center sm:!w-auto">
                                <i class="fa-regular fa-paper-plane text-sm"></i>
                                <span>{{ $t('Envoyer la demande', 'Send request') }}</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="cp-panel rounded-[2rem] p-6">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Équipe partenariat', 'Partnership team') }}</p>
                        <div class="mt-5 space-y-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            <div>
                                <p class="font-black text-[color:var(--cp-plum-950)]">{{ $t('Téléphone', 'Phone') }}</p>
                                <p>{{ $landlineDisplay }} · {{ $mobileDisplay }}</p>
                            </div>
                            <div>
                                <p class="font-black text-[color:var(--cp-plum-950)]">{{ $t('Email', 'Email') }}</p>
                                <p>{{ $supportEmail }}</p>
                            </div>
                            <div>
                                <p class="font-black text-[color:var(--cp-plum-950)]">{{ $t('Adresse', 'Address') }}</p>
                                <p>{{ $companyAddress }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="cp-panel rounded-[2rem] p-6">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Ce qui aide vraiment', 'What helps most') }}</p>
                        <ul class="mt-4 space-y-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            <li>{{ $t('Présenter clairement votre offre et votre cible.', 'Present your offer and target clearly.') }}</li>
                            <li>{{ $t('Préciser les conditions commerciales ou logistiques.', 'Specify commercial or logistical conditions.') }}</li>
                            <li>{{ $t('Partager un site, une brochure ou des références utiles.', 'Share a site, brochure or useful references.') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
