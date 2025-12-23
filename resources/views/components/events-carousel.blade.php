@php
    $events = $events ?? $shuffledEvents ?? collect();
@endphp

<section
    class="relative w-full overflow-hidden bg-black"
    id="eventsHeroCarousel"
>
    <!-- TRACK -->
    <div
        id="eventsHeroTrack"
        class="flex transition-transform duration-700 ease-in-out"
        style="transform: translateX(0%)"
    >
        @foreach($events as $event)
            @php
                $image = $event->getFirstMediaUrl('avatar', 'normal')
                    ?: 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=1600';
            @endphp

            <!-- SLIDE -->
            <div class="w-full flex-shrink-0 relative min-h-[85vh]">

                <!-- IMAGE -->
                <img
                    src="{{ $image }}"
                    alt="{{ $event->title }}"
                    class="absolute inset-0 w-full h-full object-cover"
                >

                <!-- OVERLAY -->
                <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/60 to-transparent"></div>

                <!-- CONTENT -->
                <div class="relative z-10 h-full flex items-center">
                    <div class="container mx-auto px-6 max-w-4xl text-white">

                        <!-- BADGES -->
                        <div class="flex items-center gap-3 mb-6">
                            <span class="px-4 py-2 bg-gradient-to-r from-amber-500 to-pink-500 text-sm font-black rounded-full">
                                VIP EVENT
                            </span>

                            @if($event->event_date)
                                <span class="px-4 py-2 bg-white/10 backdrop-blur rounded-full text-sm">
                                    {{ $event->event_date->format('d M Y') }}
                                </span>
                            @endif
                        </div>

                        <!-- TITLE -->
                        <h2 class="text-4xl md:text-6xl font-black leading-tight mb-6">
                            {{ $event->title }}
                        </h2>

                        <!-- DESCRIPTION -->
                        <p class="text-lg text-white/80 max-w-2xl mb-8 line-clamp-3">
                            {{ $event->description ?? __('An exclusive premium event experience.') }}
                        </p>

                        <!-- PRICE + CTA -->
                        <div class="flex flex-wrap items-center gap-6">
                            <div class="text-2xl font-black text-amber-400">
                                {{ $event->min_price
                                    ? __('From ') . \App\Helpers\CurrencyHelper::format($event->min_price)
                                    : __('Price on request') }}
                            </div>

                            <a
                                href="{{ route('events') }}"
                                class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-amber-500 to-pink-500 text-black font-black rounded-full hover:scale-105 transition"
                            >
                                {{ __('Discover Event') }}
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- ARROWS -->
    <button id="eventsPrev"
        class="absolute left-6 top-1/2 -translate-y-1/2 w-14 h-14 rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-white hover:bg-white/40 transition z-20">
        ‹
    </button>

    <button id="eventsNext"
        class="absolute right-6 top-1/2 -translate-y-1/2 w-14 h-14 rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-white hover:bg-white/40 transition z-20">
        ›
    </button>

    <!-- DOTS -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-20">
        @foreach($events as $i => $event)
            <button
                class="events-dot w-3 h-3 rounded-full bg-white/30 transition"
                data-index="{{ $i }}"
            ></button>
        @endforeach
    </div>

    <!-- THUMBNAILS -->
    <div class="absolute bottom-20 left-1/2 -translate-x-1/2 hidden md:flex gap-3 z-20">
        @foreach($events as $i => $event)
            <img
                src="{{ $event->getFirstMediaUrl('avatar', 'thumb') ?: 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=200' }}"
                data-index="{{ $i }}"
                class="events-thumb w-20 h-14 object-cover rounded-lg border-2 border-white/30 hover:border-amber-400 cursor-pointer transition"
            >
        @endforeach
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.getElementById('eventsHeroCarousel');
    const track = document.getElementById('eventsHeroTrack');
    const slides = track.children;
    const prev = document.getElementById('eventsPrev');
    const next = document.getElementById('eventsNext');
    const dots = document.querySelectorAll('.events-dot');
    const thumbs = document.querySelectorAll('.events-thumb');

    let index = 0;
    const total = slides.length;
    const delay = 6000;
    let timer;

    function update() {
        track.style.transform = `translateX(-${index * 100}%)`;

        dots.forEach((d, i) =>
            d.classList.toggle('bg-amber-400', i === index)
        );

        thumbs.forEach((t, i) =>
            t.classList.toggle('border-amber-400', i === index)
        );
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
        autoplay();
    }

    next.onclick = () => { nextSlide(); reset(); };
    prev.onclick = () => { prevSlide(); reset(); };

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
    carousel.addEventListener('mouseenter', () => clearInterval(timer));
    carousel.addEventListener('mouseleave', autoplay);

    // Swipe mobile
    let startX = 0;
    track.addEventListener('touchstart', e => startX = e.touches[0].clientX);
    track.addEventListener('touchend', e => {
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) diff > 0 ? nextSlide() : prevSlide();
        reset();
    });

    update();
    autoplay();
});
</script>