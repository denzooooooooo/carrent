@extends('layouts.app')

@section('title', __('Informations passagers'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Progress -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-blue-600">Étape 2/4</span>
                <span class="text-sm text-gray-600 dark:text-gray-400">Informations passagers</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: 50%"></div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 md:p-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                Informations des passagers
            </h1>

            <form action="{{ route('flights.review') }}" method="POST" id="passengersForm">
                @csrf

                @php
                    $passengerIndex = 0;
                @endphp

                <!-- Adultes -->
                @for($i = 0; $i < $adults; $i++)
                <div class="mb-8 p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Adulte {{ $i + 1 }}
                    </h3>

                    <input type="hidden" name="passengers[{{ $passengerIndex }}][type]" value="adult">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Civilité -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Civilité <span class="text-red-500">*</span>
                            </label>
                            <select name="passengers[{{ $passengerIndex }}][title]" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Sélectionner</option>
                                <option value="mr">M.</option>
                                <option value="mrs">Mme</option>
                                <option value="miss">Mlle</option>
                            </select>
                        </div>

                        <!-- Genre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Genre <span class="text-red-500">*</span>
                            </label>
                            <select name="passengers[{{ $passengerIndex }}][gender]" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Sélectionner</option>
                                <option value="m">Masculin</option>
                                <option value="f">Féminin</option>
                            </select>
                        </div>

                        <!-- Prénom -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Prénom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="passengers[{{ $passengerIndex }}][first_name]" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Prénom">
                        </div>

                        <!-- Nom -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="passengers[{{ $passengerIndex }}][last_name]" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Nom">
                        </div>

                        <!-- Date de naissance -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date de naissance <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="passengers[{{ $passengerIndex }}][born_on]" required
                                max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>

                        <!-- Nationalité -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nationalité <span class="text-red-500">*</span>
                            </label>
                            <select name="passengers[{{ $passengerIndex }}][nationality]" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Sélectionner</option>
                                <option value="CI">Côte d'Ivoire</option>
                                <option value="FR">France</option>
                                <option value="US">États-Unis</option>
                                <option value="GB">Royaume-Uni</option>
                                <option value="SN">Sénégal</option>
                                <option value="ML">Mali</option>
                                <option value="BJ">Bénin</option>
                                <option value="TG">Togo</option>
                                <option value="GH">Ghana</option>
                                <option value="NG">Nigeria</option>
                            </select>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="passengers[{{ $passengerIndex }}][email]" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="email@example.com">
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Téléphone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="passengers[{{ $passengerIndex }}][phone]" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="+225 XX XX XX XX XX">
                        </div>
                    </div>

                    <!-- Document d'identité -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-4">Document d'identité</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Type de document -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Type de document <span class="text-red-500">*</span>
                                </label>
                                <select name="passengers[{{ $passengerIndex }}][identity_document_type]" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">Sélectionner</option>
                                    <option value="passport">Passeport</option>
                                    <option value="national_id">Carte d'identité</option>
                                </select>
                            </div>

                            <!-- Numéro -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Numéro <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="passengers[{{ $passengerIndex }}][identity_document_number]" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white uppercase"
                                    placeholder="XX123456">
                            </div>

                            <!-- Date d'expiration -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Date d'expiration <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="passengers[{{ $passengerIndex }}][identity_document_expiry]" required
                                    min="{{ date('Y-m-d', strtotime('+6 months')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            </div>

                            <!-- Pays émetteur -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pays émetteur <span class="text-red-500">*</span>
                                </label>
                                <select name="passengers[{{ $passengerIndex }}][identity_document_issuing_country]" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">Sélectionner</option>
                                    <option value="CI">Côte d'Ivoire</option>
                                    <option value="FR">France</option>
                                    <option value="US">États-Unis</option>
                                    <option value="GB">Royaume-Uni</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                @php $passengerIndex++; @endphp
                @endfor

                <!-- Enfants -->
                @for($i = 0; $i < $children; $i++)
                <div class="mb-8 p-6 border border-gray-200 dark:border-gray-700 rounded-lg bg-blue-50 dark:bg-blue-900/10">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Enfant {{ $i + 1 }} (2-11 ans)
                    </h3>
                    <input type="hidden" name="passengers[{{ $passengerIndex }}][type]" value="child">
                    <!-- Mêmes champs que adulte mais avec max age différent -->
                    <p class="text-sm text-gray-600 dark:text-gray-400">Formulaire similaire aux adultes...</p>
                </div>
                @php $passengerIndex++; @endphp
                @endfor

                <!-- Bébés -->
                @for($i = 0; $i < $infants; $i++)
                <div class="mb-8 p-6 border border-gray-200 dark:border-gray-700 rounded-lg bg-green-50 dark:bg-green-900/10">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Bébé {{ $i + 1 }} (moins de 2 ans)
                    </h3>
                    <input type="hidden" name="passengers[{{ $passengerIndex }}][type]" value="infant">
                    <!-- Formulaire simplifié pour bébés -->
                    <p class="text-sm text-gray-600 dark:text-gray-400">Formulaire simplifié...</p>
                </div>
                @php $passengerIndex++; @endphp
                @endfor

                <!-- Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('flights.results') }}" 
                       class="px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        ← Retour
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        Continuer →
                    </button>
                </div>
            </form>
        </div>

        <!-- Info -->
        <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <p class="text-sm text-blue-800 dark:text-blue-200">
                ℹ️ Les informations doivent correspondre exactement à celles de vos documents de voyage.
            </p>
        </div>
    </div>
</div>
@endsection
