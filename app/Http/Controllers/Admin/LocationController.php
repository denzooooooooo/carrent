<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Location::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($builder) use ($search) {
                $builder->where('name_fr', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $locations = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total' => Location::count(),
            'active' => Location::where('is_active', true)->count(),
            'inactive' => Location::where('is_active', false)->count(),
            'average_daily_rate' => (float) Location::avg('price_per_day'),
        ];

        $categories = Location::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $types = Location::query()
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        return view('admin.locations.index', compact('locations', 'stats', 'categories', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_fr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'category' => 'required|in:terrestre,aérien,nautique',
            'type' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'features' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();

        // Gestion de l'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('locations', 'public');
            $data['image'] = $imagePath;
        }

        // Gestion des caractéristiques
        if ($request->features) {
            $data['features'] = array_filter($request->features);
        }

        Location::create($data);

        Session::flash('success', 'Location créée avec succès.');

        return redirect()->route('admin.locations.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        return view('admin.locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name_fr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'category' => 'required|in:terrestre,aérien,nautique',
            'type' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'features' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();

        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($location->image && Storage::disk('public')->exists($location->image)) {
                Storage::disk('public')->delete($location->image);
            }

            $imagePath = $request->file('image')->store('locations', 'public');
            $data['image'] = $imagePath;
        }

        // Gestion des caractéristiques
        if ($request->features) {
            $data['features'] = array_filter($request->features);
        }

        $location->update($data);

        Session::flash('success', 'Location mise à jour avec succès.');

        return redirect()->route('admin.locations.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        // Supprimer l'image associée
        if ($location->image && Storage::disk('public')->exists($location->image)) {
            Storage::disk('public')->delete($location->image);
        }

        $location->delete();

        Session::flash('success', 'Location supprimée avec succès.');

        return redirect()->route('admin.locations.index');
    }
}
