@extends('admin.layouts.app')

@section('title', 'Modifier la Catégorie')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                @if($type === 'event_category')
                    Modifier la Catégorie d'Événement
                @elseif($type === 'event_type')
                    Modifier le Type d'Événement
                @else
                    Modifier la Catégorie
                @endif
            </h1>
            <p class="text-gray-600 mt-1">{{ $category->name_fr }}</p>
        </div>
        <a href="{{ route('admin.categories.index', ['type' => $type]) }}" 
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <!-- Formulaire -->
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="{{ $type }}">

        <!-- Informations Principales -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-info-circle text-primary mr-2"></i>
                Informations Principales
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom FR -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom (Français) *</label>
                    <input type="text" name="name_fr" value="{{ old('name_fr', $category->name_fr) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" required>
                    @error('name_fr')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nom EN -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom (Anglais) *</label>
                    <input type="text" name="name_en" value="{{ old('name_en', $category->name_en) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" required>
                    @error('name_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catégorie Parent (uniquement pour les catégories simples) -->
                @if($type === 'category')
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie Parent</label>
                        <select name="parent_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">Aucune (Racine)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" 
                                    {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name_fr }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Icône -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Icône (Font Awesome)</label>
                        <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                               placeholder="Ex: fa-plane">
                        <p class="text-xs text-gray-500 mt-1">Classe Font Awesome</p>
                        @error('icon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ordre -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Position d'affichage</label>
                        <input type="number" name="order_position" value="{{ old('order_position', $category->order_position) }}" min="0" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @error('order_position')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Catégorie d'Événement (uniquement pour les types d'événements) -->
                @if($type === 'event_type')
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie d'Événement *</label>
                        <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($eventCategories as $cat)
                                <option value="{{ $cat->id }}" 
                                    {{ old('category_id', $category->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name_fr }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Description -->
                <div class="md:col-span-2">
                    @if($type === 'category')
                        <!-- Description FR -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Français)</label>
                            <textarea name="description_fr" rows="3" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description_fr', $category->description_fr) }}</textarea>
                            @error('description_fr')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description EN -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Anglais)</label>
                            <textarea name="description_en" rows="3" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description_en', $category->description_en) }}</textarea>
                            @error('description_en')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <!-- Description simple -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Image -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-image text-primary mr-2"></i>
                Image
            </h2>

            <div>
                @if($category->getFirstMediaUrl('avatar'))
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Image actuelle :</p>
                        <img src="{{ $category->getFirstMediaUrl('avatar', 'normal') }}" 
                             alt="Image actuelle" 
                             class="h-32 w-auto rounded-lg shadow-lg">
                    </div>
                @endif

                <label class="block text-sm font-semibold text-gray-700 mb-2">Nouvelle image</label>
                <input type="file" name="avatar" accept="image/*" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                       onchange="previewImage(this)">
                <p class="text-xs text-gray-500 mt-1">Laissez vide pour conserver l'image actuelle</p>
                @error('avatar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <!-- Prévisualisation -->
                <div id="image-preview" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Nouvelle image :</p>
                    <img id="preview-img" src="" alt="Aperçu" class="h-32 w-auto rounded-lg shadow-lg">
                </div>
            </div>
        </div>

        <!-- Statut -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-toggle-on text-primary mr-2"></i>
                Statut
            </h2>

            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" 
                       {{ old('is_active', $category->is_active) ? 'checked' : '' }} 
                       class="w-5 h-5 text-primary rounded">
                <span class="ml-3 text-gray-700">Catégorie active</span>
            </label>
        </div>

        <!-- Métadonnées -->
        <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="font-semibold text-gray-700 mb-2">Informations système</h3>
            <div class="text-sm text-gray-600 space-y-1">
                <p><strong>Slug :</strong> <span class="font-mono">{{ $category->slug }}</span></p>
                <p><strong>Créé le :</strong> {{ $category->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Modifié le :</strong> {{ $category->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.categories.index', ['type' => $type]) }}" 
               class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Annuler
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-primary to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-primary transition shadow-lg">
                <i class="fas fa-save mr-2"></i>Enregistrer les Modifications
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            };
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection