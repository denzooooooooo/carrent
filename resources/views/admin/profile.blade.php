@extends('admin.layouts.app')

@section('title', 'Mon Profil')

@section('header-content')
    <div class="bg-white border-b border-gray-200 px-4 py-4 md:px-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Mon Profil</h1>
                <p class="text-sm text-gray-600 mt-1">Gérez vos informations personnelles</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Carte de Profil (Affichage) -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 card-hover sticky top-6">
                    <div class="flex flex-col items-center">
                        @php
                            $avatarUrl = $admin->getFirstMediaUrl('avatar', 'small');
                            if (!$avatarUrl) {
                                $avatarUrl = 'https://placehold.co/128x128/4c1d95/ffffff?text=' . strtoupper(substr($admin->name, 0, 2));
                            }
                        @endphp
                        
                        <!-- Avatar cliquable -->
                        <div class="relative group cursor-pointer" onclick="document.getElementById('avatar').click()">
                            <img src="{{ $avatarUrl }}" alt="Avatar de {{ $admin->name }}" class="w-32 h-32 object-cover rounded-full border-4 border-primary shadow-md mb-4 transition-transform group-hover:scale-105">

                            
                            <!-- Overlay au survol -->
                            <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity mb-4">
                                <i class="fas fa-camera text-white text-2xl"></i>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold text-dark">{{ $admin->name }}</h2>
                        <p class="text-sm text-gray-500 mb-2 font-poppins capitalize">
                            {{ str_replace('_', ' ', $admin->role) }}
                        </p>
                        <a href="{{ route('admin.password.form') }}"
                            class="mt-4 text-sm font-semibold text-primary hover:text-purple-700 transition duration-150">
                            <i class="fas fa-lock mr-2"></i>Changer le Mot de Passe
                        </a>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-100 space-y-3">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-envelope mr-3 text-primary text-lg w-6"></i>
                            <span class="text-sm font-medium break-all">{{ $admin->email }}</span>
                        </div>
                        @if($admin->phone)
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-phone mr-3 text-primary text-lg w-6"></i>
                                <span class="text-sm font-medium">{{ $admin->phone }}</span>
                            </div>
                        @endif
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-calendar-alt mr-3 text-primary text-lg w-6"></i>
                            <span class="text-sm font-medium">
                                Membre depuis: {{ $admin->created_at?->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de Modification du Profil -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 animate-fade">
                    <h3 class="text-2xl font-semibold mb-6 text-dark border-b pb-3">
                        <i class="fas fa-edit mr-2 text-primary"></i>Modifier les Informations
                    </h3>
                    
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-form">
                        @csrf

                        <div class="space-y-5">
                            <!-- Nom -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-primary"></i>Nom Complet
                                </label>
                                <input type="text" name="name" id="name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-150 @error('name') border-red-500 @enderror"
                                    value="{{ old('name', $admin->name) }}"
                                    placeholder="Votre nom complet">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-2 text-primary"></i>Adresse Email
                                </label>
                                <input type="email" name="email" id="email" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-150 @error('email') border-red-500 @enderror"
                                    value="{{ old('email', $admin->email) }}"
                                    placeholder="votre.email@exemple.com">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-2 text-primary"></i>Téléphone (Optionnel)
                                </label>
                                <input type="text" name="phone" id="phone"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-150 @error('phone') border-red-500 @enderror"
                                    value="{{ old('phone', $admin->phone) }}"
                                    placeholder="+225 XX XX XX XX XX">
                                @error('phone')
                                    <p class="text-red-500 text-xs mt-1">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Avatar (Caché, déclenché par le clic sur l'image) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-image mr-2 text-primary"></i>Photo de Profil
                                </label>
                                <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden">
                                
                                <div class="flex items-center space-x-4">
                                    <button type="button" 
                                            onclick="document.getElementById('avatar').click()"
                                            class="px-4 py-2 border-2 border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition duration-200 font-medium">
                                        <i class="fas fa-upload mr-2"></i>Choisir une photo
                                    </button>
                                    <span id="file-name" class="text-sm text-gray-500">Max 2MB (JPG, PNG, GIF, SVG)</span>
                                </div>
                                @error('avatar')
                                    <p class="text-red-500 text-xs mt-2">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Bouton de Soumission -->
                        <div class="mt-8 flex gap-3">
                            <button type="submit"
                                class="flex-1 py-3 px-6 rounded-lg text-white font-semibold bg-gradient-to-r from-primary to-purple-700 hover:from-purple-700 hover:to-primary transition duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                <i class="fas fa-save mr-2"></i>Enregistrer les Modifications
                            </button>
                            <button type="reset"
                                class="px-6 py-3 rounded-lg text-gray-700 font-medium bg-gray-100 hover:bg-gray-200 transition duration-200">
                                <i class="fas fa-undo mr-2"></i>Réinitialiser
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Prévisualisation de l'avatar lors de la sélection d'un fichier
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const fileNameSpan = document.getElementById('file-name');
        
        if (file) {
            // Vérification de la taille (2MB max)
            if (file.size > 2048 * 1024) {
                alert('Le fichier est trop volumineux. Maximum 2MB.');
                this.value = '';
                return;
            }
            
            // Vérification du type
            if (!file.type.match('image.*')) {
                alert('Veuillez sélectionner une image.');
                this.value = '';
                return;
            }
            
            // Afficher le nom du fichier
            fileNameSpan.textContent = file.name;
            fileNameSpan.classList.add('text-primary', 'font-medium');
            
            // Prévisualisation
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Animation de succès après soumission
    document.getElementById('profile-form').addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement...';
        submitBtn.disabled = true;
    });
</script>
@endpush