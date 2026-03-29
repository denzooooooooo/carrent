@php
    $admin = auth('admin')->user();
    $unreadCount = $admin->unreadNotifications()->count();
    $routeName = request()->route()?->getName() ?? '';

    $context = match (true) {
        str_starts_with($routeName, 'admin.events.') => [
            'label' => 'Catalogue événements',
            'description' => 'Création, import PDF, packages, zones et publication.',
        ],
        str_starts_with($routeName, 'admin.bookings.') => [
            'label' => 'Réservations',
            'description' => 'Suivi opérationnel des commandes, statuts et paiements.',
        ],
        str_starts_with($routeName, 'admin.users.') => [
            'label' => 'Clients',
            'description' => 'Gestion des comptes, accès et historique d’activité.',
        ],
        str_starts_with($routeName, 'admin.packages.') => [
            'label' => 'Packages',
            'description' => 'Pilotage du catalogue premium et des contenus de vente.',
        ],
        str_starts_with($routeName, 'admin.locations.') => [
            'label' => 'Locations',
            'description' => 'Gestion des véhicules, disponibilité et présentation.',
        ],
        default => [
            'label' => 'Administration',
            'description' => 'Interface de pilotage Carré Premium.',
        ],
    };

    $quickActions = [
        [
            'label' => 'Nouvel événement',
            'icon' => 'fa-plus',
            'href' => route('admin.events.create'),
            'active' => str_starts_with($routeName, 'admin.events.'),
        ],
        [
            'label' => 'Import PDF',
            'icon' => 'fa-file-pdf',
            'href' => route('admin.events.import.form'),
            'active' => str_starts_with($routeName, 'admin.events.import'),
        ],
        [
            'label' => 'Réservations',
            'icon' => 'fa-ticket',
            'href' => route('admin.bookings.index'),
            'active' => str_starts_with($routeName, 'admin.bookings.'),
        ],
    ];
@endphp

<header class="mb-6 rounded-[1.75rem] border border-[#eadfce] bg-white/85 px-4 py-4 shadow-[0_20px_50px_rgba(38,24,59,0.08)] backdrop-blur md:px-6">
    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
        <div class="flex items-start gap-3">
            <button id="sidebar-toggle"
                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-[#eadfce] bg-white text-slate-600 transition hover:border-[rgba(91,33,182,0.18)] hover:text-[var(--admin-brand)] md:hidden">
                <i class="fas fa-bars text-lg"></i>
            </button>

            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full border border-[rgba(91,33,182,0.14)] bg-[var(--admin-brand-soft)] px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-[var(--admin-brand)]">
                        <i class="fas fa-layer-group"></i>
                        {{ $context['label'] }}
                    </span>
                    <span class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">
                        {{ now()->translatedFormat('l d F Y') }}
                    </span>
                </div>
                <h2 class="mt-3 text-2xl font-bold tracking-tight text-slate-950">
                    @yield('title', 'Dashboard')
                </h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    {{ $context['description'] }}
                </p>
            </div>
        </div>

        <div class="flex flex-col gap-4 xl:items-end">
            <div class="flex flex-wrap gap-2">
                @foreach ($quickActions as $action)
                    <a href="{{ $action['href'] }}"
                        class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold transition {{ $action['active'] ? 'border-transparent bg-[linear-gradient(135deg,var(--admin-brand),#6d28d9)] text-white shadow-[0_12px_26px_rgba(91,33,182,0.24)]' : 'border-[#eadfce] bg-white text-slate-700 hover:border-[rgba(91,33,182,0.22)] hover:text-[var(--admin-brand)]' }}">
                        <i class="fas {{ $action['icon'] }}"></i>
                        {{ $action['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="relative inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-[#eadfce] bg-white text-slate-600 transition hover:border-[rgba(91,33,182,0.2)] hover:text-[var(--admin-brand)]">
                        <i class="fas fa-bell text-lg"></i>
                        @if ($unreadCount > 0)
                            <span class="absolute -right-1 -top-1 inline-flex min-w-[1.4rem] items-center justify-center rounded-full bg-red-500 px-1.5 py-0.5 text-[11px] font-bold text-white">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <div x-show="open" x-transition @click.away="open = false"
                        class="dropdown-menu absolute right-0 z-50 mt-3 w-[22rem] overflow-hidden rounded-[1.5rem] border border-[#eadfce] bg-white shadow-2xl">
                        <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Notifications récentes</p>
                                <p class="text-xs text-slate-500">Les 10 dernières activités admin.</p>
                            </div>
                            <button @click="open = false" class="text-slate-400 transition hover:text-slate-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="max-h-80 divide-y divide-slate-100 overflow-y-auto">
                            @forelse ($admin->notifications()->latest()->take(10)->get() as $notification)
                                <div class="px-4 py-4 transition hover:bg-slate-50">
                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $notification->data['title'] ?? 'Nouvelle activité' }}
                                    </p>
                                    <p class="mt-1 text-sm leading-6 text-slate-600">
                                        {{ $notification->data['message'] ?? 'Une mise à jour a été enregistrée.' }}
                                    </p>
                                    <p class="mt-2 text-xs font-medium uppercase tracking-[0.2em] text-slate-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            @empty
                                <div class="px-4 py-8 text-center text-sm text-slate-500">
                                    Aucune notification pour le moment.
                                </div>
                            @endforelse
                        </div>

                        <div class="border-t border-slate-100 px-4 py-3 text-right">
                            <a href="{{ route('admin.notifications') }}"
                                class="text-sm font-semibold text-[var(--admin-brand)] transition hover:text-[var(--admin-brand-strong)]">
                                Voir toutes les notifications
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.profile') }}"
                    class="flex items-center gap-3 rounded-[1.25rem] border border-[#eadfce] bg-white px-3 py-2 pr-4 transition hover:border-[rgba(91,33,182,0.18)] hover:bg-white">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#5b21b6,#c88a2a)] text-sm font-bold uppercase text-white shadow-md">
                        {{ \Illuminate\Support\Str::substr($admin->name, 0, 1) }}
                    </span>
                    <span class="text-left">
                        <span class="block text-sm font-semibold text-slate-900">{{ $admin->name }}</span>
                        <span class="block text-xs uppercase tracking-[0.18em] text-slate-500">{{ str_replace('_', ' ', $admin->role) }}</span>
                    </span>
                </a>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-[#231631] px-4 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-black">
                        <i class="fas fa-right-from-bracket"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
