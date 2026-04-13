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
    public function index()
    {
        $locations = Location::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.locations.index', compact('locations'));
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
