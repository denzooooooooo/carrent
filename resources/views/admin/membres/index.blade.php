@extends('admin.layouts.app')

@section('title', 'Gestion des Membres')

@section('content')

<div class="py-8">
<div class="flex justify-between items-center mb-6 border-b pb-2">
<h1 class="text-3xl font-bold text-dark gradient-text">Liste des Membres Administratifs</h1>
<a href="{{ route('admin.members.create') }}" class="py-2 px-4 bg-primary text-white rounded-lg shadow-md hover:bg-purple-700 transition duration-300 flex items-center">
<i class="fas fa-plus mr-2"></i> Ajouter un Membre
</a>
</div>

@if($members->isEmpty())
    <div class="text-center p-10 bg-white rounded-xl shadow-lg border border-gray-100">
        <i class="fas fa-users-slash text-6xl text-gray-300 mb-4"></i>
        <p class="text-xl text-gray-500">Aucun membre administratif n'est enregistré pour l'instant.</p>
    </div>
@else
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avatar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom & Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière Connexion</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($members as $member)
                        <tr class="hover:bg-gray-50 transition duration-150 @if(Auth::guard('admin')->id() === $member->id) bg-purple-50 @endif">
                            
                            {{-- Colonne Avatar --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $avatarUrl = $member->getFirstMediaUrl('avatar', 'small');
                                    if (!$avatarUrl) {
                                        $avatarUrl = 'https://placehold.co/40x40/4c1d95/ffffff?text=' . strtoupper(substr($member->name, 0, 1));
                                    }
                                @endphp
                                <img src="{{ $avatarUrl }}" alt="{{ $member->name }}" class="w-10 h-10 object-cover rounded-full">
                            </td>

                            {{-- Colonne Nom & Email --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $member->name }} @if(Auth::guard('admin')->id() === $member->id) <span class="ml-1 text-xs px-2 py-0.5 bg-primary/20 text-primary rounded-full">Moi</span> @endif</div>
                                <div class="text-sm text-gray-500">{{ $member->email }}</div>
                            </td>

                            {{-- Colonne Rôle --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $roleClass = [
                                        'super_admin' => 'bg-red-100 text-red-800',
                                        'admin' => 'bg-primary/10 text-primary',
                                        'moderator' => 'bg-yellow-100 text-yellow-800',
                                    ][$member->role] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleClass }} capitalize">
                                    {{ str_replace('_', ' ', $member->role) }}
                                </span>
                            </td>

                            {{-- Colonne Statut --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($member->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactif</span>
                                @endif
                            </td>

                            {{-- Colonne Dernière Connexion --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $member->last_login ? $member->last_login->diffForHumans() : 'Jamais' }}
                            </td>

                            {{-- Colonne Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.members.edit', $member) }}" class="text-primary hover:text-purple-700 mr-3 transition duration-150" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <button onclick="confirmDelete('{{ $member->id }}', '{{ $member->name }}')" class="text-red-600 hover:text-red-900 transition duration-150" title="Supprimer" 
                                        {{ Auth::guard('admin')->id() === $member->id ? 'disabled' : '' }}>
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                {{-- Formulaire de suppression caché --}}
                                <form id="delete-form-{{ $member->id }}" action="{{ route('admin.members.destroy', $member) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="p-4 border-t bg-gray-50">
            {{ $members->links() }}
        </div>
    </div>
@endif


</div>

{{-- Modal de confirmation de suppression --}}

<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
<div class="bg-white p-6 rounded-xl shadow-2xl max-w-sm w-full animate-pop-in">
<div class="text-center">
<i class="fas fa-exclamation-triangle text-5xl text-red-500 mb-4"></i>
<h3 class="text-xl font-bold text-gray-900 mb-2">Confirmation de Suppression</h3>
<p class="text-sm text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer le membre <span id="member-name" class="font-semibold text-red-700"></span> ? Cette action est irréversible.</p>
<div class="flex justify-center space-x-4">
<button onclick="document.getElementById('delete-modal').classList.add('hidden')" class="py-2 px-4 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">Annuler</button>
<button id="confirm-delete-button" class="py-2 px-4 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150">Supprimer</button>
</div>
</div>
</div>
</div>

@endsection

@push('scripts')

<script>
// Fonction pour afficher le modal de confirmation
function confirmDelete(memberId, memberName) {
document.getElementById('member-name').textContent = memberName;
document.getElementById('delete-modal').classList.remove('hidden');

    const confirmButton = document.getElementById(&#39;confirm-delete-button&#39;);
    
    // Supprime tout ancien écouteur pour éviter les doubles suppressions
    confirmButton.replaceWith(confirmButton.cloneNode(true));
    const newConfirmButton = document.getElementById(&#39;confirm-delete-button&#39;);
    
    // Ajoute le nouvel écouteur qui soumet le formulaire correct
    newConfirmButton.addEventListener(&#39;click&#39;, function() {
        document.getElementById(&#39;delete-form-&#39; + memberId).submit();
    });
}

// Styles pour l&#39;animation du modal (ajustements Tailwind non inclus ici)
// Vous pouvez définir &#39;animate-pop-in&#39; dans votre configuration Tailwind si nécessaire.
// Pour l&#39;instant, c&#39;est juste une classe CSS placeholder.


</script>

@endpush