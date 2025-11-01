<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('pages.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            Session::flash('success', 'Connexion réussie ! Bienvenue sur Carré Premium.');

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->withInput();
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('pages.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'country' => 'Côte d\'Ivoire',
            'preferred_language' => 'fr',
            'preferred_currency' => 'XOF',
            'is_active' => true,
        ]);

        Auth::login($user);

        Session::flash('success', 'Inscription réussie ! Bienvenue sur Carré Premium.');

        return redirect()->route('home');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Session::flash('success', 'Déconnexion réussie.');

        return redirect()->route('home');
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('pages.users.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'nationality' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $data = $request->only([
            'first_name',
            'last_name',
            'phone',
            'date_of_birth',
            'gender',
            'nationality',
            'passport_number',
            'address',
            'city',
            'country',
            'postal_code'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path('storage/' . $user->avatar))) {
                unlink(public_path('storage/' . $user->avatar));
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        Session::flash('success', 'Profil mis à jour avec succès.');

        return back();
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        Session::flash('success', 'Mot de passe changé avec succès.');

        return back();
    }

    /**
     * Show user bookings
     */
    public function bookings()
    {
        $user = Auth::user();

        // Get user bookings - assuming there's a Booking model with user relationship
        // This will need to be adjusted based on your actual Booking model structure
        $bookings = collect([]); // Placeholder - replace with actual query when Booking model exists

        return view('pages.users.bookings', compact('bookings'));
    }

    /**
     * Redirect to Google OAuth page
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    /* public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists by Google ID
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // Check if user exists by email
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Link Google account to existing user
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'provider' => 'google',
                        'avatar_url' => $googleUser->getAvatar(),
                        'email_verified_at' => now(),
                    ]);
                } else {
                    // Create new user
                    $nameParts = $this->splitName($googleUser->getName());

                    $user = User::create([
                        'google_id' => $googleUser->getId(),
                        'provider' => 'google',
                        'first_name' => $nameParts['first_name'],
                        'last_name' => $nameParts['last_name'],
                        'email' => $googleUser->getEmail(),
                        'email_verified_at' => now(),
                        'avatar_url' => $googleUser->getAvatar(),
                        //'password' => Hash::make(Str::random(24)), // Random password
                        'country' => 'Côte d\'Ivoire',
                        'preferred_language' => 'fr',
                        'preferred_currency' => 'XOF',
                        'is_active' => true,
                    ]);
                }
            } else {
                // Update avatar if changed
                if ($user->avatar_url !== $googleUser->getAvatar()) {
                    $user->update([
                        'avatar_url' => $googleUser->getAvatar(),
                    ]);
                }
            }

            // Log the user in
            Auth::login($user, true);

            Session::flash('success', 'Connexion réussie via Google ! Bienvenue sur Carré Premium.');

            return redirect()->intended(route('home'));

        } catch (\Exception $e) {
            Session::flash('error', 'Erreur lors de la connexion avec Google. Veuillez réessayer.');
            return redirect()->route('login');
        }
    } */
    public function handleGoogleCallback()
    {
        try {
            // 1. Tente de récupérer l'utilisateur Socialite.
            // C'est ici que l'erreur 400 (Client ID/Secret/URI incorrect) apparaît souvent.
            $googleUser = Socialite::driver('google')->user();

            // 2. Vérifie si l'utilisateur existe déjà par Google ID
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // 3. Vérifie si l'utilisateur existe déjà par email
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // L'utilisateur existe, lie le compte Google
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'provider' => 'google',
                        'avatar_url' => $googleUser->getAvatar(),
                        'email_verified_at' => now(),
                    ]);
                } else {
                    // 4. Crée un nouvel utilisateur
                    $nameParts = $this->splitName($googleUser->getName());

                    // *** ATTENTION ICI : ***
                    // Vos règles de validation (phone, password) nécessitent d'être gérées.
                    // Pour le Socialite, on peut générer un mot de passe et un faux numéro de téléphone
                    // si les champs sont obligatoires dans la base de données.

                    // N'oubliez pas d'importer Str si vous utilisez Str::random(24)
                    // use Illuminate\Support\Str; 

                    $user = User::create([
                        'google_id' => $googleUser->getId(),
                        'provider' => 'google',
                        'first_name' => $nameParts['first_name'],
                        'last_name' => $nameParts['last_name'],
                        'email' => $googleUser->getEmail(),
                        'email_verified_at' => now(),
                        'avatar_url' => $googleUser->getAvatar(),
                        // Si le champ 'password' est NULLABLE, vous pouvez l'omettre.
                        // S'il est requis, il faut le fournir (même si l'utilisateur ne l'utilisera pas)
                        //'password' => Hash::make(Str::random(24)),    
                        // Si 'phone' est REQUIS et UNIQUE, générez une valeur unique ou rendez-le NULLABLE
                        //'phone' => '00000000000' . $googleUser->getId(), // Exemple : Faux numéro unique
                        //'country' => 'Côte d\'Ivoire',
                        //'preferred_language' => 'fr',
                        //'preferred_currency' => 'XOF',
                        'is_active' => true,
                    ]);
                }
            } else {
                // Utilisateur Socialite existant
                if ($user->avatar_url !== $googleUser->getAvatar()) {
                    $user->update([
                        'avatar_url' => $googleUser->getAvatar(),
                    ]);
                }
            }

            // 5. Connecte l'utilisateur
            Auth::login($user, true);

            Session::flash('success', 'Connexion réussie via Google ! Bienvenue sur Carré Premium.');

            return redirect()->intended(route('home'));

        } catch (\Exception $e) {
            // *** BLOC DE DÉBOGAGE CRUCIAL ***
            // Cela affichera l'erreur exacte renvoyée par Socialite ou Google
            if (config('app.env') === 'local' || config('app.debug') === true) {
                // Affiche le message d'erreur et arrête l'exécution
                dd("Socialite Error: " . $e->getMessage() . " | Code: " . $e->getCode() . " | File: " . $e->getFile() . " | Line: " . $e->getLine());
            }

            Session::flash('error', 'Erreur lors de la connexion avec Google. Veuillez réessayer.');
            return redirect()->route('login');
        }
    }

    /**
     * Split full name into first and last name
     */
    private function splitName($fullName)
    {
        $parts = explode(' ', $fullName, 2);

        return [
            'first_name' => $parts[0] ?? '',
            'last_name' => $parts[1] ?? $parts[0] ?? '',
        ];
    }

    /**
     * Redirect to Facebook OAuth page
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')
            ->scopes(['email', 'public_profile'])
            ->redirect();
    }

    /**
     * Handle Facebook OAuth callback
     */
    public function handleFacebookCallback()
    {
        try {
            // 1. Récupère l'utilisateur Facebook via Socialite
            $facebookUser = Socialite::driver('facebook')->user();

            // 2. Vérifie si l'utilisateur existe déjà par Facebook ID
            $user = User::where('facebook_id', $facebookUser->getId())->first();

            if (!$user) {
                // 3. Vérifie si l'utilisateur existe déjà par email
                $user = User::where('email', $facebookUser->getEmail())->first();

                if ($user) {
                    // L'utilisateur existe, lie le compte Facebook
                    $user->update([
                        'facebook_id' => $facebookUser->getId(),
                        'provider' => 'facebook',
                        'avatar_url' => $facebookUser->getAvatar(),
                        'email_verified_at' => now(),
                    ]);
                } else {
                    // 4. Crée un nouvel utilisateur
                    $nameParts = $this->splitName($facebookUser->getName());

                    // Génère un numéro de téléphone unique si requis
                    $uniquePhone = 'FB' . time() . rand(1000, 9999);

                    $user = User::create([
                        'facebook_id' => $facebookUser->getId(),
                        'provider' => 'facebook',
                        'first_name' => $nameParts['first_name'],
                        'last_name' => $nameParts['last_name'],
                        'email' => $facebookUser->getEmail(),
                        'email_verified_at' => now(),
                        'avatar_url' => $facebookUser->getAvatar(),
                        // Si 'phone' est requis et unique, utilisez un placeholder
                        //'phone' => $uniquePhone,
                        // Mot de passe nullable ou généré aléatoirement
                        // 'password' => Hash::make(Str::random(24)),
                        //'country' => 'Côte d\'Ivoire',
                        //'preferred_language' => 'fr',
                        //'preferred_currency' => 'XOF',
                        //'is_active' => true,
                    ]);
                }
            } else {
                // Utilisateur Facebook existant - Met à jour l'avatar si nécessaire
                if ($user->avatar_url !== $facebookUser->getAvatar()) {
                    $user->update([
                        'avatar_url' => $facebookUser->getAvatar(),
                    ]);
                }
            }

            // 5. Connecte l'utilisateur
            Auth::login($user, true);

            Session::flash('success', 'Connexion réussie via Facebook ! Bienvenue sur Carré Premium.');

            return redirect()->intended(route('home'));

        } catch (\Exception $e) {
            // Débogage en environnement local
            if (config('app.debug')) {
                dd([
                    'error' => 'Facebook OAuth Error',
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            Session::flash('error', 'Erreur lors de la connexion avec Facebook. Veuillez réessayer.');
            return redirect()->route('login');
        }
    }
}
