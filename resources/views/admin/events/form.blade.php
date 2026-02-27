@extends('admin.layouts.app')

@section('title', $pageTitle)

@section('content')

<div class="max-w-6xl mx-auto py-8">
<div class="flex justify-between items-center mb-8 border-b pb-2">
<h1 class="text-3xl font-bold text-dark gradient-text">{{ $pageTitle }}</h1>
<a href="{{ route('admin.events.index') }}" class="py-2 px-4 rounded-lg text-white font-semibold bg-gray-600 hover:bg-gray-700 transition duration-300 shadow-md flex items-center">
<i class="fas fa-arrow-left mr-2"></i> Retour à la liste
</a>
</div>

@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Erreur!</strong>
        <span class="block sm:inline">{!! session('error') !!}</span>
    </div>
@endif

{{-- Debug: Afficher les erreurs de validation --}}
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Erreurs de validation:</strong>
        <ul class="mt-2">
            @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Debug: Afficher les données du formulaire soumis --}}
@if (session('debug_data'))
    <div style="background: #eef; border: 1px solid #ccf; color: #33c; padding: 10px; margin-bottom: 10px;">
        <strong>Debug Data:</strong>
        <pre style="font-size: 12px;">{{ json_encode(session('debug_data'), JSON_PRETTY_PRINT) }}</pre>
    </div>
@endif

@if (session('debug_logs'))
    <div style="background: #fef; border: 1px solid #fcf; color: #c3c; padding: 10px; margin-bottom: 10px;">
        <strong>Debug Logs:</strong>
        <pre style="font-size: 12px;">{{ session('debug_logs') }}</pre>
    </div>
@endif

<div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-100">

    @php
        $isEdit = $event->exists;
        $route = $isEdit ? route('admin.events.update', $event) : route('admin.events.store');
    @endphp

    <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        {{-- SECTION 1: INFORMATIONS GÉNÉRALES --}}
        <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2">1. Informations Clés</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            {{-- Catégorie --}}
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <select name="category_id" id="category_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('category_id') border-red-500 @enderror">
                    <option value="">Sélectionner une catégorie</option>
                    @foreach($categories as $id => $name)
                        <option value="{{ $id }}" {{ old('category_id', $event->category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Type (Optionnel) --}}
            <div>
                <label for="type_id" class="block text-sm font-medium text-gray-700 mb-1">Type d'Événement (Optionnel)</label>
                <select name="type_id" id="type_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('type_id') border-red-500 @enderror">
                    <option value="">Sélectionner un type</option>
                    @foreach($types as $id => $name)
                        <option value="{{ $id }}" {{ old('type_id', $event->event_type) == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('type_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Organisateur --}}
            <div>
                <label for="organizer" class="block text-sm font-medium text-gray-700 mb-1">Organisateur</label>
                <input type="text" name="organizer" id="organizer"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('organizer') border-red-500 @enderror"
                    value="{{ old('organizer', $event->organizer) }}">
                @error('organizer')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- SECTION 2: TITRES ET DESCRIPTIONS --}}
        <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2 mt-8">2. Contenu (Français & Anglais)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Titre FR --}}
            <div>
                <label for="title_fr" class="block text-sm font-medium text-gray-700 mb-1">Titre (Français)</label>
                <input type="text" name="title_fr" id="title_fr" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('title_fr') border-red-500 @enderror"
                    value="{{ old('title_fr', $event->title_fr) }}">
                @error('title_fr')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            {{-- Titre EN --}}
            <div>
                <label for="title_en" class="block text-sm font-medium text-gray-700 mb-1">Titre (Anglais)</label>
                <input type="text" name="title_en" id="title_en" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('title_en') border-red-500 @enderror"
                    value="{{ old('title_en', $event->title_en) }}">
                @error('title_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            {{-- Description FR --}}
            <div class="md:col-span-1">
                <label for="description_fr" class="block text-sm font-medium text-gray-700 mb-1">Description (Français)</label>
                <textarea name="description_fr" id="description_fr" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('description_fr') border-red-500 @enderror">{{ old('description_fr', $event->description_fr) }}</textarea>
                @error('description_fr')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            {{-- Description EN --}}
            <div class="md:col-span-1">
                <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1">Description (Anglais)</label>
                <textarea name="description_en" id="description_en" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('description_en') border-red-500 @enderror">{{ old('description_en', $event->description_en) }}</textarea>
                @error('description_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- SECTION 3: DATE ET LIEU --}}
        <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2 mt-8">3. Date & Lieu</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            {{-- Date de début --}}
            <div>
                <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Date de Début</label>
                <input type="date" name="event_date" id="event_date" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('event_date') border-red-500 @enderror"
                    value="{{ old('event_date', $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') : '') }}">
                @error('event_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            {{-- Heure de début --}}
            <div>
                <label for="event_time" class="block text-sm font-medium text-gray-700 mb-1">Heure de Début</label>
                <input type="time" name="event_time" id="event_time" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('event_time') border-red-500 @enderror"
                    value="{{ old('event_time', $event->event_time) }}">
                @error('event_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            {{-- Date de fin (Optionnel) --}}
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date de Fin (Optionnel)</label>
                <input type="date" name="end_date" id="end_date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('end_date') border-red-500 @enderror"
                    value="{{ old('end_date', $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d') : '') }}">
                @error('end_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
             {{-- Heure de fin (Optionnel) --}}
            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Heure de Fin (Optionnel)</label>
                <input type="time" name="end_time" id="end_time"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('end_time') border-red-500 @enderror"
                    value="{{ old('end_time', $event->end_time) }}">
                @error('end_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Nom du lieu --}}
            <div class="md:col-span-2">
                <label for="venue_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du Lieu (Ex: Stade de France)</label>
                <input type="text" name="venue_name" id="venue_name" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('venue_name') border-red-500 @enderror"
                    value="{{ old('venue_name', $event->venue_name) }}">
                @error('venue_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Adresse complète --}}
            <div class="md:col-span-2">
                <label for="venue_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse Complète</label>
                <input type="text" name="venue_address" id="venue_address" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('venue_address') border-red-500 @enderror"
                    value="{{ old('venue_address', $event->venue_address) }}">
                @error('venue_address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Ville --}}
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                <input type="text" name="city" id="city" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('city') border-red-500 @enderror"
                    value="{{ old('city', $event->city) }}">
                @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Pays --}}
            <div>
                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                <input type="text" name="country" id="country" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('country') border-red-500 @enderror"
                    value="{{ old('country', $event->country) }}">
                @error('country')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- SECTION 4: TARIFS ET CAPACITÉ --}}
        <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2 mt-8">4. Tarifs & Capacité</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            {{-- Prix Minimum --}}
            <div>
                <label for="min_price" class="block text-sm font-medium text-gray-700 mb-1">Prix Min. (FCFA)</label>
                <input type="number" name="min_price" id="min_price" required min="0" step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('min_price') border-red-500 @enderror"
                    value="{{ old('min_price', $event->min_price) }}">
                @error('min_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            {{-- Prix Maximum --}}
            <div>
                <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">Prix Max. (FCFA) (Optionnel)</label>
                <input type="number" name="max_price" id="max_price" min="0" step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('max_price') border-red-500 @enderror"
                    value="{{ old('max_price', $event->max_price) }}">
                @error('max_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            {{-- Capacité Totale --}}
            <div>
                <label for="total_seats" class="block text-sm font-medium text-gray-700 mb-1">Capacité Totale (Sièges)</label>
                <input type="number" name="total_seats" id="total_seats" required min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('total_seats') border-red-500 @enderror"
                    value="{{ old('total_seats', $event->total_seats) }}">
                @error('total_seats')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>



        {{-- SECTION 5: IMAGE ET OPTIONS --}}
        <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2 mt-8">5. Média & Visibilité</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

            {{-- UPLOAD IMAGE --}}
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image Principale (Collection: avatar, Max 2MB)</label>
                <input type="file" name="image" id="image"
                    class="w-full p-2 border border-gray-300 rounded-lg bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-purple-700 transition duration-150 @error('image') border-red-500 @enderror">
                @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- APERÇU DE L'IMAGE EXISTANTE --}}
            @if ($isEdit && $event->getFirstMediaUrl('avatar'))
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image Actuelle</label>
                    <div class="flex items-center space-x-4">
                        <img src="{{ $event->getFirstMediaUrl('avatar', 'small') }}" alt="Image actuelle" class="w-24 h-24 object-cover rounded-lg border border-gray-200 shadow-md">
                        <label class="flex items-center text-sm text-red-600">
                            <input type="checkbox" name="remove_image" value="1" class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="ml-2">Supprimer l'image ?</span>
                        </label>
                    </div>
                </div>
            @endif
        </div>

        {{-- CHECKBOXES --}}
        <div class="flex items-center space-x-6 mt-4">
            {{-- Est Actif --}}
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    class="h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary"
                    {{ old('is_active', $event->is_active ?? true) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Actif (Visible sur le site)</label>
            </div>
            {{-- Est Mis en Avant --}}
            <div class="flex items-center">
                <input type="checkbox" name="is_featured" id="is_featured" value="1"
                    class="h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary"
                    {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}>
                <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">Mis en Avant (Featured)</label>
            </div>
        </div>


        {{-- SECTION 6: PACKAGES (GRILLES TARIFAIRES) --}}
        <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2 mt-8">6. Packages & Grilles Tarifaires</h2>
        
        <div id="packagesContainer">
            @if($event->exists && $event->packages->count() > 0)
                @foreach($event->packages as $index => $package)
                <div class="package-item bg-purple-50 p-6 rounded-lg border border-purple-200 mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-purple-800">Package {{ $index + 1 }}</h3>
                        <button type="button" class="remove-package-btn text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                    <input type="hidden" name="packages[{{ $index }}][id]" value="{{ $package->id }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du Package (Français)</label>
                            <input type="text" name="packages[{{ $index }}][package_name_fr]" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                value="{{ old('packages.' . $index . '.package_name_fr', $package->package_name_fr) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du Package (Anglais)</label>
                            <input type="text" name="packages[{{ $index }}][package_name_en]"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                value="{{ old('packages.' . $index . '.package_name_en', $package->package_name_en) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code Package</label>
                            <input type="text" name="packages[{{ $index }}][package_code]"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                value="{{ old('packages.' . $index . '.package_code', $package->package_code) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prix (FCFA)</label>
                            <input type="number" name="packages[{{ $index }}][price]" required min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                value="{{ old('packages.' . $index . '.price', $package->price) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantité Disponible</label>
                            <input type="number" name="packages[{{ $index }}][available_quantity]" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                value="{{ old('packages.' . $index . '.available_quantity', $package->available_quantity) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max par Commande</label>
                            <input type="number" name="packages[{{ $index }}][max_per_order]" min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                value="{{ old('packages.' . $index . '.max_per_order', $package->max_per_order) }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description (Français)</label>
                            <textarea name="packages[{{ $index }}][description_fr]" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('packages.' . $index . '.description_fr', $package->description_fr) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Inclus (Français)</label>
                            <textarea name="packages[{{ $index }}][description_included_fr]" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('packages.' . $index . '.description_included_fr', $package->description_included_fr) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Devise</label>
                            <select name="packages[{{ $index }}][currency]"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                <option value="XOF" {{ old('packages.' . $index . '.currency', $package->currency) == 'XOF' ? 'selected' : '' }}>XOF (FCFA)</option>
                                <option value="EUR" {{ old('packages.' . $index . '.currency', $package->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="USD" {{ old('packages.' . $index . '.currency', $package->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ordre d'Affichage</label>
                            <input type="number" name="packages[{{ $index }}][sort_order]" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                value="{{ old('packages.' . $index . '.sort_order', $package->sort_order) }}">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="packages[{{ $index }}][is_active]" id="package_active_{{ $index }}" value="1" {{ $package->is_active ? 'checked' : '' }}
                                class="h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="package_active_{{ $index }}" class="ml-2 text-sm font-medium text-gray-700">Package Actif</label>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <button type="button" id="addPackageBtn" class="mt-4 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-150 flex items-center">
            <i class="fas fa-plus mr-2"></i> Ajouter un Package
        </button>

        {{-- BOUTON DE SOUMISSION --}}
        <div class="mt-8 pt-4 border-t">
            <button type="submit"
                class="w-full py-3 px-4 rounded-lg text-white font-semibold bg-primary hover:bg-purple-700 transition duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-primary/50">
                <i class="fas fa-{{ $isEdit ? 'save' : 'plus-circle' }} mr-2"></i> {{ $isEdit ? 'Enregistrer les Modifications' : 'Créer l\'Événement' }}
            </button>
        </div>
    </form>

</div>

</div>

{{-- JavaScript pour gérer l'ajout de packages --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addPackageBtn = document.getElementById('addPackageBtn');
    const packagesContainer = document.getElementById('packagesContainer');
    let packageIndex = {{ $event->exists ? $event->packages->count() : 0 }};

    if (addPackageBtn && packagesContainer) {
        addPackageBtn.addEventListener('click', function() {
            const template = `
                <div class="package-item bg-purple-50 p-6 rounded-lg border border-purple-200 mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-purple-800">Package ${packageIndex + 1}</h3>
                        <button type="button" class="remove-package-btn text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du Package (Français)</label>
                            <input type="text" name="packages[${packageIndex}][package_name_fr]" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                placeholder="VIP Premium">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du Package (Anglais)</label>
                            <input type="text" name="packages[${packageIndex}][package_name_en]"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                placeholder="Premium VIP">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code Package</label>
                            <input type="text" name="packages[${packageIndex}][package_code]"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                placeholder="VIP-001">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prix (FCFA)</label>
                            <input type="number" name="packages[${packageIndex}][price]" required min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantité Disponible</label>
                            <input type="number" name="packages[${packageIndex}][available_quantity]" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                value="100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max par Commande</label>
                            <input type="number" name="packages[${packageIndex}][max_per_order]" min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                value="10">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description (Français)</label>
                            <textarea name="packages[${packageIndex}][description_fr]" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                placeholder="Description du package..."></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Inclus (Français)</label>
                            <textarea name="packages[${packageIndex}][description_included_fr]" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                placeholder="Accès au match, Boissons incluses, Cadeau souvenir..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Devise</label>
                            <select name="packages[${packageIndex}][currency]"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                <option value="XOF">XOF (FCFA)</option>
                                <option value="EUR">EUR</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ordre d'Affichage</label>
                            <input type="number" name="packages[${packageIndex}][sort_order]" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                value="${packageIndex + 1}">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="packages[${packageIndex}][is_active]" id="package_active_${packageIndex}" value="1" checked
                                class="h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="package_active_${packageIndex}" class="ml-2 text-sm font-medium text-gray-700">Package Actif</label>
                        </div>
                    </div>
                </div>
            `;
            packagesContainer.insertAdjacentHTML('beforeend', template);
            packageIndex++;
        });

        // Supprimer un package
        packagesContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-package-btn')) {
                e.target.closest('.package-item').remove();
            }
        });
    }
});
</script>

@endsection
