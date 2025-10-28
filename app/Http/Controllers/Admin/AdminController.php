<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Admin;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Voir le profil de l'administrateur connecté
     */
    public function profile()
    {
        // Récupère l'administrateur connecté via le guard 'admin'
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    /**
     * Modifier les informations de son profil
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            // L'email doit être unique, sauf pour l'utilisateur actuel (ignoré par l'ID)
            'email' => ['required', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'phone' => 'nullable|string|max:20',
            // L'avatar est facultatif et doit être une image de 2Mo maximum
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only('name', 'email', 'phone');

        // Gestion de l'Avatar avec Spatie Media Library
        if ($request->hasFile('avatar')) {
            // Supprime tous les médias existants dans la collection 'avatar'
            $admin->clearMediaCollection('avatar');

            // Ajoute le nouveau média. 'avatar' est le nom du champ et de la collection.
            $admin->addMediaFromRequest('avatar')
                ->toMediaCollection('avatar');

            // NOTE : Le champ 'avatar' dans la BDD n'est plus mis à jour ici car
            // Spatie gère l'association via la table 'media'.
            // On s'assure de ne pas écraser les données si d'autres champs 'avatar' sont dans $data.
            if (isset($data['avatar'])) {
                unset($data['avatar']);
            }
        }

        $admin->update($data);

        // Redirection avec un message de succès.
        return redirect()->route('admin.profile')->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    /**
     * Afficher le formulaire de modification du mot de passe
     */
    public function passwordForm()
    {
        return view('admin.password');
    }

    /**
     * Modifier son mot de passe
     */
    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // Validation du mot de passe
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Vérifie si le mot de passe actuel est correct
        if (!Hash::check($request->current_password, $admin->password)) {
            // Retourne avec une erreur spécifique pour le champ current_password
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])->withInput();
        }

        // Met à jour le mot de passe
        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        // Redirection avec un message de succès.
        return redirect()->route('admin.profile')->with('success', 'Votre mot de passe a été mis à jour avec succès. Vous pouvez maintenant utiliser votre nouveau mot de passe.');
    }
}
