<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    /**
     * Affiche la liste des membres.
     */
    public function index()
    {
        // Récupère tous les membres (admins) triés par date de création.
        $members = Admin::latest()->paginate(10);
        
        // La vue sera 'admin.members.index'
        return view('admin.membres.index', compact('members'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau membre.
     */
    public function create()
    {
        // La vue sera 'admin.members.create_edit'
        return view('admin.membres.create');
    }

    /**
     * Enregistre un nouveau membre.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email', // Doit être unique dans la table 'admins'
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['super_admin', 'admin', 'moderator'])],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        $member = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Gestion de l'Avatar avec Spatie Media Library
        if ($request->hasFile('avatar')) {
            $member->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }
        
        return redirect()->route('admin.members.index')->with('success', 'Le membre ' . $member->name . ' a été créé avec succès.');
    }

    /**
     * Affiche le formulaire de modification pour un membre.
     */
    public function edit(Admin $member)
    {
        // La vue sera 'admin.members.create_edit'
        return view('admin.membres.create', compact('member'));
    }

    /**
     * Met à jour les informations d'un membre.
     */
    public function update(Request $request, Admin $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // L'email doit être unique, sauf pour le membre actuel
            'email' => ['required', 'email', 'max:255', Rule::unique('admins')->ignore($member->id)], 
            'password' => 'nullable|string|min:8|confirmed', // Le mot de passe est optionnel à la modification
            'role' => ['required', Rule::in(['super_admin', 'admin', 'moderator'])],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = $request->only('name', 'email', 'role', 'phone');
        $data['is_active'] = $request->boolean('is_active');

        // Mise à jour du mot de passe si fourni
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Gestion de l'Avatar avec Spatie Media Library
        if ($request->hasFile('avatar')) {
            $member->clearMediaCollection('avatar');
            $member->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        $member->update($data);

        return redirect()->route('admin.members.index')->with('success', 'Le membre ' . $member->name . ' a été mis à jour avec succès.');
    }

    /**
     * Supprime un membre.
     */
    public function destroy(Admin $member)
    {
        // Empêche la suppression de l'utilisateur actuellement connecté par sécurité.
        if (Auth::guard('admin')->id() === $member->id) {
             return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $member_name = $member->name;
        
        // La suppression du modèle Admin, grâce au trait HasMedia, supprime aussi les fichiers associés.
        $member->delete();

        return redirect()->route('admin.members.index')->with('success', 'Le membre ' . $member_name . ' a été supprimé avec succès.');
    }
}
