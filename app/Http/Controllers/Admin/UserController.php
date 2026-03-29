<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == '1');
        }

        // Filtre par pays
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Statistiques
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $stats = [
            'total' => $totalUsers,
            'active' => $activeUsers,
            'inactive' => max($totalUsers - $activeUsers, 0),
            'new' => User::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
            'total_points' => User::sum('loyalty_points'),
        ];

        $countries = User::query()
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        return view('admin.users.index', compact('users', 'stats', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'country' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'loyalty_points' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'country' => $request->country,
                'city' => $request->city,
                'address' => $request->address,
                'loyalty_points' => $request->loyalty_points ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            Log::info('Admin user created', [
                'admin_id' => auth('admin')->id(),
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Admin user creation failed', [
                'admin_id' => auth('admin')->id(),
                'email' => $request->input('email'),
                'message' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', 'La création de l’utilisateur a échoué. Vérifie les champs puis réessaie.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['bookings', 'reviews'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'country' => $user->country,
            'city' => $user->city,
            'address' => $user->address,
            'loyalty_points' => $user->loyalty_points,
            'is_active' => (bool) $user->is_active,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'country' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'loyalty_points' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $original = $user->only([
                'first_name',
                'last_name',
                'email',
                'phone',
                'country',
                'city',
                'address',
                'loyalty_points',
                'is_active',
            ]);

            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country' => $request->country,
                'city' => $request->city,
                'address' => $request->address,
                'loyalty_points' => $request->loyalty_points ?? 0,
                'is_active' => $request->has('is_active'),
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            Log::info('Admin user updated', [
                'admin_id' => auth('admin')->id(),
                'user_id' => $user->id,
                'email' => $user->email,
                'changes' => array_keys(array_diff_assoc($data, $original)),
            ]);

            return redirect()->route('admin.users.index')->with('success', 'Utilisateur modifié avec succès.');
        } catch (\Exception $e) {
            Log::error('Admin user update failed', [
                'admin_id' => auth('admin')->id(),
                'user_id' => $user->id,
                'email' => $request->input('email', $user->email),
                'message' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', 'La modification de l’utilisateur a échoué. Vérifie les données puis réessaie.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $email = $user->email;
            $user->delete();

            Log::warning('Admin user deleted', [
                'admin_id' => auth('admin')->id(),
                'user_id' => $id,
                'email' => $email,
            ]);

            return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Admin user deletion failed', [
                'admin_id' => auth('admin')->id(),
                'user_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', 'La suppression de l’utilisateur a échoué.');
        }
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update(['is_active' => !$user->is_active]);

            Log::info('Admin user status toggled', [
                'admin_id' => auth('admin')->id(),
                'user_id' => $user->id,
                'is_active' => (bool) $user->is_active,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès.',
                'is_active' => $user->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Admin user status toggle failed', [
                'admin_id' => auth('admin')->id(),
                'user_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'La mise à jour du statut a échoué.'
            ], 500);
        }
    }
}
