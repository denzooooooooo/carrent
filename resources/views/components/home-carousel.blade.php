@props(['slides' => []])

<section class="relative w-full overflow-hidden bg-gray-900">
  <div class="relative max-w-full mx-auto">
  <div id="hp-carousel" class="carousel flex transition-transform duration-700" style="transform: translateX(0vw);">
      {{-- Slides (default images if none provided) --}}
      @php
        $default = [
          ['image' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?w=1920&h=1080&fit=crop', 'title' => 'Safari & Nature', 'caption' => 'Explorez safaris et réserves privées'],
          // Diapo 2 : paysage urbain (URL changée pour fiabilité)
          ['image' => 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?w=1920&h=1080&fit=crop', 'title' => 'Escapade Urbaine', 'caption' => 'Découvrez les capitales et leurs trésors'],
          // Diapo 3 : Jets Privés -> avion en vol
          ['image' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=1920&h=1080&fit=crop', 'title' => 'Jets Privés', 'caption' => 'Vols privés sur mesure'],
          // utilise la vidéo présente dans public/videos/ (nom exact)
          ['video' => 'SPOT CARRE PREMIVM.mp4', 'title' => '', 'caption' => 'Découvrez notre univers en vidéo', 'poster' => 'https://images.unsplash.com/photo-1526772662000-3f88f10405ff?w=1920&h=1080&fit=crop']
        ];
        $slidesData = count($slides) ? $slides : $default;
      @endphp

      @foreach($slidesData as $s)
  <div class="w-screen flex-none relative h-56 md:h-72 lg:h-96">
          @if(isset($s['video']))
            <video class="w-full h-full object-cover" autoplay muted loop playsinline @if(!empty($s['poster'])) poster="{{ $s['poster'] }}" @endif>
              <source src="{{ asset('videos/' . $s['video']) }}" type="video/mp4">
              Votre navigateur ne supporte pas la vidéo.
            </video>
          @else
            <img src="{{ $s['image'] }}" alt="{{ $s['title'] ?? 'Slide' }}" class="w-full h-full object-cover" />

            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/30 to-transparent"></div>
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-center px-4">
              <h3 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-2">{{ $s['title'] }}</h3>
              <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto">{{ $s['caption'] }}</p>
              <div class="mt-3">
                <a href="{{ url('/contact') }}" role="button" aria-label="Nous contacter" title="Nous contacter" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white rounded-md shadow-lg ring-1 ring-indigo-700/20">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m0 0l4-4m-4 4l4 4"></path></svg>
                  <span class="font-medium">Nous contacter</span>
                </a>
              </div>
            </div>
          @endif
        </div>
      @endforeach
    </div>

    {{-- Controls --}}
    <button id="hp-prev" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/20 text-white flex items-center justify-center hidden md:flex">
      <svg class="w-5 h-5" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button id="hp-next" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/20 text-white flex items-center justify-center hidden md:flex">
      <svg class="w-5 h-5" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>

    {{-- Indicators --}}
    <div id="hp-indicators" class="absolute bottom-6 left-1/2 -translate-x-1/2 flex space-x-2 z-20">
      @for($i=0;$i<count($slidesData);$i++)
        <button data-index="{{ $i }}" class="w-3 h-3 rounded-full bg-white/50"></button>
      @endfor
    </div>
  </div>

  <script>
    (function(){
      const container = document.getElementById('hp-carousel');
      const slides = container.children.length;
      // Ensure container and slide widths are set explicitly so slides don't overlap
      container.style.width = (slides * 100) + 'vw';
      for(let s=0;s<container.children.length;s++){
        container.children[s].style.width = '100vw';
        container.children[s].style.flex = '0 0 100vw';
      }
      const prev = document.getElementById('hp-prev');
      const next = document.getElementById('hp-next');
      const indicators = document.getElementById('hp-indicators').children;
      let current = 0;
      let interval = null;

      function go(i){
        if(i < 0) i = slides - 1;
        if(i >= slides) i = 0;
        current = i;
        // translate by viewport width units so each slide (w-screen) aligns correctly
        container.style.transform = 'translateX(' + (-100 * current) + 'vw)';
        for(let b=0;b<indicators.length;b++){
          indicators[b].classList.toggle('bg-white', b===current);
          indicators[b].classList.toggle('bg-white/50', b!==current);
        }
      }

      function nextSlide(){ go(current+1); }
      function prevSlide(){ go(current-1); }

      if(next) next.addEventListener('click', ()=>{ nextSlide(); resetTimer(); });
      if(prev) prev.addEventListener('click', ()=>{ prevSlide(); resetTimer(); });

      for(let i=0;i<indicators.length;i++){
        indicators[i].addEventListener('click', ()=>{ go(i); resetTimer(); });
      }

      function startTimer(){ interval = setInterval(nextSlide, 5000); }
      function resetTimer(){ clearInterval(interval); startTimer(); }

      // Initialize
      go(0);
      startTimer();
    })();
  </script>
</section>
