<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use App\Models\Category;
use App\Models\PackageInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TourPackage::with(['category', 'inventory']);

        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title_fr', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filtre par type de package
        if ($request->filled('package_type')) {
            $query->where('package_type', $request->package_type);
        }

        // Filtre par statut
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Filtre par featured
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $packages = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('admin.packages.index', compact('packages', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        
        $packageTypes = [
            'helicopter' => 'Hélicoptère',
            'private_jet' => 'Jet Privé',
            'cruise' => 'Croisière',
            'safari' => 'Safari',
            'city_tour' => 'Visite de Ville',
            'adventure' => 'Aventure',
            'luxury' => 'Luxe'
        ];

        return view('admin.packages.create', compact('categories', 'packageTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title_fr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'package_type' => 'required|in:helicopter,private_jet,cruise,safari,city_tour,adventure,luxury',
            'destination' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'duration_text_fr' => 'nullable|string|max:100',
            'duration_text_en' => 'nullable|string|max:100',
            'departure_city' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'max_participants' => 'required|integer|min:1',
            'min_participants' => 'required|integer|min:1|lte:max_participants',
            'included_services_fr' => 'nullable|array',
            'included_services_en' => 'nullable|array',
            'excluded_services_fr' => 'nullable|array',
            'excluded_services_en' => 'nullable|array',
            'itinerary_fr' => 'nullable|array',
            'itinerary_en' => 'nullable|array',
            'video_url' => 'nullable|url',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title_fr' => 'nullable|string|max:255',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_description_fr' => 'nullable|string',
            'meta_description_en' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Générer le slug
            $validated['slug'] = Str::slug($validated['title_fr']);
            
            // S'assurer que le slug est unique
            $originalSlug = $validated['slug'];
            $count = 1;
            while (TourPackage::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count;
                $count++;
            }

            $package = TourPackage::create($validated);

            // Gestion de l'image principale (avatar)
            if ($request->hasFile('avatar')) {
                $package->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatar');
            }

            // Gestion de la galerie
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $image) {
                    $package->addMedia($image)
                        ->toMediaCollection('gallery');
                }
            }

            DB::commit();

            return redirect()->route('admin.packages.index')
                ->with('success', 'Package créé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du package : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TourPackage $package)
    {
        $package->load(['category', 'inventory', 'reviews', 'bookings']);
        
        // Statistiques du package
        $stats = [
            'total_bookings' => $package->packageBookings()->count(),
            'confirmed_bookings' => $package->packageBookings()->where('status', 'confirmed')->count(),
            'total_revenue' => $package->packageBookings()->where('status', 'confirmed')->sum('final_price'),
            'average_rating' => $package->reviews()->avg('rating') ?? 0,
            'total_reviews' => $package->reviews()->count(),
        ];

        // Dates disponibles depuis l'inventaire
        $availableDates = PackageInventory::where('package_id', $package->id)
            ->where('is_available', true)
            ->where('available_date', '>=', now())
            ->orderBy('available_date')
            ->get();

        return view('admin.packages.show', compact('package', 'stats', 'availableDates'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TourPackage $package)
    {
        $categories = Category::where('is_active', true)->get();
        
        $packageTypes = [
            'helicopter' => 'Hélicoptère',
            'private_jet' => 'Jet Privé',
            'cruise' => 'Croisière',
            'safari' => 'Safari',
            'city_tour' => 'Visite de Ville',
            'adventure' => 'Aventure',
            'luxury' => 'Luxe'
        ];

        return view('admin.packages.edit', compact('package', 'categories', 'packageTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TourPackage $package)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title_fr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'package_type' => 'required|in:helicopter,private_jet,cruise,safari,city_tour,adventure,luxury',
            'destination' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'duration_text_fr' => 'nullable|string|max:100',
            'duration_text_en' => 'nullable|string|max:100',
            'departure_city' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'max_participants' => 'required|integer|min:1',
            'min_participants' => 'required|integer|min:1|lte:max_participants',
            'included_services_fr' => 'nullable|array',
            'included_services_en' => 'nullable|array',
            'excluded_services_fr' => 'nullable|array',
            'excluded_services_en' => 'nullable|array',
            'itinerary_fr' => 'nullable|array',
            'itinerary_en' => 'nullable|array',
            'video_url' => 'nullable|url',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title_fr' => 'nullable|string|max:255',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_description_fr' => 'nullable|string',
            'meta_description_en' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Mettre à jour le slug si le titre français change
            if ($package->title_fr !== $validated['title_fr']) {
                $validated['slug'] = Str::slug($validated['title_fr']);
                
                // S'assurer que le slug est unique
                $originalSlug = $validated['slug'];
                $count = 1;
                while (TourPackage::where('slug', $validated['slug'])->where('id', '!=', $package->id)->exists()) {
                    $validated['slug'] = $originalSlug . '-' . $count;
                    $count++;
                }
            }

            $package->update($validated);

            // Gestion de l'image principale
            if ($request->hasFile('avatar')) {
                $package->clearMediaCollection('avatar');
                $package->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatar');
            }

            // Gestion de la galerie
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $image) {
                    $package->addMedia($image)
                        ->toMediaCollection('gallery');
                }
            }

            DB::commit();

            return redirect()->route('admin.packages.index')
                ->with('success', 'Package mis à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du package : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TourPackage $package)
    {
        DB::beginTransaction();

        try {
            // Vérifier s'il y a des réservations confirmées
            $confirmedBookings = $package->packageBookings()
                ->whereIn('status', ['confirmed', 'pending'])
                ->count();

            if ($confirmedBookings > 0) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer ce package car il a des réservations actives.');
            }

            // Supprimer les médias
            $package->clearMediaCollection('avatar');
            $package->clearMediaCollection('gallery');
            $package->clearMediaCollection('documents');

            // Supprimer le package
            $package->delete();

            DB::commit();

            return redirect()->route('admin.packages.index')
                ->with('success', 'Package supprimé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du package : ' . $e->getMessage());
        }
    }

    /**
     * Toggle package active status
     */
    public function toggleStatus(TourPackage $package)
    {
        $package->update(['is_active' => !$package->is_active]);
        
        $status = $package->is_active ? 'activé' : 'désactivé';
        return redirect()->back()
            ->with('success', "Package {$status} avec succès !");
    }

    /**
     * Toggle package featured status
     */
    public function toggleFeatured(TourPackage $package)
    {
        $package->update(['is_featured' => !$package->is_featured]);
        
        $status = $package->is_featured ? 'mis en vedette' : 'retiré de la vedette';
        return redirect()->back()
            ->with('success', "Package {$status} avec succès !");
    }

    /**
     * Delete a specific gallery image
     */
    public function deleteGalleryImage(TourPackage $package, $mediaId)
    {
        try {
            $media = $package->getMedia('gallery')->where('id', $mediaId)->first();
            
            if ($media) {
                $media->delete();
                return response()->json(['success' => true, 'message' => 'Image supprimée avec succès']);
            }

            return response()->json(['success' => false, 'message' => 'Image non trouvée'], 404);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }   
    }
}