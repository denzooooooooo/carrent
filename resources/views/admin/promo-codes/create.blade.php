@extends('admin.layouts.app')

@section('title', 'Créer un code promo')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 py-2">
        <section class="admin-page-header">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--admin-brand)]">Offres & incentives</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Créer un code promo</h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                    Prépare une remise exploitable par le site et l’équipe commerciale, avec fenêtre de validité et ciblage clair.
                </p>
            </div>
            <a href="{{ route('admin.promo-codes.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
                <i class="fas fa-arrow-left"></i>
                Retour à la liste
            </a>
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

        <form action="{{ route('admin.promo-codes.store') }}" method="POST" class="space-y-6">
            @csrf
            @include('admin.promo-codes._form')
        </form>
    </div>
@endsection
