<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Afficher la liste des événements avec filtres.
     */
    public function index(Request $request)
    {
        $query = Event::where('is_active', true)
            ->with(['category', 'type']);

        // Filtre par catégorie/type d'événement
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtre par lieu/venue
        if ($request->filled('venue')) {
            $query->where('venue_name', 'like', '%' . $request->venue . '%');
        }

        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('event_date', $request->date);
        }

        $events = $query->orderBy('event_date', 'asc')
            ->paginate(12);

        // Récupérer les catégories pour les filtres
        $categories = \App\Models\EventCategory::where('is_active', true)->get();

        return view('pages.events', compact('events', 'categories'));
    }

    /**
     * Afficher les détails d'un événement avec les zones de sièges disponibles.
     */
    public function show($slug)
    {
        $event = Event::with(['seatZones' => function($query) {
            $query->where('is_active', true)->orderBy('price');
        }, 'category', 'type'])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        return view('pages.event-details', compact('event'));
    }
}
