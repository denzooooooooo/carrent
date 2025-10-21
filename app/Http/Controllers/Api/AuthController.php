<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Connexion Admin
     */
    public function loginAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $identifier = $request->email;

        // Recherche par email ou téléphone
        $admin = Admin::where(function ($query) use ($identifier) {
            $query->where('email', $identifier)
                ->orWhere('phone', $identifier);
        })->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiant introuvable',
                'error' => ['email' => 'Identifiant introuvable'],
            ], 422);
        }

        // Vérifier si le compte est actif
        /* if (!$admin->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Compte administrateur désactivé. Veuillez contacter le Super Administrateur.',
            ], 403);
        } */

        // Vérifier le mot de passe
        if (!Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mot de passe incorrect',
                'error' => ['password' => 'Mot de passe incorrect'],
            ], 422);
        }

        // Mettre à jour la dernière connexion
        $admin->update(['last_login' => now()]);

        // Créer le token Sanctum avec le rôle comme permission
        $token = $admin->createToken('admin_token', ['role:' . $admin->role])->plainTextToken;

        // ✅ Vérifier le rôle et retourner selon le cas
        if ($admin->role === 'super_admin') {
            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie du super-admin',
                'token' => $token,
                'role' => $admin->role,
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'phone' => $admin->phone,
                    'last_login' => $admin->last_login,
                ],
            ], 201);
        }

        if ($admin->role === 'admin') {
            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie de l\'admin',
                'token' => $token,
                'role' => $admin->role,
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'phone' => $admin->phone,
                    'last_login' => $admin->last_login,
                ],
            ], 202);
        }

        if ($admin->role === 'moderator') {
            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie du modérateur',
                'token' => $token,
                'role' => $admin->role,
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'phone' => $admin->phone,
                    'last_login' => $admin->last_login,
                ],
            ], 203);
        }
        /* // 🔢 Déterminer le code HTTP selon le rôle
        $httpCode = match ($admin->role) {
            'super_admin' => 201,
            'admin'       => 202,
            'moderator'   => 203,
            default       => 200,
        };

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'role' => $admin->role,
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => $admin->phone,
                'avatar' => $admin->avatar,
                'is_active' => $admin->is_active, 
                'last_login' => $admin->last_login,
            ],
            'token' => $token,
        ], $httpCode); */
    }

    /**
     * Connexion Utilisateur 
     */
    public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $identifier = $request->email;

        // Recherche par email ou téléphone
        $user = User::where(function ($query) use ($identifier) {
            $query->where('email', $identifier)
                ->orWhere('phone', $identifier);
        })->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiant introuvable',
                'error' => ['email' => 'Identifiant introuvable'],
            ], 422);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Mot de passe incorrect',
                'error' => ['password' => 'Mot de passe incorrect'],
            ], 422);
        }

        // Vérification email seulement pour les comptes non sociaux
        /* if (!$user->email_verified_at && empty($user->provider)) {
            return response()->json([
                'message' => 'Veuillez vérifier votre email avant de vous connecter',
            ], 403);
        } */

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => $user->only(['id', 'last_name', 'first_name', 'email', 'phone']),
        ], 200);
    }

    /**
     * Deconnexion Admin ou User 
     * 
     */
    public function logout(Request $request)
    {
        //$user = Auth::user();

        // Vérifie qu’un utilisateur est bien connecté
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun utilisateur connecté.',
            ], 401);
        }

        // Supprimer tous les tokens actifs de ce compte
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie.',
        ], 200);

    }

    /**
     * Inscription de l'utilisateur 
     */

    /**
     * Inscription de l'utilisateur
     */
    public function register(Request $request)
    {
        // 1️⃣ Validation des données
        $validator = \Validator::make($request->all(), [
            'first_name' => 'required|string|max_length:255',
            'last_name' => 'required|string|max_length:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        // 2️⃣ Création du compte utilisateur
        $user = \App\Models\User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'is_active' => true,
        ]);

        // 3️⃣ Génération du token API (si tu utilises Laravel Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4️⃣ Réponse
        return response()->json([
            'status' => true,
            'message' => 'Inscription réussie',
            'user' => $user,
            'token' => $token,
        ], 201);
    }



}
