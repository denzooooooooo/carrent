<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Carré Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Définition d'une palette plus cohérente pour le côté 'premium'
                        'primary': {
                            DEFAULT: '#4c1d95', // Un violet plus profond (purple-800)
                            'light': '#a78bfa', // purple-400
                            'dark': '#312e81', // indigo-900
                        },
                        'secondary': '#f59e0b', // L'ambre reste pour le contraste
                    },
                    boxShadow: {
                        '3xl': '0 35px 60px -15px rgba(0, 0, 0, 0.3)',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex antialiased">
        <div
            class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary to-secondary items-center justify-center p-12 shadow-2xl">
            <div class="max-w-md text-center transform transition-all duration-500 hover:scale-[1.02]">
                <div class="mb-10">
                    <i class="fas fa-crown text-8xl text-white drop-shadow-lg"></i>
                </div>
                <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight"> Carré Premium
                </h1>
                <p class="text-white/80 text-xl mb-12 font-light italic">
                    « L'accès à l'excellence en toute sécurité. »
                </p>
                <div class="grid grid-cols-2 gap-4 text-white/90">
                    <div
                        class="text-center p-4 border border-white/20 rounded-lg backdrop-blur-sm bg-white/10 hover:bg-white/20 transition-all">
                        <div class="text-3xl font-extrabold">24/7</div>
                        <div class="text-sm tracking-wider">SUPPORT & SURVEILLANCE</div>
                    </div>
                    <div
                        class="text-center p-4 border border-white/20 rounded-lg backdrop-blur-sm bg-white/10 hover:bg-white/20 transition-all">
                        <div class="text-3xl font-extrabold">100%</div>
                        <div class="text-sm tracking-wider">SÉCURITÉ GARANTIE</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-10 p-8 sm:p-10 bg-white rounded-xl shadow-2xl border border-gray-200">
                <div>
                    <div
                        class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-gradient-to-r from-primary to-secondary shadow-lg">
                        <i class="fas fa-user-shield text-white text-3xl"></i>
                    </div>
                    <h2 class="mt-6 text-center text-4xl font-extrabold text-gray-900 tracking-tight">
                        Authentification Requise
                    </h2>
                    <p class="mt-2 text-center text-md text-gray-500">
                        Veuillez entrer vos identifiants administrateur.
                    </p>
                </div>

                <form class="space-y-6" action="{{ route('admin.login') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div class="relative">
                            <label for="email" class="sr-only">Adresse email</label>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-150 sm:text-sm"
                                placeholder="Adresse email administrateur" value="{{ old('email') }}" />
                        </div>
                        <div class="relative">
                            <label for="password" class="sr-only">Mot de passe</label>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required
                                class="appearance-none block w-full pl-10 pr-10 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-150 sm:text-sm"
                                placeholder="Mot de passe" />
                            <!-- Bouton/Icône pour basculer la visibilité du mot de passe -->
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                                onclick="togglePasswordVisibility()" title="Afficher/Cacher le mot de passe">
                                <i id="password-toggle-icon"
                                    class="fas fa-eye text-gray-400 hover:text-primary transition-colors"
                                    aria-label="Afficher le mot de passe"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox"
                                class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" />
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                                Se souvenir de moi
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-primary hover:text-secondary transition-colors">
                                Mot de passe oublié ?
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="group relative w-full flex items-center justify-center py-3 px-4 border border-transparent text-lg font-semibold rounded-lg text-white bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 shadow-lg hover:shadow-xl transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-primary/50">
                            <i class="fas fa-sign-in-alt mr-2 group-hover:animate-pulse"></i>
                            Se connecter
                        </button>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-lg bg-red-50 p-4 border border-red-200">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 pt-0.5">
                                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-red-800">
                                        Échec de l'authentification
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul role="list" class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="rounded-lg bg-red-50 p-4 border border-red-200">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 pt-0.5">
                                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>

                <div class="text-center pt-4 border-t border-gray-100">
                    <a href="{{ route('home') }}"
                        class="text-sm font-medium text-gray-500 hover:text-primary transition-colors hover:underline">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Retourner au site public
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>