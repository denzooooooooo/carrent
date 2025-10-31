@extends('layouts.app')

@section('title', 'Mon Profil - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 mb-2">Mon Profil</h1>
                        <p class="text-gray-600">Gérez vos informations personnelles et vos préférences</p>
                    </div>
                    <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Informations Personnelles</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nom complet *
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', auth()->user()->name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
                                    required
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email *
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', auth()->user()->email) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
                                    required
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Téléphone
                                </label>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone', auth()->user()->phone) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
                                >
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Date de naissance
                                </label>
                                <input
                                    type="date"
                                    id="date_of_birth"
                                    name="date_of_birth"
                                    value="{{ old('date_of_birth', auth()->user()->date_of_birth?->format('Y-m-d')) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
                                >
                                @error('date_of_birth')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Preferences -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Préférences</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Preferred Currency -->
                            <div>
                                <label for="preferred_currency" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Devise préférée
                                </label>
                                <select
                                    id="preferred_currency"
                                    name="preferred_currency"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
                                >
                                    <option value="XOF" {{ auth()->user()->preferred_currency === 'XOF' ? 'selected' : '' }}>Franc CFA (XOF)</option>
                                    <option value="EUR" {{ auth()->user()->preferred_currency === 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                    <option value="USD" {{ auth()->user()->preferred_currency === 'USD' ? 'selected' : '' }}>Dollar US (USD)</option>
                                    <option value="GBP" {{ auth()->user()->preferred_currency === 'GBP' ? 'selected' : '' }}>Livre Sterling (GBP)</option>
                                </select>
                                @error('preferred_currency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Preferred Language -->
                            <div>
                                <label for="preferred_language" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Langue préférée
                                </label>
                                <select
                                    id="preferred_language"
                                    name="preferred_language"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
                                >
                                    <option value="fr" {{ auth()->user()->preferred_language === 'fr' ? 'selected' : '' }}>Français</option>
                                    <option value="en" {{ auth()->user()->preferred_language === 'en' ? 'selected' : '' }}>English</option>
                                </select>
                                @error('preferred_language')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Adresse</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Adresse
                                </label>
                                <input
                                    type="text"
                                    id="address"
                                    name="address"
                                    value="{{ old('address', auth()->user()->address) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
                                    placeholder="Votre adresse complète"
                                >
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Ville
                                </label>
                                <input
                                    type="text"
                                    id="city"
                                    name="city"
                                    value="{{ old('city', auth()->user()->city) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
                                >
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Pays
                                </label>
                                <input
                                    type="text"
                                    id="country"
                                    name="country"
                                    value="{{ old('country', auth()->user()->country) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
                                >
                                @error('country')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t border-gray-200">
                        <button
                            type="submit"
                            class="px-8 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors"
                        >
                            Mettre à jour le profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
