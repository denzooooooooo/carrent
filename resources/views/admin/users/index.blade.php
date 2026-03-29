@extends('admin.layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="mx-auto max-w-7xl space-y-8">
    <section class="admin-page-header">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-purple-600">Communauté</p>
            <h1 class="mt-2 text-3xl font-black text-gray-900">Utilisateurs</h1>
            <p class="mt-3 max-w-2xl text-gray-600">Pilotage des profils clients, de l’activité et des points de fidélité dans une vue plus propre et plus rapide à exploiter.</p>
        </div>
        <button type="button" onclick="openAddModal()" class="admin-btn-primary px-6 py-3 text-sm">
            <i class="fas fa-plus"></i>
            Ajouter un utilisateur
        </button>
    </section>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <article class="admin-kpi p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600">Total</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                    <p class="mt-2 text-sm text-gray-600">Profils enregistrés</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-purple-100 text-purple-700">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-emerald-600">Actifs</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['active'] ?? 0 }}</p>
                    <p class="mt-2 text-sm text-gray-600">{{ $stats['inactive'] ?? 0 }} inactifs</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                    <i class="fas fa-user-check text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi admin-kpi-accent p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-700">Nouveaux 30j</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['new'] ?? 0 }}</p>
                    <p class="mt-2 text-sm text-gray-600">Croissance récente</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
            </div>
        </article>

        <article class="admin-kpi p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-600">Points fidélité</p>
            <div class="mt-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-4xl font-black text-gray-900">{{ number_format($stats['total_points'] ?? 0) }}</p>
                    <p class="mt-2 text-sm text-gray-600">Cumul plateforme</p>
                </div>
                <div class="flex h-15 w-15 items-center justify-center rounded-2xl bg-purple-100 text-purple-700">
                    <i class="fas fa-star text-2xl"></i>
                </div>
            </div>
        </article>
    </section>

    <section class="admin-panel p-6 sm:p-7">
        <div class="flex flex-col gap-4 border-b border-gray-100 pb-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Filtres</p>
                <h2 class="mt-2 text-2xl font-black text-gray-900">Filtrer la base clients</h2>
            </div>
            <a href="{{ route('admin.users.index') }}" class="admin-btn-ghost px-4 py-3 text-sm">
                <i class="fas fa-rotate-right"></i>
                Réinitialiser
            </a>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}" class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
            <label class="xl:col-span-2">
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Recherche</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email, téléphone..." class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
            </label>

            <label>
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Statut</span>
                <select name="status" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                    <option value="">Tous</option>
                    <option value="1" @selected(request('status') === '1')>Actif</option>
                    <option value="0" @selected(request('status') === '0')>Inactif</option>
                </select>
            </label>

            <label>
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Pays</span>
                <select name="country" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                    <option value="">Tous les pays</option>
                    @foreach(($countries ?? collect()) as $country)
                        <option value="{{ $country }}" @selected(request('country') === $country)>{{ $country }}</option>
                    @endforeach
                </select>
            </label>

            <div class="flex items-end">
                <button type="submit" class="admin-btn-primary w-full px-5 py-3 text-sm">
                    <i class="fas fa-magnifying-glass"></i>
                    Filtrer
                </button>
            </div>
        </form>
    </section>

    <section class="space-y-4 lg:hidden">
        @forelse($users as $user)
            @php
                $initials = strtoupper(substr($user->first_name ?? 'C', 0, 1) . substr($user->last_name ?? 'P', 0, 1));
            @endphp
            <article class="admin-panel p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-4">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->first_name }}" class="h-14 w-14 rounded-full object-cover">
                        @else
                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-purple-100 text-sm font-black text-purple-700">{{ $initials }}</div>
                        @endif
                        <div>
                            <h2 class="text-lg font-black text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h2>
                            <p class="mt-1 text-sm text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="toggleStatus({{ $user->id }}, {{ $user->is_active ? 1 : 0 }})" class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->is_active ? 'Actif' : 'Inactif' }}
                    </button>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-gray-50 px-4 py-3">
                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-gray-500">Pays</p>
                        <p class="mt-2 text-sm font-bold text-gray-900">{{ $user->country ?: 'Non renseigné' }}</p>
                    </div>
                    <div class="rounded-2xl bg-gray-50 px-4 py-3">
                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-gray-500">Points</p>
                        <p class="mt-2 text-sm font-bold text-gray-900">{{ number_format($user->loyalty_points ?? 0) }}</p>
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="admin-btn-primary px-4 py-3 text-sm">
                        <i class="fas fa-eye"></i>
                        Ouvrir
                    </a>
                    <button type="button" onclick="editUser({{ $user->id }})" class="admin-btn-ghost px-4 py-3 text-sm">
                        <i class="fas fa-pen"></i>
                        Modifier
                    </button>
                </div>
            </article>
        @empty
            <div class="admin-panel px-6 py-14 text-center">
                <i class="fas fa-users text-5xl text-gray-300"></i>
                <p class="mt-4 text-lg font-bold text-gray-700">Aucun utilisateur trouvé</p>
                <p class="mt-2 text-sm text-gray-500">Ajoutez un utilisateur ou modifiez les filtres.</p>
            </div>
        @endforelse
    </section>

    <section class="admin-panel hidden overflow-hidden lg:block">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Base clients</p>
                <h2 class="mt-2 text-2xl font-black text-gray-900">Utilisateurs actifs et historiques</h2>
            </div>
            <span class="rounded-full bg-purple-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-purple-700">{{ $users->total() }} profils</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-gray-500">Utilisateur</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-gray-500">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-gray-500">Localisation</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-gray-500">Points</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-gray-500">Statut</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-gray-500">Inscription</th>
                        <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-[0.18em] text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($users as $user)
                        @php
                            $initials = strtoupper(substr($user->first_name ?? 'C', 0, 1) . substr($user->last_name ?? 'P', 0, 1));
                        @endphp
                        <tr class="transition hover:bg-[#fcfaf7]">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->first_name }}" class="h-12 w-12 rounded-full object-cover">
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 text-sm font-black text-purple-700">{{ $initials }}</div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-black text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</p>
                                        <p class="mt-1 text-xs text-gray-500">ID #{{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-semibold text-gray-900">{{ $user->email }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $user->phone ?: 'Téléphone non renseigné' }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-semibold text-gray-900">{{ $user->city ?: 'Ville non renseignée' }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $user->country ?: 'Pays non renseigné' }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-amber-800">
                                    <i class="fas fa-star text-[11px]"></i>
                                    {{ number_format($user->loyalty_points ?? 0) }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <button type="button" onclick="toggleStatus({{ $user->id }}, {{ $user->is_active ? 1 : 0 }})" class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                </button>
                            </td>
                            <td class="px-6 py-5 text-sm text-gray-600">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-sky-50 text-sky-700 transition hover:bg-sky-100" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" onclick="editUser({{ $user->id }})" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-purple-50 text-purple-700 transition hover:bg-purple-100" title="Modifier">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-red-50 text-red-700 transition hover:bg-red-100" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <i class="fas fa-users text-5xl text-gray-300"></i>
                                <p class="mt-4 text-lg font-bold text-gray-700">Aucun utilisateur trouvé</p>
                                <p class="mt-2 text-sm text-gray-500">Ajoutez un utilisateur ou ajustez les filtres.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-6 py-5">
            {{ $users->links('pagination::tailwind') }}
        </div>
    </section>

    @if($users->hasPages())
        <div class="lg:hidden">
            {{ $users->links('pagination::tailwind') }}
        </div>
    @endif
</div>

<div id="userModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/55 p-4 backdrop-blur-sm">
    <div class="w-full max-w-4xl overflow-hidden rounded-[2rem] border border-white/30 bg-white shadow-[0_28px_90px_rgba(17,10,29,0.28)]">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5 sm:px-7">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Fiche utilisateur</p>
                <h3 class="mt-2 text-2xl font-black text-gray-900" id="modalTitle">Ajouter un utilisateur</h3>
            </div>
            <button type="button" onclick="closeModal()" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-600 transition hover:bg-gray-200">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <form id="userForm" method="POST" class="space-y-6 px-6 py-6 sm:px-7">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="user_id" id="userId">

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Prénoms</span>
                    <input type="text" name="first_name" id="first_name" required class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                </label>

                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Nom</span>
                    <input type="text" name="last_name" id="last_name" required class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                </label>

                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Email</span>
                    <input type="email" name="email" id="email" required class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                </label>

                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Téléphone</span>
                    <input type="tel" name="phone" id="phone" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                </label>

                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Mot de passe <span id="passwordRequired">*</span></span>
                    <input type="password" name="password" id="password" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                    <p class="mt-2 text-xs text-gray-500" id="passwordHint">Minimum 8 caractères</p>
                </label>

                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Pays</span>
                    <select name="country" id="country" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                        @foreach(($countries ?? collect(['Côte d\'Ivoire', 'France', 'Sénégal'])) as $country)
                            <option value="{{ $country }}">{{ $country }}</option>
                        @endforeach
                    </select>
                </label>

                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Ville</span>
                    <input type="text" name="city" id="city" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                </label>

                <label>
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Points fidélité</span>
                    <input type="number" name="loyalty_points" id="loyalty_points" min="0" value="0" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100">
                </label>

                <label class="md:col-span-2">
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-gray-500">Adresse</span>
                    <textarea name="address" id="address" rows="3" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-100"></textarea>
                </label>

                <label class="md:col-span-2 flex items-center gap-3 rounded-2xl border border-gray-200 bg-gray-50 px-4 py-4">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                    <span class="text-sm font-semibold text-gray-700">Compte actif immédiatement</span>
                </label>
            </div>

            <div class="flex flex-col gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeModal()" class="admin-btn-ghost px-6 py-3 text-sm">
                    Annuler
                </button>
                <button type="submit" class="admin-btn-primary px-6 py-3 text-sm">
                    <i class="fas fa-save"></i>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('userModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Ajouter un utilisateur';
    document.getElementById('userForm').reset();
    document.getElementById('userForm').action = '{{ route("admin.users.store") }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('password').required = true;
    document.getElementById('passwordRequired').style.display = 'inline';
    document.getElementById('passwordHint').textContent = 'Minimum 8 caractères';
    document.getElementById('is_active').checked = true;
}

function editUser(id) {
    fetch(`/admin/users/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('userModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Modifier l\'utilisateur';
            document.getElementById('userForm').action = `/admin/users/${id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('userId').value = id;
            document.getElementById('first_name').value = data.first_name || '';
            document.getElementById('last_name').value = data.last_name || '';
            document.getElementById('email').value = data.email || '';
            document.getElementById('phone').value = data.phone || '';
            document.getElementById('country').value = data.country || '';
            document.getElementById('city').value = data.city || '';
            document.getElementById('address').value = data.address || '';
            document.getElementById('loyalty_points').value = data.loyalty_points || 0;
            document.getElementById('is_active').checked = !!data.is_active;
            document.getElementById('password').required = false;
            document.getElementById('password').value = '';
            document.getElementById('passwordRequired').style.display = 'none';
            document.getElementById('passwordHint').textContent = 'Laissez vide pour conserver le mot de passe existant';
        })
        .catch(() => {
            alert('Erreur lors du chargement de la fiche utilisateur.');
        });
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

function toggleStatus(userId) {
    if (!confirm('Voulez-vous changer le statut de cet utilisateur ?')) {
        return;
    }

    fetch(`/admin/users/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Erreur lors du changement de statut.');
        }
    })
    .catch(() => {
        alert('Erreur lors du changement de statut.');
    });
}

document.getElementById('userModal').addEventListener('click', function (event) {
    if (event.target === this) {
        closeModal();
    }
});
</script>
@endsection
