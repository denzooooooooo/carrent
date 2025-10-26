@extends('layouts.app')

@section('title', 'Packages Touristiques - Carré Premium')

@section('content')
<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[40vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4">
      <h1 class="text-5xl font-black text-white mb-4">Packages Touristiques</h1>
      <p class="text-xl text-white/90">Découvrez des expériences uniques avec nos packages exclusifs</p>
    </div>
  </section>

  {{-- Filters --}}
  <section class="py-8 bg-gray-50">
    <div class="container mx-auto">
      <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg p-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Type de package</label>
              <select class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-purple-600 focus:outline-none">
                <option>Tous les packages</option>
                <option>Hélicoptère</option>
                <option>Jet privé</option>
                <option>Circuits touristiques</option>
                <option>Événements VIP</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Destination</label>
              <select class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-purple-600 focus:outline-none">
                <option>Toutes les destinations</option>
                <option>Côte d'Ivoire</option>
                <option>Afrique de l'Ouest</option>
                <option>Europe</option>
                <option>Asie</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Durée</label>
              <select class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-purple-600 focus:outline-none">
                <option>Toutes les durées</option>
                <option>1-3 jours</option>
                <option>4-7 jours</option>
                <option>1-2 semaines</option>
                <option>Plus de 2 semaines</option>
              </select>
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

  {{-- Packages Grid --}}
  <section class="py-12">
    <div class="container mx-auto">
      <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {{-- Package Card 1 --}}
          <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1540962351504-03099e0a754b?w=500&h=300&fit=crop" alt="Hélicoptère" class="w-full h-48 object-cover">
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full">Disponible</span>
              </div>
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-blue-600 text-white text-sm font-bold rounded-full">Hélicoptère</span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-black mb-2">Survol de la Côte d'Ivoire</h3>
              <p class="text-gray-600 mb-4">Découvrez les merveilles de la Côte d'Ivoire vue du ciel avec notre service d'hélicoptère privé.</p>
              <div class="flex items-center text-gray-600 mb-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>2 heures de vol</span>
              </div>
              <div class="flex items-center text-gray-600 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Abidjan - Yamoussoukro</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-2xl font-black text-purple-600">850,000 FCFA</span>
                  <span class="text-sm text-gray-500 ml-2">par personne</span>
                </div>
                <a href="#" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                  Réserver
                </a>
              </div>
            </div>
          </div>

          {{-- Package Card 2 --}}
          <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1556388158-158ea5de0e5b?w=500&h=300&fit=crop" alt="Jet Privé" class="w-full h-48 object-cover">
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-orange-500 text-white text-sm font-bold rounded-full">Dernières places</span>
              </div>
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-purple-600 text-white text-sm font-bold rounded-full">Jet Privé</span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-black mb-2">Voyage Paris - Abidjan</h3>
              <p class="text-gray-600 mb-4">Voyagez en jet privé entre Paris et Abidjan avec tout le confort et la discrétion souhaités.</p>
              <div class="flex items-center text-gray-600 mb-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>6 heures de vol</span>
              </div>
              <div class="flex items-center text-gray-600 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Paris CDG - Abidjan FEL</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-2xl font-black text-purple-600">2,500,000 FCFA</span>
                  <span class="text-sm text-gray-500 ml-2">par personne</span>
                </div>
                <a href="#" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                  Réserver
                </a>
              </div>
            </div>
          </div>

          {{-- Package Card 3 --}}
          <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=500&h=300&fit=crop" alt="Circuit Touristique" class="w-full h-48 object-cover">
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full">Disponible</span>
              </div>
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-amber-600 text-white text-sm font-bold rounded-full">Circuit</span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-black mb-2">Découverte de l'Afrique de l'Ouest</h3>
              <p class="text-gray-600 mb-4">Explorez les richesses culturelles et naturelles de l'Afrique de l'Ouest en 7 jours.</p>
              <div class="flex items-center text-gray-600 mb-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>7 jours / 6 nuits</span>
              </div>
              <div class="flex items-center text-gray-600 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Côte d'Ivoire - Ghana - Togo</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-2xl font-black text-purple-600">1,200,000 FCFA</span>
                  <span class="text-sm text-gray-500 ml-2">par personne</span>
                </div>
                <a href="#" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                  Réserver
                </a>
              </div>
            </div>
          </div>

          {{-- Package Card 4 --}}
          <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=500&h=300&fit=crop" alt="Événement VIP" class="w-full h-48 object-cover">
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full">Disponible</span>
              </div>
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-red-600 text-white text-sm font-bold rounded-full">VIP</span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-black mb-2">Festival de Cannes - Pack VIP</h3>
              <p class="text-gray-600 mb-4">Vivez le Festival de Cannes comme une star avec notre package VIP complet.</p>
              <div class="flex items-center text-gray-600 mb-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>10 jours / 9 nuits</span>
              </div>
              <div class="flex items-center text-gray-600 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Cannes, France</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-2xl font-black text-purple-600">4,500,000 FCFA</span>
                  <span class="text-sm text-gray-500 ml-2">par personne</span>
                </div>
                <a href="#" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                  Réserver
                </a>
              </div>
            </div>
          </div>

          {{-- Package Card 5 --}}
          <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=500&h=300&fit=crop" alt="Safari" class="w-full h-48 object-cover">
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full">Disponible</span>
              </div>
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-green-600 text-white text-sm font-bold rounded-full">Safari</span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-black mb-2">Safari au Kenya</h3>
              <p class="text-gray-600 mb-4">Vivez une aventure inoubliable au cœur de la savane africaine avec notre safari privatif.</p>
              <div class="flex items-center text-gray-600 mb-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>5 jours / 4 nuits</span>
              </div>
              <div class="flex items-center text-gray-600 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Masai Mara, Kenya</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-2xl font-black text-purple-600">1,800,000 FCFA</span>
                  <span class="text-sm text-gray-500 ml-2">par personne</span>
                </div>
                <a href="#" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                  Réserver
                </a>
              </div>
            </div>
          </div>

          {{-- Package Card 6 --}}
          <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=500&h=300&fit=crop" alt="Croisière" class="w-full h-48 object-cover">
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-orange-500 text-white text-sm font-bold rounded-full">Dernières places</span>
              </div>
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-blue-600 text-white text-sm font-bold rounded-full">Croisière</span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-black mb-2">Croisière en Méditerranée</h3>
              <p class="text-gray-600 mb-4">Naviguez entre les plus belles îles de la Méditerranée à bord d'un yacht de luxe.</p>
              <div class="flex items-center text-gray-600 mb-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>8 jours / 7 nuits</span>
              </div>
              <div class="flex items-center text-gray-600 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Méditerranée</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-2xl font-black text-purple-600">3,200,000 FCFA</span>
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
      <h2 class="text-4xl font-black text-white mb-4">Un package sur mesure ?</h2>
      <p class="text-xl text-white/90 mb-8">Contactez notre équipe pour créer l'expérience de vos rêves</p>
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
