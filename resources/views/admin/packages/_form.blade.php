@php
// --- Logique pour gérer les données JSON et les valeurs OLD() ---
// Si une erreur de validation survient, l'input JSON brut est conservé (old()).
// Sinon, on encode les données existantes du package (pour l'édition) ou un tableau vide (pour la création).
$oldIncluded = old('included_services_fr_json');
$oldExcluded = old('excluded_services_fr_json');
$oldItinerary = old('itinerary_fr_json');

$includedServicesJson = $oldIncluded ?: json_encode($package->included_services_fr ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$excludedServicesJson = $oldExcluded ?: json_encode($package->excluded_services_fr ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$itineraryJson = $oldItinerary ?: json_encode($package->itinerary_fr ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// Récupérer l'URL de l'image principale pour l'affichage (seulement pertinent lors de l'édition)
$currentAvatarUrl = $package->getFirstMediaUrl('avatar', 'thumb') ?: 'https://placehold.co/150x150/d1d5db/ffffff?text=Pas+d\'Image';


@endphp

<div class="bg-white p-6 rounded-xl shadow-2xl border border-gray-100 space-y-8">

{{-- SECTION 1: INFORMATIONS GÉNÉRALES --}}
<div class="border-b pb-6">
    <h2 class="text-2xl font-semibold text-primary mb-4"><i class="fas fa-globe-europe mr-2"></i> Informations Clés</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Titre (Français) --}}
        <div>
            <label for="title_fr" class="block text-sm font-medium text-gray-700 mb-1 required">Titre du Package (Français)</label>
            <input type="text" name="title_fr" id="title_fr" required
                class="form-input @error('title_fr') border-red-500 @enderror" value="{{ old('title_fr', $package->title_fr) }}">
            @error('title_fr')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
        {{-- Titre (Anglais) --}}
        <div>
            <label for="title_en" class="block text-sm font-medium text-gray-700 mb-1 required">Titre du Package (Anglais)</label>
            <input type="text" name="title_en" id="title_en" required
                class="form-input @error('title_en') border-red-500 @enderror" value="{{ old('title_en', $package->title_en) }}">
            @error('title_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Catégorie --}}
        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1 required">Catégorie</label>
            <select name="category_id" id="category_id" required class="form-select @error('category_id') border-red-500 @enderror">
                <option value="">Sélectionner une catégorie</option>
                @foreach ($categories as $id => $name)
                    <option value="{{ $id }}" {{ old('category_id', $package->category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Type de Package (Enum) --}}
        <div>
            <label for="package_type" class="block text-sm font-medium text-gray-700 mb-1 required">Type de Voyage</label>
            <select name="package_type" id="package_type" required class="form-select @error('package_type') border-red-500 @enderror">
                <option value="">Sélectionner un type</option>
                @foreach ($packageTypes as $key => $value)
                    <option value="{{ $key }}" {{ old('package_type', $package->package_type) == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @error('package_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
        {{-- Destination --}}
        <div>
            <label for="destination" class="block text-sm font-medium text-gray-700 mb-1 required">Destination Principale</label>
            <input type="text" name="destination" id="destination" required
                class="form-input @error('destination') border-red-500 @enderror" value="{{ old('destination', $package->destination) }}">
            @error('destination')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Durée (Jours) --}}
        <div>
            <label for="duration" class="block text-sm font-medium text-gray-700 mb-1 required">Durée (en jours)</label>
            <input type="number" name="duration" id="duration" required min="1"
                class="form-input @error('duration') border-red-500 @enderror" value="{{ old('duration', $package->duration) }}">
            @error('duration')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Description (Français) --}}
    <div class="mt-4">
        <label for="description_fr" class="block text-sm font-medium text-gray-700 mb-1">Description (Français)</label>
        <textarea name="description_fr" id="description_fr" rows="4" class="form-textarea @error('description_fr') border-red-500 @enderror">{{ old('description_fr', $package->description_fr) }}</textarea>
        @error('description_fr')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
</div>

{{-- SECTION 2: TARIFS ET CAPACITÉ --}}
<div class="border-b pb-6">
    <h2 class="text-2xl font-semibold text-primary mb-4"><i class="fas fa-tag mr-2"></i> Prix & Capacité</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Prix de Base --}}
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700 mb-1 required">Prix de Base (€)</label>
            <input type="number" step="0.01" name="price" id="price" required min="0"
                class="form-input @error('price') border-red-500 @enderror" value="{{ old('price', $package->price) }}">
            @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
        {{-- Prix Réduit --}}
        <div>
            <label for="discount_price" class="block text-sm font-medium text-gray-700 mb-1">Prix Réduit (€)</label>
            <input type="number" step="0.01" name="discount_price" id="discount_price"
                class="form-input @error('discount_price') border-red-500 @enderror" value="{{ old('discount_price', $package->discount_price) }}">
            @error('discount_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Participants Max --}}
        <div>
            <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-1 required">Participants Max</label>
            <input type="number" name="max_participants" id="max_participants" required min="1"
                class="form-input @error('max_participants') border-red-500 @enderror" value="{{ old('max_participants', $package->max_participants) }}">
            @error('max_participants')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- SECTION 3: ITINÉRAIRE & SERVICES (JSON INPUTS) --}}
