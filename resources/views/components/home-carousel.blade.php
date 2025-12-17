@props(['slides' => []])

<section class="relative w-full overflow-hidden bg-black">
  <div class="relative w-full mx-auto">

    <div id="hp-carousel" class="flex transition-transform duration-700 ease-in-out" style="transform: translateX(0vw);">

      @php
        $default = [
          [
            'image' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1920&q=80',
            'title' => 'Voyagez autrement',
            'caption' => 'Des expériences uniques, pensées pour l’exceptionnel.'
          ],
          [
            'image' => 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?auto=format&fit=crop&w=1920&q=80',
            'title' => 'Luxe & Liberté',
            'caption' => 'Explorez les plus belles villes du monde avec élégance.'
          ],
          [
            'image' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1920&q=80',
            'title' => 'Moments Inoubliables',
            'caption' => 'Chaque destination devient un souvenir d’exception.'
          ],
          [
            'video' => 'SPOT CARRE PREMIVM.mp4',
            'poster' => 'https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=1920&q=80',
            'title' => 'Carre Premium',
            'caption' => 'Le voyage sur mesure, à votre image.'
          ]
        ];

        $slidesData = count($slides) ? $slides : $default;
      @endphp

      @foreach($slidesData as $s)
        <div class="w-screen flex-none relative h-[50vh] md:h-[60vh] lg:h-[70vh]">

          @if(isset($s['video']))
            <video class="w-full h-full object-cover" autoplay muted loop playsinline poster="{{ $s['poster'] ?? '' }}">
              <source src="{{ asset('videos/' . $s['video']) }}" type="video/mp4">
            </video>
          @else
            <img src="{{ $s['image'] }}" alt="{{ $s['title'] ?? 'Slide' }}" class="w-full h-full object-cover" />
          @endif

          <!-- Overlay -->
          <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>

          <!-- Content -->
          <div class="absolute inset-0 flex items-center justify-center">
            <div class="max-w-3xl px-6 text-center">
              <h2 class="text-2xl md:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-3">
                {{ $s['title'] }}
              </h2>
              <p class="text-sm md:text-base lg:text-lg text-white/90 max-w-xl mb-5">
                {{ $s['caption'] }}
              </p>

              <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ url('/contact') }}"
                   class="inline-flex items-center justify-center px-5 py-2.5 rounded-md
                          bg-gradient-to-r from-indigo-600 to-indigo-500
                          hover:from-indigo-700 hover:to-indigo-600
                          text-white font-semibold text-sm
                          shadow-lg transition">
                  Nous contacter
                </a>

                <a href="{{ url('/services') }}"
                   class="inline-flex items-center justify-center px-5 py-2.5 rounded-md
                          bg-white/10 backdrop-blur
                          text-white font-medium text-sm
                          border border-white/30 hover:bg-white/20 transition">
                  Découvrir nos offres
                </a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Controls -->
    <button id="hp-prev"
      class="absolute left-4 top-1/2 -translate-y-1/2 z-20 hidden md:flex
             w-12 h-12 rounded-full bg-white/20 text-white
             items-center justify-center hover:bg-white/30 transition">
      ‹
    </button>

    <button id="hp-next"
      class="absolute right-4 top-1/2 -translate-y-1/2 z-20 hidden md:flex
             w-12 h-12 rounded-full bg-white/20 text-white
             items-center justify-center hover:bg-white/30 transition">
      ›
    </button>

    <!-- Indicators -->
    <div id="hp-indicators"
         class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3 z-20">
      @for($i = 0; $i < count($slidesData); $i++)
        <button data-index="{{ $i }}"
          class="w-3 h-3 rounded-full bg-white/40 hover:bg-white transition"></button>
      @endfor
    </div>

  </div>

  <script>
    (function(){
      const container = document.getElementById('hp-carousel');
      const slides = container.children.length;

      container.style.width = (slides * 100) + 'vw';
      [...container.children].forEach(slide => {
        slide.style.width = '100vw';
        slide.style.flex = '0 0 100vw';
      });

      const prev = document.getElementById('hp-prev');
      const next = document.getElementById('hp-next');
      const indicators = document.getElementById('hp-indicators').children;

      let current = 0;
      let timer;

      function go(i){
        if(i < 0) i = slides - 1;
        if(i >= slides) i = 0;
        current = i;
        container.style.transform = 'translateX(' + (-100 * i) + 'vw)';

        [...indicators].forEach((dot, idx) => {
          dot.classList.toggle('bg-white', idx === i);
          dot.classList.toggle('bg-white/40', idx !== i);
        });
      }

      function nextSlide(){ go(current + 1); }
      function prevSlide(){ go(current - 1); }

      next?.addEventListener('click', () => { nextSlide(); reset(); });
      prev?.addEventListener('click', () => { prevSlide(); reset(); });

      [...indicators].forEach((dot, i) => {
        dot.addEventListener('click', () => { go(i); reset(); });
      });

      function start(){ timer = setInterval(nextSlide, 6000); }
      function reset(){ clearInterval(timer); start(); }

      go(0);
      start();
    })();
  </script>
</section>
