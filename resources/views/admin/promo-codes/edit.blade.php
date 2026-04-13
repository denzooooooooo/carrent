@extends('admin.layouts.app')

@section('title', 'Modifier un code promo')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 py-2">
        <section class="admin-page-header">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--admin-brand)]">Offres & incentives</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Modifier {{ $promoCode->code }}</h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                    Ajuste la remise, la période de validité et l’activation sans perdre l’historique d’utilisation.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.promo-codes.show', $promoCode->id) }}" class="admin-btn-ghost px-5 py-3 text-sm">
                    <i class="fas fa-eye"></i>
                    Voir la fiche
                </a>
                <a href="{{ route('admin.promo-codes.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
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

        @if (session('success'))
            <div class="rounded-[1.5rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-[1.5rem] border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.promo-codes.update', $promoCode->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.promo-codes._form', ['promoCode' => $promoCode])
        </form>
    </div>
@endsection