<div class="border-b pb-6">
    <h2 class="text-2xl font-semibold text-primary mb-4"><i class="fas fa-list-ol mr-2"></i> Itinéraire & Services</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Services Inclus (JSON Array) --}}
        <div>
            <label for="included_services_fr_json" class="block text-sm font-medium text-gray-700 mb-1">Services Inclus (JSON Array)</label>
            <textarea name="included_services_fr_json" id="included_services_fr_json" rows="6" class="form-textarea font-mono text-xs @error('included_services_fr_json') border-red-500 @enderror">{{ $includedServicesJson }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Format JSON Array requis : `["Service 1", "Service 2", ...]`</p>
            @error('included_services_fr_json')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Services Exclus (JSON Array) --}}
        <div>
            <label for="excluded_services_fr_json" class="block text-sm font-medium text-gray-700 mb-1">Services Exclus (JSON Array)</label>
            <textarea name="excluded_services_fr_json" id="excluded_services_fr_json" rows="6" class="form-textarea font-mono text-xs @error('excluded_services_fr_json') border-red-500 @enderror">{{ $excludedServicesJson }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Format JSON Array requis : `["Exclu 1", "Exclu 2", ...]`</p>
            @error('excluded_services_fr_json')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Itinéraire (JSON Array d'Objets) --}}
    <div class="mt-6">
        <label for="itinerary_fr_json" class="block text-sm font-medium text-gray-700 mb-1">Itinéraire Jour par Jour (JSON Array d'Objets)</label>
        <textarea name="itinerary_fr_json" id="itinerary_fr_json" rows="8" class="form-textarea font-mono text-xs @error('itinerary_fr_json') border-red-500 @enderror">{{ $itineraryJson }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Format JSON Array d'objets : `[{"day": N, "title": "Titre", "description": "Détails"}, ...]`</p>
        @error('itinerary_fr_json')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
</div>

{{-- SECTION 4: MÉDIAS ET STATUT --}}
<div class="pb-6">
    <h2 class="text-2xl font-semibold text-primary mb-4"><i class="fas fa-camera mr-2"></i> Médias & Statut</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
        
        {{-- Image Actuelle (visible seulement en mode édition) --}}
        @if ($package->exists)
        <div class="flex flex-col items-center">
            <label class="block text-sm font-medium text-gray-700 mb-2">Image Actuelle</label>
            <img src="{{ $currentAvatarUrl }}" alt="Avatar actuel" class="w-24 h-24 object-cover rounded-full border-2 border-primary shadow-lg">
        </div>
        @endif

        {{-- Nouvelle Image Principale (Avatar) --}}
        <div class="{{ $package->exists ? 'md:col-span-2' : 'md:col-span-3' }}">
            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">
                {{ $package->exists ? 'Remplacer l\'Image Principale (Optionnel)' : 'Image Principale (Avatar)' }}
            </label>
            <input type="file" name="avatar" id="avatar" accept="image/*" class="form-input-file @error('avatar') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">Fichier image, Max 2MB.</p>
            @error('avatar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
        {{-- Galerie Photos --}}
        <div class="md:col-span-3">
            <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-1">Ajouter des Images à la Galerie (Optionnel)</label>
            <input type="file" name="gallery_images[]" id="gallery_images" multiple accept="image/*" class="form-input-file @error('gallery_images.*') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">Sélectionnez plusieurs images pour la galerie. Ne supprime pas les anciennes images.</p>
            @error('gallery_images.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Statuts (visible seulement en mode édition ou pour un nouveau package) --}}
        <div class="flex items-center space-x-6 col-span-full mt-4">
            <label class="flex items-center space-x-3">
                <input type="checkbox" name="is_active" 
                       {{ old('is_active', $package->is_active ?? true) ? 'checked' : '' }} 
                       class="form-checkbox text-primary rounded">
                <span class="text-sm font-medium text-gray-700">Activer le Package (visible sur le site)</span>
            </label>
            <label class="flex items-center space-x-3">
                <input type="checkbox" name="is_featured" 
                       {{ old('is_featured', $package->is_featured) ? 'checked' : '' }} 
                       class="form-checkbox text-primary rounded">
                <span class="text-sm font-medium text-gray-700">Mettre en Avant</span>
            </label>
        </div>
    </div>
</div>


</div>

{{-- Styles Tailwind spécifiques pour les formulaires --}}

<style>
.form-input, .form-select, .form-textarea {
@apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150;
}
.form-textarea {
@apply resize-y;
}
.form-input-file {
@apply w-full p-2 border border-gray-300 rounded-lg bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-purple-700 transition duration-150;
}
.form-checkbox {
@apply h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded;
}
.required:after {
content: " ";
color: #ef4444; / red-500 */
}
</style>