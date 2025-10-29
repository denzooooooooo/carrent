<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carousel;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carousels = Carousel::orderBy('order_position', 'asc')->paginate(10);
        return view('admin.carousels.index', compact('carousels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $carousel = new Carousel();
        return view('admin.carousels.create', compact('carousel'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate($this->validationRules());

        // Créer l'entrée dans la base de données
        $carousel = Carousel::create($validatedData);

        // Gestion des Médias Spatie
        $this->handleMedia($request, $carousel);

        return redirect()->route('admin.carousels.index')->with('success', 'Le slide de carrousel a été créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carousel $carousel)
    {
        return view('admin.carousels.edit', compact('carousel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carousel $carousel)
    {
        $validatedData = $request->validate($this->validationRules(true));

        // Mettre à jour les données de la base de données
        $carousel->update($validatedData);

        // Gestion des Médias Spatie (met à jour ou supprime si demandé)
        $this->handleMedia($request, $carousel);

        return redirect()->route('admin.carousels.index')->with('success', 'Le slide de carrousel a été mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carousel $carousel)
    {
        try {
            // Spatie gère la suppression des fichiers médias automatiquement
            $carousel->delete();
            return redirect()->route('admin.carousels.index')->with('success', 'Le slide de carrousel a été supprimé.');
        } catch (\Exception $e) {
            return redirect()->route('admin.carousels.index')->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Règles de validation communes.
     */
    protected function validationRules($isUpdate = false)
    {
        return [
            'title_fr' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'subtitle_fr' => 'nullable|string|max:500',
            'subtitle_en' => 'nullable|string|max:500',
            'video_url' => 'nullable|url|max:255',
            'link_url' => 'nullable|url|max:255',
            'button_text_fr' => 'nullable|string|max:100',
            'button_text_en' => 'nullable|string|max:100',
            'order_position' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',

            // Gestion des fichiers Spatie
            'image_desktop' => $isUpdate ? 'nullable|image|max:5120' : 'required|image|max:5120', // 5MB
            'image_mobile' => 'nullable|image|max:5120',
        ];
    }

    /**
     * Gère l'ajout et le remplacement des médias (Spatie).
     */
    protected function handleMedia(Request $request, Carousel $carousel)
    {
        // Image Desktop
        if ($request->hasFile('image_desktop')) {
            $carousel->clearMediaCollection('image_desktop');
            $carousel->addMediaFromRequest('image_desktop')
                ->toMediaCollection('image_desktop');
        }

        // Image Mobile
        if ($request->hasFile('image_mobile')) {
            $carousel->clearMediaCollection('image_mobile');
            $carousel->addMediaFromRequest('image_mobile')
                ->toMediaCollection('image_mobile');
        }
    }
}
