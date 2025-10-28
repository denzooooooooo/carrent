@extends('layouts.app')

@section('title', 'Mon Profil - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50 flex">
  <!-- Sidebar -->
  <div class="hidden lg:flex lg:w-1/4 bg-white shadow-lg">
    <div class="w-full p-6">
      <div class="text-center mb-8">
        <div class="relative inline-block">
          <img
            src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name) . '&size=120&background=9333EA&color=fff' }}"
            alt="{{ Auth::user()->first_name }}"
            class="w-24 h-24 rounded-full border-4 border-purple-200 object-cover mx-auto"
          />
          <label class="absolute bottom-0 right-0 w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-purple-700 transition-colors">
            <i class="fas fa-camera text-white text-sm"></i>
            <input type="file" accept="image/*" class="hidden" />
          </label>
        </div>
        <h2 class="text-xl font-black text-gray-900 mt-4">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
        <p class="text-gray-600">{{ Auth::user()->email }}</p>
        <div class="flex justify-center mt-2">
          <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold">
            {{ Auth::user()->loyalty_points ?? 0 }} points de fidélité
          </span>
        </div>
      </div>

      <nav class="space-y-2">
        <button onclick="showTab('info')" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors active-tab" data-tab="info">
          <i class="fas fa-user"></i><span>Informations Personnelles</span>
        </button>
        <button onclick="showTab('security')" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors" data-tab="security">
          <i class="fas fa-lock"></i><span>Sécurité</span>
        </button>
        <button onclick="showTab('stats')" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors" data-tab="stats">
          <i class="fas fa-chart-line"></i><span>Statistiques</span>
        </button>
        <a href="{{ route('home') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
          <i class="fas fa-home"></i><span>Retour à l'accueil</span>
        </a>
      </nav>
    </div>
  </div>

  <!-- Main Content -->
  <div class="flex-1 p-6">
    <!-- Mobile Header -->
    <div class="lg:hidden mb-6">
      <div class="flex items-center space-x-4">
        <img
          src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name) . '&size=80&background=9333EA&color=fff' }}"
          alt="{{ Auth::user()->first_name }}"
          class="w-16 h-16 rounded-full border-4 border-purple-200 object-cover"
        />
        <div>
          <h1 class="text-2xl font-black text-gray-900">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h1>
          <p class="text-gray-600">{{ Auth::user()->email }}</p>
        </div>
      </div>
    </div>

    <!-- Tab Content -->
    <div id="info-tab" class="tab-content active">
      <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-black text-gray-900 mb-6">Informations Personnelles</h2>
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Prénom</label>
              <input type="text" name="first_name" value="{{ Auth::user()->first_name }}" class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0" />
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Nom</label>
              <input type="text" name="last_name" value="{{ Auth::user()->last_name }}" class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0" />
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Téléphone</label>
              <input type="tel" name="phone" value="{{ Auth::user()->phone }}" class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0" />
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Date de naissance</label>
              <input type="date" name="date_of_birth" value="{{ Auth::user()->date_of_birth }}" class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0" />
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Genre</label>
              <select name="gender" class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0">
                <option value="">Sélectionner</option>
                <option value="male" {{ Auth::user()->gender == 'male' ? 'selected' : '' }}>Homme</option>
                <option value="female" {{ Auth::user()->gender == 'female' ? 'selected' : '' }}>Femme</option>
                <option value="other" {{ Auth::user()->gender == 'other' ? 'selected' : '' }}>Autre</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Nationalité</label>
              <input type="text" name="nationality" value="{{ Auth::user()->nationality }}" class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0" placeholder="Ivoirienne" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Numéro de passeport</label>
            <input type="text" name="passport_number" value="{{ Auth::user()->passport_number }}" class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0" placeholder="CI123456789" />
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Adresse</label>
            <textarea name="address" rows="3" class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0" placeholder="Votre adresse complète">{{ Auth::user()->address }}</textarea>
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Photo de profil</label>
            <input type="file" name="avatar" accept="image/*" class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" />
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Ville</label>
              <input type="text" name="city" value="{{ Auth::user()->city }}" class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0" placeholder="Abidjan" />
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Pays</label>
              <input type="text" name="country" value="{{ Auth::user()->country }}" class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0" placeholder="Côte d'Ivoire" />
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Code postal</label>
              <input type="text" name="postal_code" value="{{ Auth::user()->postal_code }}" class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0" placeholder="00225" />
            </div>
          </div>
          <button type="submit" class="w-full py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all">
            <i class="fas fa-save mr-2"></i>Enregistrer les modifications
          </button>
        </form>
      </div>
    </div>

    <div id="security-tab" class="tab-content hidden">
      <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-black text-gray-900 mb-6">Paramètres de Sécurité</h2>
        <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
          @csrf
          @method('PUT')
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Mot de passe actuel</label>
            <input type="password" name="current_password" required class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0" placeholder="••••••••" />
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Nouveau mot de passe</label>
            <input type="password" name="password" required class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0" placeholder="••••••••" />
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Confirmer le nouveau mot de passe</label>
            <input type="password" name="password_confirmation" required class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white text-gray-900 focus:border-purple-600 focus:ring-0" placeholder="••••••••" />
          </div>
          <button type="submit" class="w-full py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all">
            <i class="fas fa-key mr-2"></i>Changer le mot de passe
          </button>
        </form>
      </div>
    </div>

    <div id="stats-tab" class="tab-content hidden">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6">
          <i class="fas fa-ticket-alt text-3xl text-purple-600 mb-2"></i>
          <p class="text-3xl font-black text-gray-900">{{ Auth::user()->bookings_count ?? 0 }}</p>
          <p class="text-sm text-gray-600">Réservations</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
          <i class="fas fa-check-circle text-3xl text-green-600 mb-2"></i>
          <p class="text-3xl font-black text-gray-900">{{ Auth::user()->confirmed_bookings ?? 0 }}</p>
          <p class="text-sm text-gray-600">Confirmées</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
          <i class="fas fa-wallet text-3xl text-amber-600 mb-2"></i>
          <p class="text-3xl font-black text-gray-900">{{ number_format(Auth::user()->total_spent ?? 0, 0, ',', ' ') }} XOF</p>
          <p class="text-sm text-gray-600">Dépensé</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
          <i class="fas fa-star text-3xl text-yellow-600 mb-2"></i>
          <p class="text-3xl font-black text-gray-900">{{ Auth::user()->loyalty_points ?? 0 }}</p>
          <p class="text-sm text-gray-600">Points</p>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-lg p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Réservations Récentes</h3>
        @if(Auth::user()->recent_bookings ?? collect()->isEmpty())
          <div class="text-center py-12">
            <i class="fas fa-chart-line text-6xl text-gray-300 mb-4"></i>
            <h4 class="text-xl font-bold text-gray-900 mb-2">Aucune réservation récente</h4>
            <p class="text-gray-600 mb-6">Effectuez votre première réservation pour voir vos statistiques</p>
            <a href="{{ route('flights') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition-colors">
              <i class="fas fa-plane mr-2"></i>Réserver un vol
            </a>
          </div>
        @else
          <div class="space-y-4">
            @foreach(Auth::user()->recent_bookings as $booking)
              <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-purple-600 transition-colors">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="font-bold text-gray-900">{{ $booking->booking_number }}</p>
                    <p class="text-sm text-gray-600">{{ $booking->created_at->format('d/m/Y') }}</p>
                  </div>
                  <div class="text-right">
                    <p class="font-bold text-purple-600">{{ number_format($booking->final_amount, 0, ',', ' ') }} XOF</p>
                    <span class="text-xs px-2 py-1 rounded-full {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-700' : ($booking->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                      {{ $booking->status }}
                    </span>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
function showTab(tabName) {
  // Hide all tabs
  document.querySelectorAll('.tab-content').forEach(tab => {
    tab.classList.add('hidden');
    tab.classList.remove('active');
  });
  
  // Show selected tab
  document.getElementById(tabName + '-tab').classList.remove('hidden');
  document.getElementById(tabName + '-tab').classList.add('active');
  
  // Update active button
  document.querySelectorAll('[data-tab]').forEach(btn => {
    btn.classList.remove('bg-purple-600', 'text-white');
    btn.classList.add('text-gray-700');
  });
  document.querySelector(`[data-tab="${tabName}"]`).classList.add('bg-purple-600', 'text-white');
  document.querySelector(`[data-tab="${tabName}"]`).classList.remove('text-gray-700');
}

// Initialize first tab
showTab('info');
</script>
@endsection
