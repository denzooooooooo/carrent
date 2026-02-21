@extends('admin.layouts.app')

@section('title', 'Gestion des Catégories')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Gestion des Catégories</h1>
        <a href="{{ route('admin.categories.create', ['type' => $type]) }}" 
           class="px-4 py-2 bg-gradient-to-r from-primary to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-primary transition shadow-lg">
            <i class="fas fa-plus-circle mr-2"></i>Ajouter
        </a>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-t-xl shadow-lg mb-0">
        <div class="flex border-b">
            <a href="{{ route('admin.categories.index', ['type' => 'category']) }}" 
               class="px-6 py-4 font-semibold transition {{ $type === 'category' ? 'border-b-2 border-primary text-primary' : 'text-gray-600 hover:text-primary' }}">
                <i class="fas fa-layer-group mr-2"></i>Catégories Générales
            </a>
            <a href="{{ route('admin.categories.index', ['type' => 'event_category']) }}" 
               class="px-6 py-4 font-semibold transition {{ $type === 'event_category' ? 'border-b-2 border-primary text-primary' : 'text-gray-600 hover:text-primary' }}">
                <i class="fas fa-calendar-alt mr-2"></i>Catégories Événements
            </a>
            <a href="{{ route('admin.categories.index', ['type' => 'event_type']) }}" 
               class="px-6 py-4 font-semibold transition {{ $type === 'event_type' ? 'border-b-2 border-primary text-primary' : 'text-gray-600 hover:text-primary' }}">
                <i class="fas fa-tags mr-2"></i>Types Événements
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-wrap gap-4">
            <input type="hidden" name="type" value="{{ $type }}">
            
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
            </div>

            @if($type === 'event_type' && isset($eventCategories))
                <div class="min-w-[200px]">
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Toutes les catégories</option>
                        @foreach($eventCategories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name_fr }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="min-w-[150px]">
                <select name="is_active" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Tous les statuts</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Actif</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>

            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>

            @if(request()->hasAny(['search', 'category_id', 'is_active']))
                <a href="{{ route('admin.categories.index', ['type' => $type]) }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i>Réinitialiser
                </a>
            @endif
        </form>
    </div>

    <!-- Liste -->
    <div class="bg-white rounded-b-xl shadow-lg overflow-hidden">
        @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Image</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Nom</th>
                            
                            @if($type === 'category')
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Parent</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Ordre</th>
                            @endif

                            @if($type === 'event_type')
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Catégorie</th>
                            @endif

                            @if($type === 'event_category')
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Types</th>
                            @endif

                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Événements</th>
                            
                            @if($type === 'category')
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Packages</th>
                            @endif

                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Statut</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Image -->
                                <td class="px-6 py-4">
                                    @php
                                        $imageUrl = $category->getFirstMediaUrl('avatar', 'small');
                                        $placeholder = 'https://placehold.co/100x100/9333EA/ffffff?text=' . substr($category->name_fr, 0, 1);
                                    @endphp
                                    <img src="{{ $imageUrl ?: $placeholder }}" 
                                         alt="{{ $category->name_fr }}" 
                                         class="w-12 h-12 rounded-lg object-cover shadow"
                                         onerror="this.src='{{ $placeholder }}'">
                                </td>

                                <!-- Nom -->
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $category->name_fr }}</p>
                                        <p class="text-sm text-gray-500">{{ $category->name_en }}</p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $category->slug }}</p>
                                    </div>
                                </td>

                                <!-- Parent (Catégories simples uniquement) -->
                                @if($type === 'category')
                                    <td class="px-6 py-4">
                                        @if($category->parent)
                                            <span class="text-sm text-gray-700">{{ $category->parent->name_fr }}</span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Racine</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-gray-700">{{ $category->order_position ?? 0 }}</span>
                                    </td>
                                @endif

                                <!-- Catégorie (Types d'événements uniquement) -->
                                @if($type === 'event_type')
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-700">{{ $category->category->name_fr ?? 'N/A' }}</span>
                                    </td>
                                @endif

                                <!-- Types (Catégories d'événements uniquement) -->
                                @if($type === 'event_category')
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                            {{ $category->types_count ?? 0 }}
                                        </span>
                                    </td>
                                @endif

                                <!-- Événements -->
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold">
                                        {{ $category->events_count ?? $category->events()->count() }}
                                    </span>
                                </td>

                                <!-- Packages (Catégories simples uniquement) -->
                                @if($type === 'category')
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                            {{ $category->packages()->count() }}
                                        </span>
                                    </td>
                                @endif

                                <!-- Statut -->
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.categories.toggle-status', ['id' => $category->id, 'type' => $type]) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-3 py-1 text-xs font-semibold rounded-full transition
                                                {{ $category->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                            {{ $category->is_active ? 'Actif' : 'Inactif' }}
                                        </button>
                                    </form>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.categories.show', ['category' => $category->id, 'type' => $type]) }}" 
                                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                           title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', ['category' => $category->id, 'type' => $type]) }}" 
                                           class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', ['category' => $category->id, 'type' => $type]) }}" 
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $categories->appends(request()->except('page'))->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500 mb-4">Aucune catégorie trouvée</p>
                <a href="{{ route('admin.categories.create', ['type' => $type]) }}" 
                   class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-purple-700 transition">
                    <i class="fas fa-plus-circle mr-2"></i>Créer la première catégorie
                </a>
            </div>
        @endif
    </div>
</div>
@endsection