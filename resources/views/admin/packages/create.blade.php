@extends('admin.layouts.app')

@section('title', 'Créer un Package Touristique')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 42px;
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px;
    }
    .image-preview {
        position: relative;
        display: inline-block;
    }
    .image-preview img {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .remove-image {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Créer un Package Touristique</h1>
            <p class="text-gray-600 mt-1">Ajoutez un nouveau package à votre catalogue</p>
        </div>
        <a href="{{ route('admin.packages.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
        </a>
    </div>

    <!-- Formulaire -->
    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Informations Générales -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-info-circle text-primary mr-2"></i>
                Informations Générales
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Catégorie -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie *</label>
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name_fr }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type de Package -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type de Package *</label>
                    <select name="package_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                        <option value="">Sélectionner un type</option>
                        @foreach($packageTypes as $key => $label)
                            <option value="{{ $key }}" {{ old('package_type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('package_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Titre FR -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Titre (Français) *</label>
                    <input type="text" name="title_fr" value="{{ old('title_fr') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                           placeholder="Ex: Safari Extraordinaire au Kenya" required>
                    @error('title_fr')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Titre EN -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Titre (Anglais) *</label>
                    <input type="text" name="title_en" value="{{ old('title_en') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                           placeholder="Ex: Extraordinary Safari in Kenya" required>
                    @error('title_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Destination -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Destination *</label>
                    <input type="text" name="destination" value="{{ old('destination') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                           placeholder="Ex: Nairobi, Kenya" required>
                    @error('destination')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ville de Départ -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ville de Départ</label>
                    <input type="text" name="departure_city" value="{{ old('departure_city') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                           placeholder="Ex: Paris, France">
                    @error('departure_city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description FR -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Français)</label>
                    <textarea name="description_fr" rows="4" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                              placeholder="Décrivez le package en détail...">{{ old('description_fr') }}</textarea>
                    @error('description_fr')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description EN -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Anglais)</label>
                    <textarea name="description_en" rows="4" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                              placeholder="Describe the package in detail...">{{ old('description_en') }}</textarea>
                    @error('description_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Durée et Participants -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-clock text-primary mr-2"></i>
                Durée et Participants
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Durée (jours) *</label>
                    <input type="number" name="duration" value="{{ old('duration', 1) }}" min="1" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                    @error('duration')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Texte Durée (FR)</label>
                    <input type="text" name="duration_text_fr" value="{{ old('duration_text_fr') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                           placeholder="Ex: 7 jours / 6 nuits">
                    @error('duration_text_fr')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Min Participants *</label>
                    <input type="number" name="min_participants" value="{{ old('min_participants', 1) }}" min="1" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                    @error('min_participants')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Max Participants *</label>
                    <input type="number" name="max_participants" value="{{ old('max_participants', 10) }}" min="1" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                    @error('max_participants')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tarification -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-euro-sign text-primary mr-2"></i>
                Tarification
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prix Normal (€) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                           placeholder="Ex: 2500.00" required>
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prix Réduit (€)</label>
                    <input type="number" name="discount_price" value="{{ old('discount_price') }}" step="0.01" min="0" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                           placeholder="Ex: 2200.00">
                    @error('discount_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Services Inclus/Exclus -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-list-check text-primary mr-2"></i>
                Services Inclus et Exclus
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Services Inclus FR -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Services Inclus (FR)</label>
                    <div id="included-fr-container" class="space-y-2 mb-2">
                        <input type="text" name="included_services_fr[]" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                               placeholder="Ex: Vol aller-retour">
                    </div>
                    <button type="button" onclick="addField('included-fr-container', 'included_services_fr')" 
                            class="text-sm text-primary hover:text-purple-700">
                        <i class="fas fa-plus-circle mr-1"></i>Ajouter un service
                    </button>
                </div>

                <!-- Services Exclus FR -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Services Exclus (FR)</label>
                    <div id="excluded-fr-container" class="space-y-2 mb-2">
                        <input type="text" name="excluded_services_fr[]" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                               placeholder="Ex: Assurance voyage">
                    </div>
                    <button type="button" onclick="addField('excluded-fr-container', 'excluded_services_fr')" 
                            class="text-sm text-primary hover:text-purple-700">
                        <i class="fas fa-plus-circle mr-1"></i>Ajouter un service
                    </button>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-images text-primary mr-2"></i>
                Images et Médias
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Image Principale -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Image Principale</label>
                    <input type="file" name="avatar" accept="image/*" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                           onchange="previewImage(this, 'avatar-preview')">
                    <div id="avatar-preview" class="mt-3"></div>
                    @error('avatar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Galerie -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Galerie (plusieurs images)</label>
                    <input type="file" name="gallery[]" accept="image/*" multiple 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                           onchange="previewGallery(this, 'gallery-preview')">
                    <div id="gallery-preview" class="mt-3 grid grid-cols-3 gap-2"></div>
                    @error('gallery.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- URL Vidéo -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">URL Vidéo (YouTube/Vimeo)</label>
                    <input type="url" name="video_url" value="{{ old('video_url') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                           placeholder="https://www.youtube.com/watch?v=...">
                    @error('video_url')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Statut -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-toggle-on text-primary mr-2"></i>
                Statut et Visibilité
            </h2>

            <div class="flex items-center space-x-8">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                           class="w-5 h-5 text-primary rounded focus:ring-2 focus:ring-primary">
                    <span class="ml-2 text-gray-700">Package Actif</span>
                </label>

                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} 
                           class="w-5 h-5 text-primary rounded focus:ring-2 focus:ring-primary">
                    <span class="ml-2 text-gray-700">Mettre en Vedette</span>
                </label>
            </div>
        </div>

        <!-- SEO -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-search text-primary mr-2"></i>
                Référencement SEO (Optionnel)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Titre (FR)</label>
                    <input type="text" name="meta_title_fr" value="{{ old('meta_title_fr') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg" maxlength="60">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Titre (EN)</label>
                    <input type="text" name="meta_title_en" value="{{ old('meta_title_en') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg" maxlength="60">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Description (FR)</label>
                    <textarea name="meta_description_fr" rows="2" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg" maxlength="160">{{ old('meta_description_fr') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Description (EN)</label>
                    <textarea name="meta_description_en" rows="2" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg" maxlength="160">{{ old('meta_description_en') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.packages.index') }}" 
               class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Annuler
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-primary to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-primary transition shadow-lg">
                <i class="fas fa-save mr-2"></i>Créer le Package
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Initialiser Select2
    $('select[name="category_id"], select[name="package_type"]').select2({
        placeholder: 'Sélectionner...',
        allowClear: false
    });

    // Ajouter des champs dynamiques
    function addField(containerId, fieldName) {
        const container = document.getElementById(containerId);
        const newField = document.createElement('div');
        newField.className = 'flex items-center space-x-2';
        newField.innerHTML = `
            <input type="text" name="${fieldName}[]" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                   placeholder="Ajouter un élément">
            <button type="button" onclick="this.parentElement.remove()" 
                    class="text-red-500 hover:text-red-700">
                <i class="fas fa-times-circle"></i>
            </button>
        `;
        container.appendChild(newField);
    }

    // Prévisualiser l'image principale
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <div class="image-preview">
                        <img src="${e.target.result}" class="h-32 w-auto object-cover">
                    </div>
                `;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Prévisualiser la galerie
    function previewGallery(input, previewId) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        
        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'image-preview';
                    div.innerHTML = `<img src="${e.target.result}" class="h-24 w-full object-cover">`;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endpush
@endsection