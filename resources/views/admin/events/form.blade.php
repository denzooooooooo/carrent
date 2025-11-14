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


        {{-- SECTION 6: ZONES DE SIÈGES --}}
        <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2 mt-8">6. Zones de Sièges (VIP, VVIP, VVVIP)</h2>

        {{-- Zone Standard --}}
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-800">Zone Standard</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nom de la zone FR --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Français)</label>
                    <input type="text" name="seat_zones[0][zone_name_fr]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.0.zone_name_fr', $event->exists ? ($event->seatZones->where('zone_type', 'standard')->first()->zone_name_fr ?? 'Zone Standard') : 'Zone Standard') }}">
                </div>
                {{-- Nom de la zone EN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Anglais)</label>
                    <input type="text" name="seat_zones[0][zone_name_en]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.0.zone_name_en', $event->exists ? ($event->seatZones->where('zone_type', 'standard')->first()->zone_name_en ?? 'Standard Zone') : 'Standard Zone') }}">
                </div>
                {{-- Code de la zone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code de la Zone</label>
                    <input type="text" name="seat_zones[0][zone_code]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.0.zone_code', $event->exists ? ($event->seatZones->where('zone_type', 'standard')->first()->zone_code ?? 'STD') : 'STD') }}">
                </div>
                {{-- Type de zone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de Zone</label>
                    <input type="text" name="seat_zones[0][zone_type]" value="standard" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                </div>
                {{-- Prix par siège --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix par Siège (FCFA)</label>
                    <input type="number" name="seat_zones[0][price]" required min="0" step="0.01"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.0.price', $event->exists ? ($event->seatZones->where('zone_type', 'standard')->first()->price ?? 0) : 0) }}">
                </div>
                {{-- Nombre total de sièges --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Total de Sièges</label>
                    <input type="number" name="seat_zones[0][total_seats]" required min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.0.total_seats', $event->exists ? ($event->seatZones->where('zone_type', 'standard')->first()->total_seats ?? 0) : 0) }}">
                </div>
                {{-- Description FR --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Français)</label>
                    <textarea name="seat_zones[0][description_fr]" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('seat_zones.0.description_fr', $event->exists ? ($event->seatZones->where('zone_type', 'standard')->first()->description_fr ?? '') : '') }}</textarea>
                </div>
                {{-- Description EN --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Anglais)</label>
                    <textarea name="seat_zones[0][description_en]" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('seat_zones.0.description_en', $event->exists ? ($event->seatZones->where('zone_type', 'standard')->first()->description_en ?? '') : '') }}</textarea>
                </div>
            </div>

            {{-- Checkbox Actif --}}
            <div class="flex items-center mt-4">
                <input type="checkbox" name="seat_zones[0][is_active]" id="zone_active_0" value="1"
                    class="h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary"
                    {{ old('seat_zones.0.is_active', $event->exists ? ($event->seatZones->where('zone_type', 'standard')->first()->is_active ?? true) : true) ? 'checked' : '' }}>
                <label for="zone_active_0" class="ml-2 text-sm font-medium text-gray-700">Zone Active</label>
            </div>
        </div>

        {{-- Zone VIP --}}
        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200 mb-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-blue-800">Zone VIP</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nom de la zone FR --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Français)</label>
                    <input type="text" name="seat_zones[1][zone_name_fr]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.1.zone_name_fr', $event->exists ? ($event->seatZones->where('zone_type', 'vip')->first()->zone_name_fr ?? 'Zone VIP') : 'Zone VIP') }}">
                </div>
                {{-- Nom de la zone EN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Anglais)</label>
                    <input type="text" name="seat_zones[1][zone_name_en]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.1.zone_name_en', $event->exists ? ($event->seatZones->where('zone_type', 'vip')->first()->zone_name_en ?? 'VIP Zone') : 'VIP Zone') }}">
                </div>
                {{-- Code de la zone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code de la Zone</label>
                    <input type="text" name="seat_zones[1][zone_code]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.1.zone_code', $event->exists ? ($event->seatZones->where('zone_type', 'vip')->first()->zone_code ?? 'VIP') : 'VIP') }}">
                </div>
                {{-- Type de zone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de Zone</label>
                    <input type="text" name="seat_zones[1][zone_type]" value="vip" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                </div>
                {{-- Prix par siège --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix par Siège (FCFA)</label>
                    <input type="number" name="seat_zones[1][price]" required min="0" step="0.01"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.1.price', $event->exists ? ($event->seatZones->where('zone_type', 'vip')->first()->price ?? 0) : 0) }}">
                </div>
                {{-- Nombre total de sièges --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Total de Sièges</label>
                    <input type="number" name="seat_zones[1][total_seats]" required min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.1.total_seats', $event->exists ? ($event->seatZones->where('zone_type', 'vip')->first()->total_seats ?? 0) : 0) }}">
                </div>
                {{-- Description FR --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Français)</label>
                    <textarea name="seat_zones[1][description_fr]" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('seat_zones.1.description_fr', $event->exists ? ($event->seatZones->where('zone_type', 'vip')->first()->description_fr ?? '') : '') }}</textarea>
                </div>
                {{-- Description EN --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Anglais)</label>
                    <textarea name="seat_zones[1][description_en]" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('seat_zones.1.description_en', $event->exists ? ($event->seatZones->where('zone_type', 'vip')->first()->description_en ?? '') : '') }}</textarea>
                </div>
            </div>

            {{-- Checkbox Actif --}}
            <div class="flex items-center mt-4">
                <input type="checkbox" name="seat_zones[1][is_active]" id="zone_active_1" value="1"
                    class="h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary"
                    {{ old('seat_zones.1.is_active', $event->exists ? ($event->seatZones->where('zone_type', 'vip')->first()->is_active ?? true) : true) ? 'checked' : '' }}>
                <label for="zone_active_1" class="ml-2 text-sm font-medium text-gray-700">Zone Active</label>
            </div>
        </div>

        {{-- Zone VVIP --}}
        <div class="bg-purple-50 p-6 rounded-lg border border-purple-200 mb-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-purple-800">Zone VVIP</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nom de la zone FR --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Français)</label>
                    <input type="text" name="seat_zones[2][zone_name_fr]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.2.zone_name_fr', $event->exists ? ($event->seatZones->where('zone_type', 'vvip')->first()->zone_name_fr ?? 'Zone VVIP') : 'Zone VVIP') }}">
                </div>
                {{-- Nom de la zone EN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Anglais)</label>
                    <input type="text" name="seat_zones[2][zone_name_en]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.2.zone_name_en', $event->exists ? ($event->seatZones->where('zone_type', 'vvip')->first()->zone_name_en ?? 'VVIP Zone') : 'VVIP Zone') }}">
                </div>
                {{-- Code de la zone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code de la Zone</label>
                    <input type="text" name="seat_zones[2][zone_code]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.2.zone_code', $event->exists ? ($event->seatZones->where('zone_type', 'vvip')->first()->zone_code ?? 'VVIP') : 'VVIP') }}">
                </div>
                {{-- Type de zone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de Zone</label>
                    <input type="text" name="seat_zones[2][zone_type]" value="vvip" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                </div>
                {{-- Prix par siège --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix par Siège (FCFA)</label>
                    <input type="number" name="seat_zones[2][price]" required min="0" step="0.01"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.2.price', $event->exists ? ($event->seatZones->where('zone_type', 'vvip')->first()->price ?? 0) : 0) }}">
                </div>
                {{-- Nombre total de sièges --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Total de Sièges</label>
                    <input type="number" name="seat_zones[2][total_seats]" required min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.2.total_seats', $event->exists ? ($event->seatZones->where('zone_type', 'vvip')->first()->total_seats ?? 0) : 0) }}">
                </div>
                {{-- Description FR --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Français)</label>
                    <textarea name="seat_zones[2][description_fr]" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('seat_zones.2.description_fr', $event->exists ? ($event->seatZones->where('zone_type', 'vvip')->first()->description_fr ?? '') : '') }}</textarea>
                </div>
                {{-- Description EN --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Anglais)</label>
                    <textarea name="seat_zones[2][description_en]" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('seat_zones.2.description_en', $event->exists ? ($event->seatZones->where('zone_type', 'vvip')->first()->description_en ?? '') : '') }}</textarea>
                </div>
            </div>

            {{-- Checkbox Actif --}}
            <div class="flex items-center mt-4">
                <input type="checkbox" name="seat_zones[2][is_active]" id="zone_active_2" value="1"
                    class="h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary"
                    {{ old('seat_zones.2.is_active', $event->exists ? ($event->seatZones->where('zone_type', 'vvip')->first()->is_active ?? true) : true) ? 'checked' : '' }}>
                <label for="zone_active_2" class="ml-2 text-sm font-medium text-gray-700">Zone Active</label>
            </div>
        </div>

        {{-- Zone Premium --}}
        <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200 mb-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-yellow-800">Zone Premium</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nom de la zone FR --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Français)</label>
                    <input type="text" name="seat_zones[3][zone_name_fr]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.3.zone_name_fr', $event->exists ? ($event->seatZones->where('zone_type', 'premium')->first()->zone_name_fr ?? 'Zone Premium') : 'Zone Premium') }}">
                </div>
                {{-- Nom de la zone EN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Anglais)</label>
                    <input type="text" name="seat_zones[3][zone_name_en]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.3.zone_name_en', $event->exists ? ($event->seatZones->where('zone_type', 'premium')->first()->zone_name_en ?? 'Premium Zone') : 'Premium Zone') }}">
                </div>
                {{-- Code de la zone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code de la Zone</label>
                    <input type="text" name="seat_zones[3][zone_code]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.3.zone_code', $event->exists ? ($event->seatZones->where('zone_type', 'premium')->first()->zone_code ?? 'PREM') : 'PREM') }}">
                </div>
                {{-- Type de zone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de Zone</label>
                    <input type="text" name="seat_zones[3][zone_type]" value="premium" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                </div>
                {{-- Prix par siège --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix par Siège (FCFA)</label>
                    <input type="number" name="seat_zones[3][price]" required min="0" step="0.01"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.3.price', $event->exists ? ($event->seatZones->where('zone_type', 'premium')->first()->price ?? 0) : 0) }}">
                </div>
                {{-- Nombre total de sièges --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Total de Sièges</label>
                    <input type="number" name="seat_zones[3][total_seats]" required min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.3.total_seats', $event->exists ? ($event->seatZones->where('zone_type', 'premium')->first()->total_seats ?? 0) : 0) }}">
                </div>
                {{-- Description FR --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Français)</label>
                    <textarea name="seat_zones[3][description_fr]" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('seat_zones.3.description_fr', $event->exists ? ($event->seatZones->where('zone_type', 'premium')->first()->description_fr ?? '') : '') }}</textarea>
                </div>
                {{-- Description EN --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Anglais)</label>
                    <textarea name="seat_zones[3][description_en]" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('seat_zones.3.description_en', $event->exists ? ($event->seatZones->where('zone_type', 'premium')->first()->description_en ?? '') : '') }}</textarea>
                </div>
            </div>

            {{-- Checkbox Actif --}}
            <div class="flex items-center mt-4">
                <input type="checkbox" name="seat_zones[3][is_active]" id="zone_active_3" value="1"
                    class="h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary"
                    {{ old('seat_zones.3.is_active', $event->exists ? ($event->seatZones->where('zone_type', 'premium')->first()->is_active ?? true) : true) ? 'checked' : '' }}>
                <label for="zone_active_3" class="ml-2 text-sm font-medium text-gray-700">Zone Active</label>
            </div>
        </div>

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

@endsection
