@props(['services' => []])

<section class="relative h-[520px] md:h-[680px] bg-gradient-to-br from-purple-900 via-purple-800 to-amber-900 overflow-hidden">

    {{-- Background Effects --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-20 left-20 w-72 h-72 bg-amber-500 rounded-full blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-20 right-20 w-72 h-72 bg-pink-500 rounded-full blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s"></div>
        <div class="absolute top-1/2 left-1/2 w-[800px] h-[800px] bg-gradient-radial from-white/5 to-transparent rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    </div>

    {{-- HERO + CAROUSEL --}}
    <div class="relative z-10 h-full overflow-hidden">

        @php
            $carouselSlides = [
                [
                    'image' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1920&h=1080&fit=crop',
                    'title' => 'Votre conciergerie de luxe multi-service',
                    'subtitle' => 'Luxury Jets & Helicopters',
                    'description' => 'Bienvenue chez Carré Premium, votre passerelle vers des expériences de voyage inégalées.'
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=1920&h=1080&fit=crop',
                    'title' => 'VIP EVENTS',
                    'subtitle' => 'Exclusive Premium Access',
                    'description' => "Experience unique moments at the world's greatest events."
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=1920&h=1080&fit=crop',
                    'title' => 'LUXURY PACKAGES',
                    'subtitle' => 'Tailor-Made Experiences',
                    'description' => 'Safari, yachting, exclusive tours - everything is possible.'
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=1920&h=1080&fit=crop',
                    'title' => 'PREMIUM RENTAL',
                    'subtitle' => 'Exceptional Vehicles',
                    'description' => 'Quads, motorcycles, sports cars - ultimate driving.'
                ],
            ];
        @endphp

        <div x-data="{
            current: 0,
            slides: {!! json_encode($carouselSlides, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!},
            next() { this.current = (this.current + 1) % this.slides.length },
            prev() { this.current = this.current === 0 ? this.slides.length - 1 : this.current - 1 },
            goTo(i) { this.current = i }
        }"
        class="relative h-full">

            {{-- Fallback: server-rendered first slide so a hero appears even if Alpine fails to initialize --}}
            @if(!empty($carouselSlides) && isset($carouselSlides[0]))
                @php $first = $carouselSlides[0]; @endphp
                <div class="absolute inset-0">
                    <img src="{{ $first['image'] }}" class="w-full h-full object-cover" alt="{{ $first['title'] }}" />
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
                    <div class="absolute inset-0 flex items-center">
                        <div class="container mx-auto px-6 text-center">
                            <div class="max-w-4xl mx-auto">
                                <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-md text-white text-sm rounded-full font-bold mb-8 border border-white/30 shadow-xl">
                                    ✦ TAILOR-MADE LUXURY EXPERIENCES ✦
                                </div>
                                <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white leading-tight mb-4">
                                    {{ $first['title'] }}
                                    <span class="block bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent">CARRÉ PREMIUM</span>
                                </h1>
                                <h2 class="text-xl md:text-3xl font-bold text-amber-400 mb-6">{{ $first['subtitle'] }}</h2>
                                <p class="text-white/90 text-lg md:text-xl max-w-2xl mx-auto mb-8">{{ $first['description'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Slides --}}
            <template x-for="(slide, index) in slides" :key="index">
                <div
                    x-show="current === index"
                    x-transition:enter="transition ease-out duration-1000"
                    x-transition:enter-start="opacity-0 scale-110"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-500"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute inset-0"
                >
                    <img :src="slide.image" class="w-full h-full object-cover" />

                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>

                    {{-- CONTENT --}}
                    <div class="absolute inset-0 flex items-center">
                        <div class="container mx-auto px-6 text-center">
                            <div class="max-w-4xl mx-auto">

                                {{-- Badge --}}
                                <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-md text-white text-sm rounded-full font-bold mb-8 border border-white/30 shadow-xl">
                                    ✦ TAILOR-MADE LUXURY EXPERIENCES ✦
                                </div>

                                {{-- Title --}}
                                <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white leading-tight mb-4">
                                    <span x-text="slide.title"></span>
                                    <span class="block bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent">
                                        CARRÉ PREMIUM
                                    </span>
                                </h1>

                                {{-- Subtitle --}}
                                <h2 class="text-xl md:text-3xl font-bold text-amber-400 mb-6" x-text="slide.subtitle"></h2>

                                {{-- Description --}}
                                <p class="text-white/90 text-lg md:text-xl max-w-2xl mx-auto mb-8" x-text="slide.description"></p>

                                {{-- CTA --}}
                                <div class="flex flex-col sm:flex-row justify-center gap-4">
                                    <a href="#services"
                                       class="px-10 py-4 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-500 text-white text-lg font-bold rounded-full shadow-xl hover:scale-110 transition">
                                        {{ __('DISCOVER') }}
                                    </a>

                                    <a href="{{ route('contact') }}"
                                       class="px-10 py-4 bg-white/20 backdrop-blur-md border border-white/30 text-white text-lg font-bold rounded-full shadow-xl hover:bg-white hover:text-purple-900 transition">
                                        CONTACT
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Arrows --}}
            <button @click="prev()"
                    class="hidden md:flex absolute left-6 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/20 backdrop-blur-md border border-white/30 rounded-full text-white hover:bg-white/30 shadow-xl items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button @click="next()"
                    class="hidden md:flex absolute right-6 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/20 backdrop-blur-md border border-white/30 rounded-full text-white hover:bg-white/30 shadow-xl items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            {{-- Indicators --}}
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex space-x-3">
                <template x-for="(slide, i) in slides" :key="i">
                    <button @click="goTo(i)"
                            class="w-3 h-3 rounded-full transition"
                            :class="current === i ? 'bg-white scale-125' : 'bg-white/50 hover:bg-white/75'">
                    </button>
                </template>
            </div>

            {{-- Autoplay --}}
            <div x-init="setInterval(() => next(), 5000)"></div>
        </div>
    </div>
</section>