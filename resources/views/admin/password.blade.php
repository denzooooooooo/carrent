@extends('admin.layouts.app')

@section('title', 'Changer Mot de Passe')

@section('content')

    <div class="max-w-2xl mx-auto py-8">
        <h1 class="text-3xl font-bold mb-8 text-dark gradient-text border-b pb-2">Changer mon Mot de Passe</h1>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 animate-fadew">
            <p class="text-gray-600 mb-6 font-poppins">Pour votre sécurité, veuillez choisir un mot de passe fort et unique.
            </p>

            <form action="{{ route('admin.password.update') }}" method="POST">
                @csrf
                @method('PUT') {{-- La méthode PUT est utilisée pour les mises à jour --}}

                <div class="space-y-4">
                    <!-- Mot de Passe Actuel -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de Passe
                            Actuel</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('current_password') border-red-500 @enderror">
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nouveau Mot de Passe -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau Mot de Passe
                            (Min. 8 caractères)</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmation du Nouveau Mot de Passe -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le
                            Nouveau Mot de Passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">
                    </div>
                </div>

                <!-- Bouton de Soumission -->
                <div class="mt-6">
                    <button type="submit"
                        class="w-full py-3 px-4 rounded-lg text-white font-semibold bg-primary hover:bg-purple-700 transition duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        <i class="fas fa-key mr-2"></i> Changer le Mot de Passe
                    </button>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('admin.profile') }}"
                        class="text-sm font-semibold text-gray-500 hover:text-primary transition duration-150 hover:underline">
                        <i class="fas fa-arrow-left mr-1"></i> Retourner au Profil
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection