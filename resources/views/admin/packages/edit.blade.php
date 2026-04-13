@extends('admin.layouts.app')

@section('title', 'Modifier le package')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 py-2">
        <section class="admin-page-header">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--admin-brand)]">Catalogue premium</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Modifier le package</h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                    Ajuste le contenu, les tarifs, les médias et la visibilité de <span class="font-semibold text-slate-900">{{ $package->title_fr }}</span>.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.packages.show', $package) }}" class="admin-btn-ghost px-5 py-3 text-sm">
                    <i class="fas fa-eye"></i>
                    Voir la fiche
                </a>
                <a href="{{ route('admin.packages.index') }}" class="admin-btn-ghost px-5 py-3 text-sm">
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

        <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.packages._form', ['package' => $package, 'categories' => $categories, 'packageTypes' => $packageTypes])
        </form>
    </div>
@endsection
