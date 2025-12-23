@php
    $events = $events ?? collect();
    $hasEvents = $events->isNotEmpty();
@endphp

@if($hasEvents)
<section class="relative w-full overflow-hidden bg-gradient-to-r from-black via-red-900 to-black" id="eventsHeroCarousel" style="height: 550px;">
    <!-- TRACK -->
    <div id="eventsHeroTrack" class="flex transition-transform duration-700 ease-in-out" style="transform: translateX(0%)" style="padding-top: -200px;">
        @foreach($events as $event)
            @php
                $image = $event->getFirstMediaUrl('avatar', 'normal') ?: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=1600';
                $locale = app()->getLocale();
                $title = $locale === 'fr' ? $event->title_fr : ($event->title_en ?? $event->title_fr);
                
                // Calculer le pourcentage de places disponibles
                $availabilityPercent = $event->total_seats > 0 
                    ? round(($event->available_seats / $event->total_seats) * 100) 
                    : 0;
                
                // Déterminer la couleur selon la disponibilité
                $availabilityColor = $availabilityPercent > 50 ? 'green' : ($availabilityPercent > 20 ? 'amber' : 'red');
            @endphp

            <!-- SLIDE -->
                <div class="w-full flex-shrink-0 relative h-full">
                <!-- OVERLAY GRADIENT avec effet sombre -->
                <div class="absolute inset-0 bg-gradient-to-r from-black via-red-900/90 to-black"></div>

                <!-- CONTENT -->
                <div class="relative z-10 h-[550px] flex items-start pt-6 overflow-hidden">
                    <div class="container mx-auto px-6 lg:px-12 max-w-7xl">
                        <div class="grid lg:grid-cols-2 gap-12 items-start h-[420px] overflow-hidden">
                            
                            <!-- LEFT: Event Details -->
                            <div class="text-white space-y-5 animate-fade-in relative h-[380px] overflow-hidden pointer-events-auto">
                                
                                <!-- BADGES ROW -->
                                <div class="flex flex-wrap items-center gap-3 max-h-[56px] overflow-hidden">
                                    <span class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-pink-500 text-black text-sm font-black rounded-full shadow-lg transform hover:scale-105 transition-transform">
                                        ⭐ {{ __('VIP EVENT') }}
                                    </span>
                                    
                                    @if($event->category)
                                        <span class="px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 text-white text-sm font-semibold rounded-full hover:bg-white/20 transition-colors">
                                            {{ $event->category->name ?? '' }}
                                        </span>
                                    @endif
                                    
                                    @if($event->type)
                                        <span class="px-4 py-2 bg-purple-500/20 backdrop-blur-md border border-purple-400/30 text-purple-200 text-sm font-semibold rounded-full hover:bg-purple-500/30 transition-colors">
                                            {{ $event->type->name ?? '' }}
                                        </span>
                                    @endif
                                </div>

                                <!-- TITLE -->
                                <h2 
                                    class="title-clamp font-black leading-tight max-h-[160px] overflow-hidden
                                           bg-gradient-to-r from-white via-amber-100 to-white 
                                           bg-clip-text text-transparent drop-shadow-2xl
                                           text-[clamp(1.6rem,4vw,4.5rem)] md:text-[clamp(2rem,5vw,5.5rem)]">
                                    {{ $title }}
                                </h2>
                                <p class="text-sm uppercase tracking-widest text-amber-400 font-semibold">
                                    {{ __('VIP Event') }}
                                </p>

                                <!-- EVENT INFO GRID -->
                                <div class="grid grid-cols-2 gap-4 pt-4 max-h-[260px] overflow-hidden">
                                    <!-- DATE & TIME -->
                                    @if($event->event_date)
                                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 hover:bg-white/15 transition-all">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-white/60 font-semibold uppercase">{{ __('Date') }}</p>
                                                <p class="text-sm font-bold text-white">
                                                    {{ $event->event_date->format('d M Y') }}
                                                    @if($event->event_time)
                                                        <span class="text-amber-400">{{ $event->event_time }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- LOCATION -->
                                    @if($event->venue_name || $event->city)
                                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 hover:bg-white/15 transition-all">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <div class="overflow-hidden">
                                                <p class="text-xs text-white/60 font-semibold uppercase">{{ __('Location') }}</p>
                                                <p class="text-sm font-bold text-white truncate">
                                                    {{ $event->venue_name ?? $event->city }}
                                                </p>
                                                @if($event->city && $event->venue_name)
                                                    <p class="text-xs text-white/70">{{ $event->city }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- AVAILABILITY -->
                                    @if($event->total_seats > 0)
                                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 hover:bg-white/15 transition-all">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-gradient-to-br from-{{ $availabilityColor }}-500 to-{{ $availabilityColor }}-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-white/60 font-semibold uppercase">{{ __('Availability') }}</p>
                                                <p class="text-sm font-bold text-{{ $availabilityColor }}-400">
                                                    {{ $event->available_seats }} / {{ $event->total_seats }}
                                                </p>
                                                <div class="w-full bg-white/20 rounded-full h-1.5 mt-1">
                                                    <div class="bg-{{ $availabilityColor }}-400 h-1.5 rounded-full transition-all" style="width: {{ $availabilityPercent }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- PRICE -->
                                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 hover:bg-white/15 transition-all">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-white/60 font-semibold uppercase">{{ __('Price') }}</p>
                                                <p class="text-sm font-bold text-amber-400">
                                                    @if($event->min_price)
                                                        {{ __('From') }} {{ \App\Helpers\CurrencyHelper::format($event->min_price) }}
                                                    @else
                                                        {{ __('On request') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CTA BUTTONS -->
                                <div class="flex flex-wrap items-center gap-4 pt-6 max-h-[72px] overflow-hidden">
                                    <a href="{{ route('events.show', $event->slug ?? $event->id) }}" 
                                       class="group inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-amber-500 to-pink-500 text-black font-black text-lg rounded-full hover:scale-105 hover:shadow-2xl hover:shadow-amber-500/50 transition-all duration-300">
                                        <span>{{ __('Book Now') }}</span>
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    </a>
                                    
                                    <a href="{{ route('events.show', $event->slug ?? $event->id) }}" 
                                       class="inline-flex items-center gap-2 px-6 py-4 bg-white/10 backdrop-blur-md border-2 border-white/30 text-white font-bold rounded-full hover:bg-white/20 hover:border-white/50 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ __('More Info') }}</span>
                                    </a>
                                </div>
                            </div>

                            <!-- RIGHT: Event Image Card (Desktop only) -->
                            <div class="hidden lg:block">
                                <div class="relative group">
                                    <div class="absolute -inset-1 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-500 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-opacity"></div>
                                    <div class="relative bg-black/40 backdrop-blur-xl border border-white/20 rounded-3xl p-6 transform group-hover:scale-105 transition-transform duration-500">
                                        <img src="{{ $image }}" alt="{{ $title }}" 
                                             class="w-full h-[384px] object-cover rounded-2xl shadow-2xl">
                                        
                                        @if($event->is_featured)
                                        <div class="absolute top-10 right-10 px-4 py-2 bg-gradient-to-r from-amber-500 to-pink-500 text-black text-xs font-black rounded-full shadow-lg animate-pulse">
                                            ⭐ {{ __('FEATURED') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- NAVIGATION ARROWS -->
    <button id="eventsPrev" 
            class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 w-14 h-14 md:w-16 md:h-16 rounded-full bg-white/10 backdrop-blur-md border-2 border-white/30 flex items-center justify-center text-white text-2xl font-bold hover:bg-white/20 hover:scale-110 transition-all duration-300 z-20 group">
        <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    <button id="eventsNext" 
            class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 w-14 h-14 md:w-16 md:h-16 rounded-full bg-white/10 backdrop-blur-md border-2 border-white/30 flex items-center justify-center text-white text-2xl font-bold hover:bg-white/20 hover:scale-110 transition-all duration-300 z-20 group">
        <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
        </svg>
    </button>

    <!-- DOTS INDICATOR -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3 z-20">
        @foreach($events as $i => $event)
            <button class="events-dot w-3 h-3 rounded-full bg-white/30 hover:bg-white/60 transition-all duration-300 hover:scale-125" 
                    data-index="{{ $i }}"></button>
        @endforeach
    </div>

    <!-- THUMBNAILS (Desktop only) -->
    <div class="absolute bottom-20 left-1/2 -translate-x-1/2 hidden xl:flex gap-4 z-20">
        @foreach($events as $i => $event)
            <button data-index="{{ $i }}" 
                    class="events-thumb group relative w-24 h-16 rounded-xl overflow-hidden border-3 border-white/30 hover:border-amber-400 cursor-pointer transition-all duration-300 hover:scale-110 hover:shadow-xl">
                <img src="{{ $event->getFirstMediaUrl('avatar', 'thumb') ?: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=200' }}" 
                     alt="{{ $event->title }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-colors"></div>
            </button>
        @endforeach
    </div>

    <!-- PROGRESS BAR -->
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/10 z-20">
        <div id="eventsProgress" class="h-full bg-gradient-to-r from-amber-500 to-pink-500 transition-all duration-300" style="width: 0%"></div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.getElementById('eventsHeroCarousel');
    if (!carousel) return;
    
    const track = document.getElementById('eventsHeroTrack');
    const slides = track.children;
    const prev = document.getElementById('eventsPrev');
    const next = document.getElementById('eventsNext');
    const dots = document.querySelectorAll('.events-dot');
    const thumbs = document.querySelectorAll('.events-thumb');
    const progress = document.getElementById('eventsProgress');

    let index = 0;
    const total = slides.length;
    const delay = 7000; // 7 secondes par slide
    let timer;
    let progressTimer;

    function update() {
        // Slide transition
        track.style.transform = `translateX(-${index * 100}%)`;

        // Update dots
        dots.forEach((d, i) => {
            d.classList.toggle('bg-amber-400', i === index);
            d.classList.toggle('scale-150', i === index);
            d.classList.toggle('bg-white/30', i !== index);
        });

        // Update thumbnails
        thumbs.forEach((t, i) => {
            t.classList.toggle('border-amber-400', i === index);
            t.classList.toggle('border-white/30', i !== index);
            t.classList.toggle('scale-110', i === index);
        });

        // Reset progress
        if (progress) {
            progress.style.width = '0%';
            clearInterval(progressTimer);
            startProgress();
        }
    }

    function startProgress() {
        let width = 0;
        progressTimer = setInterval(() => {
            width += 100 / (delay / 100);
            if (width >= 100) {
                width = 100;
                clearInterval(progressTimer);
            }
            if (progress) progress.style.width = width + '%';
        }, 100);
    }

    function nextSlide() {
        index = (index + 1) % total;
        update();
    }

    function prevSlide() {
        index = (index - 1 + total) % total;
        update();
    }

    function autoplay() {
        timer = setInterval(nextSlide, delay);
    }

    function reset() {
        clearInterval(timer);
        clearInterval(progressTimer);
        autoplay();
    }

    // Event listeners
    if (next) next.onclick = () => { nextSlide(); reset(); };
    if (prev) prev.onclick = () => { prevSlide(); reset(); };

    dots.forEach(dot => {
        dot.onclick = () => {
            index = +dot.dataset.index;
            update();
            reset();
        };
    });

    thumbs.forEach(thumb => {
        thumb.onclick = () => {
            index = +thumb.dataset.index;
            update();
            reset();
        };
    });

    // Pause on hover
    carousel.addEventListener('mouseenter', () => {
        clearInterval(timer);
        clearInterval(progressTimer);
    });
    carousel.addEventListener('mouseleave', () => {
        autoplay();
        startProgress();
    });

    // Touch/Swipe support
    let startX = 0;
    let isDragging = false;

    track.addEventListener('touchstart', e => {
        startX = e.touches[0].clientX;
        isDragging = true;
    });

    track.addEventListener('touchmove', e => {
        if (!isDragging) return;
        e.preventDefault();
    });

    track.addEventListener('touchend', e => {
        if (!isDragging) return;
        isDragging = false;
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) {
            diff > 0 ? nextSlide() : prevSlide();
            reset();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowLeft') { prevSlide(); reset(); }
        if (e.key === 'ArrowRight') { nextSlide(); reset(); }
    });

    // Initialize
    update();
    autoplay();
});
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out;
    will-change: transform, opacity;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.title-clamp {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

@else
<!-- FALLBACK: No Events Available -->
<section class="relative w-full overflow-hidden bg-gradient-to-br from-purple-900 via-purple-800 to-amber-900 py-24">
    <div class="container mx-auto px-6 text-center">
        <div class="max-w-2xl mx-auto">
            <div class="w-24 h-24 mx-auto mb-8 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center border-2 border-white/30">
                <svg class="w-12 h-12 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
                {{ __('No Events Available') }}
            </h2>
            <p class="text-xl text-white/80 mb-8">
                {{ __('Stay tuned! Exciting VIP events are coming soon.') }}
            </p>
            <a href="{{ route('contact') }}" 
               class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-amber-500 to-pink-500 text-black font-black rounded-full hover:scale-105 transition-transform shadow-xl">
                <span>{{ __('Contact Us') }}</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif
