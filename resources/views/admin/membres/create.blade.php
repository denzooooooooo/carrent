@extends('admin.layouts.app')

@php
// Détermine si nous sommes en mode modification ou création
$isEdit = isset($member);
$title = $isEdit ? 'Modifier le Membre : ' . $member->name : 'Ajouter un Nouveau Membre';
$action = $isEdit ? route('admin.members.update', $member) : route('admin.members.store');
@endphp

@section('title', $title)

@section('content')

<div class="max-w-3xl mx-auto py-8">
<h1 class="text-3xl font-bold mb-8 text-dark gradient-text border-b pb-2">{{ $title }}</h1>

<div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 animate-fade">
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- Utiliser PUT pour la mise à jour --}}
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="space-y-6">
            <!-- Nom Complet -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom Complet</label>
                <input type="text" name="name" id="name" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('name') border-red-500 @enderror"
                    value="{{ old('name', $isEdit ? $member->name : '') }}">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('email') border-red-500 @enderror"
                    value="{{ old('email', $isEdit ? $member->email : '') }}"
                    {{-- Empêche de modifier son propre email pour les super admins (facultatif mais bonne pratique) --}}
                    @if($isEdit && Auth::guard('admin')->id() === $member->id) readonly title="Vous ne pouvez pas modifier votre propre email ici" @endif
                    >
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rôle -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                <select name="role" id="role" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('role') border-red-500 @enderror">
                    @php
                        $currentRole = old('role', $isEdit ? $member->role : 'admin');
                        $roles = ['super_admin' => 'Super Administrateur', 'admin' => 'Administrateur', 'moderator' => 'Modérateur'];
                    @endphp
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" @if($currentRole === $key) selected @endif>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Téléphone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone (Optionnel)</label>
                <input type="text" name="phone" id="phone"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('phone') border-red-500 @enderror"
                    value="{{ old('phone', $isEdit ? $member->phone : '') }}">
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mot de Passe (Requis en création, Optionnel en modification) -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de Passe @if(!$isEdit) (Requis) @else (Laisser vide pour ne pas changer) @endif</label>
                <input type="password" name="password" id="password" @if(!$isEdit) required @endif
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmation du Mot de Passe -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmation du Mot de Passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation" @if(!$isEdit) required @endif
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">
            </div>

            <!-- Avatar (Media Library) -->
            <div class="flex items-center space-x-4">
                @if($isEdit && $member->getFirstMediaUrl('avatar', 'small'))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Avatar Actuel</label>
                        <img src="{{ $member->getFirstMediaUrl('avatar', 'small') }}" alt="Avatar" class="w-16 h-16 object-cover rounded-full border-2 border-primary">
                    </div>
                @endif
                <div>
                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Changer d'Avatar (Max 2MB)</label>
                    <input type="file" name="avatar" id="avatar"
                        class="w-full p-2 border border-gray-300 rounded-lg bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-purple-700 transition duration-150 @error('avatar') border-red-500 @enderror">
                    @error('avatar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Statut Actif -->
            <div>
                <label for="is_active" class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                        class="form-checkbox h-5 w-5 text-primary rounded border-gray-300 focus:ring-primary"
                        {{ old('is_active', $isEdit ? $member->is_active : true) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm font-medium text-gray-700">Membre actif (Peut se connecter)</span>
                </label>
                @error('is_active')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Bouton de Soumission -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.members.index') }}" class="py-2 px-6 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-300 font-semibold">
                Annuler
            </a>
            <button type="submit"
                class="py-2 px-6 rounded-lg text-white font-semibold bg-primary hover:bg-purple-700 transition duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                <i class="fas fa-save mr-2"></i> {{ $isEdit ? 'Enregistrer les Modifications' : 'Créer le Membre' }}
            </button>
        </div>
    </form>
</div>


</div>
@endsection