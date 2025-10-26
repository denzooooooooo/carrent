@extends('layouts.app')

@section('title', 'Événements - Carré Premium')

@section('content')
<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[40vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4">
      <h1 class="text-5xl font-black text-white mb-4">Événements Sportifs & Culturels</h1>
      <p class="text-xl text-white/90">Vivez des expériences uniques avec Carré Premium</p>
    </div>
  </section>

  {{-- Filters --}}
  <section class="py-8 bg-gray-50">
    <div class="container mx-auto">
      <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg p-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Type d'événement</label>
              <select class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-purple-600 focus:outline-none">
                <option>Tous les événements</option>
                <option>Football</option>
                <option>Basketball</option>
                <option>Concerts</option>
                <option>Théâtre</option>
                <option>Festivals</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Lieu</label>
              <select class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-purple-600 focus:outline-none">
                <option>Tous les lieux</option>
                <option>Stade Félix Houphouët-Boigny</option>
                <option>Palais de la Culture</option>
                <option>Parc des Sports</option>
                <option>Salle des fêtes</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
              <input type="date" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-purple-600 focus:outline-none">
            </div>
            <div class="flex items-end">
              <button class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                Rechercher
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Events Grid --}}
  <section class="py-12">
    <div class="container mx-auto">
      <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {{-- Event Card 1 --}}
          <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=500&h=300&fit=crop" alt="Match de Football" class="w-full h-48 object-cover">
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full">Disponible</span>
              </div>
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-purple-600 text-white text-sm font-bold rounded-full">Football</span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-black mb-2">Asec Mimosas vs Africa Sports</h3>
              <div class="flex items-center text-gray-600 mb-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>15 Décembre 2024 - 16h00</span>
              </div>
              <div class="flex items-center text-gray-600 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Stade Félix Houphouët-Boigny</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-2xl font-black text-purple-600">25,000 FCFA</span>
                  <span class="text-sm text-gray-500 ml-2">par personne</span>
                </div>
                <a href="#" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                  Réserver
                </a>
              </div>
            </div>
          </div>

          {{-- Event Card 2 --}}
          <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=500&h=300&fit=crop" alt="Concert" class="w-full h-48 object-cover">
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-orange-500 text-white text-sm font-bold rounded-full">Dernières places</span>
              </div>
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-amber-600 text-white text-sm font-bold rounded-full">Concert</span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-black mb-2">Concert Magic System</h3>
              <div class="flex items-center text-gray-600 mb-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>20 Décembre 2024 - 20h00</span>
              </div>
              <div class="flex items-center text-gray-600 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Palais de la Culture</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-2xl font-black text-purple-600">50,000 FCFA</span>
                  <span class="text-sm text-gray-500 ml-2">par personne</span>
                </div>
                <a href="#" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                  Réserver
                </a>
              </div>
            </div>
          </div>

          {{-- Event Card 3 --}}
          <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=500&h=300&fit=crop" alt="Basketball" class="w-full h-48 object-cover">
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full">Disponible</span>
              </div>
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-blue-600 text-white text-sm font-bold rounded-full">Basketball</span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-black mb-2">ABC vs SOA</h3>
              <div class="flex items-center text-gray-600 mb-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>22 Décembre 2024 - 18h00</span>
              </div>
              <div class="flex items-center text-gray-600 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Parc des Sports</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-2xl font-black text-purple-600">15,000 FCFA</span>
                  <span class="text-sm text-gray-500 ml-2">par personne</span>
                </div>
                <a href="#" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                  Réserver
                </a>
              </div>
            </div>
          </div>

          {{-- Event Card 4 --}}
          <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=500&h=300&fit=crop" alt="Théâtre" class="w-full h-48 object-cover">
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full">Disponible</span>
              </div>
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-red-600 text-white text-sm font-bold rounded-full">Théâtre</span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-black mb-2">La Haine de la Musique</h3>
              <div class="flex items-center text-gray-600 mb-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>25 Décembre 2024 - 19h30</span>
              </div>
              <div class="flex items-center text-gray-600 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Salle des fêtes</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-2xl font-black text-purple-600">30,000 FCFA</span>
                  <span class="text-sm text-gray-500 ml-2">par personne</span>
                </div>
                <a href="#" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                  Réserver
                </a>
              </div>
            </div>
          </div>

          {{-- Event Card 5 --}}
          <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=500&h=300&fit=crop" alt="Festival" class="w-full h-48 object-cover">
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full">Disponible</span>
              </div>
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-pink-600 text-white text-sm font-bold rounded-full">Festival</span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-black mb-2">Festival des Arts Vivants</h3>
              <div class="flex items-center text-gray-600 mb-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>31 Décembre 2024 - 21h00</span>
              </div>
              <div class="flex items-center text-gray-600 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Parc National</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-2xl font-black text-purple-600">75,000 FCFA</span>
                  <span class="text-sm text-gray-500 ml-2">par personne</span>
                </div>
                <a href="#" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                  Réserver
                </a>
              </div>
            </div>
          </div>

          {{-- Event Card 6 --}}
          <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500&h=300&fit=crop" alt="Tennis" class="w-full h-48 object-cover">
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-orange-500 text-white text-sm font-bold rounded-full">Dernières places</span>
              </div>
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-green-600 text-white text-sm font-bold rounded-full">Tennis</span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-black mb-2">Tournoi International de Tennis</h3>
              <div class="flex items-center text-gray-600 mb-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>5 Janvier 2025 - 14h00</span>
              </div>
              <div class="flex items-center text-gray-600 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Complexe Sportif</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-2xl font-black text-purple-600">40,000 FCFA</span>
                  <span class="text-sm text-gray-500 ml-2">par personne</span>
                </div>
                <a href="#" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                  Réserver
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="py-16 bg-gradient-to-r from-purple-600 to-amber-600">
    <div class="container mx-auto text-center">
      <h2 class="text-4xl font-black text-white mb-4">Vous ne trouvez pas l'événement de vos rêves ?</h2>
      <p class="text-xl text-white/90 mb-8">Contactez-nous pour organiser un événement sur mesure</p>
      <div class="flex flex-wrap gap-4 justify-center">
        <a href="{{ route('contact') }}" class="px-8 py-4 bg-white text-purple-600 font-bold rounded-xl hover:shadow-2xl transition-all">
          Demander un devis
        </a>
        <a href="tel:+225XXXXXXXXX" class="px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white hover:text-purple-600 transition-all">
          Appeler Maintenant
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
