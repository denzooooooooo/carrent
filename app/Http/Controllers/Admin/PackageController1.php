<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\TourPackage;
use App\Models\Category;

class PackageController1 extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = TourPackage::with('category')
            ->latest()
            ->paginate(12);

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('type', 'package')->pluck('name_fr', 'id');
        $packageTypes = [
            'helicopter' => 'Hélicoptère',
            'private_jet' => 'Jet Privé',
            'cruise' => 'Croisière',
            'safari' => 'Safari',
            'city_tour' => 'Tour de Ville',
            'adventure' => 'Aventure',
            'luxury' => 'Luxe',
        ];

        // On passe un package vide à la vue pour utiliser le même formulaire
        $package = new TourPackage(); 
        
        return view('admin.packages.create', compact('categories', 'packageTypes', 'package'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation simplifiée
        $validatedData = $request->validate([
            'title_fr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255', // J'ajoute title_en comme requis ici
            'category_id' => 'required|exists:categories,id',
            'destination' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description_fr' => 'nullable|string',
            'package_type' => 'required|in:helicopter,private_jet,cruise,safari,city_tour,adventure,luxury',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Préparation des données JSON et du slug
        $validatedData['slug'] = Str::slug($request->title_fr);
        
        // Décodage sûr des champs JSON
        $validatedData['included_services_fr'] = json_decode($request->included_services_fr_json, true) ?? [];
        $validatedData['excluded_services_fr'] = json_decode($request->excluded_services_fr_json, true) ?? [];
        $validatedData['itinerary_fr'] = json_decode($request->itinerary_fr_json, true) ?? [];
        
        $package = TourPackage::create($validatedData);

        // 2. Gestion des Médias
        if ($request->hasFile('avatar')) {
            $package->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $package->addMedia($file)->toMediaCollection('gallery');
            }
        }
        
        return redirect()->route('admin.packages.show', $package)->with('success', 'Le package a été créé avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TourPackage $package)
    {
        $package->load('category');
        return view('admin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TourPackage $package)
    {
        $categories = Category::where('type', 'package')->pluck('name_fr', 'id');
        $packageTypes = [
            'helicopter' => 'Hélicoptère',
            'private_jet' => 'Jet Privé',
            'cruise' => 'Croisière',
            'safari' => 'Safari',
            'city_tour' => 'Tour de Ville',
            'adventure' => 'Aventure',
            'luxury' => 'Luxe',
        ];

        return view('admin.packages.edit', compact('package', 'categories', 'packageTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TourPackage $package)
    {
        // 1. Validation simplifiée (le slug est retiré de la validation ici car il est régénéré)
        $validatedData = $request->validate([
            'title_fr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'destination' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|lt:price', // S'assurer que le prix réduit est inférieur au prix de base
            'max_participants' => 'required|integer|min:1',
            'description_fr' => 'nullable|string',
            'package_type' => 'required|in:helicopter,private_jet,cruise,safari,city_tour,adventure,luxury',
            'avatar' => 'nullable|image|max:2048', // L'avatar est optionnel à la mise à jour
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);
        
        // Préparation des données JSON et des booléens
        $validatedData['slug'] = Str::slug($request->title_fr);
        $validatedData['included_services_fr'] = json_decode($request->included_services_fr_json, true) ?? [];
        $validatedData['excluded_services_fr'] = json_decode($request->excluded_services_fr_json, true) ?? [];
        $validatedData['itinerary_fr'] = json_decode($request->itinerary_fr_json, true) ?? [];
        
        $validatedData['is_active'] = $request->has('is_active');
        $validatedData['is_featured'] = $request->has('is_featured');
        
        $package->update($validatedData);

        // 2. Gestion des Médias (Avatar principal)
        if ($request->hasFile('avatar')) {
            $package->clearMediaCollection('avatar');
            $package->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }
        
        // 3. Gestion de la Galerie (ajout seulement, la suppression nécessite une logique supplémentaire)
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $package->addMedia($file)->toMediaCollection('gallery');
            }
        }

        return redirect()->route('admin.packages.show', $package)->with('success', 'Le package touristique a été mis à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TourPackage $package)
    {
        try {
            $package->delete(); 
            return redirect()->route('admin.packages.index')->with('success', 'Le package touristique a été supprimé.');
        } catch (\Exception $e) {
            return redirect()->route('admin.packages.index')->with('error', 'Erreur lors de la suppression du package: ' . $e->getMessage());
        }
    }
}
