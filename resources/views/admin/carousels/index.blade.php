@extends('admin.layouts.app')

@section('title', 'Gestion des Slides de Carrousel')

@section('content')

    <div class="max-w-7xl mx-auto py-8 space-y-8">
        <div class="flex justify-between items-center mb-6 border-b pb-3">
            <h1 class="text-4xl font-extrabold text-dark gradient-text">Gestion des Slides de Carrousel</h1>
            <a href="{{ route('admin.carousels.create') }}"
                class="py-2 px-4 rounded-lg text-white font-semibold bg-primary hover:bg-purple-700 transition duration-300 shadow-md flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> Ajouter un Slide
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Erreur!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif


        <div class="bg-white p-6 rounded-xl shadow-2xl border border-gray-100">
            @if ($carousels->isEmpty())
                <div class="text-center py-6 text-gray-500">Aucun slide de carrousel n'a été créé.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordre
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aperçu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre
                                    (FR)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Période</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($carousels as $carousel)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $carousel->order_position }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img src="{{ $carousel->getFirstMediaUrl('image_desktop', 'thumb') ?: 'https://placehold.co/100x50/f3f4f6/374151?text=N/A' }}"
                                            alt="Image Desktop" class="h-12 w-24 object-cover rounded shadow">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $carousel->title_fr }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($carousel->is_active)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $carousel->start_date ? $carousel->start_date->format('d/m/Y') : 'Toujours' }} -
                                        {{ $carousel->end_date ? $carousel->end_date->format('d/m/Y') : 'Toujours' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.carousels.edit', $carousel) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <form action="{{ route('admin.carousels.destroy', $carousel) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce slide ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-2">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $carousels->links() }}
                </div>
            @endif
        </div>


    </div>
@endsection