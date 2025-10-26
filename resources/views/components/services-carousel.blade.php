@props(['services' => []])

<section class="relative min-h-screen bg-gradient-to-br from-purple-900 via-purple-800 to-amber-900 overflow-hidden">
  {{-- Background Effects --}}
  <div class="absolute inset-0">
    <div class="absolute top-20 left-20 w-96 h-96 bg-amber-500 rounded-full filter blur-3xl animate-pulse opacity-20"></div>
    <div class="absolute bottom-20 right-20 w-96 h-96 bg-pink-500 rounded-full filter blur-3xl animate-pulse opacity-20" style="animation-delay: 2s"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-radial from-white/5 to-transparent rounded-full"></div>
  </div>

  {{-- Image Carousel --}}
  <div class="relative z-10 h-screen overflow-hidden">
    <div class="absolute inset-0">
      {{-- Carousel Images --}}
      <div x-data="{
        currentSlide: 0,
        slides: [
          {
            image: 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1920&h=1080&fit=crop',
            title: 'VOLS PRIVÉS',
            subtitle: 'Jets & Hélicoptères de Luxe',
            description: 'Découvrez le confort absolu avec nos vols privés personnalisés'
          },
          {
            image: 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=1920&h=1080&fit=crop',
            title: 'ÉVÉNEMENTS VIP',
            subtitle: 'Accès Exclusif Premium',
            description: 'Vivez des moments uniques aux plus grands événements mondiaux'
          },
          {
            image: 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=1920&h=1080&fit=crop',
            title: 'PACKAGES LUXE',
            subtitle: 'Expériences Sur Mesure',
            description: 'Safari, yachting, circuits exclusifs - tout est possible'
          },
          {
            image: 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=1920&h=1080&fit=crop',
            title: 'LOCATION PREMIUM',
            subtitle: 'Véhicules d\'Exception',
            description: 'Quads, motos, voitures de sport - conduite ultime'
          }
        ],
        nextSlide() { this.currentSlide = (this.currentSlide + 1) % this.slides.length },
        prevSlide() { this.currentSlide = this.currentSlide === 0 ? this.slides.length - 1 : this.currentSlide - 1 },
        goToSlide(index) { this.currentSlide = index }
      }"
      class="relative h-full">

        {{-- Slides --}}
        <template x-for="(slide, index) in slides" :key="index">
          <div
            x-show="currentSlide === index"
            x-transition:enter="transition ease-out duration-1000"
            x-transition:enter-start="opacity-0 scale-110"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute inset-0"
          >
            <img
              :src="slide.image"
              :alt="slide.title"
              class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>

            {{-- Content Overlay --}}
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="container mx-auto px-6 text-center">
                <div class="max-w-5xl mx-auto">
                  {{-- Badge --}}
                  <div class="inline-flex items-center space-x-3 px-8 py-4 bg-white/20 backdrop-blur-md text-white rounded-full text-sm font-black mb-12 shadow-2xl border border-white/30 animate-pulse">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <span>EXPÉRIENCES DE LUXE SUR MESURE</span>
                  </div>

                  {{-- Main Title --}}
                  <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white mb-6 leading-tight">
                    <span x-text="slide.title" class="block"></span>
                    <span class="bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent">
                      CARRÉ PREMIUM
                    </span>
                  </h1>

                  {{-- Subtitle --}}
                  <h2 class="text-xl md:text-3xl font-bold text-amber-400 mb-8" x-text="slide.subtitle"></h2>

                  {{-- Description --}}
                  <p class="text-lg md:text-xl text-white/90 mb-12 max-w-3xl mx-auto leading-relaxed" x-text="slide.description"></p>

                  {{-- CTA Buttons --}}
                  <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a
                      href="#services"
                      class="group inline-flex items-center space-x-3 px-12 py-6 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-500 text-white font-black text-xl rounded-full hover:scale-110 transition-all duration-300 shadow-2xl"
                    >
                      <span>DÉCOUVRIR</span>
                      <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                      </svg>
                    </a>

                    <a
                      href="{{ route('contact') }}"
                      class="group inline-flex items-center space-x-3 px-12 py-6 bg-white/20 backdrop-blur-md text-white font-black text-xl rounded-full hover:bg-white hover:text-purple-900 transition-all duration-300 shadow-2xl border-2 border-white/30"
                    >
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                      </svg>
                      <span>CONTACTER</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>

        {{-- Navigation Arrows --}}
        <button
          @click="prevSlide()"
          class="absolute left-6 top-1/2 transform -translate-y-1/2 w-14 h-14 bg-white/20 backdrop-blur-md text-white rounded-full hover:bg-white/30 transition-all duration-300 shadow-2xl border border-white/30 flex items-center justify-center z-20"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <button
          @click="nextSlide()"
          class="absolute right-6 top-1/2 transform -translate-y-1/2 w-14 h-14 bg-white/20 backdrop-blur-md text-white rounded-full hover:bg-white/30 transition-all duration-300 shadow-2xl border border-white/30 flex items-center justify-center z-20"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        {{-- Indicators --}}
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20">
          <template x-for="(slide, index) in slides" :key="index">
            <button
              @click="goToSlide(index)"
              class="w-3 h-3 rounded-full transition-all duration-300"
              :class="currentSlide === index ? 'bg-white scale-125' : 'bg-white/50 hover:bg-white/75'"
            ></button>
          </template>
        </div>

        {{-- Auto-play --}}
        <div x-init="setInterval(() => { nextSlide() }, 5000)" class="hidden"></div>
      </div>
    </div>
  </div>

  
</section>
