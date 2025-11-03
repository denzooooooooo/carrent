@extends('admin.layouts.app')

@section('title', 'Modifier la Location')

@section('content')

<div class="max-w-6xl mx-auto py-8">
<div class="flex justify-between items-center mb-8 border-b pb-2">
<h1 class="text-3xl font-bold text-dark gradient-text">Modifier la Location : <span class="text-primary">{{ $location->name }}</span></h1>
<a href="{{ route('admin.locations.index') }}" class="py-2 px-4 rounded-lg text-white font-semibold bg-gray-600 hover:bg-gray-700 transition duration-300 shadow-md flex items-center">
<i class="fas fa-arrow-left mr-2"></i> Retour à la liste
</a>
</div>

@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Erreur!</strong>
        <span class="block sm:inline">{!! session('error') !!}</span>
    </div>
@endif

<div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-100">

    <form action="{{ route('admin.locations.update', $location) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- SECTION 1: INFORMATIONS GÉNÉRALES --}}
        <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2">1. Informations Générales</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

            {{-- Nom Français --}}
            <div>
                <label for="name_fr" class="block text-sm font-medium text-gray-700 mb-1">Nom (Français) *</label>
                <input type="text" name="name_fr" id="name_fr" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('name_fr') border-red-500 @enderror"
                    value="{{ old('name_fr', $location->name_fr) }}">
                @error('name_fr')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Nom Anglais --}}
            <div>
                <label for="name_en" class="block text-sm font-medium text-gray-700 mb-1">Nom (Anglais) *</label>
                <input type="text" name="name_en" id="name_en" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('name_en') border-red-500 @enderror"
                    value="{{ old('name_en', $location->name_en) }}">
                @error('name_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Catégorie --}}
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                <select name="category" id="category" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('category') border-red-500 @enderror">
                    <option value="">Sélectionner une catégorie</option>
                    <option value="terrestre" {{ old('category', $location->category) == 'terrestre' ? 'selected' : '' }}>Terrestre</option>
                    <option value="aérien" {{ old('category', $location->category) == 'aérien' ? 'selected' : '' }}>Aérien</option>
                    <option value="nautique" {{ old('category', $location->category) == 'nautique' ? 'selected' : '' }}>Nautique</option>
                </select>
                @error('category')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Type --}}
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                <input type="text" name="type" id="type" required placeholder="Ex: Voiture, Quad, Avion, Bateau..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('type') border-red-500 @enderror"
                    value="{{ old('type', $location->type) }}">
                @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Prix par jour --}}
            <div>
                <label for="price_per_day" class="block text-sm font-medium text-gray-700 mb-1">Prix par Jour (FCFA) *</label>
                <input type="number" name="price_per_day" id="price_per_day" required min="0" step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('price_per_day') border-red-500 @enderror"
                    value="{{ old('price_per_day', $location->price_per_day) }}">
                @error('price_per_day')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Capacité --}}
            <div>
                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacité (personnes) *</label>
                <input type="number" name="capacity" id="capacity" required min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('capacity') border-red-500 @enderror"
                    value="{{ old('capacity', $location->capacity) }}">
                @error('capacity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Image actuelle --}}
            <div class="md:col-span-2">
                @if($location->image)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image actuelle :</label>
                        <img src="{{ $location->getImageUrl() }}" alt="Image actuelle" class="w-32 h-32 object-cover rounded-lg border">
                    </div>
                @endif
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Changer l'image (optionnel)</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('image') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: JPG, PNG, GIF. Taille max: 2MB. Laissez vide pour garder l'image actuelle.</p>
                @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- SECTION 2: DESCRIPTIONS --}}
        <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2">2. Descriptions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

            {{-- Description Française --}}
            <div>
                <label for="description_fr" class="block text-sm font-medium text-gray-700 mb-1">Description (Français)</label>
                <textarea name="description_fr" id="description_fr" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('description_fr') border-red-500 @enderror">{{ old('description_fr', $location->description_fr) }}</textarea>
                @error('description_fr')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Description Anglaise --}}
            <div>
                <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1">Description (Anglais)</label>
                <textarea name="description_en" id="description_en" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('description_en') border-red-500 @enderror">{{ old('description_en', $location->description_en) }}</textarea>
                @error('description_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- SECTION 3: CARACTÉRISTIQUES --}}
        <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2">3. Caractéristiques</h2>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Caractéristiques (optionnel)</label>
            <div id="features-container">
                @php
                    $features = old('features', $location->features ?? []);
                @endphp
                @if($features && count($features) > 0)
                    @foreach($features as $index => $feature)
                        <div class="feature-item flex gap-2 mb-2">
                            <input type="text" name="features[]" value="{{ $feature }}"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                                placeholder="Ex: Climatisation, GPS, etc.">
                            <button type="button" class="remove-feature px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-150">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="feature-item flex gap-2 mb-2">
                        <input type="text" name="features[]" value=""
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                            placeholder="Ex: Climatisation, GPS, etc.">
                        <button type="button" class="remove-feature px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-150">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @endif
            </div>
            <button type="button" id="add-feature" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-150">
                <i class="fas fa-plus mr-2"></i>Ajouter une caractéristique
            </button>
        </div>

        {{-- SECTION 4: STATUT --}}
        <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2">4. Statut</h2>
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $location->is_active) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-primary focus:ring-primary">
                <span class="ml-2 text-sm text-gray-700">Location active</span>
            </label>
        </div>

        {{-- BOUTON DE SOUMISSION --}}
        <div class="mt-8 pt-4 border-t">
            <button type="submit"
                class="w-full py-3 px-4 rounded-lg text-white font-semibold bg-primary hover:bg-purple-700 transition duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-primary/50">
                <i class="fas fa-save mr-2"></i> Mettre à Jour la Location
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const featuresContainer = document.getElementById('features-container');
    const addFeatureBtn = document.getElementById('add-feature');

    // Fonction pour ajouter une caractéristique
    function addFeature(value = '') {
        const featureDiv = document.createElement('div');
        featureDiv.className = 'feature-item flex gap-2 mb-2';
        featureDiv.innerHTML = `
            <input type="text" name="features[]" value="${value}"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                placeholder="Ex: Climatisation, GPS, etc.">
            <button type="button" class="remove-feature px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-150">
                <i class="fas fa-trash"></i>
            </button>
        `;
        featuresContainer.appendChild(featureDiv);
    }

    // Événement pour ajouter une nouvelle caractéristique
    addFeatureBtn.addEventListener('click', function() {
        addFeature();
    });

    // Événement pour supprimer une caractéristique
    featuresContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-feature') || e.target.closest('.remove-feature')) {
            e.target.closest('.feature-item').remove();
        }
    });
});
</script>

@endsection
