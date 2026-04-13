@extends('admin.layouts.app')

@section('title', 'Créer une location')

@section('content')
    @php
        $location = new \App\Models\Location();
    @endphp

    <div class="mx-auto max-w-7xl space-y-8 py-2">
        <section class="admin-page-header">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--admin-brand)]">Mobilité premium</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Créer une location</h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                    Ajoute un véhicule ou un service de mobilité complet avec son positionnement commercial et ses caractéristiques.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.locations.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                    <i class="fas fa-arrow-left"></i>
                    Retour à la liste
                </a>
            </div>
        </section>

        @if ($errors->any())
            <div class="rounded-[1.5rem] border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                <p class="font-semibold text-red-800">Des champs sont invalides</p>
                <ul class="mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-[1.5rem] border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.locations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('admin.locations._form', ['location' => $location])
        </form>
    </div>
@endsection
