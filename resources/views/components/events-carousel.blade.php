@php
    $events = $events ?? collect();
    $hasEvents = $events->isNotEmpty();
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
@endphp

@if($hasEvents)
<section class="py-16 md:py-20 bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

        {{-- Section Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-10 md:mb-14 gap-4">
            <div>
                <p class="text-xs font-black tracking-[0.3em] text-amber-500 uppercase mb-2">
                    ✦ {{ $t('Événements VIP', 'VIP Events') }}
                </p>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-gray-900 dark:text-white leading-tight">
                    {{ $t('Derniers', 'Latest') }}
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-pink-500">
                        {{ $t('événements', 'Events') }}
                    </span>
                </h2>
                <div class="mt-3 w-16 h-1 bg-gradient-to-r from-amber-500 to-pink-500 rounded-full"></div>
            </div>
            <a href="{{ route('events') }}"
               class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-amber-500 transition-colors group whitespace-nowrap">
                <span>{{ $t('Voir tous les événements', 'See all events') }}</span>
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        {{-- Events Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 md:gap-6">
            @foreach($events as $event)
            @php
                $image = $event->getFirstMediaUrl('avatar', 'normal')
                    ?: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600&h=400&fit=crop';
                $locale = app()->getLocale();
                $title = $locale === 'fr'
                    ? $event->title_fr
                    : ($event->title_en ?? $event->title_fr);
                $categoryLabel = $locale === 'fr'
                    ? ($event->category?->name_fr ?? 'Événement VIP')
                    : ($event->category?->name_en ?? $event->category?->name_fr ?? 'VIP Event');
                $monthLabel = $event->event_date
                    ? \Illuminate\Support\Str::upper($event->event_date->locale($locale)->translatedFormat('M'))
                    : null;
                $dateLabel = $event->event_date
                    ? $event->event_date->locale($locale)->translatedFormat('d M Y')
                    : null;
            @endphp

            <a href="{{ route('events.show', $event->slug ?? $event->id) }}"
               class="group flex flex-col bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-md hover:shadow-2xl border border-gray-100 dark:border-gray-700 hover:border-amber-200 dark:hover:border-amber-700 transition-all duration-300 hover:-translate-y-1">

                {{-- Image --}}
                <div class="relative overflow-hidden aspect-[4/3]">
                    <img src="{{ $image }}"
                         alt="{{ $title }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out">

                    {{-- Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    {{-- Badges --}}
                    <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                        @if($event->is_featured)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gradient-to-r from-amber-500 to-amber-400 text-black text-xs font-black rounded-full shadow-md">
                            ⭐ {{ $t('En vedette', 'Featured') }}
                        </span>
                        @endif
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-black/40 backdrop-blur-sm border border-white/20 text-white text-xs font-bold rounded-full">
                            VIP
                        </span>
                    </div>

                    {{-- Date badge (top right) --}}
                    @if($event->event_date)
                    <div class="absolute top-3 right-3 bg-white dark:bg-gray-900 rounded-xl px-2.5 py-1.5 text-center shadow-md min-w-[48px]">
                        <p class="text-xs font-black text-amber-500 uppercase leading-none">
                            {{ $monthLabel }}
                        </p>
                        <p class="text-lg font-black text-gray-900 dark:text-white leading-tight">
                            {{ $event->event_date->format('d') }}
                        </p>
                    </div>
                    @endif

                    {{-- Hover CTA overlay --}}
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-black font-black text-sm rounded-full shadow-xl transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                            {{ $t('Réserver maintenant', 'Book now') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="flex flex-col flex-1 p-4 md:p-5">

                    {{-- Category --}}
                    <p class="text-xs font-black text-amber-500 uppercase tracking-wider mb-1.5">
                        ✦ {{ $categoryLabel }}
                    </p>

                    {{-- Title --}}
                    <h3 class="text-base font-black text-gray-900 dark:text-white leading-snug line-clamp-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors mb-3 flex-1">
                        {{ $title }}
                    </h3>

                    {{-- Meta --}}
                    <div class="space-y-1.5 mb-4">
                        @if($event->event_date)
                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 text-xs">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">
                                {{ $dateLabel }}
                                @if($event->event_time)
                                    · {{ $event->event_time }}
                                @endif
                            </span>
                        </div>
                        @endif

                        @if($event->venue_name || $event->city)
                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 text-xs">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="font-medium truncate">{{ $event->venue_name ?? $event->city }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Footer: Price + Arrow --}}
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700 mt-auto">
                        <div>
                            @if($event->min_price)
                            <p class="text-xs text-gray-400 uppercase tracking-wider leading-none mb-0.5">{{ $t('À partir de', 'From') }}</p>
                            <p class="text-base font-black text-amber-500">
                                {{ \App\Helpers\CurrencyHelper::format($event->min_price) }}
                            </p>
                            @else
                            <p class="text-xs text-gray-400 font-medium">{{ $t('Tarif sur demande', 'Price on request') }}</p>
                            @endif
                        </div>

                        <span class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center group-hover:bg-amber-500 transition-colors duration-300 flex-shrink-0">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-300 group-hover:text-black group-hover:translate-x-0.5 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- View All Button --}}
        <div class="text-center mt-10 md:mt-14">
            <a href="{{ route('events') }}"
               class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-amber-500 to-pink-500 text-black font-black text-sm rounded-full hover:scale-105 hover:shadow-xl hover:shadow-amber-500/30 transition-all duration-300">
                <span>{{ $t('Voir tous les événements', 'See all events') }}</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

    </div>
</section>

@else
{{-- ===== FALLBACK ===== --}}
<section class="py-16 md:py-20 bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-10 gap-4">
            <div>
                <p class="text-xs font-black tracking-[0.3em] text-amber-500 uppercase mb-2">✦ {{ $t('Événements VIP', 'VIP Events') }}</p>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-gray-900 dark:text-white leading-tight">
                    {{ $t('Derniers', 'Latest') }}
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-pink-500">{{ $t('événements', 'Events') }}</span>
                </h2>
                <div class="mt-3 w-16 h-1 bg-gradient-to-r from-amber-500 to-pink-500 rounded-full"></div>
            </div>
        </div>

        <div class="rounded-3xl bg-gradient-to-br from-amber-50 to-pink-50 dark:from-gray-800 dark:to-gray-800 border border-amber-100 dark:border-gray-700 p-12 md:p-20 text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-amber-400 to-pink-500 rounded-3xl flex items-center justify-center shadow-xl">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white mb-3">
                {{ $t('Aucun événement disponible', 'No Events Available') }}
            </h3>
            <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
                {{ $t('Restez à l’écoute, de nouveaux événements VIP arrivent bientôt.', 'Stay tuned! Exciting VIP events are coming soon.') }}
            </p>
            <a href="{{ route('contact') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-amber-500 to-pink-500 text-black font-black rounded-full hover:scale-105 transition-transform shadow-xl">
                <span>{{ $t('Nous contacter', 'Contact Us') }}</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif
