@extends('admin.layouts.app')

@section('title', 'Modifier le Package: ' . $package->title_fr)

@section('content')

    @php
        // Décoder les données JSON existantes pour les afficher dans les textareas
        $includedServicesJson = json_encode($package->included_services_fr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $excludedServicesJson = json_encode($package->excluded_services_fr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $itineraryJson = json_encode($package->itinerary_fr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Récupérer l'URL de l'image principale pour l'affichage
        $currentAvatarUrl = $package->getFirstMediaUrl('avatar', 'thumb') ?: 'https://placehold.co/150x150/d1d5db/ffffff?text=Pas+d\'Image';


    @endphp

    <div class="max-w-6xl mx-auto py-8">
        <div class="flex justify-between items-center mb-8 border-b pb-2">
            <h1 class="text-3xl font-bold text-dark gradient-text">Modifier : <span
                    class="text-primary">{{ $package->title_fr }}</span></h1>
            <a href="{{ route('admin.packages.index') }}"
                class="py-2 px-4 rounded-lg text-white font-semibold bg-gray-600 hover:bg-gray-700 transition duration-300 shadow-md flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>

        {{-- Le formulaire utilise la méthode POST, et la directive @method('PUT') simule la requête PUT --}}
        <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white p-6 rounded-xl shadow-2xl border border-gray-100 space-y-8">

                {{-- SECTION 1: INFORMATIONS GÉNÉRALES --}}
                <div class="border-b pb-6">
                    <h2 class="text-2xl font-semibold text-primary mb-4"><i class="fas fa-globe-europe mr-2"></i>
                        Informations Clés</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Titre (Français) --}}
                        <div>
                            <label for="title_fr" class="block text-sm font-medium text-gray-700 mb-1 required">Titre du
                                Package (Français)</label>
                            <input type="text" name="title_fr" id="title_fr" required class="form-input"
                                value="{{ old('title_fr', $package->title_fr) }}">
                            @error('title_fr')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Titre (Anglais) --}}
                        <div>
                            <label for="title_en" class="block text-sm font-medium text-gray-700 mb-1">Titre du Package
                                (Anglais)</label>
                            <input type="text" name="title_en" id="title_en" class="form-input"
                                value="{{ old('title_en', $package->title_en) }}">
                            @error('title_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Catégorie --}}
                        <div>
                            <label for="category_id"
                                class="block text-sm font-medium text-gray-700 mb-1 required">Catégorie</label>
                            <select name="category_id" id="category_id" required class="form-select">
                                <option value="">Sélectionner une catégorie</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category_id', $package->category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Type de Package (Enum) --}}
                        <div>
                            <label for="package_type" class="block text-sm font-medium text-gray-700 mb-1 required">Type de
                                Voyage</label>
                            <select name="package_type" id="package_type" required class="form-select">
                                <option value="">Sélectionner un type</option>
                                @foreach ($packageTypes as $key => $value)
                                    <option value="{{ $key }}" {{ old('package_type', $package->package_type) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('package_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Destination --}}
                        <div>
                            <label for="destination"
                                class="block text-sm font-medium text-gray-700 mb-1 required">Destination Principale</label>
                            <input type="text" name="destination" id="destination" required class="form-input"
                                value="{{ old('destination', $package->destination) }}">
                            @error('destination')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Durée (Jours) --}}
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-1 required">Durée (en
                                jours)</label>
                            <input type="number" name="duration" id="duration" required min="1" class="form-input"
                                value="{{ old('duration', $package->duration) }}">
                            @error('duration')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Description (Français) --}}
                    <div class="mt-4">
                        <label for="description_fr" class="block text-sm font-medium text-gray-700 mb-1">Description
                            (Français)</label>
                        <textarea name="description_fr" id="description_fr" rows="4"
                            class="form-textarea">{{ old('description_fr', $package->description_fr) }}</textarea>
                        @error('description_fr')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- SECTION 2: TARIFS ET CAPACITÉ --}}
                <div class="border-b pb-6">
                    <h2 class="text-2xl font-semibold text-primary mb-4"><i class="fas fa-tag mr-2"></i> Prix & Capacité
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Prix de Base --}}
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1 required">Prix de Base
                                (€)</label>
                            <input type="number" step="0.01" name="price" id="price" required min="0" class="form-input"
                                value="{{ old('price', $package->price) }}">
                            @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Prix Réduit --}}
                        <div>
                            <label for="discount_price" class="block text-sm font-medium text-gray-700 mb-1">Prix Réduit
                                (€)</label>
                            <input type="number" step="0.01" name="discount_price" id="discount_price" class="form-input"
                                value="{{ old('discount_price', $package->discount_price) }}">
                            @error('discount_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Participants Max --}}
                        <div>
                            <label for="max_participants"
                                class="block text-sm font-medium text-gray-700 mb-1 required">Participants Max</label>
                            <input type="number" name="max_participants" id="max_participants" required min="1"
                                class="form-input" value="{{ old('max_participants', $package->max_participants) }}">
                            @error('max_participants')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: ITINÉRAIRE & SERVICES (JSON INPUTS) --}}
                <div class="border-b pb-6">
                    <h2 class="text-2xl font-semibold text-primary mb-4"><i class="fas fa-list-ol mr-2"></i> Itinéraire &
                        Services</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Services Inclus (JSON Array) --}}
                        <div>
                            <label for="included_services_fr_json"
                                class="block text-sm font-medium text-gray-700 mb-1">Services Inclus (JSON Array)</label>
                            <textarea name="included_services_fr_json" id="included_services_fr_json" rows="6"
                                class="form-textarea font-mono text-xs">{{ old('included_services_fr_json', $includedServicesJson) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Format JSON Array requis : `["Service 1", "Service 2",
                                ...]`</p>
                            @error('included_services_fr_json')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Services Exclus (JSON Array) --}}
                        <div>
                            <label for="excluded_services_fr_json"
                                class="block text-sm font-medium text-gray-700 mb-1">Services Exclus (JSON Array)</label>
                            <textarea name="excluded_services_fr_json" id="excluded_services_fr_json" rows="6"
                                class="form-textarea font-mono text-xs">{{ old('excluded_services_fr_json', $excludedServicesJson) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Format JSON Array requis : `["Exclu 1", "Exclu 2", ...]`
                            </p>
                            @error('excluded_services_fr_json')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Itinéraire (JSON Array d'Objets) --}}
                    <div class="mt-6">
                        <label for="itinerary_fr_json" class="block text-sm font-medium text-gray-700 mb-1">Itinéraire Jour
                            par Jour (JSON Array d'Objets)</label>
                        <textarea name="itinerary_fr_json" id="itinerary_fr_json" rows="8"
                            class="form-textarea font-mono text-xs">{{ old('itinerary_fr_json', $itineraryJson) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Format JSON Array d'objets : `[{"day": N, "title": "Titre",
                            "description": "Détails"}, ...]`</p>
                        @error('itinerary_fr_json')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- SECTION 4: MÉDIAS ET STATUT --}}
                <div class="pb-6">
                    <h2 class="text-2xl font-semibold text-primary mb-4"><i class="fas fa-camera mr-2"></i> Médias & Statut
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">

                        {{-- Image Actuelle --}}
                        <div class="flex flex-col items-center">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image Actuelle</label>
                            <img src="{{ $currentAvatarUrl }}" alt="Avatar actuel"
                                class="w-24 h-24 object-cover rounded-full border-2 border-primary shadow-lg">
                        </div>

                        {{-- Nouvelle Image Principale (Avatar) --}}
                        <div class="md:col-span-2">
                            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Remplacer l'Image
                                Principale (Optionnel)</label>
                            <input type="file" name="avatar" id="avatar" accept="image/*" class="form-input-file">
                            <p class="text-xs text-gray-500 mt-1">L'ancienne image sera supprimée si un nouveau fichier est
                                sélectionné.</p>
                            @error('avatar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Galerie Photos (Note: la gestion de la galerie est plus complexe en CRUD simple. Ici, nous
                        gérons seulement l'upload.) --}}
                        {{-- <div>
                            <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-1">Ajouter des
                                Images à la Galerie</label>
                            <input type="file" name="gallery_images[]" id="gallery_images" multiple accept="image/*"
                                class="form-input-file">
                            <p class="text-xs text-gray-500 mt-1">Les images existantes ne sont pas supprimées par défaut
                                ici.</p>
                            @error('gallery_images')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div> --}}

                        {{-- Statuts --}}
                        <div class="flex items-center space-x-6 col-span-full mt-4">
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="is_active" {{ old('is_active', $package->is_active) ? 'checked' : '' }} class="form-checkbox text-primary rounded">
                                <span class="text-sm font-medium text-gray-700">Activer le Package</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="is_featured" {{ old('is_featured', $package->is_featured) ? 'checked' : '' }} class="form-checkbox text-primary rounded">
                                <span class="text-sm font-medium text-gray-700">Mettre en Avant</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- BOUTON DE SOUMISSION --}}
                <div class="mt-8 pt-4 border-t">
                    <button type="submit"
                        class="w-full py-3 px-4 rounded-lg text-white font-semibold bg-green-600 hover:bg-green-700 transition duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i> Enregistrer les Modifications
                    </button>
                </div>

            </div>
        </form>


    </div>

    {{-- Styles Tailwind spécifiques --}}

    <style>
        .form-input,
        .form-select,
        .form-textarea {
            @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150;
        }

        .form-textarea {
            @apply resize-y;
        }

        .form-input-file {
            @apply w-full p-2 border border-gray-300 rounded-lg bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-purple-700 transition duration-150;
        }

        .required:after {
            content: " ";
            color: #ef4444;/ red-500 */
        }
    </style>

@endsection