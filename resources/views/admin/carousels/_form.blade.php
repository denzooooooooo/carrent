@php
// Bloc de code pour définir les variables nécessaires, en s'assurant que $carousel est défini.
// Ceci gère les scénarios de création et de modification.
$isEditing = $carousel->exists ?? false;
$desktopImage = $carousel->getFirstMediaUrl('image_desktop', 'thumb') ?? null;
$mobileImage = $carousel->getFirstMediaUrl('image_mobile', 'thumb') ?? null;
$carousel = $carousel ?? (object)[ // Objet par défaut pour la création pour éviter les erreurs
    'title_fr' => null, 'title_en' => null, 'subtitle_fr' => null, 'subtitle_en' => null,
    'link_url' => null, 'button_text_fr' => null, 'button_text_en' => null,
    'video_url' => null, 'start_date' => null, 'end_date' => null,
    'order_position' => 0, 'is_active' => true, 'exists' => false
];
@endphp
@extends('admin.layouts.app')
@section('title', $isEditing ? 'Modification du Slide Carrousel' : 'Création d\'un Nouveau Slide')

@push('styles')
<style>
    /* * Définition d'une couleur 'primary' basée sur l'exemple donné (Indigo/Purple)
     * Nous utilisons ici indigo-600 comme couleur de base 'primary' pour l'apparence.
     */
    .text-primary, .focus\:ring-primary, .text-primary:hover, .text-primary:focus {
        --tw-text-opacity: 1;
        color: rgb(79 70 229 / var(--tw-text-opacity)); /* indigo-600 */
    }
    .focus\:ring-primary {
        --tw-ring-color: rgb(79 70 229 / var(--tw-ring-opacity));
    }
    .from-primary {
        --tw-gradient-from: rgb(79 70 229) var(--tw-gradient-from-position);
        --tw-gradient-to: rgb(79 70 229 / 0) var(--tw-gradient-to-position);
    }

    /* Styles pour la prévisualisation d'image (ajustés pour le formulaire) */
    .image-preview {
        position: relative;
        display: inline-block;
        max-width: 100%;
    }
    .image-preview img {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        width: 100%;
        height: auto;
        max-height: 200px;
        object-fit: cover;
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
        line-height: 1;
        transition: transform 0.2s;
        z-index: 10;
    }
    .remove-image:hover {
        transform: scale(1.1);
    }
    .input[type="file"] {
        padding: 0.5rem 1rem;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ $isEditing ? 'Modification du Slide Carrousel' : 'Création d\'un Nouveau Slide' }}
            </h1>
            <p class="text-gray-600 mt-1">{{ $isEditing ? 'Mettez à jour les informations du slide.' : 'Ajoutez un nouveau slide à votre carrousel.' }}</p>
        </div>
        {{-- Remplacez 'admin.carousels.index' par votre route de liste --}}
        <a href="{{ route('admin.carousels.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
        </a>
    </div>

    <!-- Formulaire -->
    <form action="{{ $isEditing ? route('admin.carousels.update', $carousel) : route('admin.carousels.store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="space-y-6">
        @csrf
        @if ($isEditing)
            @method('PUT')
            {{-- Champ caché pour la suppression d'image côté serveur --}}
            <input type="hidden" name="remove_desktop_image" id="remove_desktop_image" value="0">
            <input type="hidden" name="remove_mobile_image" id="remove_mobile_image" value="0">
        @endif

        {{-- SECTION 1: CONTENU TEXTUEL --}}
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-pen text-primary mr-2"></i>
                Contenu Textuel
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Titre FR -->
                <div>
                    <label for="title_fr" class="block text-sm font-semibold text-gray-700 mb-2">Titre (Français) *</label>
                    <input type="text" name="title_fr" id="title_fr" required 
                           value="{{ old('title_fr', $carousel->title_fr) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                           placeholder="Ex: Destinations de Rêve">
                    @error('title_fr')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Titre EN -->
                <div>
                    <label for="title_en" class="block text-sm font-semibold text-gray-700 mb-2">Titre (Anglais)</label>
                    <input type="text" name="title_en" id="title_en" 
                           value="{{ old('title_en', $carousel->title_en) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                           placeholder="Ex: Dream Destinations">
                    @error('title_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Sous-titre FR -->
                <div class="md:col-span-2">
                    <label for="subtitle_fr" class="block text-sm font-semibold text-gray-700 mb-2">Sous-titre (Français)</label>
                    <textarea name="subtitle_fr" id="subtitle_fr" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                              placeholder="Un court texte d'accroche...">{{ old('subtitle_fr', $carousel->subtitle_fr) }}</textarea>
                    @error('subtitle_fr')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Sous-titre EN -->
                <div class="md:col-span-2">
                    <label for="subtitle_en" class="block text-sm font-semibold text-gray-700 mb-2">Sous-titre (Anglais)</label>
                    <textarea name="subtitle_en" id="subtitle_en" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                              placeholder="A short compelling text...">{{ old('subtitle_en', $carousel->subtitle_en) }}</textarea>
                    @error('subtitle_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- SECTION 2: BOUTON ET LIENS --}}
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-link text-primary mr-2"></i>
                Liens & Bouton
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- URL de Redirection -->
                <div class="md:col-span-3">
                    <label for="link_url" class="block text-sm font-semibold text-gray-700 mb-2">URL de Redirection</label>
                    <input type="url" name="link_url" id="link_url" 
                           placeholder="https://votre-site.com/page" 
                           value="{{ old('link_url', $carousel->link_url) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('link_url')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Texte Bouton FR -->
                <div>
                    <label for="button_text_fr" class="block text-sm font-semibold text-gray-700 mb-2">Texte Bouton (Français)</label>
                    <input type="text" name="button_text_fr" id="button_text_fr" 
                           value="{{ old('button_text_fr', $carousel->button_text_fr) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Ex: Découvrir">
                    @error('button_text_fr')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Texte Bouton EN -->
                <div>
                    <label for="button_text_en" class="block text-sm font-semibold text-gray-700 mb-2">Texte Bouton (Anglais)</label>
                    <input type="text" name="button_text_en" id="button_text_en" 
                           value="{{ old('button_text_en', $carousel->button_text_en) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Ex: Discover">
                    @error('button_text_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- SECTION 3: MÉDIAS --}}
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-images text-primary mr-2"></i>
                Images et Vidéo
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Image Desktop -->
                <div>
                    <label for="image_desktop" class="block text-sm font-semibold text-gray-700 mb-2">Image Desktop (1920x800 min.)*</label>
                    <input type="file" name="image_desktop" id="image_desktop" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                           onchange="previewImage(this, 'desktop-preview', 'desktop')">
                    @error('image_desktop')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <div id="desktop-preview" class="mt-3">
                        {{-- Affichage de l'image existante --}}
                        @if ($desktopImage)
                            <div class="image-preview">
                                <img src="{{ $desktopImage }}" alt="Image Desktop Actuelle">
                                <span class="remove-image" onclick="removeExistingImage('desktop')"><i class="fas fa-times"></i></span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Image Mobile -->
                <div>
                    <label for="image_mobile" class="block text-sm font-semibold text-gray-700 mb-2">Image Mobile (768x1024 min.)</label>
                    <input type="file" name="image_mobile" id="image_mobile" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                           onchange="previewImage(this, 'mobile-preview', 'mobile')">
                    @error('image_mobile')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <div id="mobile-preview" class="mt-3">
                        {{-- Affichage de l'image existante --}}
                        @if ($mobileImage)
                            <div class="image-preview">
                                <img src="{{ $mobileImage }}" alt="Image Mobile Actuelle">
                                <span class="remove-image" onclick="removeExistingImage('mobile')"><i class="fas fa-times"></i></span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- URL Vidéo -->
                <div class="md:col-span-2">
                    <label for="video_url" class="block text-sm font-semibold text-gray-700 mb-2">URL Vidéo (YouTube/Vimeo)</label>
                    <input type="url" name="video_url" id="video_url" 
                           placeholder="https://www.youtube.com/watch?v=XYZ" 
                           value="{{ old('video_url', $carousel->video_url) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('video_url')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- SECTION 4: PLANIFICATION --}}
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-calendar-alt text-primary mr-2"></i>
                Planification et Ordre
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Date de Début -->
                <div>
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Date de Début</label>
                    <input type="date" name="start_date" id="start_date" 
                           value="{{ old('start_date', $carousel->start_date ? \Carbon\Carbon::parse($carousel->start_date)->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('start_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Date de Fin -->
                <div>
                    <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">Date de Fin</label>
                    <input type="date" name="end_date" id="end_date" 
                           value="{{ old('end_date', $carousel->end_date ? \Carbon\Carbon::parse($carousel->end_date)->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('end_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Position d'Ordre -->
                <div>
                    <label for="order_position" class="block text-sm font-semibold text-gray-700 mb-2">Position d'Ordre</label>
                    <input type="number" name="order_position" id="order_position" min="0" 
                           placeholder="0" 
                           value="{{ old('order_position', $carousel->order_position ?? 0) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('order_position')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Statut (Activer le Slide) -->
                <div class="flex items-center pt-8">
                    <label for="is_active" class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', $carousel->is_active ?? true) ? 'checked' : '' }}
                               class="w-5 h-5 text-primary rounded focus:ring-2 focus:ring-primary">
                        <span class="ml-2 text-gray-700 font-semibold">Activer le Slide</span>
                    </label>
                    @error('is_active')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.carousels.index') }}" 
               class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Annuler
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-primary to-indigo-600 text-white rounded-lg hover:from-indigo-600 hover:to-primary transition shadow-lg">
                <i class="fas fa-save mr-2"></i>{{ $isEditing ? 'Mettre à Jour le Slide' : 'Créer le Slide' }}
            </button>
        </div>
    </form>
</div>
@push('scripts')
<script>
    /**
     * Preview the selected image file.
     * @param {HTMLInputElement} input The file input element.
     * @param {string} previewId The ID of the container for the preview.
     * @param {string} type The type of image ('desktop' or 'mobile').
     */
    function previewImage(input, previewId, type) {
        const previewContainer = document.getElementById(previewId);
        previewContainer.innerHTML = '';
        
        // Réinitialiser le champ caché de suppression si un nouveau fichier est sélectionné
        const removeField = document.getElementById(`remove_${type}_image`);
        if (removeField) removeField.value = '0';

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.innerHTML = `
                    <div class="image-preview">
                        <img src="${e.target.result}" alt="Image Preview">
                    </div>
                `;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    /**
     * Gère la suppression d'une image existante lors de la modification.
     * @param {string} type The type of image ('desktop' or 'mobile').
     */
    function removeExistingImage(type) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette image ? Elle sera définitivement supprimée après la sauvegarde du formulaire.')) {
            return;
        }

        const previewElement = document.querySelector(`#${type}-preview .image-preview`);
        if (previewElement) {
            previewElement.remove();
        }

        // 1. Définir le champ caché pour indiquer au serveur de supprimer l'ancienne image
        const removeField = document.getElementById(`remove_${type}_image`);
        if (removeField) {
            removeField.value = '1';
        }

        // 2. Vider le champ de type file pour éviter l'envoi d'un nouveau fichier
        document.getElementById(`image_${type}`).value = '';
    }
</script>
@endpush
@endsection
