<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Afficher la liste des packages avec filtres.
     */
    public function index(Request $request)
    {
        $query = TourPackage::where('is_active', true)
            ->with(['category']);

        // Filtre par type de package
        if ($request->filled('type')) {
            $query->where('package_type', $request->type);
        }

        // Filtre par destination
        if ($request->filled('destination')) {
            $query->where('destination', 'like', '%' . $request->destination . '%');
        }

        // Filtre par durée
        if ($request->filled('duration')) {
            switch ($request->duration) {
                case '1-3':
                    $query->whereBetween('duration', [1, 3]);
                    break;
                case '4-7':
                    $query->whereBetween('duration', [4, 7]);
                    break;
                case '1-2-weeks':
                    $query->whereBetween('duration', [7, 14]);
                    break;
                case 'more-than-2-weeks':
                    $query->where('duration', '>', 14);
                    break;
            }
        }

        $packages = $query->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Récupérer les types de packages distincts pour les filtres
        $packageTypes = TourPackage::where('is_active', true)
            ->distinct()
            ->pluck('package_type')
            ->filter()
            ->values();

        // Récupérer les destinations distinctes
        $destinations = TourPackage::where('is_active', true)
            ->distinct()
            ->pluck('destination')
            ->filter()
            ->values();

        return view('pages.packages', compact('packages', 'packageTypes', 'destinations'));
    }

    /**
     * Display the specified package.
     */
    public function show($slug)
    {
        $package = TourPackage::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'reviews'])
            ->firstOrFail();

        // Get similar packages from same category
        $similarPackages = TourPackage::where('category_id', $package->category_id)
            ->where('id', '!=', $package->id)
            ->where('is_active', true)
            ->limit(3)
            ->get();

        return view('pages.package-details', compact('package', 'similarPackages'));
    }
}
