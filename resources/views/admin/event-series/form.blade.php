@extends('admin.layouts.app')

@section('title', $pageTitle)

@section('content')

<div class="max-w-6xl mx-auto py-8">
    <div class="flex justify-between items-center mb-8 border-b pb-2">
        <h1 class="text-3xl font-bold text-dark gradient-text">{{ $pageTitle }}</h1>
        <a href="{{ route('admin.event-series.index') }}" class="py-2 px-4 rounded-lg text-white font-semibold bg-gray-600 hover:bg-gray-700 transition duration-300 shadow-md flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
        </a>
    </div>

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Erreur!</strong>
            <span class="block sm:inline">{!! session('error') !!}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Erreurs de validation:</strong>
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-100">
        @php
            $isEdit = $eventSeries->exists;
            $route = $isEdit ? route('admin.event-series.update', $eventSeries) : route('admin.event-series.store');
        @endphp

        <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2">1. Informations Générales</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name_fr" class="block text-sm font-medium text-gray-700 mb-1">Nom (Français)</label>
                    <input type="text" name="name_fr" id="name_fr" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('name_fr', $eventSeries->name_fr) }}">
                </div>
                <div>
                    <label for="name_en" class="block text-sm font-medium text-gray-700 mb-1">Nom (Anglais)</label>
                    <input type="text" name="name_en" id="name_en" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('name_en', $eventSeries->name_en) }}">
                </div>

            <div class="mb-6">
                <label for="description_fr" class="block text-sm font-medium text-gray-700 mb-1">Description (Français)</label>
                <textarea name="description_fr" id="description_fr" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('description_fr', $eventSeries->description_fr) }}</textarea>
            </div>
            <div class="mb-6">
                <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1">Description (Anglais)</label>
                <textarea name="description_en" id="description_en" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('description_en', $eventSeries->description_en) }}</textarea>
            </div>

            <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2 mt-8">2. Lieu & Dates</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="venue_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du Lieu</label>
                    <input type="text" name="venue_name" id="venue_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('venue_name', $eventSeries->venue_name) }}">
                </div>
                <div>
                    <label for="venue_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                    <input type="text" name="venue_address" id="venue_address"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('venue_address', $eventSeries->venue_address) }}">
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                    <input type="text" name="city" id="city"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('city', $eventSeries->city) }}">
                </div>
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                    <input type="text" name="country" id="country"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('country', $eventSeries->country) }}">
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date de Début</label>
                    <input type="date" name="start_date" id="start_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('start_date', $eventSeries->start_date ? \Carbon\Carbon::parse($eventSeries->start_date)->format('Y-m-d') : '') }}">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date de Fin</label>
                    <input type="date" name="end_date" id="end_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('end_date', $eventSeries->end_date ? \Carbon\Carbon::parse($eventSeries->end_date)->format('Y-m-d') : '') }}">
                </div>

            <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2 mt-8">3. Organisation</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="organizer" class="block text-sm font-medium text-gray-700 mb-1">Organisateur</label>
                    <input type="text" name="organizer" id="organizer"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('organizer', $eventSeries->organizer) }}">
                </div>
                <div>
                    <label for="sport_type" class="block text-sm font-medium text-gray-700 mb-1">Type de Sport</label>
                    <input type="text" name="sport_type" id="sport_type" placeholder="football, tennis, basketball..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('sport_type', $eventSeries->sport_type) }}">
                </div>

            <h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2 mt-8">4. Images</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="main_image" class="block text-sm font-medium text-gray-700 mb-1">Image Principale</label>
                    <input type="file" name="main_image" id="main_image" accept="image/*"
                        class="w-full p-2 border border-gray-300 rounded-lg bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-purple-700">
                    @if($isEdit && $eventSeries->getFirstMediaUrl('main_image'))
                        <div class="mt-2">
                            <img src="{{ $eventSeries->getFirstMediaUrl('main_image', 'small') }}" class="w-32 h-32 object-cover rounded-lg">
                            <label class="flex items-center text-sm text-red-600 mt-2">
                                <input type="checkbox" name="remove_main_image" value="1" class="h-4 w-4 text-red-600 border-gray-300 rounded">
                                <span class="ml-2">Supprimer</span>
                            </label>
                        </div>
                    @endif
                </div>
                <div>
                    <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-1">Image de Couverture</label>
                    <input type="file" name="cover_image" id="cover_image" accept="image/*"
                        class="w-full p-2 border border-gray-300 rounded-lg bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-purple-700">
                    @if($isEdit && $eventSeries->getFirstMediaUrl('cover_image'))
                        <div class="mt-2">
                            <img src="{{ $eventSeries->getFirstMediaUrl('cover_image', 'small') }}" class="w-32 h-32 object-cover rounded-lg">
                            <label class="flex items-center text-sm text-red-600 mt-2">
                                <input type="checkbox" name="remove_cover_image" value="1" class="h-4 w-4 text-red-600 border-gray-300 rounded">
                                <span class="ml-2">Supprimer</span>
                            </label>
                        </div>
                    @endif
                </div>

            <div class="flex items-center space-x-6 mt-4 mb-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        class="h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary"
                        {{ old('is_active', $eventSeries->is_active ?? true) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Actif</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1"
                        class="h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary"
                        {{ old('is_featured', $eventSeries->is_featured) ? 'checked' : '' }}>
                    <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">Mis en Vedette</label>
                </div>

            <div class="mt-8 pt-4 border-t">
                <button type="submit"
                    class="w-full py-3 px-4 rounded-lg text-white font-semibold bg-primary hover:bg-purple-700 transition duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-primary/50">
                    <i class="fas fa-{{ $isEdit ? 'save' : 'plus-circle' }} mr-2"></i> {{ $isEdit ? 'Enregistrer les Modifications' : 'Créer la Série' }}
                </button>
            </div>
        </form>
    </div>

@endsection
