<div class="bg-white p-6 rounded-xl shadow-2xl border border-gray-100">
    <div class="flex justify-between items-center mb-6 border-b pb-3">
        <h2 class="text-2xl font-semibold text-gray-800">{{ $title }} (Derniers 5)</h2>
        <a href="{{ route('admin.categories.create', $type) }}"
            class="py-2 px-4 rounded-lg text-white font-semibold bg-primary hover:bg-purple-700 transition duration-300 shadow-md flex items-center">
            <i class="fas fa-plus-circle mr-2"></i> Créer Nouveau
        </a>
    </div>

    @if ($categories->isEmpty())
        <div class="text-center py-6 text-gray-500">Aucune catégorie trouvée de ce type.</div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom (FR)
                        </th>
                        @if (isset($hasParent) && $hasParent)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent
                            </th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ $category->getFirstMediaUrl('avatar', 'thumb') ?: 'https://placehold.co/40x40/f3f4f6/374151?text=N/A' }}"
                                    alt="{{ $category->name_fr }}" class="h-10 w-10 rounded-full object-cover">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $category->name_fr }}
                            </td>
                            @if (isset($hasParent) && $hasParent)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $category->category->name_fr ?? 'N/A' }}
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($category->is_active)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.categories.edit', ['type' => $type, 'id' => $category->id]) }}"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', ['type' => $type, 'id' => $category->id]) }}"
                                    method="POST" class="inline"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif


</div>