@extends('admin.layouts.app')

@section('title', 'Créer un Slide de Carrousel')

@section('content')

    <div class="max-w-4xl mx-auto py-8">
        <div class="flex justify-between items-center mb-8 border-b pb-2">
            <h1 class="text-3xl font-bold text-dark gradient-text">Créer un Nouveau Slide</h1>
            <a href="{{ route('admin.carousels.index') }}"
                class="py-2 px-4 rounded-lg text-white font-semibold bg-gray-600 hover:bg-gray-700 transition duration-300 shadow-md flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>

        {{-- Formulaire de Création --}}
        <form action="{{ route('admin.carousels.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Inclusion du formulaire partiel --}}
            @include('admin.carousels._form', ['carousel' => $carousel])

            {{-- BOUTON DE SOUMISSION --}}
            <div class="mt-8 pt-4 border-t bg-white p-6 rounded-xl shadow-2xl">
                <button type="submit"
                    class="w-full py-3 px-4 rounded-lg text-white font-semibold bg-primary hover:bg-purple-700 transition duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    <i class="fas fa-plus-circle mr-2"></i> Créer le Slide
                </button>
            </div>
        </form>


    </div>
@endsection