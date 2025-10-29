@extends('admin.layouts.app')

@section('title', 'Détails de la Catégorie')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <div class="flex items-center space-x-3 mb-2">
                <h1 class="text-3xl font-bold text-gray-900">{{ $category->name_fr }}</h1>
                <span class="px-3 py-1 text-xs font-semibold rounded-full
                    {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $category->is_active ? 'Actif' : 'Inactif' }}
                </span>
            </div>
            <p class="text-gray-600">{{ $category->name_en }}</p>
            <p class="text-sm text-gray-500 font-mono mt-1">{{ $category->slug }}</p>
        </div>
        
        <div class="flex space-x-2">
            <a href="{{ route('admin.categories.index', ['type' => $type]) }}" 
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
            <a href="{{ route('admin.categories.edit', ['category' => $category->id, 'type' => $type]) }}" 
               class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne Principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Image -->
            @if($category->getFirstMediaUrl('avatar'))
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <img src="{{ $category->getFirstMediaUrl('avatar', 'normal') }}" 
                         alt="{{ $category->name_fr }}" 
                         class="w-full h-64 object-cover">
                </div>
            @endif

            <!-- Description -->
            @if(($type === 'category' && ($category->description_fr || $category->description_en)) || 
                ($type !== 'category' && $category->description))
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-align-left text-primary mr-2"></i>
                        Description
                    </h2>
                    
                    @if($type === 'category')
                        @if($category->description_fr)
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Français</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $category->description_fr }}</p>
                            </div>
                        @endif

                        @if($category->description_en)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">English</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $category->description_en }}</p>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-700 leading-relaxed">{{ $category->description }}</p>
                    @endif
                </div>
            @endif

            <!-- Relations -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-link text-primary mr-2"></i>
                    Relations
                </h2>

                @if($type === 'category')
                    <!-- Sous-catégories -->
                    @if($category->children && $category->children->count() > 0)
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-folder-tree text-blue-500 mr-2"></i>
                                Sous-catégories ({{ $category->children->count() }})
                            </h3>
                            <div class="space-y-2">
                                @foreach($category->children as $child)
                                    <a href="{{ route('admin.categories.show', ['category' => $child->id, 'type' => 'category']) }}" 
                                       class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                        <span class="font-medium text-gray-900">{{ $child->name_fr }}</span>
                                        <span class="text-sm text-gray-500 ml-2">({{ $child->name_en }})</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Packages -->
                    @if($category->packages && $category->packages->count() > 0)
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-suitcase text-green-500 mr-2"></i>
                                Packages Touristiques ({{ $category->packages->count() }})
                            </h3>
                            <div class="space-y-2">
                                @foreach($category->packages->take(5) as $package)
                                    <a href="{{ route('admin.packages.show', $package->id) }}" 
                                       class="block p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                                        <span class="font-medium text-gray-900">{{ $package->title_fr }}</span>
                                        <span class="text-sm text-gray-500 ml-2">{{ number_format($package->price, 0) }}€</span>
                                    </a>
                                @endforeach
                                @if($category->packages->count() > 5)
                                    <p class="text-sm text-gray-500 pl-3">Et {{ $category->packages->count() - 5 }} autres...</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Événements -->
                    @if($category->events && $category->events->count() > 0)
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-calendar-check text-purple-500 mr-2"></i>
                                Événements ({{ $category->events->count() }})
                            </h3>
                            <div class="space-y-2">
                                @foreach($category->events->take(5) as $event)
                                    <a href="{{ route('admin.events.show', $event->id) }}" 
                                       class="block p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                                        <span class="font-medium text-gray-900">{{ $event->title_fr }}</span>
                                        <span class="text-sm text-gray-500 ml-2">{{ $event->event_date->format('d/m/Y') }}</span>
                                    </a>
                                @endforeach
                                @if($category->events->count() > 5)
                                    <p class="text-sm text-gray-500 pl-3">Et {{ $category->events->count() - 5 }} autres...</p>
                                @endif
                            </div>
                        </div>
                    @endif
                @elseif($type === 'event_category')
                    <!-- Types d'événements -->
                    @if($category->types && $category->types->count() > 0)
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-tags text-blue-500 mr-2"></i>
                                Types d'Événements ({{ $category->types->count() }})
                            </h3>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($category->types as $type)
                                    <a href="{{ route('admin.categories.show', ['category' => $type->id, 'type' => 'event_type']) }}" 
                                       class="block p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition text-center">
                                        <span class="font-medium text-gray-900">{{ $type->name_fr }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Événements -->
                    @if($category->events && $category->events->count() > 0)
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-calendar-check text-purple-500 mr-2"></i>
                                Événements ({{ $category->events->count() }})
                            </h3>
                            <div class="space-y-2">
                                @foreach($category->events->take(5) as $event)
                                    <a href="{{ route('admin.events.show', $event->id) }}" 
                                       class="block p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                                        <span class="font-medium text-gray-900">{{ $event->title_fr }}</span>
                                        <span class="text-sm text-gray-500 ml-2">{{ $event->event_date->format('d/m/Y') }}</span>
                                    </a>
                                @endforeach
                                @if($category->events->count() > 5)
                                    <p class="text-sm text-gray-500 pl-3">Et {{ $category->events->count() - 5 }} autres...</p>
                                @endif
                            </div>
                        </div>
                    @endif
                @elseif($type === 'event_type')
                    <!-- Catégorie parente -->
                    @if($category->category)
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-folder text-blue-500 mr-2"></i>
                                Catégorie Parente
                            </h3>
                            <a href="{{ route('admin.categories.show', ['category' => $category->category->id, 'type' => 'event_category']) }}" 
                               class="block p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                <span class="font-medium text-gray-900">{{ $category->category->name_fr }}</span>
                                <span class="text-sm text-gray-500 ml-2">({{ $category->category->name_en }})</span>
                            </a>
                        </div>
                    @endif

                    <!-- Événements -->
                    @if($category->events && $category->events->count() > 0)
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-calendar-check text-purple-500 mr-2"></i>
                                Événements ({{ $category->events->count() }})
                            </h3>
                            <div class="space-y-2">
                                @foreach($category->events->take(5) as $event)
                                    <a href="{{ route('admin.events.show', $event->id) }}" 
                                       class="block p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                                        <span class="font-medium text-gray-900">{{ $event->title_fr }}</span>
                                        <span class="text-sm text-gray-500 ml-2">{{ $event->event_date->format('d/m/Y') }}</span>
                                    </a>
                                @endforeach
                                @if($category->events->count() > 5)
                                    <p class="text-sm text-gray-500 pl-3">Et {{ $category->events->count() - 5 }} autres...</p>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif

                @if(($type === 'category' && !$category->children->count() && !$category->packages->count() && !$category->events->count()) ||
                    ($type === 'event_category' && !$category->types->count() && !$category->events->count()) ||
                    ($type === 'event_type' && !$category->events->count()))
                    <p class="text-gray-500 italic text-center py-8">Aucune relation trouvée</p>
                @endif
            </div>
        </div>

        <!-- Colonne Latérale -->
        <div class="space-y-6">
            <!-- Informations -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Informations</h3>
                
                <div class="space-y-3">
                    @if($type === 'category' && $category->parent)
                        <div class="flex items-center justify-between py-2 border-b">
                            <span class="text-gray-600 flex items-center">
                                <i class="fas fa-folder w-5 text-primary mr-2"></i>Parent
                            </span>
                            <a href="{{ route('admin.categories.show', ['category' => $category->parent->id, 'type' => 'category']) }}" 
                               class="font-semibold text-primary hover:underline">
                                {{ $category->parent->name_fr }}
                            </a>
                        </div>
                    @endif

                    @if($type === 'category' && $category->icon)
                        <div class="flex items-center justify-between py-2 border-b">
                            <span class="text-gray-600">Icône</span>
                            <span class="font-semibold"><i class="{{ $category->icon }} mr-1"></i> {{ $category->icon }}</span>
                        </div>
                    @endif

                    @if($type === 'category')
                        <div class="flex items-center justify-between py-2 border-b">
                            <span class="text-gray-600">Position</span>
                            <span class="font-semibold">{{ $category->order_position ?? 0 }}</span>
                        </div>
                    @endif

                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-600">Statut</span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Actions</h3>
                
                <div class="space-y-2">
                    <form action="{{ route('admin.categories.toggle-status', ['id' => $category->id, 'type' => $type]) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full py-2 px-4 rounded-lg transition
                                {{ $category->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                            <i class="fas fa-{{ $category->is_active ? 'ban' : 'check' }} mr-2"></i>
                            {{ $category->is_active ? 'Désactiver' : 'Activer' }}
                        </button>
                    </form>

                    <a href="{{ route('admin.categories.edit', ['category' => $category->id, 'type' => $type]) }}" 
                       class="block w-full py-2 px-4 text-center bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>

                    <form action="{{ route('admin.categories.destroy', ['category' => $category->id, 'type' => $type]) }}" 
                          method="POST" onsubmit="return confirm('Êtes-vous sûr ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full py-2 px-4 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <!-- Métadonnées -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Métadonnées</h3>
                
                <div class="text-sm text-gray-600 space-y-2">
                    <p><strong>Créé le :</strong><br>{{ $category->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Modifié le :</strong><br>{{ $category->updated_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Slug :</strong><br><span class="font-mono text-xs">{{ $category->slug }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection