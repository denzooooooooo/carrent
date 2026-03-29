@php
    $accountUser = $accountUser ?? auth()->user();
    $activePage = $activePage ?? 'profile';
    $accountName = trim(($accountUser?->first_name ?? '') . ' ' . ($accountUser?->last_name ?? ''));
    $accountName = $accountName !== '' ? $accountName : ($accountUser?->email ?? 'Carré Premium');
    $accountInitials = collect(explode(' ', $accountName))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(mb_substr($part, 0, 1)))
        ->implode('');
    $accountInitials = $accountInitials !== '' ? $accountInitials : 'CP';
@endphp

<aside class="space-y-4">
    <div class="cp-panel rounded-[2rem] p-5 sm:p-6">
        <div class="flex items-center gap-4">
            @if($accountUser?->avatar_url)
                <img src="{{ $accountUser->avatar_url }}" alt="{{ $accountName }}" class="h-16 w-16 rounded-[1.35rem] object-cover shadow-lg">
            @else
                <span class="inline-flex h-16 w-16 items-center justify-center rounded-[1.35rem] bg-[color:var(--cp-plum-900)] text-lg font-black text-white shadow-lg">
                    {{ $accountInitials }}
                </span>
            @endif

            <div class="min-w-0">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">Espace client</p>
                <p class="mt-1 truncate text-xl font-black text-[color:var(--cp-plum-950)]">{{ $accountName }}</p>
                <p class="mt-1 truncate text-sm text-[color:var(--cp-ink-soft)]">{{ $accountUser?->email }}</p>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-3">
            <div class="rounded-[1.35rem] bg-[#faf6ff] px-4 py-3">
                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Réservations</p>
                <p class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $accountUser?->bookings_count ?? 0 }}</p>
            </div>
            <div class="rounded-[1.35rem] bg-[#fff6e8] px-4 py-3">
                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[#9f6510]">Confirmées</p>
                <p class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $accountUser?->confirmed_bookings ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="cp-panel rounded-[2rem] p-3">
        <nav class="space-y-1.5">
            <a href="{{ route('profile') }}" @class([
                'flex items-center gap-3 rounded-[1.2rem] px-4 py-3 text-sm font-bold transition',
                'bg-[#f3eaff] text-[color:var(--cp-plum-900)] shadow-sm' => $activePage === 'profile',
                'text-[color:var(--cp-ink-soft)] hover:bg-[#faf6ff]' => $activePage !== 'profile',
            ])>
                <i class="fa-regular fa-user text-sm"></i>
                <span>Mon profil</span>
            </a>

            <a href="{{ route('bookings') }}" @class([
                'flex items-center gap-3 rounded-[1.2rem] px-4 py-3 text-sm font-bold transition',
                'bg-[#f3eaff] text-[color:var(--cp-plum-900)] shadow-sm' => $activePage === 'bookings',
                'text-[color:var(--cp-ink-soft)] hover:bg-[#faf6ff]' => $activePage !== 'bookings',
            ])>
                <i class="fa-regular fa-calendar-check text-sm"></i>
                <span>Mes réservations</span>
            </a>

            <a href="{{ route('events') }}" class="flex items-center gap-3 rounded-[1.2rem] px-4 py-3 text-sm font-bold text-[color:var(--cp-ink-soft)] transition hover:bg-[#faf6ff]">
                <i class="fa-solid fa-ticket text-sm"></i>
                <span>Voir les événements</span>
            </a>
        </nav>
    </div>
</aside>
