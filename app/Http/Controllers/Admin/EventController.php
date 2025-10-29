<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On charge la catégorie pour l'affichage dans la carte.
        $events = Event::with('category', 'type')->latest()->paginate(12);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = EventCategory::where('is_active', true)->pluck('name_fr', 'id');
        $types = EventType::where('is_active', true)->pluck('name_fr', 'id');
        $event = new Event(); // Crée une instance vide pour le formulaire
        $pageTitle = 'Créer un nouvel Événement';

        return view('admin.events.form', compact('event', 'categories', 'types', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateEvent($request);

        try {
            DB::beginTransaction();

            // Création de l'événement
            $event = Event::create($validatedData);

            // Gestion de l'image (Spatie Media Library)
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $event->addMediaFromRequest('image')
                      ->toMediaCollection('avatar');
            }

            DB::commit();

            return redirect()->route('admin.events.index')->with('success', 'L\'événement **' . $event->title_fr . '** a été créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la création de l\'événement : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('category', 'type'); // Charge les relations pour l'affichage
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $categories = EventCategory::where('is_active', true)->pluck('name_fr', 'id');
        $types = EventType::where('is_active', true)->pluck('name_fr', 'id');
        $pageTitle = 'Modifier l\'Événement : ' . $event->title_fr;

        return view('admin.events.form', compact('event', 'categories', 'types', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validatedData = $this->validateEvent($request, $event);

        try {
            DB::beginTransaction();

            // Mise à jour de l'événement
            $event->update($validatedData);

            // Gestion de l'image (Spatie Media Library)
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Supprime l'ancien avatar et ajoute le nouveau
                $event->clearMediaCollection('avatar');
                $event->addMediaFromRequest('image')
                      ->toMediaCollection('avatar');
            }

            // Gestion de la suppression de l'image
            if ($request->input('remove_image')) {
                $event->clearMediaCollection('avatar');
            }

            DB::commit();

            return redirect()->route('admin.events.index')->with('success', 'L\'événement **' . $event->title_fr . '** a été mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour de l\'événement : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $title = $event->title_fr;
        try {
            // Suppression des médias associés
            $event->clearMediaCollection('avatar');
            // Suppression de l'événement
            $event->delete();
            return redirect()->route('admin.events.index')->with('success', 'L\'événement **' . $title . '** a été supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de l\'événement : ' . $e->getMessage());
        }
    }

    /**
     * Valide les données de la requête pour la création ou la mise à jour d'un événement.
     */
    protected function validateEvent(Request $request, Event $event = null)
    {
        $rules = [
            'category_id' => ['required', 'exists:event_categories,id'],
            'type_id' => ['nullable', 'exists:event_types,id'], // Ajouté 'type_id' si vous utilisez EventType
            'title_fr' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'description_fr' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'venue_name' => ['required', 'string', 'max:255'],
            'venue_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'event_date' => ['required', 'date'],
            'event_time' => ['required', 'date_format:H:i'],
            'end_date' => ['nullable', 'date', 'after_or_equal:event_date'],
            'end_time' => ['nullable', 'date_format:H:i', 'required_with:end_date'],
            'image' => [($event ? 'nullable' : 'required'), 'image', 'max:2048'], // 2MB max
            'min_price' => ['required', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'gte:min_price'],
            'total_seats' => ['required', 'integer', 'min:1'],
            'organizer' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'meta_title_fr' => ['nullable', 'string', 'max:255'],
            'meta_title_en' => ['nullable', 'string', 'max:255'],
            'meta_description_fr' => ['nullable', 'string', 'max:500'],
            'meta_description_en' => ['nullable', 'string', 'max:500'],
        ];

        $validated = $request->validate($rules);

        // Ajout/Mise à jour du slug
        $validated['slug'] = Str::slug($validated['title_fr']);

        // Le champ 'available_seats' devrait être géré par un système d'inventaire. 
        // Pour l'instant, on le définit égal à total_seats à la création (si non défini)
        if (!$event) {
            $validated['available_seats'] = $validated['total_seats'];
        }

        // On s'assure que les booléens sont bien présents (même si la case n'est pas cochée)
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['event_type'] = $request->input('type_id'); // Mapping du champ type_id vers event_type dans le modèle
        
        return $validated;
    }
}
