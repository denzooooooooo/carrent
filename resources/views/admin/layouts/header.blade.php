@php
    $admin = auth('admin')->user();

    $groups = [
        [
            'title' => 'Pilotage',
            'items' => [
                [
                    'route' => 'admin.dashboard',
                    'label' => 'Dashboard',
                    'icon' => 'fa-chart-line',
                    'active' => request()->routeIs('admin.dashboard'),
                    'badge' => null,
                ],
                [
                    'route' => 'admin.bookings.index',
                    'label' => 'Réservations',
                    'icon' => 'fa-ticket',
                    'active' => request()->routeIs('admin.bookings.*'),
                    'badge' => \App\Models\Booking::count(),
                ],
            ],
        ],
        [
            'title' => 'Catalogue',
            'items' => [
                [
                    'route' => 'admin.events.index',
                    'label' => 'Événements',
                    'icon' => 'fa-calendar-days',
                    'active' => request()->routeIs('admin.events.*'),
                    'badge' => \App\Models\Event::count(),
                ],
                [
                    'route' => 'admin.packages.index',
                    'label' => 'Packages',
                    'icon' => 'fa-suitcase',
                    'active' => request()->routeIs('admin.packages.*'),
                    'badge' => \App\Models\TourPackage::count(),
                ],
                [
                    'route' => 'admin.locations.index',
                    'label' => 'Locations',
                    'icon' => 'fa-car-side',
                    'active' => request()->routeIs('admin.locations.*'),
                    'badge' => \App\Models\Location::count(),
                ],
                [
                    'route' => 'admin.categories.index',
                    'label' => 'Catégories',
                    'icon' => 'fa-folder-open',
                    'active' => request()->routeIs('admin.categories.*'),
                    'badge' => \App\Models\Category::count(),
                ],
            ],
        ],
        [
            'title' => 'Audience',
            'items' => [
                [
                    'route' => 'admin.users.index',
                    'label' => 'Utilisateurs',
                    'icon' => 'fa-users',
                    'active' => request()->routeIs('admin.users.*'),
                    'badge' => \App\Models\User::count(),
                ],
                [
                    'route' => 'admin.members.index',
                    'label' => 'Administrateurs',
                    'icon' => 'fa-user-shield',
                    'active' => request()->routeIs('admin.members.*'),
                    'badge' => \App\Models\Admin::count(),
                ],
                [
                    'route' => 'admin.carousels.index',
                    'label' => 'Carrousels',
                    'icon' => 'fa-images',
                    'active' => request()->routeIs('admin.carousels.*'),
                    'badge' => null,
                ],
            ],
        ],
    ];

    if ($admin->isAccountant() || $admin->isAdmin() || $admin->isSuperAdmin()) {
        $groups[] = [
            'title' => 'Finance',
            'items' => [
                [
                    'route' => 'admin.accountant.reports',
                    'label' => 'Rapports',
                    'icon' => 'fa-chart-pie',
                    'active' => request()->routeIs('admin.accountant.reports'),
                    'badge' => null,
                ],
            ],
        ];
    }
@endphp

<aside id="sidebar"
    class="w-80 flex-shrink-0 flex-col border-r border-white/10 bg-[linear-gradient(180deg,#231631_0%,#2d1948_38%,#38205f_100%)] text-white shadow-2xl transition-all duration-300 ease-in-out md:flex">
    <div class="flex h-24 items-center justify-between border-b border-white/10 px-6">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/12 text-sand-300 backdrop-blur">
                <i class="fas fa-crown text-xl"></i>
            </span>
            <span>
                <span class="block text-[11px] font-semibold uppercase tracking-[0.3em] text-white/45">Admin console</span>
                <span class="mt-1 block text-lg font-semibold tracking-tight text-white">Carré Premium</span>
            </span>
        </a>
        <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-white/65">
            {{ strtoupper(str_replace('_', ' ', $admin->role)) }}
        </span>
    </div>

    <div class="px-4 pt-5">
        <div class="rounded-[1.5rem] border border-white/10 bg-white/8 p-4 backdrop-blur">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-white/45">Raccourcis</p>
            <div class="mt-4 grid gap-3">
                <a href="{{ route('admin.events.create') }}"
                    class="flex items-center justify-between rounded-2xl border border-white/8 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/16">
                    <span class="flex items-center gap-3">
                        <i class="fas fa-plus"></i>
                        Nouvel événement
                    </span>
                    <i class="fas fa-arrow-right text-white/50"></i>
                </a>
                <a href="{{ route('admin.events.import.form') }}"
                    class="flex items-center justify-between rounded-2xl border border-white/8 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/16">
                    <span class="flex items-center gap-3">
                        <i class="fas fa-file-pdf"></i>
                        Import PDF / tableur
                    </span>
                    <i class="fas fa-arrow-right text-white/50"></i>
                </a>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-5">
        @foreach ($groups as $group)
            <section class="{{ !$loop->first ? 'mt-6' : '' }}">
                <p class="mb-3 px-3 text-[11px] font-semibold uppercase tracking-[0.28em] text-white/38">
                    {{ $group['title'] }}
                </p>
                <div class="space-y-1.5">
                    @foreach ($group['items'] as $item)
                        <a href="{{ route($item['route']) }}"
                            class="sidebar-link flex items-center gap-3 rounded-2xl px-4 py-3 {{ $item['active'] ? 'active' : '' }}">
                            <i class="fas {{ $item['icon'] }} w-5 text-center text-base"></i>
                            <span class="font-medium">{{ $item['label'] }}</span>
                            @if (!is_null($item['badge']))
                                <span class="ml-auto rounded-full bg-white/12 px-2.5 py-1 text-[11px] font-semibold text-white/80">
                                    {{ number_format((int) $item['badge'], 0, ',', ' ') }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    </nav>

    <div class="border-t border-white/10 bg-white/5 p-4">
        <a href="{{ route('admin.profile') }}"
            class="group flex items-center gap-3 rounded-[1.4rem] border border-white/8 bg-white/6 p-3 transition hover:bg-white/10">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#ffffff_0%,#f0e7ff_100%)] text-sm font-bold uppercase text-[#4b2386] shadow-lg">
                {{ \Illuminate\Support\Str::substr($admin->name, 0, 1) }}
            </span>
            <span class="min-w-0 flex-1">
                <span class="block truncate text-sm font-semibold text-white">{{ $admin->name }}</span>
                <span class="block truncate text-xs uppercase tracking-[0.18em] text-white/45">{{ str_replace('_', ' ', $admin->role) }}</span>
            </span>
            <i class="fas fa-chevron-right text-white/30 transition group-hover:text-white/60"></i>
        </a>
    </div>
</aside>
