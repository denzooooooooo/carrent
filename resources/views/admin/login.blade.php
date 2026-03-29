<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion Admin | Carré Premium</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(109, 40, 217, 0.22), transparent 28%),
                radial-gradient(circle at bottom right, rgba(200, 138, 42, 0.2), transparent 28%),
                linear-gradient(160deg, #f9f5ef 0%, #f4efff 48%, #fff 100%);
            min-height: 100vh;
        }

        .admin-auth-shell::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.48), transparent 35%),
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.4), transparent 24%);
            pointer-events: none;
        }

        .admin-auth-glow {
            box-shadow: 0 32px 90px rgba(76, 29, 149, 0.18);
        }

        .admin-display {
            font-family: 'Sora', sans-serif;
        }
    </style>
</head>

<body class="text-slate-900 antialiased">
    <div class="relative min-h-screen overflow-hidden">
        <div class="admin-auth-shell relative mx-auto flex min-h-screen max-w-7xl flex-col px-4 py-6 sm:px-6 lg:flex-row lg:items-stretch lg:gap-6 lg:px-8">
            <section class="relative hidden overflow-hidden rounded-[2rem] bg-[linear-gradient(150deg,#24123b_0%,#4c1d95_48%,#7c3aed_100%)] p-10 text-white shadow-[0_30px_80px_rgba(24,12,44,0.18)] lg:flex lg:w-[46%] lg:flex-col lg:justify-between">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.18),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(200,138,42,0.2),transparent_35%)]"></div>
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-3 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold tracking-[0.22em] uppercase text-white/75">
                        <i class="fas fa-shield-halved text-[#dbc19a]"></i>
                        Console sécurisée
                    </div>
                    <div class="mt-8 max-w-xl">
                        <p class="admin-display text-5xl font-semibold leading-tight">
                            Pilote l’activité premium avec une interface plus claire et plus sûre.
                        </p>
                        <p class="mt-6 text-lg leading-8 text-white/78">
                            Réservations, événements, paiements et contenus sont centralisés ici. Cette console est réservée aux administrateurs autorisés.
                        </p>
                    </div>
                </div>

                <div class="relative z-10 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-[1.5rem] border border-white/10 bg-white/10 p-5 backdrop-blur">
                        <p class="text-xs uppercase tracking-[0.24em] text-white/55">Accès</p>
                        <p class="admin-display mt-3 text-3xl font-semibold">24/7</p>
                        <p class="mt-2 text-sm text-white/72">Interface disponible pour l’équipe opérationnelle.</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-white/10 bg-white/10 p-5 backdrop-blur">
                        <p class="text-xs uppercase tracking-[0.24em] text-white/55">Protection</p>
                        <p class="admin-display mt-3 text-3xl font-semibold">Token</p>
                        <p class="mt-2 text-sm text-white/72">Session régénérée et accès cloisonné par rôle.</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-white/10 bg-white/10 p-5 backdrop-blur">
                        <p class="text-xs uppercase tracking-[0.24em] text-white/55">Support</p>
                        <p class="admin-display mt-3 text-3xl font-semibold">Ops</p>
                        <p class="mt-2 text-sm text-white/72">Gestion rapide des demandes et des réservations clients.</p>
                    </div>
                </div>
            </section>

            <section class="flex flex-1 items-center justify-center">
                <div class="admin-auth-glow w-full max-w-xl rounded-[2rem] border border-white/70 bg-white/90 p-6 backdrop-blur-xl sm:p-10">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#5b21b6,#c88a2a)] text-white shadow-lg">
                                <i class="fas fa-user-shield text-2xl"></i>
                            </div>
                            <p class="mt-6 text-sm font-semibold uppercase tracking-[0.28em] text-[#5b21b6]">Connexion admin</p>
                            <h1 class="admin-display mt-3 text-3xl font-semibold tracking-tight text-slate-950">
                                Accéder au cockpit Carré Premium
                            </h1>
                            <p class="mt-3 text-sm leading-7 text-slate-600 sm:text-base">
                                Connecte-toi avec ton compte administrateur pour gérer les catalogues, paiements et opérations.
                            </p>
                        </div>
                        <a href="{{ route('home') }}"
                            class="hidden rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-[#ece3ff] hover:text-[#4c1d95] sm:inline-flex sm:items-center sm:gap-2">
                            <i class="fas fa-arrow-left"></i>
                            Site public
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="mt-8 rounded-[1.5rem] border border-red-200 bg-red-50/90 px-5 py-4 text-sm text-red-700">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-circle-exclamation mt-0.5 text-base"></i>
                                <div>
                                    <p class="font-semibold text-red-800">Connexion refusée</p>
                                    <ul class="mt-2 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mt-8 rounded-[1.5rem] border border-red-200 bg-red-50/90 px-5 py-4 text-sm font-medium text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form class="mt-8 space-y-6" action="{{ route('admin.login') }}" method="POST">
                        @csrf

                        <div class="grid gap-5">
                            <label class="block">
                                <span class="mb-2 block text-sm font-semibold text-slate-700">Adresse email</span>
                                <span class="relative block">
                                    <i class="fas fa-envelope pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input id="email" name="email" type="email" autocomplete="email" required
                                        value="{{ old('email') }}"
                                        class="w-full rounded-2xl border border-slate-200 bg-white px-12 py-4 text-base text-slate-900 outline-none transition focus:border-[#cdb7f4] focus:ring-4 focus:ring-[#ece3ff]"
                                        placeholder="admin@carrepremium.com">
                                </span>
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-sm font-semibold text-slate-700">Mot de passe</span>
                                <span class="relative block">
                                    <i class="fas fa-lock pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input id="password" name="password" type="password" autocomplete="current-password" required
                                        class="w-full rounded-2xl border border-slate-200 bg-white px-12 py-4 pr-14 text-base text-slate-900 outline-none transition focus:border-[#cdb7f4] focus:ring-4 focus:ring-[#ece3ff]"
                                        placeholder="Votre mot de passe">
                                    <button type="button" id="toggle-password"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-[#5b21b6]"
                                        aria-label="Afficher ou masquer le mot de passe">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </span>
                            </label>
                        </div>

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                                <input id="remember_me" name="remember" type="checkbox"
                                    class="h-4 w-4 rounded border-slate-300 text-[#5b21b6] focus:ring-[#6d28d9]">
                                Rester connecté
                            </label>
                            <span class="text-sm text-slate-500">
                                Accès réservé aux profils autorisés.
                            </span>
                        </div>

                        <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-3 rounded-2xl bg-[linear-gradient(135deg,#5b21b6,#7c3aed)] px-6 py-4 text-base font-semibold text-white shadow-[0_18px_45px_rgba(91,33,182,0.18)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_55px_rgba(91,33,182,0.22)]">
                            <i class="fas fa-arrow-right-to-bracket"></i>
                            Se connecter
                        </button>
                    </form>

                    <div class="mt-8 flex items-center justify-between gap-4 rounded-[1.5rem] border border-slate-200 bg-slate-50/80 px-5 py-4 text-sm text-slate-600">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#f4efff] text-[#5b21b6]">
                                <i class="fas fa-headset"></i>
                            </span>
                            <div>
                                <p class="font-semibold text-slate-800">Besoin d’un accès ou d’un support ?</p>
                                <p>Contacte le super administrateur ou l’équipe ops.</p>
                            </div>
                        </div>
                        <a href="{{ route('home') }}"
                            class="inline-flex shrink-0 items-center gap-2 rounded-full border border-slate-200 px-4 py-2 font-semibold text-slate-600 transition hover:border-[#ece3ff] hover:text-[#4c1d95] sm:hidden">
                            <i class="fas fa-arrow-left"></i>
                            Site
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        const passwordField = document.getElementById('password');
        const toggleButton = document.getElementById('toggle-password');

        toggleButton?.addEventListener('click', () => {
            const isPassword = passwordField.type === 'password';
            passwordField.type = isPassword ? 'text' : 'password';
            toggleButton.innerHTML = `<i class="fas fa-${isPassword ? 'eye-slash' : 'eye'}"></i>`;
        });
    </script>
</body>

</html>
