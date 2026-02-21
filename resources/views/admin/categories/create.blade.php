@extends('admin.layouts.app')

@section('title', 'Créer une Catégorie')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    @if($type === 'event_category')
                        Créer une Catégorie d'Événement
                    @elseif($type === 'event_type')
                        Créer un Type d'Événement
                    @else
                        Créer une Catégorie
                    @endif
                </h1>
            </div>
            <a href="{{ route('admin.categories.index', ['type' => $type]) }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <!-- Formulaire -->
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
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
                        <input type="text" name="name_fr" value="{{ old('name_fr') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Ex: Concerts" required>
                        @error('name_fr')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom EN -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nom (Anglais) *</label>
                        <input type="text" name="name_en" value="{{ old('name_en') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Ex: Concerts" required>
                        @error('name_en')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catégorie Parent (uniquement pour les catégories simples) -->
                    @if($type === 'category')
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie Parent</label>
                            <select name="parent_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                                <option value="">Aucune (Racine)</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
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
                            <input type="text" name="icon" value="{{ old('icon') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                placeholder="Ex: fa-plane">
                            <p class="text-xs text-gray-500 mt-1">Classe Font Awesome (ex: fa-plane, fa-hotel)</p>
                            @error('icon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ordre -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Position d'affichage</label>
                            <input type="number" name="order_position" value="{{ old('order_position', 0) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            @error('order_position')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Catégorie d'Événement (uniquement pour les types d'événements) -->
                    @if($type === 'event_type')
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie d'Événement *</label>
                            <select name="category_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($eventCategories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                    placeholder="Description de la catégorie...">{{ old('description_fr') }}</textarea>
                                @error('description_fr')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description EN -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Anglais)</label>
                                <textarea name="description_en" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                    placeholder="Category description...">{{ old('description_en') }}</textarea>
                                @error('description_en')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <!-- Description simple pour EventCategory et EventType -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <textarea name="description" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                    placeholder="Description...">{{ old('description') }}</textarea>
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
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Image de la catégorie</label>
                    <input type="file" name="avatar" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg" onchange="previewImage(this)">
                    <p class="text-xs text-gray-500 mt-1">Formats acceptés : JPG, PNG, WebP (Max 2MB)</p>
                    @error('avatar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Prévisualisation -->
                    <div id="image-preview" class="mt-4 hidden">
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
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-5 h-5 text-primary rounded focus:ring-2 focus:ring-primary">
                    <span class="ml-3 text-gray-700">Catégorie active</span>
                </label>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.categories.index', ['type' => $type]) }}"
                    class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-primary to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-primary transition shadow-lg">
                    <i class="fas fa-save mr-2"></i>Créer la Catégorie
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

                    reader.onload = function (e) {
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